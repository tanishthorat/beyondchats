# BeyondChats — Articles

This repository contains a Laravel backend and a React + Vite frontend for the BeyondChats articles project. The frontend displays scraped articles, allows viewing article details, and provides a "View Original" button to open the source/original article.

---

## Live Links

- Frontend (live): https://beyondchats-gamma.vercel.app/
- Backend API (live): https://beyondchats-rt7s.onrender.com

If you don't have a live frontend link yet, follow the deployment steps below to publish the frontend and paste the URL above.

---

## Local setup (quick)

Prerequisites:
- Node.js >= 18, npm/Yarn
- PHP >= 8.1, Composer
- A running PostgreSQL (or other DB configured in Laravel `.env`)

Backend (Laravel)

1. Open a terminal and go to the backend folder:

```bash
cd backend
```

2. Install PHP dependencies:

```bash
composer install
```

3. Copy the environment file and set DB credentials / other values:

```bash
cp .env.example .env
# then edit .env
```

4. Generate the app key and run migrations (and optionally seed):

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

5. (Optional) Run the Laravel dev server:

```bash
php artisan serve --port=8000
# or use your preferred local server
```

Frontend (React + Vite)

1. Open a second terminal and go to the frontend folder:

```bash
cd frontend
```

2. Install node modules:

```bash
npm install
# or: yarn
```

3. Create `.env` (if you want to override API base url). Example:

```
VITE_API_BASE_URL=https://beyondchats-rt7s.onrender.com
```

4. Run the dev server:

```bash
npm run dev
# open http://localhost:5173 (or the port printed by Vite)
```

5. Build for production:

```bash
npm run build
```

---

## How to check the original vs. updated article

- Open the app and navigate to the articles list.
- Click a card to open the article detail view.
- If the article was scraped from an external site and contains a `source_url`, the detail page shows a `View Original` button in the header — click it to open the original article in a new tab.

Notes: the app sanitizes rendered article HTML (removes inline SVGs and other potentially large embedded assets) to avoid layout breakage. The scraped/original HTML is still accessible via the `source_url` button.

---

## Project architecture / data flow

Simple ASCII diagram to quickly summarize the architecture and data flow:

================================

          +----------------------------+
          |          Browser           |
          +-------------+--------------+
                        |
                        | HTTP Requests (View / Click)
                        v
          +----------------------------+
          |  Frontend (React + Vite)   |
          +-------------+--------------+
                        |
                        | REST API Calls (fetch articles, CRUD)
                        v
          +----------------------------+
          |   Backend (Laravel API)    |
          +-------------+--------------+
                        |
                        | Reads / Writes Articles
                        v
          +----------------------------+
          |     PostgreSQL Database    |
          +----------------------------+

Additional Components
---------------------

          +----------------------------+
          |     Scraper / Worker       |
          | (Script or Laravel Command)|
          +-------------+--------------+
                        |
                        | Fetch external sites, extract
                        | article content and `source_url`
                        v
          +----------------------------+
          |     PostgreSQL Database    |
          +----------------------------+

- The **scraper/worker** fetches external articles, extracts content and `source_url`, and stores them in the database.  
- The **frontend** reads stored articles via the backend API and provides a **"View Original"** action that opens the `source_url` in a new tab.


Key code locations:
- Frontend: `frontend/src` — main components:
  - `components/ArticleList.tsx` — list & skeleton loader
  - `components/ArticleDetail.tsx` — article detail, sanitization, "View Original" button
  - `context/ArticleContext.tsx` — data fetching and pagination
- Backend: `backend/app`, `backend/routes`, `backend/database/migrations` — article model, controllers and migrations

---

## Deploying the frontend (example: Vercel)

1. Connect the `frontend` folder to your Vercel or Netlify project.
2. Set the build command to:

```
npm run build
```

3. Set the output directory to `dist` (Vite default) and add an environment variable:

```
VITE_API_BASE_URL=https://beyondchats-rt7s.onrender.com
```

4. Deploy and then paste the produced URL in the "Frontend (live)" section above.

---
