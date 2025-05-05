<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ExcelController extends Controller
{
    public function store(Request $request)
    {
        ini_set('memory_limit', '512M');
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        $file = $request->file('file');
        $filename = $file->getRealPath();

        // Load Excel file
        $spreadsheet = IOFactory::load($filename);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $header = $rows[0];
        $dataRows = array_slice($rows, 1);

        $chunkSize = 100;
        $chunks = array_chunk($dataRows, $chunkSize);
        $paths = [];

        // ✅ Ensure directory exists
        $storagePath = storage_path('app/chunks');
        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        foreach ($chunks as $index => $chunkRows) {
            $newSpreadsheet = new Spreadsheet();
            $newSheet = $newSpreadsheet->getActiveSheet();

            // Header
            foreach ($header as $colIndex => $cell) {
                $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                $newSheet->setCellValue("{$colLetter}1", $cell);
            }

            // Data
            foreach ($chunkRows as $rowIndex => $row) {
                foreach ($row as $colIndex => $cellValue) {
                    $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                    $newSheet->setCellValue("{$colLetter}" . ($rowIndex + 2), $cellValue);
                }
            }

            $chunkFileName = "part_" . ($index + 1) . ".xlsx";
            $chunkFilePath = $storagePath . DIRECTORY_SEPARATOR . $chunkFileName;

            // Save to disk
            $writer = IOFactory::createWriter($newSpreadsheet, 'Xlsx');
            $writer->save($chunkFilePath);

            // Save relative path for JSON response
            $paths[] = 'storage/app/chunks/' . $chunkFileName;
        }

        return response()->json([
            'message' => 'Chunks created successfully',
            'files' => $paths
        ]);
    }
}
