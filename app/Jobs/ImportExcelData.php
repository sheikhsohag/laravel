<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class ImportExcelData implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600;
    public $tries = 3;
    public $maxExceptions = 1;

    public function __construct(
        protected string $filePath,
        protected string $importBatchId,
        protected int $startRow = 1,
        protected ?array $columnMapping = null
    ) {}

    public function handle()
    {
        // Disable query log to save memory
        DB::disableQueryLog();
        
        // Temporarily disable foreign key checks (if needed)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $reader = ReaderEntityFactory::createReaderFromFile($this->filePath);
        $reader->open($this->filePath);

        $batchInsertData = [];
        $rowCounter = 0;
        $chunkSize = 300; // Adjust based on your server capacity

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rowCounter++;
                
                if ($rowCounter < $this->startRow) continue;
                
                // First row is headers for column mapping
                if ($rowCounter === $this->startRow && empty($this->columnMapping)) {
                    $this->columnMapping = $this->mapHeadersToColumns($row->toArray());
                    continue;
                }

                $rowData = $this->prepareRowData($row->toArray());
                
                if (!empty($rowData)) {
                    $batchInsertData[] = $rowData;
                }

                if (count($batchInsertData) >= $chunkSize) {
                    $this->bulkInsert($batchInsertData);
                    $batchInsertData = [];
                }
            }
        }

        if (!empty($batchInsertData)) {
            $this->bulkInsert($batchInsertData);
        }

        $reader->close();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    protected function mapHeadersToColumns(array $headers): array
    {
        $mapping = [];
        $dbColumns = [
            'website', 'gender', 'firstName', 'lastName', 'title', 'brandName',
            'email', 'result', 'emailStatus', 'seniority', 'departments', 'mobilePhone',
            'employees', 'industry', 'keywords', 'personLinkedin', 'brandLinkedinUrl',
            'facebookUrl', 'city', 'state', 'country', 'brandAddress', 'brandCity',
            'brandState', 'brandCountry', 'brandPhone', 'category', 'combinedFollowers',
            'currency', 'genericEmails', 'estimatedMonthlyRevenue', 'facebookCategories',
            'facebookUrl_1', 'instagramFollowers', 'instagramUrl', 'languageCode', 'phone',
            'plan', 'platform', 'productVariants', 'productsSold', 'region', 'subregion',
            'technologies', 'Technologies_2', 'foundedYear', 'whatsapp', 'storeStatus',
            'lastUpdatedDate'
        ];
        
        foreach ($headers as $index => $header) {
            $normalizedHeader = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $header));
            foreach ($dbColumns as $column) {
                if (strtolower($column) === $normalizedHeader) {
                    $mapping[$index] = $column;
                    break;
                }
            }
        }
        
        return $mapping;
    }

    protected function prepareRowData(array $row): array
    {
        $data = [];
        foreach ($this->columnMapping as $index => $column) {
            if (isset($row[$index])) {
                // Special handling for date fields
                if ($column === 'lastUpdatedDate' && !empty($row[$index])) {
                    $data[$column] = $this->parseDate($row[$index]);
                } else {
                    $data[$column] = $this->cleanValue($row[$index]);
                }
            }
        }
        
        // Add import tracking
        $data['created_at'] = now();
        $data['updated_at'] = now();
        
        return $data;
    }

    protected function cleanValue($value)
    {
        if (is_string($value)) {
            $value = trim($value);
            return $value === '' ? null : $value;
        }
        return $value;
    }

    protected function parseDate($dateString)
    {
        try {
            return \Carbon\Carbon::parse($dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function bulkInsert(array $data)
    {
        $chunks = array_chunk($data, 1000);
        foreach ($chunks as $chunk) {
            DB::table('excels')->insert($chunk);
        }
        
        // Update batch progress
        DB::table('import_batches')
            ->where('id', $this->importBatchId)
            ->increment('processed_rows', count($data));
    }

    public function failed(\Throwable $exception)
    {
        DB::table('import_batches')
            ->where('id', $this->importBatchId)
            ->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage()
            ]);
    }
}