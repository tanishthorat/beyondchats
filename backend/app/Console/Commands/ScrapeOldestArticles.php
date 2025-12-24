<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\DomCrawler\Crawler;

// Note: install Goutte (wrapper) via: composer require weidner/goutte
// Adjust the Client import if your package provides a different namespace.

class ScrapeOldestArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:oldest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape the oldest 5 articles from the last page and insert into DB';

    public function handle()
    {
        $this->info('Starting scrape of oldest articles...');

        // Create a Goutte client. If you installed a different package, adjust accordingly.
        $client = new \Goutte\Client();

        // LISTING URL: change this to the site you want to scrape
        $listingUrl = 'https://example.com/articles';

        // Fetch listing page
        $crawler = $client->request('GET', $listingUrl);

        // Find last page link (selector is a placeholder and must be adjusted)
        try {
            $lastLinkNode = $crawler->filter('.pagination a')->last();
            $lastPageUrl = $lastLinkNode->link()->getUri();
        } catch (\Exception $e) {
            $this->warn('Could not find pagination last link; using listing URL.');
            $lastPageUrl = $listingUrl;
        }

        $this->info("Last page URL: {$lastPageUrl}");

        // Fetch last page and collect article items (selector placeholders)
        $lastPage = $client->request('GET', $lastPageUrl);

        $items = $lastPage->filter('.article-item')->each(function (Crawler $node) {
            $title = $node->filter('.title')->count() ? trim($node->filter('.title')->text()) : null;
            $url = $node->filter('a')->count() ? $node->filter('a')->attr('href') : null;
            return ['title' => $title, 'url' => $url];
        });

        if (empty($items)) {
            $this->warn('No articles found on the last page with current selectors.');
            return 1;
        }

        // Grab the oldest 5 from the end of the list
        $oldestFive = array_slice($items, -5);

        foreach ($oldestFive as $a) {
            if (empty($a['url'])) continue;

            try {
                $detail = $client->request('GET', $a['url']);

                $content = $detail->filter('body')->count() ? $detail->filter('body')->html() : null;
                $description = null;
                try {
                    $description = $detail->filter('meta[name="description"]')->attr('content');
                } catch (\Exception $e) {}
                $image = null;
                try {
                    $image = $detail->filter('meta[property="og:image"]')->attr('content');
                } catch (\Exception $e) {}

                // Insert or update
                DB::table('articles')->updateOrInsert(
                    ['source_url' => $a['url']],
                    [
                        'title' => $a['title'] ?? 'Untitled',
                        'description' => $description,
                        'content' => $content,
                        'image_url' => $image,
                        'is_processed' => false,
                        'references' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                $this->info('Saved: ' . ($a['title'] ?? $a['url']));
            } catch (\Exception $e) {
                $this->error('Error processing ' . $a['url'] . ': ' . $e->getMessage());
            }
        }

        $this->info('Scrape complete.');
        return 0;
    }
}
