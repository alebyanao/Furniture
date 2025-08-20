document.addEventListener('DOMContentLoaded', function() {
    // Check if hero image swiper exists
    const heroImageSwiper = document.querySelector('.hero-image-swiper');
    
    if (heroImageSwiper) {
        // Initialize Swiper for hero images
        const swiper = new Swiper('.hero-image-swiper', {
            loop: true,
            autoplay: {
                delay: 4000, // 4 seconds
                disableOnInteraction: false,
                pauseOnMouseEnter: false,
            },
            speed: 1000, // 1 second transition
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            allowTouchMove: false, // Disable manual swiping
            // Remove indicators and controls
            pagination: false,
            navigation: false,
        });

        // Optional: Add entrance animation to hero section
        const heroSection = document.querySelector('.hero-section');
        if (heroSection) {
            heroSection.style.opacity = '0';
            heroSection.style.transform = 'translateY(20px)';
            heroSection.style.transition = 'all 0.8s ease';
            
            setTimeout(() => {
                heroSection.style.opacity = '1';
                heroSection.style.transform = 'translateY(0)';
            }, 100);
        }
    }
});

// Auto-populate product ID in review form
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('reviewModal');
    if (modal) {
        modal.addEventListener('show.bs.modal', function() {
            const productIdField = document.querySelector('input[name="ProductID"]');
            if (productIdField) {
                productIdField.value = '$Product.ID';
            }
        });
    }
});

// Unified Carousel functionality with Auto-play
let carouselPositions = {};
let carouselIntervals = {};
let userInteractionTimers = {};

// Initialize carousels when DOM is loaded - multiple attempts for reliability
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing carousels...');
    initializeCarousels();
    setupHoverEvents();
    setupResizeHandler();
});

// Backup initialization in case DOMContentLoaded already fired
if (document.readyState === 'loading') {
    // DOM is still loading
} else {
    // DOM is already loaded
    setTimeout(initializeCarousels, 100);
}

function initializeCarousels() {
    console.log('Initializing carousels...');
    
    // Force show all carousel buttons first
    const allButtons = document.querySelectorAll('.carousel-btn');
    allButtons.forEach(btn => {
        btn.style.display = 'flex';
        btn.style.opacity = '1';
        btn.style.visibility = 'visible';
        btn.style.zIndex = '9999';
    });
    
    const carousels = document.querySelectorAll('.product-carousel');
    console.log('Found carousels:', carousels.length);
    
    carousels.forEach(carousel => {
        const carouselId = carousel.id;
        console.log('Processing carousel:', carouselId);
        
        // Initialize position
        carouselPositions[carouselId] = 0;
        
        // Get carousel elements
        const wrapper = carousel.querySelector('.product-carousel-wrapper');
        const slides = carousel.querySelectorAll('.product-carousel-slide');
        const container = carousel.closest('.product-carousel-container');
        
        console.log(`Carousel ${carouselId}:`, {
            slides: slides.length,
            hasWrapper: !!wrapper,
            hasContainer: !!container
        });
        
        if (!wrapper || !container) {
            console.error(`Missing elements for carousel ${carouselId}`);
            return;
        }
        
        // Get visible slides count based on screen size
        const visibleSlides = getVisibleSlidesCount();
        console.log(`Visible slides for ${carouselId}: ${visibleSlides}`);
        
        // Check if navigation is needed
        if (slides.length <= visibleSlides) {
            console.log(`Hiding navigation for ${carouselId} - only ${slides.length} slides`);
            container.classList.add('hide-nav');
            stopAutoPlay(carouselId);
        } else {
            console.log(`Showing navigation for ${carouselId} - ${slides.length} slides`);
            container.classList.remove('hide-nav');
            
            // Force show buttons for this carousel
            const navButtons = container.querySelectorAll('.carousel-btn');
            navButtons.forEach(btn => {
                btn.style.display = 'flex';
                btn.style.opacity = '1';
                btn.style.visibility = 'visible';
            });
            
            // Start auto-play for non-bestseller carousels
            if (!carouselId.includes('bestseller')) {
                console.log(`Starting auto-play for ${carouselId}`);
                setTimeout(() => startAutoPlay(carouselId), 2000);
            }
        }
        
        // Reset wrapper transform
        if (wrapper) {
            wrapper.style.transform = 'translateX(0%)';
        }
    });
    
    console.log('Carousel initialization complete');
}

function getVisibleSlidesCount() {
    if (window.innerWidth <= 576) return 1;
    if (window.innerWidth <= 768) return 2;
    if (window.innerWidth <= 1200) return 3;
    return 4;
}

