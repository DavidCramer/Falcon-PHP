		var editor, currentID;

		function isOpen(){
			var clicked = jQuery(arguments[0].target);
			jQuery('.newMethod').remove();
			if(clicked.data('save') || clicked.parent().hasClass('item-selected')){
				return saveCloseFile(clicked);
			}else{
				var editorID = clicked.data('id');
				if(typeof editor === 'object'){
					editor.save();
					editor.toTextArea();
				}
				jQuery('.editor-wrapper').hide();
				// remove if not changed
				if(jQuery('#item'+currentID+'>div.item-open').length === 0){
					jQuery('#wrapper'+currentID).remove();
				}				
				if(clicked.hasClass('item-open')){
					jQuery('.item-selected').removeClass('item-selected');
					clicked.parent().addClass('item-selected');
					jQuery('#wrapper'+editorID).show();
					initEditor(arguments[0].target);
					return false;
				}
			}
		}
		function saveCloseFile(entry){
			
			var editorID;
			// check if save button was clicked.
				if(entry.data('save')){
					editorID = entry.parent().parent().data('id');
					// indicate saving
					entry.parent().parent().removeClass('item-open').addClass('item-saving')

					// Do Save Process
					//var bld = jQuery('#textarea'+editorID).baldrick();
					//console.log(bld);
					//bld.doAction();
					//jQuery('#textarea'+editorID)
					//console.log(editor);

					jQuery('#textarea'+editorID).doAction();



					// end save process
					entry.parent().parent().removeClass('item-saving').parent().removeClass('item-selected')
					entry.parent().hide();
					return false;
				}
			var editorID = entry.data('id');
			if(entry.hasClass('item-open')){
				if(!confirm('Close file without saving?')){
					return false;
				}
			}
			
			jQuery('#'+editorID).remove();
			jQuery('#wrapper'+editorID).remove();
			entry.removeClass('item-open').parent().removeClass('item-selected')
			entry.find('.tools').hide();
			jQuery('#wrapper'+editorID).remove();
			return false;
		}
		function newEdit(){
			console.log(arguments);
			jQuery('#main').append(arguments[0]);
			initEditor(arguments[1]);
		}
		function initEditor(){
			/* Setup Editor */
			
			//var editor;
			var clicked = jQuery(arguments[0]);
			var editorID = clicked.data('id');
			//console.log(arguments);
			var textArea = document.getElementById('textarea'+editorID);
			//console.log(arguments);
			if(typeof textArea === 'object'){
				currentID = editorID;
				editor = CodeMirror.fromTextArea(textArea, {
					lineNumbers: true,
					matchBrackets: true,
					mode: "application/x-httpd-php",
					indentUnit: 4,
					indentWithTabs: true,
					enterMode: "keep",
					tabMode: "shift",
					lineWrapping: false
				});
				//editor.refresh();
				//editor.focus();
				editor.on('change', function(cm){
					var itemrow = jQuery('#item'+editorID);
					itemrow.data('changed', true).find('.entry').addClass('item-open').find('.tools').show();

				});
				editor.on('blur', function(cm){
					cm.save();
				});

				/// DO THIS ON CHANGE ONLY!
				//clicked.find('.entry').addClass('item-open');
				//clicked.find('.icon-remove').show();
				
			}

		}		
		function sendToEditor(){
			if(typeof editor === 'undefined'){
				initEditor();
			}
			editor.setValue(arguments[0]);
			editor.refresh();
			editor.display.wrapper.id = 'WOOOT';
			console.log(editor);
		}

		function saveFile(){
			//console.log(arguments);
			var item = jQuery('#item'+currentID);
			item.find('.item-open').removeClass('item-open');
			item.find('.tools').hide();
		}
		function newMethod(){
			//console.log(arguments);
			jQuery('.newMethod').remove();
			jQuery('#list-content').prepend('<div class="item newMethod form-inline"><div class="entry"><input id="newMethodInput" type="text" name="newMethod" class="input-block-level trigger" data-method="POST" data-request="module/routing/methods" disdata-autoload="true" data-event="change" data-target="list-content" placeholder="new filename"></div></div>');
			jQuery('#newMethodInput').focus();
		}

	    jQuery(window).keypress(function(event) {
	    	if(!editor){return;}

	        if (!(event.which == 115 && event.metaKey) && !(event.which == 83 && event.metaKey) && !(event.which == 19)) return true;
	        event.preventDefault();
	        editor.save();
	        jQuery('#textarea'+currentID).doAction();
	        return false;
	    });


function doHighlight() {
	var precode = jQuery('#readCode');
	var code = precode.val();

	jQuery('#dumpVars').addClass('cm-s-default');
  CodeMirror.runMode(code, "application/x-httpd-php",
                     document.getElementById("dumpVars"));
}

