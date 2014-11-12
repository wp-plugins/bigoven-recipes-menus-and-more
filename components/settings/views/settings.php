<div class="wrap">
	<h2><?php _e('Recipes - Settings'); ?></h2>

	<?php settings_errors(); ?>

	<p><?php printf(__('This plugin is &copy; 2014 BigOven, but your content is your own, and always will be. Read Our <a href="%s" target="_blank">Pledge to Food Bloggers</a>.'), 'http://blog.bigoven.com/index.php/our-pledge-to-food-bloggers/'); ?></p>

	<p><?php printf(__('Do you need help? <a href="%s" target="_blank">Click here</a> to learn more about the BigOven plugin for WordPress.'), 'http://support.bigoven.com/hc/en-us/articles/202384125'); ?></p>

	<form action="options.php" method="post">
		<h3><?php _e('Display'); ?></h3>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="<?php echo self::_get_setting_id('number-instructions-yes'); ?>"><?php _e('Instructions'); ?></label></th>
					<td>
						<input type="hidden" id="<?php echo self::_get_setting_id('number-instructions-no'); ?>" name="<?php echo self::_get_setting_name('number-instructions'); ?>" value="no" />

						<label>
							<input type="checkbox" <?php checked('yes', $settings['number-instructions']); ?> id="<?php echo self::_get_setting_id('number-instructions-yes'); ?>" name="<?php echo self::_get_setting_name('number-instructions'); ?>" value="yes" />
							<?php _e('Each recipe instruction on my site should be numbered'); ?>
						</label>
						<p class="description"><?php _e('Checking this box will display your recipe instructions in a sequentially numbered list'); ?></p>
					</td>
				</tr>
			</tbody>
		</table>

		<h3><?php _e('Enhance My Recipes'); ?></h3>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="<?php echo self::_get_setting_id('save-recipe-yes'); ?>"><?php _e('Save Recipe &amp;<br />Make Grocery List'); ?></label></th>
					<td>
						<input type="hidden" id="<?php echo self::_get_setting_id('save-recipe-no'); ?>" name="" value="no" />

						<label>
							<input type="checkbox" <?php checked('yes', $settings['save-recipe']); ?> id="<?php echo self::_get_setting_id('save-recipe-yes'); ?>" name="<?php echo self::_get_setting_name('save-recipe'); ?>" value="yes" />
							<?php _e('Let readers save your recipes'); ?>
						</label>
						<p class="description"><?php printf(__('Checking this box will allow your readers to save the recipes they’d like to their own recipe collection, and make mobile-friendly grocery lists. <a href="%s">Learn more</a>.'), $support_url); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="<?php echo self::_get_setting_id('feature-recipe-yes'); ?>"><?php _e('Feature My Recipes<br />(coming soon)'); ?></label></th>
					<td>
						<input type="hidden" id="<?php echo self::_get_setting_id('feature-recipe-no'); ?>" name="" value="no" />

						<label>
							<input type="checkbox" <?php checked('yes', $settings['feature-recipe']); ?> id="<?php echo self::_get_setting_id('feature-recipe-yes'); ?>" name="<?php echo self::_get_setting_name('feature-recipe'); ?>" value="yes" />
							<?php _e('Let BigOven feature your recipes'); ?>
						</label>
						<p class="description"><?php printf(__('Let BigOven’s editorial team know that a snippet of my recipes can be featured, including full link back to original source for recipe instructions. <a href="%s" target="_blank">Learn more</a>.'), $support_url); ?></p>
					</td>
				</tr>
			</tbody>
		</table>

		<p>
			<?php printf(__('Got an idea or suggested improvement? <a href="%s" target="_blank">Let us know</a>.'), 'http://www.bigoven.com/site/comments'); ?>
		</p>

		<p class="submit">
			<input type="hidden" name="<?php echo self::_get_setting_name('imported-ziplist-recipes'); ?>" value="<?php echo 'yes' === $settings['imported-ziplist-recipes'] ? 'yes' : 'no'; ?>" />

			<?php settings_fields(BO_RECIPES_SETTINGS_PAGE); ?>
			<input type="submit" class="button button-primary" value="<?php _e('Save Changes'); ?>" />
		</p>
	</form>

	<?php if('no' === $settings['imported-ziplist-recipes']) { ?>

	<form action="<?php echo esc_attr(esc_url($settings_url)); ?>" method="post">
		<h3><?php _e('Import from ZipList'); ?></h3>

		<p>
			<strong><?php _e('Important:'); ?></strong>
			<?php printf(__('Before importing from ZipList Recipes, please back up your MySQL database. This process causes non-reversible changes to post content in order to replace ZipList Recipes shortcodes with BigOven Recipes shortcodes. <a href="%s" target="_blank">Learn more</a>.'), $support_url);; ?>
		</p>

		<p class="submit">
			<?php wp_nonce_field('bo-recipes-import-ziplist-recipes', 'bo-recipes-import-ziplist-recipes-nonce'); ?>
			<input type="submit" class="button button-primary" id="bo-recipes-import-ziplist-recipes" value="<?php _e('Import from ZipList'); ?>" />
		</p>
	</form>

	<?php } ?>
</div>
