<?php

namespace App\Helpers;

/**
 * SEO Helper Functions
 * 
 * Utility functions to assist with SEO optimization throughout the application
 */

class SeoHelper
{
    /**
     * Generate a meta-friendly description from HTML content
     * 
     * @param string $content
     * @param int $length
     * @return string
     */
    public static function generateDescription(string $content, int $length = 160): string
    {
        // Strip HTML tags
        $text = strip_tags($content);
        
        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        
        // Trim to length
        $text = substr($text, 0, $length);
        
        // Ensure we don't cut off in the middle of a word
        if (strlen($text) == $length) {
            $text = substr($text, 0, strrpos($text, ' ')) . '...';
        }
        
        return trim($text);
    }

    /**
     * Generate SEO-friendly slug from title
     * 
     * @param string $title
     * @return string
     */
    public static function generateSlug(string $title): string
    {
        return str_slug($title);
    }

    /**
     * Extract keywords from content
     * 
     * @param string $content
     * @param int $count
     * @return array
     */
    public static function extractKeywords(string $content, int $count = 5): array
    {
        // Remove HTML tags
        $text = strip_tags($content);
        
        // Convert to lowercase
        $text = strtolower($text);
        
        // Remove common words (stop words)
        $stopWords = [
            'the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for',
            'of', 'with', 'by', 'from', 'is', 'are', 'was', 'were', 'be', 'been',
            'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would',
            'could', 'should', 'may', 'might', 'must', 'can', 'this', 'that',
            'these', 'those', 'as', 'it', 'if'
        ];
        
        // Split into words
        $words = str_word_count($text, 1);
        
        // Filter out stop words and short words
        $keywords = array_filter($words, function($word) use ($stopWords) {
            return !in_array($word, $stopWords) && strlen($word) > 3;
        });
        
        // Count frequencies
        $frequencies = array_count_values($keywords);
        
        // Sort by frequency
        arsort($frequencies);
        
        // Return top keywords
        return array_slice(array_keys($frequencies), 0, $count);
    }

    /**
     * Check if URL is canonical
     * 
     * @param string $url
     * @return bool
     */
    public static function isCanonical(string $url): bool
    {
        // Check for query parameters that shouldn't affect canonical
        $parsed = parse_url($url);
        
        $nonCanonicalParams = ['utm_source', 'utm_medium', 'utm_campaign', 'fb_ref', 'page'];
        
        if (!isset($parsed['query'])) {
            return true;
        }
        
        parse_str($parsed['query'], $query);
        
        foreach ($query as $key => $value) {
            if (in_array($key, $nonCanonicalParams)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get SEO score for a page (0-100)
     * 
     * @param array $seoData
     * @return int
     */
    public static function calculateSeoScore(array $seoData): int
    {
        $score = 0;
        $maxScore = 100;
        
        // Check title (15 points)
        if (!empty($seoData['title']) && strlen($seoData['title']) >= 30 && strlen($seoData['title']) <= 60) {
            $score += 15;
        }
        
        // Check description (15 points)
        if (!empty($seoData['description']) && strlen($seoData['description']) >= 120 && strlen($seoData['description']) <= 160) {
            $score += 15;
        }
        
        // Check keywords (10 points)
        if (!empty($seoData['keywords']) && count(explode(',', $seoData['keywords'])) >= 3) {
            $score += 10;
        }
        
        // Check canonical (10 points)
        if (!empty($seoData['canonical'])) {
            $score += 10;
        }
        
        // Check h1 tag (15 points)
        if (!empty($seoData['h1'])) {
            $score += 15;
        }
        
        // Check meta image (10 points)
        if (!empty($seoData['og_image'])) {
            $score += 10;
        }
        
        // Check alt text on images (15 points)
        if (!empty($seoData['images_with_alt'])) {
            $score += 15;
        }
        
        return min($score, $maxScore);
    }

    /**
     * Generate breadcrumb schema
     * 
     * @param array $breadcrumbs
     * @return string
     */
    public static function generateBreadcrumbSchema(array $breadcrumbs): string
    {
        $items = [];
        
        foreach ($breadcrumbs as $position => $breadcrumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position + 1,
                'name' => $breadcrumb['name'],
                'item' => $breadcrumb['url'],
            ];
        }
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
        
        return '<script type="application/ld+json">' . json_encode($schema) . '</script>';
    }

    /**
     * Check if content is original (basic duplicate check)
     * 
     * @param string $content
     * @param int $minWords
     * @return bool
     */
    public static function isOriginalContent(string $content, int $minWords = 300): bool
    {
        $wordCount = str_word_count(strip_tags($content));
        return $wordCount >= $minWords;
    }

    /**
     * Format date for schema
     * 
     * @param \DateTime|string $date
     * @return string
     */
    public static function formatSchemaDate($date): string
    {
        if (is_string($date)) {
            $date = new \DateTime($date);
        }
        
        return $date->format('Y-m-d');
    }
}
