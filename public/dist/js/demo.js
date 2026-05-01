/* AdminLTE Demo - Minimal initialization */
(function() {
    'use strict';
    
    // Hide spinner on page load
    function hideSpinner() {
        const preloader = document.querySelector('.preloader');
        if (preloader) {
            // Add animation class to fade out
            preloader.style.opacity = '0';
            preloader.style.visibility = 'hidden';
            preloader.style.transition = 'opacity 0.3s ease-out, visibility 0.3s ease-out';
            
            // After animation, set display to none
            setTimeout(function() {
                preloader.style.display = 'none';
            }, 300);
        }
    }
    
    // Hide spinner when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', hideSpinner);
    } else {
        // DOM already loaded
        hideSpinner();
    }
    
    // Also hide spinner on window load (in case)
    window.addEventListener('load', function() {
        hideSpinner();
    });
    
    // Fallback: hide spinner after 3 seconds if page is stuck
    setTimeout(hideSpinner, 3000);
    
    console.log('AdminLTE Demo loaded');
})();
