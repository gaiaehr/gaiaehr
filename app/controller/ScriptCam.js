Ext.define('App.controller.ScriptCam', {
    extend: 'Ext.app.Controller',

	refs: [
        {
            ref:'webCamWindow',
            selector:'scriptcamwindow'
        },
        {
            ref:'ScriptCamCameras',
            selector:'combobox[action=ScriptCamCameras]'
        }
//        {
//            ref:'photoIdImage',
//            selector:'image[action=photoIdImage]'
//        }
	],

	init: function() {
		var me = this;

		Ext.define('App.ux.ScriptCamWindow',{
			extend:'Ext.window.Window',
			alias:'widget.scriptcamwindow',
			title:'...',
			items:[
				{
					xtype:'container',
					id:'WebCamCanvas',
					height:320,
					width:320

				}
			],
			buttons:[
				{
					xtype:'combobox',
					action:'ScriptCamCameras',
					store:Ext.create('Ext.data.Store',{
						fields: [ 'option', 'value' ]
					}),
					queryMode: 'local',
					displayField: 'option',
					valueField: 'value',
					editable:false
				},
				'->',
				{
					text:i18n('capture_img'),
					action:'onCaptureImage'
				}
			]
		});


        me.control({
            'scriptcamwindow':{
                render:me.onWebCamWindowRender
            },
            'image':{
                render:me.onImageRender
            },
            'combobox[action=ScriptCamCameras]':{
                select:me.onWebCamCamerasSelect
            },
            'button[action=onWebCam]':{
                click:me.onWebCamClick
            },
            'button[action=onCaptureImage]':{
                click:me.onCaptureImageClick
            }
        })
	},

    onImageRender:function(img){
        img.el.on('click', function(e,el){
            Ext.widget('window',{
                html:'<img src="' + img.src + '">'
            }).show();
        });
    },

    onWebCamCamerasSelect:function(cmb, records){
        $.scriptcam.changeCamera(records[0].data.value);
    },

    onWebCamClick:function(btn){

        var action = btn.up('panel').action,
	        win = Ext.widget('scriptcamwindow',{
		        action: action,
                imgPanel:btn.up('panel')
            });

	    win.down('container').setSize({
            width: action == 'patientImage' ? 320 : 640,
            height: action == 'patientImage' ? 320 : 360
        });

        win.show();
    },

    onWebCamWindowRender:function(win){
        var me = this;

        Ext.Function.defer(function(){
            $('#WebCamCanvas').scriptcam({
                showMicrophoneErrors:false,
                onError:me.onError,
                cornerRadius:0,
                width: win.action == 'patientImage' ? 320 : 640,
                height: win.action == 'patientImage' ? 320 : 360,
                onWebcamReady:function (cameraNames, camera){
                    me.onScriptCamReady(cameraNames, camera, me);
                },
	            onPictureAsBase64:function(b64){
                    me.onPictureAsBase64(b64, win);
                },
                path:'lib/ScriptCam/',
                uploadImage:'resources/images/upload.png'
            });
        }, 200);

    },

    onCaptureImageClick:function(btn){
        var me = this,
            win =  me.getWebCamWindow(),
	        panel = btn.up('panel').imgPanel,
            imgCmp = panel.down('image'),
            field = panel.down('textareafield'),
	        b64 = $.scriptcam.getFrameAsBase64();

        imgCmp.setSrc('data:image/jpg;base64,' + b64);
        field.setValue(b64);
        win.close();
    },

	onPictureAsBase64: function (b64, win) {
        var panel = win.imgPanel,
	        imgCmp = panel.down('image'),
	        field = panel.down('textareafield');

        imgCmp.setSrc('data:image/jpg;base64,' + b64);
        field.setValue(b64);
        win.close();
    },

    changeCamera: function () {
        $.scriptcam.changeCamera($('#cameraNames').val());
    },

    onError: function onError(errorId,errorMsg) {
        $( "#btn1" ).attr( "disabled", true );
        $( "#btn2" ).attr( "disabled", true );
        alert(errorMsg);
    },

    onScriptCamReady:function (cameraNames,camera,me) {
        var cmb = me.getScriptCamCameras(),
            store = cmb.getStore(),
            data = [];

	    $.each(cameraNames, function(index, text) {
            data.push({
                option:text,
                value:index
            });
        });

        store.loadData(data);
        me.getScriptCamCameras().setValue(camera);
    }
});