<?php

namespace App\Http\Controllers;

class OrderController extends Controller
{
    public function index()
    {
        $data = [
            'page_title'   => 'Order Lists',
            'active'       => 'order',
            'order_status' => [
                (object) [
                    'id'    => 1,
                    'title' => 'Payment Pending',
                ],
            ],
        ];

        return view('order.list', $data);
    }
}
