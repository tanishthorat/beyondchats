# backend/app

Place your Laravel Models and Controllers here.

Supabase (Postgres) notes
-------------------------

- Update `../.env` to use the `pgsql` driver and your Supabase connection values.
- Ensure PHP has the `pdo_pgsql` extension enabled (`php -m | grep pdo_pgsql`).
- Run migrations with `php artisan migrate` after configuring the DB.

Scraper command
---------------

This project includes a console command scaffold at `app/Console/Commands/ScrapeOldestArticles.php` which demonstrates scraping the last page and inserting the oldest 5 articles into the `articles` table. Install Goutte before running the command:

composer require weidner/goutte

Then run:

php artisan scrape:oldest

Adjust selectors and the listing URL inside the command to match the target site.

Production (recommended)
------------------------

Use the Supabase Transaction Pooler and require SSL to avoid connection exhaustion and enforce secure connections in production. Update `backend/.env` with values from your Supabase dashboard; a production-ready snippet looks like:

```
# Use the transaction pooler host (check Project Settings > Database > Connection Pooling)
DB_CONNECTION=pgsql
DB_HOST=aws-0-[region].pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.your-project-ref
DB_PASSWORD=your_supabase_password_here
DB_SSLMODE=require
```

Also verify `config/database.php` has the `sslmode` entry in the `pgsql` connection block:

```
'sslmode' => env('DB_SSLMODE', 'prefer'),
```

This ensures Laravel uses the enforced SSL mode at runtime.
