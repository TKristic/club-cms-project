<?php

namespace App\Http\Controllers;

use App\Services\JsonSponsorRepository;
use App\Services\XmlSponsorRepository;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    protected function repo(string $format)
    {
        return $format === 'xml'
            ? app(XmlSponsorRepository::class)
            : app(JsonSponsorRepository::class);
    }

    public function index(string $format = 'json')
    {
        $sponsors = $this->repo($format)->all();
        return view('public.sponsors.index', compact('sponsors', 'format'));
    }

    public function store(Request $request, string $format = 'json')
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'url'  => 'nullable|url|max:255',
        ]);
        $this->repo($format)->create($data);

        return redirect()->route('sponsors.index', $format)->with('success', 'Sponzor dodan.');
    }

    public function update(Request $request, string $format, int $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'url'  => 'nullable|url|max:255',
        ]);
        $this->repo($format)->update($id, $data);

        return redirect()->route('sponsors.index', $format)->with('success', 'Sponzor ažuriran.');
    }

    public function destroy(string $format, int $id)
    {
        $this->repo($format)->delete($id);

        return redirect()->route('sponsors.index', $format)->with('success', 'Sponzor obrisan.');
    }
}