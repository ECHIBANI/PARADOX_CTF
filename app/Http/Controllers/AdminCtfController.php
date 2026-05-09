<?php

namespace App\Http\Controllers;

use App\Models\CtfChallenge;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminCtfController extends Controller
{
    public function index()
    {
        $challenges = CtfChallenge::orderBy('created_at', 'desc')->get();
        return view('admin.ctf.index', compact('challenges'));
    }

    public function create()
    {
        return view('admin.ctf.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'difficulty' => 'required|in:easy,medium,hard',
            'points' => 'required|integer|min:0',
            'description' => 'required|string',
            'statement' => 'required|string',
            'flag' => 'required|string|max:255',
            'theme' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'file_1' => 'nullable|file|max:20480', // 20MB max pour les fichiers
            'file_2' => 'nullable|file|max:20480',
            'hint_1_title' => 'nullable|string|max:255',
            'hint_1_content' => 'nullable|string',
            'hint_2_title' => 'nullable|string|max:255',
            'hint_2_content' => 'nullable|string',
            'is_visible' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['flag_hash'] = Hash::make($validated['flag']);
        $validated['is_visible'] = $request->has('is_visible');
        unset($validated['flag']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->move(public_path('images/ctf'), $request->file('image')->getClientOriginalName());
            $validated['image'] = $request->file('image')->getClientOriginalName();
        }
        
        if ($request->hasFile('file_1')) {
            $filename = time() . '_1_' . $request->file('file_1')->getClientOriginalName();
            $request->file('file_1')->move(public_path('uploads/ctf'), $filename);
            $validated['file_1'] = 'uploads/ctf/' . $filename;
        }

        if ($request->hasFile('file_2')) {
            $filename = time() . '_2_' . $request->file('file_2')->getClientOriginalName();
            $request->file('file_2')->move(public_path('uploads/ctf'), $filename);
            $validated['file_2'] = 'uploads/ctf/' . $filename;
        }

        CtfChallenge::create($validated);

        return redirect()->route('admin.ctf.index')->with('success', 'Challenge créé avec succès.');
    }

    public function edit(CtfChallenge $challenge)
    {
        return view('admin.ctf.form', compact('challenge'));
    }

    public function update(Request $request, CtfChallenge $challenge)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'difficulty' => 'required|in:easy,medium,hard',
            'points' => 'required|integer|min:0',
            'description' => 'required|string',
            'statement' => 'required|string',
            'flag' => 'nullable|string|max:255', // Optional on update
            'theme' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'file_1' => 'nullable|file|max:20480',
            'file_2' => 'nullable|file|max:20480',
            'hint_1_title' => 'nullable|string|max:255',
            'hint_1_content' => 'nullable|string',
            'hint_2_title' => 'nullable|string|max:255',
            'hint_2_content' => 'nullable|string',
            'is_visible' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_visible'] = $request->has('is_visible');

        if (!empty($validated['flag'])) {
            $validated['flag_hash'] = Hash::make($validated['flag']);
        }
        unset($validated['flag']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->move(public_path('images/ctf'), $request->file('image')->getClientOriginalName());
            $validated['image'] = $request->file('image')->getClientOriginalName();
        }

        if ($request->hasFile('file_1')) {
            $filename = time() . '_1_' . $request->file('file_1')->getClientOriginalName();
            $request->file('file_1')->move(public_path('uploads/ctf'), $filename);
            $validated['file_1'] = 'uploads/ctf/' . $filename;
        }

        if ($request->hasFile('file_2')) {
            $filename = time() . '_2_' . $request->file('file_2')->getClientOriginalName();
            $request->file('file_2')->move(public_path('uploads/ctf'), $filename);
            $validated['file_2'] = 'uploads/ctf/' . $filename;
        }

        $challenge->update($validated);

        return redirect()->route('admin.ctf.index')->with('success', 'Challenge mis à jour avec succès.');
    }

    public function destroy(CtfChallenge $challenge)
    {
        $challenge->delete();
        return redirect()->route('admin.ctf.index')->with('success', 'Challenge supprimé avec succès.');
    }

    public function toggleVisibility(CtfChallenge $challenge)
    {
        $challenge->update(['is_visible' => !$challenge->is_visible]);
        return back()->with('success', 'Visibilité du challenge mise à jour.');
    }
}
