<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'            => 'required',
            'prism_id'         => 'required',
            'production_time'  => 'required',
            'fulfillment_time' => 'required',
            'threshold'        => 'required',
            'description'      => 'required',
            'capacity_id'      => 'required',

            //'colors.*.color' => 'required',
            //'colors.*.name'  => 'required',

            'templates.*.design_name' => 'required',
            'templates.*.price'       => 'required',
            //'templates.*.shape'         => 'required',
            //'templates.*.orientation'   => 'required',
            //'templates.*.unit'          => 'required',
            //'templates.*.enable_resize' => 'required',
            //'templates.*.bleed'         => 'required',
            //'templates.*.safety_line'   => 'required',
            //'templates.*.width'         => 'required',
            //'templates.*.height'        => 'required',
            //'templates.*.ratio'         => 'required',

            'templates.*.design.*.file'       => 'required',
            'templates.*.design.*.page_name'  => 'required',
            'templates.*.design.*.location_x' => 'required',
            'templates.*.design.*.location_y' => 'required',

            'templates.*.preview.*.file'           => 'required',
            'templates.*.preview.*.preview_name'   => 'required',
            'templates.*.preview.*.thumbnail_name' => 'required',
            'templates.*.preview.*.file_config'    => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'colors.*.color' => 'color code',
            'colors.*.name'  => 'color name',

            'templates.*.design_name'   => 'design name',
            'templates.*.price'         => 'price',
            'templates.*.shape'         => 'shape',
            'templates.*.orientation'   => 'orientation',
            'templates.*.unit'          => 'unit',
            'templates.*.enable_resize' => 'enable resize',
            'templates.*.bleed'         => 'bleed',
            'templates.*.safety_line'   => 'safety line',
            'templates.*.width'         => 'width',
            'templates.*.height'        => 'height',
            'templates.*.ratio'         => 'ratio',

            'templates.*.design.*.file'      => 'design file',
            'templates.*.design.*.page_name' => 'design page name',

            'templates.*.preview.*.file'           => 'preview file',
            'templates.*.preview.*.preview_name'   => 'preview name',
            'templates.*.preview.*.thumbnail_name' => 'preview thumbnail name',
            'templates.*.preview.*.file_config'    => 'preview file config',
        ];
    }
}
