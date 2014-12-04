=== Recipe SEO and Save Recipe Plugin ===
Contributors: bigoven
Donate link: http://wordpress.bigoven.com/
Tags: recipe,hrecipe,ziplist,grocery,food,recipes
Requires at least: 3.0.1
Tested up to: 4.0
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Make your food blog better with rich snippet markup, SEO improvements, save recipe, ziplist import, make grocery list and more.

== Description ==

* **Improve your Google and Bing search-engine presence**: This plugin takes care of the hidden "rich snippets" markup of recipes for Search Engine Optimization (SEO), marking up your recipes with the preferred format from Google and Schema.org.

* **Import from Ziplist**: The Ziplist plugin for WordPress will soon be discontinued. Transitioning to the BigOven recipe WordPress plugin as your alternative is seamless. The BigOven plugin will import your recipes and change the shortcodes in old posts to the newly updated recipe records in a single click.  As this plugin modifies previous posts, be sure to back up your MySQL database before you click the option "Import from Ziplist" in the Settings area.

* **Give your readers "Save Recipe" and "Add to Grocery List" features**! These optional, convenient features allow your recipes to easily be saved to the reader's "My Recipes" area and grocery list. Cooks can bring your recipes to the grocery store or kitchen counter with the award-winning [BigOven recipe apps](http://www.bigoven.com/mobile). Full credit and links to your original recipe are preserved.  BigOven wants to help highlight your great content, making more 
foodies aware of your writing.  We'll be using this plugin to help our editors scour the web for the the most popular food content.

* **Organize your recipes**: Search your recipes by title for easy reference while posting. Your recipes list will live in the "Recipes" area of the WordPress compose dialogue. This makes combining multiple recipes in a post an easy task.

A few notes about the sections above:

== Installation ==

If you're planning to import from Ziplist (enabled by the "Import from Ziplist" button in the Settings area), we URGE you 
to first make a backup of your recipe data.  This is because the data is imported into a new format (non-destructively) but your 
old recipe posts and references are pointed to the new format.  That is, the old shortcodes used by Ziplist are updated 
to the new format used by this plugin.

1. Make a backup of your MySQL database.  See [Backing up your Database](http://codex.wordpress.org/Backing_Up_Your_Database)
2. Activate the plugin through the Plugins menu in Wordpress
3. Click "Add Recipe" in compose window to add a new recipe.  Enter recipe details there, including featured-image for the recipe. 
4. Write your blog post, including text, media and more.  Where you want to place your recipe, click "Add Recipe".
3. See documentation at [wordpress.bigoven.com](http://wordpress.bigoven.com "Wordpress Recipe SEO plugin")

== Frequently Asked Questions ==

= How do I import from Ziplist? =

First, make a backup copy of your data.  This is because the importer (on the plugin's Settings screen) will update your old posts 
to the new format, and you may want to revert changes back if you choose to do so later.

= Where is the data stored? =

In your local Wordpress database, in the wp_postmeta table.

= Does any of my data get sent to BigOven? =

Your recipes are your own.  When cooks decide to save a recipe, BigOven does store pointer snapshot information of your recipe privately,
complete with a link back to your recipe.  Your recipes are, and will always be, your own.  Be sure to read [BigOven's Pledge to Food Bloggers](http://wordpress.bigoven.com).


== Screenshots ==

1. To add a recipe, simply click "Add Recipe" in the compose window.  If you don't see that, be sure to Activate the Plugin.
2. Rich snippets help your recipes stand out.  This plugin renders your recipes in rich-snippet-compatible format, but it's ultimately 
up to Google whether or not to display recipes from your site in Rich Snippets format.  Google reports that they use a variety of "quality signals"
to determine whether or not to render the rich snippet version of the recipe in search results.
3.  The Recipes option on the left hand bar lets you see all your recipes added, including search for them in an easy interface.
4.  When adding a recipe (partial screen shown), you can enter title, ingredients, preparation, yield, preparation time, nutrition information and more.
5.  The settings view lets you choose whether or not to list recipe instructions in numbered form, whether or not to enable the "Save Recipe" button and more.

== Changelog ==
= 1.0.8 =
* cursor on button hover changes to pointer

= 1.0.7 =
* cleaner button design

= 1.0.6 =
* Remove common hook (was interfering with other common plugins)

= 1.0.5 = 
* Clarifying improvements to text in control panel

= 1.0.4 =
* You can now put an exclamation point at the start of an ingredient line and it'll be treated as a heading.  Example:

!For the Frosting
1/2 cup butter
1 cup sugar
etc.
!For the Cake
2 cups flour
etc.

= 1.0.2 =
* Readme update

= 1.0.1 =
* Readme improvements, screenshots

= 1.0 =
* Major new release supporting Import from Ziplist, recipe SEO rich snippet markup, Save recipe / Save Grocery list features.

== Upgrade Notice ==

= 1.0 =
Initial release.