//******************************************************************************
// Photo ID Window
//******************************************************************************
Ext.define('App.classes.PhotoIdWindow', {
	extend       : 'Ext.window.Window',
	alias        : 'widget.photoidwindow',
	height       : 292,
	width        : 320,
	layout       : 'fit',
	renderTo     : document.body,
	initComponent: function() {
		var me = this;


		window.webcam.set_api_url( 'dataProvider/WebCamImgHandler.php' );
	    window.webcam.set_swf_url( 'lib/jpegcam/htdocs/webcam.swf' );
	    window.webcam.set_quality( 100 ); // JPEG quality (1 - 100)
	    window.webcam.set_shutter_sound( true, 'lib/jpegcam/htdocs/shutter.mp3' ); // play shutter click sound
	    window.webcam.set_hook( 'onComplete', 'onWebCamComplete' );

		Ext.apply(this, {
			html:window.webcam.get_html(320, 240),
			buttons: [
				{
					text   : 'Cancel',
					handler: function(){
						this.close();
					}
				},
				{
					text   : 'Capture Image',
					iconCls: 'save',
					handler: me.captureToCanvas
				}
			]
		},null);
		me.callParent(arguments);
	},

	captureToCanvas:function(){
		window.webcam.snap();
	}
});