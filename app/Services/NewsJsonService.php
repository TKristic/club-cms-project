<?php

namespace App\Services;

use App\Models\Club;
use App\Models\News;
use Illuminate\Support\Str;

class NewsJsonService {
    public function export(): string {
        $news = News::latest('published_at')->get()->map(fn ($n) => [
            'title'        => $n->title,
            'excerpt'      => $n->excerpt,
            'body'         => $n->body,
            'published_at' => $n->published_at?->toDateString(),
            'author'       => $n->author?->name,
        ]);

        return json_encode($news, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function import(string $json): int {
        $items = json_decode($json, true);

        if (! is_array($items)) {
            throw new \RuntimeException('Neispravan JSON format.');
        }

        $clubId = Club::value('id') ?? 1;
        $count = 0;

        foreach ($items as $item) {
            $title = trim($item['title'] ?? '');
            $body  = trim($item['body'] ?? '');

            if ($title === '' || $body === '') {
                continue;
            }

            try {
                News::create([
                    'club_id'      => $clubId,
                    'user_id'      => auth()->id(),
                    'title'        => $title,
                    'slug'         => Str::slug($title) . '-' . Str::random(5),
                    'excerpt'      => $item['excerpt'] ?? null,
                    'body'         => $body,
                    'published_at' => $item['published_at'] ?? now(),
                ]);
                $count++;
            } catch (\Throwable $e) {
                continue;
            }
        }

        return $count;
    }
}