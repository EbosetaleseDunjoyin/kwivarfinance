<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use App\Mail\ExceptionOccured;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];
    
    protected $adminEMail = "tech@nextpayday.co";
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public function report(Throwable $exception)
    {
        
        if ($this->shouldReport($exception)) {
            Mail::to($this->adminEMail)->send(new ExceptionOccured($exception));
        }

        return parent::report($exception);
    }
}
