<?php

namespace App\GraphQL\Mutations;

use App\Models\Wishlist;
use GraphQL\Type\Definition\ResolveInfo;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Ramsey\Uuid\Uuid;
use Joselfonseca\LighthouseGraphQLPassport\GraphQL\Mutations\BaseAuthResolver;
use Validator, Image;
use App\Models\Business\Business;
use App\Models\Business\BusinessCategory;
use App\Models\Business\BusinessImage;
use App\Models\Business\BusinessArea;
use App\Models\Business\BusinessLink;
use App\Models\Business\BusinessCoupon;
use App\Models\Business\BusinessInstagram;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Facades\Instagram;
use App\Models\CouponClick;
use App\Models\ContactClick;
use App\Models\ButtonClick;

/**
 * Class UpdateUser.
 */
class BusinessMutation extends BaseAuthResolver
{
    /**
     * @param $rootValue
     * @param array               $args
     * @param GraphQLContext|null $context
     * @param ResolveInfo         $resolveInfo
     *
     * @return array
     */

    public function basic($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $slug = slugify($args['name']);
        $test = Business::where('slug', $slug)->first();
        if($test) {
          return array(
            'status' => 'error',
            'message' => "Nama bisnis telah digunakan. Silahkan menggunakan nama lain."
          );
        }

        $logoname = "";
        $logo = $args['logo'];
        if($logo) {
            $validator = Validator::make($args, array(
                'logo' => 'image|mimes:png,jpeg,jpg,gif'
            ));

            if ($validator->fails()) {
              return array(
                'status' => 'error',
                'message' => $validator->errors()->first()
              );
            }
            $extension = $logo->getClientOriginalExtension();
            $logoname =  sha1(Str::random(32)).".".$extension;

            $path = storage_path('app/public/logo');
            if(!file_exists($path)){
              mkdir($path, 0755, true);
            }
            $logotarget = $path.'/'.$logoname;
            Image::make($logo)->fit(500,500)->save($logotarget);
        }

        $business = Business::create(array(
            'slug' => $slug,
            'user_id' => $context->user()->id,
            'name' => $args['name'],
            'email' => $args['email'],
            'address' => $args['address'],
            'location' => $args['location'],
            'description' => $args['description'],
            'logo' => $logoname,
            'phone' => $args['phone'],
            'whatsapp' => $args['whatsapp'],
            'website' => $args['website'],
            'instagram' => $args['instagram'],
            'youtube' => $args['youtube'],
            'is_treasure_arise' => $args['is_treasure_arise']
        ));

        foreach(explode(",", $args['category']) as $category) {
            BusinessCategory::create(array(
                'business_id' => $business->id,
                'category_id' => $category
            ));
        }

        foreach($args['areas'] as $area) {
            BusinessArea::create(array(
                'business_id' => $business->id,
                'area' => $area
            ));
        }

        if ($args['newsletter']) {
            $this->mailchimp($args['email']);
        }

        return array(
            'status' => 'success',
            'message' => 'Success create business'
        );
    }

