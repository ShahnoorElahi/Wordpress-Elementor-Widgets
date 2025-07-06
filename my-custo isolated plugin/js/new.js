// Carousel initialization function
function initCarousel(carousel) {
    const inner = carousel.querySelector('.carousel-inner1');
    const items = Array.from(carousel.querySelectorAll('.carousel-item1'));
    const prevBtn = carousel.querySelector('.carousel-control-prev1');
    const nextBtn = carousel.querySelector('.carousel-control-next1');
    
    let currentIndex = 0;
    let isAnimating = false;
    let intervalId = null;
    const intervalDuration = 5000;

    // Initialize carousel
    function initialize() {
        if (items.length === 0) return;
        
        items.forEach((item, index) => {
            item.classList.toggle('active1', index === currentIndex);
        });
        
        startAutoCycle();
        
        // Add event listeners
        if (prevBtn) {
            prevBtn.addEventListener('click', (e) => {
                e.preventDefault();
                navigate('prev');
            });
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', (e) => {
                e.preventDefault();
                navigate('next');
            });
        }
        
        if (inner) {
            inner.addEventListener('touchstart', handleTouchStart, { passive: true });
            inner.addEventListener('touchend', handleTouchEnd, { passive: true });
        }
    }

    // Navigation function
    function navigate(direction) {
        if (isAnimating || items.length <= 1) return;
        isAnimating = true;
        
        const newIndex = getNewIndex(direction);
        if (newIndex === currentIndex) {
            isAnimating = false;
            return;
        }
        
        const currentItem = items[currentIndex];
        const nextItem = items[newIndex];
        
        // Prepare animation
        nextItem.style.transform = direction === 'next' ? 'translateX(100%)' : 'translateX(-100%)';
        nextItem.classList.add('active1');
        
        // Force reflow
        nextItem.offsetHeight;
        
        // Start animation
        currentItem.style.transition = 'transform 0.6s ease-in-out';
        nextItem.style.transition = 'transform 0.6s ease-in-out';
        
        currentItem.style.transform = direction === 'next' ? 'translateX(-100%)' : 'translateX(100%)';
        nextItem.style.transform = 'translateX(0)';
        
        // Clean up after animation
        function handleTransitionEnd() {
            currentItem.style.transition = '';
            nextItem.style.transition = '';
            currentItem.classList.remove('active1');
            currentItem.style.transform = '';
            nextItem.style.transform = '';
            
            currentItem.removeEventListener('transitionend', handleTransitionEnd);
            nextItem.removeEventListener('transitionend', handleTransitionEnd);
            
            currentIndex = newIndex;
            isAnimating = false;
            resetAutoCycle();
        }
        
        currentItem.addEventListener('transitionend', handleTransitionEnd);
        nextItem.addEventListener('transitionend', handleTransitionEnd);
    }

    function getNewIndex(direction) {
        if (direction === 'next') {
            return (currentIndex + 1) % items.length;
        } else {
            return (currentIndex - 1 + items.length) % items.length;
        }
    }

    function startAutoCycle() {
        if (intervalDuration > 0 && items.length > 1) {
            clearInterval(intervalId);
            intervalId = setInterval(() => navigate('next'), intervalDuration);
        }
    }

    function resetAutoCycle() {
        clearInterval(intervalId);
        startAutoCycle();
    }

    function handleTouchStart(e) {
        touchStartX = e.touches[0].clientX;
        clearInterval(intervalId);
    }

    function handleTouchEnd(e) {
        const touchEndX = e.changedTouches[0].clientX;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > 50) {
            navigate(diff > 0 ? 'next' : 'prev');
        }
        
        resetAutoCycle();
    }

    // Initialize the carousel
    initialize();
}

// Function to initialize all carousels on the page
function initializeAllCarousels() {
    document.querySelectorAll('.carousel1').forEach(carousel => {
        if (!carousel.dataset.initialized) {
            carousel.dataset.initialized = 'true';
            initCarousel(carousel);
        }
    });
}

// Elementor-specific initialization
function initializeForElementor() {
    // Check if Elementor is available
    if (typeof elementor !== 'undefined' && elementor.hooks) {
        // Initialize when widget is rendered
        elementor.hooks.addAction('frontend/element_ready/my-custom-single-file-widget.default', function($scope) {
            const carousels = $scope[0].querySelectorAll('.carousel1');
            carousels.forEach(carousel => {
                if (!carousel.dataset.initialized) {
                    carousel.dataset.initialized = 'true';
                    initCarousel(carousel);
                }
            });
        });
    }
}

// Main initialization
document.addEventListener('DOMContentLoaded', () => {
    initializeAllCarousels();
    initializeForElementor();
});

// MutationObserver for dynamically added content
const observer = new MutationObserver((mutations) => {
    initializeAllCarousels();
});

observer.observe(document.body, {
    childList: true,
    subtree: true
});

// Initialize any existing carousels immediately (for editor)
initializeAllCarousels();
initializeForElementor();