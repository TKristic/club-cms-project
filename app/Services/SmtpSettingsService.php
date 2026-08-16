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

    /** Čita postavke iz INI datoteke (#4 — čitanje). */
    public function all(bool $decryptPassword = false): array
    {
        $defaults = [
            'host' => '', 'port' => '587', 'username' => '',
            'password' => '', 'encryption' => 'tls',
            'from_address' => '', 'from_name' => '',
        ];

        if (! is_file($this->path)) {
            return $defaults;
        }

        $data = array_merge($defaults, parse_ini_file($this->path) ?: []);

        if ($decryptPassword && ! empty($data['password'])) {
            try {
                $data['password'] = Crypt::decryptString($data['password']); // AES dešifriranje (#23)
            } catch (\Throwable $e) {
                $data['password'] = '';
            }
        }

        return $data;
    }

    /** Piše postavke u INI datoteku; lozinku AES-šifrira (#4 pisanje + #23). */
    public function save(array $data): void
    {
        $existing = $this->all();

        // ako lozinka nije unesena, zadrži postojeću (već šifriranu)
        $password = $existing['password'];
        if (! empty($data['password'])) {
            $password = Crypt::encryptString($data['password']); // AES šifriranje (#23)
        }

        $lines = [
            '; SMTP postavke kluba — generirano automatski',
            'host = ' . $this->q($data['host'] ?? ''),
            'port = ' . $this->q($data['port'] ?? '587'),
            'username = ' . $this->q($data['username'] ?? ''),
            'password = ' . $this->q($password),
            'encryption = ' . $this->q($data['encryption'] ?? 'tls'),
            'from_address = ' . $this->q($data['from_address'] ?? ''),
            'from_name = ' . $this->q($data['from_name'] ?? ''),
        ];

        file_put_contents($this->path, implode("\n", $lines) . "\n");
    }

    protected function q(string $value): string
    {
        return '"' . str_replace('"', '\"', $value) . '"';
    }
}