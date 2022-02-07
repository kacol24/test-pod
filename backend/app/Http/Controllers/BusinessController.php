<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Resources\Business as BusinessResource;
use App\Mail\ApproveRejectEmail;
use App\Mail\BusinessStatistics;
use App\Models\Business\Business;
use App\Models\Business\BusinessCategory;
use App\Models\Business\Category;
use Cache;
use DB;
use Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Lang;
use Theme;
use Validator;
use View;

class BusinessController extends Controller {
  public function index() {
    $data = array(
      'categories' => Category::all(),
      'page_title' => 'Business Lists',
      'active' => 'business'
    );

    return view('business/list', $data);
  }

  public function datatable(Request $request) {
    $search = $request->search;
    $status = $request->status;
    $sorting = 'updated_at';
    $order = $request->order;

    if($request->has('sort')) {
      $sorting = $request->sort;
    }

    return BusinessResource::collection(Business::when(! empty($search), function ($query) use ($search) {
      return $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
    })->when(! empty($status), function ($query) use ($status) {
      return $query->where('status', $status);
    })->orderBy($sorting, $order)->paginate($request->limit));
  }

    public function edit($id)
    {
        $business = Business::where('id', $id)->first();
        $categories = Category::where('is_active', 1)->orderBy('order_weight', 'asc')
                              ->get();
        $selected_category = [];
        foreach ($business->categories as $category) {
            $selected_category[] = $category['category_id'];
        }

        $data = [
            'entity'            => $business,
            'page_title'        => $business->name,
            'active'            => 'business',
            'categories'        => category_tree($categories),
            'selected_category' => $selected_category,
        ];

        return view('business/edit', $data);
    }

    public function update(Request $request, $id) {
    $request->flash();

    $input = $request->all();
    \DB::beginTransaction();
    try {
      $business = Business::find($id);

        if (isset($input['category_id']) && is_array($input['category_id'])) {
            BusinessCategory::where('business_id', $id)->delete();
            foreach ($input['category_id'] as $category_id) {
                BusinessCategory::create(['business_id' => $id, 'category_id' => $category_id]);
            }
        }

        if(isset($input['is_publish'])) {
        $business->is_publish = $input['is_publish'];
      }
      $business->updated_at = date('Y-m-d H:i:s');

      if ($request->status == Business::STATUS_APPROVED) {
          $business->status = Business::STATUS_APPROVED;
          $business->approved_at = now();
          $subject = 'Selamat! Bisnis Anda Sudah Diterima - JPCC Marketplace';
      }

      if ($request->status == Business::STATUS_REJECTED) {
          $business->status = Business::STATUS_REJECTED;
          $subject = 'Maaf! Pengajuan Bisnis Anda Ditolak - JPCC Marketplace';
      }

      $business->save();

      if ($request->status) {
          Mail::to($business->email)
              ->queue(new ApproveRejectEmail($business, $request->reason));
      }

      // send email

      \DB::commit();
    }catch(\Exception $e){
      \DB::rollback();
      throw new \Exception($e->getMessage());
    }

    \Cache::forget('businesss');
    return redirect()->route('business.list')->with('status', 'Success edit business');
  }

  public function upload(Request $request) {
    $data = array();
    foreach($request->file('file') as $file) {
      $validator = Validator::make(array('file' => $file), [
        'file' => 'image|mimes:png,jpeg,jpg,gif'
      ]);

      if ($validator->fails()) {
        return ['status' => 'error','message' => 'Invalid image format'];
      }

      $sizes = config('image.business');
      if(theme_config('image.business')) {
        $sizes = array_merge($sizes, theme_config('image.business'));
      }

      $data[] = generate_image($sizes,$file);
    }

    return ['status' => 'success', 'data' => $data];
  }

