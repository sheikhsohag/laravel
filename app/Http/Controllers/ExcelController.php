<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Common\Exception\UnsupportedTypeException;

class ExcelController extends Controller
{
    // Set higher time limit (in seconds)
    const PROCESSING_TIMEOUT = 300; // 5 minutes
    const MEMORY_LIMIT = '512M'; // 512MB
    
    public function store(Request $request)
    {
        // Set initial memory and time limits
        ini_set('memory_limit', self::MEMORY_LIMIT);
        set_time_limit(self::PROCESSING_TIMEOUT);

        try {
            // Validate the incoming request has an Excel file
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv'
            ]);

            // Get the uploaded file with proper extension
            $file = $request->file('file');
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = strtolower($file->getClientOriginalExtension());
            
            // Ensure chunk directory exists
            Storage::makeDirectory('chunk');
            
            // Create appropriate reader
            $reader = $this->createReaderForExtension($extension);
            $reader->open($file->getPathname());
            
            $chunkFiles = [];
            $chunkSize = 300;
            $chunkIndex = 0;
            $rowIndex = 0;
            $header = null;
            $currentChunk = [];
            
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    // Check memory and time limits periodically
                    if ($rowIndex % 100 === 0) {
                        $this->checkTimeLimit();
                        $this->checkMemoryUsage();
                    }
                    
                    $rowData = $row->toArray();
                    
                    // Store header row
                    if ($rowIndex === 0) {
                        $header = $rowData;
                        $rowIndex++;
                        continue;
                    }
                    
                    // Add to current chunk
                    $currentChunk[] = $rowData;
                    
                    // When chunk size reached, save to file
                    if (count($currentChunk) === $chunkSize) {
                        $chunkFilename = $this->saveChunk($originalFilename, $header, $currentChunk, ++$chunkIndex, $extension);
                        $chunkFiles[] = $chunkFilename;
                        $currentChunk = [];
                    }
                    
                    $rowIndex++;
                }
            }
            
            // Save remaining rows in the last chunk
            if (!empty($currentChunk)) {
                $chunkFilename = $this->saveChunk($originalFilename, $header, $currentChunk, ++$chunkIndex, $extension);
                $chunkFiles[] = $chunkFilename;
            }
            
            $reader->close();
            
            return response()->json([
                'status' => 'success',
                'original_filename' => $file->getClientOriginalName(),
                'chunk_files' => $chunkFiles,
                'chunks_count' => count($chunkFiles),
                'total_rows' => $rowIndex - 1,
                'message' => 'File successfully chunked and stored',
                'processing_time' => microtime(true) - LARAVEL_START,
                'memory_usage' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB'
            ]);

        } catch (UnsupportedTypeException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unsupported file type. Please upload XLSX, XLS, or CSV files.'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage(),
                'memory_usage' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB'
            ], 500);
        }
    }
    
    protected function createReaderForExtension($extension)
    {
        switch ($extension) {
            case 'xlsx':
                return ReaderEntityFactory::createXLSXReader();
            case 'xls':
                return ReaderEntityFactory::createXLSReader();
            case 'csv':
                return ReaderEntityFactory::createCSVReader();
            default:
                throw new UnsupportedTypeException('Unsupported file type: ' . $extension);
        }
    }
    
    protected function saveChunk($originalFilename, $header, $data, $chunkIndex, $extension)
    {
        // Add header to the chunk data
        array_unshift($data, $header);
        
        // Create a temporary file for the chunk
        $tempFilename = 'temp_chunk_' . $chunkIndex . '.' . $extension;
        
        // Save chunk to temporary file
        Excel::store(new \App\Exports\ArrayToExcelExport($data), $tempFilename);
        
        // Define final chunk filename
        $chunkFilename = $originalFilename . '_chunk_' . $chunkIndex . '.' . $extension;
        
        // Move to permanent location
        Storage::move($tempFilename, 'chunk/' . $chunkFilename);
        
        return $chunkFilename;
    }
    
    protected function checkTimeLimit()
    {
        $maxExecutionTime = ini_get('max_execution_time');
        $elapsedTime = microtime(true) - LARAVEL_START;
        
        // If we're within 5 seconds of the limit, reset it
        if ($maxExecutionTime > 0 && $elapsedTime > ($maxExecutionTime - 5)) {
            set_time_limit($maxExecutionTime + 30);
        }
    }
    
    protected function checkMemoryUsage()
    {
        $memoryLimit = ini_get('memory_limit');
        $usedMemory = memory_get_usage(true);
        $limitBytes = $this->convertToBytes($memoryLimit);
        
        // If we're using more than 80% of memory, increase it
        if ($usedMemory > ($limitBytes * 0.8)) {
            $newLimit = max($limitBytes * 2, 512 * 1024 * 1024); // At least 512MB
            ini_set('memory_limit', $newLimit);
        }
    }
    
    protected function convertToBytes($memoryLimit)
    {
        if (preg_match('/^(\d+)(.)$/', $memoryLimit, $matches)) {
            if ($matches[2] == 'G') {
                return $matches[1] * 1024 * 1024 * 1024;
            } elseif ($matches[2] == 'M') {
                return $matches[1] * 1024 * 1024;
            } elseif ($matches[2] == 'K') {
                return $matches[1] * 1024;
            }
        }
        return (int)$memoryLimit;
    }
}