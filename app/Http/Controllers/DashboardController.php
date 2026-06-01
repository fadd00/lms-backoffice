<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Member;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_books' => Book::count(),
            'total_categories' => Category::count(),
            'total_members' => Member::count(),
            'active_borrowings' => Borrowing::where('status', 'Borrowed')->count(),
        ];

        $recent_borrowings = Borrowing::with(['member', 'book'])
            ->latest()
            ->limit(5)
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recent_borrowings' => $recent_borrowings
        ]);
    }
}
