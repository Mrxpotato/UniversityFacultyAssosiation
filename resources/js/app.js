import './bootstrap';
import Alpine from 'alpinejs';

// =====================================================
// UNIVERSITY FACULTY ASSOCIATION - PREMIUM ANIMATIONS
// International Contest Ready - 2026 Edition
// =====================================================

// Initialize Alpine.js
window.Alpine = Alpine;

// ==================== Alpine.js Custom Directives ====================

// Smooth reveal on scroll
Alpine.directive('reveal', (el, { expression, modifiers }) => {
    const delay = modifiers.includes('delay') ? parseInt(expression) || 200 : 0;
    const direction = modifiers.find(m => ['up', 'down', 'left', 'right', 'scale'].includes(m)) || 'up';
    
    const transforms = {
        up: 'translateY(40px)',
        down: 'translateY(-40px)',
        left: 'translateX(-40px)',
        right: 'translateX(40px)',
        scale: 'scale(0.9)'
    };

    el.style.opacity = '0';
    el.style.transform = transforms[direction];
    el.style.transition = `all 0.8s cubic-bezier(0.4, 0, 0.2, 1) ${delay}ms`;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translate(0) scale(1)';
                }, 50);
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    observer.observe(el);
});

// Stagger children animation
Alpine.directive('stagger', (el, { expression }) => {
    const delay = parseInt(expression) || 100;
    const children = el.children;
    
    Array.from(children).forEach((child, index) => {
        child.style.opacity = '0';
        child.style.transform = 'translateY(30px)';
        child.style.transition = `all 0.6s cubic-bezier(0.4, 0, 0.2, 1) ${index * delay}ms`;
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                Array.from(children).forEach((child) => {
                    child.style.opacity = '1';
                    child.style.transform = 'translateY(0)';
                });
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.1 });

    observer.observe(el);
});

// Counter animation
Alpine.directive('counter', (el, { expression }) => {
    const target = parseInt(expression) || parseInt(el.textContent) || 0;
    const duration = 2000;
    const isDecimal = el.dataset.decimal === 'true';
    const prefix = el.dataset.prefix || '';
    const suffix = el.dataset.suffix || '';
    
    el.textContent = prefix + '0' + suffix;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(el, 0, target, duration, prefix, suffix, isDecimal);
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.5 });

    observer.observe(el);
});

// ==================== Alpine.js Magic Properties ====================

// Smooth transitions helper
Alpine.magic('transition', () => ({
    fade: {
        'x-transition:enter': 'transition ease-out duration-300',
        'x-transition:enter-start': 'opacity-0',
        'x-transition:enter-end': 'opacity-100',
        'x-transition:leave': 'transition ease-in duration-200',
        'x-transition:leave-start': 'opacity-100',
        'x-transition:leave-end': 'opacity-0'
    },
    slideUp: {
        'x-transition:enter': 'transition ease-out duration-300',
        'x-transition:enter-start': 'opacity-0 transform translate-y-4',
        'x-transition:enter-end': 'opacity-100 transform translate-y-0',
        'x-transition:leave': 'transition ease-in duration-200',
        'x-transition:leave-start': 'opacity-100 transform translate-y-0',
        'x-transition:leave-end': 'opacity-0 transform translate-y-4'
    },
    slideDown: {
        'x-transition:enter': 'transition ease-out duration-300',
        'x-transition:enter-start': 'opacity-0 transform -translate-y-4',
        'x-transition:enter-end': 'opacity-100 transform translate-y-0',
        'x-transition:leave': 'transition ease-in duration-200',
        'x-transition:leave-start': 'opacity-100 transform translate-y-0',
        'x-transition:leave-end': 'opacity-0 transform -translate-y-4'
    },
    scale: {
        'x-transition:enter': 'transition ease-out duration-300',
        'x-transition:enter-start': 'opacity-0 transform scale-95',
        'x-transition:enter-end': 'opacity-100 transform scale-100',
        'x-transition:leave': 'transition ease-in duration-200',
        'x-transition:leave-start': 'opacity-100 transform scale-100',
        'x-transition:leave-end': 'opacity-0 transform scale-95'
    }
}));

// ==================== Alpine.js Data Components ====================

// Toast notification system
Alpine.data('toast', () => ({
    toasts: [],
    add(message, type = 'success', duration = 5000) {
        const id = Date.now();
        this.toasts.push({ id, message, type, show: false });
        
        setTimeout(() => {
            const toast = this.toasts.find(t => t.id === id);
            if (toast) toast.show = true;
        }, 50);

        if (duration > 0) {
            setTimeout(() => this.remove(id), duration);
        }
        
        return id;
    },
    remove(id) {
        const toast = this.toasts.find(t => t.id === id);
        if (toast) {
            toast.show = false;
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }, 300);
        }
    }
}));

