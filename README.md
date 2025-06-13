# WhatsApp Floating Button WordPress Plugin

![Plugin Version](https://img.shields.io/badge/version-1.1-blue)  
A customizable WhatsApp floating button with an optional sticky bottom bar for mobile and tablet devices, built for WordPress.

## Overview
The **WhatsApp Floating Button** plugin adds a fully customizable WhatsApp button to your WordPress site, allowing visitors to initiate chats instantly. Unlike free plugins like **Chaty**, which limit customization options (e.g., position, colors, or page-specific display) without premium upgrades, this plugin offers complete control for free. It also includes an optional sticky bottom bar for mobile and tablet users, enhancing accessibility on smaller screens. Built with performance, security, and accessibility in mind, this plugin is ideal for WordPress site owners seeking seamless user engagement.

**Author**: WizScript  
**Version**: 1.1  
**License**: GPL-2.0+

## Features
- **Customizable Floating Button**:
  - Position: Right Bottom, Left Bottom, or custom (pixel-based left/right/bottom).
  - Size: Small (50px), Extra Large (70px), Extra Extra Large (90px), or custom (30–150px).
  - Colors: Custom background and WhatsApp icon (outer/inner) via WordPress color picker.
  - Hover effects: Smooth scaling and opacity transition.
- **Sticky Bottom Bar (Mobile/Tablet Only)**:
  - Displays WhatsApp and Call buttons on mobile (≤768px) and tablet (768px–991px), hidden on desktop (≥992px).
  - Customizable phone numbers, background colors, Call icon colors, and bar height (30–100px).
  - Hides floating button on mobile/tablet when enabled to avoid overlap.
- **Page Visibility Control**:
  - Show/hide on all pages, include/exclude specific pages, or select custom pages via a multiselect dropdown.
- **Admin Interface**:
  - Bootstrap 5-powered settings page with form validation and WordPress color picker.
  - Dynamic fields (e.g., custom position/size) and a detailed help page.
- **Security**:
  - Sanitizes inputs (phone numbers, colors, page IDs).
  - Prevents direct file access and shows admin-only error messages.
- **Accessibility**:
  - SVGs with `role="img"` and `aria-label` for screen readers.
  - Accessible form inputs with `aria-describedby`.
- **Performance**:
  - Lightweight custom Bootstrap CSS (~20KB) for sticky bar.
  - Dynamic CSS via `wp_add_inline_style` for caching.

## Why This Plugin?
Free plugins like Chaty restrict customization (e.g., no custom positions or page rules in free versions) and often require paid upgrades. This plugin was born out of frustration with those limitations, offering:
- Full control over button appearance and behavior.
- Granular page visibility options.
- A mobile-optimized sticky bar, absent in Chaty’s free version.
- Optimized performance with minimal dependencies.

## Installation
1. **Download**:
   - Clone the repo: `git clone https://github.com/kunalBrahma/WhatsApp-Floating-Button.git`
   - Or download the ZIP file and extract it.
2. **Upload**:
   - Copy the `wp-whatsapp-floating-button` folder to `/wp-content/plugins/` via FTP or WordPress’s plugin uploader.
3. **Activate**:
   - Go to **Plugins** in the WordPress admin dashboard and activate “WhatsApp Floating Button”.
4. **Configure**:
   - Navigate to **WhatsApp Button > Settings** to set up the button, sticky bar, and page visibility.

## Usage
1. **Access Settings**:
   - Go to **WhatsApp Button > Settings** in the WordPress admin menu.
2. **Configure Floating Button**:
   - Enter a WhatsApp phone number (e.g., `+1234567890`).
   - Choose position, size, and colors (background, icon outer/inner).
3. **Enable Sticky Bar** (Optional):
   - Check “Enable Sticky Bottom Bar” for mobile/tablet.
   - Set a mobile number for the Call button, colors, and bar height.
4. **Set Page Visibility**:
   - Choose “All Pages”, “Include Only”, “Exclude”, or “Custom” and select pages as needed.
5. **Save & Test**:
   - Click “Save Changes” and test on your site’s front end (use mobile/tablet for sticky bar).
6. **Help Page**:
   - Visit **WhatsApp Button > Help** for detailed instructions.

## Folder Structure
wp-whatsapp-floating-button/
├── wp-whatsapp-floating-button.php
├── css/
│   ├── whatsapp-float.css
│   └── bootstrap-wfb.min.css
├── js/
│   └── whatsapp-float.js
├── includes/
│   └── admin-settings.php
└── assets/
    └── whatsapp-icon.png (16x16 or 20x20 pixels)


## Troubleshooting
- **Button/Bar Not Showing**:
  - Verify phone numbers are set (`wfb_phone_number`, `wfb_mobile_number` for sticky bar).
  - Check page visibility settings to ensure the current page isn’t excluded.
  - Test sticky bar on mobile/tablet (≤991px).
- **Styles Not Applied**:
  - Clear browser/WordPress caches.
  - Inspect for CSS conflicts using browser developer tools.
- **Validation Errors**:
  - Phone numbers must be `+` followed by 10–15 digits.
  - Check JavaScript console for errors if color picker or form fails.

## Future Work
- **Live Preview**: Real-time preview of button/bar in the admin panel.
- **Multilingual Support**: Integration with WPML/Polylang for language-specific settings.
- **Analytics**: Track button clicks with Google Analytics.
- **Custom Icons**: Allow uploading custom PNG/SVG icons.
- **Shortcode**: Add `[wfb_button]` for manual placement.
- **Post Type Support**: Extend page visibility to posts and custom post types.

## Contributing
Contributions are welcome! To contribute:
1. Fork the repository.
2. Create a feature branch (`git checkout -b feature/your-feature`).
3. Commit changes (`git commit -m 'Add your feature'`).
4. Push to the branch (`git push origin feature/your-feature`).
5. Open a pull request with a clear description.

Please follow WordPress coding standards and test changes thoroughly.

## Support
For issues or feature requests, create a GitHub issue or contact the author (WizScript) via GitHub. Check the **Help** page in the admin menu for usage details.

## License
This plugin is licensed under the [GPL-2.0+](https://www.gnu.org/licenses/gpl-2.0.html).

## Acknowledgments
Inspired by the need to overcome limitations in free plugins like Chaty, this plugin aims to empower WordPress users with a free, flexible WhatsApp integration.

---
Happy chatting with your visitors! 📱
