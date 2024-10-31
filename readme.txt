=== Popular Authors ===
Tags: popular, popular authors, author, top authors, views, top 10
Contributors: webberzone, Ajay
Donate link: https://ajaydsouza.com/donate/
Stable tag: 1.2.1
Requires at least: 6.3
Requires PHP: 7.4
Tested up to: 6.6
License: GPLv2 or later

Discover and appreciate your blog’s most popular authors, a simple and powerful addon for Top 10 - Popular Posts for WordPress.

== Description ==

[Popular Authors WordPress plugin](https://webberzone.com/downloads/popular-authors/) is the ultimate addon for [Top 10](https://webberzone.com/plugins/top-10/), the best plugin to showcase your most popular posts on WordPress. With Popular Authors, you can also highlight the top authors on your blog by page views and give them the recognition they deserve.

Popular Authors lets you display the most popular authors using different methods. You can use the Gutenberg block, the shortcode, the widget or the template tag to insert the popular authors list anywhere on your site. You can also customize the appearance and settings of each method to suit your needs.

Popular Authors also gives you the flexibility to choose the time range for calculating the page views. You can show the popular authors of all time, or limit it to a specific period, such as last day, last week, last month, etc. This way, you can keep your popular authors list fresh and dynamic.

Popular Authors is a must-have addon for Top 10 if you want to boost your blog's engagement and credibility. By showcasing your most popular authors, you can attract more readers, increase social shares, and build a loyal community around your blog.

= Features =

* Block: Add a Gutenberg block by searching for `popular authors` or `author` and customize its settings and style
* Multi-widget capable: You can have multiple widgets of Popular Authors on your sidebar or footer, each with its own title, number of authors, time range, and more
* Custom Time Range: List popular authors within a specific time range (eg. last 1 day, last 7 days, last 30 days, etc.) or show the all-time favorites
* Shortcode: Use the `[wzpa_popular_authors]` shortcode to display your most popular authors anywhere in your posts or pages
* Template tags: Use `wzpa_list_popular_authors()` to display the popular authors programmatically in your theme files or plugins
* Inbuilt Styles: You can select between two inbuilt styles or create your own using CSS

= Contribute =

Popular Authors is also available on [Github](https://github.com/webberzone/popular-authors). If you've got some cool feature that you'd like to implement into the plugin or a bug you've been able to fix, consider forking the project and sending me a pull request. Please don't use that for support requests.


== Screenshots ==

1. Popular Authors Widget settings
2. Popular Authors tab under Top 10 Settings

== Installation ==

= WordPress install (the easy way) =
1. Navigate to Plugins within your WordPress Admin Area

2. Click "Add new" and in the search box enter "Popular Authors"

3. Find the plugin in the list (usually the first result) and click "Install Now"

= Manual install =
1. Download the plugin

2. Extract the contents of popular-authors.zip to wp-content/plugins/ folder. You should get a folder called popular-authors.

3. Activate the Plugin in WP-Admin.

= Usage =

Popular Authors can be used in four ways:

1. Block: Add a Gutenberg block by searching for `popular authors` or `author`
2. Widget: Simply drag and drop "Popular Authors" widget into your theme’s sidebar and configure it
3. Shortcode `[wzpa_popular_authors]`, so you can embed it inside a post or a page
4. Template tag: Use `wzpa_list_popular_authors()` to display the popular authors anywhere on your theme

== Frequently Asked Questions ==

Check out the [FAQ on the plugin page](https://wordpress.org/plugins/popular-authors/#faq) and the [FAQ on the WebberZone knowledgebase](https://webberzone.com/support/section/popular-authors/).
It is the fastest way to get support as I monitor the forums regularly. I also provide [*paid* premium support via email](https://webberzone.com/support/).


= How can I customise the output? =

The main CSS class is:

* **wzpa_authors** or **wzpa_authors_daily**: Class of the main wrapper `div`

= Shortcodes =

Use `[wzpa_popular_authors]` to display the popular authors. Check [this knowledge base article for shortcode parameters](https://webberzone.com/support/knowledgebase/popular-authors-shortcode/)

== Changelog ==

= 1.2.1 =

* Bug fixes:
	* Removed incorrect shortcode `tptn_views`.
	* Styles Handler used the wrong filter.
	* Renamed cache setting to avoid conflict with Top 10.

= 1.2.0 =

Release post: [https://webberzone.com/announcements/popular-authors-1-2-0](https://webberzone.com/announcements/popular-authors-1-2-0)

Popular Authors has been rewritten to use classes and autoloading.

* Features:
	* New style options: Choose between a Card Layout or Left Thumbs
	* New filter: `wzpa_custom_template` which can be used to override the Popular Authors HTML output
	* New filters: `wzpa_query_fields`, `wzpa_query_join`, `wzpa_query_where`, `wzpa_query_groupby`, `wzpa_query_orderby` and `wzpa_query_limits` to modify the Popular Authors mySQL query

* Bug fixes:
	* Block gave validation errors for the Daily range and Hour range fields when blank

= 1.1.1 =

* Security fix in block

= 1.1.0 =

Release post: [https://webberzone.com/announcements/popular-authors-1-1-0](https://webberzone.com/announcements/popular-authors-1-1-0)

* Features:
	* New Gutenberg block. Find it by searching for `popular authors` or `author`
	* New settings tab added to Top 10 Settings page where global settings for this plugin can be configured
	* New setting to display the author avatar
	* New setting to cache the output. This option respects the cache settings of Top 10 and uses the similar transient names

* Enhancements/Modifications:
	* An admin notice will be displayed if Top 10 v3 and above is not installed

= 1.0.1 =

* Widget now has two additonal settings: Exclude admins and Show full names to make it easier to use

= 1.0.0 =

* Initial release

For previous changelog entries please visit [Github Releases page](https://github.com/WebberZone/popular-authors/releases)


== Upgrade Notice ==

= 1.2.1 =
Fixes severa bugs. Check the release post on WebberZone.com
