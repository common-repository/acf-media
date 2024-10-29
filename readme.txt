=== Advanced Custom Fields: Media Field ===
Contributors: 300FeetOut
Tags: acf
Requires at least: 3.6.0
Tested up to: 5.2.0
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides a media field for ACF that allows for image, video, background-position, title and alt to all exist within one field.

== Description ==

The field stores json with a url, background-position string, title and alt for use with custom theme development.

= Compatibility =

This ACF field type is compatible with:
* ACF 5

== Installation ==

1. Copy the `acf_media` folder into your `wp-content/plugins` folder
2. Activate the Advanced Custom Fields: Media plugin via the plugins admin page
3. Create a new field via ACF and select the Media type
4. Read the description above for usage instructions

== Changelog ==

= 1.0.0 =
* Initial Release.
= 1.1.0 =
* Added fallback image support to video files.