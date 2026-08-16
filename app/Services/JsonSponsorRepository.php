<?php

namespace App\Services;

class JsonSponsorRepository
{
    protected string $path;

    public function __construct()
    {
        $this->path = storage_path('app/sponsors.json');
    }

    /** READ — svi zapisi */
    public function all(): array
    {
        if (! is_file($this->path)) {
            return [];
        }
        return json_decode(file_get_contents($this->path), true) ?: [];
    }

    /** CREATE */
    public function create(array $data): array
    {
        $items = $this->all();
        $data['id'] = $this->nextId($items);
        $items[] = $data;
        $this->save($items);
        return $data;
    }

    /** UPDATE */
    public function update(int $id, array $data): bool
    {
        $items = $this->all();
        foreach ($items as &$item) {
            if ($item['id'] === $id) {
                $item = array_merge($item, $data, ['id' => $id]);
                $this->save($items);
                return true;
            }
        }
        return false;
    }

    /** DELETE */
    public function delete(int $id): bool
    {
        $items = $this->all();
        $filtered = array_values(array_filter($items, fn ($i) => $i['id'] !== $id));
        if (count($filtered) === count($items)) {
            return false;
        }
        $this->save($filtered);
        return true;
    }

    protected function save(array $items): void
    {
        file_put_contents($this->path, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    protected function nextId(array $items): int
    {
        return empty($items) ? 1 : max(array_column($items, 'id')) + 1;
    }
}