@extends('layouts.modern')

@section('content')
<!-- Hero Section -->
<section class="hero-section fade-in-up">
    <div class="hero-container">
        <h1 class="hero-title">
            Architecting the<br>Future
        </h1>
        <p class="hero-subtitle">
            We are a modern IT solutions provider, delivering cutting-edge software development, cloud architecture, cybersecurity, and AI-powered solutions that transform businesses and drive innovation.
        </p>
        <a href="#contact" class="hero-cta">
            <i class="fas fa-rocket"></i>
            Explore Solutions
        </a>
    </div>
</section>

<!-- Our Expertise Section -->
<section class="section" id="expertise">
    <div class="section-container">
        <div class="section-header">
            <h2 class="section-title">Our Expertise</h2>
            <p class="section-subtitle">
                We combine deep technical knowledge with innovative thinking to deliver solutions that exceed expectations
            </p>
        </div>
        
        <div class="card-grid">
            <div class="glass-card fade-in-up">
                <div class="card-icon">
                    <i class="fas fa-code"></i>
                </div>
                <h3 class="card-title">Software Development</h3>
                <p class="card-description">
                    Custom software solutions built with modern technologies. From web applications to enterprise systems, we create scalable, maintainable, and high-performance software tailored to your business needs.
                </p>
            </div>
            
            <div class="glass-card fade-in-up float-animation">
                <div class="card-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 class="card-title">Mobile Applications</h3>
                <p class="card-description">
                    Native and cross-platform mobile apps that deliver exceptional user experiences. We build iOS and Android applications that engage users and drive business growth.
                </p>
            </div>
            
            <div class="glass-card fade-in-up">
                <div class="card-icon">
                    <i class="fas fa-database"></i>
                </div>
                <h3 class="card-title">Database Solutions</h3>
                <p class="card-description">
                    Robust database architecture and management. We design, implement, and optimize database systems that ensure data integrity, security, and lightning-fast performance.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="section animated-section" id="services" style="background: var(--dark-bg-secondary); position: relative; overflow: hidden;">
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="bg-orb orb-1"></div>
        <div class="bg-orb orb-2"></div>
        <div class="bg-orb orb-3"></div>
    </div>
    
    <div class="section-container" style="position: relative; z-index: 1;">
        <div class="section-header">
            <h2 class="section-title">Our Services</h2>
            <p class="section-subtitle">
                Comprehensive technology solutions designed to propel your business forward
            </p>
        </div>
        
        <div class="card-grid">
            @forelse($services as $index => $service)
            <div class="glass-card fade-in-up {{ $index % 3 == 1 ? 'float-animation' : '' }}">
                <div class="card-icon">
                    @if($service->picture)
                        <img src="{{ asset('storage/services/' . $service->picture) }}" alt="{{ $service->name }}" style="width: 40px; height: 40px; object-fit: cover;">
                    @else
                        <i class="fas fa-cogs"></i>
                    @endif
                </div>
                <h3 class="card-title">{{ $service->name }}</h3>
                <p class="card-description">
                    {{ Str::limit($service->description, 200) }}
                </p>
            </div>
            @empty
            <!-- Fallback services if database is empty -->
            <div class="glass-card fade-in-up">
                <div class="card-icon">
                    <i class="fas fa-cloud"></i>
                </div>
                <h3 class="card-title">Cloud Architecture</h3>
                <p class="card-description">
                    Design and implement scalable cloud infrastructure on AWS, Azure, and Google Cloud.
                </p>
            </div>
            
            <div class="glass-card fade-in-up">
                <div class="card-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="card-title">Cybersecurity</h3>
                <p class="card-description">
                    Protect your digital assets with comprehensive security solutions.
                </p>
            </div>
            
            <div class="glass-card fade-in-up float-animation">
                <div class="card-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <h3 class="card-title">AI & Intelligence</h3>
                <p class="card-description">
                    Harness the power of artificial intelligence and machine learning.
                </p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Technology Stack Section -->
