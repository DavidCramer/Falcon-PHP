function showDeleteButton(){
	jQuery('#routeDeleteButton').show();
	jQuery('#routeSaveButton').html('<i class="icon-save"></i> &nbsp;Save Changes');

}
function confirmDeleteRoute(){
	return confirm('Are you sure you want to delete this route?');
}
function toggleRouteDesc(){
	var view = jQuery('.routedesc').toggleClass('hide');
	var date = new Date();
	var show = view.hasClass('hide');
	date.setTime(date.getTime()+(60*60*60*1000));
	document.cookie = "route-desc="+show+"; expires="+date.toGMTString()+"; path=/";

}
function loadRoute(){
	jQuery('#main').html(arguments[0]);
}

function saveRoute(){
	jQuery('#routeSaver').html(arguments[0]);
}

function addRouteHeader(){


	var index = jQuery('.headersDefine').length;
	var head = jQuery('#route-head').val();
	var val = jQuery('#head-val').val();
	
	if(head == ''){
		return;
	}
	jQuery('#noheads').remove();	
	jQuery('.routeHeads').val('');
	//jQuery('#routeMethod'+method).remove();

	jQuery('#headList').append('<div style="margin: 5px 0;" id="header'+index+'" class="row headersDefine"><div class="span2"><span style="text-align:left; opacity: 1;">'+head+'</span><input type="hidden" value="'+encodeURIComponent(head)+'" name="preheaders[header][]"></div><div class="span3"><span style="text-align:left; opacity: 1;">'+val+'</span><input type="hidden" value="'+encodeURIComponent(val)+'" name="preheaders[value][]"></div><div class="span1"><button onclick="jQuery(\'#header'+index+'\').remove();" type="button" class="btn btn-small btn-danger"><i class="icon-remove"></i></button></div></div>');

}
function addRouteLib(){

	var index = jQuery('.routeLibDefine').length;
	var file = jQuery('#lib-file').val();
	
	if(file == ''){
		return;
	}
	jQuery('#noLibs').remove();	
	jQuery('.routeLib').val('');

	jQuery('#libList').append('<div style="margin: 5px 0;" id="routeLib'+index+'" class="row routeLibDefine"><div class="span5"><span style="text-align:left; opacity: 1;">'+file+'</span><input type="hidden" value="'+file+'" name="libraries[]"></div><div class="span1"><button onclick="jQuery(\'#routeLib'+index+'\').remove();" type="button" class="btn btn-small btn-danger"><i class="icon-remove"></i></button></div></div>');
}
function addRouteMethod(){


	
	var method = jQuery('#route-method').val();
	var file = jQuery('#method-file').val();
	
	if(file == '' || method == ''){
		return;
	}
	jQuery('#noMethods').remove();	
	jQuery('.routeMethod').val('');
	jQuery('#routeMethod'+method).remove();



	jQuery('#methodsList').append('<div style="margin: 5px 0;" id="routeMethod'+method+'" class="row routeMethodDefine"><div style="margin-left: 0;" class="span1"><span style="opacity: 1;" class="btn btn-primary btn-small btn-block disabled">'+method+'</span></div><div class="span5"><span style="text-align:left; opacity: 1;">'+file+'</span><input type="hidden" value="'+file+'" name="methods['+method+'][file]"></div><div class="span1"><button onclick="jQuery(\'#routeMethod'+method+'\').remove();" type="button" class="btn btn-small btn-danger"><i class="icon-remove"></i></button></div></div>');

}


function uploadHandle(){
	console.log(arguments);
}
