<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function index(Request $request)
    {
        $query = License::query();
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $licenses = $query->orderBy('id', 'desc')->paginate(20);
        return view('licenses.index', compact('licenses'));
    }

    public function create()
    {
        return view('licenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);
        License::create($validated);
        return redirect()->route('licenses.index')->with('success', 'Licence créée avec succès.');
    }

    public function edit(License $license)
    {
        return view('licenses.edit', compact('license'));
    }

    public function update(Request $request, License $license)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);
        $license->update($validated);
        return redirect()->route('licenses.index')->with('success', 'Licence modifiée avec succès.');
    }

    public function destroy(License $license)
    {
        $license->delete();
        return redirect()->route('licenses.index')->with('success', 'Licence supprimée.');
    }
} 