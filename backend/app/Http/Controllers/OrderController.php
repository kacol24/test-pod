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

    public function edit($id)
    {
        $data = [
            'page_title' => 'Edit Order',
            'active'     => 'order',
        ];

        return view('order.edit', $data);
    }
}
