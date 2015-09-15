(function() {
	if( typeof(tinyMCE) != "undefined" && typeof(tinyMCE.majorVersion) != "undefined" && tinyMCE.majorVersion >= 4 ){
		tinymce.PluginManager.add('goodlayers', function( editor, url ) {
			var list = [];
			for(var i in gdlr_shortcodes){
				if( gdlr_shortcodes[i] ){
					var item = {};
					item.text = gdlr_shortcodes[i].title;
					item.value = gdlr_shortcodes[i].value;
					item.onclick = function() {
						editor.insertContent(this.value());
					}
					list.push(item);
				}
			}
		
			editor.addButton( 'goodlayers', {
				text: 'GDLR Shortcode',
				type: 'menubutton',
				icon: false,
				menu: list
			});
		});
	}else{
		tinymce.create('tinymce.plugins.goodlayers', {
		
			init : function(ed, url) { },
			createControl : function(n, cm) {
		
				if(n=='goodlayers'){
					var mlb = cm.createListBox('goodlayers', {
						 title : 'Shortcode',
						 onselect : function(v) {
							if(tinyMCE.activeEditor.selection.getContent() == ''){
								tinyMCE.activeEditor.selection.setContent( v );
							}
						 }
					});
				
					for(var i in gdlr_shortcodes){
						mlb.add(gdlr_shortcodes[i].title, gdlr_shortcodes[i].value);
					}
					
					return mlb;
				}
				return null;
			}
		
		
		});
		tinymce.PluginManager.add('goodlayers', tinymce.plugins.goodlayers);
	}
})();