(function() {
    tinymce.create('tinymce.plugins.oscitasRestabs', {
        init : function(ed, url) {
            ed.addButton('oscitasrestabs', {
                title : 'Tabs Shortcode',
                image : url+'/icon.png',
                onclick : function() {
                    ert_create_oscitas_responsive_tab();
                    jQuery( "#ert-form-restabs" ).dialog({
                        dialogClass : 'wp-dialog osc-dialog',
                        autoOpen: true,
                        height: 'auto',
                        width: 800,
                        modal: true
                    });
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname : "Responsive Tabs Shortcode",
                author : 'Oscitas Themes',
                authorurl : 'http://www.oscitasthemes.com/',
                infourl : 'http://www.oscitasthemes.com/',
                version : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('oscitasrestabs', tinymce.plugins.oscitasRestabs);
})();
function responsive_result_show(){
    if(jQuery('#oscitas-restabs-responsive').prop('checked')){
        jQuery('.responsive_result').show();
    } else{
        jQuery('.responsive_result').hide();
    }
}
function ert_create_oscitas_responsive_tab(){
    if(jQuery('#ert-form-restabs').length){
        jQuery('#ert-form-restabs').remove();
    }
    // creates a form to be displayed everytime the button is clicked
    // you should achieve this using AJAX instead of direct html code like this
    var form = jQuery('<div id="ert-form-restabs" title="Easy Responsive Tab Shortcode"><table id="oscitas-table" class="form-table" style="margin-top: 0px;">\
			<tr>\
				<th><label for="oscitas-restabs-position">Show Tabs Position</label></th>\
				<td colspan="3"><select name="type" id="oscitas-restabs-position">\
					<option value="">Top</option>\
					<option value="tabs-below">Bottom</option>\
				</select><br />\
				</td>\
			</tr>\
			<tr>\
				<th><label for="oscitas-restabs-alignment">Tabs Alignment</label></th>\
				<td colspan="3"><select name="type" id="oscitas-restabs-alignment">\
					<option value="osc-tabs-left" selected="selected">Left</option>\
					<option value="osc-tabs-center">center</option>\
					<option value="osc-tabs-right">Right</option>\
				</select><br />\
				</td>\
			</tr>\
			<tr>\
				<th><label for="oscitas-restabs-pills">Tabs With Pills</label></th>\
				<td colspan="3">\
				    <input type="checkbox" id="oscitas-restabs-pills">\
                    <small>Check this checkbox to show selector on selected tab</small>\
				</td>\
			</tr>\
        <tr>\
				<th><label for="oscitas-restabs-responsive">Make Responsive</label></th>\
				<td colspan="3">\
				    <input type="checkbox" id="oscitas-restabs-responsive">\
                    <small>Check this option if you want to make tabs responsive with more button</small>\
				</td>\
			</tr>\
			<tr class="responsive_result">\
				<th><label for="oscitas-restabs-icon">Use Icon</label></th>\
				<td colspan="3">\
				    <input type="checkbox" id="oscitas-restabs-icon">&nbsp;&nbsp;&nbsp;<i class="res_tab_icon"></i>\
				</td>\
			</tr>\
			<tr class="responsive_result">\
				<th><label for="oscitas-restabs-text">Drop Down Text</label></th>\
				<td colspan="3"><input type="text" name="title" id="oscitas-restabs-text" value="More"/><br />\
				</td>\
			</tr>\
			<tr>\
				<th><label for="oscitas-restabs-number">Number of Tabs</label></th>\
				<td  colspan="3"><input type="text" name="title" id="oscitas-restabs-number" value="4"/>&nbsp;&nbsp;Enter a numeric value, Default is 4</td>\
			</tr>\
			<tr>\
				<th><label for="oscitas-restabs-tabcolor">Tab Color</label></th>\
				<td> <input type="text" value="" id="oscitas-restabs-tabcolor" class="ert_color_selector" />\
                </div></td>\
				<th><label for="oscitas-restabs-tabheadcolor">Tab Heading Color</label></th>\
				<td> <input type="text" value="" id="oscitas-restabs-tabheadcolor" class="ert_color_selector" />\
                </div></td>\
			</tr>\
			<tr>\
				<th><label for="oscitas-restabs-seltabcolor">Active Tab Color</label></th>\
				<td> <input type="text" value="" id="oscitas-restabs-seltabcolor" class="ert_color_selector" /></td>\
				<th><label for="oscitas-restabs-seltabheadcolor">Active Tab Heading Color</label></th>\
				<td> <input type="text" value="" id="oscitas-restabs-seltabheadcolor" class="ert_color_selector" /></td>\
			</tr>\
			<tr>\
				<th><label for="oscitas-restabs-tabhovercolor">Tab Hover Color</label></th>\
				<td> <input type="text" value="" id="oscitas-restabs-tabhovercolor" class="ert_color_selector" /></td>\
			    <th><label for="oscitas-restabs-contentcolor">Tab Content BG Color</label></th>\
				<td> <input type="text" value="" id="oscitas-restabs-contentcolor" class="ert_color_selector" /></td>\
			</tr>\
             <tr>\
				<th><label for="oscitas-restabs-class">Custom Class</label></th>\
				<td colspan="3"><input type="text" name="line" id="oscitas-restabs-class" value=""/><br />\
				</td>\
			</tr>\
		</table>\
		<p class="submit" style="padding-right: 10px;text-align: right;">\
			<input type="button" id="oscitas-restab-submit" class="button-primary" value="Insert Responsive Tabs" name="submit" />\
		</p>\
		</div>');
    var table = form.find('table');
    form.appendTo('body').hide();
    jQuery('.ert_color_selector').wpColorPicker();
    responsive_result_show();
    jQuery('#oscitas-restabs-responsive').click(function(){
        responsive_result_show();
    });
    form.find('#oscitas-restab-submit').click(function(){

        var cusclass='',icon='',text='',pills='',position='',item= 0,eactive='',responsive='';
        var num=table.find('#oscitas-restabs-number').val();
        var color=['tabcolor','tabheadcolor','seltabcolor','seltabheadcolor','tabhovercolor','contentcolor'],ert_color='';
        jQuery.each(color,function(index,val){
            if(jQuery('#oscitas-restabs-'+val).val()!=''){
                ert_color +=' '+val+'="'+jQuery('#oscitas-restabs-'+val).val()+'"';
            }

        })
        if(jQuery('#oscitas-restabs-pills').prop('checked')){
            pills=' pills="nav-pills"';

        }
        if(jQuery('#oscitas-restabs-responsive').prop('checked')){
            responsive=' responsive="true"';
            if(jQuery('#oscitas-restabs-icon').prop('checked')){
                icon=' icon="true"';

            }
            if(table.find('#oscitas-restabs-text').val()!=''){
                text= ' text="'+table.find('#oscitas-restabs-text').val()+'"';
            }
        } else{
            responsive=' responsive="false"';
        }

        if(table.find('#oscitas-restabs-position').val()!=''){
            position= ' position="tabs-below"';
        }

        if(table.find('#oscitas-restabs-alignment').val()!=''){
            position += ' alignment="'+table.find('#oscitas-restabs-alignment').val()+'"';
        }

        if(table.find('#oscitas-restabs-class').val()!=''){
            cusclass= ' class="'+table.find('#oscitas-restabs-class').val()+'"';
        }
        if( Math.floor(num) == num && jQuery.isNumeric(num)){
            item=num;
        } else{
            item=4;
        }
            var shortcode = '[restabs'+position+pills+responsive+icon+text+cusclass+ert_color;
        shortcode += ']';

            for(var i=1;i<=item;i++){
                if(i==1){
                    eactive=' active="active"';
                }
                else{
                    eactive='';
                }
                shortcode+='<br/>[restab title="Tab number '+i+'"'+eactive+']Tab '+i+' content goes here.[/restab]';
            }
        shortcode += '[/restabs]';

        // inserts the shortcode into the active editor
        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

        // closes dialog box
        jQuery( "#ert-form-restabs" ).dialog('close');

    });
}


