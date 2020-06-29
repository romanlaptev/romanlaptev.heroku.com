var webApp = null;

window.onload = function(){
	webApp = _app();
console.log( webApp );	

	webApp.init();
}//end load


var _app = function ( opt ){
//console.log(arguments);	
	var _vars = {
		//"init_url" : "?q=load-xml",
		//"init_url" : "?q=send_rpc_request&action=get_content_list",
		//"init_url" : "?q=send_rpc_request&action=get_booklist",
		"init_url" : "?q=book-list",

		"localRequestUrl" : "data/export.xml",
		//"localRequestUrl" : "/mnt/d2/temp/export_mydb_allnodes.xml",
		
		"serverUrl" : "/projects/romanlaptev.heroku/projects/db_admin/",
//		"serverUrl" : "https://romanlaptev-cors.herokuapp.com/\
//https://romanlaptev.herokuapp.com/projects/db_admin/",
		//"serverUrl" : "https://romanlaptev.herokuapp.com/projects/db_admin/",
		
		"templates" : {
			"tpl-node" : _getTpl("tpl-node"),
			"tpl-page-list" : _getTpl("tpl-page-list"),
			"tpl-page-list-item" : _getTpl("tpl-page-list-item"),
			"tpl-booklist" : "<h3>Book list</h3><ul>{{list}}</ul>"
		},
		"appContainer" : func.getById("App"),
		"contentList" : func.getById("content-list"),
		"panelService" : func.getById("collapse-panel-service"),

		"log" :  func.getById("log"),
		"btnToggle" : func.getById("btn-toggle-log"),
		"$num_notes" : func.getById("num-notes"),
		
		"breadcrumbs": {},
		
		"waitWindow" : func.getById("win1"),
		"waitWindowLoad" : func.getById("win-load"),
		
		"loadProgress" : func.getById("load-progress")	,
		"loadProgressBar" : func.getById("load-progress-bar"),
		
		"numTotalLoad" : func.getById("num-total-load"),
		"percentComplete" : func.getById("percent-complete"),
		
		//"totalBytes" : func.getById("total"),
		//"totalMBytes" : func.getById("total-mb"),
		//"loaded" : func.getById("loaded"),
		//"loadInfo" : func.getById("load-info")

		"nodeModal" : $("#node-modal"),
		"addNodeTitle": "add new node",
		"editNodeTitle": "edit node, id: "//,
	};


	var _init = function(){
//console.log("init _notes");

		defineEvents();
		
					
		var parseUrl = window.location.search; 
		if( parseUrl.length > 0 ){
			_vars["GET"] = func.parseGetParams(); 
			_urlManager();
		} else {
			if( _vars["init_url"] ){
					//parseUrl = _vars["init_url"].substring(2);
					parseUrl = _vars["init_url"];
//console.log(parseUrl);					
			}
			_vars["GET"] = func.parseGetParams( parseUrl ); 
			_urlManager();
		}
		
	};//end _init()


	function _getTpl( id ){
		var tpl = func.getById(id);
		var html = tpl.innerHTML;
		return html;
	}//end _getTpl()
	

	function defineEvents(){
//console.log( _vars.contentList );

		setClickHandler({
			"containerName":"panelService"
		});

		setClickHandler({
			"containerName":"appContainer"
		});

		
		//ADD NEW NODE (NOTE)
		document.forms["form_node"].onsubmit = function(event){

			event = event || window.event;
			var target = event.target || event.srcElement;
			
			if (event.preventDefault) { 
				event.preventDefault();
			} else {
				event.returnValue = false;
			}
			
//console.log("Submit form", event, this);
			var form = document.forms["form_node"];
//console.log(form);
//console.log(form.elements, form.elements.length);
//console.log(form.elements["number"]);
//form.action = "?q=save-node";
//console.log(form.action);
			var isValid = checkForm({
					"form" : form
			});
			
			if( isValid ){
				_vars["nodeModal"].modal("hide");
console.log( $(form).serialize() );

var formData = new FormData( form );

// Display the key/value pairs
for (var pair of formData.entries()) {
console.log(pair[0]+ ', ' + pair[1]);
}


//var formValues = {
//"action" : p["action"],
//"requestMethod" : form.getAttribute("method"),
//"enctype" : form.getAttribute("enctype") ? form.getAttribute("enctype") : null
//};
//sendForm( formValues );

			} else {
_vars["logMsg"] = "form validation error";
func.logAlert(_vars["logMsg"], "error");
			}
				
			return false;
		};//end event
				
	}//end defineEvents()

	function _urlManager( target ){
//console.log(target, _vars["GET"]);

		switch( _vars["GET"]["q"] ) {

			case "toggle-log":
//console.log(webApp.vars["log"]..style.display);
				if( _vars["log"].style.display==="none"){
					_vars["log"].style.display="block";
					_vars["btnToggle"].innerHTML="-";
				} else {
					_vars["log"].style.display="none";
					_vars["btnToggle"].innerHTML="+";
				}
			break;
		
			case "clear-log":
				_vars["log"].innerHTML="";
			break;
						
			case "load-xml":
				loadXml();
			break;
			
			case "book-list"://output content hierarchy
			
				_vars["breadcrumbs"] = {"top":"book list"}//?q=book-list
				
				//get book list from local contentObj
				if( _vars["contentObj"] ){
					//form book list (parent_id=0)
					var bookList = _getPageList({
							"parent_id" : "0"
					});
				//console.log(bookList);
					var html = _formPageList({
						"pages" : bookList,
						"html": _vars["templates"]["tpl-booklist"]
					});
					_vars["contentList"].innerHTML = html;
				}
				 
				//get book list from server
				if( !_vars["contentObj"] ){
					
				//if( _vars["serverUrl"] ){
					rpc_action = "get_booklist";
					sendRPC({
						"action" : rpc_action,
						"postFunc" : function( resp ){
	console.log("-- end rpc_request", resp );
							resp["action"] = rpc_action;
							parseServerResponse(resp);
						}
					});
				}
				
			break;

			case "view-node"://output single content node with child pages links
			
				//get node from local contentObj
				if( _vars["contentObj"] ){
					var nodeObj = _getNode({
						"id" : _vars["GET"]["id"]
					});
	//console.log(nodeObj);
					if( nodeObj ){
						
						var html = _formNode({"node" : nodeObj});
						_vars["contentList"].innerHTML = html;
						
						//hide service buttons
						var btnDelete = func.getById("btn-delete-node");
						btnDelete.style.display="none";
						
						var btnEdit = func.getById("btn-edit-node");
						btnEdit.style.display="none";
						
					} else {
	_vars["logMsg"] = "Not find node, id:" + _vars["GET"]["id"];
	func.logAlert(_vars["logMsg"], "error");
	console.log( _vars["logMsg"] );
					}
				}

				//get node from server
				if( !_vars["contentObj"] ){
					rpc_action = "get_content_item";
					sendRPC({
						"action" : rpc_action,
						"id" : _vars["GET"]["id"],
						"postFunc" : function( resp ){
//console.log("-- end rpc_request", resp );
							resp["action"] = rpc_action;
							resp["id"] = _vars["GET"]["id"];
							parseServerResponse(resp);
						}
					});
				}
				
			break;
/*
			case "send_rpc_request":
				sendRPC({
					"action" : _vars["GET"]["action"],//"get_content_list",
					"postFunc" : function( resp ){
console.log("-- end rpc_request", resp );
						resp["action"] = _vars["GET"]["action"];
						parseServerResponse(resp);
					}
				});
			break;
*/		
			case "delete-node":
				rpc_action = "remove_content_item";
				sendRPC({
					"action" : rpc_action,
					"id" : _vars["GET"]["id"],
					"postFunc" : function( resp ){
//console.log("-- end rpc_request", resp );
						resp["action"] = rpc_action;
						resp["id"] = _vars["GET"]["id"];
						parseServerResponse(resp);
					}
				});
			break;


			case "form-add-node":
				var title = _vars["addNodeTitle"];
				_vars["nodeModal"].find(".modal-title").text( title );
			break;
			
			case "form-edit-node":
//console.log("-- form-edit-node");
				var title = _vars["editNodeTitle"] + _vars["GET"]["id"];
				_vars["nodeModal"].find(".modal-title").text( title );
			break;
			
			//case "save-node":
			//break;

			
			default:
console.log("function _urlManager(),  GET query string: ", _vars["GET"]);			
			break;
		}//end switch
		
	}//end _urlManager()


	function setClickHandler(opt){
		var p = {
			"containerName" : null
		};
		
		//extend options object
		for(var key in opt ){
			p[key] = opt[key];
		}
//console.log( p );

		var clickContainerName = p["containerName"];

		if( !_vars[clickContainerName] ){
_vars["logMsg"] = "warning, container '"+clickContainerName+"' undefined, setClickHandler()";
func.logAlert(_vars["logMsg"], "warning");
			return false;
		}

		_vars[clickContainerName].onclick = function(event){
			event = event || window.event;
			var target = event.target || event.srcElement;
//console.log( event );
	// //console.log( this );//page-container
	//console.log( target.tagName );
	// //console.log( event.eventPhase );
	// //console.log( "preventDefault: " + event.preventDefault );
			// //event.stopPropagation ? event.stopPropagation() : (event.cancelBubble=true);
			// //event.preventDefault ? event.preventDefault() : (event.returnValue = false);				
			
			if( target.tagName === "A"){
				//if ( target.href.indexOf("#") !== -1){
					if (event.preventDefault) { 
						event.preventDefault();
					} else {
						event.returnValue = false;				
					}
					_vars["GET"] = func.parseGetParams( target.href ); 
					_urlManager();
				//}
			}
			
		}//end event

	}//end setClickHandler()


	function checkForm(opt){
		var p = {
			"form" : null
		};
		
		//extend options object
		for(var key in opt ){
			p[key] = opt[key];
		}
console.log( p );

		if( !p["form"] ){
_vars["logMsg"] = "error, checkForm()";
func.logAlert(_vars["logMsg"], "error");
			return false;
		}

		var form = p["form"];

		var res = true;
		for( var n = 0; n < form.elements.length; n++){
			var _element = form.elements[n];
				
			if( _element.classList.contains("invalid-field") ){
				_element.classList.remove("invalid-field");
			}	
			//if( _element.type === "text" ){
				//if( _element.className.indexOf("require-form-element") !== -1 ){
				if( _element.classList.contains("require-form-element") ){
//console.log( _element.value );
					//_element.className.replace("invalid-field", "").trim();
					if( _element.value.length === 0 ){
						res = false;
_vars["logMsg"] = "error, empty required input field <b>'" + _element.name +"'</b>";
func.logAlert( _vars["logMsg"], "error");
						//_element.className += " invalid-field";
						_element.classList.add("invalid-field")
//console.log( _element.className );
//console.log( _element.classList );
						//break;
					}
				}
			//}
				
		}//next
		
		return res;
	}//end checkForm()



	function loadXml(){

//if(_vars["contentObj"]){
	//return;
//}
		_vars["contentList"].innerHTML = "";

		//start block window
		if( _vars["waitWindowLoad"] ){
			_vars["waitWindowLoad"].style.display="block";
		}

		func.runAjax( {
			"requestMethod" : "GET", 
			"url" : _vars["localRequestUrl"], 
			
			"onProgress" : function( e ){
				var percentComplete = 0;
				if(e.lengthComputable) {
					percentComplete = Math.ceil(e.loaded / e.total * 100);
				}
console.log( "Loaded " + e.loaded + " bytes of total " + e.total, e.lengthComputable, percentComplete+"%" );

				_vars["totalBytes"] = e.total;
				_vars["totalMBytes"] = (( e.total / 1024) / 1024).toFixed(2);
				_vars["loaded"] = e.loaded;

				if( _vars["loadProgressBar"] ){
					////loadProgress.value = percentComplete;
					_vars["loadProgressBar"].className = "progress-bar";
					_vars["loadProgressBar"].style.width = percentComplete+"%";
					_vars["loadProgressBar"].innerHTML = percentComplete+"%";
				}
			},//end callback function
			
			"onError" : function( xhr ){
//console.log( "onError ", xhr);
_vars["logMsg"] = "error, not load " + _vars["localRequestUrl"]
func.logAlert(_vars["logMsg"], "error");
console.log( _vars["logMsg"] );
			},//end callback function
			
			"onLoadEnd" : function( headers ){
//console.log( "onLoadEnd ", headers);
_vars["logMsg"] = "load bytes: " + _vars["totalBytes"]+", Mbytes: " + _vars["totalMBytes"];
func.logAlert(_vars["logMsg"], "info");

_vars["logMsg"] = "e.loaded: " + _vars["loaded"];
console.log(_vars["logMsg"]);

			},//end callback function
			
			"callback": function( data, runtime ){
//console.log(data.length, typeof data, data );
_vars["logMsg"] = "load " + _vars["localRequestUrl"]  +", runtime: "+ runtime +" sec";
func.logAlert(_vars["logMsg"], "info");
console.log( _vars["logMsg"] );
// //console.log( "_postFunc(), " + typeof data );
// //console.log( data );
// //for( var key in data){
// //console.log(key +" : "+data[key]);
// //}

				if( !data ){
_vars["logMsg"] = "error, no XML data in " + _vars["localRequestUrl"] ;
func.logAlert(_vars["logMsg"], "error");
console.log( _vars["logMsg"] );
					return false;
				}
					
				var xmlObj = func.convertXmlToObj(data);
				_vars["contentObj"] = {
					"content" : xmlObj["xroot"]["childNodes"]["xdata"][0]["childNodes"]["content"],
					"content_links" : xmlObj["xroot"]["childNodes"]["xdata"][0]["childNodes"]["content_links"]
				};
				
				//convert content_links array to objects array {content_id : parent_id}
				var content_links = {};
				for( var n = 0; n < _vars["contentObj"]["content_links"][0]["childNodes"]["item"].length; n++){
					var item = _vars["contentObj"]["content_links"][0]["childNodes"]["item"][n]["attributes"];
					var key = item["content_id"];
					var value = item["parent_id"];
					content_links[key] = value;
				}//next
				_vars["contentObj"]["content_links"] = content_links;
				
				//convert content xml object to js object
				var content = {};
				var num_nodes = 0;
				for( var n = 0; n < _vars["contentObj"]["content"][0]["childNodes"]["node"].length; n++){
					var xmlObj = _vars["contentObj"]["content"][0]["childNodes"]["node"][n];
					
					var nodeObj = {};
					
					for( var key in xmlObj["attributes"]){
						nodeObj[key] = xmlObj["attributes"][key];
					}//next
					
					for( var key in xmlObj["childNodes"]){
						nodeObj[key] = xmlObj["childNodes"][key][0]["text"];
							
						var node_attr = xmlObj["childNodes"][key][0]["attributes"];
						for( var attr in node_attr){
							nodeObj[attr] = node_attr[attr];
						}//next
					}//next
					
					var id = nodeObj["id"];
					content[id] = nodeObj;
					
					num_nodes++;
				}//next
				_vars["contentObj"]["content"] = content;
delete data;
delete xmlObj;

				//clear block window
//setTimeout(function(){
				if( webApp["vars"]["waitWindowLoad"] ){
					webApp["vars"]["waitWindowLoad"].style.display="none";
				}		
//}, 1000*3);

				if( num_nodes > 0 ){//set number of notes
					_vars["$num_notes"].innerHTML  = num_nodes;
					
					_vars["GET"] = func.parseGetParams("?q=book-list"); 
					_urlManager();
				} else {
					_vars["logMsg"] = "Not find nodes";
					func.logAlert(_vars["logMsg"], "error");
					console.log( _vars["logMsg"] );
				}

				
			}//end callback()
		});
	
	}//end loadXml()

	
	function _getPageList( opt ){
		var p = {
			"parent_id": null
		};
		
		//extend options object
		for(var key in opt ){
			p[key] = opt[key];
		}
//console.log( p );

		if( !p["parent_id"]){
			return false;
		}
		
		var nodes=_vars["contentObj"]["content"];
		var content_links=_vars["contentObj"]["content_links"];
		
		var contentIdList = [];
		for( var content_id in content_links){
			var parent_id = content_links[content_id];
			if ( parent_id === p["parent_id"] ){
				contentIdList.push( content_id );
			}
		}//next
//console.log( contentIdList );			
		
		var pageList = [];
		for( var n = 0; n < contentIdList.length; n++){
			var id = contentIdList[n];
			var mainPage = nodes[id];
			//mainPage["child_nodes"] = _getChildNodes( id );
			pageList.push( mainPage );
		}//next
		
		return pageList;
	}//end _getPageList


	function _getNode( opt ){
		try{
			var p = {
				"id": null,
				"nodes" : _vars["contentObj"]["content"],
				"content_links" : _vars["contentObj"]["content_links"]
			};
		} catch(e){
console.log(e);
			_vars["logMsg"] = "error, _getNode()";
			//_vars["logMsg"] .= ", " + e;
			func.logAlert(_vars["logMsg"], "error");
			return false;
		}
		
		//extend options object
		for(var key in opt ){
			p[key] = opt[key];
		}
//console.log( p );

		if( !p["id"]){
_vars["logMsg"] = "error, empty node id, _getNode()";
func.logAlert(_vars["logMsg"], "error");
			return false;
		}
		
		var nodeObj = p.nodes[p.id];
		nodeObj["child_nodes"] = _getChildNodes( p.id );
		
		return nodeObj;
	}//end _getNode
	
	
	function _getChildNodes( parent_id ){
		//var childNodes = [];
		var childNodes = _getPageList({
			"parent_id" : parent_id,
		});
		return childNodes;
	}//end _getChildNodes()


	function _formPageList(opt){
		var p = {
			"pages": [],
			"html": _vars["templates"]["tpl-page-list"],
			"itemHtml": _vars["templates"]["tpl-page-list-item"]
		};
		
		//extend options object
		for(var key in opt ){
			p[key] = opt[key];
		}
//console.log( p );

		if( p["pages"].length === 0){
_vars["logMsg"] = "error, empty pages list, _drawPageList()";
func.logAlert(_vars["logMsg"], "error");
			return false;
		}
		
		var listHtml = "";
		for( var n = 0; n < p["pages"].length; n++){
			var itemHtml = p["itemHtml"];
			var item = p["pages"][n];
			for( var key in item ){
				var key2 = "{{"+key+"}}";
				if( itemHtml.indexOf( key2) !== -1 ){
//console.log(key, item[key]);
					itemHtml = itemHtml.replace(new RegExp(key2, 'g'), item[key]);
				}
			}//next
			listHtml += itemHtml;
		}//next

		html = p["html"].replace("{{list}}", listHtml);
		return html;
	}//end _formPageList()


	function _formNode(opt){
		var p = {
			"node": false
		};
		
		//extend options object
		for(var key in opt ){
			p[key] = opt[key];
		}
//console.log( p );

		if( !p["node"]){
_vars["logMsg"] = "error, _formNode()";
func.logAlert(_vars["logMsg"], "error");
			return false;
		}
		var node = p.node;
		
		//form HTML
		var html =_vars["templates"]["tpl-node"];

//---------------------------------- add linked page, form link				
//delete node["child_nodes"];

		if( node["child_nodes"] ){
			if( node["child_nodes"].length > 0){
				var linkedHtml = _formPageList({
					"pages" : node["child_nodes"]
				});
				html = html.replace("{{child_nodes}}", linkedHtml);
			}
		}

//-------------------------------- convert timestamp to string Data		
		if( node["created"] ){
			if( node["created"].length > 0){
				var createdString = func.timeStampToDateStr({
					timestamp : node["created"],
					format : "yyyy-mm-dd hh:min" 
				});
				html = html.replace("{{created}}", createdString);
//console.log(createdString);
			}
		}
		if( node["changed"] ){
			if( node["changed"].length > 0){
				var changedString = func.timeStampToDateStr({
					timestamp : node["changed"],
					format : "yyyy-mm-dd hh:min" 
				});
				html = html.replace("{{changed}}", changedString);
			}
		}
		
//------------------------------- insert data into template
		for( var key in node){
//console.log(key, node[key]);
			var key2 = "{{"+key+"}}";
			if( html.indexOf(key2) !== -1 ){
//console.log(key, node[key]);
				if( node[key] ){
					html = html.replace(new RegExp(key2, 'g'), node[key]);
				} else {
_vars["logMsg"] = "warning, undefined key "+key+", title: <b>"+node["title"]+"</b>,_drawNode()";
func.logAlert(_vars["logMsg"], "warning");
					html = html.replace(new RegExp(key2, 'g'), "");
				}
			}
		}//next
		
//-------------------------------- form breadcrumbs
		//add container link to breadcrumbs
		_vars["breadcrumbs"][ "key_" + node.id ] = node["title"];
//console.log("add breadcrumb item: ", node.id);
		//form breadcrumbs line
		var breadcrumbs = "";
		var clear = false;
		for( var item in _vars["breadcrumbs"] ){

			if( item === "top"){
				var itemTitle = _vars["breadcrumbs"][item];
				breadcrumbs = "<a href='?q=book-list' class='btn'>" + itemTitle + "</a> > ";
				continue;
			}
			
			var itemID = item.replace("key_", "");
			
			if( clear ){//clear unuseful tail breadrumbs
				delete _vars["breadcrumbs"][item];
			} else {
				var itemTitle = _vars["breadcrumbs"][item];
				if( itemID !== node.id ){
					breadcrumbs += "<a href='?q=view-node&id="+itemID+"' class=''>" + itemTitle + "</a> > ";
				} else {
					breadcrumbs += "<span class='active-item'>" + itemTitle + "</span>";
				}
			}
//console.log( itemID, node.id, itemID === node.id );
//console.log( typeof itemID, typeof node.id );
			if( itemID === node.id ){//detect unuseful tail breadrumbs
				clear = true;
			}
			
		}//next
//console.log( breadcrumbs );

		html = html.replace("{{breadcrumbs}}", breadcrumbs);
//console.log(html);
		return html;
	}//end _formNode()


	function sendRPC( opt ){

		var p = {
			"action": "",//"get_content_list",
			"url" : _vars["serverUrl"],
			"postFunc": null
		};
		
		//extend options object
		for(var key in opt ){
			p[key] = opt[key];
		}
//console.log( p );

		//if( !p["parent_id"]){
			//return false;
		//}
		
		var url = false;
		switch( p.action ){
			
			case "get_content_list":
				url = p.url + "?q=content/rpc_list";
			break;
			
			case "get_booklist":
				url = p.url + "?q=content/rpc_booklist";
			break;
			
			case "get_content_item":
				url = p.url + "?q=content/rpc_get_item&id="+p.id;
			break;
			
			case "remove_content_item":
				url = p.url + "?q=content/rpc_remove&id="+p.id;
			break;
			
			//default:
			//break;
		}//end switch
		
		if( !url ){
_vars["logMsg"] = "error, sendRPC(), wrong RPC action: " + p.action;
func.logAlert(_vars["logMsg"], "error");
console.log(_vars["logMsg"]);
			return false;
		}
		
		//start block window
		if( webApp["vars"]["waitWindow"] ){
			webApp["vars"]["waitWindow"].style.display="block";
		}

		$.getJSON(  url, function( resp ){
//console.log(resp);
			if( typeof p.postFunc === "function"){
				p.postFunc( resp );
			}
		})
		.done(function () {
_vars["logMsg"] = "$.getJSON, done, url: " + url;
func.logAlert(_vars["logMsg"], "success");
//console.log( arguments );
		})
		.fail(function (xhr, textStatus, error) {
_vars["logMsg"] = "$.getJSON, "+textStatus+", "+error+", url: " + url;
func.logAlert( _vars["logMsg"], "error");
//console.log( _vars["logMsg"], arguments );
			if( typeof p.postFunc === "function"){
				var resp = {
					"eventType": "error", 
					"data": []
				};
				p.postFunc(resp);
			}
			
		});

	}//end sendRPC()


	function parseServerResponse( opt ){
		var p = {
			"eventType": "error",
			"data" : null,
			"action" : false
		};
		
		//extend options object
		for(var key in opt ){
			p[key] = opt[key];
		}
//console.log( p );

		//_vars["logMsg"] = "end rpc_request, status: " + p["eventType"];
		//func.logAlert(_vars["logMsg"], p["eventType"] );

		//clear block window
//setTimeout(function(){
		if( webApp["vars"]["waitWindow"] ){
			webApp["vars"]["waitWindow"].style.display="none";
		}		
//}, 1000*3);
		
		if( p["eventType"] === "error" ){
console.log( p );

_vars["logMsg"] = "end rpc_request, status: " + p["eventType"];
func.logAlert(_vars["logMsg"], p["eventType"] );

_vars["logMsg"] = "server error, trying to load XML local file";
func.logAlert(_vars["logMsg"], "warning");

			_vars["serverUrl"] = false;
			_vars["init_url"] = "?q=load-xml";
			_vars["GET"] = func.parseGetParams( _vars["init_url"] ); 
			_urlManager();
			return false;
		}

		if( !p["action"] ){
console.log( p );
_vars["logMsg"] = "error, undefined RPC action, parseServerResponse()";
func.logAlert(_vars["logMsg"], "error");
			return false;
		}


		switch( p.action ){
			
			case "get_booklist":
				if( p["data"] && p["data"].length > 0){
					var html = _formPageList({
						"pages" : p["data"],
						"html": _vars["templates"]["tpl-booklist"]
					});
					_vars["contentList"].innerHTML = html;
				}
			break;

			case "get_content_item":
				if( p["data"]){
						
					//convert child nodes key content_id -> id
					if( p["data"]["child_nodes"] && 
							p["data"]["child_nodes"].length > 0
						){
						var child_nodes = p["data"]["child_nodes"];
						for( var n = 0; n < child_nodes.length; n++){
							child_nodes[n]["id"] = child_nodes[n]["content_id"];
							delete child_nodes[n]["content_id"];
							delete child_nodes[n]["parent_id"];
						}//next
					} else {
						p["data"]["child_nodes"] = [];
					}
					
					var html = _formNode({"node" : p["data"]});
					_vars["contentList"].innerHTML = html;
						
				} else {
console.log( p );
_vars["logMsg"] = "Not find node, id:" + p.id;
func.logAlert(_vars["logMsg"], "error");
console.log( _vars["logMsg"] );
				}
			break;

			case "remove_content_item":
				_vars["logMsg"] = p["message"];
				func.logAlert(_vars["logMsg"], p["eventType"] );
				
				_vars["init_url"] = "?q=book-list";
				_vars["GET"] = func.parseGetParams( _vars["init_url"] ); 
				_urlManager();
			break;
			
			default:
console.log( p );
_vars["logMsg"] = "error, unknown RPC action: " + p.action +", parseServerResponse()";
func.logAlert(_vars["logMsg"], "error");
				return false;
			break;
		}//end switch

		
	}//end parseServerResponse()

	
	// public interfaces
	return{
		vars : _vars,
		init:	function(){ 
			return _init(); 
		}
	};
	
}//end _app()
