jQuery(document).ready(function($) {
	$('.bo-recipes-embed').click(function(event) {
		event.preventDefault();

		this.select();
	});

	$('#bo-recipes-import-ziplist-recipes').click(function(event) {
		if(!confirm('Are you sure you want to import from ZipList? This process is non-reversible, so please make sure you have backed up your database.')) {
			event.preventDefault();
		}
	});
});
