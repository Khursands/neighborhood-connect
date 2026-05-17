#!/usr/bin/env bash
# Neighborhood Connect — One-command local setup
# Usage: bash scripts/setup.sh
set -euo pipefail

echo "🏘️  Neighborhood Connect Setup"
echo "================================"

# Check WP-CLI
if ! command -v wp &>/dev/null; then
  echo "⬇️  Installing WP-CLI..."
  curl -sS https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -o /usr/local/bin/wp
  chmod +x /usr/local/bin/wp
  echo "✅ WP-CLI installed"
fi

WP="docker compose exec wordpress wp --allow-root --path=/var/www/html"

echo ""
echo "⏳  Waiting for WordPress to be ready..."
until docker compose exec wordpress curl -s http://localhost > /dev/null 2>&1; do
  sleep 2
done
echo "✅ WordPress is up"

echo ""
echo "⚙️  Installing WordPress..."
$WP core install \
  --url="http://localhost:8080" \
  --title="Neighborhood Connect" \
  --admin_user="admin" \
  --admin_password="admin123" \
  --admin_email="admin@neighborhood.local" \
  --skip-email 2>/dev/null || echo "   (Already installed — skipping)"

echo ""
echo "🎨  Activating theme..."
$WP theme activate neighborhood-connect 2>/dev/null || echo "   Theme already active or not found"

echo ""
echo "🔌  Activating plugins..."
$WP plugin activate nc-core   2>/dev/null || echo "   nc-core already active"
$WP plugin activate nc-events 2>/dev/null || echo "   nc-events already active"

echo ""
echo "🔗  Setting up permalinks..."
$WP rewrite structure '/%postname%/' --hard
$WP rewrite flush --hard

echo ""
echo "📂  Creating sample pages..."
declare -A pages=(
  ["About"]="about"
  ["Contact"]="contact"
  ["Events"]="events"
  ["Services"]="services"
  ["Issues"]="issues"
  ["Blog"]="blog"
)

for title in "${!pages[@]}"; do
  slug="${pages[$title]}"
  $WP post create \
    --post_type=page \
    --post_title="$title" \
    --post_name="$slug" \
    --post_status=publish \
    --porcelain 2>/dev/null || true
done

echo ""
echo "📍  Setting front page..."
FRONT_PAGE=$($WP post list --post_type=page --name=home --field=ID --format=ids 2>/dev/null || echo "")
if [ -z "$FRONT_PAGE" ]; then
  FRONT_PAGE=$($WP post create --post_type=page --post_title="Home" --post_name=home --post_status=publish --porcelain)
fi
BLOG_PAGE=$($WP post list --post_type=page --name=blog --field=ID --format=ids 2>/dev/null || echo "")

$WP option update show_on_front page
$WP option update page_on_front "$FRONT_PAGE"
[ -n "$BLOG_PAGE" ] && $WP option update page_for_posts "$BLOG_PAGE"

echo ""
echo "🌱  Adding sample data..."

# Sample Events
$WP post create \
  --post_type=nc_event \
  --post_title="Community Block Party" \
  --post_content="Join us for our annual block party! Food, games, live music, and great company. All residents welcome." \
  --post_status=publish \
  --meta_input='{"_nc_event_date":"2026-05-24","_nc_event_time":"14:00","_nc_event_end_time":"20:00","_nc_location":"Park Avenue & Main St","_nc_capacity":"200","_nc_event_category":"social"}' \
  2>/dev/null || true

$WP post create \
  --post_type=nc_event \
  --post_title="Farmers Market" \
  --post_content="Fresh local produce, artisan goods, and more. Every Saturday morning at Town Square." \
  --post_status=publish \
  --meta_input='{"_nc_event_date":"2026-05-25","_nc_event_time":"08:00","_nc_event_end_time":"13:00","_nc_location":"Town Square","_nc_capacity":"500","_nc_event_category":"food"}' \
  2>/dev/null || true

$WP post create \
  --post_type=nc_event \
  --post_title="Kids Soccer League Kickoff" \
  --post_content="Sign your kids up for the spring soccer league. Ages 6–14 welcome. Coaches needed!" \
  --post_status=publish \
  --meta_input='{"_nc_event_date":"2026-05-27","_nc_event_time":"09:00","_nc_event_end_time":"12:00","_nc_location":"Central Park Sports Field","_nc_capacity":"100","_nc_event_category":"sports"}' \
  2>/dev/null || true

# Sample Services
$WP post create \
  --post_type=nc_service \
  --post_title="Ahmed's Plumbing Services" \
  --post_content="Professional plumbing services for residential and commercial properties. 15+ years experience. Emergency services available 24/7." \
  --post_status=publish \
  --meta_input='{"_nc_service_category":"Plumbing","_nc_price":"From \$60\/hr","_nc_rating":"4.9","_nc_phone":"+1 555-0101"}' \
  2>/dev/null || true

$WP post create \
  --post_type=nc_service \
  --post_title="Green Thumb Gardening" \
  --post_content="Expert garden design, maintenance, and landscaping. Seasonal clean-ups, lawn care, and planting services." \
  --post_status=publish \
  --meta_input='{"_nc_service_category":"Gardening","_nc_price":"From \$40\/hr","_nc_rating":"4.7"}' \
  2>/dev/null || true

# Sample Issues
$WP post create \
  --post_type=nc_issue \
  --post_title="Broken Streetlight — Oak Ave & 5th St" \
  --post_content="The streetlight at this intersection has been out for 3 weeks. It's a safety hazard for pedestrians and cyclists at night." \
  --post_status=publish \
  --meta_input='{"_nc_status":"open","_nc_location":"Oak Ave & 5th St","_nc_votes":"23"}' \
  2>/dev/null || true

$WP post create \
  --post_type=nc_issue \
  --post_title="Large Pothole on Riverside Road" \
  --post_content="There is a large pothole that has already caused at least two flat tyres. Needs urgent repair." \
  --post_status=publish \
  --meta_input='{"_nc_status":"in-progress","_nc_location":"Riverside Rd near No. 42","_nc_votes":"18"}' \
  2>/dev/null || true

echo ""
echo "✅  Setup complete!"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "  🌐  Site:       http://localhost:8080"
echo "  🔧  Admin:      http://localhost:8080/wp-admin"
echo "  👤  Username:   admin"
echo "  🔑  Password:   admin123"
echo "  🗄️  DB Admin:   http://localhost:8081"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
