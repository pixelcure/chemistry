WP Extend
===

WP Extend (WPX) is a framework that makes it easier to use WordPress as a CMS. It tries to bridge the gap between WordPress' native ability to make custom post types, taxonomies, options pages, and metaboxes and the Dashboard, by providing a GUI interface for developers to work with outside of templates. It also provides a library of commonly used functions and drop-in templates for theme building geared toward CMS architecture. 

WPX is inspired by CMSes like [ProcessWire](http://processwire.com "ProcessWire") and [Drupal](http://drupal.org "Drupal"), where rolling out GUIs for custom content that the CMS doesn't provide out-of-box is as easy as flipping a few switches in the Dashboard. 

(TLDR: [I don't want to read anything, show me how to use it!](https://github.com/alkah3st/wpx/wiki/))

What can you do with WPX?
-------------------------

A whole bunch of stuff. Here are the core features:

*   Make **custom post types** in the Dashboard (and extend Posts & Pages)
*   Make **custom taxonomies** in the Dashboard (and extend Tags & Categories)
*   Group **custom fields into metaboxes** in the Dashboard
*   Assign **custom fields** to post types, taxonomies, or options pages in the Dashboard
*   Create your own **custom field types** to use in WPX 
*   Make **options pages** in the Dashboard
*   Access a **library of common functions** for CMS theme building
*   Drop in **ready-made templates** geared toward CMS theme building
*   Deploy your install with a [**ready-made Grunt setup**](http://gruntjs.com/ "**ready-made Gruntjs setup")  
* 	Use the **WPX API** in your theme outside of the Dashboard

What custom fields ship with WPX?
----------------------------------------------------

* Checkbox
* File Upload
* Image Upload
* Gallery
* Post
* Term
* Text
* Textarea
* Visual Editor

*Post and Term allow users to pick one or multiple posts or terms to associate with a post.* 

How is WPX different than Advanced Custom Fields (ACF) or Pods?
-------------------------------------------------------------------

[ACF](http://www.advancedcustomfields.com/ "ACF") and [Pods](http://pods.io/ "Pods") are both amazing and definitely more mature than WPX. WPX is somewhere in between the two: it doesn't have all pretty GUI bells and whistles that ACF has or the templating engine that Pods provides. Instead, it has a little bit of both and is, IMHO, a lot easier to get started using. As someone who's built a lot of WordPress installs that behave as a CMS, my goal with WPX was to provide a quick toolset for making a bunch of custom types of content in the Dashboard and assigning a bunch of custom fields to them (in groups where necessary) so I could get right to making my templates without any bellyaching. I wanted to be able to attach a single plugin, copy over a handful of templates, set up my CMS architecture in the Dashboard, and slap down a grunt build without having to reinvent the wheel over and over again. This is what WPX does. 

I've been inspired by an extremely simple CMS called ProcessWire (http://processwire.com/). If you haven't worked with ProcessWire before, you'll be shocked at how simple it is to set up very complex CMS structures in its admin interface. I wanted to bring as much of its amazing workflow back to WordPress. 

### Anyway, here are some other departures: ###

* WPX is free and always will be.
* The end result of using WPX is geared toward the client experience in the Dashboard using WordPress-as-a-CMS, and not bloggers or theme developers (though it can be used to that end). 
* With the exception of the optional utility functions, WPX is meant to be used transparently in your theme. You can access everything that WPX generates with regular WordPress functions.


Installation
------------

This section describes how to install the plugin and get it working.

1. Upload the `/wpx/` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. That's it!

##Frequently Asked Questions

Check out the documentation here: [https://github.com/alkah3st/wpx/wiki/](https://github.com/alkah3st/wpx/wiki/).

##Where is the support forum?

Log all your bugs and feature requests here: [https://github.com/alkah3st/wpx/issues](https://github.com/alkah3st/wpx/issues)