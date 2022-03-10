<?php
use Core\Vendor\Pingpong\Themes\ThemeFacade as Theme;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Str;
use Core\Models\Banner\Banner;
use Core\Models\User\UserAddress;
use Core\Library\Product\FacadesCategory;
use Core\Models\Product\Brand;
use Core\Models\Page\Page;
use Core\Repositories\Facades\CategoryRepository;
use Core\Repositories\Facades\ProductRepository;
use Core\Models\Product\Category;
use Core\Models\Setting\Currency;
use Illuminate\Support\Facades\Storage;
use Core\Models\Setting\Store;
use Core\Models\Setting\AdminLog;

function array_by_key($array, $key) {
    $return = array();
    foreach($array as $val) {
      $return[] = $val[$key];
    }
    return $return;
}

function thumbnail_size() {
  return current(array_keys(theme_config('image.product')));
}

function categories() {
  return Cache::tags(['store'.session('store')->id.app_key(), 'categories'.session('store')->id.app_key()])->rememberForever('categorybranch'.session('store')->id.app_key(), function() {
    return  CategoryRepository::getCategoryBranch();
  });
}

function featured_categories() {
  return Cache::tags(['store'.session('store')->id.app_key(), 'categories'.session('store')->id.app_key()])->rememberForever('featuredcategory'.session('store')->id.app_key(), function() {
    return  Category::where('is_active', 1)->where('is_featured', 1)->where('store_id', session('store')->id)->get();
  });
}

function array_to_query($array) {
  $string = '';
  $i=0;
  foreach($array as $key => $arr) {
    if($i>0) {
      $string .= '&';
    }
    if(is_array($arr)) {
      foreach($arr as $s) {
        $string .= $key."[]=".$s;
      }
    }else {
      $string .= $key."=".$arr;
    }
    $i++;
  }
  return $string;
}

function cache_key($name,$array) {
  $output = implode('', array_map(
    function ($v, $k) {
        if(is_array($v)){
            return $k.'[]'.implode('&'.$k.'[]', $v);
        }else{
            return $k.''.$v;
        }
    },
    $array,
    array_keys($array)
  ));
  $store_id = (session('store')) ? session('store')->id : 1;
  return str_replace("-", "_", $name.$output.'lang_'.session('language').session('currency').$store_id.app_key());
}

function get_banner($type, $limit=0) {
  $agent = new Agent();

  $banners = Banner::active()->where('store_id', session('store')->id)->where('type',$type);

  if($limit) {
    $banners = $banners->take($limit);
  }

  $banners = $banners->orderBy('order_weight','asc')->select('id','title','url')->get();

  foreach($banners as $banner) {
    if($agent->isMobile()) {
      $banner->image = $banner->image()->mobile_image;
      $banner->agent = 'mobile';
    }else {
      $banner->image = $banner->image()->desktop_image;
      $banner->agent = 'desktop';
    }

  }
  return $banners;
}


function interval_day($CheckIn,$CheckOut){
  $CheckInX = explode("-", $CheckIn);
  $CheckOutX =  explode("-", $CheckOut);
  $date1 =  mktime(0, 0, 0, $CheckInX[1],$CheckInX[2],$CheckInX[0]);
  $date2 =  mktime(0, 0, 0, $CheckOutX[1],$CheckOutX[2],$CheckOutX[0]);
  $interval =($date2 - $date1)/(3600*24);

  return  $interval ;
}

function check_permission($actionName){
  if(session('admin')){
    foreach(session('admin')->role->permissions as $permission) {
      if($permission->action_name == $actionName) {
        return true;
      }
    }
  }
  return false;
}

function generate_image($sizes,$file)
{
  $extension = $file->getClientOriginalExtension();
  $filename =  sha1(Str::random(32)).".".$extension;
  $images = array();

  foreach($sizes as $key => $size)
  {
    $path = storage_path('app/b2b2c/').$key;
    if(!file_exists($path)){
      mkdir($path, 0755, true);
    }
    $filetarget = $path.'/'.$filename;
    if($size['w']==0 && $size['h']==0) {
      $file->storeAs('/b2b2c/'.$key, $filename);
    }else {
      $img = Image::make($file)->fit($size['w'],$size['h'])->save($filetarget);
      if(env('FILESYSTEM_DRIVER') == 's3') {
        Storage::putFileAs('/b2b2c/'.$key, $filetarget, $filename);
        unlink($filetarget);
      }
    }

    $images[$key] = image_url($key, $filename);
  }

  return array('filename' => $filename, 'images' => $images);
}

