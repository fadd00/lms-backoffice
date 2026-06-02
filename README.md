# LMS Backoffice (Library Management System)

A modern, full-featured Library Management System built with **Laravel 13**, **Inertia.js**, **Vue 3**, and **Shadcn Vue**.

## 🚀 Key Features

- **Automated Authentication**: Register and Login system.
- **Role-based Access Control**:
  - **Admin**: Full access including User Management and Approval.
  - **Pustakawan (Librarian)**: Manage Assets (Books/Categories/Members) and Transactions.
  - **Guest**: Restricted view after registration until approved by Admin.
- **Library Management**:
  - CRUD for Books (with Stock management).
  - CRUD for Categories.
  - CRUD for Members.
- **Transaction Engine**:
  - Integrated Borrowing and Returning flows.
  - Automatic Stock decrement on borrowing and increment on returning.
  - Transaction History with unique codes.
- **Modern UI/UX**:
  - Responsive design using Tailwind CSS.
  - Smooth SPA experience with Inertia.js.
  - Accessible components from Shadcn Vue.

## 🛠️ Prerequisites

Before you begin, ensure you have the following installed:
- **PHP** >= 8.5
- **Composer** (PHP dependency manager)
- **Node.js** & **NPM**
- **SQLite** (or any database of your choice)

## 🏁 Getting Started

Follow these steps to set up the project locally:

### 1. Clone & Install Dependencies

```bash
# Clone the repository
git clone <your-repo-url>
cd lms-backoffice

# Install PHP dependencies
composer install

# Install JS dependencies
npm install
```

### 2. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Database Set Up

By default, the project uses **SQLite**.
1. Create a database file: `touch database/database.sqlite`
2. Update your `.env` to use the sqlite connection.
3. Run migrations and seed the data:

```bash
php artisan migrate:fresh --seed
```

**Default Admin Credentials:**
- Email: `admin@lms.com`
- Password: `password`

### 4. Running the Application

You need to run **two** separate terminals:

**Terminal 1: Laravel Backend**
```bash
php artisan serve
```

**Terminal 2: Vite Dev Server (Frontend)**
```bash
npm run dev
```

The application will be accessible at `http://127.0.0.1:8000`.

## 📂 Project Structure Highlights

- `app/Http/Controllers/`: Contains the logic for Books, Members, Users, and Borrowings.
- `app/Http/Middleware/CheckApprovedUser.php`: Forces new registrants to wait for Admin approval.
- `resources/js/pages/`: Vue components for each module.
- `database/migrations/`: Database schema definitions.

## 📜 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
