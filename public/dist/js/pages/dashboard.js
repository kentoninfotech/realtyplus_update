/* Dashboard page initialization */
(function() {
    'use strict';
    
    $(function() {
        // Initialize Chart.js if available
        if (typeof Chart !== 'undefined') {
            // Chart initialization would go here
        }
        
        // Initialize datetime picker if available
        if ($.fn.datetimepicker) {
            $('.datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD HH:mm'
            });
        }
        
        // Initialize daterange picker if available
        if ($.fn.daterangepicker) {
            $('.daterange').daterangepicker({
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                dateLimit: { days: 60 },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: 'left',
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-primary',
                cancelClass: 'btn-default',
                separatorClass: 'span-separator',
                locale: {
                    applyLabel: 'Apply',
                    cancelLabel: 'Cancel',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            });
        }
        
        // Initialize DataTables if available
        if ($.fn.DataTable) {
            $('.datatable').DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false
            });
        }
    });
})();
