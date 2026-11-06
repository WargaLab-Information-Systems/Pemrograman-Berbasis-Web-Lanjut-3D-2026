# Copilot instructions

## Build, test, and lint

- Install PHP dependencies with `composer install`; install frontend dependencies with `npm install`.
- Run the Laravel test suite with `composer test` (or `php artisan test`). Run one file with `php artisan test tests/Feature/ExampleTest.php` or `php artisan test tests/Unit/ExampleTest.php`; narrow further with `php artisan test --filter=test_the_application_returns_a_successful_response`.
- Check PHP formatting with `vendor/bin/pint --test`; apply formatting with `vendor/bin/pint`.
- Build frontend assets with `npm run build`; start Vite in watch mode with `npm run dev`.
- `composer dev` starts the Laravel server, queue listener, log viewer, and Vite together. `composer setup` is the repository's initial setup script; it installs dependencies, creates the environment file and app key, migrates, and builds assets.
- There is no npm lint or frontend test script in `package.json`.

## Architecture

- This is a Laravel 13 / PHP 8.3 application. `routes/web.php` defines the named browser routes: `/` renders the home page, while `/buku` and `/buku/{id}` are handled by `BukuController`.
- `BukuController` retrieves Eloquent book records and supplies them to the listing and detail Blade views. The shared `layouts.app` layout provides navigation and loads `resources/css/app.css` through Vite. `x-kartu-buku` is the reusable book-card component.
- The library domain is represented by `Buku`, `Kategori`, `Anggota`, and `Peminjaman`. A category has many books; a book belongs to a category and has many loans; a member has many loans; and each loan belongs to a member and a book. Schema and relationship details live in `database/migrations` and the model classes.
- The persisted category table is named `categorys` (not Laravel's conventional `kategoris`), and the models explicitly set their table names and relationship foreign keys. Preserve these mappings when changing the schema or models; do not rename tables as a side effect of unrelated work.
- `DatabaseSeeder` currently calls the category and book factories directly with `count(10)->create()`. It is in the `Database\Seeders` namespace, so model factory calls must import `App\Models\Kategori` and `App\Models\Buku`. `BukuSeeder` only updates category IDs on existing books; it does not create book rows.

## Repository conventions and integration points

- Domain names and database columns use Indonesian (`Buku`, `Kategori`, `Anggota`, `Peminjaman`; for example `judul`, `penulis`, `tahun_terbit`, `kategori_id`). Follow the existing domain vocabulary and explicit Eloquent mappings.
- Browser navigation uses named routes (`home`, `buku.index`, `buku.show`); use `route(...)` in Blade links rather than hard-coded paths.
- Views compose pages with `layouts.app`, `@section('content')`, and the `x-kartu-buku` component. The card and detail templates currently expect `tahun` and `kategori`, while the database/model fields are `tahun_terbit` and `kategori_id` with a `kategori` relationship. When changing this flow, explicitly align the controller's data (including loading the category relationship) with the view contract rather than assuming raw Eloquent models provide those template keys.
- PHPUnit is configured in `phpunit.xml` to use in-memory SQLite, array cache/session/mail, and synchronous queues. Feature tests extend `Tests\TestCase`; unit tests use PHPUnit directly. Existing tests are framework examples, so add coverage around the affected route or behavior when expanding the application.
- Frontend assets are built by Vite with the Laravel and Tailwind CSS Vite plugins. The source entry points are `resources/css/app.css` and `resources/js/app.js`; use the existing Vite asset pipeline for changes to them.
