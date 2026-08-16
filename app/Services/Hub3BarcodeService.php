<?php

namespace App\Services;

use Le\PaymentBarcodeGenerator\Data;
use Le\PaymentBarcodeGenerator\Generator;
use Le\PaymentBarcodeGenerator\Party;
use Le\PDF417\PDF417;
use Le\PDF417\Renderer\ImageRenderer;

class Hub3BarcodeService
{
    /**
     * Vraća PNG HUB-3A barkod kao data-URI (base64) za <img src="...">.
     *
     * @param array{
     *   amount: float, payer_name: string, payer_address?: string, payer_city?: string,
     *   payee_name: string, payee_address?: string, payee_city?: string,
     *   iban: string, model?: string, reference: string, description: string
     * } $p
     */
    public function dataUri(array $p): string
    {

        $p = array_merge([
            'payer_name'  => 'Clan kluba',
            'payer_address' => '', 'payer_city' => '',
            'payee_name'  => 'Klub',
            'payee_address' => '', 'payee_city' => '',
            'iban' => '', 'model' => 'HR00',
            'reference' => '0', 'description' => 'Clanarina',
            'amount' => 0,
        ], $p);

    // ... ostatak metode (new Data(...), Generator, render) ostaje isti
        $data = new Data(
            payer: new Party(
                name: $p['payer_name'],
                address: $p['payer_address'] ?? '',
                city: $p['payer_city'] ?? '',
            ),
            payee: new Party(
                name: $p['payee_name'],
                address: $p['payee_address'] ?? '',
                city: $p['payee_city'] ?? '',
            ),
            iban: $p['iban'],
            currency: 'EUR',
            amount: (int) round($p['amount'] * 100),   // EUR → centi
            model: $p['model'] ?? 'HR00',
            reference: $p['reference'],
            code: 'COST',
            description: $p['description'],
        );

        $generator = new Generator(
            pdf417: new PDF417(),
            renderer: new ImageRenderer(['format' => 'data-url', 'scale' => 3, 'ratio' => 3]),
        );

        // render($data) vraća Intervention\Image\Image; s 'data-url' formatom to je base64 PNG
        return (string) $generator->render($data);
    }
}