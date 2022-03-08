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

        //$image = env('BACKEND_URL').'/storage/masterproduct/'.$this->thumbnail();

        //$array['image'] = '<img width="40" src="'.image_url('masterproduct', $image).'"/>';

        $array['action'] = '<a href="" class="btn btn-default d-inline-flex me-3">
                                    Order A Sample
                                </a>
                                <a class="text-color:icon no-underline me-3" href="javascript:void(0)">
                                    <i class="fas fa-fw fa-download"></i>
                                </a>
                                <a class="text-color:icon no-underline me-3" href="javascript:void(0)">
                                    <i class="fas fa-fw fa-trash"></i>
                                </a>
                                <a class="text-color:icon no-underline" href="' . route('design.edit', 1) . '">
                                    <i class="fas fa-fw fa-edit"></i>
                                </a>';

        return $array;
    }
}
