<?php
/*
Plugin Name: WhatsApp Floating Button
Description: A customizable WhatsApp floating button with an optional sticky bottom bar for mobile and tablet.
Version: 1.6
Author: WizScript
*/

// Prevent direct access
if (!defined('ABSPATH')) {
 exit;
}

// Enqueue front-end styles and scripts
function wfb_enqueue_assets() {
 wp_enqueue_style('wfb-style', plugins_url('css/whatsapp-float.css', __FILE__), [], '1.6');
 wp_enqueue_script('wfb-script', plugins_url('js/whatsapp-float.js', __FILE__), ['jquery'], '1.6', true);

 // Enqueue minimal Bootstrap CSS for sticky bar (custom build for flexbox and buttons)
 if (get_option('wfb_enable_sticky_bar', '0') === '1') {
 wp_enqueue_style('bootstrap-wfb', plugins_url('css/bootstrap-wfb.min.css', __FILE__), [], '1.6');
 }

 // Dynamic CSS for floating button and sticky bar
 $css = '';
 $phone = get_option('wfb_phone_number', '');
 $position = get_option('wfb_position', 'right-bottom');
 $custom_left = get_option('wfb_custom_left', '20');
 $custom_right = get_option('wfb_custom_right', '20');
 $custom_bottom = get_option('wfb_custom_bottom', '20');
 $custom_horizontal = get_option('wfb_custom_horizontal', 'right');
 $bg_color = get_option('wfb_bg_color', '#25D366');
 $size = get_option('wfb_size', 'sm');
 $custom_size = get_option('wfb_custom_size', '60');
 $bar_size = get_option('wfb_bar_size', '50');
 $whatsapp_bg_color = get_option('wfb_whatsapp_bar_bg_color', '#8c52ff');
 $call_bg_color = get_option('wfb_call_bar_bg_color', '#2cb742');

 if ($position === 'custom') {
 $css .= ".whatsapp-float.custom-position {";
 if ($custom_horizontal === 'right') {
 $css .= "right: {$custom_right}px; bottom: {$custom_bottom}px;";
 } else {
 $css .= "left: {$custom_left}px; bottom: {$custom_bottom}px;";
 }
 $css .= "}";
 }
 if ($size === 'custom') {
 $font_size = floor($custom_size * 0.5);
 $css .= ".whatsapp-float.custom-size { width: {$custom_size}px; height: {$custom_size}px; font-size: {$font_size}px; }";
 }
 $css .= ".whatsapp-float { background-color: {$bg_color}; }";
 if (get_option('wfb_enable_sticky_bar', '0') === '1') {
 $icon_size = floor($bar_size * 0.6);
 $font_size = floor($bar_size * 0.4);
 $css .= ".mobile-sticky { height: {$bar_size}px; }";
 $css .= ".mobile-sticky svg { width: {$icon_size}px; height: {$icon_size}px; }";
 $css .= ".mobile-sticky span { font-size: {$font_size}px; }";
 $css .= ".mobile-sticky a.whatsapp { background-color: {$whatsapp_bg_color}; }";
 $css .= ".mobile-sticky a.call { background-color: {$call_bg_color}; }";
 }
 wp_add_inline_style('wfb-style', $css);
}
add_action('wp_enqueue_scripts', 'wfb_enqueue_assets');

// Include admin settings
require_once plugin_dir_path(__FILE__) . 'includes/admin-settings.php';

// Check page visibility
function wfb_should_display() {
 $visibility = get_option('wfb_page_visibility', 'all');
 $selected_pages = get_option('wfb_selected_pages', []);

 if ($visibility === 'all') {
 return true;
 }

 $current_page = get_the_ID();
 if ($visibility === 'include') {
 return in_array($current_page, $selected_pages);
 }
 if ($visibility === 'exclude') {
 return !in_array($current_page, $selected_pages);
 }
 if ($visibility === 'custom') {
 return in_array($current_page, $selected_pages);
 }
 return false;
}

