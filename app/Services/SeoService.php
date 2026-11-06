<?php

namespace App\Services;

class SeoService
{
    private string $title = '';
    private string $description = '';
    private string $keywords = '';
    private string $canonical = '';
    private string $ogTitle = '';
    private string $ogDescription = '';
    private string $ogImage = '';
    private string $ogType = 'website';
    private string $twitterCard = 'summary_large_image';
    private array $structuredData = [];

    /**
     * Set page title and OG title
     */
    public function setTitle(string $title): self
    {
        $this->title = $title . ' | ' . config('app.name', 'iGEO');
        $this->ogTitle = $title;
        return $this;
    }

    /**
     * Set meta description
     */
    public function setDescription(string $description): self
    {
        // Limit to 160 characters for optimal Google display
        $this->description = substr($description, 0, 160);
        $this->ogDescription = $this->description;
        return $this;
    }

    /**
     * Set keywords (though less important now)
     */
    public function setKeywords(string $keywords): self
    {
        $this->keywords = $keywords;
        return $this;
    }

    /**
     * Set canonical URL to prevent duplicate content issues
     */
    public function setCanonical(string $url): self
    {
        $this->canonical = $url;
        return $this;
    }

    /**
     * Set Open Graph image
     */
    public function setOgImage(string $imageUrl): self
    {
        $this->ogImage = $imageUrl;
        return $this;
    }

    /**
     * Set Open Graph type
     */
    public function setOgType(string $type): self
    {
        $this->ogType = $type;
        return $this;
    }

    /**
     * Set Twitter card type
     */
    public function setTwitterCard(string $type): self
    {
        $this->twitterCard = $type;
        return $this;
    }

    /**
     * Add JSON-LD structured data
     */
    public function addStructuredData(array $data): self
    {
        $this->structuredData = $data;
        return $this;
    }

    /**
     * Generate all meta tags as HTML
     */
    public function render(): string
    {
        $html = '';

        // Basic meta tags
        if ($this->title) {
            $html .= '<title>' . htmlspecialchars($this->title) . '</title>' . "\n";
        }

        if ($this->description) {
            $html .= '<meta name="description" content="' . htmlspecialchars($this->description) . '">' . "\n";
        }

        if ($this->keywords) {
            $html .= '<meta name="keywords" content="' . htmlspecialchars($this->keywords) . '">' . "\n";
        }

        // Canonical URL
        if ($this->canonical) {
            $html .= '<link rel="canonical" href="' . htmlspecialchars($this->canonical) . '">' . "\n";
        }

        // Open Graph tags
        if ($this->ogTitle) {
            $html .= '<meta property="og:title" content="' . htmlspecialchars($this->ogTitle) . '">' . "\n";
        }

        if ($this->ogDescription) {
            $html .= '<meta property="og:description" content="' . htmlspecialchars($this->ogDescription) . '">' . "\n";
        }

        if ($this->ogImage) {
            $html .= '<meta property="og:image" content="' . htmlspecialchars($this->ogImage) . '">' . "\n";
            $html .= '<meta property="og:image:width" content="1200">' . "\n";
            $html .= '<meta property="og:image:height" content="630">' . "\n";
        }

        $html .= '<meta property="og:type" content="' . htmlspecialchars($this->ogType) . '">' . "\n";
        $html .= '<meta property="og:url" content="' . htmlspecialchars(url()->current()) . '">' . "\n";

        // Twitter Card tags
        $html .= '<meta name="twitter:card" content="' . htmlspecialchars($this->twitterCard) . '">' . "\n";

        if ($this->ogTitle) {
            $html .= '<meta name="twitter:title" content="' . htmlspecialchars($this->ogTitle) . '">' . "\n";
        }

        if ($this->ogDescription) {
            $html .= '<meta name="twitter:description" content="' . htmlspecialchars($this->ogDescription) . '">' . "\n";
        }

        if ($this->ogImage) {
            $html .= '<meta name="twitter:image" content="' . htmlspecialchars($this->ogImage) . '">' . "\n";
        }

        // Additional SEO meta tags
        $html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
        $html .= '<meta name="robots" content="index, follow">' . "\n";
        $html .= '<meta name="language" content="English">' . "\n";

        // JSON-LD Structured Data
        if (!empty($this->structuredData)) {
            $html .= '<script type="application/ld+json">' . "\n";
            $html .= json_encode($this->structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n";
            $html .= '</script>' . "\n";
        }

        return $html;
    }

    /**
     * Generate sitemap entry data
     */
    public static function generateSitemapEntry(string $url, string $lastmod = null, string $changefreq = 'weekly', float $priority = 0.8): array
    {
        return [
            'url' => $url,
            'lastmod' => $lastmod ?? now()->toAtomString(),
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }
}
