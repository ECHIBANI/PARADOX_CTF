<?php
namespace App\Http\Controllers;

use App\Models\Vehicle;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Vehicle::where('available', true)->take(3)->get();
        $comments = \App\Models\Comment::with('user')->orderByDesc('rating')->orderByDesc('created_at')->take(3)->get();
        return view('front.home', compact('featured', 'comments'));
    }
}
