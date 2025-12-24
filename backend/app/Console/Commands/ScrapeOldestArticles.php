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
        // 1. Target the LAST page directly (Page 15 based on your debug file)
        // This ensures we get the oldest articles.
        $url = 'https://beyondchats.com/blogs/page/15/'; 

        $this->info("Fetching page: $url");

        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
        ])->get($url);

        if ($response->failed()) {
            $this->error("Failed to fetch page.");
            return;
        }

        $crawler = new Crawler($response->body());

        // 2. Use the CORRECT class names from your HTML snippet
        $articles = $crawler->filter('.entry-card')->each(function (Crawler $node) {
            try {
                return [
                    'title' => $node->filter('.entry-title a')->text(),
                    'link'  => $node->filter('.entry-title a')->attr('href'),
                    'image' => $node->filter('.ct-media-container img')->count() ? $node->filter('.ct-media-container img')->attr('src') : null,
                ];
            } catch (\Exception $e) {
                return null; // Skip if broken HTML
            }
        });

        // Filter out nulls and take the last 5 (or all if less than 5)
        $articles = array_filter($articles);
        $oldestArticles = array_slice($articles, -5); 

        $this->info("Found " . count($oldestArticles) . " articles. Processing...");

        foreach ($oldestArticles as $articleData) {
            $this->info("Scraping content for: " . $articleData['title']);

            // 3. Visit the individual article page to get the full text
            $detailResponse = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
            ])->get($articleData['link']);

            if ($detailResponse->ok()) {
                $detailCrawler = new Crawler($detailResponse->body());
                
                // Try standard Blocksy theme content class, fallback to body if missing
                $content = $detailCrawler->filter('.entry-content')->count() > 0 
                    ? $detailCrawler->filter('.entry-content')->html() 
                    : "Content not found";

                // 4. Save to Supabase
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