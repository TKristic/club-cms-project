<?php

namespace App\Services;

use SimpleXMLElement;

class XmlSponsorRepository
{
    protected string $path;

    public function __construct()
    {
        $this->path = storage_path('app/sponsors.xml');
    }

    protected function load(): SimpleXMLElement
    {
        if (! is_file($this->path)) {
            return new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><sponsors/>');
        }
        return simplexml_load_file($this->path);
    }

    /** READ */
    public function all(): array
    {
        $xml = $this->load();
        $result = [];
        foreach ($xml->sponsor as $s) {
            $result[] = [
                'id'   => (int) $s['id'],
                'name' => (string) $s->name,
                'url'  => (string) $s->url,
            ];
        }
        return $result;
    }

    /** CREATE */
    public function create(array $data): array
    {
        $xml = $this->load();
        $ids = [];
        foreach ($xml->sponsor as $s) {
            $ids[] = (int) $s['id'];
        }
        $id = empty($ids) ? 1 : max($ids) + 1;

        $node = $xml->addChild('sponsor');
        $node->addAttribute('id', (string) $id);
        $node->addChild('name', htmlspecialchars($data['name']));
        $node->addChild('url', htmlspecialchars($data['url'] ?? ''));

        $this->save($xml);
        return ['id' => $id] + $data;
    }

    /** UPDATE */
    public function update(int $id, array $data): bool
    {
        $xml = $this->load();
        foreach ($xml->sponsor as $s) {
            if ((int) $s['id'] === $id) {
                $s->name = $data['name'] ?? (string) $s->name;
                $s->url  = $data['url'] ?? (string) $s->url;
                $this->save($xml);
                return true;
            }
        }
        return false;
    }

    /** DELETE */
    public function delete(int $id): bool
    {
        $xml = $this->load();
        $index = 0;
        foreach ($xml->sponsor as $s) {
            if ((int) $s['id'] === $id) {
                unset($xml->sponsor[$index]);
                $this->save($xml);
                return true;
            }
            $index++;
        }
        return false;
    }

    protected function save(SimpleXMLElement $xml): void
    {
        // uredno formatiran ispis
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        $dom->save($this->path);
    }
}