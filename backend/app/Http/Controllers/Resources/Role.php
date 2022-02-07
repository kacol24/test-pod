<?php

namespace App\Http\Controllers\Resources;

use Lang;
use Illuminate\Http\Resources\Json\JsonResource;

class Role extends JsonResource
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
            'name' => '<a href="'.route('permission.edit', ['id' => $this->id]).'">'.$this->name.'</a>',
            'action' => '<a class="text-color:icon no-underline mr-3 delete" href="javascript:void(0)" url="'.route('role.delete').'" id="'.$this->id.'"><i class="fas fa-fw fa-trash"></i></a><a class="text-color:icon no-underline" href="'.route('role.edit', ['id' => $this->id]).'"><i class="fas fa-fw fa-edit"></i></a>'
        ];
    }
}
