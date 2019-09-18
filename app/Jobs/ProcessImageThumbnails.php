<?php
// app/Jobs/ProcessImageThumbnails.php
namespace App\Jobs;
 
use App\Image as ImageModel;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use App\Inventory;
use Session;
use Illuminate\Support\Facades\Storage;


 use Illuminate\Support\Facades\Validator;
class ProcessImageThumbnails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
 
    protected $results;
 
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($results)
    {
        $this->results = $results;
    }
 
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $results=$this->results;
       
        foreach ($results as $row) {
            

             $data=[
                    'serial_number'=>$row->serial_number, 'part_no'=>$row->part_no, 'asset_id'=>$row->asset_id, 'category'=>$row->category, 'sub_category_one'=>$row->sub_category_one, 'brand'=>$row->brand, 'model'=>$row->model, 'attribute_1'=>$row->attribute_1, 'attribute_2'=>$row->attribute_2, 'attribute_3'=>$row->attribute_3, 'attribute_4'=>$row->attribute_4, 'supplier_id'=>$row->supplier_id, 'comment'=>$row->comment, 'wh_id'=>$row->wh_id, 'location'=>$row->location, 'wh_box_id'=>$row->wh_box_id, 'status'=>$row->status, 'lot_no'=>$row->lot_no, 'main_category'=>$row->main_category, 'sub_category_two'=>$row->sub_category_two, 'sub_category_three'=>$row->sub_category_three, 'prod_serial'=>$row->prod_serial
                ];
                try 
                {
                    Inventory::create($data);
                }
                catch(\Illuminate\Database\QueryException $e){
                    $a[]=$e->getMessage();
                   Session::flash("error","Some Error occured in csv check error file");
                }

                if(isset($a)){
                    Storage::disk('local')->put("error.txt",$a);
                }

            }
    }
}