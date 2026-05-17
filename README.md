# Neighborhood Connect — Hyperlocal Community Platform

## Problem Statement

**The Disconnected Neighborhood Problem**

Modern urban and suburban neighborhoods are increasingly fragmented. Despite living metres apart, residents often:

- Have no idea about local events (garage sales, block parties, community meetings)
- Cannot easily find trusted local service providers (plumbers, tutors, babysitters)
- Lack a unified channel to report local issues (broken streetlights, potholes, safety concerns)
- Miss opportunities to share resources, skills, or second-hand goods with neighbours
- Feel isolated — especially newcomers, elderly residents, and remote workers

Existing platforms fail this niche:
| Platform | Problem |
|---|---|
| Facebook Groups | Privacy concerns, algorithm-driven, noisy |
| Nextdoor | Closed ecosystem, poor UI, US-centric |
| WhatsApp Groups | No structure, hard to search, floods of messages |
| City portals | Outdated, non-interactive, one-way communication |

**Our Solution: Neighborhood Connect**

A WordPress-powered community hub where residents in a defined locality can:
1. **Post & discover local events** with RSVP and calendar sync
2. **List & find local services** with ratings and reviews
3. **Report community issues** that get tracked and resolved
4. **Exchange goods & skills** via a neighborhood marketplace
5. **Engage in discussions** on a neighborhood-scoped forum

---

## Features

### Core Features
- **Event Management** — Create events with date, location, capacity, RSVP tracking
- **Service Directory** — Local businesses and freelancers can list services
- **Issue Tracker** — Report local issues with photo uploads and status tracking
- **Community Forum** — Category-based discussions per neighborhood
- **Marketplace** — Free/paid listings for goods and skills
- **Neighborhood Map** — Interactive map of events, services, and issues
- **Member Profiles** — Resident verification, reputation system
- **Notifications** — Email and browser push notifications

### Modern Functionalities
- Mobile-first responsive design
- Dark / Light mode toggle
- Progressive Web App (PWA) support
- REST API endpoints for mobile apps
- AJAX-powered dynamic content loading
- SEO-optimized with Open Graph tags
- Accessibility (WCAG 2.1 AA compliant)
- Google Maps / OpenStreetMap integration
- Social login (Google, Facebook)
- Role-based access (Admin, Moderator, Resident, Guest)

---

## Tech Stack

| Layer | Technology |
|---|---|
| CMS | WordPress 6.x |
| Backend | PHP 8.2 + MySQL 8.0 |
| Theme | Custom WordPress Theme (mobile-first) |
| Plugins | Custom: `nc-core`, `nc-events` |
| Containerization | Docker + Docker Compose |
| Styling | CSS3 (Custom Properties, Grid, Flexbox) |
| JavaScript | Vanilla JS + AJAX |
| Icons | Font Awesome 6 |
| Fonts | Google Fonts (Inter) |

---

## Project Structure

```
neighborhood-connect/
├── README.md
├── docker-compose.yml          # WordPress + MySQL + phpMyAdmin
├── .env.example                # Environment variable template
├── theme/
│   └── neighborhood-connect/   # Custom WordPress theme
│       ├── style.css           # Theme header + base styles
│       ├── functions.php       # Theme setup, hooks, AJAX handlers
│       ├── index.php           # Blog index fallback
│       ├── front-page.php      # Homepage template
│       ├── header.php          # Site header + navigation
│       ├── footer.php          # Site footer
│       ├── single.php          # Single post view
│       ├── page.php            # Static page template
│       ├── archive.php         # Archive listings
│       ├── search.php          # Search results
│       ├── 404.php             # Error page
│       ├── comments.php        # Comments template
│       ├── assets/
│       │   ├── css/main.css    # Main stylesheet (mobile-first)
│       │   └── js/main.js      # Interactive features + AJAX
│       └── template-parts/
│           ├── content-event.php
│           ├── content-service.php
│           └── content-post.php
├── plugins/
│   ├── nc-core/                # Core custom post types & fields
│   │   └── nc-core.php
│   └── nc-events/              # Event-specific functionality
│       └── nc-events.php
└── scripts/
    └── setup.sh                # One-command local setup
```

---

## Quick Start (Local Development)

### Prerequisites
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed and running
- Git

### Setup

```bash
# 1. Clone the repo
git clone <your-repo-url>
cd neighborhood-connect

# 2. Copy environment file
cp .env.example .env

# 3. Start containers
docker compose up -d

# 4. Wait ~30 seconds for WordPress to initialize, then open:
open http://localhost:8080

# 5. Complete WordPress setup at:
# http://localhost:8080/wp-admin/install.php

# 6. Install the custom theme & plugins via the one-liner:
bash scripts/setup.sh
```

### Default Credentials (after setup)
| Service | URL | Credentials |
|---|---|---|
| WordPress | http://localhost:8080 | admin / admin123 |
| phpMyAdmin | http://localhost:8081 | root / rootpass |
| MySQL | localhost:3306 | nc_user / nc_pass |

### Activate Theme & Plugins
1. Go to **Appearance → Themes** → Activate "Neighborhood Connect"
2. Go to **Plugins** → Activate "NC Core" and "NC Events"
3. Go to **Settings → Permalinks** → Select "Post name" → Save

---

## Environment Variables

Copy `.env.example` to `.env` and update:

```env
WORDPRESS_DB_HOST=db
WORDPRESS_DB_NAME=neighborhood_connect
WORDPRESS_DB_USER=nc_user
WORDPRESS_DB_PASSWORD=nc_pass
MYSQL_ROOT_PASSWORD=rootpass
WORDPRESS_TABLE_PREFIX=nc_
WORDPRESS_DEBUG=true
```

---

## API Endpoints

The theme registers custom REST API endpoints:

| Method | Endpoint | Description |
|---|---|---|
| GET | `/wp-json/nc/v1/events` | List all events |
| GET | `/wp-json/nc/v1/events/{id}` | Single event |
| POST | `/wp-json/nc/v1/events/{id}/rsvp` | RSVP to event |
| GET | `/wp-json/nc/v1/services` | List services |
| GET | `/wp-json/nc/v1/issues` | List community issues |
| POST | `/wp-json/nc/v1/issues` | Submit new issue |

---

## Custom Post Types

| Post Type | Slug | Description |
|---|---|---|
| Event | `nc_event` | Community events with date/location/RSVP |
| Service | `nc_service` | Local business/freelancer listings |
| Issue | `nc_issue` | Community issues with status tracking |

---

## Deployment

### Production (with SSL)

For production deployment, update `docker-compose.yml` to add:
- Nginx reverse proxy with SSL termination
- Let's Encrypt certificates via Certbot
- Redis object caching
- CDN integration for static assets

Recommended hosts: **WP Engine**, **Kinsta**, **SiteGround**, or any VPS with Docker support.

---

## Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/my-feature`
3. Commit your changes: `git commit -m 'Add my feature'`
4. Push to the branch: `git push origin feature/my-feature`
5. Open a Pull Request

---

## License

MIT License — see [LICENSE](LICENSE) for details.

---

## Author

Built with care for stronger, more connected communities.
