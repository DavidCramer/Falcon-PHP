function toggleWidth(){
	var layout = jQuery('#layout');
	var date = new Date();
	date.setTime(date.getTime()+(24*60*60*1000));
	if(layout.hasClass('wide')){
		layout.removeClass('wide');
		document.cookie = "wide-nav=0; expires="+date.toGMTString()+"; path=/";	
	}else{
		layout.addClass('wide');
		document.cookie = "wide-nav=1; expires="+date.toGMTString()+"; path=/";	
	}
}

function fullscreenToggle(){
	var layout = jQuery('#layout');
	var date = new Date();
	date.setTime(date.getTime()+(24*60*60*1000));
	if(layout.hasClass('full-screen')){
		layout.removeClass('full-screen');
		document.cookie = "full-screen=0; expires="+date.toGMTString()+"; path=/";	
	}else{
		layout.addClass('full-screen');
		document.cookie = "full-screen=1; expires="+date.toGMTString()+"; path=/";	
	}
}


jQuery(document).ready( function() {

/*setTimeout(function(){

bootbox.dialog("You can close this. It's just a test.", [
	{
		"label" : "Save",
		"class" : "btn-primary",
		"callback": function() {
		
		}
	},{
		"label" : "Cancel",
		"class" : "btn-danger",
		"callback": function() {

		}
	}
	], {
		"header"  : "Dialog example"
	});


}, 2000);*/

	if(document.getElementById('prim-picker')){
		var style = document.getElementById('prim-picker');
	}else{
		var style = document.createElement('style');
		style.id = 'prim-picker';
		style.type = 'text/css';
		document.head.appendChild(style);
	}
	var def = jQuery('#nav').css('background-color');
	//alert(def);
	jQuery('#actions-bodycolor a i').minicolors({
		defaultValue	: def,
	    change: function(hex, opacity) {
	        //console.log(hex + ' - ' + opacity);
	        style.textContent = '.btn-primary,.primary{background-color: '+hex+' !important;}';
            var date = new Date();
            date.setTime(date.getTime()+(24*60*60*1000));                                
            document.cookie = "custom-primary="+hex+"; expires="+date.toGMTString()+"; path=/";

	    }
	});

});