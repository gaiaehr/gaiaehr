Ext.define('App.controller.patient.encounter.EncounterSign', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'EncounterSignWindow',
			selector: '#EncounterSignWindow'
		},
		{
			ref: 'EncounterCoSignSupervisorCombo',
			selector: '#EncounterCoSignSupervisorCombo'
		},
		{
			ref: 'EncounterCoSignSupervisorBtn',
			selector: '#EncounterCoSignSupervisorBtn'
		},
		{
			ref: 'EncounterCancelSignBtn',
			selector: '#EncounterCancelSignBtn'
		},
		{
			ref: 'EncounterSignBtn',
			selector: '#EncounterSignBtn'
		},
		{
			ref: 'EncounterSignDocumentGrid',
			selector: '#EncounterSignDocumentGrid'
		},
		{
			ref: 'EncounterSignAlertGrid',
			selector: '#EncounterSignAlertGrid'
		},

		// super bill stuff
		{
			ref: 'EncounterSignSuperBillGrid',
			selector: '#EncounterSignSuperBillGrid'
		},
		{
			ref: 'EncounterSignSuperBillServiceAddBtn',
			selector: '#EncounterSignSuperBillServiceAddBtn'
		}


	],

	init: function(){
		var me = this;

		this.control({
			'#EncounterSignWindow': {
				show: me.onEncounterSignWindowShow
			},
			'#EncounterCoSignSupervisorCombo': {
				beforerender: me.onEncounterCoSignSupervisorComboBeforeRender
			},
			// Buttons
			'#EncounterCoSignSupervisorBtn': {
				click: me.onEncounterCoSignSupervisorBtnClick
			},
			'#EncounterSignBtn': {
				click: me.onEncounterSignBtnClick
			},
			'#EncounterCancelSignBtn': {
				click: me.onEncounterCancelSignBtnClick
			},
			// super bill stuff
			'#EncounterSignSuperBillServiceAddBtn': {
				click: me.onEncounterSignSuperBillServiceAddBtnClick
			},
			'#EncounterSignSuperCptSearchCmb': {
				select: me.onEncounterSignSuperCptSearchCmbSelect
			}
		});
	},

	// super bill stuff
	onEncounterSignSuperBillServiceAddBtnClick: function(){
		var me = this,
			grid = me.getEncounterSignSuperBillGrid(),
			store = grid.getStore();

		grid.editingPlugin.cancelEdit();
		var records = store.add({
			pid: me.encounter.data.pid,
			eid: me.encounter.data.eid,
			units: 1,
			create_uid: app.user.id,
			date_create: new Date()
		});
		grid.editingPlugin.startEdit(records[0], 0);
	},

	onEncounterSignSuperCptSearchCmbSelect: function(cmb, records){
		var me = this,
			record = cmb.up('form').getForm().getRecord();

		record.set({
			code: records[0].data.code,
			code_type: records[0].data.code_type
		});

	},


	onEncounterCoSignSupervisorBtnClick: function(){
		this.coSignEncounter();
	},

	onEncounterSignBtnClick: function(){
		this.signEncounter();
	},

	onEncounterCancelSignBtnClick: function(){
		this.cancelCheckout();
	},

	coSignEncounter: function(){
		this.getEncounterSignWindow().enc.doSignEncounter(true);
	},

	signEncounter: function(){
		if(a('require_enc_supervisor')){
			if(this.getEncounterCoSignSupervisorCombo().isValid()){
				this.getEncounterSignWindow().enc.doSignEncounter(false);
			}
		}else{
			this.getEncounterSignWindow().enc.doSignEncounter(false);
		}
	},

	cancelCheckout: function(){
		this.getEncounterSignWindow().close();
		this.getEncounterSignWindow().down('form').getForm().reset();
	},

	onEncounterCoSignSupervisorComboBeforeRender: function(cmb){
		cmb.getStore().load();
	},

	onEncounterSignWindowShow: function(){
		var me = this,
			win = me.getEncounterSignWindow(),
			coSignCombo = me.getEncounterCoSignSupervisorCombo(),
			coSignBtn = me.getEncounterCoSignSupervisorBtn(),
			signBtn = me.getEncounterSignBtn();

		me.encounter = win.enc.encounter;

		me.pid = win.enc.pid;
		me.eid = win.enc.eid;

		if(a('access_encounter_checkout')){

			me.getEncounterSignSuperBillGrid().getStore().load({
				filters:[
					{
						property: 'eid',
						value: me.eid
					}
				]
			});

			me.getEncounterSignAlertGrid().getStore().load({
				params: {
					eid: me.eid
				}
			});
		}

		me.getEncounterSignDocumentGrid().loadDocs(app.patient.eid);

		var isCoSigned = win.enc.encounter.data.supervisor_uid != null && win.enc.encounter.data.supervisor_uid > 0;

		if(isCoSigned){
			coSignCombo.setValue(win.enc.encounter.data.supervisor_uid);
		}else{
			coSignCombo.reset();
		}

		if(win.enc.isClose() || !a('sign_enc')){
			signBtn.disable();
			coSignBtn.disable();
			coSignCombo.setVisible(isCoSigned);
			coSignCombo.disable();
		}else{
			// not previously signed and required supervisor
			if(!isCoSigned && a('require_enc_supervisor')){
				signBtn.enable();
				coSignBtn.disable();
				coSignCombo.show();
				coSignCombo.enable();

				// previously signed and supervisor
			}else if(isCoSigned && a('sign_enc_supervisor')){
				signBtn.disable();
				coSignBtn.enable();
				coSignCombo.show();
				coSignCombo.enable();
				// not previously and
			}else{
				signBtn.enable();
				coSignBtn.disable();
				coSignCombo.hide();
				coSignCombo.disable();
			}
		}
	},

	alertIconRenderer: function(v){
		if(v == 1){
			return '<img src="resources/images/icons/icoLessImportant.png" />'
		}else if(v == 2){
			return '<img src="resources/images/icons/icoImportant.png" />'
		}
		return v;
	},

	onRemoveService: function(grid, rowIndex, colIndex, item, e, record){
		var me = this;

		//TODO: handle the remove logic

	}


});