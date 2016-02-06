Ext.define('App.controller.Scanner', {
	extend: 'Ext.app.Controller',
	requires: [
		'App.view.scanner.Window'
	],
	refs: [
		{
			ref: 'ScannerWindow',
			selector: '#ScannerWindow'
		},
		{
			ref: 'ScannerImage',
			selector: '#ScannerImage'
		},
		{
			ref: 'ScannerCombo',
			selector: '#ScannerCombo'
		},
		{
			ref: 'ScannerScanBtn',
			selector: '#ScannerScanBtn'
		},
		{
			ref: 'ScannerOkBtn',
			selector: '#ScannerOkBtn'
		}
	],

	/**
	 *
	 */
	ws: null,

	connected: false,

	init: function(){
		var me = this;

		me.control({
			'viewport': {
				afterrender: me.doWebSocketConnect
			},
			'#ScannerWindow': {
				show: me.onScannerWindowShow,
				close: me.onScannerWindowClose
			},
			'#ScannerScanBtn': {
				click: me.onScannerScanBtnClick
			},
			'#ScannerImageEditBtn': {
				toggle: me.onScannerImageEditBtnClick
			},
			'#ScannerOkBtn': {
				click: me.onScannerOkBtnClick
			}
		});
	},

	onScannerScanBtnClick: function(){
		this.doScan();
	},

	doLoadScannersCombo: function(data){
		var combo = this.getScannerCombo(),
			store = combo.getStore(),
            checked;

		store.loadData(data);
		checked = store.findRecord('Checked', 'true');
		if(checked){
			combo.select(checked);
		}
	},

	doLoadScannedDocument: function(data){
		var me = this,
			image = me.getScannerImage();

		image.setSrc('data:image/png;base64,' + data);
		me.getScannerWindow().body.el.unmask();
		me.getScannerWindow().doComponentLayout();
		me.getScannerWindow().down('toolbar').enable();
	},

	getSources: function(){
		var me = this;
		me.ws.send('getSources');
	},

	onScannerWindowShow: function(){
		//this.doWebSocketConnect();
	},

	onScannerWindowClose: function(){
		//this.ws.close();
	},

	doWebSocketConnect: function(){
		var me = this;

		if(me.connected) return;
		me.ws = new WebSocket('wss://localhost:8443/TwainService');

		me.ws.onopen = function(evt){
			me.conencted = true;
			me.getScanWindow();
			me.getSources();
			app.fireEvent('scanconnected', this);
		};

		me.ws.onerror = function(){
			say(_('no_scanner_service_found'));
		};

		me.ws.onmessage = function(evt){
			var response = eval('(' + evt.data + ')');

			if(response.action == 'getSources'){
				me.doLoadScannersCombo(response.data);
			}else if(response.action == 'getDocument'){
				me.doLoadScannedDocument(response.data);
			}
		};

		me.ws.onclose = function(e){
			me.conencted = false;
			app.fireEvent('scandisconnected', this);
		};
	},

	onScannerImageEditBtnClick: function(btn, pressed){
		if(pressed){
			this.dkrm = new Darkroom('#ScannerImage', {
				save: false,
				replaceDom: false
			});
			btn.setText(_('editing'));
		}else{
			this.dkrm.selfDestroy();
			delete this.dkrm;
			btn.setText(_('edit'));
		}

		this.getScannerScanBtn().setDisabled(pressed);
		this.getScannerOkBtn().setDisabled(pressed);
	},

	getDocument: function(){
		return this.getScannerImage().imgEl.dom.src;
	},

	doScan: function(){
		var me = this,
			combo = this.getScannerCombo();

		me.getScannerWindow().down('toolbar').disable();
		me.getScannerWindow().body.el.mask(_('scanning_document'));
		me.ws.send('getDocument:' + combo.getValue());
	},

	onScannerOkBtnClick: function(){
		app.fireEvent('scancompleted', this, this.getDocument());
		this.getScannerWindow().close();
	},

	getScanWindow: function(){
		if(!this.getScannerWindow()){
			Ext.create('App.view.scanner.Window');
		}
		return this.getScannerWindow();
	},

	initScan: function(){
		this.getScanWindow();
		this.getScannerWindow().show();
		//if(this.getScannerCombo().getValue() !== ''){
		//	this.doScan();
		//}
	}
});