<section class="section">
    <div class="section-container">
        <div class="section-header">
            <h2 class="section-title">Technology Stack</h2>
            <p class="section-subtitle">
                We work with cutting-edge technologies to deliver world-class solutions
            </p>
        </div>
        
        <div class="card-grid">
            <div class="glass-card fade-in-up">
                <div class="card-icon">
                    <i class="fab fa-laravel"></i>
                </div>
                <h3 class="card-title">Backend Development</h3>
                <p class="card-description">
                    Laravel, Node.js, Python, PHP - Building robust server-side applications with modern frameworks and best practices.
                </p>
            </div>
            
            <div class="glass-card fade-in-up">
                <div class="card-icon">
                    <i class="fab fa-react"></i>
                </div>
                <h3 class="card-title">Frontend Development</h3>
                <p class="card-description">
                    React, Vue.js, Angular, Tailwind CSS - Creating beautiful, responsive, and performant user interfaces.
                </p>
            </div>
            
            <div class="glass-card fade-in-up">
                <div class="card-icon">
                    <i class="fab fa-docker"></i>
                </div>
                <h3 class="card-title">DevOps & Infrastructure</h3>
                <p class="card-description">
                    Docker, Kubernetes, CI/CD, AWS - Automating deployments and managing infrastructure as code.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Digital Products Section -->
<section class="section products-section animated-section" style="position: relative; overflow: hidden;">
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="bg-orb orb-4"></div>
        <div class="bg-orb orb-5"></div>
        <div class="bg-orb orb-6"></div>
    </div>
    
    <div class="section-container" style="position: relative; z-index: 1;">
        <div class="section-header">
            <h2 class="section-title">Our Digital Products</h2>
            <p class="section-subtitle">
                Ready-to-deploy solutions tailored to your business needs
            </p>
        </div>
        
        <div class="products-grid">
            @forelse($products as $product)
            <div class="product-card glass-card fade-in-up">
                <div class="product-icon">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                    @elseif($product->icon)
                        <i class="{{ $product->icon }}"></i>
                    @else
                        <i class="fa-solid fa-box"></i>
                    @endif
                </div>
                <h3 class="product-name">{{ $product->name }}</h3>
                @if($product->short_description)
                <p class="product-tagline">{{ $product->short_description }}</p>
                @endif
                <p class="product-description">{{ Str::limit($product->description, 120) }}</p>
                <div class="product-footer">
                    <div class="product-price">
                        <span class="price-label">Starting at</span>
                        <span class="price-amount">${{ $product->formatted_price }}</span>
                    </div>
                    @if($product->url)
                    <a href="{{ $product->url }}" class="btn-gradient btn-sm">
                        Learn More <i class="fas fa-arrow-right"></i>
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-box-open" style="font-size: 4rem; color: var(--primary-color); opacity: 0.5; margin-bottom: 1rem;"></i>
                <p style="color: var(--text-secondary); font-size: 1.1rem;">No products available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Portfolio Preview Section -->
<section class="section" style="background: var(--dark-bg-secondary);">
    <div class="section-container">
        <div class="section-header">
            <h2 class="section-title">Featured Projects</h2>
            <p class="section-subtitle">
                Explore our successful implementations across various industries
            </p>
        </div>
        
        <div class="card-grid">
            @forelse($galleries->take(3) as $gallery)
            <div class="glass-card fade-in-up">
                <div class="card-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <h3 class="card-title">{{ $gallery->title }}</h3>
                <p class="card-description">{{ Str::limit($gallery->description, 150) }}</p>
            </div>
            @empty
            <div class="glass-card fade-in-up">
                <div class="card-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <h3 class="card-title">Enterprise Management System</h3>
                <p class="card-description">
                    A comprehensive ERP solution for manufacturing companies, streamlining operations and improving efficiency.
                </p>
            </div>
            
            <div class="glass-card fade-in-up">
                <div class="card-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 class="card-title">Mobile Banking App</h3>
                <p class="card-description">
                    Secure and user-friendly mobile banking application serving thousands of customers daily.
                </p>
            </div>
            
            <div class="glass-card fade-in-up">
                <div class="card-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 class="card-title">E-Commerce Platform</h3>
                <p class="card-description">
                    Scalable online marketplace connecting buyers and sellers with advanced features and analytics.
                </p>
            </div>
            @endforelse
        </div>
        
        <div style="text-align: center; margin-top: var(--spacing-xl);">
            <a href="{{ route('public.gallery') }}" class="hero-cta">
                <i class="fas fa-images"></i>
                View All Projects
            </a>
        </div>
    </div>
</section>

