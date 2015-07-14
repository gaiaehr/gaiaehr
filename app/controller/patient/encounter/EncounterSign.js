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
			}
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

			App.app.getController('patient.encounter.SuperBill').reconfigureSupperBillGrid(me.encounter.services());

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
	}

});