function moveCarousel(carouselId, direction, isAutoPlay = false) {
    console.log(`Moving carousel ${carouselId}, direction: ${direction}, auto: ${isAutoPlay}`);
    const carousel = document.getElementById(carouselId);
    if (!carousel) {
        console.log(`Carousel ${carouselId} not found`);
        return;
    }
    
    const wrapper = carousel.querySelector('.product-carousel-wrapper');
    const slides = carousel.querySelectorAll('.product-carousel-slide');
    const visibleSlides = getVisibleSlidesCount();
    const maxPosition = Math.max(0, slides.length - visibleSlides);
    
    if (!carouselPositions[carouselId]) {
        carouselPositions[carouselId] = 0;
    }
    
    carouselPositions[carouselId] += direction;
    
    // Auto-play loop behavior
    if (isAutoPlay) {
        if (carouselPositions[carouselId] > maxPosition) {
            carouselPositions[carouselId] = 0; // Reset to beginning
        }
        if (carouselPositions[carouselId] < 0) {
            carouselPositions[carouselId] = maxPosition; // Go to end
        }
    } else {
        // Manual control boundary checks
        if (carouselPositions[carouselId] < 0) {
            carouselPositions[carouselId] = 0;
        }
        if (carouselPositions[carouselId] > maxPosition) {
            carouselPositions[carouselId] = maxPosition;
        }
    }
    
    // Calculate transform percentage
    const slideWidth = 100 / visibleSlides;
    const transformX = -(carouselPositions[carouselId] * slideWidth);
    
    console.log(`Moving to position ${carouselPositions[carouselId]}, transform: ${transformX}%`);
    wrapper.style.transform = `translateX(${transformX}%)`;
}

function startAutoPlay(carouselId) {
    console.log(`Starting auto-play for ${carouselId}`);
    
    // Don't start auto-play for Best Sellers section
    if (carouselId.includes('bestseller')) {
        console.log(`Skipping auto-play for ${carouselId} - Best Sellers excluded`);
        return;
    }
    
    // Clear existing interval
    if (carouselIntervals[carouselId]) {
        clearInterval(carouselIntervals[carouselId]);
    }
    
    // Start new interval
    carouselIntervals[carouselId] = setInterval(() => {
        // Check if user is not currently interacting
        if (!userInteractionTimers[carouselId]) {
            moveCarousel(carouselId, 1, true);
        }
    }, 4000); // 4 seconds per slide
    
    // Add visual indicator
    const carousel = document.getElementById(carouselId);
    if (carousel) {
        const container = carousel.closest('.carousel-outer-container');
        const navButtons = container.querySelectorAll('.carousel-btn');
        navButtons.forEach(btn => btn.classList.add('auto-playing'));
    }
}

function stopAutoPlay(carouselId) {
    console.log(`Stopping auto-play for ${carouselId}`);
    if (carouselIntervals[carouselId]) {
        clearInterval(carouselIntervals[carouselId]);
        delete carouselIntervals[carouselId];
    }
    
    // Remove visual indicator
    const carousel = document.getElementById(carouselId);
    if (carousel) {
        const container = carousel.closest('.carousel-outer-container');
        const navButtons = container.querySelectorAll('.carousel-btn');
        navButtons.forEach(btn => btn.classList.remove('auto-playing'));
    }
}

function handleCarouselClick(carouselId, direction) {
    console.log(`Manual click for ${carouselId}, direction: ${direction}`);
    
    // Set user interaction flag
    userInteractionTimers[carouselId] = true;
    
    // Move carousel
    moveCarousel(carouselId, direction);
    
    // Clear any existing user interaction timer
    if (userInteractionTimers[carouselId + '_timeout']) {
        clearTimeout(userInteractionTimers[carouselId + '_timeout']);
    }
    
    // Set timer to resume auto-play after 8 seconds of inactivity
    userInteractionTimers[carouselId + '_timeout'] = setTimeout(() => {
        delete userInteractionTimers[carouselId];
        delete userInteractionTimers[carouselId + '_timeout'];
        
        // Resume auto-play if carousel has enough slides
        const carousel = document.getElementById(carouselId);
        if (carousel) {
            const slides = carousel.querySelectorAll('.product-carousel-slide');
            const visibleSlides = getVisibleSlidesCount();
            
            if (slides.length > visibleSlides && !carouselId.includes('bestseller')) {
                startAutoPlay(carouselId);
            }
        }
    }, 8000);
}

function setupHoverEvents() {
    const carouselContainers = document.querySelectorAll('.carousel-outer-container');
    
    carouselContainers.forEach(container => {
        const carousel = container.querySelector('.product-carousel');
        if (!carousel) return;
        
        const carouselId = carousel.id;
        
        // Pause auto-play on hover
        container.addEventListener('mouseenter', () => {
            console.log(`Mouse entered ${carouselId} - pausing auto-play`);
            if (carouselIntervals[carouselId]) {
                clearInterval(carouselIntervals[carouselId]);
            }
        });
        
        // Resume auto-play on mouse leave (with delay)
        container.addEventListener('mouseleave', () => {
            console.log(`Mouse left ${carouselId} - resuming auto-play`);
            
            // Only resume if user is not actively clicking buttons
            if (!userInteractionTimers[carouselId]) {
                setTimeout(() => {
                    const slides = carousel.querySelectorAll('.product-carousel-slide');
                    const visibleSlides = getVisibleSlidesCount();
                    
                    if (slides.length > visibleSlides && !carouselId.includes('bestseller')) {
                        startAutoPlay(carouselId);
                    }
                }, 1000);
            }
        });
    });
}

