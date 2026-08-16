<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index() {
        $galleries = Gallery::withCount('media')->with('media')->latest()->get();
        return view('public.gallery.index', compact('galleries'));
    }

    public function show(Gallery $gallery) {
        $gallery->load('media');
        return view('public.gallery.show', compact('gallery'));
    }
}