<!-- Clients Section -->
<section class="section">
    <div class="section-container">
        <div class="section-header">
            <h2 class="section-title">Trusted By</h2>
            <p class="section-subtitle">
                Companies and organizations that trust iGEO for their technology needs
            </p>
        </div>
        
        <div class="card-grid">
            @forelse($publicClients->take(6) as $client)
                <div class="glass-card fade-in-up text-center" style="padding: var(--spacing-lg);">
                    <div style="display: flex; justify-content: center; align-items: center; min-height: 100px;">
                        @if(isset($client->logo) && $client->logo)
                            <!-- Display actual logo if available -->
                            <img src="{{ asset('storage/' . $client->logo) }}" 
                                 alt="{{ $client->company ?: $client->name }}" 
                                 style="max-width: 120px; max-height: 60px; object-fit: contain;"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <!-- Fallback to company name if logo fails -->
                            <div style="display: none; font-size: 1.25rem; font-weight: 600; color: var(--text-primary);">
                                {{ $client->company ?: $client->name }}
                            </div>
                        @else
                            <!-- Display company name as text logo -->
                            <div style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary);">
                                {{ $client->company ?: $client->name }}
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="glass-card text-center" style="padding: var(--spacing-xl); grid-column: 1 / -1;">
                    <p class="card-description">Building partnerships with innovative companies worldwide</p>
                </div>
            @endforelse
        </div>
        
        <div style="text-align: center; margin-top: var(--spacing-xl);">
            <a href="{{ route('public.clients') }}" class="modern-btn-outline" style="padding: var(--spacing-sm) var(--spacing-lg); display: inline-flex;">
                <i class="fas fa-users"></i> View All Clients
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section" id="contact">
    <div class="cta-container">
        <h2 class="cta-title">Ready to Innovate?</h2>
        <p class="cta-subtitle">
            Let's build something amazing together. Get in touch with our team to discuss your project and discover how we can help you achieve your goals.
        </p>
        <a href="#contact-form" class="cta-button">
            <i class="fas fa-paper-plane"></i>
            Start Your Project
        </a>
    </div>
</section>

<!-- Contact Form Section -->
<section class="section" id="contact-form">
    <div class="section-container">
        <div class="section-header">
            <h2 class="section-title">Get In Touch</h2>
            <p class="section-subtitle">
                Fill out the form below and our team will get back to you within 24 hours
            </p>
        </div>
        
        <div style="max-width: 800px; margin: 0 auto;">
            <div class="glass-card">
                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <div style="margin-bottom: var(--spacing-md);">
                        <label style="display: block; margin-bottom: var(--spacing-xs); font-weight: 600;">Full Name *</label>
                        <input type="text" name="name" required 
                               style="width: 100%; padding: var(--spacing-sm); background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: var(--radius-sm); color: var(--text-primary); font-size: 1rem;">
                    </div>
                    
                    <div style="margin-bottom: var(--spacing-md);">
                        <label style="display: block; margin-bottom: var(--spacing-xs); font-weight: 600;">Email Address *</label>
                        <input type="email" name="email" required 
                               style="width: 100%; padding: var(--spacing-sm); background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: var(--radius-sm); color: var(--text-primary); font-size: 1rem;">
                    </div>
                    
                    <div style="margin-bottom: var(--spacing-md);">
                        <label style="display: block; margin-bottom: var(--spacing-xs); font-weight: 600;">Phone Number</label>
                        <input type="tel" name="phone" 
                               style="width: 100%; padding: var(--spacing-sm); background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: var(--radius-sm); color: var(--text-primary); font-size: 1rem;">
                    </div>
                    
                    <div style="margin-bottom: var(--spacing-md);">
                        <label style="display: block; margin-bottom: var(--spacing-xs); font-weight: 600;">Service Interest</label>
                        <select name="project_type" 
                                style="width: 100%; padding: var(--spacing-sm); background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: var(--radius-sm); color: var(--text-primary); font-size: 1rem;">
                            <option value="">Select a service</option>
                            @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div style="margin-bottom: var(--spacing-md);">
                        <label style="display: block; margin-bottom: var(--spacing-xs); font-weight: 600;">Project Details *</label>
                        <textarea name="message" rows="5" required 
                                  style="width: 100%; padding: var(--spacing-sm); background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: var(--radius-sm); color: var(--text-primary); font-size: 1rem; resize: vertical;"></textarea>
                    </div>
                    
                    <button type="submit" class="hero-cta" style="width: 100%; justify-content: center;">
                        <i class="fas fa-paper-plane"></i>
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
