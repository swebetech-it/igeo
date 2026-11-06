# SEO Quick Reference Guide

## For Content Creators

### Blog Posts
- **Title**: 30-60 characters, main keyword first
- **Excerpt**: 150-200 characters, compelling, with action
- **Content**: 1000+ words, well-structured with h2/h3 headers
- **Images**: High quality, descriptive alt text, optimized size
- **Tags**: 5-10 relevant keywords
- **Featured Image**: 1200x630px (1.91:1 ratio)

### Gallery Images
- **Filename**: descriptive-location-year.jpg (e.g., modern-pharmacy-dar-2024.jpg)
- **Alt Text**: Detailed description (e.g., "Modern pharmacy storefront in Dar es Salaam")
- **Size**: Compress to <200KB
- **Format**: JPG for photos, PNG for graphics

### Careers/Jobs
- **Title**: Include location and level (e.g., "Senior Pharmacist - Dar es Salaam")
- **Description**: Clear duties, qualifications, benefits
- **Keywords**: Position, location, industry terms
- **Tags**: Department, level, type

## For Developers

### Adding SEO to New Pages

1. **Update Controller**:
```php
return view('public.page-name', compact('data'), [
    'meta_title' => 'Page Title',
    'meta_description' => 'Compelling description under 160 characters',
    'meta_keywords' => 'keyword1, keyword2, keyword3',
    'og_image' => asset('path/to/image.jpg'),
]);
```

2. **Update Blade Template**:
```blade
@extends('layouts.web')

@section('title', 'Page Title')

@section('meta_description', 'Your description')

@section('meta_keywords', 'your, keywords, here')

@section('content')
    <!-- Your content -->
@endsection
```

3. **Add Structured Data**:
```blade
@section('structured_data')
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "PageType",
        "name": "Page Name",
        "description": "Page description"
    }
    </script>
@endsection
```

### Model Scopes

Add `published()` scope to models to help with sitemap generation:

```php
// In model
public function scopePublished($query)
{
    return $query->where('published', true)
                ->where('published_at', '<=', now());
}
```

### Image Optimization

```blade
<!-- Good -->
<img src="/images/modern-pharmacy-dar-2024.jpg" 
     alt="Modern pharmacy storefront with green entrance in Dar es Salaam, Tanzania"
     loading="lazy"
     width="800"
     height="600">

<!-- Bad -->
<img src="/img/123.jpg" alt="image">
```

## Technical Checklist

- [ ] All pages have unique titles (30-60 chars)
- [ ] All pages have descriptions (120-160 chars)
- [ ] All images have alt text
- [ ] All links have descriptive anchor text
- [ ] No broken links (404 errors)
- [ ] All canonical tags present
- [ ] Mobile responsive on all pages
- [ ] Page speed < 3 seconds
- [ ] HTTPS on all pages
- [ ] Sitemap submited to Google
- [ ] robots.txt configured
- [ ] JSON-LD structured data present

## Performance Targets

- **Core Web Vitals**:
  - Largest Contentful Paint (LCP): < 2.5 seconds
  - First Input Delay (FID): < 100 milliseconds
  - Cumulative Layout Shift (CLS): < 0.1

- **Page Speed**:
  - Mobile: > 50 PageSpeed score
  - Desktop: > 80 PageSpeed score

## Content Keywords

### Primary Keywords (Target):
- Construction engineering Tanzania
- Pharmaceutical consulting
- Infrastructure solutions
- Professional services East Africa

### Secondary Keywords:
- Building contractors Dar es Salaam
- Engineering consultants Tanzania
- Construction projects
- Sustainable development
- Health sector solutions

## Monthly Tasks

- [ ] Publish 2-4 new blog posts
- [ ] Update gallery with new project images
- [ ] Review top performing pages
- [ ] Check Google Search Console
- [ ] Update team/careers if needed
- [ ] Check for broken links
- [ ] Review site speed metrics

## Tools & Resources

- [Google Search Console](https://search.google.com/search-console)
- [Google Analytics](https://analytics.google.com)
- [PageSpeed Insights](https://pagespeed.web.dev)
- [Mobile-Friendly Test](https://search.google.com/test/mobile-friendly)
- [Rich Results Test](https://search.google.com/test/rich-results)
- [Structured Data Test](https://www.google.com/webmasters/markup-helper/)

---

**For Questions**: Contact your development team or SEO manager
