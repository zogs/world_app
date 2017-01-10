$(document).ready(function() {

	/*===========================================================
		// Autocomplete City Name
	============================================================*/

	//create the data loader with the URL
	var cities_loader = new Bloodhound({
	    datumTokenizer: function (datum) {
	        return Bloodhound.tokenizers.whitespace(datum.token);
	    },
	    queryTokenizer: Bloodhound.tokenizers.whitespace,
	    remote: {
	        url: getCitiesAutocompleteURL(),
	        
	    }
	});
	// Initialize data loader (Bloodhound suggestion engine)
	cities_loader.initialize();

	// Get autocomplete URL
	function getCitiesAutocompleteURL() {

		return $(".city-autocomplete:first").attr('data-autocomplete-url')+'/'+$("#city_to_location_cc1").val()+'/%QUERY';
	}

	//find all input who need the autocompletion's feature
	$('.city-autocomplete').each(function(index){

		var input = $(this);
		var template_empty = input.attr('data-template-empty');
		var template_header = input.attr('data-template-header');
		var template_footer = input.attr('data-template-footer');
		var template_suggestion = Handlebars.compile( '<p class="tt-name">{{name}}</p><p class="tt-sub">{{state}}</p><p class="tt-id">{{id}}</p>');
		var trigger_length = input.attr('data-trigger-length');
		
		input.typeahead(
			{		
			minLength: trigger_length
			},
			{
			name: 'city'+index,
			displayKey: 'name',
			source: cities_loader.ttAdapter(),
			templates: {
				empty : template_empty,
				footer : template_footer,
				header : template_header,
				suggestion: template_suggestion
				},
			}
		)
		.on('typeahead:selected',function(evt,suggestion){
			$('.city-id-autocompleted').get(index).value = suggestion.id;

			if($(this).hasClass('auto-adjust-width')){
				console.log(suggestion.name.length);
				$(this).attr('size',suggestion.name.length+2);
			}
		})
		;
	})


	/*===========================================================
	// Display States Select Box with Select2
	============================================================*/
	if($('.geo-select').length != 0){

		$('.geo-select-country').select2({ formatResult: countryFlag, formatSelection: countryFlag});				
		$('.geo-select:not(.geo-select-country,.hide)').select2();
	}

	
	/*===========================================================
	// Ajax States Select Box on Change
	============================================================*/	
	$('.geo-select-ajax').change(function(){

		var select = $(this);
		var url = select.attr('data-ajax-url');
		var level = select.attr('data-geo-level');
		var value = select.val();
		select.addClass('geo-loading');

		$.ajax({
			type: 'GET',
			url: url,
			data: { level: level, value: value },
			dataType: 'json',
			success: function(data){

				select.removeClass('geo-loading');
				$('select.geo-select-'+data.level).first().empty().append(data.options).removeClass('hide').select2().show();
				$('#control-'+data.level).removeClass('hide').nextAll('.control-group').addClass('hide');				
							
			}
		})
	});
});




function countryFlag(state) {

	return "<img class='flag flag-"+state.id.toLowerCase()+"' /> "+state.text;
}
