<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'BeyondChats') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            background: #18181b;
            min-height: 100vh;
            color: #a1a1aa;
            padding: 3rem 1.5rem;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 3rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #27272a;
        }

        .header h1 {
            font-size: 1.5rem;
            font-weight: 400;
            color: #e4e4e7;
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .header .subtitle {
            font-size: 0.875rem;
            color: #71717a;
            font-weight: 400;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            font-size: 0.75rem;
            color: #a1a1aa;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            background: #22c55e;
            border-radius: 50%;
        }

        .section {
            margin-bottom: 3rem;
        }

        .section-title {
            font-size: 0.875rem;
            font-weight: 400;
            color: #e4e4e7;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .endpoints {
            display: flex;
            flex-direction: column;
            gap: 1px;
            background: #27272a;
            border: 1px solid #27272a;
        }

        .endpoint {
            background: #18181b;
            padding: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            transition: background 0.2s;
        }

        .endpoint:hover {
            background: #1f1f23;
        }

        .method {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 3px;
            min-width: 4rem;
            text-align: center;
            flex-shrink: 0;
        }

        .method.get {
            background: #065f46;
            color: #6ee7b7;
        }

        .method.post {
            background: #1e40af;
            color: #93c5fd;
        }

        .method.put {
            background: #92400e;
            color: #fcd34d;
        }

        .method.delete {
            background: #991b1b;
            color: #fca5a5;
        }

        .endpoint-content {
            flex: 1;
            min-width: 0;
        }

        .endpoint-path {
            font-size: 0.875rem;
            color: #e4e4e7;
            margin-bottom: 0.5rem;
            word-break: break-all;
        }

        .endpoint-description {
            font-size: 0.8125rem;
            color: #71717a;
        }

        .icon-svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .quick-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1px;
            background: #27272a;
            border: 1px solid #27272a;
            margin-bottom: 3rem;
        }

        .link-item {
            background: #18181b;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: #a1a1aa;
            transition: background 0.2s, color 0.2s;
        }

        .link-item:hover {
            background: #1f1f23;
            color: #e4e4e7;
        }

        .link-item svg {
            color: #71717a;
            transition: color 0.2s;
        }

        .link-item:hover svg {
            color: #a1a1aa;
        }

        .link-text {
            font-size: 0.875rem;
        }

        .info-grid {
            display: flex;
            flex-direction: column;
            gap: 1px;
            background: #27272a;
            border: 1px solid #27272a;
        }

        .info-item {
            background: #18181b;
            padding: 1.25rem;
            display: flex;
            gap: 1rem;
        }

        .info-item svg {
            color: #71717a;
            flex-shrink: 0;
        }

        .info-content {
            flex: 1;
        }

        .info-title {
            font-size: 0.875rem;
            color: #e4e4e7;
            margin-bottom: 0.25rem;
        }

        .info-description {
            font-size: 0.8125rem;
            color: #71717a;
        }

        code {
            background: #27272a;
            padding: 0.125rem 0.375rem;
            border-radius: 3px;
            font-size: 0.8125rem;
            color: #a1a1aa;
        }

        @media (max-width: 640px) {
            body {
                padding: 2rem 1rem;
            }

            .endpoint {
                flex-direction: column;
            }

            .method {
                align-self: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name', 'BeyondChats') }}</h1>
            <p class="subtitle">BeyondChats Article Management API</p>
            <div class="status-badge">
                <span class="status-dot"></span>
                <span>ONLINE</span>
            </div>
        </div>

        <div class="quick-links">
            <a href="/api/articles" class="link-item">
                <svg class="icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="link-text">Browse Articles</span>
            </a>
            
            <a href="https://beyondchats.com/blogs" target="_blank" class="link-item">
                <svg class="icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                </svg>
                <span class="link-text">Source Blog</span>
            </a>
        </div>

        <div class="section">
            <h2 class="section-title">Endpoints</h2>
            
            <div class="endpoints">
                <div class="endpoint">
                    <span class="method get">GET</span>
                    <div class="endpoint-content">
                        <div class="endpoint-path">/api/articles</div>
                        <div class="endpoint-description">Retrieve paginated list of all articles</div>
                    </div>
                </div>

                <div class="endpoint">
                    <span class="method get">GET</span>
                    <div class="endpoint-content">
                        <div class="endpoint-path">/api/articles/{id}</div>
                        <div class="endpoint-description">Fetch a specific article by ID</div>
                    </div>
                </div>

                <div class="endpoint">
                    <span class="method post">POST</span>
                    <div class="endpoint-content">
                        <div class="endpoint-path">/api/articles</div>
                        <div class="endpoint-description">Create a new article</div>
                    </div>
                </div>

                <div class="endpoint">
                    <span class="method put">PUT</span>
                    <div class="endpoint-content">
                        <div class="endpoint-path">/api/articles/{id}</div>
                        <div class="endpoint-description">Update existing article</div>
                    </div>
                </div>

                <div class="endpoint">
                    <span class="method delete">DELETE</span>
                    <div class="endpoint-content">
                        <div class="endpoint-path">/api/articles/{id}</div>
                        <div class="endpoint-description">Delete an article</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Information</h2>
            
            <div class="info-grid">
                <div class="info-item">
                    <svg class="icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <div class="info-content">
                        <div class="info-title">Environment</div>
                        <div class="info-description">Configure database credentials in <code>.env</code></div>
                    </div>
                </div>

                <div class="info-item">
                    <svg class="icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <div class="info-content">
                        <div class="info-title">Scraper</div>
                        <div class="info-description">Run <code>php artisan scrape:oldest</code> to fetch articles</div>
                    </div>
                </div>

                <div class="info-item">
                    <svg class="icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    <div class="info-content">
                        <div class="info-title">Optimization</div>
                        <div class="info-description">Process articles with <code>npm run optimize</code></div>
                    </div>
                </div>

                <div class="info-item">
                    <svg class="icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                    </svg>
                    <div class="info-content">
                        <div class="info-title">Response Format</div>
                        <div class="info-description">All endpoints return JSON with standard structure</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
