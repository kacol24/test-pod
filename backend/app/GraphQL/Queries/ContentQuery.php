<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Models\Banner;
/**
 * Class Blog.
 */
class ContentQuery
{
    /**
     * @param $rootValue
     * @param array               $args
     * @param GraphQLContext|null $context
     * @param ResolveInfo         $resolveInfo
     *
     * @return array
     */
    public function banners($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo) {
        $banners = Banner::active()->where('type',$args['type']);

        if(isset($args['limit'])) {
            $banners = $banners->take($args['limit']);  
        }

        $banners = $banners->orderBy('order_weight','asc')->get();

        foreach($banners as $banner) {
            $banner->desktop = image_url('desktop',$banner->desktop_image);
            $banner->mobile = image_url('mobile',$banner->mobile_image);
        }
        return $banners;
    }
}
