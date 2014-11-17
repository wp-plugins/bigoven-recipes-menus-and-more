<?php

if(!defined('ABSPATH')) { exit; }

// Recipes are stored as a custom post type with the following post_type key
if(!defined('BO_RECIPES_RECIPE_SHORTCODE')) {
	define('BO_RECIPES_RECIPE_SHORTCODE', 'seo_recipe');
}

class BO_Recipes_Components_Display_Shortcode {

	/**
	 * Initialize this component by setting up appropriate actions and filters,
	 * as well as adding shortcodes as necessary and performing any default
	 * tasks that are needed on site load.
	 */
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
		} else {
			// Actions that only affect the frontend interface or operation
			add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
		}

		// Actions that affect both the administrative and frontend interface or operation
		add_action('init', array(__CLASS__, 'add_shortcodes'));
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
		} else {
			// Filters that only affect the frontend interface or operation
		}

		// Filters that affect both the administrative and frontend interface or operation
	}

	#region Shortcode Registration

	public static function add_shortcodes() {
		add_shortcode(BO_RECIPES_RECIPE_SHORTCODE, array(__CLASS__, 'display_recipe'));
	}

	#endregion Shortcode Registration

	#region Shortcode Display

	public static function display_recipe($atts, $content = null) {
		$recipe_id = isset($atts['id']) && is_numeric($atts['id']) ? absint($atts['id']) : null;
		$recipe = $recipe_id ? get_post($recipe_id) : null;

		ob_start();

		if($recipe && BO_RECIPES_RECIPE_TYPE === $recipe->post_type && 'publish' === $recipe->post_status) {
			$recipe_summary = wptexturize(convert_smilies(convert_chars(wpautop($recipe->post_content))));

			include(path_join(dirname(__FILE__), 'views/recipe.php'));
		}

		return ob_get_clean();
	}

	#endregion Shortcode Display

	#region Scripts and Styles

	public static function enqueue_scripts() {
		if((is_singular() || is_page()) && has_shortcode(get_queried_object()->post_content, BO_RECIPES_RECIPE_SHORTCODE)) {
			wp_enqueue_style('bo-recipes-shortcode', plugins_url('resources/bo-recipes-shortcode.css', __FILE__), array(), BO_RECIPES_VERSION);
			wp_enqueue_script('bo-recipes-shortcode', plugins_url('resources/bo-recipes-shortcode.js', __FILE__), array('jquery'), BO_RECIPES_VERSION, true);
		}
	}

	#endregion Scripts and Styles
}

require_once('lib/template-tags.php');

BO_Recipes_Components_Display_Shortcode::init();
