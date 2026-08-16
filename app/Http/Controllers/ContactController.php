<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Club;
use App\Models\ContactMessage;

class ContactController extends Controller
{

public function create()
{
    return view('public.contact.create');
}

public function store(Request $request)
{
    $data = $request->validate([
        'name'    => 'required|string|max:255',
        'email'   => 'required|email|max:255',
        'subject' => 'nullable|string|max:255',
        'message' => 'required|string|max:2000',
    ]);

    $data['club_id'] = Club::value('id') ?? 1;
    ContactMessage::create($data);

    return redirect()->route('contact.create')
        ->with('success', 'Poruka je poslana! Hvala na javljanju.');
}
}
