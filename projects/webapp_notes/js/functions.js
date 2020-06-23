//console.log for old IE
if (!window.console){ 
	window.console = {
		"log" : function( msg ){
			var log = getById("log");
			if(log){
				log.innerHTML += msg +"<br>";
			} else {
				alert(msg);
				//document.writeln(msg);
			}
		}
	}
};


function _log( msg, id){
//console.log(arguments);
//alert(arguments.length);
//		for( var n = 0; n < arguments.length; n++){
//			var _s = "<li> arguments." + n +" = "+ arguments[n] + "</li>";
//alert( _s );
//		}
	var id = id || arguments[1];//IE4 fix
//alert( msg );
//alert( id );

	if(!id){
		var id = "log";
	}
	
	var output = getById(id);
	if( output ){	
		if( msg.length == 0){
			output.innerHTML = "";
		} else {
			output.innerHTML += msg;
		}
		
	} else {
		console.log(msg);
		//alert(msg);
		//document.writeln(msg);
	}
	
	if( typeof _showHiddenLog === "function"){
//console.log(_showHiddenLog);
		_showHiddenLog();
	}
	
}//end _log()

function getById(id){
	
	if( document.querySelector ){
		var obj = document.querySelector("#"+id);
		return obj;
	}
	
	if( document.getElementById ){
		var obj = document.getElementById(id);
		return obj;
	}
	
	if( document.all ){
		var obj = document.all[id];
		return obj;
	}
	
	//if( document.layers ){
		//var obj = document.layers[id];
		//return obj;
	//}
	
	return false;
}//end getById()


function get_attr_to_obj( attr ){
	var item_attr = {};
	for(var item = 0; item < attr.length; item++) {
		item_attr[attr[item].name] = attr[item].value;
	}
	return item_attr;
}//end get_attr_to_obj()



		function _convertXmlToObj(xml){
//console.log( xml.childNodes.item(0).nodeName );			
//console.log( xml.firstChild.nodeName );			
//console.log( xml.documentElement.nodeName );			
			var rootTagName = xml.documentElement.nodeName;
			var xmlDoc = xml.getElementsByTagName( rootTagName);
//console.log( xmlDoc, typeof xmlDoc) ;
//console.log( xmlDoc.item(0), typeof xmlDoc.item(0)) ;
//console.log( xmlDoc.length) ;
//console.log( xmlDoc.item(0).childNodes.length ) ;
//console.log( xmlDoc.item(0).childNodes.item(1).nodeName ) ;
// for(var key in xmlDoc){
// console.log( key +", "+ xmlDoc[key]+ ", " + typeof xmlDoc[key]);
// }
			var xmlObj = {};
			for(var n1 = 0; n1 < xmlDoc.length; n1++){
//console.log( xmlDoc.item(n1) );
//console.log( xmlDoc.item(n1).childNodes ) ;
				var _node = xmlDoc.item(n1);
				var key = _node.nodeName;
				xmlObj[key] = {};
				_parseChildNodes( _node, xmlObj[key] );
			}
			//console.log(xmlObj);				
			return xmlObj;

			function _parseChildNodes( node, nodeObj ){
				var _childNodes = node.childNodes;
// if( !node.children){
// console.log("Internet Explorer (including version 11!) does not support the .children property om XML elements.!!!!");
// }
				if( _childNodes.length > 0){
					if( _childNodes.length === 1 &&
						_childNodes.item(0).nodeType === 3
					){//one child node of type TEXT cannot contain the "children" property!!!!
							//_ch["_itemType"] = ;
					} else {
						nodeObj["childNodes"] = {};
					}
				}
				
				for(var n = 0; n < _childNodes.length; n++){
					var child = _childNodes.item(n);//<=IE9
//console.log( "nodeType: "+ child.nodeType);
//console.log( "nodeName: "+ child.nodeName);

					if (child.nodeType !== 1){// not Node.ELEMENT_NODE
						if (child.nodeType === 3){// #text
							
							var _text = "";
							if ("textContent" in child){
								_text = child.textContent;
								
							} else {
								_text = child.text;
							}
							
							_text = _text.trim();
							if( _text.length > 0){
								if( 
									_text !== "\n" &&
									_text !== "\n\n" &&
									_text !== "\n\n\n"
								){
									nodeObj["text"] = _text;
								}
							}
						}
					} else {
//console.log( "nodeName: "+ child.nodeName);
						var key = child.nodeName;

						//if( !nodeObj["children"] ){
							//nodeObj["children"] = {};
						//}
						if( !nodeObj["childNodes"][key] ){
							nodeObj["childNodes"][key] = [];
						}

						var _ch = {
							//"_length": child.childNodes.length
						};
						var attr = __getAttrToObject(child.attributes);
						if(attr){
							_ch["attributes"] = attr;
/*							
							for( var _key in attr){
//console.log( _key + ": "+ attr[_key]);
								_ch[_key] = attr[_key];
							}//next
*/
						}
						
						//if( child.childNodes.length === 1){
							////continue;
							//_ch["nodeType"] = child.nodeType;
							//_ch["_child_nodes"] = child.childNodes;
							//_ch["_item"] = child.childNodes.item(0);
							//_ch["_itemType"] = child.childNodes.item(0).nodeType;
						//}
						
						nodeObj["childNodes"][key].push(_ch);

						_parseChildNodes(child, _ch );
					}
				}
			}//end _parseChildNodes()

			function __getAttrToObject( attr ){
				if( attr.length === 0){
					return false;
				}
				var item_attr = {};
				for(var item = 0; item < attr.length; item++) {
					item_attr[attr[item].name] = attr[item].value;
				}
				return item_attr;
			}//end _get_attr_to_obj()
		
		}//end _convertXmlToObj()		


