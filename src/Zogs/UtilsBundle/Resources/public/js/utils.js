$(document).ready(function() {

	/*==================================
    	Select2
    ==================================*/
    if($('.select2').length != 0) {
    	$('.select2').select2();    	
    }


    /*==================================
    	Datetime Picker
    ==================================*/
    $('input.with_date_picker').datetimepicker({
    	lang:'fr',    	
		 timepicker:false,
		 format:'d/m/Y',
		 minDate: 0,
		 mask:true,
		 lazyInit: true,
		 dayOfWeekStart: 1,
	});

    $('input.with_date_picker_futur_only').datetimepicker({
    	lang:'fr',    	
		 timepicker:false,
		 format:'d/m/Y',
		 minDate: 0,
		 mask:true,
		 lazyInit: true,
		 dayOfWeekStart: 1,
	});	

    $('input.with_date_picker_past_only').datetimepicker({
    	lang:'fr',    	
		 timepicker:false,
		 format:'d/m/Y',
		 maxDate: 0,
		 mask:true,
		 lazyInit: true,
		 dayOfWeekStart: 1,
	});	

	$('input.with_time_picker').datetimepicker({
		lang:'fr',
		datepicker: false,
		format: 'H:i',
		step:30,
		defaultTime:'13:00'
	});

	/*==================================
		Tooltip bootstrap
	==================================*/	
	$('.tooltiptop').tooltip( { delay: { show: 200, hide: 100 }} );
	$('.tooltipbottom').tooltip( { placement : 'bottom', delay: { show: 200, hide: 100 }} );


	/*==================================
	MOBILE MENU
	===================================*/
	if($("#menu-mobile").length!=0 && $("#menu-mobile").css('display')!='none'){

		$("#mmenu").mmenu({});	
	}

});