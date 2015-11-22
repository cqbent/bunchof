=== Jet Event System for BuddyPress ===
Contributors: milordk
Donate link: http://milordk.ru/projects/wordpress-buddypress/podderzhka.html
Plugin link: http://milordk.ru/r-lichnoe/opyt/cms/jet-event-system-for-buddypress-sistema-sobytij-dlya-vashej-socialnoj-seti.html
Authoe link: http://milordk.ru/
Tags: BuddyPress, Wordpress MU, meta, members, widget, event, group, invite, events
Requires at least: 3.1, BuddyPress 1.2.8
Tested up to: 3.2.1, BuddyPress 1.2.9
Stable tag: trunk

The modern System of events for your social network. Ability to attract members of the network to the ongoing activities, etc.

== Description ==

<strong>en:</strong> The modern System of events for your social network. Ability to attract members of the network to the ongoing activities, a wide range of possibilities and options, support for different types of display, etc.
Support for 9 languages.

<strong>ru:</strong> Современная Система событий для Вашей социальной сети. Возможность привлекать участников сети к проводимым мероприятиям, широкий спектр возможностей и настроек, поддержка разных видов отображения и т.п.
Поддержка 9 языков.

<strong>Before you install or upgrade sure to read the Readme file!</strong>! 
<strong>Перед установкой или обновлением обязательно прочтите Readme файл!</strong>

Translation for the following locations: ru_RU, fr_FR, de_DE, es_ES, da_DK, it_IT, sv_SE (see Translate section in readme)

Live Demo: <a href="http://sportactions.ru">Sport site</a> , <a href="http://volks-wagen-club.ru">Volkswagen Club</a> (Please do not create events on these sites! Use the <a href="http://jes.milordk.ru">jes.milordk.ru</a>)


<a href="http://jes.milordk.ru">Official website of the plugin</a> (You can register and create events, thereby testing the latest version of plug-ins under development)


<em>Implementation of many functions in the future - beyond the needs of my projects. I am interested to develop a plug-in direction of maximum capacity for most users. Absolutely not renounce the support of you, both financially and from the ideas and assistance in programming.</em>


== Installation ==

1. Upload `jet-event-system-for-buddypress` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit the admin panel plugin (BuddyPress -> JES Event System)
4. The plugin automatically updates the Templates (and DB), but if you changed the subject and / or updated plug-in - need to visit the administrative panel of the plugin and follow the instructions - upgrade Templates!
5. Add widget to site (if needed)
6. See Events Catalog: http://yousite/events

== Upgrade Notice ==

* Visit the admin panel and update the database and Templates


== Screenshots ==

1. **Event catalog**
2. **Single Event**
3. **Datepicker**
4. **Widget on Site Wide**
5. **Admin Panel**

== Frequently Asked Questions ==

= Fix conflit BP Ajax Chat =

In /wp-content/plugins/buddypress-ajax-chat/bp-chat/bp-chat-cssjs.php
change <em>add_action( 'template_redirect', 'bp_chat_add_js', 1);</em> to <em>add_action( 'template_redirect', 'bp_chat_add_js', <strong>10</strong>);</em>

= Where can I see the plug-in work? =

You can see the latest version of the dev website: <a href="http://jes.milordk.ru">http://jes.milordk.ru</a>

= Can I affect the development of plug-in? =

Yes, the dev website there is a group for proposals for the development of plug-in. <a href="http://jes.milordk.ru">http://jes.milordk.ru</a>

= I have not updated Templates, what to do? =

Make sure that the owner of the folders and files contained in them is the owner of the web server (www/apache/nginx/nobody?)

= I'm not working choose the date when creating and / or edit an event =

You need to check whether all files are copied Templates. Make sure that all plugin js-scripts are not only registered in the boot, but also present on the specified routes

= I'm not going localization Datapicker, what to do? =

If there was no localization datapicker - check wplocale in administrative panel of the plugin and the presence of a file in the folder plugin / js/jquery-iu-locale/jquery.ui.datepicker- <wplocale>. Js. If not - then rename the file most suitable for you.

= What resources are consumed plugin? =

Home page:
С плагином: Render Time: 0.696 sec (9.6% for queries). DB queries: 57, none defective, none > 0.500 sec. Memory: 34.7MB 
Без плагина: Render Time: 0.632 sec (10.4% for queries). DB queries: 52, none defective, none > 0.500 sec. Memory: 32.3MB

Page "Events directory": Render Time: 0.375 sec (11.0% for queries). DB queries: 59, none defective, none > 0.500 sec. Memory: 34.7MB

= Can I get extended support plug-in or implementation of additional (specific) functional? =
You can, but keep in mind that the cost of implementing individual (not popular majority) functions - should be paid off

== Special Note ==

<strong>Correct operation of the plugin is guaranteed only in case of installation through the administrative panel of Wordpress! If you install the plugin yourself - you must have the necessary knowledge of web servers, access rights & etc.!</strong>

Used your theme should be based on the default theme BP (styles and functions)! The efficiency of the plug can only be guaranteed on these themes!
As one of the options for addressing emerging problems with other themes - connection functions.php from a default theme to your BP

<strong>If you are using a theme different from the BP-Default, be sure to install and activate</strong> - <a href="http://wordpress.org/extend/plugins/bp-template-pack">BP Template Pack</a>

