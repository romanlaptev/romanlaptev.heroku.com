//var init_w = 300;
//var init_h = 250;
//var support = false;
var logMsg;

//console.log("module sharedFunc:", typeof sharedFunc, sharedFunc);
var func = sharedFunc();
//console.log("func:", func);

window.onload = function(){
	logMsg = navigator.userAgent;
	func.logAlert( logMsg, "info" );
//----------------------
	defineEvents();
};//end window.load

$(document).ready( function(){
console.log("jQuery version:" + $.fn.jquery);
console.log("jQuery UI version:" + $.ui.version);

	/* PIE */
	if (window.PIE) {
		$('.search, .search form, .menu-left, .menu-left header, .bank-pfoto span, .txt-sl, .main-slide, .backet, .center-bck, .btn, .menu-info, .menu-info header, .slide-main, .slide-main header, .direction article, .catalog article, .catalog article header, .option, .price-block, .price-block header, .catalog article footer, .head-catalog ul li a').each(function() {
		PIE.attach(this);
		});
	}

//----------------------------------
	$(".scroll-to").on("click", function(){
		$('body').scrollTo( $(this).attr("href"), 800, {offset: 0});
		return false;
	});

//-------------------------- 
	$(".fancybox").fancybox({
		helpers : {
			overlay : {
				locked : false
			}
		}
	});

//-------------------------- 
	$(".select-tov-modal .texture-close-btn").live("click", function(){ 
			$(".select-tov-modal").hide(); 
	});

//-------------------------
/*
	$(".filters a").click(
		function()
		{
			$(".filters a").removeClass("active");
			$(this).addClass("active");
			
			if ( $(this).attr("id") == "reset-link" )
			{
				$(this).removeClass("active");
			}
			
		}
	);
*/

	//$("#material").selectBox();
	  $("#material").change(function() {
		if ($("#flizelin").is(":selected")) {
		  $(".select_tov").show();
		} else {
		  $(".select_tov").hide();
		}
	  }).trigger('change');

		$('#material').change(function(){
				calc_order_price();
		});
		
		$('#amount, #amount-ui').keyup(function(e)	{
				calc_order_price();
		});

});//end ready


function calc_order_price()
{
	var roll_width = $('#material option:selected').val();
	switch(roll_width)
	{
		case "0.9":// печать на флизелиновых обоях 
			var print_price = $("input[name=print_price1]").val();
		break;
		
		case "1":// печать на на полиэстере 1м 
			var print_price = $("input[name=print_price2]").val();
		break;
		
		case "1.5":// печать на на полиэстере 1,5м
			var print_price = $("input[name=print_price3]").val();
		break;
	}
	var h_cm = parseInt ( $("input[name=h_cm]").val() );
	var num_roll = $("input[name=num_roll]").val();
	var product_price = print_price * ( h_cm / 100) * num_roll;
	$("#pr-price").val( product_price.toFixed(2) );
}


function defineEvents(){

	var btn_clear_log = func.getById("btn-clear-log");
	var logPanel = func.getById("log");
	btn_clear_log.onclick = function( event ){
//console.log("click...", e);			
		event = event || window.event;
		var target = event.target || event.srcElement;
		if (event.preventDefault) { 
			event.preventDefault();
		} else {
			event.returnValue = false;				
		}
		logPanel.innerHTML = "";
	};//end event

}//end defineEvents()

