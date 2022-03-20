=== ShopWP ===
Contributors: andrewmrobbins
Donate link: https://wpshop.io/purchase/
Tags: shopify, ecommerce, store, sell, products, shop, purchase, buy, wpshopify
Requires at least: 5.4
Requires PHP: 5.6
Tested up to: 5.8.3
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Build custom Shopify experiences on WordPress.

== Description ==

**Note: If you're upgrading from version 3.x, please [read through the 4.0 migration guide first](https://docs.wpshop.io/guides/migrating-to-4.0)**

Sell Shopify products on any WordPress site with ShopWP. Buy buttons? No problem. You can display product variants as buttons or dropdowns. Enable one-time purchases or subscriptions. Send customers directly to the Shopify checkout or add products to the built-in cart. We have all the shortcodes and blocks you need to build a uniquely branded ecommerce experience on WordPress.

Whether you’re using WordPress as a landing page or need a whole storefront, ShopWP will provide beautiful layouts and give your customers the confidence they need to buy from you.

We believe your ecommerce store should represent your brand. It shouldn't cost tens of thousands of dollars to built a shopping experience. We want to empower entrepreneurs and small businesses to create ecommerce experiences that rival the big players.

= Features =
* Simple to use buy buttons 
* Sync product / collection detail pages
* No iFrames
* 10 shortcodes for displaying products 
* Built-in [cart experience](https://wpshop.io/features/#cart-experience)
* SEO optimized
* Filtering and sorting functionality (Pro only)
* [Show variants as buttons](https://wpshop.io/features/#variant-buttons) instead of dropdowns (Pro only)
* Show products in [carousels](https://wpshop.io/features/#carousel) or modals (Pro only)
* [Subscription products](https://wpshop.io/features/#subscriptions) via Recharge extension (Pro only)
* [Direct checkout](https://wpshop.io/features/#direct-checkout) (Pro only)

See the [full list of features here](https://wpshop.io/features/)

= ShopWP Pro =
Use discount code `15OFF` to save 15% off when upgrading to [ShopWP Pro](https://wpshop.io/purchase). Take your store to the next level with awesome features like: template overrides, filtering / sorting, automatic post syncing, dedicated support, and more! [Save 15% off ShopWP Pro](https://wpshop.io/purchase)

= Links =
* [Website](https://wpshop.io/)
* [Documentation](https://docs.wpshop.io/)
* [ShopWP Pro](https://wpshop.io/purchase/)
* [Demo](https://wpshop.io/features/)

== Installation ==
From the WordPress dashboard:

1. Visit Plugins > Add New
2. Search for *ShopWP*
3. Activate ShopWP from your Plugins page
4. Click on the new menu item called __ShopWP__ 
5. Within the Connect tab, click the "Begin the connection process" button
6. Follow the wizard to connect your Shopify store. We've [created a guide](https://docs.wpshop.io/getting-started/syncing/).

== Screenshots ==
1. Connect your Shopify store to WordPress
2. Sync your Shopify store
3. Example of the syncing process running
4. How the plugin settings appear
5. Edit screen of the product detail page
6. List of product posts

== Frequently Asked Questions ==

Read the [full list of FAQ](https://wpshop.io/faq/)

= How does this work? =
You can think of WordPress as the frontend and Shopify as the backend. You still manage your store data inside Shopify (e.g., changing prices) and those changes automatically show inside WordPress. ShopWP is bundled with its own fly-out cart experience and allows you to sell directly on WordPress. When the user is ready to checkout, they're redirected to the defalt Shopify checkout page to enter payment information.

After installing the plugin, you can connect your Shopify store by following the easy to use wizard. After connecting, you can display your products in the following ways:

- Using the default pages "example.com/products" and "example.com/collections"
- Shortcodes
- Programmatically through the plugin’s Render API

You can also create product detail pages by syncing the product posts.

= Is this SEO friendly? =
We’ve gone to great lengths to ensure we’ve conformed to all the SEO best practices including semantic alt text, Structured Data, and indexable content.

= Doesn’t Shopify already have a WordPress plugin? =

They used to, but it has [been discontinued](https://wptavern.com/shopify-discontinues-its-official-plugin-for-wordpress).

Shopify has instead moved attention to their buy button embedd, which allows you to show products with a JavaScript snippet. The main drawback to this is that [Shopify](https://www.shopify.com/?ref=wps&utm_content=links&utm_medium=website&utm_source=wpshopify) uses iFrames for the embed which limits the ability for layout customizations. Additionally, managing multiple JavaScript embeds can get annoying really fast.

In contrast, ShopWP creates an iFrame-free experience allowing you to sync Shopify data directly into WordPress. We also save the products and collections as custom post types which unlocks the native power of WordPress itself.

= Does this work with third party Shopify apps? =
The only "Unfortunately no. We rely on the main Shopify API which doesn’t expose third-party app data. However the functionality found in many of the Shopify apps can be reproduced by other WordPress plugins.

= How do I display my products? =
Documentation on how to display your products can be [found here](https://docs.wpshop.io/getting-started/displaying).

= How does the checkout process work? =
ShopWP does not handle any portion of the checkout process. When a customer clicks the checkout button within the cart, they’re redirected to the default Shopify checkout page to finish the process.

= Does this work with Shopify's Lite plan? =
Absolutely! In fact this is our recommendation if you intend to only sell on WordPress. More information on Shopify's [Lite plan](https://www.shopify.com/lite)

= Can I use this and Shopify at the same time? =

Absolutely! ShopWP doesn’t prevent you from using [Shopify](https://www.shopify.com/?ref=wps&utm_content=links&utm_medium=website&utm_source=wpshopify) on other platforms like Facebook or using a Shopify theme directly.

== Changelog ==

The full changelog can be [found here](https://wpshop.io/changelog/)

**Note: If you're upgrading from version 3.x, please [read through the 4.0 migration guide first](https://docs.wpshop.io/guides/migrating-to-4.0)**

= 4.1.0 =

- **New feature:** Shopify product SEO information is now synced as post meta
- **New featured:** Multiple collections can now be combined when filtering the Storefront
- **Fixed:** Product carousel not removing skeleton loader
- **Fixed:** Carousel height issue
- **Fixed:** Bug causing error: Cannot read properties of undefined
- **Added:** New plugin setting to adjust the currency symbol and code
- **Added:** New PHP function `get_products_by_collection_ids`
- **Added:** New PHP function `get_products`
- **Added:** Icons for Edit in shopify links
- **Added:** New icon for empty cart state
- **Added:** New illustration on Wizard completion step
- **Improved:** Better error handling during syncing process
- **Updated:** The syncing process now uses Shopify's GraphQL Storefront API instead of the old Rest API
- **Dev:** Updated Composer and NPM dependencies

= 4.0.17 =
- **Fixed:** Bug causing out of stock variants to show as available
- **Improved:** Storefront skeleton loaders are now smoother
- **Improved:** Added better error handling when fetching items
- **Dev:** Updated dev dependencies

= 4.0.16 =
- **Fixed:** Bug causing checkout redirect to fail sometimes
- **Fixed:** Bug causing skeleton loader to stay visible if no products notice shows
- **Updated:** Plugin now uses Storefront API version 2022-01

= 4.0.15 =
- **Fixed:** Bug causing free version to stay deactivated
- **Updated:** Added new basename for free version

= 4.0.14 =
- **Fixed:** Bug causing products not to show when showing more than one with `connective="or"`
- **Fixed:** Bug causing the `do.shopRender` not to work properly
- **Fixed:** Bug causing Storefront products to disappear when selecting custom Price options
- **Fixed:** Bug within `get_all_product_vendors` when caching Storefront vendors
- **Fixed:** Turning off the plugin cache would not "skip" the cache as expected
- **Added:** New Visual Builder tab within the plugin settings in preparation for `4.1.0`.
- **Updated:** Reduced JavaScript payload size
- **Updated:** Adjusted the syncing process checks to improve performance
- **Updated:** Changed default cache time to one day
- **Updated:** Added better timing for removing skeleton loaders
- **Dev:** Updated dependencies