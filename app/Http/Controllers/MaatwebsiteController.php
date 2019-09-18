<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\ExcelValidate;
use Input;
use App\Contact;
use DB;
use Session;
 use Illuminate\Support\Facades\Validator;
use Excel;
use App\Jobs\ProcessImageThumbnails;

class MaatwebsiteController extends Controller
{
    public function importExport()
    {
        return view('importExport');
    }
    public function downloadExcel($type)
    {
        $data = Inventory::get()->toArray();
        return Excel::create('laravelcode', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download($type);
    }
    public function importExcel(Request $request)
    {
        if($request->hasFile('import_file')){


    $file = $request->file('import_file');
    $handle = fopen($file,"r");
    $header = fgetcsv($handle, 0, ',');
    $countheader= count($header); 
    if($countheader>4  && in_array('id',$header) && in_array('serial_number',$header) && in_array('part_no',$header)){
       
            // Excel::load($request->file('import_file')->getRealPath(), function ($reader) {
            //     foreach ($reader->toArray() as $key => $row) {
            //         $data['first_name'] = $row['first_name'];
            //         $data['last_name'] = $row['last_name'];
            //         $data['email'] = $row['email']; 

            //         if(!empty($data)) {
            //             DB::table('contacts')->insert($data);
            //         }
            //     }
            // });
            // return $request->file('import_file')->getRealPath();
            Excel::filter('chunk')->load($request->file('import_file')->getRealPath())->chunk(5300, function($results) {
                foreach ($results->toArray() as $key => $value) {
             $validator=Validator::make($value,[
 
                   'serial_number'=>'required|unique:inventories', 
                    'part_no'=>'required', 
                    'asset_id'=>'required', 
                    'category'=>'required|min:1|max:3', 
                    'sub_category_one'=>'required|min:1|max:3', 
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
                    'main_category'=>'required|min:1|max:3', 
                    'sub_category_two'=>'required|min:1|max:3', 
                    'sub_category_three'=>'required|min:1|max:3', 
                    'prod_serial'=>'unique:inventories'
    ]);

       if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->back()->withInput();
       }
       // dd($validator);
   }
        // ProcessImageThumbnails::dispatch();
         dispatch(new ProcessImageThumbnails($results));

			});
            // Session::flash('success', 'Youe file successfully import in database!!!');
        }
        else{
        Session::flash('error', 'Your DB table column and Excel first row not matching');


        }


        }

        

        return back();
    }
}