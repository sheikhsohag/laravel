<?php

namespace App\Jobs;

use App\Mail\JobBulkMail;
use App\Models\EmailLog;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class JobBulkMailJob implements ShouldQueue
{
    use Queueable, Batchable, Dispatchable, SerializesModels,InteractsWithQueue;
    private $tries = 3;
    private $timeout = 300;

    /**
     * Create a new job instance.
     */
    private $content;
    private $emailJob;
    public function __construct($content, $emailJob)
    {
        $this->content = $content;
        $this->emailJob = $content;
    }

    public function handle(): void
    {
        if($this->batch()->canceled()){
            return;
        }

        try{
          
            Mail::to($this->content['recipient'])
                ->send(new JobBulkMail("this is message"));
                
            EmailLog::create([
                'email_job_id' => $this->emailJob->id,
                'recipient_email' => $this->content['recipient'],
                'status' => 'sent',
                'sent_at' => now(),
            ]);

        }catch(\Exception $e){
            EmailLog::create([
                'email_job_id' => $this->emailJob->id,
     
                'recipient_email' => $this->content['recipient'],
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'attempts' => $this->attempts(),
            ]);
            
            throw $e;
        }
    }

    public function failed(\Exception $exception){
        EmailLog::create([
            'email_job_id' => $this->emailJob->id,
            'recipient_email' => $this->content['recipient'],
            'status' => 'failed',
            'error_message' => $exception->getMessage(),
            'failed_at' => now(),
        ]);
    }
}
