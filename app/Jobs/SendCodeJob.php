<?php

namespace App\Jobs;

use App\Mail\SendCodeMail;
use App\Models\VerificationCode;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $email;
    protected $code;

    /**
     * Create a new job instance.
     */
    public function __construct($email, $code)
    {
        $this->email = $email;
        $this->code = $code;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Update or create the verification code in the database
        VerificationCode::updateOrCreate(
            ['email' => $this->email],
            [
                'code' => $this->code,
                'expires_at' => now()->addMinutes(10),
            ]
        );

        // Send the verification code via email
        Mail::to($this->email)->send(new SendCodeMail($this->code, $this->email));

    }
}