// Output floating button
function wfb_render_button() {
 if (!wfb_should_display()) {
 return;
 }

 $phone = get_option('wfb_phone_number', '');
 $position = get_option('wfb_position', 'right-bottom');
 $size = get_option('wfb_size', 'sm');
 $enable_sticky_bar = get_option('wfb_enable_sticky_bar', '0');
 $icon_outer_color = get_option('wfb_icon_outer_color', '#2CB742');
 $icon_inner_color = get_option('wfb_icon_inner_color', '#FFFFFF');

 if (empty($phone)) {
 if (current_user_can('manage_options')) {
 echo '<div style="position: fixed; bottom: 20px; right: 20px; background: red; color: white; padding: 10px; z-index: 1000;">WhatsApp Button: Phone number not set!</div>';
 }
 return;
 }

 // Hide floating button on mobile/tablet if sticky bar is enabled
 if ($enable_sticky_bar === '1') {
 echo '<style>@media (max-width: 991px) { .whatsapp-float { display: none !important; } }</style>';
 }

 $class = 'whatsapp-float';
 if ($position === 'custom') {
 $class .= ' custom-position';
 } else {
 $class .= ' ' . $position;
 }
 if ($size === 'custom') {
 $class .= ' custom-size';
 } else {
 $class .= ' ' . $size;
 }

 echo '<a href="https://wa.me/' . esc_attr($phone) . '" class="' . esc_attr($class) . '" target="_blank" rel="nofollow" aria-label="Contact via WhatsApp"><svg role="img" aria-label="WhatsApp Icon" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 58 58" xml:space="preserve"><g><path style="fill:' . esc_attr($icon_outer_color) . ';" d="M0,58l4.988-14.963C2.457,38.78,1,33.812,1,28.5C1,12.76,13.76,0,29.5,0S58,12.76,58,28.5 S45.24,57,29.5,57c-4.789,0-9.299-1.187-13.26-3.273L0,58z"></path><path style="fill:' . esc_attr($icon_inner_color) . ';" d="M47.683,37.985c-1.316-2.487-6.169-5.331-6.169-5.331c-1.098-0.626-2.423-0.696-3.049,0.42 c0,0-1.577,1.891-1.978,2.163c-1.832,1.241-3.529,1.193-5.242-0.52l-3.981-3.981l-3.981-3.981c-1.713-1.713-1.761-3.41-0.52-5.242 c0.272-0.401,2.163-1.978,2.163-1.978c1.116-0.627,1.046-1.951,0.42-3.049c0,0-2.844-4.853-5.331-6.169 c-1.058-0.56-2.357-0.364-3.203,0.482l-1.758,1.758c-5.577,5.577-2.831,11.873,2.746,17.45l5.097,5.097l5.097,5.097 c5.577,5.577,11.873,8.323,17.45,2.746l1.758-1.758C48.048,40.341,48.243,39.042,47.683,37.985z"></path></g></svg></a>';
}
add_action('wp_footer', 'wfb_render_button');

