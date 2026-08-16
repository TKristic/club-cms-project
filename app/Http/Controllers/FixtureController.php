<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fixture;

class FixtureController extends Controller
{
    
    public function index() {
        $upcoming = Fixture::upcoming()->with('category')
            ->orderBy('kickoff_at')->get();

        $results = Fixture::played()->with('category')
            ->orderByDesc('kickoff_at')->get();

        return view('public.fixtures.index', compact('upcoming', 'results'));
    }
}
