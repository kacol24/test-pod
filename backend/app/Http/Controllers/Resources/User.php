<?php

namespace App\Http\Controllers\Resources;

use Lang;
use Illuminate\Http\Resources\Json\JsonResource;
use Core\Models\User\UserGroup;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $groups = UserGroup::all();
        $customer_status = array('active','suspended');

        $group_html = '<select name="group_id" class="form-control customer-group" style="width:150px;" url="'.route('customer.updategroup').'" id="'.$this->id.'">';
        foreach($groups as $group) {
            if($this->group_id == $group->id) {
                $group_html .= '<option selected="selected" value="'.$group->id.'">'.$group->name.'</option>';
            } else {
                $group_html .= '<option value="'.$group->id.'">'.$group->name.'</option>';
            }
          }
        $group_html .= '</select>';

        $status_html = '<select name="status" class="form-control customer-status" style="width:150px;" url="'.route('customer.updatestatus').'" id="'.$this->id.'">';
        foreach($customer_status as $status) {
            if($this->status == $status) {
                $status_html .= '<option selected="selected" value="'.$status.'">'.Lang::get('general.'.$status).'</option>';
            } else {
                $status_html .= '<option value="'.$status.'">'.Lang::get('general.'.$status).'</option>';
            }
        }
        $status_html .= '</select>';

        return [
            'name' => '<a href="'.route('customer.dashboard', $this->id).'" style="color:#0d638f;">'.$this->name.'</a>',
            'email' => $this->email,
            'gender' => ucfirst($this->gender),
            'dob' => date('d F Y',strtotime($this->dob)),
            'group' => $group_html,
            'status' => $status_html,
            'avatar' => '<div class="avatar avatar--xs mx-md-auto bg-color:'. ($this->gender == "male" ? "blue": "red-500") .'">'. get_initials($this->name) .'</div>'
        ];
    }
}
