<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DesignDatatableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $active = '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch"'. ($this->is_publish ? 'checked' : '') .'><label class="form-check-label" for="flexSwitchCheckDefault">Active</label></div>';

        $array = [
            'id'             => $this->id,
            'title'          => $this->title,
            'unit_sold'      => $this->unit_sold,
            'products_count' => $this->products->count(),
            'status'         => $active,
        ];

        $image = $this->products->first()->masterproduct->thumbnail_url;
        $array['image'] = '<img width="40" src="'.$image.'"/>';

        $array['action'] = view('product.datatable-actions', ['entity' => $this])->render();

        return $array;
    }
}
