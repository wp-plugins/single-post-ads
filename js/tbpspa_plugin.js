// http://www.garyc40.com/2010/03/how-to-make-shortcodes-user-friendly/
// closure to avoid namespace collision
(function(){
	// creates the plugin
	tinymce.create('tinymce.plugins.tbpspa', {
		// creates control instances based on the control's id.
		// our button's id is "tbpspa_button"
		createControl : function(id, controlManager) {
			if (id == 'tbpspa_button') {
				// creates the button
				var button = controlManager.createButton('tbpspa_button', {
					title : 'Single Post Ads Shortcode', // title of the button
					image : '../wp-content/plugins/tbp-single-post-ads/images/tbpspa_button.gif',  // path to the button's image
					onclick : function() {
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'Insert SimpleS3Video Shortcode', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=ss3vdo-form' );
					}
				});
				return button;
			}
			return null;
		}
	});
	
	// registers the plugin. DON'T MISS THIS STEP!!!
	tinymce.PluginManager.add('tbpspa', tinymce.plugins.tbpspa);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form = jQuery('<div id="ss3vdo-form"><table id="ss3vdo-table" class="form-table">\
			<tr>\
				<th><label for="ss3vdo-bucket">Bucket</label></th>\
				<td><input type="text" size="70%" name="bucket" id="ss3vdo-bucket"><br />\
				<small>If not using the default bucket, enter it here</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss3vdo-file">Video File</label></th>\
				<td><input type="text" size="70%" name="file" id="ss3vdo-file" value="" /><br /><small>Enter your video file name</small></td>\
			</tr>\
			<tr>\
			<tr>\
				<th><label for="ss3vdo-play">Auto Play</label></th>\
				<td><input type="text" name="play" id="ss3vdo-play" value="" /><br /><small>Enter true to automatically play video or false to pause video on load</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss3vdo-width">Video Width</label></th>\
				<td><input type="text" name="width" id="ss3vdo-width" value="" /><br />\
					<small>Desired width of video if not default</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss3vdo-height">Video Height</label></th>\
				<td><input type="text" name="height" id="ss3vdo-height" value="" /><br />\
					<small>Desired height of video if not default</small></td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="ss3vdo-submit" class="button-primary" value="Insert Video" name="submit" />\
		</p>\
		</div>');
		
		var table = form.find('table');
		form.appendTo('body').hide();
		
		// handles the click event of the submit button
		form.find('#ss3vdo-submit').click(function(){
			// defines the options and their default values
			// again, this is not the most elegant way to do this
			// but well, this gets the job done nonetheless
			var options = { 
				'bucket'    : '',
        'file'    : '',
				'play'    : '',
				'width'    : '',
				'height'    : ''
				};
			var shortcode = '[ss3vdo';
			
			for( var index in options) {
				var value = table.find('#ss3vdo-' + index).val();
				
				// attaches the attribute to the shortcode only if it's different from the default value
				if ( value !== options[index] )
					shortcode += ' ' + index + '="' + value + '"';
			}
			
			shortcode += ']';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});
	});
})()
