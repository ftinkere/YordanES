<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\Mailer;

class SendMail implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $email,
        public Mailable $mailable,
        private readonly Mailer $mailer,
        private readonly Repository $configRepository,
    ) {}

    public function handle(): void
    {
        if ($this->configRepository->get('app.env') == 'local') {
            $this->email = 'ftinkere+yordanes_test@ya.ru';
        }

        $this->mailer->to($this->email)
            ->send($this->mailable);
    }
}
