<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'BeyondChats') }} API Documentation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #6366f1;
            --secondary: #8b5cf6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --dark: #242424ff;
            --light: #c5c5c5ff;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            line-height: 1.6;
            background: #242424ff;
            min-height: 100vh;
            color: #1e293b;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header Section */
        .header {
            text-align: center;
            color: white;
            margin-bottom: 3rem;
            animation: fadeInDown 0.6s ease-out;
        }

        .header h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .header .subtitle {
            font-size: 1.25rem;
            opacity: 0.95;
            font-weight: 300;
        }

        .header .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(19, 79, 0, 0.2);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeInUp 0.6s ease-out;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.15);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }

        /* API Endpoints */
        .endpoints-grid {
            display: grid;
            gap: 1rem;
        }

        .endpoint {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 12px;
            padding: 1.25rem;
            border-left: 4px solid var(--primary);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .endpoint:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .endpoint::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--method-color);
            transition: width 0.3s ease;
        }

        .endpoint:hover::before {
            width: 8px;
        }

        .endpoint-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.75rem;
            flex-wrap: wrap;
        }

        .method {
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .method.get {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .method.post {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .method.put {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .method.delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .endpoint-path {
            font-family: 'Fira Code', 'Courier New', monospace;
            font-size: 1rem;
            font-weight: 600;
            color: #475569;
            flex: 1;
        }

        .copy-btn {
            padding: 0.4rem 0.8rem;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 600;
        }

        .copy-btn:hover {
            background: #f8fafc;
            border-color: var(--primary);
            color: var(--primary);
            transform: scale(1.05);
        }

        .copy-btn.copied {
            background: #10b981;
            color: white;
            border-color: #10b981;
        }

        .endpoint-description {
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }

        .endpoint-example {
            background: #1e293b;
            color: #e2e8f0;
            padding: 1rem;
            border-radius: 8px;
            font-family: 'Fira Code', monospace;
            font-size: 0.85rem;
            margin-top: 0.75rem;
            position: relative;
            overflow-x: auto;
        }

        .endpoint-example::before {
            content: 'CURL';
            position: absolute;
            top: 0.5rem;
            right: 0.75rem;
            font-size: 0.7rem;
            color: #64748b;
            font-weight: 700;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .action-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .action-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.2);
        }

        .action-card .icon {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
        }

        .action-card h3 {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }

        .action-card p {
            font-size: 0.9rem;
            color: #64748b;
        }

        /* Code Blocks */
        code {
            background: #eef2ff;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-family: 'Fira Code', monospace;
            font-size: 0.9rem;
            color: #6366f1;
            border: 1px solid #e0e7ff;
        }

        /* Footer */
        .footer {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .footer-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-content {
            display: grid;
            gap: 0.75rem;
        }

        .footer-item {
            display: flex;
            align-items: start;
            gap: 0.75rem;
            padding: 0.75rem;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 3px solid var(--primary);
        }

        .footer-item .emoji {
            font-size: 1.2rem;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.6;
                transform: scale(1.1);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
            
            .endpoint-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .card {
                padding: 1.5rem;
            }
        }

        /* Button Styles */
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .btn-secondary {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-secondary:hover {
            background: var(--primary);
            color: white;
        }

        .button-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>BeyondChats API</h1>
            <p class="subtitle">Intelligent Article Management & Optimization Platform</p>
            <div class="status-badge">
                <span class="status-dot"></span>
                <span>API Active</span>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="/api/articles" class="action-card">
                <div class="icon">📄</div>
                <h3>Browse Articles</h3>
                <p>View all scraped articles in JSON format</p>
            </a>
            
            <div class="action-card" onclick="window.scrollTo({top: document.querySelector('.endpoints-grid').offsetTop - 100, behavior: 'smooth'})">
                <div class="icon">🔗</div>
                <h3>API Endpoints</h3>
                <p>Explore available REST endpoints</p>
            </div>
            
            <div class="action-card" onclick="alert('Run: php artisan scrape:oldest')">
                <div class="icon">🕷️</div>
                <h3>Run Scraper</h3>
                <p>Execute article scraping command</p>
            </div>
            
            <div class="action-card" onclick="window.open('https://beyondchats.com/blogs', '_blank')">
                <div class="icon">🌐</div>
                <h3>Source Blog</h3>
                <p>Visit BeyondChats blog</p>
            </div>
        </div>

        <!-- Main API Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon">🔌</div>
                <div>
                    <h2 class="card-title">REST API Endpoints</h2>
                    <p style="color: #64748b; margin: 0;">Full CRUD operations for article management</p>
                </div>
            </div>

            <div class="endpoints-grid">
                <!-- GET All Articles -->
                <div class="endpoint">
                    <div class="endpoint-header">
                        <span class="method get">GET</span>
                        <code class="endpoint-path">/api/articles</code>
                        <button class="copy-btn" onclick="copyToClipboard('/api/articles', this)">Copy</button>
                    </div>
                    <p class="endpoint-description">📋 Retrieve paginated list of all articles with metadata</p>
                    <div class="endpoint-example">curl -X GET "{{ url('/api/articles') }}" \
  -H "Accept: application/json"</div>
                </div>

                <!-- GET Single Article -->
                <div class="endpoint">
                    <div class="endpoint-header">
                        <span class="method get">GET</span>
                        <code class="endpoint-path">/api/articles/{id}</code>
                        <button class="copy-btn" onclick="copyToClipboard('/api/articles/{id}', this)">Copy</button>
                    </div>
                    <p class="endpoint-description">🔍 Fetch a specific article by ID with full content</p>
                    <div class="endpoint-example">curl -X GET "{{ url('/api/articles/1') }}" \
  -H "Accept: application/json"</div>
                </div>

                <!-- POST Create Article -->
                <div class="endpoint">
                    <div class="endpoint-header">
                        <span class="method post">POST</span>
                        <code class="endpoint-path">/api/articles</code>
                        <button class="copy-btn" onclick="copyToClipboard('/api/articles [POST]', this)">Copy</button>
                    </div>
                    <p class="endpoint-description">➕ Create a new article with title, content, and metadata</p>
                    <div class="endpoint-example">curl -X POST "{{ url('/api/articles') }}" \
  -H "Content-Type: application/json" \
  -d '{"title":"Article Title","content":"..."}'</div>
                </div>

                <!-- PUT Update Article -->
                <div class="endpoint">
                    <div class="endpoint-header">
                        <span class="method put">PUT</span>
                        <code class="endpoint-path">/api/articles/{id}</code>
                        <button class="copy-btn" onclick="copyToClipboard('/api/articles/{id} [PUT]', this)">Copy</button>
                    </div>
                    <p class="endpoint-description">✏️ Update existing article content and attributes</p>
                    <div class="endpoint-example">curl -X PUT "{{ url('/api/articles/1') }}" \
  -H "Content-Type: application/json" \
  -d '{"title":"Updated Title","content":"..."}'</div>
                </div>

                <!-- DELETE Article -->
                <div class="endpoint">
                    <div class="endpoint-header">
                        <span class="method delete">DELETE</span>
                        <code class="endpoint-path">/api/articles/{id}</code>
                        <button class="copy-btn" onclick="copyToClipboard('/api/articles/{id} [DELETE]', this)">Copy</button>
                    </div>
                    <p class="endpoint-description">🗑️ Delete an article permanently from the database</p>
                    <div class="endpoint-example">curl -X DELETE "{{ url('/api/articles/1') }}" \
  -H "Accept: application/json"</div>
                </div>
            </div>

            <div class="button-group">
                <a href="/api/articles" class="btn btn-primary">🚀 Try API Now</a>
                <a href="https://beyondchats.com/blogs" target="_blank" class="btn btn-secondary">📰 View Source Blog</a>
            </div>
        </div>

        <!-- Developer Notes -->
        <div class="footer">
            <h3 class="footer-title">
                💡 Developer Notes
            </h3>
            <div class="footer-content">
                <div class="footer-item">
                    <span class="emoji">🔐</span>
                    <div>
                        <strong>Environment Setup:</strong> Configure database credentials and API keys in <code>.env</code> file before running the application.
                    </div>
                </div>
                
                <div class="footer-item">
                    <span class="emoji">🕷️</span>
                    <div>
                        <strong>Scraper Command:</strong> Execute <code>php artisan scrape:oldest</code> to scrape the 5 oldest articles from BeyondChats blog.
                    </div>
                </div>
                
                <div class="footer-item">
                    <span class="emoji">🤖</span>
                    <div>
                        <strong>LLM Processing:</strong> NodeJS script optimizes articles using Google Search and LLM APIs. Run <code>npm run optimize</code> to process articles.
                    </div>
                </div>
                
                <div class="footer-item">
                    <span class="emoji">⚛️</span>
                    <div>
                        <strong>Frontend:</strong> ReactJS application consumes this API to display articles. Check <code>/frontend</code> directory for UI code.
                    </div>
                </div>
                
                <div class="footer-item">
                    <span class="emoji">📊</span>
                    <div>
                        <strong>Response Format:</strong> All endpoints return JSON with standard structure: <code>{"success": true, "data": {...}}</code>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text, button) {
            const fullUrl = window.location.origin + text.split(' ')[0];
            navigator.clipboard.writeText(fullUrl).then(() => {
                const originalText = button.textContent;
                button.textContent = '✓ Copied!';
                button.classList.add('copied');
                
                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('copied');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy:', err);
                alert('Copy failed. Please copy manually: ' + fullUrl);
            });
        }

        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
