@extends('layouts.website-new')

@section('content')
<!-- Hero Section -->
<section class="hero">
    <div class="hero-content fade-in-up">
        <h1>
            NEW DESIGN
            <span>SHOWCASE</span>
            INDUSTRIAL MINIMALISM
        </h1>
        <p>This is a completely different style from the original website, featuring an industrial minimalist aesthetic with a monochromatic color scheme and gold accents.</p>
        <div class="hero-buttons">
            <a href="#features" class="btn btn-primary">Explore Features</a>
            <a href="#contact" class="btn btn-outline">Get Started</a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section" id="features">
    <div class="container">
        <div class="section-title">
            <h2>DESIGN FEATURES</h2>
            <p class="section-subtitle">What Makes This Design Different</p>
        </div>
        
        <div class="grid grid-3">
            <div class="card fade-in-up">
                <div class="card-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <h3>Monochromatic Palette</h3>
                <p>Uses a sophisticated black, gray, and gold color scheme that evokes industrial strength and luxury.</p>
            </div>
            
            <div class="card fade-in-up delay-1">
                <div class="card-icon">
                    <i class="fas fa-font"></i>
                </div>
                <h3>Distinct Typography</h3>
                <p>Features Oswald for headings and Roboto Mono for body text, creating a bold, technical aesthetic.</p>
            </div>
            
            <div class="card fade-in-up delay-2">
                <div class="card-icon">
                    <i class="fas fa-border-all"></i>
                </div>
                <h3>Clean Grid System</h3>
                <p>Utilizes a structured grid layout with ample whitespace for a minimalist, organized appearance.</p>
            </div>
            
            <div class="card fade-in-up">
                <div class="card-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>Fully Responsive</h3>
                <p>Adapts seamlessly to all device sizes with a mobile-first approach and flexible grid system.</p>
            </div>
            
            <div class="card fade-in-up delay-1">
                <div class="card-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Subtle Animations</h3>
                <p>Incorporates refined animations and transitions that enhance user experience without distraction.</p>
            </div>
            
            <div class="card fade-in-up delay-2">
                <div class="card-icon">
                    <i class="fas fa-code"></i>
                </div>
                <h3>Modern Codebase</h3>
                <p>Built with contemporary CSS techniques including CSS variables, flexbox, and grid layouts.</p>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="section" style="background-color: var(--primary-medium);">
    <div class="container">
        <div class="section-title">
            <h2>DESIGN ELEMENTS</h2>
            <p class="section-subtitle">Visual Components Showcase</p>
        </div>
        
        <div class="grid grid-4">
            <div class="card fade-in-up">
                <div style="height: 150px; background-color: var(--primary-light); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-square" style="font-size: 3rem; color: var(--accent-gold);"></i>
                </div>
                <h3>Buttons</h3>
                <p>Solid and outlined button variants with hover effects</p>
            </div>
            
            <div class="card fade-in-up delay-1">
                <div style="height: 150px; background-color: var(--primary-light); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-heading" style="font-size: 3rem; color: var(--accent-gold);"></i>
                </div>
                <h3>Typography</h3>
                <p>Hierarchical heading system with distinct styles</p>
            </div>
            
            <div class="card fade-in-up delay-2">
                <div style="height: 150px; background-color: var(--primary-light); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-th-large" style="font-size: 3rem; color: var(--accent-gold);"></i>
                </div>
                <h3>Grid Layout</h3>
                <p>Flexible grid system for content organization</p>
            </div>
            
            <div class="card fade-in-up delay-3">
                <div style="height: 150px; background-color: var(--primary-light); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-mobile" style="font-size: 3rem; color: var(--accent-gold);"></i>
                </div>
                <h3>Responsive</h3>
                <p>Adaptive design for all screen sizes</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="section" id="contact">
    <div class="container">
        <div class="section-title">
            <h2>TRY THE NEW DESIGN</h2>
            <p class="section-subtitle">See How It Works</p>
        </div>
        
        <div class="grid grid-2">
            <div class="contact-form fade-in-up">
                <h3>Request a Demo</h3>
                <p>Fill out this form to see how the new design would look for your website.</p>
                
                <form>
                    <div class="form-group">
                        <label class="form-label" for="demo-name">Full Name</label>
                        <input type="text" id="demo-name" class="form-control" placeholder="Enter your name">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="demo-email">Email Address</label>
                        <input type="email" id="demo-email" class="form-control" placeholder="Enter your email">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="demo-message">Message</label>
                        <textarea id="demo-message" class="form-control" rows="4" placeholder="Tell us about your project"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Request Demo</button>
                </form>
            </div>
            
            <div class="fade-in-up delay-1">
                <div class="card">
                    <h3>Design Benefits</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 1rem; padding-left: 1.5rem; position: relative;">
                            <i class="fas fa-check" style="position: absolute; left: 0; top: 0.3rem; color: var(--accent-gold);"></i>
                            <strong>Professional Appearance:</strong> Creates a strong, authoritative presence
                        </li>
                        <li style="margin-bottom: 1rem; padding-left: 1.5rem; position: relative;">
                            <i class="fas fa-check" style="position: absolute; left: 0; top: 0.3rem; color: var(--accent-gold);"></i>
                            <strong>High Contrast:</strong> Excellent readability and accessibility
                        </li>
                        <li style="margin-bottom: 1rem; padding-left: 1.5rem; position: relative;">
                            <i class="fas fa-check" style="position: absolute; left: 0; top: 0.3rem; color: var(--accent-gold);"></i>
                            <strong>Brand Differentiation:</strong> Stands out from typical colorful designs
                        </li>
                        <li style="margin-bottom: 1rem; padding-left: 1.5rem; position: relative;">
                            <i class="fas fa-check" style="position: absolute; left: 0; top: 0.3rem; color: var(--accent-gold);"></i>
                            <strong>Versatile:</strong> Works for any industry or business type
                        </li>
                        <li style="padding-left: 1.5rem; position: relative;">
                            <i class="fas fa-check" style="position: absolute; left: 0; top: 0.3rem; color: var(--accent-gold);"></i>
                            <strong>Easy to Maintain:</strong> Simple color scheme reduces maintenance
                        </li>
                    </ul>
                </div>
                
                <div class="card" style="margin-top: 2rem;">
                    <h3>Implementation</h3>
                    <p>To switch to this new design:</p>
                    <ol style="padding-left: 1.5rem;">
                        <li style="margin-bottom: 0.5rem;">Update your website controller</li>
                        <li style="margin-bottom: 0.5rem;">Modify your routes file</li>
                        <li style="margin-bottom: 0.5rem;">Rebuild your assets with Vite</li>
                        <li>Deploy the changes to production</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection