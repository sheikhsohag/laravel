public function upload()
{
    request()->validate([
        'myexcel' => 'required|mimes:xlsx,xls,csv'
    ]);

    if (request()->hasFile('myexcel')) {
        $file = request()->file('myexcel');
        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($file->getPathname());

        $header = [];
        $chunks = [];
        $currentChunk = [];
        $chunkSize = 1000;
        $rowCount = 0;

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rowCount++;
                $cells = $row->getCells();
                $rowData = array_map(function($cell) {
                    return $cell->getValue();
                }, $cells);

                if ($rowCount === 1) {
                    $header = $rowData;
                    continue;
                }

                $currentChunk[] = $rowData;
                
                if (count($currentChunk) >= $chunkSize) {
                    $chunks[] = $currentChunk;
                    $currentChunk = [];
                }
            }
        }

        // Add the remaining rows
        if (!empty($currentChunk)) {
            $chunks[] = $currentChunk;
        }

        $reader->close();

        $batch = Bus::batch([])->dispatch();
        
        foreach ($chunks as $chunk) {
            $batch->add(new SalesExcelProcess($chunk, $header));
        }

        return $batch;
    }

    return redirect()->back()->with('error', 'Please upload a file');
}

---------------------------------------------------------------------------------------------


composer require maatwebsite/excel
composer require box/spout



job




<?php

namespace App\Jobs;

use App\Http\Controllers\Controller;
use Throwable;
use App\Models\Sales;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SalesExcelProcess implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $header;

    public function __construct($data, $header)
    {
        $this->data   = $data;
        $this->header = $header;
    }

    public function handle()
    {
        foreach ($this->data as $sale) {
            $saleData = array_combine($this->header, $sale);
            Sales::create($saleData);
        }
    }

    public function failed(Throwable $exception)
    {
        Log::error('Sales Excel Processing Failed: ' . $exception->getMessage());
    }
}


Controller


<?php

namespace App\Http\Controllers;

use App\Jobs\SalesExcelProcess;
use Illuminate\Support\Facades\Bus;
use Maatwebsite\Excel\Facades\Excel;

class SalesController extends Controller
{
    public function index()
    {
        return view('upload-file');
    }

    public function upload()
    {
        request()->validate([
            'myexcel' => 'required|mimes:xlsx,xls,csv'
        ]);

        if (request()->hasFile('myexcel')) {
            $file = request()->file('myexcel');
            
            // Using Maatwebsite/Excel
            $data = Excel::toArray([], $file);
            
            // Remove header
            $header = $data[0][0];
            $rows = array_slice($data[0], 1);
            
            // Chunk the data
            $chunks = array_chunk($rows, 1000);
            
            $batch = Bus::batch([])->dispatch();
            
            foreach ($chunks as $chunk) {
                $batch->add(new SalesExcelProcess($chunk, $header));
            }

            return $batch;
        }

        return redirect()->back()->with('error', 'Please upload a file');
    }

    // Keep the existing batch methods...
    public function batch()
    {
        $batchId = request('id');
        return Bus::findBatch($batchId);
    }

    public function batchInProgress()
    {
        $batches = DB::table('job_batches')->where('pending_jobs', '>', 0)->get();
        if (count($batches) > 0) {
            return Bus::findBatch($batches[0]->id);
        }

        return [];
    }
}



above code alternative..