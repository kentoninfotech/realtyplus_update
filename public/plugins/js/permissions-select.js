/* Permissions Select - Multiple select initialization */
(function() {
    'use strict';
    
    $(function() {
        if ($.fn.select2) {
            // Initialize select2 for permission select elements
            $('.permissions-select').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: 'Select permissions...'
            });
        }
    });
})();