//**************************************
//musFM.html?dirname=/music/A&pls=/music/0_playlists/russian.json
//$_GET = parseGetParams(); 
//or 
//$_GET = parseGetParams("?test=1"); 
//console.log( $_GET);
//**************************************
function parseGetParams( parseStr ) { 

	if( !parseStr ){
		var parse_url = window.location.search.substring(1).split("&"); 
	} else {
		p = parseStr.split("?");
//console.log(p);
		parseStr = p["1"];
		var parse_url = parseStr.split("&"); 
	}
//console.log(parse_url);
	
	var $_GET = {}; 
	for(var n = 0; n < parse_url.length; n++) { 
	var getVar = parse_url[n].split("="); 
		//$_GET[ getVar[0] ] = typeof(getVar[1])=="undefined" ? "" : getVar[1]; 
		if( typeof(getVar[1])=="undefined" ){
			$_GET[ getVar[0] ] = "";
		} else {
		 $_GET[ getVar[0] ] = getVar[1];
		}
	}//next
	return $_GET; 
}//end parseGetParams()


/*
	runAjax( {
		"requestMethod" : "GET", 
		"enctype" : "application/x-www-form-urlencoded",
		"url" : _vars["db_url"], 
		"params" : params,// object
		"formData": null, //object formData
		"onProgress" : function(e){	},
		"callback": _postFunc
	});
*/
function runAjax( opt ){
//console.log(arguments);
	
	var p = {
		"requestMethod" : "GET", 
		"responseType" : "", //arraybuffer, blob, document, ms-stream, text
		"enctype" : "application/x-www-form-urlencoded",
		//"enctype" : "multipart/form-data",
		"url" : false, 
		"params": null,//params object
		"formData": null,
		"async" :  true,
		"callback" : null,
		"onProgress" : null,
		"onError" : null,
		"onLoadEnd" : null
	};
	//extend options object
	for(var key in opt ){
		p[key] = opt[key];
	}
//console.log(p);

	var requestMethod = p["requestMethod"]; 
	var url = p["url"]; 
	var async = p["async"]; 
	var callback = p["callback"]; 

	//get values from params and form paramsStr....
	//if( requestMethod === "GET"){
		var num=0;
		if( p["params"] ){
			var paramsStr = "";
			for( var item in p["params"]){
				var value = encodeURIComponent( p["params"][item] );
				if( num > 0){
					paramsStr += "&";
				}
				paramsStr += item + "=" + value;
				num++;
			}//next
			url += "?"+ paramsStr;
			url += "&noCache=" + (new Date().getTime()) + Math.random(); //no cache
		} else {
			url += "?noCache=" + (new Date().getTime()) + Math.random(); //no cache
		}
		
	//}

	
	if( !url || url.length === 0){
		var msg = "Parameters error, needed 'url'";			
console.log( msg );
//_log( "<p  class='text-danger'>" +msg+"</p>");
		return false;
	}
	
	var xhr = _createRequestObject();

	if ( !xhr ) {
console.log("error, ", xhr);
		var msg = "_createRequestObject() error";			
console.log( msg, xhr );
//_log( "<p  class='text-danger'>" +msg+"</p>");
		return false;
	}

	//block overlay and wait window
	var overlay = getById("overlay");
	if( overlay ){
		overlay.className="modal-backdrop in";
		overlay.style.display="block";
	}
	var waitWindow = getById("wait-window");
	if( waitWindow ){
		waitWindow.className="modal-dialog";
		waitWindow.style.display="block";
	}
	
	var timeStart = new Date();

	xhr.open( requestMethod, url, async );
	
	//Check responseType support:
//https://msdn.microsoft.com/ru-ru/library/hh872882(v=vs.85).aspx
//https://developer.mozilla.org/en-US/docs/Web/API/XMLHttpRequest/responseType
//Error, "The response type cannot be changed for synchronous requests made from a document."
	// Opera 12 crash!!!!
	// if( "responseType" in xhr && p["async"] ){
		// xhr.responseType = p["responseType"];
	// }
	
	xhr.onreadystatechange  = function() { 
//console.log("state:", xhr.readyState);
		if( xhr.readyState == 4) {
//console.log( xhr  );
//for(var key in xhr){
//console.log( key +" : "+xhr[key] );
//}

//console.log("end request, state " + xhr.readyState + ", status: " + xhr.status);
//console.log( "xhr.onerror = ", xhr.onerror  );

				//hide block overlay and wait window
				if( overlay ){
					//overlay.className="";
					overlay.style.display="none";
				}
				if( waitWindow ){
					waitWindow.style.display="none";
				}
					
				if( xhr.status === 200){
					
					var timeEnd = new Date();
					var runtime = (timeEnd.getTime() - timeStart.getTime()) / 1000;
var msg = "ajax load url: " + url + ", runtime: " + runtime +" sec";
console.log(msg);
//console.log( "xhr.response: ", xhr.response );

// if( "responseType" in xhr){
// // console.log( "xhr.response: ", xhr.response );
// console.log( "responseType: " + xhr.responseType );
// }

// try{
// console.log( "xhr.responseText: ", xhr.responseText );
// } catch(e){
// console.log( e );
// }

// try{
// console.log( "xhr.responseXML: ", xhr.responseXML );
// } catch(e){
// console.log( e );
// }
					
					if( typeof callback === "function"){
						
						if( xhr.responseXML ){
//var test = xhr.responseXML.selectNodes("//pma_xml_export");	
//var test = xhr.responseXML.getElementsByTagName("database");
//console.log( test.item(0).nodeName);

							//fix IE8
//console.log("Content-Type:: " + xhr.getResponseHeader("Content-Type") );
							var contentType = xhr.getResponseHeader("Content-Type");
							if( contentType === "application/xml" ||
								contentType === "text/xml"){
								var data = xhr.responseXML;
							} else {
								var data = xhr.responseText;
							}

							callback(data, runtime);
						} else {
							var data = xhr.responseText;
							callback(data, runtime);
						}
					}
					//if browser not define callback "onloadend"
					var test = "onloadend" in xhr;
					if( !test ){
						_loadEnd();
					}

				} else {
//console.log(xhr);					
console.log("Ajax load error, url: " + xhr.responseURL);
console.log("status: " + xhr.status);
console.log("statusText:" + xhr.statusText);

// var msg = "";
// msg += "<p>Ajax load error</p>";
// msg += "<p>url: " + xhr.responseURL + "</p>";
// msg += "<p>status: " + xhr.status + "</p>";
// msg += "<p>status text: " + xhr.statusText + "</p>";
// _log("<div class='alert alert-danger'>" + msg + "</div");

					if( typeof  p["onError"] === "function"){
						p["onError"](xhr);
					}
				}
				
		}
	};//end xhr.onreadystatechange
	
	if( "onloadstart" in xhr ){
		xhr.onloadstart = function(e){
//console.log(arguments);
//console.log("event type:" + e.type);
// console.log("time: " + e.timeStamp);
// console.log("total: " + e.total);
// console.log("loaded: " + e.loaded);
		}
	}

	if( "onload" in xhr ){
		xhr.onload = function(e){
//console.log(arguments);
//console.log("event type:" + e.type);
// console.log("time: " + e.timeStamp);
// console.log("total: " + e.total);
// console.log("loaded: " + e.loaded);
		}
	}

	if( "onloadend" in xhr ){
		xhr.onloadend = function(e){
//console.log(arguments);
//console.log("event type:" + e.type);
// console.log("time: " + e.timeStamp);
// console.log("total: " + e.total);
// console.log("loaded: " + e.loaded);
//console.log(xhr.getResponseHeader('X-Powered-By') );
			_loadEnd();
		}//end event callback
	}
	
	function _loadEnd(){
		//hide block overlay and wait window
		// if( overlay ){
			// //overlay.className="";
			// overlay.style.display="none";
		// }
		// if( waitWindow ){
			// waitWindow.style.display="none";
		// }
		
		var all_headers = xhr.getAllResponseHeaders();
//console.log( all_headers );
		if( typeof  p["onLoadEnd"] === "function"){
			p["onLoadEnd"](all_headers);
		}
	}//end _loadEnd()
	
//console.log( "onprogress" in xhr  );
//console.log( xhr.responseType, typeof xhr.responseType );
//console.log( window.ProgressEvent, typeof  window.ProgressEvent);
	if( "onprogress" in xhr ){
		xhr.onprogress = function(e){
//console.log("ajax onprogress");
//console.log(arguments);
			var percentComplete = 0;
			if(e.lengthComputable) {
				percentComplete = Math.ceil(e.loaded / e.total * 100);
			}
console.log( "Loaded " + e.loaded + " bytes of total " + e.total, e.lengthComputable, percentComplete+"%" );

			var loadProgressBar = getById("load-progress-bar");
			if( loadProgressBar ){
				//loadProgress.value = percentComplete;
				loadProgressBar.className = "progress-bar";
				loadProgressBar.style.width = percentComplete+"%";
				loadProgressBar.innerHTML = percentComplete+"%";
			}

			if( typeof  p["onProgress"] === "function"){
				p["onProgress"](e);
			}
		}
		
		//xhr.addEventListener('progress', function(e) {
//console.log("ajax onprogress", e);
		//}, false);
		
//console.log( "xhr.onprogress ", xhr.onprogress);
//console.log( "xhr.onprogress ", xhr.onprogress.handleEvent  );
	}

	if( "onabort" in xhr ){
		xhr.onabort = function(e){
// console.log(arguments);
//console.log("event type:" + e.type);
// console.log("time: " + e.timeStamp);
// console.log("total: " + e.total);
// console.log("loaded: " + e.loaded);
		}
	}

//console.log( "onerror" in xhr  );
//console.log( "xhr.onerror " + xhr.onerror  );
	if( "onerror" in xhr ){
//console.log( "xhr.onerror = ", xhr.onerror  );
		xhr.onerror = function(e){
//console.log(arguments);
console.log("event type:" + e.type);
console.log("time: " + e.timeStamp);
console.log("total: " + e.total);
console.log("loaded: " + e.loaded);
			// if( typeof  p["onError"] === "function"){
				// p["onError"]({
					// "url" : xhr.responseURL,
					// "status" : xhr.status,
					// "statusText" : xhr.statusText
				// });
			// }
		}
	}

//console.log(xhr.upload);
	if( xhr.upload ){
		
		xhr.upload.onerror = function(e){
console.log(arguments);
console.log("event type:" + e.type);
console.log("time: " + e.timeStamp);
console.log("total: " + e.total);
console.log("loaded: " + e.loaded);
		};
	
		xhr.upload.onabort = function(e){
console.log(arguments);
console.log("event type:" + e.type);
console.log("time: " + e.timeStamp);
console.log("total: " + e.total);
console.log("loaded: " + e.loaded);
		};
	
		xhr.upload.onload = function(e){
// console.log(arguments);
// console.log("event type:" + e.type);
// console.log("time: " + e.timeStamp);
// console.log("total: " + e.total);
// console.log("loaded: " + e.loaded);
		};
	
		xhr.upload.onloadstart = function(e){
// console.log(arguments);
// console.log("event type:" + e.type);
// console.log("time: " + e.timeStamp);
// console.log("total: " + e.total);
// console.log("loaded: " + e.loaded);
		};
		
		xhr.upload.onloadend = function(e){
// console.log(arguments);
// console.log("event type:" + e.type);
// console.log("time: " + e.timeStamp);
// console.log("total: " + e.total);
// console.log("loaded: " + e.loaded);
		};
		
		//Listen to the upload progress.
		xhr.upload.onprogress = function(e) {
			if (e.lengthComputable) {
				var percent = (e.loaded / e.total) * 100;
console.log( "Loaded " + e.loaded + " bytes of total " + e.total, e.lengthComputable, percent+"%" );
			}
		};
		
		xhr.upload.ontimeout = function(e){
console.log(arguments);
console.log("event type:" + e.type);
console.log("time: " + e.timeStamp);
console.log("total: " + e.total);
console.log("loaded: " + e.loaded);
		};

	}
	
	//send query	
	if( requestMethod !== "POST"){
		xhr.send();
	} else {
		
		//http://learn.javascript.ru/xhr-forms
		//form POST body
		if( p["enctype"] === "application/x-www-form-urlencoded"){
			
			var test = "setRequestHeader" in xhr;
	//console.log( "setRequestHeader: " + test );
			if (test) {
				xhr.setRequestHeader("Content-Type", p["enctype"]);
			}
			
			var body = "";
			var n = 0;
			for(var key in p["formData"]){
				var value = p["formData"][key];
				if( n > 0){
					body += "&";
				}
				body += key + "=" + encodeURIComponent(value);
				n++;
			}//next
//console.log( body );
			xhr.send( body );
		} else {
			xhr.send( p["formData"] );
		}
	}

	function _createRequestObject() {
		var request = false;
		
		if (window.XMLHttpRequest) { // Mozilla, Safari, Opera ...
//console.log("try use XMLHttpRequest");		
			request = new XMLHttpRequest();
		} 

		if(!request){ // IE
//console.log("try use Microsoft.XMLHTTP");		
			request = new ActiveXObject("Microsoft.XMLHTTP");
		}

		if(!request){
//console.log("try use Msxml2.XMLHTTP");		
			request=new ActiveXObject('Msxml2.XMLHTTP');
		}

		return request;
	}//end _createRequestObject()
	
}//end runAjax()

		function _alert( message, level ){
			switch (level) {
				case "info":
					message = "<p class='alert alert-info'>" + message + "</p>";
					_log(message);
				break;
				
				case "warning":
					message = "<p class='alert alert-warning'>" + message + "</p>";
					_log(message);
				break;
				
				case "danger":
				case "error":
					message = "<p class='alert alert-danger'>" + message + "</p>";
					_log(message);
				break;
				
				case "success":
					message = "<p class='alert alert-success'>" + message + "</p>";
					_log(message);
				break;
				
				default:
					_log(message);
				break;
			}//end switch
			
		}//end _alert()		

