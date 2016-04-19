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

		if(record.data.provider_uid === 0){
			if(me.getEncounterSpecialtyCmb()) me.getEncounterSpecialtyCmb().setVisible(false);

		}else{
			User.getUser(record.data.provider_uid, function(provider){
				me.setSpecialtyCombo(provider, record.data.specialty_id);
			});
		}

	},

	setSpecialtyCombo: function(provider, specialty){
		var show = this.reloadSpecialityCmbBySpecialty(provider.specialty, specialty);
		this.getEncounterSpecialtyCmb().setVisible(show);
		this.getEncounterSpecialtyCmb().setDisabled(!show);
	},

	reloadSpecialityCmbBySpecialty: function(specialties, specialty){
		var me = this,
			show = false;

		if(Ext.isNumeric(specialty) && specialty > 0){
			me.getEncounterSpecialtyCmb().setValue(eval(specialty));

		}else if(Ext.isArray(specialties) && specialties.length == 1){
			me.getEncounterSpecialtyCmb().setValue(eval(specialties[0]));

		}else{
			me.getEncounterSpecialtyCmb().setValue(null);
		}


		if(Ext.isArray(specialties)){

			var store = this.getEncounterSpecialtyCmb().getStore(),
				filters = [],
				show = true;

			for(var i = 0; i < specialties.length; i++){
				Ext.Array.push(filters, specialties[i]);
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
