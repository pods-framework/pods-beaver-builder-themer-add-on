# pods-beaver-themer
Integration of Beaver Themer and Pods 

Details:
https://github.com/pods-framework/pods/issues/4004

## Requirements

* [Pods](http://pods.io/) 2.4+
* [Beaver Themer](https://www.wpbeaverbuilder.com) 1.0

Generally you can use the shortcode `[pods field='your_field']` anywhere ( every text/url/html field ) it's basically the same as with `[wpbb ...]`
Things like`[pods field='your_field._img.thumbnail']` work fine see http://pods.io/docs/build/using-magic-tags/ for more options!

Currently The Field Connections are intended to be used on "single templates" or in "post modules"  
The Template Field Connection for Archive Pages is planned, meahnwhile you cna just use a pods shortcode `[pods name="your_pod" template="your_template"]`

## Beta - use in production at your own risk!

#### Field connectors for photo and multiple-photo working:
- select dropdown only list's matching Fields 
- don't set Restrict File Types to custom in Pods Admin (known issue, working on better filter)
- currently no option to choose image size i hope BB will use the same picker as for Media Gallery Fields (ID is in the array)


#### Field connectors for url, html, string:
- select dropdown populated with all existing fields for PODS - field_name ( Custom Post Type ) if two pods use the same field-name both are shown in the same line as an Template can be used for multiple "locations"
- currently uses just plain pods()->display();
- images fields are only output as url 
- fields with multiple relations (pick_field) still need some ideas / use cases best to go and use Templates for it!


## Planned:
- add settings/code for output_type ( e.g IMAGES as url, image-link, ...)
- add settings/code for image_size (depending on BB )
- Add Support to Select Templates http://pods.io/tutorials/how-tos-sceencasts-series/using-pods-templates-part-1/  use `[pods template='your_template']` or similar shortcode



Help / Feedback Welcome - Thank You!!!

Bernhard aka Quasel
