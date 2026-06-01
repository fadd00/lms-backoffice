<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use App\Models\Member;
use App\Models\Borrowing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        $admin = User::create([
            'name' => 'Admin Perpus',
            'email' => 'admin@lms.com',
            'password' => Hash::make('password'),
            'role' => 'Admin'
        ]);

        $pustakawan = User::create([
            'name' => 'Staf Pustakawan',
            'email' => 'staff@lms.com',
            'password' => Hash::make('password'),
            'role' => 'Pustakawan'
        ]);

        // 2. Seed Categories
        $categories = ['Sains', 'Teknologi', 'Fiksi', 'Sejarah', 'Komik'];
        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[] = Category::create(['name' => $cat]);
        }

        // 3. Seed Members
        $members = [
            [
                'member_id' => 'MBR001',
                'name' => 'Ahmad Fadd',
                'email' => 'ahmad@example.com',
                'phone' => '08123456789',
                'address' => 'Jl. Merdeka No. 10'
            ],
            [
                'member_id' => 'MBR002',
                'name' => 'Siti Aminah',
                'email' => 'siti@example.com',
                'phone' => '08567891234',
                'address' => 'Jl. Sudirman Blok B'
            ]
        ];
        $memberModels = [];
        foreach ($members as $mem) {
            $memberModels[] = Member::create($mem);
        }

        // 4. Seed Books
        $books = [
            [
                'book_code' => 'B001',
                'title' => 'Laravel for Beginners',
                'author' => 'Taylor Otwell',
                'publisher' => 'Packt',
                'publication_year' => 2024,
                'stock' => 10,
                'category_id' => $categoryModels[1]->id // Teknologi
            ],
            [
                'book_code' => 'B002',
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'publisher' => 'Scribner',
                'publication_year' => 1925,
                'stock' => 5,
                'category_id' => $categoryModels[2]->id // Fiksi
            ],
            [
                'book_code' => 'B003',
                'title' => 'A Brief History of Time',
                'author' => 'Stephen Hawking',
                'publisher' => 'Bantam',
                'publication_year' => 1988,
                'stock' => 3,
                'category_id' => $categoryModels[0]->id // Sains
            ]
        ];
        $bookModels = [];
        foreach ($books as $book) {
            $bookModels[] = Book::create($book);
        }

        // 5. Seed Borrowings
        Borrowing::create([
            'transaction_code' => 'TRX001',
            'member_id' => $memberModels[0]->id,
            'book_id' => $bookModels[0]->id,
            'borrow_date' => now()->subDays(2)->toDateString(),
            'return_date' => now()->addDays(5)->toDateString(),
            'status' => 'Borrowed',
            'created_by' => $pustakawan->id
        ]);

        Borrowing::create([
            'transaction_code' => 'TRX002',
            'member_id' => $memberModels[1]->id,
            'book_id' => $bookModels[1]->id,
            'borrow_date' => now()->subDays(10)->toDateString(),
            'return_date' => now()->subDays(3)->toDateString(),
            'status' => 'Returned',
            'created_by' => $admin->id
        ]);
    }
}
