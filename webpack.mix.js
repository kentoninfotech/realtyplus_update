const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// Main app builds
mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');

// Copy landing page libraries
mix.copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'public/dist/landing/css/')
   .copy('node_modules/animate.css/animate.min.css', 'public/dist/landing/lib/animate/')
   .copy('node_modules/owl.carousel/dist/assets/owl.carousel.min.css', 'public/dist/landing/lib/owlcarousel/')
   .copy('node_modules/owl.carousel/dist/owl.carousel.min.js', 'public/dist/landing/lib/owlcarousel/')
   .copy('node_modules/owl.carousel/dist/assets/', 'public/dist/landing/lib/owlcarousel/assets/')
   .copy('node_modules/wowjs/dist/wow.min.js', 'public/dist/landing/lib/wow/')
   .copy('node_modules/waypoints/lib/noframework.waypoints.min.js', 'public/dist/landing/lib/waypoints/waypoints.min.js')
   .copy('node_modules/jquery.easing/jquery.easing.min.js', 'public/dist/landing/lib/easing/easing.min.js')
   .copy('node_modules/counterup2/dist/index.js', 'public/dist/landing/lib/counterup/counterup.min.js')
   .copy('node_modules/jquery/dist/jquery.min.js', 'public/dist/landing/js/');

// Copy jQuery for library dependencies
mix.copy('node_modules/jquery/dist/jquery.min.js', 'public/js/');

// Copy admin panel assets
mix.copy('plugins/fontawesome-free/css/all.min.css', 'public/plugins/fontawesome-free/css/')
   .copy('plugins/fontawesome-free/webfonts/', 'public/plugins/fontawesome-free/webfonts/')
   .copy('plugins/icheck-bootstrap/icheck-bootstrap.min.css', 'public/plugins/icheck-bootstrap/')
   .copy('plugins/jquery/jquery.min.js', 'public/plugins/jquery/')
   .copy('plugins/bootstrap/js/bootstrap.bundle.min.js', 'public/plugins/bootstrap/js/')
   .copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'public/plugins/bootstrap/css/')
   
   // Dashboard plugins - CSS
   .copy('plugins/select2/css/select2.min.css', 'public/plugins/select2/css/')
   .copy('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css', 'public/plugins/select2-bootstrap4-theme/')
   .copy('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css', 'public/plugins/tempusdominus-bootstrap-4/css/')
   .copy('plugins/overlayScrollbars/css/OverlayScrollbars.min.css', 'public/plugins/overlayScrollbars/css/')
   .copy('plugins/daterangepicker/daterangepicker.css', 'public/plugins/daterangepicker/')
   .copy('plugins/summernote/summernote-bs4.min.css', 'public/plugins/summernote/')
   .copy('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css', 'public/plugins/datatables-bs4/css/')
   
   // AdminLTE theme CSS (already precompiled)
   .copy('dist/css/adminlte.min.css', 'public/dist/css/')
   // Dashboard plugins - JS
   .copy('plugins/select2/js/select2.full.min.js', 'public/plugins/select2/js/')
   .copy('plugins/moment/moment.min.js', 'public/plugins/moment/')
   .copy('plugins/daterangepicker/daterangepicker.js', 'public/plugins/daterangepicker/')
   .copy('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js', 'public/plugins/tempusdominus-bootstrap-4/js/')
   .copy('plugins/summernote/summernote-bs4.min.js', 'public/plugins/summernote/')
   .copy('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js', 'public/plugins/overlayScrollbars/js/')
   .copy('plugins/chart.js/Chart.min.js', 'public/plugins/chart.js/')
   .copy('plugins/chart.js/Chart.min.css', 'public/plugins/chart.js/')
   .copy('plugins/jquery-knob/jquery.knob.min.js', 'public/plugins/jquery-knob/')
   .copy('plugins/jquery-ui/jquery-ui.min.js', 'public/plugins/jquery-ui/')
   .copy('plugins/jquery-ui/jquery-ui.min.css', 'public/plugins/jquery-ui/')
   .copy('plugins/datatables/jquery.dataTables.min.js', 'public/plugins/datatables/')
   .copy('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js', 'public/plugins/datatables-bs4/js/')
   
   // DataTables extensions
   .copy('plugins/datatables-responsive/js/dataTables.responsive.min.js', 'public/plugins/datatables-responsive/js/')
   .copy('plugins/datatables-responsive/js/responsive.bootstrap4.min.js', 'public/plugins/datatables-responsive/js/')
   .copy('plugins/datatables-buttons/js/dataTables.buttons.min.js', 'public/plugins/datatables-buttons/js/')
   .copy('plugins/datatables-buttons/js/buttons.bootstrap4.min.js', 'public/plugins/datatables-buttons/js/')
   .copy('plugins/datatables-buttons/js/buttons.html5.min.js', 'public/plugins/datatables-buttons/js/')
   .copy('plugins/datatables-buttons/js/buttons.print.min.js', 'public/plugins/datatables-buttons/js/')
   .copy('plugins/datatables-buttons/js/buttons.colVis.min.js', 'public/plugins/datatables-buttons/js/')
   .copy('plugins/jszip/jszip.min.js', 'public/plugins/jszip/')
   .copy('plugins/pdfmake/pdfmake.min.js', 'public/plugins/pdfmake/')
   .copy('plugins/pdfmake/vfs_fonts.js', 'public/plugins/pdfmake/')
   
   // Summernote fonts
   .copy('plugins/summernote/font/', 'public/plugins/summernote/font/')
   
   // AdminLTE core components
   .copy('build/js/', 'public/dist/js/adminlte-components/');