<?php

namespace App\Services;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class ClubMailer
{
    public function __construct(protected SmtpSettingsService $settings) {}

    public function configure(): void
    {
        $s = $this->settings->all(decryptPassword: true);
        $useSsl = ($s['encryption'] === 'ssl') || ((int) $s['port'] === 465);

        config([
            'mail.default'                 => 'smtp',
            'mail.mailers.smtp.transport'  => 'smtp',
            'mail.mailers.smtp.host'       => $s['host'],
            'mail.mailers.smtp.port'       => (int) $s['port'],
            'mail.mailers.smtp.username'   => $s['username'] ?: null,
            'mail.mailers.smtp.password'   => $s['password'] ?: null,
            'mail.mailers.smtp.scheme'     => $useSsl ? 'smtps' : 'smtp',
            'mail.mailers.smtp.encryption' => $s['encryption'] ?: null,
            'mail.from.address'            => $s['from_address'] ?: 'no-reply@klub.test',
            'mail.from.name'               => $s['from_name'] ?: 'Klub',
        ]);
    }

    public function send(string $to, Mailable $mailable): void
    {
        $this->configure();
        Mail::to($to)->send($mailable);
    }
}