    private function mailchimp($email)
    {
        $auth = base64_encode('user:'.config('services.mailchimp.apikey'));
        $data = [
            'apikey'        => config('services.mailchimp.apikey'),
            'email_address' => $email,
            'status'        => 'subscribed',
        ];

        $json_data = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,
            'https://'.config('services.mailchimp.server').'.api.mailchimp.com/3.0/lists/'.config('services.mailchimp.listid').'/members');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Basic '.$auth
        ]);
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        $result = json_decode(curl_exec($ch));

        if ($result != null) {
            $result->status = (string) $result->status;

            if ($result->status == 'subscribed') {
                return ['status' => 'success', 'message' => $email.' success to subscribe'];
            } else {
                return ['status' => 'error', 'message' => $email.' is already a list member.'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Invalid request, please try again'];
        }
    }

    public function updateBasic($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $slug = slugify($args['name']);
        $test = Business::where('slug', $slug)->where('user_id','!=',$context->user()->id)->first();
        if($test) {
          return array(
            'status' => 'error',
            'message' => "Nama bisnis telah digunakan. Silahkan menggunakan nama lain."
          );
        }

        $logoname = "";
        if(isset($args['logo'])) {
            $logo = $args['logo'];
            $validator = Validator::make($args, array(
                'logo' => 'image|mimes:png,jpeg,jpg,gif'
            ));

            if ($validator->fails()) {
              return array(
                'status' => 'error',
                'message' => $validator->errors()->first()
              );
            }
            $extension = $logo->getClientOriginalExtension();
            $logoname =  sha1(Str::random(32)).".".$extension;

            $path = storage_path('app/public/logo');
            if(!file_exists($path)){
              mkdir($path, 0755, true);
            }
            $logotarget = $path.'/'.$logoname;
            Image::make($logo)->fit(500,500)->save($logotarget);
        }

        $business = Business::where('slug' , $args['slug'])->where('user_id', $context->user()->id)->first();
        $business->slug = $slug;
        $business->name = $args['name'];
        $business->email = $args['email'];
        $business->address = $args['address'];
        $business->location = $args['location'];
        $business->description = $args['description'];
        if($logoname) {
            $business->logo = $logoname;
        }
        $business->phone = $args['phone'];
        $business->whatsapp = $args['whatsapp'];
        $business->website = $args['website'];
        $business->instagram = $args['instagram'];
        $business->youtube = $args['youtube'];
        if($args['is_treasure_arise'] && !$business->is_treasure_arise) {
            $business->is_treasure_arise = $args['is_treasure_arise'];
            $business->treasure_arise_status = 'pending';
        }
        // $business->status = 'pending';
        $business->save();

        BusinessCategory::where('business_id', $business->id)->delete();
        foreach(explode(",", $args['category']) as $category) {
            BusinessCategory::create(array(
                'business_id' => $business->id,
                'category_id' => $category
            ));
        }

        BusinessArea::where('business_id', $business->id)->delete();
        foreach($args['areas'] as $area) {
            BusinessArea::create(array(
                'business_id' => $business->id,
                'area' => $area
            ));
        }

        return array(
            'status' => 'success',
            'message' => 'Success update business'
        );
    }

    public function uploadImage($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $imagename = "";
        $image = $args['image'];
        $validator = Validator::make($args, array(
            'image' => 'image|mimes:png,jpeg,jpg,gif'
        ));

        if ($validator->fails()) {
          return array(
            'status' => 'error',
            'message' => $validator->errors()->first()
          );
        }
        $extension = $image->clientExtension();
        $imagename =  sha1(Str::random(32)).".".$extension;

        $path = storage_path('app/public/image');
        if(!file_exists($path)){
          mkdir($path, 0755, true);
        }
        $imagetarget = $path.'/'.$imagename;
        Image::make($image)->fit(500,500)->save($imagetarget);
        return array(
            'status' => 'success',
            'message' => 'Upload success',
            'filename' => $imagename,
            'url' => image_url('image', $imagename),
        );
    }

    public function updateComplete($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $business = Business::where('slug' , $args['slug'])->where('user_id', $context->user()->id)->first();
        $business->company_type = $args['company_type'];
        $business->company_size = $args['company_size'];
        $business->ownership = $args['ownership'];
        $business->establish_since = $args['establish_since'];
        $business->tags = implode(",",$args['tags']);
        // $business->status = 'pending';
        $business->type = 'complete';
        $business->save();

        BusinessImage::where('business_id', $business->id)->delete();
        foreach($args['images'] as $image) {
            BusinessImage::create(array(
                'business_id' => $business->id,
                'image' => $image['filename']
            ));
        }

        BusinessLink::where('business_id', $business->id)->delete();
        foreach($args['links'] as $link) {
            BusinessLink::create(array(
                'business_id' => $business->id,
                'title' => $link['title'],
                'url' => $link['url'],
            ));
        }

        return array(
            'status' => 'success',
            'message' => 'Success update business'
        );
    }

    public function createCoupon($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $business = Business::where('slug' , $args['slug'])->where('user_id', $context->user()->id)->first();

        BusinessCoupon::create(array(
            'business_id' => $business->id,
            'code' => $args['code'],
            'description' => $args['description'],
            'how_to_use' => $args['how_to_use']
        ));

        return array(
            'status' => 'success',
            'message' => 'Success create coupons'
        );
    }

    public function deleteCoupon($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $business = BusinessCoupon::join('business','business.id','=','business_coupons.business_id')->where('business_coupons.id' , $args['id'])->where('user_id', $context->user()->id)->delete();

        return array(
            'status' => 'success',
            'message' => 'Success create coupons'
        );
    }

    public function publishCoupon($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $business = BusinessCoupon::join('business','business.id','=','business_coupons.business_id')->where('business_coupons.id' , $args['id'])->where('user_id', $context->user()->id)->update(array(
            'is_publish' => $args['is_publish']
        ));

        return array(
            'status' => 'success',
            'message' => 'Success create coupons'
        );
    }

    public function connectInstagram($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $business = Business::where('slug' , $args['slug'])->where('user_id', $context->user()->id)->first();
        if($business) {
            $resp = Instagram::getToken(str_replace("#_","", $args['code']));

            if($resp['status']=="success") {
                BusinessInstagram::create(array(
                    'business_id' => $business->id,
                    'ig_user_id' => $resp['user']['id'],
                    'username' => $resp['user']['username'],
                    'access_token' => $resp['user']['access_token'],
                    'expired_at' => date("Y-m-d", strtotime(date("Y-m-d"))+$resp['user']['expires_in'])
                ));
            }
            return $resp;
        }

        return array(
            'status' => 'error',
            'message' => 'Business not found'
        );
    }

    public function disconnectInstagram($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $business = Business::where('slug', $args['slug'])
                            ->where('user_id', $context->user()->id)
                            ->first();
        if ($business) {
            $disconnect = $business->connectInstagram()->delete();
        }

        if (!$disconnect) {
            return [
                'status'  => 'error',
                'message' => 'Failed to disconnect Instagram',
            ];
        }

        return [
            'status'  => 'success',
            'message' => 'Success disconnect Instagram',
        ];
    }

    public function toggleWishlist($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $userId = $context->user()->id;
        $business = Business::where('slug', $args['slug'])->first();

        $wishlist = Wishlist::where([
            'user_id'     => $userId,
            'business_id' => $business->id,
        ])->first();


        if ($wishlist) {
            $wishlist->delete();

            return [
                'status'  => 'success',
                'message' => 'Success removed from favourites.'
            ];
        }

        Wishlist::create([
            'user_id'     => $userId,
            'business_id' => $business->id,
        ]);

        return [
            'status'  => 'success',
            'message' => 'Success add to favourites.'
        ];
    }

    public function couponClick($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo) {
        $bearerToken = $context->request->bearerToken();
        $parsedJwt = (new \Lcobucci\JWT\Parser())->parse($bearerToken);
        if ($parsedJwt->hasHeader('jti')) {
          $tokenId = $parsedJwt->getHeader('jti');
        } elseif ($parsedJwt->hasClaim('jti')) {
          $tokenId = $parsedJwt->getClaim('jti');
        }

        \DB::beginTransaction();

        CouponClick::firstOrCreate(array(
          'business_id' => $args['business_id'],
          'token' => $tokenId,
          'date' => date("Y-m-d")
        ));
        $coupon = BusinessCoupon::find($args['coupon_id']);
        $coupon->increment('copied');

        \DB::commit();

        return [
            'status'  => 'success',
            'message' => 'Success add to favourites.'
        ];
    }

    public function contactClick($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo) {
        $bearerToken = $context->request->bearerToken();
        $parsedJwt = (new \Lcobucci\JWT\Parser())->parse($bearerToken);
        if ($parsedJwt->hasHeader('jti')) {
          $tokenId = $parsedJwt->getHeader('jti');
        } elseif ($parsedJwt->hasClaim('jti')) {
          $tokenId = $parsedJwt->getClaim('jti');
        }

        ContactClick::firstOrCreate(array(
          'business_id' => $args['business_id'],
          'token' => $tokenId,
          'date' => date("Y-m-d")
        ));

        return [
            'status'  => 'success',
            'message' => 'Success add to favourites.'
        ];
    }

    public function buttonClick($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo) {
        $bearerToken = $context->request->bearerToken();
        $parsedJwt = (new \Lcobucci\JWT\Parser())->parse($bearerToken);
        if ($parsedJwt->hasHeader('jti')) {
          $tokenId = $parsedJwt->getHeader('jti');
        } elseif ($parsedJwt->hasClaim('jti')) {
          $tokenId = $parsedJwt->getClaim('jti');
        }

        ButtonClick::firstOrCreate(array(
          'business_id' => $args['business_id'],
          'token' => $tokenId,
          'date' => date("Y-m-d")
        ));

        return [
            'status'  => 'success',
            'message' => 'Success save data.'
        ];
    }

    public function deleteBusiness($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $business = Business::slug($args['slug'])->first();
        $business->delete();

        return [
            'status'  => 'success',
            'message' => 'Success delete data.'
        ];
    }
}
