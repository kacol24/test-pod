<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConnectTokopediaDatatableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'store'       => $this->store->storename,
            'store_name'  => $this->store_name,
            'platform_id' => $this->platform_id,
            'created_at'  => $this->created_at->format('d M Y H:i'),
        ];

        if (! $this->platform_id) {
            $data['platform_id'] = '<a href="#" class="btn btn-primary btn-sm" @click.prevent="id = '. $this->id .'; platform_id = window.prompt(\'Platform ID\'); $nextTick(function() { return submit(); })">Set Platform ID</a>';
        }

        return $data;
    }
}
