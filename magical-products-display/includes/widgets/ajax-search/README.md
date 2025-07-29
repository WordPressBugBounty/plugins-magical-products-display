# MPD AJAX Search Widget

## Overview
The MPD AJAX Search Widget is the 11th widget in the Magical Products Display plugin ecosystem. It provides real-time AJAX search functionality for WooCommerce products with modern UI/UX and extensive customization options.

## Features

### Free Features
- Real-time AJAX search as user types
- Configurable minimum character trigger (default: 3 characters)
- Debounced search with customizable delay (default: 300ms)
- Search across product title, content, excerpt, and SKU
- Configurable results limit (default: 10 products)
- Modern Material Design 3 inspired UI
- Accessibility compliant (WCAG 2.1 AA)
- Touch-friendly design (44px minimum touch targets)
- Loading spinners with multiple styles
- Clear search functionality
- Responsive design for all devices
- Dark mode support
- Reduced motion support for accessibility

### Pro Features (mgppro_is_active)
- Advanced filters (categories, tags, price range, featured, stock status)
- Multiple filter layout styles (YouTube-style, Integrated)
- Custom result layouts (List, Grid, Compact)
- Enhanced search analytics

## File Structure
```
includes/widgets/ajax-search/
├── ajax-search-widget.php     # Main widget class
└── ajax-search-handler.php    # AJAX request handler

assets/css/
└── mpd-ajax-search.css        # Widget styles (nested CSS)

assets/js/
└── mpd-ajax-search.js         # Widget JavaScript functionality
```

## Implementation Details

### Widget Registration
The widget is registered in `magical-products-display.php`:
```php
require_once(MAGICAL_PRODUCTS_DISPLAY_DIR . '/includes/widgets/ajax-search/ajax-search-widget.php');
require_once(MAGICAL_PRODUCTS_DISPLAY_DIR . '/includes/widgets/ajax-search/ajax-search-handler.php');
$widgets_manager->register(new \mgProducts_AJAX_Search());
```

### Assets Registration
CSS and JS are registered in `includes/assets-managment.php`:
```php
wp_register_style('mpd-ajax-search', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/mpd-ajax-search.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
wp_register_script("mpd-ajax-search", MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/mpd-ajax-search.js', array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
```

### AJAX Endpoints
- `wp_ajax_mpd_ajax_search` (logged in users)
- `wp_ajax_nopriv_mpd_ajax_search` (non-logged in users)

## CSS Architecture

### Class Naming Convention
- Prefix: `mpd-ajax-search`
- BEM-like structure: `.mpd-ajax-search__component--modifier`
- Nested CSS for better organization

### Key CSS Classes
- `.mpd-ajax-search` - Main container
- `.mpd-ajax-search__container` - Widget wrapper
- `.mpd-ajax-search__input-wrapper` - Input field wrapper
- `.mpd-ajax-search__input` - Search input field
- `.mpd-ajax-search__icon` - Search icon
- `.mpd-ajax-search__clear` - Clear button
- `.mpd-ajax-search__spinner` - Loading spinner
- `.mpd-ajax-search__results` - Results container
- `.mpd-ajax-search__result-item` - Individual result item
- `.mpd-ajax-search__filters` - Pro filters container

### Responsive Breakpoints
- Mobile: max-width: 768px
- Small Mobile: max-width: 480px
- High DPI displays supported

## JavaScript Functionality

### Main Class: `MPDAjaxSearch`
- Handles user input with debouncing
- Manages AJAX requests with caching
- Implements keyboard navigation
- Provides accessibility features
- Handles filter interactions (Pro)

### Key Methods
- `performSearch(query)` - Execute search request
- `displayResults(data)` - Render search results
- `handleInput(e)` - Process user input
- `handleKeydown(e)` - Keyboard navigation
- `getActiveFilters()` - Get Pro filter values

### Event Handling
- Input events (input, focus, blur)
- Keyboard navigation (Arrow keys, Enter, Escape)
- Click events on results and filters
- Document-level events for closing

## Elementor Controls

