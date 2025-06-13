jQuery(document).ready(function($) {
    // Initialize WordPress color picker with fallback
    if (typeof $.fn.wpColorPicker !== 'undefined') {
        $('.wfb-color-picker').wpColorPicker();
    } else {
        console.warn('WordPress Color Picker not available.');
    }

    // Show/hide custom position fields
    $('#wfb_position').on('change', function() {
        if ($(this).val() === 'custom') {
            $('.wfb-custom-position').show();
            $('#wfb_custom_horizontal').trigger('change');
        } else {
            $('.wfb-custom-position').hide();
            $('.wfb-left-position, .wfb-right-position').hide();
        }
    }).trigger('change');

    // Show/hide left/right position fields
    $('#wfb_custom_horizontal').on('change', function() {
        if ($(this).val() === 'right') {
            $('.wfb-right-position').show();
            $('.wfb-left-position').hide();
        } else {
            $('.wfb-left-position').show();
            $('.wfb-right-position').hide();
        }
    }).trigger('change');

    // Show/hide custom size field
    $('#wfb_size').on('change', function() {
        if ($(this).val() === 'custom') {
            $('.wfb-custom-size').show();
        } else {
            $('.wfb-custom-size').hide();
        }
    }).trigger('change');

    // Show/hide sticky bar settings
    $('#wfb_enable_sticky_bar').on('change', function() {
        if ($(this).is(':checked')) {
            $('.wfb-sticky-bar-settings').show();
            $('#wfb_mobile_number').prop('required', true);
        } else {
            $('.wfb-sticky-bar-settings').hide();
            $('#wfb_mobile_number').prop('required', false);
        }
    }).trigger('change');

    // Show/hide page visibility fields
    $('#wfb_page_visibility').on('change', function() {
        if ($(this).val() === 'custom') {
            $('.wfb-custom-pages').show();
        } else {
            $('.wfb-custom-pages').hide();
        }
    }).trigger('change');

    // Bootstrap form validation
    $('form.needs-validation').on('submit', function(event) {
        var form = $(this)[0];
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        $(this).addClass('was-validated');
    });
});