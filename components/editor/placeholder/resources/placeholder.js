jQuery(document).ready(function($) {
	wp.mce.views.register(BO_Recipes_Editor_Placeholder.shortcode, {
		View: {
			initialize: function(options) {
				this.shortcode     = options.shortcode;
				this.shortcodeHtml = false;
				this.shortcodeId   = this.shortcode.attrs.named.id ? this.shortcode.attrs.named.id : false;
			},

			getHtml: function() {
				var shortcodeView = this;

				if(this.shortcodeHtml) {
					return shortcodeView.shortcodeHtml;
				} else if(this.shortcodeId) {
					$.post(
						ajaxurl,
						{
							action: BO_Recipes_Editor_Placeholder.ajaxAction,
							id:     this.shortcodeId
						},
						function(data, status) {
							shortcodeView.shortcodeHtml = data;
							shortcodeView.render(true);
						},
						'html'
					);

					return '<div class="bo-recipes-placeholder" data-id="' + this.shortcodeId + '"><div class="bo-recipes-placeholder-title">' + BO_Recipes_Editor_Placeholder.textLoadingRecipe + '</div></div>';
				} else {
					return false;
				}
			}
		},

		edit: function(node) {
			var id = $(node).find('.bo-recipes-placeholder').data('id');

			BO_Recipes_Editor_Buttons.popup(id);
		}
	});
});
