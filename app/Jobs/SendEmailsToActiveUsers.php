<?php

namespace App\Jobs;

use App\Mail\MailActiveUsers;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailsToActiveUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $numberOfFailues = 0;

    public function handle()
    {
        try {
            $activeUsers = User::where('is_active', true)->get();

            if ($activeUsers->isNotEmpty()) {
                foreach ($activeUsers as $user) {
                    // Your email sending logic here
                    // Example using Laravel Mail facade
                    Mail::to($user->email)->send(new MailActiveUsers($user));
                }
            }
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }

    public function failed(\Exception $exception)
    {
       $this->numberOfFailues++;

       if($this->numberOfFailues === 3){
           $this->delete();
       }
    }
}
