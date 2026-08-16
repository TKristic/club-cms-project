<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Club;
use App\Models\Enrollment;


class EnrollmentController extends Controller
{
    
    public function create()
    {
        return view('public.enroll.create');
    }

    public function review(Request $request)
    {
        $data = $request->validate([
            'child_first_name' => 'required|string|max:255',
            'child_last_name'  => 'required|string|max:255',
            'child_birth_date' => 'required|date|before:today',
            'parent_name'      => 'required|string|max:255',
            'parent_email'     => 'required|email|max:255',
            'parent_phone'     => 'required|string|max:255',
            'note'             => 'nullable|string|max:1000',
        ]);

        return view('public.enroll.review', compact('data'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'child_first_name' => 'required|string|max:255',
            'child_last_name'  => 'required|string|max:255',
            'child_birth_date' => 'required|date|before:today',
            'parent_name'      => 'required|string|max:255',
            'parent_email'     => 'required|email|max:255',
            'parent_phone'     => 'required|string|max:255',
            'note'             => 'nullable|string|max:1000',
        ]);

        $data['club_id'] = Club::value('id') ?? 1;
        $data['status'] = 'novo';
        Enrollment::create($data);

        return redirect()->route('enroll.create')
            ->with('success', 'Zahtjev za upis je zaprimljen! Javit ćemo vam se uskoro.');
    }
}
