<?php

namespace App\Http\Controllers\Resources;

use App\Models\Product\ProductImage;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $active = '<div class="form-group mb-0"><div class="custom-control custom-switch"><input class="custom-control-input toogle-active" url="'.route('product.status',
                $this->id).'"';
        if ($this->is_publish) {
            $active .= ' checked="checked"';
        }
        $active .= 'id="'.$this->id.'" type="checkbox" value="'.$this->id.'"><label class="custom-control-label" for="'.$this->id.'">Active</label></div></div>';

        $array = [
            'id'       => $this->id,
            'title'    => $this->title,
            'category' => $this->categories->first()->name,
            'status'   => $active,
            'action'   => '<div class="d-flex"></div>',
        ];

        $image = optional(ProductImage::where('product_id', $this->id)->pluck('image'))->first();

        $array['image'] = '<img width="40" src="'.image_url('masterproduct', $image).'"/>';

        $array['action'] = '<a class="text-color:icon no-underline mr-3 delete" href="javascript:void(0)" url="'.route('product.bulkdelete').'" id="'.$this->id.'"><i class="fas fa-fw fa-trash"></i></a><a class="text-color:icon no-underline" href="'.route('product.edit',
                ['id' => $this->id]).'"><i class="fas fa-fw fa-edit"></i></a>';

        return $array;
    }
}