function setupResizeHandler() {
    let resizeTimer;
    
    window.addEventListener('resize', () => {
        console.log('Window resized - updating carousels');
        
        // Debounce resize events
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            // Stop all auto-play during resize
            Object.keys(carouselIntervals).forEach(carouselId => {
                stopAutoPlay(carouselId);
            });
            
            // Reinitialize carousels with new screen size
            setTimeout(() => {
                initializeCarousels();
            }, 500);
        }, 250);
    });
}

// Touch/swipe support for mobile devices
function setupTouchEvents() {
    const carousels = document.querySelectorAll('.product-carousel');
    
    carousels.forEach(carousel => {
        const carouselId = carousel.id;
        let touchStartX = 0;
        let touchEndX = 0;
        let isSwiping = false;
        
        carousel.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
            isSwiping = true;
            
            // Pause auto-play during touch
            if (carouselIntervals[carouselId]) {
                clearInterval(carouselIntervals[carouselId]);
            }
        }, { passive: true });
        
        carousel.addEventListener('touchend', (e) => {
            if (!isSwiping) return;
            
            touchEndX = e.changedTouches[0].screenX;
            handleSwipeGesture(carouselId, touchStartX, touchEndX);
            
            isSwiping = false;
            
            // Resume auto-play after swipe
            setTimeout(() => {
                const slides = carousel.querySelectorAll('.product-carousel-slide');
                const visibleSlides = getVisibleSlidesCount();
                
                if (slides.length > visibleSlides && !carouselId.includes('bestseller')) {
                    startAutoPlay(carouselId);
                }
            }, 3000);
        }, { passive: true });
        
        carousel.addEventListener('touchcancel', () => {
            isSwiping = false;
        });
    });
}

function handleSwipeGesture(carouselId, startX, endX) {
    const minSwipeDistance = 50;
    const swipeDistance = Math.abs(startX - endX);
    
    if (swipeDistance < minSwipeDistance) return;
    
    if (startX > endX) {
        // Swipe left - next slide
        handleCarouselClick(carouselId, 1);
    } else {
        // Swipe right - previous slide
        handleCarouselClick(carouselId, -1);
    }
}

// Keyboard navigation support
function setupKeyboardEvents() {
    document.addEventListener('keydown', (e) => {
        // Only handle arrow keys when focus is on carousel buttons
        if (!e.target.classList.contains('carousel-btn')) return;
        
        const container = e.target.closest('.carousel-outer-container');
        if (!container) return;
        
        const carousel = container.querySelector('.product-carousel');
        if (!carousel) return;
        
        const carouselId = carousel.id;
        
        switch(e.key) {
            case 'ArrowLeft':
                e.preventDefault();
                handleCarouselClick(carouselId, -1);
                break;
            case 'ArrowRight':
                e.preventDefault();
                handleCarouselClick(carouselId, 1);
                break;
        }
    });
}

// Performance optimization: Intersection Observer for lazy initialization
function setupIntersectionObserver() {
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const carousel = entry.target.querySelector('.product-carousel');
                    if (carousel && !carouselIntervals[carousel.id]) {
                        const slides = carousel.querySelectorAll('.product-carousel-slide');
                        const visibleSlides = getVisibleSlidesCount();
                        
                        if (slides.length > visibleSlides && !carousel.id.includes('bestseller')) {
                            setTimeout(() => startAutoPlay(carousel.id), 1000);
                        }
                    }
                }
            });
        }, {
            threshold: 0.5,
            rootMargin: '50px'
        });
        
        // Observe all carousel containers
        document.querySelectorAll('.carousel-outer-container').forEach(container => {
            observer.observe(container);
        });
    }
}

// Error handling and fallbacks
function handleCarouselError(carouselId, error) {
    console.error(`Error in carousel ${carouselId}:`, error);
    
    // Stop auto-play on error
    stopAutoPlay(carouselId);
    
    // Try to reset carousel position
    try {
        const carousel = document.getElementById(carouselId);
        if (carousel) {
            const wrapper = carousel.querySelector('.product-carousel-wrapper');
            if (wrapper) {
                wrapper.style.transform = 'translateX(0%)';
                carouselPositions[carouselId] = 0;
            }
        }
    } catch (resetError) {
        console.error(`Failed to reset carousel ${carouselId}:`, resetError);
    }
}

// Initialize everything when DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing all carousel features...');
    
    try {
        initializeCarousels();
        setupHoverEvents();
        setupResizeHandler();
        setupTouchEvents();
        setupKeyboardEvents();
        setupIntersectionObserver();
        
        console.log('All carousel features initialized successfully');
    } catch (error) {
        console.error('Error initializing carousels:', error);
    }
});

// Clean up intervals when page unloads
window.addEventListener('beforeunload', () => {
    Object.keys(carouselIntervals).forEach(carouselId => {
        stopAutoPlay(carouselId);
    });
    
    Object.keys(userInteractionTimers).forEach(key => {
        if (key.includes('_timeout')) {
            clearTimeout(userInteractionTimers[key]);
        }
    });
});

// Expose functions globally for onclick handlers
window.handleCarouselClick = handleCarouselClick;
window.initializeCarousels = initializeCarousels;