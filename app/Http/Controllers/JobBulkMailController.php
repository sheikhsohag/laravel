<?php

namespace App\Http\Controllers;

use App\Jobs\JobBulkMailJob;
use App\Models\EmailJob;
use Illuminate\Http\Request;

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


    }
}
