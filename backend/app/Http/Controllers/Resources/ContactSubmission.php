<?php

namespace App\Http\Controllers\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Lang;

class ContactSubmission extends JsonResource
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
            'id'      => $this->id,
            'name'    => $this->name,
            'email'   => '<a href="mailto:'.$this->email.'">'.$this->email.'</a>',
            'message' => $this->message,
            'sent_to' => $this->sent_to,
            'created_at' => $this->created_at->format('d M Y H:i') . ' <small>('.$this->created_at->diffForHumans() .')</small>',
            'action' => '<a class="text-color:icon no-underline mr-3 delete" href="javascript:void(0)" url="'.route('contact_submissions.delete').'" id="'.$this->id.'"><i class="fas fa-fw fa-trash"></i></a>'

        ];

        return $array;
    }
}
