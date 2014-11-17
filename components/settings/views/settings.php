<div class="wrap">
	<h2><?php _e('Recipes - Settings'); ?></h2>

	<?php settings_errors(); ?>

	<p><?php printf(__('This plugin is &copy;2014 BigOven, but all your content is your own, and always will be. Read our <a href="%s" target="_blank">Pledge to Food Bloggers</a>.'), 'http://blog.bigoven.com/index.php/our-pledge-to-food-bloggers/'); ?></p>

	<p><?php printf(__('Do you need help? <a href="%s" target="_blank">Click here</a> to learn more about the BigOven plugin for WordPress.'), 'http://wordpress.bigoven.com'); ?></p>

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
							<?php _e('Let readers save your recipes or add items to mobile grocery list'); ?>
						</label>
						<p class="description"><?php printf(__('Checking this box will allow your readers to save the recipes they’d like to their own recipe collection, and make mobile-friendly grocery lists, with a full link back to your site. <a href="%s">Learn more</a>.'), 'http://wordpress.bigoven.com'); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="<?php echo self::_get_setting_id('feature-recipe-yes'); ?>"><?php _e('Feature My Recipes<br />(coming soon)'); ?></label></th>
					<td>
						<input type="hidden" id="<?php echo self::_get_setting_id('feature-recipe-no'); ?>" name="" value="no" />

						<label>
							<input type="checkbox" <?php checked('yes', $settings['feature-recipe']); ?> id="<?php echo self::_get_setting_id('feature-recipe-yes'); ?>" name="<?php echo self::_get_setting_name('feature-recipe'); ?>" value="yes" />
							<?php _e('Let BigOven feature your recipes in the popular <a href="http://www.bigoven.com/mobile">BigOven mobile apps</a>'); ?>
						</label>
						<p class="description"><?php printf(__('BigOven is always looking for the best food writing on the web to feature.  In 2015 and beyond, we will be displaying select articles of great food writing and recipe ideas - full blog articles from third-party blogs - in a "Best of Web" section within the popular mobile apps (11+ million downloads at this writing).  To qualify for this exposure, your blog must be mobile-friendly (check on iPhone in particular) and you must have the "Save Recipe" option above enabled on your blog.  Checking this option will let BigOven’s editorial team know you\'d welcome no-cost additional exposure. <a href="%s" target="_blank">Learn more</a>.'), $support_url); ?></p>
					</td>
				</tr>
			</tbody>
		</table>

		<p>
			<?php printf(__('<h3>Got ideas?</h3><p>We want BigOven to be the ideal food-blogger\'s companion, helping you make a great site and delighting your readers with tools that make planning and organizing easier. Got an idea or suggested improvement? <a href="%s" target="_blank">Let us know</a>.</p>'), 'http://www.bigoven.com/site/comments'); ?>
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
			<?php printf(__('Before importing from ZipList Recipes, please back up your MySQL database. This makes irreversible changes to post content in order to replace ZipList Recipes shortcodes with BigOven Recipes shortcodes. <a href="%s" target="_blank">Learn more</a>.'), $support_url);; ?>
		</p>

		<p class="submit">
			<?php wp_nonce_field('bo-recipes-import-ziplist-recipes', 'bo-recipes-import-ziplist-recipes-nonce'); ?>
			<input type="submit" class="button button-primary" id="bo-recipes-import-ziplist-recipes" value="<?php _e('Import from ZipList'); ?>" />
		</p>
	</form>

	<?php } ?>
</div>
