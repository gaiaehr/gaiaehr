Ext.define('App.controller.Support', {
    extend: 'Ext.app.Controller',
	requires:[
		'App.ux.ManagedIframe'
	],
	refs: [
        {
            ref:'viewport',
            selector:'viewport'
        }
	],

	init: function() {
		var me = this;
		me.control({
			'button[action=supportBtn]':{
				click: me.supportBtnClick
			}
		});

	},

	supportBtnClick: function(btn){
		var me = this;

		me.getSupportWindow();
		me.winSupport.remove(me.miframe);
		me.winSupport.add(
			me.miframe = Ext.create('App.ux.ManagedIframe',{src: btn.src})
		);
	},

	getSupportWindow:function(){
		var me = this;

		if(me.winSupport){
			me.winSupport.show();
		}else{
			me.winSupport = Ext.create('Ext.window.Window', {
				title: _('support'),
				closeAction: 'hide',
				bodyStyle: 'background-color: #ffffff; padding: 5px;',
				animateTarget: me.Footer,
				resizable: false,
				draggable: false,
				maximizable: false,
				autoScroll: true,
				maximized: true,
				dockedItems: {
					xtype: 'toolbar',
					dock: 'top',
					items: ['-', {
						text: 'List issues',
						iconCls: 'list',
						action: 'supportBtn',
						url: 'http://GaiaEHR.org/projects/GaiaEHR001/issues'
					}, '-', {
						text: 'Create an issue',
						iconCls: 'icoAddRecord',
						action: 'supportBtn',
						url: 'http://GaiaEHR.org/projects/GaiaEHR001/issues/new'
					}]
				}
			});
			me.winSupport.show();
		}


	}

});
