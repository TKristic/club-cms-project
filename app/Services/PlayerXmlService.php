<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Club;
use App\Models\Player;
use SimpleXMLElement;

class PlayerXmlService {
    public function export(int $categoryId): string {
        $players = Player::where('category_id', $categoryId)->get();

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><players/>');

        foreach ($players as $p) {
            $node = $xml->addChild('player');
            $node->addChild('first_name', htmlspecialchars($p->first_name ?? ''));
            $node->addChild('last_name', htmlspecialchars($p->last_name ?? ''));
            $node->addChild('email', htmlspecialchars($p->email ?? ''));
            $node->addChild('position', htmlspecialchars($p->position ?? ''));
            $node->addChild('birth_date', $p->birth_date?->toDateString() ?? '');
            $node->addChild('jersey_number', $p->jersey_number ?? '');
        }

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        return $dom->saveXML();
    }

    public function import(string $xmlString, int $categoryId): int {
        $xml = simplexml_load_string($xmlString);

        if ($xml === false) {
            throw new \RuntimeException('Neispravan XML format.');
        }

        $clubId = Club::value('id') ?? 1;
        $count = 0;

        foreach ($xml->player as $p) {
            $firstName = trim((string) $p->first_name);
            $email     = trim((string) $p->email);

            if ($firstName === '' || $email === '') {
                continue;
            }

            try {
                Player::create([
                    'club_id'       => $clubId,
                    'category_id'   => $categoryId,
                    'first_name'    => $firstName,
                    'last_name'     => trim((string) $p->last_name),
                    'email'         => $email,
                    'position'      => trim((string) $p->position) ?: null,
                    'birth_date'    => trim((string) $p->birth_date) ?: null,
                    'jersey_number' => trim((string) $p->jersey_number) ?: null,
                ]);
                $count++;
            } catch (\Throwable $e) {
                continue;
            }
        }

        return $count;
    }
}