//================================
//Usage :  var today = func.timeStampToDateStr({
//timestamp : ....timestamp string....,
//format : "yyyy-mm-dd hh:min" 
//});

//https://stackoverflow.com/questions/847185/convert-a-unix-timestamp-to-time-in-javascript
// Create a new JavaScript Date object based on the timestamp
// multiplied by 1000 so that the argument is in milliseconds, not seconds.
//var date = new Date(unix_timestamp * 1000)

//================================
		function _timeStampToDateStr( opt ){
			var p = {
				"timestamp" : null,
				"format" : ""
			};
			for(var key in opt ){
				p[key] = opt[key];
			}
//console.log( p );
			
			//date.setTime( timestamp);
			if( !p.timestamp || p.timestamp.length === 0){
				var d = new Date();
			} else {
				// multiplied by 1000 so that the argument is in milliseconds, not seconds.
				timestamp = p.timestamp * 1000;
				var d = new Date( timestamp );
			}
//console.log( d );
			
			var sYear = d.getFullYear();

			var sMonth = d.getMonth() + 1;
	//console.log( sMonth, typeof sMonth );
			if( sMonth < 10){
				sMonth = "0" + sMonth;
			}
			
			var sDate = d.getDate();
			if( sDate < 10){
				sDate = "0" + sDate;
			}
			
			var sHours = d.getHours();
			if( sHours < 10){
				sHours = "0" + sHours;
			}
			
			var sMinutes = d.getMinutes();
			if( sMinutes < 10){
				sMinutes = "0" + sMinutes;
			}
			
			var sSec = d.getSeconds();
			if( sSec < 10){
				sSec = "0" + sSec;
			}
			
			var dateStr =  sDate + "-" + sMonth + "-" + sYear + " " + sHours + ":" + sMinutes + ":" + sSec;
			
			switch( p.format ){
				
				case "yyyy-mm-dd":
					dateStr = sYear + "-" + sMonth + "-" + sDate;
				break;
				
				case "yyyy-mm-dd hh:min":
					dateStr = sYear + "-" + sMonth + "-" + sDate + " " + sHours + ":" + sMinutes;
				break;
				
				case "yyyy-mm-dd hh:min:sec":
					dateStr = sYear + "-" + sMonth + "-" + sDate + " " + sHours + ":" + sMinutes + ":" + sSec;
				break;
				
			}//end switch
			
			return dateStr;
		}//end _timeStampToDateStr()


if( typeof window.jQuery === "function"){
	$(document).ready(function(){
		
		var msg = "jQuery version: " + jQuery.fn.jquery;
		_alert(msg, "info");
		
		$(".scroll-to").addClass("nolink").on("click", function(){
			if($(this).attr("href")){
				var elem = $(this).attr("href");
			} else {
				var elem = $(this).attr("data-target");
			}
			
			$('html,body').animate({
				scrollTop: 0
				}, 500);
			return false;
		});
		
	});//end ready	
}
