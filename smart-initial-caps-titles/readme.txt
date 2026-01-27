=== Smart Initial Caps Titles ===
Contributors: amadasunese
Tags: titles, typography, headlines, news, formatting
Requires at least: 5.5
Tested up to: 6.9
Requires PHP: 7.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically converts post titles to Initial Caps while preserving ALL CAPS and numeric acronyms like FG, INEC, 5G, 2FA, and 50BN.

== Description ==

Smart Initial Caps Titles intelligently formats WordPress post titles by converting them to Initial Caps while preserving important acronyms and numeric terms commonly used in news, tech, and official content.

Unlike CSS-only solutions, this plugin safely processes titles at runtime without modifying database content or harming SEO.

Perfect for:
* News websites
* Blogs with ALL CAPS headlines
* Government, politics, and tech content

== Features ==

* Automatically preserves ALL CAPS words (FG, INEC, USA)
* Preserves numeric acronyms (5G, 2FA, 50BN, 2027PVC)
* Converts remaining words to Initial Caps
* Optional category-based rules (e.g. apply only to Politics)
* Choose where it applies:
  - Homepage / Blog page
  - Single posts
  - Archives
* Performance optimized:
  - Skips short titles
  - Uses WordPress object cache
* SEO-safe (no database changes)

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate **Smart Initial Caps Titles** from the Plugins menu
3. Go to **Settings → Initial Caps Titles**
4. Configure where and how the formatter applies

== Frequently Asked Questions ==

= Does this change my post titles permanently? =
No. Titles remain unchanged in the database. Formatting is applied dynamically.

= Will this affect SEO? =
No. Search engines read the same title content; only casing is adjusted for display.

= Can I limit this to certain categories? =
Yes. You can select specific categories from the settings page.

= Does it support numeric acronyms like 5G or 2FA? =
Yes. These are automatically detected and preserved.

== Screenshots ==

1. Settings page showing location and category controls
2. Example homepage titles with preserved acronyms

== Changelog ==

= 1.1.0 =
* Added numeric acronym detection (5G, 2FA, 50BN)
* Added category-based rules
* Performance optimizations with caching
* Improved settings UI

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.1.0 =
Adds numeric acronym preservation, category filtering, and performance improvements.
