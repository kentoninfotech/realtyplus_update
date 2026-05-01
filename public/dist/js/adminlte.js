/**
 * AdminLTE Core Initialization
 * Loads and initializes all AdminLTE components
 */

(function() {
  'use strict';
  
  window.AdminLTE = window.AdminLTE || {};
  
  // Initialize Layout
  AdminLTE.initLayout = function() {
    // Add wrapper class if needed
    const wrapper = document.querySelector('.wrapper');
    if (wrapper) {
      wrapper.classList.add('layout-initialized');
    }
  };
  
  // Initialize Sidebar Menu Interactions
  AdminLTE.initSidebar = function() {
    // Handle sidebar menu toggle for items with submenus - using event delegation
    const sidebar = document.querySelector('.nav-sidebar');
    if (!sidebar) {
      console.warn('Sidebar .nav-sidebar not found');
      return;
    }
    
    // Attach click handler to all nav links in sidebar
    sidebar.addEventListener('click', function(e) {
      const navLink = e.target.closest('a.nav-link');
      if (!navLink) return;
      
      const navItem = navLink.closest('.nav-item');
      const submenu = navItem.querySelector('.nav-treeview');
      
      // Only prevent default if there's a submenu
      if (submenu) {
        e.preventDefault();
        e.stopPropagation();
        
        // Toggle this item's menu-open class
        navItem.classList.toggle('menu-open');
        
        // Close other open menus at the same level (siblings)
        const siblings = navItem.parentElement.querySelectorAll('.nav-item');
        siblings.forEach(function(sibling) {
          if (sibling !== navItem && sibling.classList.contains('menu-open')) {
            sibling.classList.remove('menu-open');
          }
        });
        
        console.log('Submenu toggled for:', navItem);
      }
    });
    
    console.log('Sidebar menu initialized with event delegation');
    
    // Also handle pushmenu for main sidebar toggle
    const pushMenuBtn = document.querySelector('[data-widget="pushmenu"]');
    if (pushMenuBtn) {
      pushMenuBtn.addEventListener('click', function(e) {
        e.preventDefault();
        document.body.classList.toggle('sidebar-collapse');
        console.log('Sidebar collapsed/expanded');
      });
    }
  };
  
  // Initialize all components
  AdminLTE.init = function() {
    this.initLayout();
    this.initSidebar();
    console.log('AdminLTE initialized');
  };
  
  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
      AdminLTE.init();
    });
  } else {
    AdminLTE.init();
  }
})();
