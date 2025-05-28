<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\BulkEmail;
use App\Models\EmailJob;
use App\Models\EmailLog;

class SendBulkEmail implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;
    
    protected $emailData;
    protected $jobRecord;

    public function __construct($emailData, EmailJob $jobRecord)
    {
        $this->emailData = $emailData;
        $this->jobRecord = $jobRecord;
    }

    public function handle()
    {
        if ($this->batch()->cancelled()) {
            return;
        }

        try {
            Mail::to($this->emailData['to'])
                ->send(new BulkEmail($this->emailData));
                
            EmailLog::create([
                'email_job_id' => $this->jobRecord->id,
                'recipient_email' => $this->emailData['to'],
                'status' => 'sent',
                'sent_at' => now(),
            ]);
            
        } catch (\Exception $e) {
            EmailLog::create([
                'email_job_id' => $this->jobRecord->id,
                'recipient_email' => $this->emailData['to'],
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'attempts' => $this->attempts(),
            ]);
            
            throw $e; // This will trigger retry if configured
        }
    }

    public function failed(\Exception $exception)
    {
        EmailLog::create([
            'email_job_id' => $this->jobRecord->id,
            'recipient_email' => $this->emailData['to'],
            'status' => 'failed',
            'error_message' => $exception->getMessage(),
            'failed_at' => now(),
        ]);
    }
}