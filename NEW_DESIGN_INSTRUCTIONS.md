# New Website Design Implementation

This document explains how to implement the new industrial minimalist design for the DSS Engineering website.

## Overview

The new design features:
- Industrial minimalist aesthetic
- Monochromatic color scheme (blacks, grays) with gold accents
- Distinct typography using Oswald and Roboto Mono fonts
- Clean grid-based layout
- Subtle animations and transitions

## Files Created

1. `resources/css/website-new.css` - New stylesheet with industrial minimalist styles
2. `resources/views/layouts/website-new.blade.php` - New website layout template
3. `resources/views/home-new.blade.php` - New homepage template
4. `resources/views/new-design.blade.php` - Demo page showcasing the new design
5. `app/Http/Controllers/WebsiteController.php` - Updated with new method
6. `routes/web.php` - Updated with new routes
7. `vite.config.js` - Updated to include new CSS file

## Implementation Steps

### 1. Switch to the New Homepage

To switch the main homepage to the new design, update the route in `routes/web.php`:

```php
// Change this line:
Route::get('/', [WebsiteController::class, 'home'])->name('home');

// To this:
Route::get('/', [WebsiteController::class, 'homeNew'])->name('home');
```

### 2. Or Create a Toggle System

Alternatively, you can create a toggle system to switch between designs by updating the controller method:

```php
public function home()
{
    // Check for a query parameter or session variable to determine which design to show
    if (request()->query('new_design')) {
        // Get data for new homepage
        $services = Service::take(6)->get();
        $galleries = Gallery::orderBy('created_at', 'desc')->get();
        $experiences = Experience::orderBy('year', 'desc')->take(6)->get();
        
        return view('home-new', compact('services', 'galleries', 'experiences'));
    }
    
    // Original homepage logic
    $services = Service::take(6)->get();
    $galleryImages = Gallery::orderBy('created_at', 'desc')->get();
    $experiences = Experience::orderBy('year', 'desc')->take(3)->get();
    $latestBlogs = Blog::orderBy('created_at', 'desc')->take(3)->get();
    $galleries = Gallery::where('featured', true)->take(5)->get();
    if ($galleries->isEmpty()) {
        $galleries = Gallery::take(5)->get();
    }
    
    return view('home', compact('services', 'galleryImages', 'galleries', 'experiences', 'latestBlogs'));
}
```

Then you can access the new design with: `/?new_design=1`

### 3. Update Navigation (Optional)

If you want to add a link to the new design in your navigation, update the layout files:

In `resources/views/layouts/website.blade.php` or `resources/views/layouts/website-new.blade.php`:

```html
<nav class="hidden md:flex items-center space-x-8">
    <a href="{{ route('home') }}" class="nav-link">Home</a>
    <a href="{{ route('home.new') }}" class="nav-link">New Design</a>
    <!-- other navigation items -->
</nav>
```

## Key Features of the New Design

### Color Scheme
- Primary Dark: #1a1a1a
- Primary Medium: #2d2d2d
- Primary Light: #3d3d3d
- Accent Gold: #d4af37
- Highlight: #ff6b35

### Typography
- Headings: Oswald (sans-serif, uppercase)
- Body Text: Roboto Mono (monospace)

### Layout
- Grid-based with flexible columns
- Ample whitespace for minimalist aesthetic
- Card-based components with subtle hover effects

### Components
- Industrial-styled buttons with gold accents
- Feature cards with top border animations
- Clean form elements with monochromatic styling
- Responsive navigation with mobile toggle

## Viewing the New Design

You can view the new design at:
- `/home-new` - New homepage
- `/new-design` - Demo page showcasing design elements

## Customization

To customize the new design:
1. Modify color variables in `resources/css/website-new.css`
2. Adjust typography in the same file
3. Update component styles as needed
4. Rebuild assets with `npm run build`

## Browser Support

The new design uses modern CSS features including:
- CSS Grid
- CSS Variables
- Flexbox
- Modern pseudo-elements

These features are supported in all modern browsers.