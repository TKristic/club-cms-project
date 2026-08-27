<?php

namespace App\Services;

use App\Models\Club;
use App\Models\News;
use Illuminate\Support\Str;

class NewsJsonService
{
    /** Izvoz svih vijesti u JSON string (pisanje niza zapisa). */
    public function export(): string
    {
        $news = News::latest('published_at')->get()->map(fn ($n) => [
            'title'        => $n->title,
            'excerpt'      => $n->excerpt,
            'body'         => $n->body,
            'published_at' => $n->published_at?->toDateString(),
            'author'       => $n->author?->name,
        ]);

        return json_encode($news, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /** Uvoz iz JSON stringa (čitanje niza zapisa). Vraća broj uvezenih. */
    public function import(string $json): int
    {
        $items = json_decode($json, true);

        if (! is_array($items)) {
            throw new \RuntimeException('Neispravan JSON format.');
        }

        $clubId = Club::value('id') ?? 1;
        $count = 0;

        foreach ($items as $item) {
            if (empty($item['title'])) {
                continue;
            }

            News::create([
                'club_id'      => $clubId,
                'user_id'      => auth()->id(),
                'title'        => $item['title'],
                'slug'         => Str::slug($item['title']) . '-' . Str::random(5),
                'excerpt'      => $item['excerpt'] ?? null,
                'body'         => $item['body'] ?? '',
                'published_at' => $item['published_at'] ?? now(),
            ]);
            $count++;
        }

        return $count;
    }
}