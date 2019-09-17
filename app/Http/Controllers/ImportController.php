<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use App\Contact;
use App\CsvData;
use App\Http\Requests\CsvImportRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
// use Illuminate\Http\Request;
use App\Jobs\ExcelUploadJob;
use App\User;

class ImportController extends Controller implements ShouldQueue
{
    use Queueable;

    public function getImport()
    {
        return view('import');
    }

    public function parseImport(CsvImportRequest $request)
    {
        $id=1;
        $file=$request->file('csv_file');
       // $extension = $request->file('csv_data')->guessExtension();
      $filename = $request->file('csv_file')->getClientOriginalName();
      // return $filename;
      if ($file) {
          $s3 = Storage::disk('local')->put($filename, file_get_contents($file->getRealPath()));;
      }
      // $this->dispatch(new UploadImagesThumb($filename, $id));
      dispatch(new ExcelUploadJob($filename,$id,$request));
    }

    public function processImport(Request $request)
    {
        $data = CsvData::find($request->csv_data_file_id);
        $csv_data = json_decode($data->csv_data, true);
        foreach ($csv_data as $row) {
            $contact = new Contact();
            foreach (config('app.db_fields') as $index => $field) {
                if ($data->csv_header) {
                    $contact->$field = $row[$request->fields[$field]];
                } else {
                    $contact->$field = $row[$request->fields[$index]];
                }
            }
            $contact->save();
        }

        return view('import_success');
    }

}
