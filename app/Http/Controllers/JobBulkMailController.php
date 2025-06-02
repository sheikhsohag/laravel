<?php

namespace App\Http\Controllers;

use App\Jobs\JobBulkMailJob;
use App\Models\EmailJob;
use App\Models\EmailLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;


class JobBulkMailController extends Controller
{
    public function sendBulkEmail(Request $request)
    {
        $validated = $request->validate([
            'subject'=>'required|string',
            'recipients'=>'required|array',
            'recipients.*'=>'required|email'
        ]);

        $emailJob = EmailJob::create([
            'subject' => $validated['subject'],
            'content' => "test mail for test job",
            'total_recipients' => count($validated['recipients']),
            'status' => 'pending',
        ]);

        $job = [];

        foreach($validated['recipients'] as $recipient){
            $job[] = new JobBulkMailJob(['email'=>$recipient],$emailJob);
        }

        $batch = Bus::batch($job)
        ->then(function() use ($emailJob){
            $emailJob->update([
                "status"=>"completed",
                "completed_at"=>now()
            ]);
        })
        ->catch(function(Throwable $e) use ($emailJob){
             $emailJob->update([
                "status"=>"failed",
                 "completed_at"=>now()
             ]);
        })
        ->finally(function() use ($emailJob) {
            $sentCount = EmailLog::where('email_job_id', $emailJob->id)
                    ->where('status', 'sent')
                    ->count();
                    
                $failedCount = EmailLog::where('email_job_id', $emailJob->id)
                    ->where('status', 'failed')
                    ->count();
                    
                $emailJob->update([
                    'sent_count' => $sentCount,
                    'failed_count' => $failedCount,
                ]);
        })
        ->dispatch();

        
    $emailJob->update(['batch_id' => $batch->id]);

        return response()->json([
            'message' => 'Bulk email job dispatched',
            'job_id' => $emailJob->id,
            'batch_id' => $batch->id,
        ], 202);

    }
}
