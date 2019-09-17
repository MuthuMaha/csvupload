<?php

namespace App\Jobs;
use Illuminate\Support\Facades\Storage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Contact;
use App\CsvData;
use App\Http\Requests\CsvImportRequest;
// use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class ExcelUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $filename,$request;

  public function __construct($filename,$id,$request)
  {
    $this->filename = $filename;
    $this->request = $request;
  }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $request=$this->request;
        $filename=$this->filename;
        // dd($filename);
        // $id=$this->id;
        $path =Storage::disk('local')->path($this->filename);
          

        if ($request->has('header')) {
            $data = Excel::load($path, function($reader) {})->get()->toArray();
        } else {
            $data = array_map('str_getcsv', file($path));
        }

        if (count($data) > 0) {
            if ($request->has('header')) {
                $csv_header_fields = [];
                foreach ($data[0] as $key => $value) {
                    $csv_header_fields[] = $key;
                }
            }
            $csv_data = array_slice($data, 0, 2);

            $csv_data_file = CsvData::create([
                'csv_filename' => $filename,
                'csv_header' => $request->has('header'),
                'csv_data' => json_encode($data)
            ]);
        } else {
            return redirect()->back();
        }

        return view('import_fields', compact( 'csv_header_fields', 'csv_data', 'csv_data_file'));

    }
}
