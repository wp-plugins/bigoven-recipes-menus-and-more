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
			$shortcode_template = bo_recipes_get_setting('shortcode-template');

			include(path_join(dirname(__FILE__), "views/{$shortcode_template}"));
		}

		return ob_get_clean();
	}

	#endregion Shortcode Display

	#region Scripts and Styles

	public static function enqueue_scripts() {
		if((is_singular() || is_page()) && has_shortcode(get_queried_object()->post_content, BO_RECIPES_RECIPE_SHORTCODE)) {
			$shortcode_templates = bo_recipes_get_shortcode_templates();
			$shortcode_template = bo_recipes_get_setting('shortcode-template');

			$template_data = $shortcode_templates[$shortcode_template];

			foreach($template_data['scripts'] as $script) {
				wp_enqueue_script('bo-recipes-shortcode-{$script}', plugins_url("resources/{$script}", __FILE__), array('jquery'), BO_RECIPES_VERSION, true);
			}

			foreach($template_data['stylesheets'] as $stylesheet) {
				wp_enqueue_style("bo-recipes-shortcode-{$stylesheet}", plugins_url("resources/{$stylesheet}", __FILE__), array(), BO_RECIPES_VERSION);
			}
		}
	}

	#endregion Scripts and Styles

	#region Shortcode Templates

	private static $templates = null;

	public static function get_shortcode_templates() {
		if(is_null(self::$templates)) {
			$templates = array();

			$template_matcher = trailingslashit(path_join(dirname(__FILE__), 'views')) . '*.php';

			foreach(glob($template_matcher) as $template) {
				$template_contents = file_get_contents($template);

				$template_matches = array();
				$scripts_matches = array();
				$stylesheets_matches = array();

				if(preg_match('#Template:\s?(.+)$#mi', $template_contents, $template_matches)) {
					$name = trim($template_matches[1]);
				} else {
					continue;
				}

				if(preg_match('#Scripts:\s?(.+)$#mi', $template_contents, $scripts_matches)) {
					$scripts = array_map('trim', explode(',', $scripts_matches[1]));
				} else {
					$scripts = array();
				}

				if(preg_match('#Stylesheets:\s?(.+)$#mi', $template_contents, $stylesheets_matches)) {
					$stylesheets = array_map('trim', explode(',', $stylesheets_matches[1]));
				} else {
					$stylesheets = array();
				}

				$template_basename = basename($template);

				$templates[$template_basename] = compact('name', 'scripts', 'stylesheets');
			}


			self::$templates = $templates;
		}

		return self::$templates;
	}

	#endregion Shortcode Templates
}

require_once('lib/template-tags.php');

BO_Recipes_Components_Display_Shortcode::init();
