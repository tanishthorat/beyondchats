<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Article;

class ScrapeOldestArticles extends Command
{
    protected $signature = 'scrape:oldest';
    protected $description = 'Scrape 5 oldest articles from BeyondChats';

    public function handle()
    {
        // 1. Start from the blogs index to find last page, then iterate backwards
        $base = 'https://beyondchats.com/blogs/';
        $this->info("Fetching blog index: $base");

        $indexResp = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
        ])->get($base);

        if ($indexResp->failed()) {
            $this->error("Failed to fetch blog index.");
            return;
        }

        $indexHtml = $indexResp->body();
        $indexCrawler = new Crawler($indexHtml);

        // Try to extract last page number from pagination links
        $lastPage = 0;
        try {
            // common pagination link patterns
            $indexCrawler->filter('a')->each(function (Crawler $node) use (&$lastPage) {
                $href = $node->attr('href') ?: '';
                if (preg_match('#/page/(\d+)/#', $href, $m)) {
                    $num = intval($m[1]);
                    if ($num > $lastPage) $lastPage = $num;
                }
                // also page query param fallback
                if (preg_match('#page=(\d+)#', $href, $m2)) {
                    $num = intval($m2[1]);
                    if ($num > $lastPage) $lastPage = $num;
                }
            });
        } catch (\Exception $e) {
            // ignore
        }

        // fallback if cannot detect
        if ($lastPage === 0) {
            $lastPage = 15; // previous fallback
        }

        $this->info("Detected last page: $lastPage");

        // Collect up to 5 oldest articles by iterating from last page backwards
        $collected = [];
        $seen = [];
        $page = $lastPage;

        $articleSelectors = ['.entry-card', '.post-card', '.post', 'article', '.blog-post'];
        $titleSelectors = ['.entry-title a', 'h2 a', 'h3 a', 'a'];

        while (count($collected) < 5 && $page >= 1) {
            $pageUrl = $page === 1 ? $base : rtrim($base, '/') . "/page/{$page}/";
            $this->info("Fetching page: $pageUrl");
            $resp = Http::withHeaders(['User-Agent' => 'Mozilla/5.0'])->get($pageUrl);
            if ($resp->failed()) {
                $this->warn("Failed to fetch page $page. Skipping.");
                $page--;
                continue;
            }

            $crawler = new Crawler($resp->body());

            $nodes = [];
            foreach ($articleSelectors as $sel) {
                if ($crawler->filter($sel)->count() > 0) {
                    $nodes = $crawler->filter($sel)->each(function (Crawler $n) use ($titleSelectors) {
                        $title = null;
                        $link = null;
                        $image = null;
                        foreach ($titleSelectors as $ts) {
                            if ($n->filter($ts)->count() > 0) {
                                try {
                                    $title = trim($n->filter($ts)->text());
                                    $link = $n->filter($ts)->attr('href');
                                    break;
                                } catch (\Exception $e) {
                                    // continue
                                }
                            }
                        }
                        // image fallback
                        try {
                            if ($n->filter('img')->count() > 0) {
                                $image = $n->filter('img')->first()->attr('src');
                            }
                        } catch (\Exception $e) {
                            $image = null;
                        }

                        if ($title && $link) return ['title' => $title, 'link' => $link, 'image' => $image];
                        return null;
                    });
                    // if we found something with this selector, don't try other selectors (to avoid duplicates)
                    if (!empty($nodes)) break;
                }
            }

            foreach ($nodes as $n) {
                if (!$n) continue;
                $href = $n['link'];
                if (in_array($href, $seen)) continue;
                $seen[] = $href;
                $collected[] = $n;
                if (count($collected) >= 5) break;
            }

            $page--;
        }

        $this->info("Found " . count($collected) . " articles. Processing...");

        foreach ($collected as $articleData) {
            $this->info("Scraping content for: " . $articleData['title']);

            $detailResponse = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
            ])->get($articleData['link']);

            if ($detailResponse->ok()) {
                $detailCrawler = new Crawler($detailResponse->body());

                // Try multiple content selectors
                $content = null;
                $contentSelectors = ['.entry-content', '.post-content', '.content', 'article .content', '.article-content'];
                foreach ($contentSelectors as $cs) {
                    try {
                        if ($detailCrawler->filter($cs)->count() > 0) {
                            $content = $detailCrawler->filter($cs)->html();
                            break;
                        }
                    } catch (\Exception $e) {
                        // continue
                    }
                }

                if (!$content) {
                    // fallback: use body text
                    $content = $detailCrawler->filter('body')->count() > 0 ? $detailCrawler->filter('body')->html() : 'Content not found';
                }

                Article::updateOrCreate(
                    ['source_url' => $articleData['link']],
                    [
                        'title' => $articleData['title'],
                        'content' => $content,
                        'image_url' => $articleData['image'],
                        'is_processed' => false
                    ]
                );
            }
        }

        $this->info("Phase 1 Complete! Data saved to Database.");
    }
}