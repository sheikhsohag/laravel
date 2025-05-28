<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use App\Jobs\SendBulkEmail;
use App\Models\EmailJob;
use App\Models\EmailLog;
use Illuminate\Support\Facades\Validator;

class BulkEmailController extends Controller
{
    public function sendBulkEmails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();
        
        // Create job record
        $emailJob = EmailJob::create([
            'subject' => $validated['subject'],
            'content' => $validated['content'],
            'total_recipients' => count($validated['recipients']),
            'status' => 'pending',
        ]);

        // Prepare jobs
        $jobs = [];
        foreach ($validated['recipients'] as $recipient) {
            $jobs[] = new SendBulkEmail([
                'to' => $recipient,
                'subject' => $validated['subject'],
                'content' => $validated['content'],
            ], $emailJob);
        }

        // Dispatch batch
        $batch = Bus::batch($jobs)
            ->then(function () use ($emailJob) {
                $emailJob->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
            })
            ->catch(function (Throwable $e) use ($emailJob) {
                $emailJob->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            })
            ->finally(function () use ($emailJob) {
                // Update counts
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

        // Update job with batch ID
        $emailJob->update(['batch_id' => $batch->id]);

        return response()->json([
            'message' => 'Bulk email job dispatched',
            'job_id' => $emailJob->id,
            'batch_id' => $batch->id,
        ], 202);
    }

    // In BulkEmailController.php
    public function getJobStatus($jobId)
    {
        $emailJob = EmailJob::findOrFail($jobId);
        
        // Calculate percentage
        $total = $emailJob->total_recipients;
        $processed = $emailJob->sent_count + $emailJob->failed_count;
        $percentage = $total > 0 ? round(($processed / $total) * 100) : 0;

        $logs = EmailLog::where('email_job_id', $jobId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'job' => $emailJob,
            'stats' => [
                'total' => $total,
                'sent' => $emailJob->sent_count,
                'failed' => $emailJob->failed_count,
                'pending' => $total - $processed,
                'percentage' => $percentage,
            ],
            'logs' => $logs,
        ]);
    }

    public function getBatchProgress($batchId)
    {
        $batch = Bus::findBatch($batchId);
        
        if (!$batch) {
            return response()->json(['message' => 'Batch not found'], 404);
        }

        $percentage = $batch->totalJobs > 0 
            ? round(($batch->processedJobs() / $batch->totalJobs) * 100)
            : 0;

        return response()->json([
            'total_jobs' => $batch->totalJobs,
            'pending_jobs' => $batch->pendingJobs,
            'failed_jobs' => $batch->failedJobs,
            'processed_jobs' => $batch->processedJobs(),
            'progress' => $batch->progress(),
            'percentage' => $percentage,
            'finished' => $batch->finished(),
        ]);
    }
}