// Dropdown with smooth transitions
Alpine.data('dropdown', () => ({
    open: false,
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    }
}));

// Modal with animations
Alpine.data('modal', () => ({
    open: false,
    show() {
        this.open = true;
        document.body.style.overflow = 'hidden';
    },
    hide() {
        this.open = false;
        document.body.style.overflow = '';
    }
}));

// Tabs component
Alpine.data('tabs', (defaultTab = 0) => ({
    activeTab: defaultTab,
    setTab(index) {
        this.activeTab = index;
    }
}));

// Accordion component
Alpine.data('accordion', () => ({
    activeIndex: null,
    toggle(index) {
        this.activeIndex = this.activeIndex === index ? null : index;
    },
    isOpen(index) {
        return this.activeIndex === index;
    }
}));

// ==================== Utility Functions ====================

// Animate counter
function animateCounter(element, start, end, duration, prefix, suffix, isDecimal) {
    const startTime = performance.now();
    
    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        // Ease out cubic
        const easeOut = 1 - Math.pow(1 - progress, 3);
        const current = start + (end - start) * easeOut;
        
        if (isDecimal) {
            element.textContent = prefix + current.toFixed(2) + suffix;
        } else {
            element.textContent = prefix + Math.floor(current).toLocaleString() + suffix;
        }
        
        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }
    
    requestAnimationFrame(update);
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Throttle function
function throttle(func, limit) {
    let inThrottle;
    return function(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// ==================== DOM Ready Initializations ====================

document.addEventListener('DOMContentLoaded', () => {
    // Initialize scroll reveal for elements with .reveal class
    initScrollReveal();
    
    // Initialize smooth page transitions
    initPageTransitions();
    
    // Initialize ripple effect
    initRippleEffect();
    
    // Initialize parallax effects
    initParallax();
    
    // Initialize number counters
    initCounters();
    
    // Initialize progress bars
    initProgressBars();
    
    // Add loaded class to body
    document.body.classList.add('loaded');
});

// Scroll Reveal
function initScrollReveal() {
    const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    revealElements.forEach(el => observer.observe(el));
}

// Page Transitions
function initPageTransitions() {
    const links = document.querySelectorAll('a:not([target="_blank"]):not([href^="#"]):not([href^="mailto:"]):not([href^="tel:"])');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href && !href.startsWith('javascript:')) {
                e.preventDefault();
                document.body.classList.add('page-transitioning');
                setTimeout(() => {
                    window.location.href = href;
                }, 300);
            }
        });
    });
}

// Ripple Effect
function initRippleEffect() {
    const buttons = document.querySelectorAll('.btn-premium, .ripple');
    
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position: absolute;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: rippleEffect 0.6s linear;
                left: ${x}px;
                top: ${y}px;
                width: 100px;
                height: 100px;
                margin-left: -50px;
                margin-top: -50px;
                pointer-events: none;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
}

// Parallax Effects
function initParallax() {
    const parallaxElements = document.querySelectorAll('[data-parallax]');
    
    if (parallaxElements.length > 0) {
        window.addEventListener('scroll', throttle(() => {
            const scrollY = window.pageYOffset;
            
            parallaxElements.forEach(el => {
                const speed = parseFloat(el.dataset.parallax) || 0.5;
                const offset = scrollY * speed;
                el.style.transform = `translateY(${offset}px)`;
            });
        }, 16));
    }
}

// Counter Animation
function initCounters() {
    const counters = document.querySelectorAll('[data-counter]');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const target = parseInt(el.dataset.counter) || 0;
                const duration = parseInt(el.dataset.duration) || 2000;
                const prefix = el.dataset.prefix || '';
                const suffix = el.dataset.suffix || '';
                const isDecimal = el.dataset.decimal === 'true';
                
                animateCounter(el, 0, target, duration, prefix, suffix, isDecimal);
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.5 });
    
    counters.forEach(counter => observer.observe(counter));
}

// Progress Bars
function initProgressBars() {
    const progressBars = document.querySelectorAll('.progress-bar-fill[data-progress]');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const progress = el.dataset.progress || 0;
                el.style.width = progress + '%';
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.1 });
    
    progressBars.forEach(bar => {
        bar.style.width = '0%';
        observer.observe(bar);
    });
}

// Add ripple keyframe to document
const style = document.createElement('style');
style.textContent = `
    @keyframes rippleEffect {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .page-transitioning {
        opacity: 0;
        transform: translateY(-10px);
        transition: all 0.3s ease;
    }
    
    body {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    body.loaded {
        opacity: 1;
    }
`;
document.head.appendChild(style);

// Start Alpine
Alpine.start();
