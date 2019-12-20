//var support = false;

//console.log("module sharedFunc:", typeof sharedFunc, sharedFunc);
var func = sharedFunc();
//console.log("func:", func);

_vars = {
	"logMsg": "",
}//end _vars object
console.log(_vars);


window.onload = function(){
	_vars.logMsg = navigator.userAgent;
	func.logAlert( _vars.logMsg, "info" );
//----------------------
	_vars["logPanel"] = func.getById("log");
	_vars["btnClearLog"] = func.getById("btn-clear-log");

//---------------------
	_vars["btnClearLog"].onclick = function( event ){
//console.log("click...", e);			
		event = event || window.event;
		var target = event.target || event.srcElement;
		if (event.preventDefault) { 
			event.preventDefault();
		} else {
			event.returnValue = false;				
		}
		_vars["logPanel"].innerHTML = "";
	};//end event
//---------------------

	//Start webApp
	if( typeof webApp === "object"){
		_runApp();
	}
	function _runApp(){
		webApp.init( function(){
console.log("end webApp initialize....");
		});
	}//end _runApp()

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
	
});//end ready