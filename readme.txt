=== Pods Beaver Themer Add-On ===
Contributors: quasel, sc0ttkclark, jimtrue, smarterdigitalltd
Donate link: https://pods.io/friends-of-pods/
Tags: pods, beaver builder, beaver themer,
Requires at least: 4.4
Tested up to: 4.8
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Integration with Beaver Builder Themer (https://www.wpbeaverbuilder.com/beaver-themer/). Provides a UI for mapping Field Connections with Pods.

== Description ==

Easily select Pods fields in Beaver-Themer field connections from a dropdown based on the currently displayed item (CPT).

Requires:

* [Pods](https://wordpress.org/plugins/pods/) 2.4+
* [Beaver Builder](https://www.wpbeaverbuilder.com) 1.10+
* [Beaver Themer](https://www.wpbeaverbuilder.com/beaver-themer/) 1.0+

= Features =

* Support for Field Connections including Images, Text, Relationships, and Website fields.

Please keep in mind this is the first released version. Your feedback and ideas will help improve future releases.

= Bug Reports =

Please use [GitHub](https://github.com/pods-framework/pods-beaver-builder-themer-add-on/issues) for Ideas and  Bug reports. Please note GitHub is _not_ a support forum.

= Planned Features =

* Posts Module: Select Pods as source for the query to display posts based on related fields, or a customized pods query
* Select fields from any User not only based on the current logged in user
* Add more default options

= Further Reading =

For more info, check out the following articles:

* [Video conversation about the Integration](https://youtu.be/zBXQuFWBoRA?list=PLciFKq2WdpdBMdSJ073InW1dS4JHkI-bF)
* [Building a Custom Settings Page using Pods and Beaver Themer](https://www.youtube.com/watch?v=qy1NqBiShpI).
* [Join the #beaver-themer channel on Pods Slack](https://pods.io/chat/) from the Pods Framework Team.


== Frequently Asked Questions ==

= Where do we go for Support on your plugin? =

For the fastest support, you can contact us on our Slack Chat at [https://pods.io/chat/](https://pods.io/chat/) in the #beaver-themer channel. We do not staff our Slack channel 24 hours, but we do check any questions that come through daily and reply to any unanswered questions.

We are growing a community of Beaver Themer users who are starting to hang out on our Pods Slack Chat. Please be respectful and realize that most everyone who is helping you is a volunteer sharing their time to make this product better.

= I've found a Bug or I have a Feature Request =

If you've uncovered a Bug or have a Feature Request, we kindly request you to [Create an issue on GitHub](https://github.com/pods-framework/pods-beaver-builder-themer-add-on/issues/new). Please be very specific about what steps you did to create the issue you're having and include any screenshots or other configuration parameters to help us recreate or isolate the issue.

= How to Test upcoming Versions =

You can use [GitHub Updater](https://github.com/afragen/github-updater). A simple plugin to enable automatic updates to your GitHub, Bitbucket, or GitLab hosted WordPress plugins, themes, and language packs. It also allows for the remote installation of plugins or themes. Select Branch 1.x for the latest Version.

== Screenshots ==

1. Select type of Field Connection.
2. Example dropdown for different field types.
3. Support for all types of Field Connections like Color.
4. Example dropdown for color.
5. Example for Templates or Magic Tags.

== Changelog ==

= 1.1.1 - June 29th, 2017 =
* Added js to improve usability for all kind of posts modules (thx smarterdigitalltd)
* Added support for PowerPack custom grid
* Moved all add action/filter to init
* Fixed single relations for posts module
* Fixed multiple relations to the same CPT are now listed
* Fixed field connections for Archive Pages


= 1.1 - June 14th, 2017 =

* Output related pods in Beaver Builder: Post Module, Post Carousel, Post Slider / PowerPack (1.2.8+): Content Grid / UABB (1.4.5+): Advanced Posts
* Code Cleanup & Improvements
* New filters pods_beaver_loop_settings_find_params and pods_beaver_loop_settings_field_params

= 1.0 - June 9th, 2017 =

* Official release on WordPress.org
