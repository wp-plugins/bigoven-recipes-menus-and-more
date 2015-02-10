jQuery(document).ready(function($) {
	$(document).on('click', '.insert-bo-recipe', function(event) {
		event.preventDefault();

		var $this = $(this),
			editor = BO_Recipes_Editor_Buttons.stateName,
			workflow = wp.media.editor.add(editor, { frame: 'post', state: BO_Recipes_Editor_Buttons.stateName, title: BO_Recipes_Editor_Buttons.stateTitle });

		wp.media.editor.open(editor, { title: BO_Recipes_Editor_Buttons.stateTitle });
	});
});
