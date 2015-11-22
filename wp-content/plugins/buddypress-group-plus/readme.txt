=== BuddyPress Group Plus ===
Contributors: C J Kruger
Donate link:
Tags: buddypress, groups, group map, group field, group additional field, group tab, custom buddypress groups
Requires at least: 3.2.1
Tested up to: 3.2.1
BuddyPress: 1.5.1
Stable tag: 1.2

Adds loads more features to your BuddyPress groups - Add a extra tab to your groups, group maps, info into the group header and more.

== Description ==

Please note that this is a beta release so there will be more to come including some additional functionality.

== Installation ==

= Automatic Installation =

1. From inside your WordPress administration panel, visit 'Plugins -> Add New'
2. Search for `BuddyPress Group Plus` and find this plugin in the results
3. Click 'Install'
4. Once installed, activate via the 'Plugins -> Installed' page
5. Click BP Group Plus link under the BuddyPress admin menu
6. Check the options you want
6. Customize the field you want

= Manual Installation =

1. Upload `buddypress-group-plus` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
5. Click BP Group Plus link under the BuddyPress admin menu
6. Check the options you want
6. Customize the field you want

== Frequently Asked Questions ==

= I got a error message =

`Fatal error: Call to undefined function bp_get_groups_root_slug() in /wp-content/plugins/buddypress-group-plus/admin.php on line 27`

That error will be because you're not using BuddyPress 1.5+ (or, if you are, something's gone horribly wrong. Try deactivating BuddyPress and activating again). ( Thanks to Paul Gibbs and Acouphene )

= I got a error message =

"The plugin does not have a valid header"

Was a mistake with the 1.1 file structure, has been correctet in 1.2

== Screenshots ==

1. All front-end features - Overview
2. Group Header Info back-end
3. Group Plus Tab back-end
4. Customized Group Plus Tab front-end
5. Customized Group Plus Tab back-end
6. Displaying the field by field,area,field(2),area(2)

== Notes ==
License.txt - contains the licensing details for this component.

This plugin is still in a beta state and should not be used on a production site.

== Roadmap ==

= 1.1 =
* Correct the group api edit screen $success msg
* Sort out the admin menu - name field appropriately

== Changelog ==

= 1.2 =

* Corrected the svn trunc
* Cleaned up the admin menu's
* Fixed a bug with donation info saving as null
* Added a little info msg to help people get started and understand what the features actually do

= 1.1 =
* Completely rewritten the hole darn thing
* Fixed bug that killed group description
* Every element listed below is customizable and optional
* Added a group tab ( via group api )
* Added group map within the tab
* Added two text fields within the tab
* Added two text areas within the tab
* Added ability to show field,field,area,area or field,area,field,area
* Reworked the group header meta stuff
* Reworked and added the ability to make any field a link
* Tried to exclude disabled functionality to keep calls down to a minimum
* There's more, just can't remember

= 1.0 =
* initial release

== Upgrade Notice ==

= 1.1 =
Rewritten from the group up, bug fixes and optimization - not compatible with version 1.0.