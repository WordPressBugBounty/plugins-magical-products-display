# MPD Layout Server

Server-side plugin for managing and distributing Magical Products Display page layouts via REST API.

## Description

This plugin creates a custom post type for managing Elementor-compatible page layouts that can be distributed to client sites running the Magical Products Display plugin.

## Features

- Custom Post Type: `mpd-page-layout` for managing layouts
- Layout Types: single-product, archive-product, cart, checkout, my-account, empty-cart, thankyou
- REST API endpoints for distributing layouts
- Support for Pro/Free layout distinction
- Widget dependency tracking
- Layout categorization

## Installation

1. Upload the `mpd-layout-server` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to **MPD Layouts** in the admin menu to add layouts

## REST API Endpoints

### Base URL
```
/wp-json/mpd-layout-server/v1/
```

### Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/layouts` | GET | Get all published layouts |
| `/layouts?type={type}` | GET | Filter layouts by type |
| `/layouts?category={category}` | GET | Filter layouts by category |
| `/layouts?pro=true` | GET | Filter pro layouts only |
| `/layouts/{layout_id}` | GET | Get single layout with details |
| `/layouts/{layout_id}/structure` | GET | Get layout JSON structure |
| `/layouts/type/{type}` | GET | Get all layouts of a specific type |
| `/info` | GET | Get server info and stats |
| `/verify` | POST | Verify license (for Pro features) |

### Example Response

```json
{
  "success": true,
  "count": 2,
  "layouts": [
    {
      "id": "single-product-modern",
      "name": "Modern Product Layout",
      "description": "A modern product page layout",
      "thumbnail": "https://example.com/thumbnails/modern.jpg",
      "type": "single-product",
      "category": "modern",
      "is_pro": false,
      "widgets": ["mpd-product-gallery", "mpd-product-title", "mpd-product-price"],
      "source": "server",
      "updated_at": "2024-01-15 10:30:00"
    }
  ]
}
```

## Client Configuration

On client sites running Magical Products Display:

1. Go to **Magical Products > Settings > Remote Server**
2. Enter the server URL (e.g., `https://your-server.com`)
3. Enter API key if required
4. Enable remote layouts

## Creating Layouts

### Required Fields

- **Layout ID**: Unique identifier (e.g., `single-product-modern`)
- **Layout Type**: Template type this layout is for
- **Layout Structure**: Valid Elementor JSON structure

### JSON Structure Format

```json
[
  {
    "id": "abc12345",
    "elType": "container",
    "isInner": false,
    "settings": {
      "flex_direction": "row",
      "flex_gap": {
        "column": "20",
        "row": "20",
        "unit": "px"
      }
    },
    "elements": [
      {
        "id": "def67890",
        "elType": "widget",
        "widgetType": "mpd-product-title",
        "isInner": false,
        "settings": {},
        "elements": []
      }
    ]
  }
]
```

## Hooks & Filters

### Actions

- `mpd_layout_server_activated` - Fired when plugin is activated
- `mpd_layout_server_deactivated` - Fired when plugin is deactivated

### Filters

- `mpd_layout_server_permission_check` - Filter permission check for API access

## Requirements

- WordPress 5.8+
- PHP 7.4+

## Changelog

### 1.0.0
- Initial release
- Custom post type for layouts
- REST API endpoints
- Meta boxes for layout configuration
- JSON structure validation

## License

GPL v2 or later
