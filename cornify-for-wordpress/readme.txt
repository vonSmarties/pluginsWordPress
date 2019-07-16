=== Cornify for WordPress ===
Contributors: BandonRandon
Tags: cornify, unicorns, rainbows, april fools
Requires at least: 2.9
Tested up to: 4.9-RC2
Stable tag: 1.1

Cornify Your WordPress Website.

== Description ==

Adds Cornify (cornify.com) to your WordPress. After five seconds of inactivity the site will show unicorns
to the visitor until they interact with the site again. This was developed primarily as an April fools joke.

== Installation ==

Just activate the plugin and if you don't interact with your site you should see unicorns. There is no control panel, at least for the moment.
See FAQ for manual options. When you're done with it and had enough, just deactivate it.

== Frequently Asked Questions ==

= The plugin doesn't seem to work. Do I need anything special in my theme? =

Yes, your theme must have a call to `php wp_footer(); ?>` at the very bottom right before the `` tag.

= Can I change the interval of the unicorns? =

Yes, you can change `var idol_time = 5000;` in js/cornify.js to the number of (milli)seconds you would like before unicorns start showing up.

== Screenshots ==

1. A cornified site

== Changelog ==

= 1.2 =
* Tested with WordPress 4.9 RC2
* Removed manual if statement in `cornify-wordpress.php` plugin is now always on. This option may come back as an option.

= 1.1 =
* Added JS to the footer instead of the header
* Moved JS to a folder instead of in the root
* Renamed utils.js to cornify.js
* Use `wp_enqueue_scripts` instead of `template_redirect`

= 1.0 =
* Just launched.

== Upgrade Notice ==

= 1.0 =
First release
