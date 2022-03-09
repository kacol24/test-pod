<?php

namespace App\Http\Controllers\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Lang;

class Option extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'created_at' => $this->created_at->format('d M Y H:i:s'),
            'action'     => '<a class="text-color:icon no-underline mr-3 delete" href="javascript:void(0)" url="'.route('option.delete').'" id="'.$this->id.'"><i class="fas fa-fw fa-trash"></i></a><a class="text-color:icon no-underline" href="'.route('option.edit',
                    ['id' => $this->id]).'"><i class="fas fa-fw fa-edit"></i></a>',
        ];
    }
}
