<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class WaitingRoomController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'Guest') {
            return redirect('/dashboard');
        }

        return Inertia::render('WaitingRoom');
    }
}