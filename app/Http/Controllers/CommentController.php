<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'rating'     => 'required|integer|min:1|max:5',
            'body'       => 'required|string|min:10|max:1000',
        ]);

        // Un seul commentaire par user par voiture
        Comment::updateOrCreate(
            ['vehicle_id' => $validated['vehicle_id'], 'user_id' => auth()->id()],
            ['rating' => $validated['rating'], 'body' => $validated['body']]
        );

        return back()->with('success', 'Votre avis a été publié !');
    }
}
