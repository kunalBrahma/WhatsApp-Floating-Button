<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register top-level menu and submenus
function wfb_register_settings_menu() {
    add_menu_page(
        'WhatsApp Floating Button',
        'WhatsApp Button',
        'manage_options',
        'wfb-settings',
        'wfb_render_settings_page',
        plugins_url('../assets/whatsapp-icon.png', __FILE__),
        80
    );
    add_submenu_page(
        'wfb-settings',
        'Settings',
        'Settings',
        'manage_options',
        'wfb-settings',
        'wfb_render_settings_page'
    );
    add_submenu_page(
        'wfb-settings',
        'Help',
        'Help',
        'manage_options',
        'wfb-help',
        'wfb_render_help_page'
    );
}
add_action('admin_menu', 'wfb_register_settings_menu');

// Register settings with validation
function wfb_register_settings() {
    register_setting('wfb_settings_group', 'wfb_phone_number', ['sanitize_callback' => 'wfb_sanitize_phone']);
    register_setting('wfb_settings_group', 'wfb_position', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('wfb_settings_group', 'wfb_custom_horizontal', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('wfb_settings_group', 'wfb_custom_left', ['sanitize_callback' => 'absint']);
    register_setting('wfb_settings_group', 'wfb_custom_right', ['sanitize_callback' => 'absint']);
    register_setting('wfb_settings_group', 'wfb_custom_bottom', ['sanitize_callback' => 'absint']);
    register_setting('wfb_settings_group', 'wfb_bg_color', ['sanitize_callback' => 'sanitize_hex_color']);
    register_setting('wfb_settings_group', 'wfb_size', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('wfb_settings_group', 'wfb_custom_size', ['sanitize_callback' => 'absint']);
    register_setting('wfb_settings_group', 'wfb_icon_outer_color', ['sanitize_callback' => 'sanitize_hex_color']);
    register_setting('wfb_settings_group', 'wfb_icon_inner_color', ['sanitize_callback' => 'sanitize_hex_color']);
    register_setting('wfb_settings_group', 'wfb_enable_sticky_bar', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('wfb_settings_group', 'wfb_mobile_number', ['sanitize_callback' => 'wfb_sanitize_phone']);
    register_setting('wfb_settings_group', 'wfb_whatsapp_bar_bg_color', ['sanitize_callback' => 'sanitize_hex_color']);
    register_setting('wfb_settings_group', 'wfb_call_bar_bg_color', ['sanitize_callback' => 'sanitize_hex_color']);
    register_setting('wfb_settings_group', 'wfb_call_icon_fill', ['sanitize_callback' => 'sanitize_hex_color']);
    register_setting('wfb_settings_group', 'wfb_call_icon_stroke', ['sanitize_callback' => 'sanitize_hex_color']);
    register_setting('wfb_settings_group', 'wfb_bar_size', ['sanitize_callback' => 'absint']);
    register_setting('wfb_settings_group', 'wfb_page_visibility', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('wfb_settings_group', 'wfb_selected_pages', ['sanitize_callback' => 'wfb_sanitize_pages']);
}
add_action('admin_init', 'wfb_register_settings');

// Sanitize phone number
function wfb_sanitize_phone($input) {
    $input = sanitize_text_field($input);
    if (preg_match('/^\+\d{10,15}$/', $input) || empty($input)) {
        return $input;
    }
    add_settings_error('wfb_settings_group', 'invalid_phone', 'Phone number must start with + and contain 10–15 digits.', 'error');
    return '';
}

// Sanitize page IDs
function wfb_sanitize_pages($input) {
    if (!is_array($input)) {
        return [];
    }
    return array_map('absint', $input);
}

// Render settings page with Bootstrap
function wfb_render_settings_page() {
    if (isset($_GET['settings-updated']) && !get_settings_errors('wfb_settings_group')) {
        add_settings_error('wfb_settings_group', 'settings_updated', 'Settings saved successfully.', 'success');
    }
    settings_errors('wfb_settings_group');
    ?>
    <div class="wrap">
        <h1>WhatsApp Floating Button Settings</h1>
        <form method="post" action="options.php" class="needs-validation" novalidate>
            <?php settings_fields('wfb_settings_group'); ?>
            <div class="card p-4 mb-4">
                <h5 class="card-title">General Settings</h5>
                <div class="mb-3">
                    <label for="wfb_phone_number" class="form-label">WhatsApp Phone Number</label>
                    <input type="text" name="wfb_phone_number" id="wfb_phone_number" value="<?php echo esc_attr(get_option('wfb_phone_number')); ?>" placeholder="e.g., +1234567890" class="form-control" required pattern="^\+\d{10,15}$" aria-describedby="wfb_phone_number_help" />
                    <div id="wfb_phone_number_help" class="form-text">Enter phone number with country code (e.g., +1234567890) for the WhatsApp button.</div>
                    <div class="invalid-feedback">Please enter a valid phone number.</div>
                </div>
                <div class="mb-3">
                    <label for="wfb_position" class="form-label">Floating Button Position</label>
                    <select name="wfb_position" id="wfb_position" class="form-select">
                        <option value="right-bottom" <?php selected(get_option('wfb_position'), 'right-bottom'); ?>>Right Bottom</option>
                        <option value="left-bottom" <?php selected(get_option('wfb_position'), 'left-bottom'); ?>>Left Bottom</option>
                        <option value="custom" <?php selected(get_option('wfb_position'), 'custom'); ?>>Custom</option>
                    </select>
                    <div class="form-text">Choose where the floating button appears on the screen.</div>
                </div>
                <div class="mb-3 wfb-custom-position" style="display: none;">
                    <label for="wfb_custom_horizontal" class="form-label">Horizontal Alignment</label>
                    <select name="wfb_custom_horizontal" id="wfb_custom_horizontal" class="form-select">
                        <option value="right" <?php selected(get_option('wfb_custom_horizontal', 'right'), 'right'); ?>>Right</option>
                        <option value="left" <?php selected(get_option('wfb_custom_horizontal'), 'left'); ?>>Left</option>
                    </select>
                    <div class="form-text">Select left or right alignment for custom positioning.</div>
                </div>
                <div class="mb-3 wfb-custom-position wfb-left-position" style="display: none;">
                    <label for="wfb_custom_left" class="form-label">Custom Left Position (px)</label>
                    <input type="number" name="wfb_custom_left" id="wfb_custom_left" value="<?php echo esc_attr(get_option('wfb_custom_left', '20')); ?>" min="0" class="form-control" />
                    <div class="form-text">Distance from the left edge in pixels.</div>
                </div>
                <div class="mb-3 wfb-custom-position wfb-right-position" style="display: none;">
                    <label for="wfb_custom_right" class="form-label">Custom Right Position (px)</label>
                    <input type="number" name="wfb_custom_right" id="wfb_custom_right" value="<?php echo esc_attr(get_option('wfb_custom_right', '20')); ?>" min="0" class="form-control" />
                    <div class="form-text">Distance from the right edge in pixels.</div>
                </div>
                <div class="mb-3 wfb-custom-position" style="display: none;">
                    <label for="wfb_custom_bottom" class="form-label">Custom Bottom Position (px)</label>
                    <input type="number" name="wfb_custom_bottom" id="wfb_custom_bottom" value="<?php echo esc_attr(get_option('wfb_custom_bottom', '20')); ?>" min="0" class="form-control" />
                    <div class="form-text">Distance from the bottom edge in pixels.</div>
                </div>
                <div class="mb-3">
                    <label for="wfb_bg_color" class="form-label">Floating Button Background Color</label>
                    <input type="text" name="wfb_bg_color" id="wfb_bg_color" value="<?php echo esc_attr(get_option('wfb_bg_color', '#25D366')); ?>" class="form-control wfb-color-picker" />
                    <div class="form-text">Choose the background color for the floating button.</div>
                </div>
                <div class="mb-3">
                    <label for="wfb_icon_outer_color" class="form-label">WhatsApp Icon Outer Color</label>
                    <input type="text" name="wfb_icon_outer_color" id="wfb_icon_outer_color" value="<?php echo esc_attr(get_option('wfb_icon_outer_color', '#2CB742')); ?>" class="form-control wfb-color-picker" />
                    <div class="form-text">Color for the outer part of the WhatsApp icon.</div>
                </div>
                <div class="mb-3">
                    <label for="wfb_icon_inner_color" class="form-label">WhatsApp Icon Inner Color</label>
                    <input type="text" name="wfb_icon_inner_color" id="wfb_icon_inner_color" value="<?php echo esc_attr(get_option('wfb_icon_inner_color', '#FFFFFF')); ?>" class="form-control wfb-color-picker" />
                    <div class="form-text">Color for the inner part of the WhatsApp icon.</div>
                </div>
                <div class="mb-3">
                    <label for="wfb_size" class="form-label">Floating Button Size</label>
                    <select name="wfb_size" id="wfb_size" class="form-select">
                        <option value="sm" <?php selected(get_option('wfb_size'), 'sm'); ?>>Small (50px)</option>
                        <option value="xl" <?php selected(get_option('wfb_size'), 'xl'); ?>>Extra Large (70px)</option>
                        <option value="xxl" <?php selected(get_option('wfb_size'), 'xxl'); ?>>Extra Extra Large (90px)</option>
                        <option value="custom" <?php selected(get_option('wfb_size'), 'custom'); ?>>Custom</option>
                    </select>
                    <div class="form-text">Select the size of the floating button.</div>
                </div>
                <div class="mb-3 wfb-custom-size" style="display: none;">
                    <label for="wfb_custom_size" class="form-label">Custom Floating Button Size (px)</label>
                    <input type="number" name="wfb_custom_size" id="wfb_custom_size" value="<?php echo esc_attr(get_option('wfb_custom_size', '60')); ?>" min="30" max="150" class="form-control" />
                    <div class="form-text">Enter size between 30 and 150 pixels.</div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="wfb_enable_sticky_bar" id="wfb_enable_sticky_bar" value="1" <?php checked(get_option('wfb_enable_sticky_bar', '0'), '1'); ?> class="form-check-input" />
                        <label for="wfb_enable_sticky_bar" class="form-check-label">Enable Sticky Bottom Bar (Mobile & Tablet Only)</label>
                        <div class="form-text">Show a sticky bar with WhatsApp and Call buttons on mobile and tablet (hidden on desktop).</div>
                    </div>
                </div>
                <div class="mb-3 wfb-sticky-bar-settings" style="display: none;">
                    <label for="wfb_mobile_number" class="form-label">Mobile Number for Call Button</label>
                    <input type="text" name="wfb_mobile_number" id="wfb_mobile_number" value="<?php echo esc_attr(get_option('wfb_mobile_number')); ?>" placeholder="e.g., +1234567890" class="form-control" pattern="^\+\d{10,15}$" aria-describedby="wfb_mobile_number_help" />
                    <div id="wfb_mobile_number_help" class="form-text">Enter phone number with country code for the Call button in the sticky bar.</div>
                    <div class="invalid-feedback">Please enter a valid phone number.</div>
                </div>
                <div class="mb-3 wfb-sticky-bar-settings" style="display: none;">
                    <label for="wfb_whatsapp_bar_bg_color" class="form-label">WhatsApp Bar Background Color</label>
                    <input type="text" name="wfb_whatsapp_bar_bg_color" id="wfb_whatsapp_bar_bg_color" value="<?php echo esc_attr(get_option('wfb_whatsapp_bar_bg_color', '#8c52ff')); ?>" class="form-control wfb-color-picker" />
                    <div class="form-text">Background color for the WhatsApp button in the sticky bar.</div>
                </div>
                <div class="mb-3 wfb-sticky-bar-settings" style="display: none;">
                    <label for="wfb_call_bar_bg_color" class="form-label">Call Bar Background Color</label>
                    <input type="text" name="wfb_call_bar_bg_color" id="wfb_call_bar_bg_color" value="<?php echo esc_attr(get_option('wfb_call_bar_bg_color', '#2cb742')); ?>" class="form-control wfb-color-picker" />
                    <div class="form-text">Background color for the Call button in the sticky bar.</div>
                </div>
                <div class="mb-3 wfb-sticky-bar-settings" style="display: none;">
                    <label for="wfb_call_icon_fill" class="form-label">Call Icon Fill Color</label>
                    <input type="text" name="wfb_call_icon_fill" id="wfb_call_icon_fill" value="<?php echo esc_attr(get_option('wfb_call_icon_fill', '#ffffff')); ?>" class="form-control wfb-color-picker" />
                    <div class="form-text">Fill color for the Call icon in the sticky bar.</div>
                </div>
                <div class="mb-3 wfb-sticky-bar-settings" style="display: none;">
                    <label for="wfb_call_icon_stroke" class="form-label">Call Icon Stroke Color</label>
                    <input type="text" name="wfb_call_icon_stroke" id="wfb_call_icon_stroke" value="<?php echo esc_attr(get_option('wfb_call_icon_stroke', '#ffffff')); ?>" class="form-control wfb-color-picker" />
                    <div class="form-text">Stroke color for the Call icon in the sticky bar.</div>
                </div>
                <div class="mb-3 wfb-sticky-bar-settings" style="display: none;">
                    <label for="wfb_bar_size" class="form-label">Sticky Bar Height (px)</label>
                    <input type="number" name="wfb_bar_size" id="wfb_bar_size" value="<?php echo esc_attr(get_option('wfb_bar_size', '50')); ?>" min="30" max="100" class="form-control" />
                    <div class="form-text">Enter height between 30 and 100 pixels for the sticky bar.</div>
                </div>
                <h5 class="card-title mt-4">Page Visibility Settings</h5>
                <div class="mb-3">
                    <label for="wfb_page_visibility" class="form-label">Show Button and Bar on</label>
                    <select name="wfb_page_visibility" id="wfb_page_visibility" class="form-select">
                        <option value="all" <?php selected(get_option('wfb_page_visibility', 'all'), 'all'); ?>>All Pages</option>
                        <option value="include" <?php selected(get_option('wfb_page_visibility'), 'include'); ?>>Include Only on Specific Pages</option>
                        <option value="exclude" <?php selected(get_option('wfb_page_visibility'), 'exclude'); ?>>Exclude on Specific Pages</option>
                        <option value="custom" <?php selected(get_option('wfb_page_visibility'), 'custom'); ?>>Custom Page Selection</option>
                    </select>
                    <div class="form-text">Control where the floating button and sticky bar appear.</div>
                </div>
                <div class="mb-3 wfb-custom-pages" style="display: none;">
                    <label for="wfb_selected_pages" class="form-label">Select Pages</label>
                    <select name="wfb_selected_pages[]" id="wfb_selected_pages" class="form-select" multiple>
                        <?php
                        $pages = get_pages();
                        $selected_pages = get_option('wfb_selected_pages', []);
                        foreach ($pages as $page) {
                            echo '<option value="' . esc_attr($page->ID) . '" ' . (in_array($page->ID, $selected_pages) ? 'selected' : '') . '>' . esc_html($page->post_title) . '</option>';
                        }
                        ?>
                    </select>
                    <div class="form-text">Select one or more pages to include or exclude (based on visibility setting).</div>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
    <?php
}

// Render Help Page
function wfb_render_help_page() {
    ?>
    <div class="wrap">
        <h1>WhatsApp Button Help</h1>
        <div class="card p-4">
            <h5 class="card-title">How to Use the Plugin</h5>
            <p class="card-text">
                1. Go to the <strong>Settings</strong> submenu to configure the plugin.<br>
                2. Enter your WhatsApp phone number with country code (e.g., +1234567890).<br>
                3. Select a position for the floating button (right bottom, left bottom, or custom coordinates).<br>
                4. For custom position, choose horizontal alignment (left or right) and set left/right and bottom positions.<br>
                5. Choose a background color and icon colors for the floating button.<br>
                6. Select a size for the floating button (small, extra large, extra extra large, or custom).<br>
                7. Optionally enable the sticky bottom bar for mobile and tablet devices (hidden on desktop).<br>
                8. For the sticky bar, set the mobile number for the Call button, background colors, icon colors, and bar height.<br>
                9. Under Page Visibility Settings, choose to show the button and bar on all pages, include/exclude specific pages, or select custom pages.<br>
                10. Save changes and test the floating button and/or sticky bar on your site’s front end.
            </p>
        </div>
    </div>
    <?php
}

// Enqueue admin scripts and styles
function wfb_admin_scripts($hook) {
    if (!in_array($hook, ['toplevel_page_wfb-settings', 'whatsapp-button_page_wfb-help'])) {
        return;
    }
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css', [], '5.3.6');
    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js', ['jquery'], '5.3.6', true);
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wfb-admin-script', plugins_url('../js/whatsapp-float.js', __FILE__), ['jquery', 'wp-color-picker', 'bootstrap'], '1.6', true);
}
add_action('admin_enqueue_scripts', 'wfb_admin_scripts');
?>