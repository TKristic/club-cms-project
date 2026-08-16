<?php

namespace App\Console\Commands;

use App\Models\Club;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class HnsTest extends Command
{
    protected $signature = 'hns:test';
    protected $description = 'Dohvati HNS stranicu i spremi HTML za analizu';

    public function handle(): int
    {
        $url = Club::value('hns_url');
        if (! $url) {
            $this->error('HNS link nije postavljen (/admin → Klub → HNS semafor link).');
            return self::FAILURE;
        }

        $this->info('Dohvaćam: ' . $url);

        $html = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
            'Accept-Language' => 'hr-HR,hr;q=0.9,en;q=0.8',
            'Accept-Encoding' => 'gzip, deflate',
            'Referer' => 'https://semafor.hns.family/',
            'Upgrade-Insecure-Requests' => '1',
            'Sec-Fetch-Dest' => 'document',
            'Sec-Fetch-Mode' => 'navigate',
            'Sec-Fetch-Site' => 'none',
        ])->timeout(20)->get($url)->body();

        $path = storage_path('app/hns_debug.html');
        file_put_contents($path, $html);

        $this->info('Dohvaćeno znakova: ' . strlen($html));
        $this->info('Spremljeno u: ' . $path);

        $data = app(\App\Services\HnsScraperService::class)->refresh();

        if (! ($data['ok'] ?? false)) {
            $this->error($data['error'] ?? 'Nepoznata greška.');
            return self::FAILURE;
        }

        $this->newLine();
        $this->info('LJESTVICA: ' . ($data['standings']['title'] ?? '—'));
        foreach (array_slice($data['standings']['rows'], 0, 5) as $r) {
            $this->line("  {$r['position']}. {$r['club']} — {$r['points']} bod ({$r['played']} ut.)");
        }

        $this->newLine();
        $this->info('UTAKMICE (prvih 5):');
        foreach (array_slice($data['fixtures'], 0, 5) as $m) {
            $score = $m['played'] ? "{$m['res1']}:{$m['res2']}" : 'vs';
            $this->line("  {$m['date']} — {$m['home']} {$score} {$m['away']}");
        }

        return self::SUCCESS;
    }
}