function slugify($title) {
  return preg_replace('/-$/', '', preg_replace('/^-/', '', preg_replace('/\-{2,}/', '-', preg_replace('/([^a-z0-9]+)/', '-',strtolower($title)))));
}

function slugUniqify($slug_candidate, $slug_possible_conflicts = array()) {
  if(count($slug_possible_conflicts)) {
    $new_slug = $slug_candidate.'-'.count($slug_possible_conflicts);
    $i=1;
    while (in_array($new_slug, $slug_possible_conflicts)) {
      $new_slug = $slug_candidate.'-'.(count($slug_possible_conflicts)+$i);
      $i++;
    }
    $slug_candidate = $new_slug;
  }
  return $slug_candidate;
}

function image_url($size,$image)
{
  if(env('FILESYSTEM_DRIVER') == 's3') {
    if(!Storage::exists('b2b2c/'.$size.'/'.$image) || !$image) {
      return asset('backend/b2b2c/default-img.png');
    }else {
      return Storage::url('b2b2c/'.$size.'/'.$image);
    }
  }else {
    $file = array('url' => asset('b2b2c/'.$size.'/'.$image), 'path' => storage_path('app/b2b2c/').$size."/".$image);
    if(!file_exists($file['path']) || empty($image))
    {
      $file['url'] = asset('images/default-img.png');
    }
    return $file['url'];
  }
}

function generate_key($value) {
  return str_replace(' ', '', strtolower($value));
}

function generate_random_string($length = 10, $prefix = '') {
    $rand = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    if($prefix != ''){
        return $prefix.$rand;
    } else {
        return $rand;
    }
}

function get_created_string ( $time )
{
  $now = time() ;

  $x = $time;
  /* no 7h differences anymore */
  //$x = $x - (7 * 3600);

  $sec = $now - $x ;

  if ($sec < 0) $sec = 0;

  $min = floor($sec / 60) ;
  if ( $min <= 1 ) return "1 minute ago" ;
  else if ( $min < 60 ) return "$min minutes ago" ;

  $hour = floor($min / 60) ;
  if ( $hour <= 1 ) return "1 hour ago" ;
  else if ( $hour < 24 ) return "$hour hours ago" ;

  $day = floor($hour / 24) ;

  if ( $day < 7 )
  return date ( 'l', $x ) . ' at '. date ( 'H:i', $x ) ;
  else if ( $day < 365 )
  return date ( 'F j', $x ) . ' at ' . date ( 'H:i', $x ) ;

  return date ( 'F j, Y', $x ) . ' at ' . date ( 'H:i', $x ) ;
}

function category_tree($elements, $parentId = 0) {
  $branch = array();
  foreach ($elements as $element) {
    if ($element->parent_id == $parentId) {
      $element->children = array();
      $children = category_tree($elements, $element->id);
      if ($children) {
          $element->children = $children;
      }
      $branch[] = $element;
    }
  }
  return $branch;
}

function site_url($domain) {
  if(env('FORCE_HTTPS')) {
    return 'https://'.$domain;
  }else {
    return 'http://'.$domain;
  }
}

function generate_orderid($id) {
  return str_pad($id, 6, "0", STR_PAD_LEFT);
}

function app_key() {
  return str_replace(" ", "", strtolower(env('APP_NAME').env('APP_ENV')));
}

function insert_admin_Log($message) {
  if(session('admin')) {
    AdminLog::create(array(
      'admin_id' => session('admin')->id,
      'log' => $message
    ));
  }
}

function get_initials($name) {
  $words = explode(" ", $name);
  $initials = null;
  foreach ($words as $i => $w) {
    if($i==0) {
      $initials .= ucfirst($w[0]);
      if(count($words)<2) {
        $initials .= ucfirst($w[1]);
      }
    }else if($i==1) {
      $initials .= ucfirst($w[0]);
    }
  }

  return $initials;
}