  public function getOption(Request $request) {
    #Review
    $return = '';
    $option_set = OptionSet::find($request->set_id);
    $options = Option::whereIn('id',json_decode($option_set->value,true))->get();
    if($options) {
      foreach($options as $option) {
        $return .= '
          <div class="form-group">
          <label class="text-uppercase">'.$option->title(session('language')).'</label>
          <input type="checkbox" name="options[]" style="display:none;" id="'.generate_key($option->title(session('store')->default_language)).'" title="'.$option->title(session('language')).'" title-id="'.$option->title('id').'" title-en="'.$option->title('en').'"/>
          <ul class="list-unstyled d-flex">';
        foreach($option->details as $detail) {
          $return .= '<li><label><input class="option-detail" type="checkbox" name="'.$detail->title('id').'" parent="'.generate_key($option->title(session('store')->default_language)).'" value="'.$detail->title(session('language')).'" title="'.$detail->title(session('language')).'" title-id="'.$detail->title('id').'" title-en="'.$detail->title('en').'" key="'.generate_key($option->title(session('store')->default_language).$detail->title(session('store')->default_language)).'" image="'.$detail->image.'">&nbsp;&nbsp;'.$detail->title(session('language')).'</label></li>';
        }

        $return .= '</ul></div>';
      }
    }
    return ['status' => 'success', 'value' => $return];
  }

  public function getAttribute(Request $request) {
    $return = '';
    $attribute = Attribute::find($request->set_id);


    if($attribute) {
      foreach($attribute->detail as $detail) {
        foreach ($detail->content as $key => $content) {
          $detail[$content['keyword'].'_'.$content['language']] = $content['value'];
        }
      }
      foreach($attribute->detail as $detail) {
        $return .= '<div class="form-group append" '.xCloak('id').' x-show="language == \'id\'">
                  <label for="platform">'.$detail->title_id.'</label>
                  <input type="text" class="form-control" name="attribute'.$detail->id.'id" id="platform">
                </div>';

        $return .= '<div class="form-group append" '.xCloak('en').' x-show="language == \'en\'">
                  <label for="platform">'.$detail->title_en.'</label>
                  <input type="text" class="form-control" name="attribute'.$detail->id.'en" id="platform">
                </div>';
      }
    }else {
      $attribute = array('detail' => array());
    }
    return ['status' => 'success', 'value' => $return, 'attribute' => $attribute];
  }

  public function deleteBusiness($id) {
    Business::where('id',$id)->delete();
    BusinessSku::where('business_id',$id)->delete();
    Cache::tags('businesss'.session('store')->id.app_key())->flush();
    return redirect()->route('business.list')->with('status', 'Success delete business');
  }

  public function bulkDelete(Request $request) {
    if(is_array($request->input('ids'))) {
      Business::whereIn('id', $request->input('ids'))->delete();
      BusinessSku::whereIn('business_id', $request->input('ids'))->delete();
    }
    else {
      Business::whereIn('id', json_decode($request->input('ids'),true))->delete();
      BusinessSku::whereIn('business_id', json_decode($request->input('ids'),true))->delete();
    }

    Cache::tags('businesss'.session('store')->id.app_key())->flush();
    if($request->input('back_url'))
      return redirect($request->input('back_url'));
  }

  public function status(Request $request, $id) {
    if($request->input('status') == 'disable') {
      Business::where('id', $id)->where('store_id', session('store')->id)->update(array('is_publish' => 0));
    }else {
      Business::where('id', $id)->where('store_id', session('store')->id)->update(array('is_publish' => 1));
    }
  }

    public function sendStatistics(Request $request)
    {
        $businesses = [];
        if ($request->id) {
            $businesses[] = Business::find($request->id);
        } else {
            $businesses = Business::approved()->get();
        }

        $startDate = today()->subDay()->subMonths(3);
        $endDate = today()->subDay();

        foreach ($businesses as $business) {
            Mail::to($business->email)
                ->queue(new BusinessStatistics($business, $startDate, $endDate));
        }

        return redirect()->route('business.list')->with('status', 'Berhasil mengirim email statistik');
    }
}
