<?php

namespace App\Services;

use App\Models\Club;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class HnsScraperService {

    protected string $cacheKey = 'hns_data';
    protected int $cacheMinutes = 60;
    protected ?string $url = null;

    public function __construct() {
        $this->url = Club::value('hns_url');
    }

    public function data(): array {
        if (! $this->url) {
            return ['ok' => false, 'error' => 'HNS link nije postavljen u postavkama kluba.'];
        }

        return Cache::remember($this->cacheKey, now()->addMinutes($this->cacheMinutes), function () {
            return $this->scrape($this->url);
        });
    }

    public function refresh(): array {
        Cache::forget('hns_data');
        return $this->data();
    }

    protected function scrape(string $url): array {
        try {
            $html = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language' => 'hr-HR,hr;q=0.9,en;q=0.8',
                'Accept-Encoding' => 'gzip, deflate',
                'Referer' => 'https://semafor.hns.family/',
            ])->timeout(20)->get($url)->body();
        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => 'Dohvat nije uspio: ' . $e->getMessage()];
        }

        $crawler = new Crawler($html);

        return [
            'ok'        => true,
            'fetched_at'=> now()->toDateTimeString(),
            'standings' => $this->parseStandings($crawler),
            'fixtures'  => $this->parseFixtures($crawler),
        ];
    }

    protected function parseStandings(Crawler $crawler): array {
        $node = $crawler->filter('.competition_table.type1');
        if ($node->count() === 0) {
            return ['title' => null, 'rows' => []];
        }

        $title = $crawler->filter('.block.competition_table h2')->count()
            ? trim($crawler->filter('.block.competition_table h2')->first()->text())
            : null;

        $rows = $node->first()->filter('li.row')->each(function (Crawler $li) {
            $logo = $li->filter('.club img')->count()
                ? $li->filter('.club img')->attr('src') : null;

            return [
                'club_id'  => $li->attr('data-clubid'),
                'position' => $this->text($li, '.position'),
                'club'     => trim(preg_replace('/\s+/', ' ', $li->filter('.club a')->count() ? $li->filter('.club a')->text() : '')),
                'logo'     => $logo,
                'played'   => $this->text($li, '.played'),
                'wins'     => $this->text($li, '.wins'),
                'draws'    => $this->text($li, '.draws'),
                'losses'   => $this->text($li, '.losses'),
                'gplus'    => $this->text($li, '.gplus'),
                'gminus'   => $this->text($li, '.gminus'),
                'gdiff'    => $this->text($li, '.gdiff'),
                'points'   => $this->text($li, '.points'),
            ];
        });

        return ['title' => $title, 'rows' => $rows];
    }

    protected function parseFixtures(Crawler $crawler): array {
        $node = $crawler->filter('.matchlist');
        if ($node->count() === 0) {
            return [];
        }

        return $node->first()->filter('li.row')->each(function (Crawler $li) {
            $res1 = $this->text($li, '.result .res1');
            $res2 = $this->text($li, '.result .res2');
            $played = ($res1 !== '-' && $res1 !== '' && $res2 !== '-' && $res2 !== '');

            return [
                'date'    => $this->text($li, '.date'),
                'home'    => trim(preg_replace('/\s+/', ' ', $li->filter('.club1 a')->count() ? $li->filter('.club1 a')->text() : '')),
                'home_id' => $li->filter('.club1')->count() ? $li->filter('.club1')->attr('data-id') : null,
                'home_logo' => $li->filter('.club1 img')->count() ? $li->filter('.club1 img')->attr('src') : null,
                'away'    => trim(preg_replace('/\s+/', ' ', $li->filter('.club2 a')->count() ? $li->filter('.club2 a')->text() : '')),
                'away_id' => $li->filter('.club2')->count() ? $li->filter('.club2')->attr('data-id') : null,
                'away_logo' => $li->filter('.club2 img')->count() ? $li->filter('.club2 img')->attr('src') : null,
                'res1'    => $res1,
                'res2'    => $res2,
                'played'  => $played,
                'round'   => $this->text($li, '.competitionround'),
            ];
        });
    }

    protected function text(Crawler $node, string $selector): string {
        $found = $node->filter($selector);
        return $found->count() ? trim($found->first()->text()) : '';
    }

    public function clubId(): ?string {
        $url = \App\Models\Club::value('hns_url');
        if ($url && preg_match('#/klubovi/(\d+)/#', $url, $m)) {
            return $m[1];
        }
        return null;
    }

    public function splitFixtures(array $fixtures): array {
        $played = array_values(array_filter($fixtures, fn ($m) => $m['played']));
        $upcoming = array_values(array_filter($fixtures, fn ($m) => ! $m['played']));

        return [
            'recent'   => array_slice(array_reverse($played), 0, 3), // zadnje odigrane
            'upcoming' => array_slice($upcoming, 0, 3),               // sljedeće
        ];
    }
}