// Output sticky bottom bar
function wfb_render_sticky_bar() {
 if (!wfb_should_display()) {
 return;
 }

 $enable_sticky_bar = get_option('wfb_enable_sticky_bar', '0');
 $phone = get_option('wfb_phone_number', '');
 $mobile_number = get_option('wfb_mobile_number', '');
 $icon_outer_color = get_option('wfb_icon_outer_color', '#2CB742');
 $icon_inner_color = get_option('wfb_icon_inner_color', '#FFFFFF');
 $call_icon_fill = get_option('wfb_call_icon_fill', '#ffffff');
 $call_icon_stroke = get_option('wfb_call_icon_stroke', '#ffffff');

 if ($enable_sticky_bar !== '1' || empty($phone) || empty($mobile_number)) {
 if (current_user_can('manage_options') && $enable_sticky_bar === '1') {
 $error_message = empty($phone) ? 'WhatsApp Button: WhatsApp number not set!' : 'WhatsApp Button: Mobile number for Call button not set!';
 echo '<div style="position: fixed; bottom: 20px; right: 20px; background: red; color: white; padding: 10px; z-index: 1000;">' . esc_html($error_message) . '</div>';
 }
 return;
 }

 ?>
 <div class="d-flex mobile-sticky">
 <a href="https://wa.me/<?php echo esc_attr($phone); ?>" class="whatsapp flex-fill p-3 d-flex gap-2 text-uppercase align-items-center text-white" target="_blank" rel="nofollow" aria-label="Contact via WhatsApp">
 <svg role="img" aria-label="WhatsApp Icon" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 58 58" xml:space="preserve"><g><path style="fill:<?php echo esc_attr($icon_outer_color); ?>;" d="M0,58l4.988-14.963C2.457,38.78,1,33.812,1,28.5C1,12.76,13.76,0,29.5,0S58,12.76,58,28.5 S45.24,57,29.5,57c-4.789,0-9.299-1.187-13.26-3.273L0,58z"></path><path style="fill:<?php echo esc_attr($icon_inner_color); ?>;" d="M47.683,37.985c-1.316-2.487-6.169-5.331-6.169-5.331c-1.098-0.626-2.423-0.696-3.049,0.42 c0,0-1.577,1.891-1.978,2.163c-1.832,1.241-3.529,1.193-5.242-0.52l-3.981-3.981l-3.981-3.981c-1.713-1.713-1.761-3.41-0.52-5.242 c0.272-0.401,2.163-1.978,2.163-1.978c1.116-0.627,1.046-1.951,0.42-3.049c0,0-2.844-4.853-5.331-6.169 c-1.058-0.56-2.357-0.364-3.203,0.482l-1.758,1.758c-5.577,5.577-2.831,11.873,2.746,17.45l5.097,5.097l5.097,5.097 c5.577,5.577,11.873,8.323,17.45,2.746l1.758-1.758C48.048,40.341,48.243,39.042,47.683,37.985z"></path></g></svg>
 <span>WhatsApp</span>
 </a>
 <a href="tel:<?php echo esc_attr($mobile_number); ?>" class="call flex-fill p-3 d-flex gap-2 text-uppercase align-items-center text-white" rel="nofollow" aria-label="Call Now">
 <svg role="img" aria-label="Call Icon" viewBox="0 0 24 24" fill="<?php echo esc_attr($call_icon_fill); ?>" xmlns="http://www.w3.org/2000/svg"><path d="M21.97 18.33C21.97 18.69 21.89 19.06 21.72 19.42C21.55 19.78 21.33 20.12 21.04 20.44C20.55 20.98 20.01 21.37 19.4 21.62C18.8 21.87 18.15 22 17.45 22C16.43 22 15.34 21.76 14.19 21.27C13.04 20.78 11.89 20.12 10.75 19.29C9.6 18.45 8.51 17.52 7.47 16.49C6.44 15.45 5.51 14.36 4.68 13.22C3.86 12.08 3.2 10.94 2.72 9.81C2.24 8.67 2 7.58 2 6.54C2 5.86 2.12 5.21 2.36 4.61C2.6 4 2.98 3.44 3.51 2.94C4.15 2.31 4.85 2 5.59 2C5.87 2 6.15 2.06 6.4 2.18C6.66 2.3 6.89 2.48 7.07 2.74L9.39 6.01C9.57 6.26 9.7 6.49 9.79 6.71C9.88 6.92 9.93 7.13 9.93 7.32C9.93 7.56 9.86 7.8 9.72 8.03C9.59 8.26 9.4 8.5 9.16 8.74L8.4 9.53C8.29 9.64 8.24 9.77 8.24 9.93C8.24 10.01 8.25 10.08 8.27 10.16C8.3 10.24 8.33 10.3 8.35 10.36C8.53 10.69 8.84 11.12 9.28 11.64C9.73 12.16 10.21 12.69 10.73 13.22C11.27 13.75 11.79 14.24 12.32 14.69C12.84 15.13 13.27 15.43 13.61 15.61C13.66 15.63 13.72 15.66 13.79 15.69C13.87 15.72 13.95 15.73 14.04 15.73C14.21 15.73 14.34 15.67 14.45 15.56L15.21 14.81C15.46 14.56 15.7 14.37 15.93 14.25C16.16 14.11 16.39 14.04 16.64 14.04C16.83 14.04 17.03 14.08 17.25 14.17C17.47 14.26 17.7 14.39 17.95 14.56L21.26 16.91C21.52 17.09 21.7 17.3 21.81 17.55C21.91 17.8 21.97 18.05 21.97 18.33Z" stroke="<?php echo esc_attr($call_icon_stroke); ?>" stroke-width="1.5" stroke-miterlimit="10"></path></svg>
 <span>Call Now</span>
 </a>
 </div>
 <?php
}
add_action('wp_footer', 'wfb_render_sticky_bar');
?>