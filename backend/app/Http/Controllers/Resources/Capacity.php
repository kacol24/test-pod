<?php

namespace App\Http\Controllers\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Lang;

class Capacity extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [
            'id'     => $this->id,
            'title'  => $this->title,
            'capacity'  => $this->capacity,
            'action' => '<a class="text-color:icon no-underline mr-3 delete" href="javascript:void(0)" url="'.route('capacity.delete').'" id="'.$this->id.'"><i class="fas fa-fw fa-trash"></i></a><a class="text-color:icon no-underline" href="'.route('capacity.edit',
                    ['id' => $this->id]).'"><i class="fas fa-fw fa-edit"></i></a>',
        ];

        return $array;
    }
}
