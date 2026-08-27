<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Services\HnsScraperService;

class HomeController extends Controller
{
    public function index(HnsScraperService $hns, \App\Services\WeatherService $weather)
    {
        $news = News::published()->latest('published_at')->take(3)->get();

        $hnsData = $hns->data();
        $split   = ($hnsData['ok'] ?? false)
            ? $hns->splitFixtures($hnsData['fixtures'])
            : ['recent' => [], 'upcoming' => []];

        return view('public.home', [
            'news'      => $news,
            'hns'       => $hnsData,
            'clubHnsId' => $hns->clubId(),
            'recent'    => $split['recent'],
            'upcoming'  => $split['upcoming'],
            'weather'   => $weather->current(),
        ]);
    }
}
