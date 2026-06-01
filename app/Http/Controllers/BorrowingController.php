<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $book = Book::findOrFail($request->book_id);

            // Validasi stok
            if ($book->stock < 1) {
                throw new \Exception('Stok buku habis.');
            }

            // Kurangi stok
            $book->decrement('stock');

            // Insert ke borrowings
            Borrowing::create([
                'transaction_code' => $request->transaction_code,
                'member_id' => $request->member_id,
                'book_id' => $request->book_id,
                'borrow_date' => $request->borrow_date,
                'return_date' => $request->return_date,
                'status' => 'Borrowed',
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()->back()->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function returnBook($id)
    {
        DB::transaction(function () use ($id) {
            $borrowing = Borrowing::findOrFail($id);

            if ($borrowing->status === 'Returned') {
                throw new \Exception('Buku sudah dikembalikan sebelumnya.');
            }

            $borrowing->update(['status' => 'Returned']);
            $borrowing->book()->increment('stock');
        });

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan.');
    }
}
