<?php

if(!defined('ABSPATH')) { exit; }

class BO_Recipes_Components_Editor_Placeholder {

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
			add_action('wp_ajax_bo_recipes_placeholder', array(__CLASS__, 'get_placeholder'));
		} else {
			// Actions that only affect the frontend interface or operation
		}

		// Actions that affect both the administrative and frontend interface or operation
		add_action('after_setup_theme', array(__CLASS__, 'add_editor_style'));
		add_action('wp_enqueue_editor', array(__CLASS__, 'enqueue_scripts'));
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
		} else {
			// Filters that only affect the frontend interface or operation
		}

		// Filters that affect both the administrative and frontend interface or operation
	}

	#region AJAX

	public static function get_placeholder() {
		$data = stripslashes_deep($_POST);

		$id = isset($data['id']) ? $data['id'] : false;

		if($id && BO_RECIPES_RECIPE_TYPE === get_post_type($data['id'])) {
			$recipe = get_post($data['id']);

			include('views/placeholder.php');
		} else {
			echo __('Invalid recipe...');
		}

		exit;
	}

	#endregion AJAX

	#region TinyMCE Editor

	public static function add_editor_style() {
		add_editor_style(plugins_url('resources/editor-style.css', __FILE__));
	}

	public static function enqueue_scripts($settings) {
		if($settings['tinymce']) {
			wp_enqueue_script('bo-recipes-editor-placeholder', plugins_url('resources/placeholder.js', __FILE__), array('jquery', 'editor'), BO_RECIPES_VERSION, true);
			wp_localize_script('bo-recipes-editor-placeholder', 'BO_Recipes_Editor_Placeholder', array(
				'ajaxAction' => 'bo_recipes_placeholder',
				'shortcode'  => BO_RECIPES_RECIPE_SHORTCODE,
				'textLoadingRecipe' => __('Loading recipe&hellip;'),
			));
		}
	}

	#endregion TinyMCE Editor
}

BO_Recipes_Components_Editor_Placeholder::init();
