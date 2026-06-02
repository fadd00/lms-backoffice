<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class BorrowingController extends Controller
{
    public function index()
    {
        return Inertia::render('borrowings/Index', [
            'borrowings' => Borrowing::with(['member', 'book'])->latest()->get(),
            'books' => Book::where('stock', '>', 0)->get(),
            'members' => Member::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date',
            'return_date' => 'required|date|after:borrow_date',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $book = Book::findOrFail($request->book_id);

                if ($book->stock < 1) {
                    throw new \Exception('Stok buku habis.');
                }

                $book->decrement('stock');

                $transaction_code = 'TRX-' . strtoupper(uniqid());

                Borrowing::create([
                    'transaction_code' => $transaction_code,
                    'member_id' => $request->member_id,
                    'book_id' => $request->book_id,
                    'borrow_date' => $request->borrow_date,
                    'return_date' => $request->return_date,
                    'status' => 'Borrowed',
                    'created_by' => auth()->id(),
                ]);
            });

            return redirect()->back()->with('success', 'Peminjaman berhasil dicatat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function returnBook($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $borrowing = Borrowing::findOrFail($id);

                if ($borrowing->status === 'Returned') {
                    throw new \Exception('Buku sudah dikembalikan sebelumnya.');
                }

                $borrowing->update(['status' => 'Returned']);
                $borrowing->book()->increment('stock');
            });

            return redirect()->back()->with('success', 'Buku berhasil dikembalikan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Borrowing $borrowing)
    {
        if ($borrowing->status === 'Borrowed') {
            $borrowing->book()->increment('stock');
        }
        
        $borrowing->delete();
        return redirect()->back()->with('success', 'Data transaksi berhasil dihapus.');
    }
}
