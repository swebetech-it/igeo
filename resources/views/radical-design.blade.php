@extends('layouts.website-radical')

@section('content')
<!-- Hero Section -->
<section class="hero-radical">
    <!-- Floating Elements -->
    <div class="floating-element floating-1 pulse"></div>
    <div class="floating-element floating-2"></div>
    <div class="floating-element floating-3"></div>
    
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-glitch" data-text="RADICAL DESIGN">
                RADICAL DESIGN
            </h1>
            <p class="hero-p">This is a completely different aesthetic from the original website, featuring an asymmetrical layout, cyberpunk color scheme, and futuristic design elements.</p>
            <div class="hero-btns">
                <a href="#features" class="cyber-btn cyber-btn-primary">
                    <i class="fas fa-rocket mr-2"></i> EXPLORE FEATURES
                </a>
                <a href="#contact" class="cyber-btn cyber-btn-secondary">
                    <i class="fas fa-flask mr-2"></i> TRY NOW
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section">
    <div class="container">
        <h2 class="text-center">DESIGN PRINCIPLES</h2>
        <p class="text-center" style="max-width: 700px; margin: 0 auto 3rem;">What makes this design radically different from conventional websites.</p>
        
        <div class="asym-grid">
            <div class="asym-item">
                <div class="neon-card">
                    <div class="neon-card-icon">
                        <i class="fas fa-border-none"></i>
                    </div>
                    <h3>ASYMMETRICAL GRID</h3>
                    <p>Breaks traditional symmetrical layouts with dynamic, flowing grid structures that create visual interest.</p>
                </div>
            </div>
            
            <div class="asym-item">
                <div class="neon-card">
                    <div class="neon-card-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3>CYBERPUNK PALETTE</h3>
                    <p>Vibrant neon colors against dark backgrounds create a high-contrast, futuristic aesthetic.</p>
                </div>
            </div>
            
            <div class="asym-item">
                <div class="neon-card">
                    <div class="neon-card-icon">
                        <i class="fas fa-font"></i>
                    </div>
                    <h3>FUTURISTIC TYPOGRAPHY</h3>
                    <p>Tech-inspired fonts with glowing effects and glitch animations for a digital-native feel.</p>
                </div>
            </div>
            
            <div class="asym-item">
                <div class="neon-card">
                    <div class="neon-card-icon">
                        <i class="fas fa-wave-square"></i>
                    </div>
                    <h3>DYNAMIC ANIMATIONS</h3>
                    <p>Subtle but engaging animations including floating elements, pulsing effects, and glitch text.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Diagonal Section -->
