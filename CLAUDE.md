# Project: Logic Auto Parts

Nuxt 3 storefront for a European car-parts e-commerce site (BMW/Mercedes/Audi/Porsche/Land Rover). Backed by a WordPress REST API on `qsz.zoy.temporary.site/website_11f3c7a8` (hardcoded as `WP_URL` in `composables/useVehicleData.js`).

Note: `nuxt.config.ts` / `cms.x-trekkers.com` GraphQL config and a few components (`Post.vue`, `ReviewsSec.vue`, `MontBlacSlider.vue`) are leftovers from a different, unrelated prior project (a trekking site) - not mounted anywhere, ignore them.

## WordPress snippets (`wp-snippets/`)

The WP backend runs custom PHP snippets managed through a code-snippets plugin - **not files on the live server's disk**. This folder holds mirrored copies so changes can be developed/reviewed here.

**Workflow**: when a snippet needs a code change, edit the file in `wp-snippets/` here. The user then manually copies the updated code into the live WP snippet editor themselves - there is no direct write access to the live backend's PHP from this repo/session.

Four snippets:
1. **`bulk-import-endpoint.php`** - CSV bulk import (`custom/v1/import-products`, `import-fitment`) plus the admin panel's product CRUD API: `custom/v1/admin-products` (list), `admin-product-save` (create/update), `admin-product-delete`. Auth: `X-Import-Secret` header (shared secret, constant `BULK_IMPORT_SECRET`), checked via `hash_equals`. This constant is reused by `orders-endpoint.php` below - **must stay active** alongside it.
2. **`temporary-csv-fitment-parser.php`** - public/unauthenticated read routes: `custom/v2/vehicle` (drives all cascading vehicle dropdowns + the products sidebar tree) and `custom/v1/get-user-billing` (looks up a WP user by email - **no auth check, known PII exposure**, not yet fixed).
3. **`extract-ebay-fitment-data.php`** - one-off admin-only maintenance script (not a REST route), triggered via `?run_fitment_sync=1` in wp-admin. Parses a big eBay CSV export to backfill `wp_custom_product_fitment`. Treat as a throwaway tool, not part of the standing API surface.
4. **`orders-endpoint.php`** (added 2026-07-25, **not yet live** - see Orders section below) - order tracking tables + admin/customer order APIs.

There's also a couple of **external routes not mirrored here** (source never pasted into this repo - ask the user for current source before changing them):
- `custom/v1/product-detail?id=...` (full product + fitment + images for one item).
- `custom/v1/process-square-payment` - charges the card via Square. **Has a live, confirmed bug**: it sends `currency: GBP` (or similar) to Square's Create Payment API, but the Square sandbox location is USD-only, so every payment attempt fails with `"This merchant can only process payments in USD, but amount was provided in GBP."` (confirmed 2026-07-25 via a live end-to-end checkout test with a Square sandbox test card). This is on top of the still-needed `custom_record_order()` integration (see Orders section) - can't fix either without this snippet's source, never shared yet.
- Whatever handles register/login against `w84_custom_app_users` (see below) - also never seen, not required for the orders work so far since orders link by email instead.

## Checkout flow bug found & fixed (2026-07-25)

`pages/checkout.vue`'s `WP_URL` constant was missing a trailing slash before its query string: `'https://qsz.zoy.temporary.site/website_11f3c7a8?rest_route=/'`. WordPress 301-redirects that to the trailing-slash form, and a cross-origin POST that hits a redirect gets blocked by the browser (`TypeError: Failed to fetch`) - so every real checkout attempt was silently dying before ever reaching `process-square-payment`, surfacing only as the generic toast "Failed communicating with core gateway." **Fixed** by adding the trailing slash (`.../website_11f3c7a8/?rest_route=/`). Confirmed via a live browser test (filled real guest details + a Square sandbox test card, patched `window.fetch` to inspect the actual request/response) that the request now reaches the backend - which is how Bug 2 above (GBP vs USD) surfaced next.

## Database tables in use

