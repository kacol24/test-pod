<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Core\Models\User\UserAddress;
use Core\Models\User\UserPointLog;
use Core\Repositories\Facades\Shipping;
use Core\Models\Order\Order;
/**
 * Class Brand.
 */
class UserQuery
{
    /**
     * @param $rootValue
     * @param array               $args
     * @param GraphQLContext|null $context
     * @param ResolveInfo         $resolveInfo
     *
     * @return array
     */
    public function addresses($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo) {
        return UserAddress::where('user_id', $context->user->id)->orderBy('is_primary','desc')->select('user_address.*')->get();
    }

    public function address($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo) {
        return UserAddress::where('user_id', $context->user->id)->where('id', $args['id'])->orderBy('is_primary','desc')->select('user_address.*')->first();
    }

    public function searchCitySubdistrict($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo) {
        return Shipping::getSubdistrictOrCity($args['s']);
    }

    public function mypoints($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo) {
        $limit = 10;
        $page = 1;
        if(isset($args['limit'])) {
            $limit = $args['limit'];
        }
        if(isset($args['page'])) {
            $page = $args['page'];
        }
        $points = UserPointLog::where('user_id', $context->user->id)->orderBy('id','desc')->paginate($limit, ['*'], 'Points', $page);
        $data['datas'] = $points;
        $pages = array(
            'count' => $points->count(),
            'current_page' => $points->currentPage(),
            'total_data' => $points->total(),
            'last_page' => $points->lastPage()
        );
        $data['pagination'] = $pages;
        return $data;
    }

    public function myorders($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo) {
        $limit = 10;
        $page = 1;
        if(isset($args['limit'])) {
            $limit = $args['limit'];
        }
        if(isset($args['page'])) {
            $page = $args['page'];
        }

        $orders = Order::where('customer_id', $context->user->id)->orderBy('created_at','desc')->paginate($limit, ['*'], 'Orders', $page);
        foreach($orders as $order) {
            $this->defineOrderContent($order, $args);
        }
        $data['datas'] = $orders;
        $pages = array(
            'count' => $orders->count(),
            'current_page' => $orders->currentPage(),
            'total_data' => $orders->total(),
            'last_page' => $orders->lastPage()
        );
        $data['pagination'] = $pages;
        return $data;
    }

    public function orderDetail($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo) {
        $order = Order::where('id', $args['id'])->where('customer_id', $context->user->id)->first();
        $order = $this->defineOrderContent($order, $args);
        return $order;
    }

    public function defineOrderContent($order, $input) {
        $order->order_status = array(
            'id' => $order->status_id,
            'title' => $order->status->title($input['language'])
        );

        foreach($order->details as $detail) {
            $detail->title = $detail->title($input['language']);
            $detail->image = image_url('productthumbnail', $detail->image);
            if($detail->option1($input['language'])) {
                $option_1 = json_decode($detail->option1($input['language'])->value,true);
                $detail->option_1 = array(
                    'title' => $option_1['option']['title'],
                    'value' => $option_1['option_detail']['title']
                ); 
            }
            if($detail->option2($input['language'])) {
                $option_2 = json_decode($detail->option2($input['language'])->value,true);
                $detail->option_2 = array(
                    'title' => $option_2['option']['title'],
                    'value' => $option_2['option_detail']['title']
                ); 
            }
            
        }

        return $order;
    }
}