<section class="diagonal-section">
    <div class="diagonal-bg"></div>
    <div class="diagonal-content">
        <div class="container">
            <h2 class="text-center">VISUAL ELEMENTS</h2>
            <p class="text-center" style="max-width: 700px; margin: 0 auto 3rem;">Key components that define the radical aesthetic.</p>
            
            <div class="asym-grid">
                <div class="asym-item">
                    <div class="neon-card">
                        <div style="height: 150px; background: linear-gradient(135deg, var(--dark-purple), var(--deep-black)); display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                            <i class="fas fa-square" style="font-size: 3rem; color: var(--electric-blue);"></i>
                        </div>
                        <h3>GLASS CARDS</h3>
                        <p>Translucent panels with neon borders and glowing effects create depth and dimension.</p>
                    </div>
                </div>
                
                <div class="asym-item">
                    <div class="neon-card">
                        <div style="height: 150px; background: linear-gradient(135deg, var(--dark-purple), var(--deep-black)); display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                            <i class="fas fa-heading" style="font-size: 3rem; color: var(--hot-pink);"></i>
                        </div>
                        <h3>GRADIENT TEXT</h3>
                        <p>Headlines with vibrant color gradients that shift across the spectrum for visual impact.</p>
                    </div>
                </div>
                
                <div class="asym-item">
                    <div class="neon-card">
                        <div style="height: 150px; background: linear-gradient(135deg, var(--dark-purple), var(--deep-black)); display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                            <i class="fas fa-th-large" style="font-size: 3rem; color: var(--neon-green);"></i>
                        </div>
                        <h3>DIAGONAL SECTIONS</h3>
                        <p>Skewed background sections that break the traditional horizontal layout flow.</p>
                    </div>
                </div>
                
                <div class="asym-item">
                    <div class="neon-card">
                        <div style="height: 150px; background: linear-gradient(135deg, var(--dark-purple), var(--deep-black)); display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                            <i class="fas fa-mobile" style="font-size: 3rem; color: var(--cyber-orange);"></i>
                        </div>
                        <h3>RESPONSIVE ASYMMETRY</h3>
                        <p>Layout adapts to different screen sizes while maintaining the asymmetrical aesthetic.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="section">
    <div class="container">
        <h2 class="text-center">IMPLEMENTATION</h2>
        <p class="text-center" style="max-width: 700px; margin: 0 auto 3rem;">How to integrate this radical design into your website.</p>
        
        <div class="asym-grid">
            <div class="asym-item">
                <div class="cyber-form">
                    <h3>REQUEST DEMO</h3>
                    <p>See how this design would transform your website.</p>
                    
                    <form>
                        <div class="form-group">
                            <label class="form-label" for="demo-name">FULL NAME</label>
                            <input type="text" id="demo-name" class="form-control" placeholder="Enter your name">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="demo-email">EMAIL ADDRESS</label>
                            <input type="email" id="demo-email" class="form-control" placeholder="Enter your email">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="demo-message">PROJECT DETAILS</label>
                            <textarea id="demo-message" class="form-control" rows="4" placeholder="Tell us about your project"></textarea>
                        </div>
                        
                        <button type="submit" class="cyber-btn cyber-btn-primary">
                            <i class="fas fa-paper-plane mr-2"></i> REQUEST DEMO
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="asym-item">
                <div class="neon-card">
                    <h3>BENEFITS</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 1rem; padding-left: 1.5rem; position: relative;">
                            <i class="fas fa-bolt" style="position: absolute; left: 0; top: 0.3rem; color: var(--neon-green);"></i>
                            <strong>MEMORABLE:</strong> Stands out from generic website designs
                        </li>
                        <li style="margin-bottom: 1rem; padding-left: 1.5rem; position: relative;">
                            <i class="fas fa-eye" style="position: absolute; left: 0; top: 0.3rem; color: var(--neon-green);"></i>
                            <strong>ATTENTION GRABBING:</strong> Bold visuals that captivate visitors
                        </li>
                        <li style="margin-bottom: 1rem; padding-left: 1.5rem; position: relative;">
                            <i class="fas fa-sync-alt" style="position: absolute; left: 0; top: 0.3rem; color: var(--neon-green);"></i>
                            <strong>MODERN:</strong> Reflects contemporary design trends
                        </li>
                        <li style="margin-bottom: 1rem; padding-left: 1.5rem; position: relative;">
                            <i class="fas fa-code" style="position: absolute; left: 0; top: 0.3rem; color: var(--neon-green);"></i>
                            <strong>PERFORMANT:</strong> Lightweight CSS with modern techniques
                        </li>
                        <li style="padding-left: 1.5rem; position: relative;">
                            <i class="fas fa-mobile-alt" style="position: absolute; left: 0; top: 0.3rem; color: var(--neon-green);"></i>
                            <strong>RESPONSIVE:</strong> Adapts to all device sizes seamlessly
                        </li>
                    </ul>
                    
                    <h3 style="margin-top: 2rem;">SWITCHING TO THIS DESIGN</h3>
                    <ol style="padding-left: 1.5rem;">
                        <li style="margin-bottom: 0.5rem;">Update your routes file</li>
                        <li style="margin-bottom: 0.5rem;">Modify your controller methods</li>
                        <li style="margin-bottom: 0.5rem;">Run asset compilation with Vite</li>
                        <li>Deploy changes to production</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection