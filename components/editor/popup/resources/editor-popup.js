var BORecipesViewModel = function() {
	var _this = this;

	_this.state = ko.observable('search');

	// States

	_this.createStateActive = ko.computed(function() { return 'create' === _this.state(); });
	_this.searchStateActive = ko.computed(function() { return 'search' === _this.state(); });

	_this.setCreateStateActive = function() { _this.state('create'); };
	_this.setSearchStateActive = function() { _this.state('search'); };

	// Search

	_this.hasSearched = ko.observable(false);

	_this.searchActive = ko.observable(false);
	_this.searchPage = ko.observable(1);
	_this.searchPages = ko.observable(1);
	_this.searchQuery = ko.observable('');
	_this.searchResults = ko.observableArray();

	_this.lastPage = _this.searchQuery();
	_this.lastQuery = _this.searchQuery();

	_this.searchResultsEmpty = ko.computed(function() {
		return 0 === _this.searchResults().length && _this.hasSearched();
	});

	function fetch(page, query) {
		_this.lastPage = page;
		_this.lastQuery = query;

		_this.searchActive(true);

		jQuery.post(
			ajaxurl,
			{
				action: 'bo_recipes_search',
				page: page,
				query: query
			},
			function(data, status) {
				_this.searchResults.removeAll();

				_this.hasSearched(true);

				for(var i = 0; i < data.recipes.length; i++) {
					_this.searchResults.push(data.recipes[i]);
				}

				_this.searchPage(data.page);
				_this.searchPages(data.pages);

				_this.searchActive(false);
			},
			'json'
		);
	};

	_this.nextPage = function() { fetch(_this.lastPage + 1, _this.lastQuery); };
	_this.previousPage = function() { fetch(_this.lastPage - 1, _this.lastQuery); };
	_this.search = function() { fetch(1, _this.searchQuery()); };

	_this.hasNextPage = ko.computed(function() { return _this.searchPage() < _this.searchPages(); });
	_this.hasPreviousPage = ko.computed(function() { return _this.searchPage() > 1; });

	_this.insert = function(recipe) {
		window.parent.send_to_editor(recipe.shortcode);
	};

	_this.enterable = function(data, event) {
		var key = event.which ? event.which : event.keyCode;

		if(13 === key) {
			_this.search();
			return false;
		} else {
			return true;
		}
	};

	// Create

	_this.createActive = ko.observable(false);

	_this.createIngredients = ko.observable('');
	_this.createInstructions = ko.observable('');
	_this.createName = ko.observable('');
	_this.createSummary = ko.observable('');

	_this.create = function() {
		_this.createActive(true);

		jQuery.post(
			ajaxurl,
			{
				action: 'bo_recipes_create',
				ingredients: _this.createIngredients(),
				instructions: _this.createInstructions(),
				name: _this.createName(),
				summary: _this.createSummary()
			},
			function(data, status) {
				_this.createActive(false);

				_this.createIngredients('');
				_this.createInstructions('');
				_this.createName('');
				_this.createSummary('');

				_this.setSearchStateActive();

				_this.insert(data);
			},
			'json'
		);
	};
};

jQuery(document).ready(function($) {
	var $wrapper = $('.bo-recipes-process-wrapper');

	if($wrapper.size() > 0) {
		window.BORVM = new BORecipesViewModel();

		ko.applyBindings(window.BORVM, $wrapper.get(0));

		window.BORVM.search();
	}
});