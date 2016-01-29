/*
 GaiaEHR (Electronic Health Records)
 PhotoIdWindow.js
 UX
 Copyright (C) 2012 Ernesto Rodriguez

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
Ext.define('App.ux.PhotoIdWindow',
{
	extend : 'Ext.window.Window',
	alias : 'widget.photoidwindow',
	height : 320,
	width : 320,
	layout : 'fit',
	renderTo : document.body,
	initComponent : function()
	{
		var me = this;

		window.webcam.set_api_url('dataProvider/WebCamImgHandler.php');
		window.webcam.set_swf_url('lib/jpegcam/htdocs/webcam.swf');
		window.webcam.set_quality(100);
		// JPEG quality (1 - 100)
		window.webcam.set_shutter_sound(true, 'lib/jpegcam/htdocs/shutter.mp3');
		// play shutter click sound
		window.webcam.set_hook('onComplete', 'onWebCamComplete');

		Ext.apply(me,
		{
			html : window.webcam.get_html(320, 320),
			buttons : [
			{
				text : _('capture'),
				iconCls : 'save',
				handler : me.captureToCanvas
			},
			{
				text : _('cancel'),
				scope : me,
				handler : function()
				{
					this.close();
				}
			}]
		});
		me.callParent(arguments);
	},

	captureToCanvas : function()
	{
		window.webcam.snap();
	}
}); 