- **`wp_custom_products`** (PK `item_id`, **not** AUTO_INCREMENT - always compute `SELECT MAX(item_id)+1` before inserting) - one row per product: sku, title, price, description, brand, placement_on_vehicle, manufacturer_part_number, interchange_part_number, other_part_number, fitment_notes, vin_required_message.
- **`wp_custom_product_fitment`** (FK `product_id` → `item_id`) - one row per year/make/model/submodel/engine combo a product fits.
- **`wp_custom_product_images`** (FK `product_id` → `item_id`) - one row per product image (`picture_url`).
- **`w84_custom_app_users`** (id, username, email, password [WP-style bcrypt hash], created_at) - the site's **own custom login/register system**, separate from WordPress's core `wp_users` table. This is the real "customer account" table for the storefront (confirmed via live phpMyAdmin dump 2026-07-25, 3 rows). Note the `w84_` prefix differs from the `wp_custom_*` naming used elsewhere - a literal table name, not `$wpdb->prefix`-driven, so don't assume other tables share this prefix.
- **`wp_custom_orders`** / **`wp_custom_order_items`** (added 2026-07-25, schema in `orders-endpoint.php`, **not yet created on the live DB** - visit `?run_orders_setup=1` as admin once the snippet is pasted in) - see Orders section below.

Both fitment/images and order/order-items are one-to-many per product/order - never assume a flat 1-row shape.

## Admin panel

Lives inside this Nuxt app: `pages/admin/{products,orders}/{index,new,[id]}.vue` (products has `new.vue`, orders doesn't need one - orders are only created by checkout) + shared `components/AdminProductForm.vue`. Nav in `layouts/admin.vue`. Login via `pages/admin/index.vue` → `server/api/admin-login.js` (checks `ADMIN_USERNAME`/`ADMIN_PASSWORD` from `.env`) → `sessionStorage` flag checked by `middleware/admin-auth.js`.

## Orders / customer tracking (started 2026-07-25, client-requested next phase)

Client asked to move past the product catalog into "customers, orders, all of this" - explicitly **not** WooCommerce, a fully custom system matching the existing custom-table approach. Built so far:

- `wp-snippets/orders-endpoint.php`: `wp_custom_orders` + `wp_custom_order_items` tables (created via `?run_orders_setup=1`, same one-off pattern as the fitment CSV script), a `custom_record_order($data)` PHP function, and REST routes `admin-orders`/`admin-order-detail`/`admin-order-update-status` (gated by the same `X-Import-Secret`) plus a public `my-orders?email=` (customer-facing history, same "public-by-email" pattern as `get-user-billing` - same caveat, no ownership check).
- `pages/admin/orders/index.vue` + `[id].vue`: admin order list (search/status filter) + detail view with a status dropdown (pending/paid/processing/shipped/delivered/cancelled/refunded).
- Orders link to `w84_custom_app_users` via `app_user_id`, resolved by matching the checkout email against that table - nullable, so guest checkouts still work fine without a matching account.

**Still needed before this is live/functional (in order):**
1. User pastes `orders-endpoint.php` into WP, visits `?run_orders_setup=1` once to create the two tables.
2. The actual gap: **`process-square-payment`'s source has never been shared** - need it to add one call, `custom_record_order($body)`, right after the Square charge succeeds (it already receives `billingDetails`/`itemsOrdered`/`amountCents`/`userType` from `pages/checkout.vue` - same shape `custom_record_order()` expects, plus whatever Square returns as the payment id). Without this, checkouts won't create order rows at all.
3. No customer-facing "my orders" page built yet in the Nuxt app (the `my-orders` API exists, UI doesn't) - not asked for yet, only the admin side was requested so far.

## Last session (2026-07-25) - bugs found & fixed

1. **Edit product silently failed** - `pages/admin/products/[id].vue` was sending an unnecessary `X-Import-Secret` header on its GET to `product-detail`. That forces a CORS preflight, and `product-detail`'s route only gets WordPress's default preflight response (doesn't allow that custom header), so the browser blocked the whole request (`Failed to fetch`) and the edit form never loaded real data. **Fixed** by dropping that header (the route is public and never checked it anyway). Verified live end-to-end (edit → change price → save → confirmed persisted → reverted).
2. **Add-product image section looked capped at 1 image** - wasn't actually capped (unlimited via "+ Add Image"), just started with zero visible slots. **Fixed**: `AdminProductForm.vue` now seeds 4 empty image slots by default to match the storefront's typical gallery size.
3. **Fitment section: delete icon/fields overflowing the card** - the delete button was crammed into the same grid cell as the Engine input via a nested flex, squeezing it past the card edge at admin-panel content widths. **Fixed**: gave the delete button its own dedicated narrow grid column (`md:grid-cols-[repeat(5,minmax(0,1fr))_2rem]`) instead of nesting it inside the Engine cell.

**Open/known issues, not yet fixed** (flagged to user, low priority unless asked):
- `get-user-billing` and a `forgot-password` → `change-password` flow both expose/allow account actions with no auth/ownership check (PII leak + account-takeover risk).
- `pages/checkout-auth.vue` has a stray literal `""` text node rendering at the top of the sign-in/guest checkout screen.
