<?php

namespace App\Http\Controllers\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Lang;

class Category extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $active = '<div class="form-group mb-0"><div class="custom-control custom-switch"><input class="custom-control-input toogle-active" url="'.route('category.status',
                $this->id).'"';
        if ($this->is_active) {
            $active .= ' checked="checked"';
        }
        $active .= 'id="'.$this->id.'" type="checkbox"><label class="custom-control-label" for="'.$this->id.'">Active</label></div></div>';
        $array = [
            'id'     => $this->id,
            'title'  => $this->name,
            'parent' => ($this->parent_id) ? $this->parent->name : '',
            'active' => $active,
            'action' => '<a class="text-color:icon no-underline mr-3 delete" href="javascript:void(0)" url="'.route('category.delete').'" id="'.$this->id.'"><i class="fas fa-fw fa-trash"></i></a><a class="text-color:icon no-underline" href="'.route('category.edit',
                    ['id' => $this->id]).'"><i class="fas fa-fw fa-edit"></i></a>',
        ];

        return $array;
    }
}
