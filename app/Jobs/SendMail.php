<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendMail implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $email,
        public Mailable $mailable,
    ) {}

    public function handle(): void
    {
        if (config('app.env') == 'local') {
            $this->email = 'ftinkere+yordanes_test@ya.ru';
        }

        Mail::to($this->email)
            ->send($this->mailable);
    }
}
