<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendEmailsToActiveUsers;

class JobsController extends Controller
{
    //

    public function sendMailToactiveUsers(){
        SendEmailsToActiveUsers::dispatch();
    }
}
