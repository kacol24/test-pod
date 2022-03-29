<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderDatatableResource;
use App\Models\Order\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function datatable(Request $request)
    {
        $search = $request->search;
        $sorting = 'updated_at';
        $order = $request->order;
        $start_date = ($request->start_date != "") ? date("Y-m-d", strtotime($request->start_date)) : "";
        $end_date = ($request->end_date != "") ? date("Y-m-d", strtotime($request->end_date)) : "";
        $status = $request->status;

        if ($request->has('sort')) {
            $sorting = $request->sort;
        }

        return OrderDatatableResource::collection(
            Order::query()
                 ->when(! empty($search), function ($query) use ($search) {
                     return $query->where('order_no', 'like', "%{$search}%");
                 })
                 ->when(! empty($status), function ($query) use ($status) {
                     return $query->where('status_id', $status);
                 })
                 ->when(! empty($start_date), function ($query) use ($start_date) {
                     return $query->whereRaw('date(orders.created_at) >= ?', $start_date);
                 })
                 ->when(! empty($end_date), function ($query) use ($end_date) {
                     return $query->whereRaw('date(orders.created_at) <= ?', $end_date);
                 })
                 ->orderBy($sorting, $order)
                 ->paginate($request->limit)
        );
    }

    public function index()
    {
        return view('orders.index');
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);

        return view('orders.edit', compact('order'));
    }
}
