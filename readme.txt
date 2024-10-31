=== Scrnshots Feed ===
Contributors: sksmatt
Donate link: http://www.mattvarone.com/donate/
Tags: scrnshots.com, scrnshots, widget, screenshots, service, json
Requires at least: 3.2
Tested up to: 3.2.2
Stable tag: 3.0

Widget to display Scrnshots.com captures on your sites.

== Description ==

This Widget for WordPress allows you to show Scrnshots.com captures on your sites. It's very easy to setup and configure via the widgets panel.

== Installation ==

1. Upload the files.
2. Activate the plugin.
3. Configure your scrnshots feed widget under the Appearance > Widgets tab. 

== Advanced Usage ==

The function is `<?php get_scrnshots_feed(); ?>` allows to print your scrnshots feed on any part of a theme. It supports the following parameters:

`
// set defaults
$defaults = array(
	'num_items' => 10, // (int) how many photos you want to display.
	'imagesize' => 'medium', // (string) small, medium, large or fullsize.
	'id_name' => 'sksmatt', // (string) specify a user id ( ie:sksmatt ).
	'rel' => '', // (boolean) Adds rel="nofollow" to links.
);`


EXAMPLE

The `$args` above would show the 10 most recent screnshots from `sksmatt` user in medium size.
