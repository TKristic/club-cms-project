<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;


class PlayerController extends Controller {
    public function index() {
        $categories = Category::with(['players' => fn ($q) => $q->orderBy('last_name')])
            ->orderBy('sort_order')
            ->get();

        return view('public.players.index', compact('categories'));
    }
}
