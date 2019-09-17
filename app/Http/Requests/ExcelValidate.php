<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExcelValidate extends FormRequest
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
                    'serial_number'=>'required|unique', 
                    'part_no'=>'required', 
                    'asset_id'=>'required', 
                    'category'=>'required', 
                    'sub_category_one'=>'required', 
                    'brand'=>'required', 
                    'model'=>'required', 
                    'attribute_1'=>'required', 
                    'attribute_2'=>'required', 
                    'attribute_3'=>'required', 
                    'attribute_4'=>'required', 
                    'supplier_id'=>'required', 
                    'comment'=>'required', 
                    'wh_id'=>'required', 
                    'location'=>'required', 
                    'wh_box_id'=>'required', 
                    'status'=>'required', 
                    'lot_no'=>'required', 
                    'main_category'=>'required', 
                    'sub_category_two'=>'required', 
                    'sub_category_three'=>'required', 
                    'prod_serial'=>'unique'
                ];
    }
}
