<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    protected float $lat = 45.81;
    protected float $lon = 15.98;

    public function current(): ?array
    {
        return Cache::remember('weather_current', now()->addMinutes(30), function () {
            try {
                $response = Http::timeout(10)->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude'  => $this->lat,
                    'longitude' => $this->lon,
                    'current'   => 'temperature_2m,weather_code',
                    'timezone'  => 'Europe/Zagreb',
                ]);

                if ($response->failed()) {
                    return null;
                }

                $current = $response->json('current');

                return [
                    'temp'        => round($current['temperature_2m']),
                    'code'        => $current['weather_code'],
                    'description' => $this->describe($current['weather_code']),
                    'icon'        => $this->icon($current['weather_code']),
                ];
            } catch (\Throwable $e) {
                return null;
            }
        });
    }

    protected function describe(int $code): string
    {
        return match (true) {
            $code === 0            => 'Vedro',
            $code <= 3             => 'Djelomično oblačno',
            in_array($code, [45, 48]) => 'Magla',
            $code >= 51 && $code <= 67 => 'Kiša',
            $code >= 71 && $code <= 77 => 'Snijeg',
            $code >= 80 && $code <= 82 => 'Pljuskovi',
            $code >= 95            => 'Grmljavina',
            default                => 'Promjenjivo',
        };
    }

    protected function icon(int $code): string
    {
        return match (true) {
            $code === 0            => '☀️',
            $code <= 3             => '⛅',
            in_array($code, [45, 48]) => '🌫️',
            $code >= 51 && $code <= 67 => '🌧️',
            $code >= 71 && $code <= 77 => '❄️',
            $code >= 80 && $code <= 82 => '🌦️',
            $code >= 95            => '⛈️',
            default                => '🌡️',
        };
    }
}