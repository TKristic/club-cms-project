<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;

class SmtpSettingsService
{
    protected string $path;

    public function __construct()
    {
        $this->path = storage_path('app/smtp.ini');
    }

    public function all(bool $decrypt = false): array {
        $defaults = [
            'host' => '', 'port' => '587', 'username' => '',
            'password' => '', 'encryption' => 'tls',
            'from_address' => '', 'from_name' => '',
        ];

        if (! is_file($this->path)) {
            return $defaults;
        }

        $parsed = parse_ini_file($this->path, false, INI_SCANNER_RAW);
        $data = array_merge($defaults, is_array($parsed) ? $parsed : []);

        if ($decrypt) {
            foreach ($data as $key => $value) {
                if ($value === '') {
                    continue;
                }
                try {
                    $data[$key] = Crypt::decryptString($value);
                } catch (\Throwable $e) {
                    // ako nije sifrirano
                }
            }
        }

        return $data;
    }

    public function save(array $data): void
    {
        $existing = $this->all(); 

        if (empty($data['password'])) {
            $data['password'] = null;
        }

        $fields = ['host', 'port', 'username', 'password', 'encryption', 'from_address', 'from_name'];
        $lines = ['; SMTP postavke kluba — sve vrijednosti AES-šifrirane'];

        foreach ($fields as $field) {
            if ($field === 'password' && $data['password'] === null) {
                $encrypted = $existing['password'] ?? '';
            } else {
                $value = (string) ($data[$field] ?? '');
                $encrypted = $value === '' ? '' : Crypt::encryptString($value);
            }

            $lines[] = $field . ' = ' . $this->q($encrypted);
        }

        file_put_contents($this->path, implode("\n", $lines) . "\n");
    }

    protected function q(string $value): string {
        return '"' . str_replace('"', '\"', $value) . '"';
    }
}