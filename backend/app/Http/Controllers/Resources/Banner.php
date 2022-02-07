<?php

namespace App\Http\Controllers\Resources;

use Lang;
use Illuminate\Http\Resources\Json\JsonResource;

class Banner extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $active = '<div class="form-group mb-0"><div class="custom-control custom-switch"><input class="custom-control-input toogle-active" url="'.route('banner.status', $this->id).'"';
        if($this->is_active) {
            $active .= ' checked="checked"';
        }
        $active .= 'id="'.$this->id.'" type="checkbox"><label class="custom-control-label" for="'.$this->id.'">Active</label></div></div>';
        return [
            'title' => $this->title,
            'type' => ucwords(str_replace('_', ' ', $this->type)),
            'status' => $active,
            'start_date' => date('d F Y H:i',strtotime($this->start_date)),
            'end_date' => $this->end_date ? date('d F Y H:i', strtotime($this->end_date)) : 'Never',
            'action' => '<a class="text-color:icon no-underline mr-3 delete" href="javascript:void(0)" url="'.route('banner.destroy').'" id="'.$this->id.'"><i class="fas fa-fw fa-trash"></i></a><a class="text-color:icon no-underline" href="'.route('banner.edit', ['banner' => $this->id]).'"><i class="fas fa-fw fa-edit"></i></a>'
        ];
    }
}
