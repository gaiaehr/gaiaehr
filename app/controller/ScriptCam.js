Ext.define('App.controller.ScriptCam', {
	extend: 'Ext.app.Controller',

	refs: [
		{
			ref: 'webCamWindow',
			selector: 'scriptcamwindow'
		},
		{
			ref: 'ScriptCamCameras',
			selector: 'combobox[action=ScriptCamCameras]'
		}
		//        {
		//            ref:'photoIdImage',
		//            selector:'image[action=photoIdImage]'
		//        }
	],

	init: function(){
		var me = this;

		me.userMedia = null;


		me.control({
//			'scriptcamwindow': {
//				render: me.onWebCamWindowRender
//			},
//			'image': {
//				render: me.onImageRender
//			},
			'button[action=onWebCam]': {
				click: me.onWebCamClick
			},
			'button[action=onCaptureImage]': {
				click: me.onCapture
			}
		})
	},

//	onImageRender: function(img){
//		img.el.on('click', function(e, el){
//			Ext.widget('window', {
//				html: '<img src="' + img.src + '">'
//			}).show();
//		});
//	},

	onWebCamClick: function(btn){
		var me = this;

		me.action = btn.up('panel').action;
		me.imgPanel = btn.up('panel');

//		if(me.action == 'patientImage'){
//			me.width = 480;
//			me.height = 480;
//		}else{
//			me.width = 595;
//			me.height = 360;
//		}

		navigator.getMedia = ( navigator.getUserMedia ||
			navigator.webkitGetUserMedia ||
			navigator.mozGetUserMedia ||
			navigator.msGetUserMedia);
		navigator.getMedia({
			video: true,
			audio: false
		}, me.onConnect, me.onError);
	},

	onConnect: function(stream){
		var me = app.getController('ScriptCam');

		me.win = Ext.create('Ext.window.Window', {
			title: '...',
			html: ('<video id="WebCamVideo" width="640" height="480"></video>'),
			autoShow: true,
			buttons: [
				'->',
				{
					text: _('capture_img'),
					action: 'onCaptureImage'
				}
			],
			listeners: {
				close: function(){
					stream.stop();
				}
			}
		});

		me.video = document.getElementById('WebCamVideo');
		me.video.src = window.URL ? window.URL.createObjectURL(stream) : stream;
		me.video.play();
	},

	onError: function(error){
		say(error);
	},

	onClose: function(){
		var video = document.getElementById('WebCamVideo');
		video.src = window.URL ? window.URL.createObjectURL(stream) : stream;
		video.play();
	},

	onCapture: function(){
		var me = this,
			canvas = document.createElement('canvas'),
			ctx = canvas.getContext('2d'),
			imgCmp = me.imgPanel.down('image'),
			field = me.imgPanel.down('textareafield'),
			dataURL;

		canvas.width = me.video.videoWidth / 4;
		canvas.height = me.video.videoHeight / 4;
		ctx.drawImage(me.video, 0, 0, canvas.width, canvas.height);
		dataURL = canvas.toDataURL();
		canvas.remove();
		imgCmp.setSrc(dataURL);
		field.setValue(dataURL);
		me.win.close();
	}
});