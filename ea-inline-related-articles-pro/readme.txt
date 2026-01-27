=== EA Inline Related Articles Pro ===
Contributors: amadasunese
Donate link: https://amadasunese.pythonanywhere.com
Tags: related posts, inline articles, news seo, internal linking, editorial tools
Requires at least: 5.5
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 2.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically insert inline related articles inside post content to improve engagement, SEO, and reader retention.

== Description ==

EA Inline Related Articles Pro automatically injects **contextually relevant articles directly inside your post content**, without requiring you to edit existing posts.

Unlike traditional related-post widgets that appear only at the bottom of articles, this plugin places related content **within the article flow**, increasing visibility and engagement.

It is built specifically for:
* News websites
* Digital newspapers
* Blogs with long-form content
* SEO-focused publishers

The plugin works on **already published posts** and is safe for production use.

= Key Features =

* Admin settings page
* Choose **multiple paragraph positions** (e.g. 3, 7, 12)
* Insert **multiple inline blocks per article**
* AI-powered relevance (offline, no API required)
* Category-based fallback relevance
* Lightweight and performance-friendly
* Works with Classic Editor and Block Editor
* PHP 5.6+ compatible

= How It Works =

1. The plugin splits post content into paragraphs.
2. Inline related articles are injected after selected paragraph numbers.
3. Related articles are ranked using:
   * AI-style content similarity scoring (optional)
   * Category relevance as fallback
4. Content is injected dynamically — your posts are never modified in the database.

= SEO Benefits =

* Improves internal linking structure
* Increases page views per session
* Reduces bounce rate
* Helps search engines crawl deeper content
* Ideal for evergreen and breaking news articles

== Installation ==

= Automatic Installation =

1. Go to Plugins → Add New
2. Search for “EA Inline Related Articles Pro”
3. Install and activate the plugin
4. Go to Settings → Inline Related Articles

= Manual Installation =

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin from the Plugins menu
3. Configure settings under Settings → Inline Related Articles

== Configuration ==

After activation, go to:

Settings → Inline Related Articles

Available options:

* Paragraph numbers (comma-separated, e.g. 3,7,12)
* Number of related articles per block
* Enable or disable AI-powered relevance

== Frequently Asked Questions ==

= Does this permanently change my post content? =
No. The plugin injects content dynamically when the page loads.

= Does it work with old posts? =
Yes. It works automatically on all published posts.

= Does it require an external AI API? =
No. AI relevance works offline and does not send data externally.

= Is it compatible with caching plugins? =
Yes. Fully compatible with popular caching plugins.

= Does it support AMP? =
AMP compatibility is planned for a future release.

== Screenshots ==

1. Inline related articles displayed inside post content
2. Admin settings page
3. Multiple inline blocks within a single article

== Changelog ==

= 2.0.2 =
* PHP 5.6 compatibility fix
* Improved relevance sorting stability

= 2.0.0 =
* Admin settings page
* Multiple inline blocks per article
* AI-powered relevance engine
* Performance improvements

= 1.0.0 =
* Initial release
* Single inline block
* Category-based relevance

== Upgrade Notice ==

= 2.0.0 =
Major update with admin controls, multiple inline blocks, and AI-powered relevance.

== License ==

This plugin is licensed under the GNU General Public License v2 or later.

You are free to use, modify, and redistribute this software under the terms of the GPL.
