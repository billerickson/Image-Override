=== Image Override ===
Contributors: billerickson
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=K9K2YFSJAMLKE
Tags: image, thumbnail, featured, 
Requires at least: 3.0
Tested up to: 3.6
Stable tag: 1.2

Allows you to override WordPress' auto generated thumbnails. 

== Description ==

When you upload an image, WordPress will automatically scale/crop it to many different sizes. If you're not happy with the auto-crops, use this plugin to upload an alternative image. 

If you have images showing up in your theme already, there's nothing you need to do after activating this plugin. All WordPress functions that provide the thumbnail should now automatically work with your override image.

This plugin will add a metabox to every post type and allow you to modify every image size (built-in and custom ones added using add_image_size). You can use two filters to change these (image_override_post_types and image_override_sizes). For examples, see the documentation.

[Documentation](https://github.com/billerickson/Image-Override/wiki) | [Support Forum](https://github.com/billerickson/Image-Override/issues)

== Installation ==

1. Upload the `image-override` folder to your `/wp-content/plugins/` directory

2. Activate the "Image Override" plugin in your WordPress administration interface

3. Create (or edit) a page or a post with a featured image.

4. Down below, in the Image Override metabox, upload an alternative image for one of the sizes, and save the post.

5. Any function you're currently using to display the image (ex: the_post_thumbnail( 'medium' ) ) will now display the new image if the override is applied to that size.

== Changelog ==

= 1.3 =
* Fix issue in metabox script to allow changing of WP Content directory

= 1.2 = 
* Fixed an issue if you use an array for the image size
* Update metabox script to version 0.9.3

= 1.1 = 
* Add localization

= 1.0 = 
* Initial release