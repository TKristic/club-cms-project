<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ApiClientDemo extends Command
{
    protected $signature = 'api:demo {email} {password} {--base=}';
    protected $description = 'REST klijent koji konzumira vlastiti API';

    public function handle(): int
    {
        $base = rtrim($this->option('base') ?: config('app.url'), '/');
        $this->info("API baza: $base");

        // 1) Prijava → token
        $login = Http::acceptJson()->post("$base/api/login", [
            'email'    => $this->argument('email'),
            'password' => $this->argument('password'),
        ]);

        if ($login->failed()) {
            $this->error('Prijava nije uspjela: ' . $login->status());
            $this->line($login->body());
            return self::FAILURE;
        }

        $token = $login->json('token');
        $this->info('Prijava OK. Uloge: ' . implode(', ', $login->json('user.roles') ?? []));

        // 2) Resurs 1 — vijesti
        $news = Http::acceptJson()->withToken($token)->get("$base/api/news");
        $this->info('GET /api/news → ' . $news->status() . ', broj: ' . count($news->json() ?? []));

        // 3) Resurs 2 — igrači
        $players = Http::acceptJson()->withToken($token)->get("$base/api/players");
        $this->info('GET /api/players → ' . $players->status() . ', broj: ' . count($players->json() ?? []));

        // 4) Autorizacija — pokušaj kreirati vijest
        $create = Http::acceptJson()->withToken($token)->post("$base/api/news", [
            'title' => 'Test preko API-ja',
            'body'  => 'Sadržaj kreiran kroz REST klijent.',
        ]);
        $this->info('POST /api/news → ' . $create->status() . ' '
            . ($create->status() === 201 ? '(kreirano — admin)' : '(zabranjeno — nije admin)'));

        return self::SUCCESS;
    }
}