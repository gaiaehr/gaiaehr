
Ext.define('App.ux.ScriptCamWindow',{
	extend:'Ext.window.Window',
	alias:'widget.webcamwindow',
//    closeAction:'hide',
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
            action:'webCamCameras',
            store:Ext.create('App.store.WebCamCameras'),
            queryMode: 'local',
            displayField: 'option',
            valueField: 'value',
            editable:false
        },
        '->',
        {
            text:w('capture_img'),
            action:'onCaptureImage'
        }
    ]
});
