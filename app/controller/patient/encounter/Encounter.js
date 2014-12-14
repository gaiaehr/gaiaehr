Ext.define('App.controller.patient.encounter.Encounter', {
	extend: 'Ext.app.Controller',
	requires: [
		'App.ux.combo.ActiveSpecialties'
	],
	refs: [
		{
			ref: 'EncounterPanel',
			selector: '#encounterPanel'
		},
		{
			ref: 'EncounterDetailWindow',
			selector: '#EncounterDetailWindow'
		},
		{
			ref: 'EncounterProviderCmb',
			selector: '#EncounterProviderCmb'
		},
		{
			ref: 'EncounterSpecialtyCmb',
			selector: '#EncounterSpecialtyCmb'
		},
		{
			ref: 'EncounterDetailForm',
			selector: '#EncounterDetailForm'
		}
	],

	init: function(){
		var me = this;

		this.control({
			'viewport':{
				patientunset: me.onPatientUnset
			},
			'#EncounterDetailWindow': {
				show: me.onEncounterDetailWindowShow
			},
			'#EncounterProviderCmb': {
				beforerender: me.onEncounterProviderCmbBeforeRender,
				select: me.onEncounterProviderCmbSelect
			}
		});
	},

	/**
	 * set the encounter record to null when the patient is closed
	 */
	onPatientUnset:function(){
		if(this.getEncounterPanel()) this.getEncounterPanel().encounter = null;
	},

	/**
	 * get the encounter record form the encounter panel or return null
	 * @returns {*}
	 */
	getEncounterRecord: function(){
		return this.getEncounterPanel() ? this.getEncounterPanel().encounter : null;
	},

	onEncounterProviderCmbBeforeRender: function(cmb){
		var container = cmb.up('container');

		container.setFieldLabel(''); // label showing bug

		container.insert((container.items.indexOf(cmb) + 1), {
			xtype: 'activespecialtiescombo',
			itemId: 'EncounterSpecialtyCmb',
			fieldLabel: _('specialty'),
			labelWidth: cmb.labelWidth,
			width: cmb.width,
			name: 'specialty_id',
			allowBlank: false
		});
	},

	onEncounterProviderCmbSelect: function(cmb, slected){
		var me = this;

		User.getUser(slected[0].data.option_value, function(provider){
			me.setSpecialtyCombo(provider);
		});
	},

	onEncounterDetailWindowShow: function(){
		var me = this,
			record = me.getEncounterDetailForm().getForm().getRecord();

		if(record.data.provider_uid == 0){
			if(me.getEncounterSpecialtyCmb()) me.getEncounterSpecialtyCmb().setVisible(false);
		}else{
			User.getUser(record.data.provider_uid, function(provider){
				me.setSpecialtyCombo(provider);
			});
		}

	},

	setSpecialtyCombo: function(provider){
		var me = this,
			show = false;

		me.getEncounterSpecialtyCmb().setVisible(me.reloadSpecialityCmbBySpecialty(provider.specialty));

	},

	reloadSpecialityCmbBySpecialty: function(specialty){
		var me = this,
			show = false;

		if(Ext.isNumeric(specialty)){

			me.getEncounterSpecialtyCmb().setValue(specialty);

		}else if(Ext.isArray(specialty) && specialty.length == 1){

			me.getEncounterSpecialtyCmb().setValue(specialty[0]);

		}else if(Ext.isArray(specialty)){

			show = true;

			var store = this.getEncounterSpecialtyCmb().getStore(),
				filters = [];

			for(var i = 0; i < specialty.length; i++){
				Ext.Array.push(filters, specialty[i]);
			}

			store.clearFilter(true);
			store.filter([
				{
					property: 'active',
					value: true
				},
				{
					property: 'id',
					value: new RegExp(filters.join('|'))
				}
			]);
		}

		return show;
	}

});