<?php

namespace App\Http\Controllers\Resources;

use Lang;
use Illuminate\Http\Resources\Json\JsonResource;

class Admin extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'action' => '<a class="text-color:icon no-underline mr-3 delete" href="javascript:void(0)" url="'.route('admin.delete').'" id="'.$this->id.'"><i class="fas fa-fw fa-trash"></i></a><a class="text-color:icon no-underline" href="'.route('admin.edit', ['id' => $this->id]).'"><i class="fas fa-fw fa-edit"></i></a>'
        ];
    }
}
