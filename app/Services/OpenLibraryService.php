<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenLibraryService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.open_library.base_url', 'https://openlibrary.org');
    }

    /**
     * Search for books by title, author, or ISBN.
     */
    public function searchBooks(string $query, int $limit = 10): array
    {
        try {
            $response = Http::timeout(10)
                ->get($this->baseUrl . '/search.json', [
                    'q' => $query,
                    'limit' => $limit,
                    'fields' => 'title,author_name,author_key,first_publish_year,isbn,cover_i,cover_edition_key,publisher,subject_key'
                ]);

            if (!$response->successful()) {
                Log::error('Open Library API search failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return [];
            }

            $data = $response->json();
            return $this->formatSearchResults($data['docs'] ?? []);
        } catch (\Exception $e) {
            Log::error('Open Library API search error', [
                'query' => $query,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Get detailed information about a specific book by its key.
     */
    public function getBookDetails(string $key): ?array
    {
        try {
            $response = Http::timeout(10)
                ->get($this->baseUrl . "/works/{$key}.json");

            if (!$response->successful()) {
                Log::error('Open Library API book details failed', [
                    'key' => $key,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }

            return $this->formatBookDetails($response->json());
        } catch (\Exception $e) {
            Log::error('Open Library API book details error', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Search for books by ISBN.
     */
    public function searchByIsbn(string $isbn): ?array
    {
        try {
            $response = Http::timeout(10)
                ->get($this->baseUrl . '/api/books', [
                    'bibkeys' => "ISBN:{$isbn}",
                    'format' => 'json',
                    'jscmd' => 'data'
                ]);

            if (!$response->successful()) {
                Log::error('Open Library API ISBN search failed', [
                    'isbn' => $isbn,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }

            $data = $response->json();
            $isbnKey = "ISBN:{$isbn}";
            
            if (!isset($data[$isbnKey])) {
                return null;
            }

            return $this->formatIsbnResult($data[$isbnKey]);
        } catch (\Exception $e) {
            Log::error('Open Library API ISBN search error', [
                'isbn' => $isbn,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Format search results for consistent API response.
     */
    private function formatSearchResults(array $docs): array
    {
        return array_map(function ($doc) {
            return [
                'key' => $doc['key'] ?? '',
                'title' => $doc['title'] ?? '',
                'authors' => $this->formatAuthors($doc['author_name'] ?? []),
                'author_keys' => $doc['author_key'] ?? [],
                'first_publish_year' => $doc['first_publish_year'] ?? null,
                'isbn' => $this->extractIsbn($doc),
                'cover_url' => $this->getCoverUrl($doc),
                'publisher' => $this->formatPublishers($doc['publisher'] ?? []),
                'subjects' => $this->formatSubjects($doc['subject_key'] ?? []),
            ];
        }, $docs);
    }

    /**
     * Format detailed book information.
     */
    private function formatBookDetails(array $data): array
    {
        return [
            'key' => $data['key'] ?? '',
            'title' => $data['title'] ?? '',
            'authors' => $this->formatAuthorsFromDetails($data['authors'] ?? []),
            'description' => $data['description'] ?? '',
            'first_publish_year' => $data['first_publish_date'] ? 
                substr($data['first_publish_date'], 0, 4) : null,
            'cover_url' => $this->getCoverUrlFromDetails($data),
            'subjects' => $this->formatSubjects($data['subjects'] ?? []),
            'publisher' => $this->formatPublishers($data['publishers'] ?? []),
        ];
    }

    /**
     * Format ISBN search result.
     */
    private function formatIsbnResult(array $data): array
    {
        return [
            'key' => $data['key'] ?? '',
            'title' => $data['title'] ?? '',
            'authors' => $this->formatAuthorsFromDetails($data['authors'] ?? []),
            'description' => $data['description'] ?? '',
            'publish_date' => $data['publish_date'] ?? '',
            'cover_url' => $this->getCoverUrlFromDetails($data),
            'publisher' => $this->formatPublishers($data['publishers'] ?? []),
            'isbn' => $this->extractIsbnFromDetails($data),
        ];
    }

    /**
     * Format author names.
     */
    private function formatAuthors(array $authors): array
    {
        return array_map(function ($author) {
            return is_array($author) ? ($author['name'] ?? '') : $author;
        }, $authors);
    }

    /**
     * Format authors from detailed book data.
     */
    private function formatAuthorsFromDetails(array $authors): array
    {
        return array_map(function ($author) {
            return $author['name'] ?? '';
        }, $authors);
    }

    /**
     * Extract ISBN from document.
     */
    private function extractIsbn(array $doc): ?string
    {
        if (isset($doc['isbn']) && is_array($doc['isbn']) && !empty($doc['isbn'])) {
            return $doc['isbn'][0];
        }
        
        if (isset($doc['isbn_13']) && is_array($doc['isbn_13']) && !empty($doc['isbn_13'])) {
            return $doc['isbn_13'][0];
        }
        
        if (isset($doc['isbn_10']) && is_array($doc['isbn_10']) && !empty($doc['isbn_10'])) {
            return $doc['isbn_10'][0];
        }
        
        return null;
    }

    /**
     * Extract ISBN from detailed data.
     */
    private function extractIsbnFromDetails(array $data): ?string
    {
        if (isset($data['isbn_13']) && !empty($data['isbn_13'])) {
            return $data['isbn_13'];
        }
        
        if (isset($data['isbn_10']) && !empty($data['isbn_10'])) {
            return $data['isbn_10'];
        }
        
        return null;
    }

    /**
     * Get cover URL from search result.
     */
    private function getCoverUrl(array $doc): ?string
    {
        $coverId = $doc['cover_i'] ?? null;
        $coverEditionKey = $doc['cover_edition_key'] ?? null;
        
        if ($coverId) {
            return "https://covers.openlibrary.org/b/id/{$coverId}-M.jpg";
        }
        
        if ($coverEditionKey) {
            return "https://covers.openlibrary.org/b/olid/{$coverEditionKey}-M.jpg";
        }
        
        return null;
    }

    /**
     * Get cover URL from detailed book data.
     */
    private function getCoverUrlFromDetails(array $data): ?string
    {
        $coverId = $data['covers'][0] ?? null;
        
        if ($coverId) {
            return "https://covers.openlibrary.org/b/id/{$coverId}-M.jpg";
        }
        
        return null;
    }

    /**
     * Format publishers.
     */
    private function formatPublishers(array $publishers): array
    {
        return array_map(function ($publisher) {
            return is_array($publisher) ? ($publisher['name'] ?? '') : $publisher;
        }, $publishers);
    }

    /**
     * Format subjects.
     */
    private function formatSubjects(array $subjects): array
    {
        return array_map(function ($subject) {
            // Remove prefix and clean up subject names
            return preg_replace('/^[^:]+:\s*/', '', $subject);
        }, $subjects);
    }
}
