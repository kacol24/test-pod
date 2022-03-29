<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDatatableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'order_no'   => '<a href="'.route('orders.edit', $this).'" class="text-decoration-none">'.$this->order_no.'<small class="d-block text-color:icon">Tokopedia</small></a>',
            'customer'   => $this->shipping->name.'<small class="d-block text-color:icon">'.$this->shipping->phone.'</small>',
            'address'      => $this->shipping->address.'<small class="d-block text-color:icon">'.$this->shipping->city.'</small>',
            'total'      => 'IDR ' . number_format($this->final_amount, 0, ",", ".").'<small class="d-block text-color:icon">'.$this->payment_method.'</small>',
            'created_at' => date('d F Y', strtotime($this->created_at)).'<small class="d-block text-color:icon">'.date('H:i A', strtotime($this->created_at)).'</small>',
            'status'     => '<div class="d-flex align-items-center"><span class="badge badge-status-'.$this->status_id.' d-inline-block p-0" style="width: 6px;height: 6px;"></span>'.$this->status->title.'</div>',
        ];
    }
}
