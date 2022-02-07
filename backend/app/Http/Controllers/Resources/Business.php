<?php

namespace App\Http\Controllers\Resources;

use Carbon\Carbon;
use Lang;
use Illuminate\Http\Resources\Json\JsonResource;

class Business extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'category' => optional($this->firstcategory())->title." - ".optional($this->secondcategory())->title,
            'status' => ucfirst($this->status),
            'treasure_arise_status' => ucfirst($this->treasure_arise_status),
            'action' => '<div class="d-flex"><a class="text-color:icon no-underline" href="'.route('business.edit',
                    ['id' => $this->id]).'"><i class="fas fa-fw fa-eye"></i></a><a class="ml-2 text-color:icon no-underline" data-confirm="Send statistic email?" href="'.route('business.statistics',
                    ['id' => $this->id]).'"><i class="fas fa-fw fa-paper-plane"></i></a></div>',
            'action_treasure_arise' => '<a class="text-color:icon no-underline" href="'.route('treasure_arise.edit', ['id' => $this->id]).'"><i class="fas fa-fw fa-eye"></i></a>',
        ];

        if ($this->status == \App\Models\Business\Business::STATUS_APPROVED && $this->approved_at) {
            $array['status'] .= '<br><small>' .Carbon::parse($this->approved_at)->format('d M Y H:i:s') .'</small>';
        } else {
            $array['status'] .= ' <i class="fas fa-info-circle" data-toggle="tooltip" data-html="true" title="Approved timestamp was not recorded. Est. approved at: '. $this->updated_at->format('d M Y H:i:s') .' <small>(taken from last updated at, not accurate)</small>"></i>';
        }

        return $array;
    }
}
