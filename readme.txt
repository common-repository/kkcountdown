=== KK Countdown ===
Contributors: Krzysztof Furtak
Version: 1.3
Tags: countdown, count, down, time
Requires at least: 2.8
Tested up to: 3.0
Stable tag: trunk

== Description ==

Plug-in counts time to a particular date in the future.

= Shortcode =

Tag can be inserted in a random place on web pages or in articles.

Tag has to hold two attributes:
* The first one, called "IDKKC" is a mandatory one. The ID of countdown needs to be provided (example: `IDKKC = "3"`)
* The second, called "KKCHEAD" is an optional one.  It gives an option to either turn on or turn off a countdown header. This attribute takes two values:  0 (header is off) and 1 (header is on)(example: `KKCHEAD = "1"`).

Correct tag should be as following:
`[kkcountdown  idkkc = "3"  kkchead = "1" ]` or `[kkcountdown  idkkc = "3"]`


DEMO: http://krzysztof-furtak.pl/2010/05/wp-kkcountdown-plugin/
Author Site: http://krzysztof-furtak.pl/

== Installation ==

1. Upload plugin to the `/wp-content/plugins/` directory

2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= If it works? =
Yes :)

== Screenshots ==
1. PA
2. PA - Edit Option
3. Shortcode

== Changelog ==

= 1.3 =
* NEW: Function added allows to adjust the settings of countdown.
* CHANGE: Plugin is fully based on Ajax (no need to reload a page ).
* CHANGE: Modified view of plugin settings.
* FIX: Plugin bug causing issues under Wordpress 3.0

= 1.2.3 =
* FIX: Bug in javascript init function

= 1.2.2 =
* FIX: Some bug in javascript function

= 1.2.1 =
* NEW: German translation - thx for Thomas.
* CHANGE: Plugin website.

= 1.2 =
* NEW: Widget can have a title set up.
* NEW: Icons included in a control panel.
* NEW: Countdown can be either activated or deactivated.
* NEW: New options included such as : ‘add/edit countdown’.
* NEW: Additional tag is introduced which can be used as a countdown in articles and web pages.
* CHANGE: Improved countdown script which is more efficient and works much faster.

= 1.1 =
* Change: Interface Updated

= 1.1.1 =
* Change: Interface Updated

= 1.1 =
* Change: Interface Updated

= 1.0.1 =
* NEW: Support for languages - English, Polish

= 1.0 =
* NEW: possibility to add as many dates as you wish till which time will be counted
* NEW: If there are less than 24 hours left to a particular day, digits will start glowing in red