<strong>Conflict jQuery:</strong>
* Event Calendar 6.7.5
* Buddypress Ajax Chat ( see FAQ )

Your wishes for the development of plug-in you can leave: http://jes.milordk.ru/groups/proposals-for-the-future

Tell about problems with the plugin and read the other comments you can: http://jes.milordk.ru/groups/the-bugs


== Translate ==

* <strong>ru_RU</strong> - <em>Jettochkin</em>, <a href="http://milordk.ru" target="_blank">milordk.ru</a>
* <strong>fr_FR</strong> - <em>Laurent Hermann</em>, <a href="http://www.paysterresdelorraine.com/" target="_blank">paysterresdelorraine.com/</a>
* <strong>de_DE</strong> - <em>Manuel MЭller</em>, <a href="http://www.pixelartist.de" target="_blank">pixelartist.de</a>
* <strong>es_ES</strong> - <em>Alex_Mx</em>
* <strong>da_DK</strong> - <em>Chono</em>, <a href="http://www.chono.dk">chono.dk</a>
* <strong>it_IT</strong> - <em>Andrea Bianchi</em>
* <strong>sv_SE</strong> - <em>Thomas Schneider</em>
* <strong>pt_BR</strong> - <em>Adilson, <a href="http://www.xrides.com.br/">XRides - DH e Freeride</a></em>
* <strong>fi</strong> - <em>Juho Pirhonen</em>, http://mediajalostamo.fi

Discuss the issues of localization plugin, you can: http://jes.milordk.ru/groups/translates

== Future ==

* In version 1.6.7 will be reviewed relating to the use of archival events in the widget! Can fully use the sorting mechanism of events
* In version 1.7 will be added to link events to a group
* In version 1.8 will add the ability to use a template for a single event
* In version 3.0 will add the ability to associate events with blogs


List of future functions formed the opinion creator plug-in and test participants, if you have something to offer - <a href="http://jes.milordk.ru/groups/proposals-for-the-future">Poposals for the future</a>

Author plug reserves the right to order the implementation of functions


== Contact ==

For suggestions, bugs, hugs and love can be donated at the following locations.

[Authors page](http://milordk.ru)

[Plugin page](http://milordk.ru/r-lichnoe/opyt/cms/jet-event-system-for-buddypress-sistema-sobytij-dlya-vashej-socialnoj-seti.html)


== Changelog ==

= 1.7.0 =

* Update & fixed Calendar Style
* Update JQuery Scripts
* Fixed Admin Panel

= 1.6.9 =

* Update to Wordpress 3.2 and BuddyPress 1.2.9
* Fixed Datapicker
* Note: Update DB and Templates from JES admin panel!
* Note: Support for plug-in will be carried out before the BP 1.3 and later released a brand new plugin with the ability to update the existing! All information on this site http://jes.milordk.ru

= 1.6.8.1 =

* add Finnish translate (thanks translation team's Mediajalostamo, Juho Pirhonen)
* Special Important Notice: In the near future in light of a new version of the plugin! Conceptually, the new system, more opportunities! All information on jes.milordk.ru

= 1.6.8 =

* add shortcode to post: ex. [jpostevent jevent='n'], where n - event id

= 1.6.7 =

* Added ability to specify the code for the "Social Share"
* Fixed a problem arises when you activate the plugin
* To create the event: If you specify an avatar by default - it completely replaces gravitar

= 1.6.6 = 

* Redesign Admin Panel (full compatibility with Wordpress 3.1)
* Any fix

= 1.6.5.9.3 =

* ! Optional update !
* Check compatibility with Wordpress 3.1
* Check compatibility with BuddyPress 1.2.8
* Update translate pot-file (+ru_RU)
* Update Calendar View scripts

= 1.6.5.9.1.1 =

* Important update for users of 1.6.5.9.1 (Please update DB & Templates from JES adminpanel)


= 1.6.5.9.1 =

* The ability to use the image for an avatar by default. If the image is not specified, it uses the gravatar


= 1.6.5.9 = 

* Fix conflict BP Ajax Chat
* Fix widget

= 1.6.5.8.2 =

* Minor changes: A title single event, adjusting style to style the calendar, save the settings plug-in mechanism, the formation date (as long as nothing significant - all in the future)
> Planned changes and additions - are expected after the release of the WP 3.1 and BP 1.3

= 1.6.5.8.1 =

* Fix: en-US and sr-RS date format for datapicker

= 1.6.5.8 =

* Fixed widgets (php shot code, thanks RoyDean Leighton, Jr. and Brajesh)
* Fixed table property templates ( thanks RoyDean Leighton, Jr.)
* Fized date format (thanks Rich)

= 1.6.5.7 =

* !!! Update technical, optional
* Tested with BP 1.2.7
* Update sv_SE translate

= 1.6.5 =

* Localization of a calendar for the style of "Calendar"

= 1.6.4 =

* Update Calendar Style Engine

= 1.6.3.3 =

* Optimized loading of js-scripts and css-styles
* Fixed id for div
* Updated the style of the "Calendar" for a directory of events

= 1.6.3.1 =

* Fixed: When using the catalog of events to the calendar - the tab "My Events" lists all the events.

Other see: http://jes.milordk.ru/changelog.html


`<?php code(); // goes in backticks ?>`