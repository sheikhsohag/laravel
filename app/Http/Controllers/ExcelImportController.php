<?php

namespace App\Http\Controllers;

use App\Jobs\ImportExcelData;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExcelImportController extends Controller
{
    public function showUploadForm()
    {
        return view('excel.upload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv', 

        ]);
        
        $filePath = $request->file('file')->store('temp_excel_imports');
        $batchId = Str::uuid();
        
        // Record the batch
        $importBatch = DB::table('import_batches')->insertGetId([
            'batch_id' => $batchId,
            'original_name' => $request->file('file')->getClientOriginalName(),
            'file_path' => $filePath,
            'status' => 'queued',
            'import_type' => $request->input('import_type', 'default'),
            'total_rows' => $this->countExcelRows(storage_path('app/private/'.$filePath)),
            'processed_rows' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Dispatch the job
        ImportExcelData::dispatch(
            storage_path('app/private/'.$filePath),
            $importBatch
        )->onQueue('excel_imports');
        
        return response()->json([
            'status' => 'success',
            'batch_id' => $batchId,
            'tracking_url' => route('import.status', $batchId),
        ]);
    }
    
    protected function countExcelRows(string $filePath): int
    {
        $reader = ReaderEntityFactory::createReaderFromFile($filePath);
        $reader->open($filePath);
        
        $count = 0;
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $count++;
            }
        }
        
        $reader->close();
        return $count - 1; // Subtract header row
    }
    
    public function checkStatus($batchId)
    {
        $batch = DB::table('import_batches')
            ->where('batch_id', $batchId)
            ->first();
            
        if (!$batch) {
            return response()->json(['error' => 'Batch not found'], 404);
        }
            
        return response()->json([
            'status' => $batch->status,
            'processed_rows' => $batch->processed_rows,
            'total_rows' => $batch->total_rows,
            'progress' => $batch->total_rows > 0 
                ? round(($batch->processed_rows / $batch->total_rows) * 100, 2)
                : 0,
            'filename' => $batch->original_name,
            'created_at' => $batch->created_at,
            'updated_at' => $batch->updated_at,
        ]);
    }
    
    public function listImports()
    {
        $imports = DB::table('import_batches')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return response()->json($imports);
    }
}