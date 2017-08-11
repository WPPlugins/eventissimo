=== Eventissimo ===
Contributors: Digitalissimo
Tags: events, event, calendar, facebook, shortcode, widget
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=UXV6CWADKJQ5A
Requires at least: 3.8
Tested up to: 3.4
Stable tag: 1.4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create and organize events into your site.
Your events also automatically  created on Facebook.
Import your Facebook Events.

== Description ==
Now into your site Wordpress you can create and organize your events.

*   Create and duplicate events
*   Create Events only date or repeat
*   Gallery of the events
*   Whenever  you add an event to WordPress will automatically be added to Facebook (user or organizer organizer page)
*   Post Events automatically on your Facebook profile
*   Import your Facebook Events
*   View Calendar of your events
*   Export events with url Json o Rss (eg. http://www.example.com/apievent/)
*   Use Widget or shortcode for view events into your site

Multilanguage: English, Italian


= Facebook =
To automate the creation of an event on facebook you have to register as a developer on facebook, create an APP and to recove id and privat key of APP.
NEWS: Import your events of Facebook!!!

= Note: Single Template =
If you would change single template of events copy pages/events-template-single.php into your template and custom it.

== Other notes ==

= SHORTCODE =

**Calendar**

*  `[eventissimo type='CALENDAR' backcolorHEX='[#069c88]' fontcolorHEX='[#FFFFFF]' search='[TRUE|FALSE]']` :  BackcolorHEX is backcolor of the event's title. Default is #069c88
FontcolorHEX is the font color of the event's title. Default is  #FFFFFF
Search is TRUE for input search into calendar FALSE not displayed


**List or Block Events**

*  `[eventissimo type='[LIST|BLOCK]' date='true' ]`: you view date of events
* `[eventissimo type='[LIST|BLOCK]' limit='#' ]`: you type a number for limit list event, default 10
*  `[eventissimo type='[LIST|BLOCK]' paginate='true' ]`: you view events with pagination (events per page defined with limit number, if not defined number is 10).
*  `[eventissimo type='[LIST|BLOCK]' view='[OLD|NEXT]']`: you defined past events or next events, Default is NEXT
*  `[eventissimo type='[LIST|BLOCK]' defined='TODAY|MONTH']`: you defined today events or all events of current month. MONTH combined with view NEXT lets you see only next events.

**Slideshow**

*  `[eventissimo type='CYCLE' view='[OLD|NEXT]' defined='TODAY|MONTH']`

=API EVENTS=

**Example**

*  Events Feed Rss `http://www.tests.it/apievent/get?type=rss`
*  Events Json `http://www.tests.it/apievent/get?type=json`

**Parameters**

*  *type*: JSON | RSS ex: `http://www.tests.it/apievent/get?type=rss`
*  *search*: id_author | description | title
   *value*: terms of search 
   eg: `http://www.tests.it/apievent/getapievent/get?type=rss&search=id_author&value=1`
*  *defined*: TODAY | MONTH  eg: `http://www.tests.it/apievent/get?type=rss&defined=TODAY`
*  *view*: OLD | NEXT  eg: `http://www.tests.it/apievent/get?type=rss&view=NEXT`

**Structure Json**

`[
{
"id": 123,
"classname": 123,
"randomColor": "#C913D5",
"id_author": "1",
"description": "test events for json",
"title": "Test event",
"url": "http://www.example.it/events/tests",
"thumbs": <img width="150" height="150" src="http://www.example.it/wp-content/uploads/2014/02/example150x150.jpg" class="attachment-thumbnail wp-post-image" alt="Example" />,
"cover":<img width="690" height="300" src="http://www.example.it/wp-content/uploads/2014/02/example90x300.jpg" class="attachment-fb_cover_image wp-post-image" alt="Example" />,
"coverBig":<img width="690" height="500" src="http://www.example.it/wp-content/uploads/2014/02/example.jpg" class="attachment-large wp-post-image" alt="Example" />,
"types": [
{
"term_id": 1,
"name": "test1",
"slug": "test1",
"term_group": 0,
"term_taxonomy_id": 1,
"taxonomy": "typeEvents",
"description": "",
"parent": 0,
"count": 1,
"filter": "raw"
}
],
"categories": [
{
"term_id": 2,
"name": "test2",
"slug": "test2",
"term_group": 0,
"term_taxonomy_id": 2,
"taxonomy": "eventscategories",
"description": "",
"parent": 0,
"count": 1,
"filter": "raw"
},
{
"term_id": 3,
"name": "test3",
"slug": "test3",
"term_group": 0,
"term_taxonomy_id": 3,
"taxonomy": "eventscategories",
"description": "",
"parent": 0,
"count": 1,
"filter": "raw"
}
],
"date_begin": "1391904000",
"date_end": "1391904000",
"hour_begin": "00:00",
"hour_end": "03:00"
},
{..}
]`


== Screenshots ==
1. Create Facebook Post 
2. Create Event Facebook 

== Installation ==
1. Upload `eventissimo` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. If you want to publish events on Facebook you must create an App

== Changelog ==
= 1.4.3 =
* Fixed problem with hour event
* Added Shortcode calendar with search
* Fixed color shortcode calendar
= 1.4.2 =
* Fixed hour of event Facebook
* Choise if the owner event is a page (if user is administrator of the page)
= 1.4.1 =
* Fixed option.php error of configuration plugin
* Added possibility to choice of what to display on the page event
= 1.4 =
* Fixed problem json
= 1.3.9 =
* Fixed conflict Time events
= 1.3.8 =
* Fixed problem Slub single event
= 1.3.7 =
* Added call Json and Rss of events
= 1.3.6 =
* Import your Facebook events Thanks for idea http://www.richardoutram.co.uk
= 1.3 =
* Added Single Events custom
= 1.2 =
* Correct Bugs Link Events, added Slideshow events
= 1.1.1 =
* Correct Bugs css and Facebook
= 1.0.1 =
* Correct Bug Bootstrap
= 1.0.0 =
* Create plugin

== Upgrade Notice ==
* 

== Frequently Asked Questions ==
=  How do I create an app on facebook? = 
See https://developers.facebook.com/
