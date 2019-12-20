/*
var Calc =  Calc || function(){
		
		// private variables and functions
		var _vars = {
		};

		
		// public interfaces
		return{
			vars: _vars
		};
		
};//end Calc
*/
var webApp = {
	
	"vars" : {
		"logMsg" : "",
		"errorMsg" : {
			"noTexture" : "Выберите текстуру материала."
		},
		"imageResultFilename" : "test.png",
		"init_w" : 300,
		"init_h" : 250,
		"cropUrl" : "php/crop_image/crop_filters.php"//,
	},//end vars
	
	"init" : function( postFunc ){
console.log("init webapp!");

		this.vars["domObj"] = {
			//form input
			"inpPrice" : func.getById("inp-price"),
			"inpTexture" : func.getById("inp-texture"),
			
			"btnSaveImage" : func.getById("btn-save-image"),
			"copyBlock" : func.getById("copy-block"),
			//"destBlock" : func.getById("dest"),
			"formOrder" : func.getById("order-form"),
			"materialFlizelin" : func.getById("flizelin")
		};
		
		var _dom = this.vars["domObj"];
		for( var key in _dom){
			if( !_dom[key] ){
//console.log(key, _dom[key] );
				webApp.vars.logMsg = "warning, undefined DOM element <b>" +key+"</b>  !";
func.logAlert( webApp.vars.logMsg, "warning" );
			}
		}//next

		defineEvents();
		
	}//end init()
	
};//end webApp()
console.log(webApp);

function defineEvents(){

//-------------------------- 
	func.addEvent( webApp.vars["domObj"]["btnSaveImage"], "click", function(e){
console.log( e );
		e.preventDefault();
		html2canvas( 
			webApp.vars["domObj"]["copyBlock"], {
				//scale: 2,
				//backgroundColor: "#ffff00",
				//imageTimeout: 0
			}).then( function(canvas) {
//console.log(canvas);
			//webApp.vars["domObj"]["destBlock"].appendChild(canvas);
			canvas.toBlob(function(blob) {
console.log(blob);
				saveAs(blob, webApp.vars["imageResultFilename"] );
			});
			
			// var dataURL = canvas.toDataURL();//PNG
// console.log(dataURL)	;
			// webApp.vars["domObj"]["btnSaveImage"].href = dataURL;
		});

	});//end event
	
	
//-------------------------- Send form
	webApp.vars["domObj"]["formOrder"].onsubmit = function(e){
//console.log(e.type, e);
//console.log(this);
		var res = checkForm({
			"form" : this//,
			//"action" : "save_message"
		});
		return res;
	};//end event()

//=======================================

/*
    	$("#addtocart-decor-design").on("submit", 
		function()
		{
			var error=false;
			if ($("#no-material").is(":selected")) 
			{
alert( "Пожалуйста, выберите из выпадающего списка материал товара.");	
				error=true;
			}

			if ($("#flizelin").is(":selected")) 
			{
				if ($("input[name=texture]").val()=="") 
				{
alert( "Пожалуйста, выберите текстуру материала.");	
					error=true;
				}
			}

			if (error)
			{
				return false;
			}
			else
			{
//----------------------- сохранить в поля заказа значения ширины и высоты стены
				w = $("#amount").val();
				h = $("#amount-ui").val();
				$('input[name=w_cm]').val( w );
				$('input[name=h_cm]').val( h );
				$("#decor_dynamic_size_field").val( w + "x" + h );
				
//return false;
			}
		}
	);
*/

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

		$("#material").change(function(){
			
			if ($("#flizelin").is(":selected")) {
				$(".select_tov").show();
			} else {
				$(".select_tov").hide();
			}
			
			calc_order_price();
		});
		
		$('#amount, #amount-ui').keyup(function(e)	{
				calc_order_price();
		});
	
}//end defineEvents()


function calc_order_price(){

	var roll_width = $('#material option:selected').val();
	switch(roll_width){
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

	webApp.vars["domObj"]["inpPrice"].value = product_price.toFixed(2);
}//end calc_order_price()


function checkForm(opt){
	var p = {
		"form" : null,
		"action" : ""
	};
	
	//extend options object
	for(var key in opt ){
		p[key] = opt[key];
	}
//console.log( p );

	var error=false;
	
//console.log( webApp.vars["domObj"]["materialFlizelin"].selected );
	if ( webApp.vars["domObj"]["materialFlizelin"].selected ){
		if ( webApp.vars["domObj"]["inpTexture"].value ==="" ){
func.logAlert( webApp.vars.errorMsg["noTexture"], "error" );
			error=true;
		}
	}

	if (error){
		return false;
	}

	//получить ссылку для кадрированого фрагмента изображения
	//if ( parseInt($('#w').val()) ) {
		//get_crop_url();
	//}
	
	//------------------------
	// var texture_name = $("input[name=texture]").val();
	// texture_name = texture_name;
	// $("input[name=texture]").val(texture_name);
	
	var formValues = {
		"form" : p["form"],
		//"action" : p["action"],
		"requestMethod" : p["form"].getAttribute("method"),
		"enctype" : p["form"].getAttribute("enctype") ? p["form"].getAttribute("enctype") : null
	};
	
	//send form using Ajax request
	//sendForm( formValues );
	//return false;
}//end checkForm()