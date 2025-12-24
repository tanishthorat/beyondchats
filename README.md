# BeyondChats Assignment

CRITICAL: Setup instructions & Architecture Diagram

- **Phases:**
  - Phase 1 (backend): Laravel API — see [backend/.env](backend/.env) and [backend/routes/api.php](backend/routes/api.php)
  - Phase 2 (worker): Node.js Search/Scrape/LLM — see [worker/index.js](worker/index.js) and [worker/.env](worker/.env)
  - Phase 3 (frontend): React + Tailwind — see [frontend/src/App.jsx](frontend/src/App.jsx) and [frontend/tailwind.config.js](frontend/tailwind.config.js)

Setup (quick):

- Backend: create a Laravel project in the `backend` folder, copy `.env` values and configure DB.
- Worker: run `npm install` in `worker` and add your `OPENAI_API_KEY` / `SERPAPI_KEY` to `worker/.env`.
- Frontend: run `npm install` in `frontend`, then `npm run start` to launch the dev server.

Architecture Diagram (high level):

Search -> Worker Scraper -> LLM -> Backend (store results) -> Frontend (display)

