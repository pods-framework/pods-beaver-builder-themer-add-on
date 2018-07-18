# pods-beaver-builder-themer-add-on
**Integration of Beaver Themer and Pods**

Details: https://github.com/pods-framework/pods/issues/4004

Short Introduction Video for Pods & Beaver Themer from a User to give you an Idea

[![Watch the video](https://slack-imgs.com/?c=1&o1=wi400.he300&url=https%3A%2F%2Fi.ytimg.com%2Fvi%2FbpkbZTBAz0o%2Fhqdefault.jpg)](https://www.youtube.com/watch?v=bpkbZTBAz0o)

Big Thank you to @Jonathan!

## Requirements

* [Pods](http://pods.io/) 2.4+
* [Beaver Builder](http://pods.io/beaver-builder/) 2.0+
* [Beaver Themer](http://pods.io/beaver-themer/) 1.0+

Check out [pods.io](http://pods.io/) for our User Guide, Forums, and other resources to help you develop with Pods.
Please report bugs or request featured on [GitHub](https://github.com/pods-framework/pods-beaver-builder-themer-add-on/issues)

Generally you can use the shortcode `[pods field='your_field']` anywhere ( every text/url/html field ) it's basically the same as with `[wpbb ...]`
Things like`[pods field='your_field._img.thumbnail']` work fine see http://pods.io/docs/build/using-magic-tags/ for more options!
Or to pull in a Template: `[pods name="your_pod" template="your_template"]`

## Slack

[Join the Pods Slack](https://pods.io/chat) and look for the channel _#beaver-themer_
You will be automatically added to some channels. Please take a look at other channels too.

#### Field connectors for photo and multiple-photo:
- Select dropdown only list's matching Fields (it lists all "Media" fields)
- Choose Size
- Set default image

#### Field connectors for url, html, string:
- select dropdown populated with all existing fields for PODS - field_name ( Custom Post Type )
- currently uses just plain pods()->display();
- images fields are only output as url for magic tag style use advanced ...
- fields with multiple relations (pick_field) still need some ideas / use cases best to go and use Templates for it!


Help / Feedback Welcome - Thank You!!!
- Bernhard Gronau (Quasel)
