<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable=['serial_number', 'part_no', 'asset_id', 'category', 'sub_category_one', 'brand', 'model', 'attribute_1', 'attribute_2', 'attribute_3', 'attribute_4', 'supplier_id', 'comment', 'wh_id', 'location', 'wh_box_id', 'status', 'lot_no', 'main_category', 'sub_category_two', 'sub_category_three', 'prod_serial'];
}
