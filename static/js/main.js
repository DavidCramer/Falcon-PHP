

function checkForScripts(){
	//console.log(arguments);
	var clicked = jQuery(arguments[0].target);
	if(clicked.data('scripts')){
		var scripts = clicked.data('scripts').split(',');
		for(s=0;s<scripts.length; s++){
			jQuery.getScript(scripts[s]);
		}	
	}
}