### Content Tab
#### Search Settings
- Placeholder Text (text)
- Minimum Characters (number, 1-10)
- Search Delay (number, 100-2000ms)
- Results Limit (number, 1-50)
- Show Search Icon (toggle)
- Show Clear Button (toggle)

#### Pro Filter Settings (Pro Only)
- Enable Filters (toggle)
- Filter Layout Style (select)
- Available Filters (multi-select)

### Style Tab
#### Search Bar Style
- Width (responsive slider)
- Height (slider)
- Background Color (color picker)
- Text Color (color picker)
- Border (border control)
- Border Radius (dimensions)
- Typography (typography control)
- Padding (dimensions)

#### Icon Style
- Icon Size (slider)
- Icon Color (color picker)
- Icon Position (select: left/right)

#### Loading Style
- Spinner Type (select: dots/circle/bars)
- Spinner Color (color picker)
- Spinner Size (slider)

#### Results Style
- Background Color (color picker)
- Border (border control)
- Border Radius (dimensions)
- Max Height (slider)
- Box Shadow (box shadow control)

### Advanced Tab
- Custom CSS (code editor)
- Animation (animation control)

## Security Features

### Nonce Verification
Each widget instance generates a unique nonce for AJAX requests:
```php
$nonce = wp_create_nonce('mpd_ajax_search_' . $widget_id);
```

### Input Sanitization
All inputs are properly sanitized:
- `sanitize_text_field()` for text inputs
- `intval()` for numeric inputs
- `wp_kses_post()` for rich content

### Rate Limiting
Prevents excessive requests:
- 30 requests per minute per IP
- Automatic cleanup of old rate limit data

### Data Validation
- Minimum query length validation
- Filter value validation
- Results limit constraints

## Performance Optimizations

### Caching
- Transient API for result caching (5 minutes)
- Client-side caching with Map object
- Cache invalidation on filter changes

### Query Optimization
- `fields => 'ids'` for initial query
- Efficient meta and tax queries
- Optimized image loading

### Frontend Performance
- Debounced search requests
- Hardware accelerated animations
- CSS containment for performance
- Lazy loading for images

### Database Optimization
- Indexed database queries
- Efficient WP_Query usage
- Minimal data transfer

## Accessibility Features

### ARIA Support
- `role="combobox"` on input
- `aria-expanded` state management
- `role="listbox"` on results
- `role="option"` on result items

### Keyboard Navigation
- Arrow keys for navigation
- Enter key for selection
- Escape key for closing
- Tab order management

### Screen Reader Support
- Live regions for announcements
- Descriptive labels and hints
- Semantic HTML structure

### Visual Accessibility
- WCAG 2.1 AA color contrast
- Focus indicators
- Reduced motion support
- High contrast mode support

## Browser Compatibility
- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+
- Internet Explorer 11 (graceful degradation)

## WordPress Standards Compliance
- WordPress Coding Standards
- Proper escaping and sanitization
- Translatable strings with text domain
- Hook and filter system
- Security best practices

## Usage Examples

### Basic Implementation
```php
// In Elementor template
echo do_shortcode('[elementor-template id="123"]');
```

### Programmatic Usage
```javascript
// Initialize widget manually
const searchWidget = new MPDAjaxSearch(document.querySelector('.mpd-ajax-search'));

// Trigger search programmatically
searchWidget.search('product name');

// Reset widget
searchWidget.reset();
```

### Custom Styling
```css
.mpd-ajax-search {
  --search-bg: #f8f9fa;
  --search-border: #dee2e6;
  --search-focus: #007bff;
}
```

## Troubleshooting

### Common Issues

1. **Search not working**
   - Check AJAX URL configuration
   - Verify nonce generation
   - Check server error logs

2. **Styling issues**
   - Check CSS file loading
   - Verify selector specificity
   - Test in different browsers

3. **Performance issues**
   - Review caching settings
   - Optimize database queries
   - Check rate limiting

### Debug Mode
Enable WordPress debug mode to see detailed error messages:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## Future Enhancements
- Voice search integration
- Search analytics dashboard
- Advanced sorting options
- Product comparison features
- Search suggestions/autocomplete
- Integration with popular filter plugins

## Support
For support and feature requests, please contact the Magical Products Display team or visit the plugin documentation.
