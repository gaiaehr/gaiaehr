Ext.define('App.controller.patient.encounter.SuperBill', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		// super bill stuff
		{
			ref: 'SuperBillGrid',
			selector: 'superbillpanel'
		},
		{
			ref: 'SuperBillServiceAddBtn',
			selector: '#SuperBillServiceAddBtn'
		},
		{
			ref: 'SuperBillEncounterDxCombo',
			selector: '#SuperBillEncounterDxCombo'
		}
	],

	init: function(){
		var me = this;

		this.control({
			'viewport': {
				immunizationedit: me.onImmunizationEdit
			},
			'superbillpanel': {
				beforeedit: me.onSuperBillGridBeforeEdit
			},
			'#SuperBillServiceAddBtn': {
				click: me.onSuperBillServiceAddBtnClick
			},
			'#SuperCptSearchCmb': {
				select: me.onSuperCptSearchCmbSelect
			}
		});
	},

	onImmunizationEdit:function(controller, record){
		var serviceRecords = this.getServiceFormEncounterRecord('cvx', record.data.id);

		if(serviceRecords.length == 0){
			this.promptAddService(record, 'cvx');
		}

	},

	promptAddService: function(record, type){
		var me = this;

		Ext.Msg.show({
			title: _('wait'),
			msg: _('super_bill_prompt_add_question'),
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn: function(btn){

				if(btn == 'yes'){
					me.addService(record, type);
				}
			}
		});
	},

	addService: function(record, type){
		var me = this;

		if(type == 'cvx'){
			Immunizations.getCptByCvx(record.data.code, function(services){
				if(services.length == 0){
					app.msg(_('oops'), _('no_service_code_found'), true);
				} else if(services.length == 1){
					me.doAddService(record, type, services[0]);
				}else{

				}
			});
		}

	},

	doAddService: function(record, type, service){
		var store = this.getController('patient.encounter.Encounter').getEncounterRecord().services();

		store.add({
			pid: record.data.pid,
			eid: record.data.eid,
			units: 1,
			reference_type: type,
			reference_id: record.data.id,
			code: service.code,
			code_type: service.code_type,
			code_text: service.code_text,
			create_uid: app.user.id,
			date_create: new Date()
		});

		store.sync({
			callback:function(){
				app.msg(_('sweet'), _('service_added'));
			}
		})

	},



	onSuperBillGridBeforeEdit: function(plugin, context){

		this.getSuperBillEncounterDxCombo().getStore().load({
			filters: [
				{
					property: 'eid',
					value: context.record.data.eid
				}
			]
		});
	},

	// super bill stuff
	onSuperBillServiceAddBtnClick: function(){
		var me = this,
			grid = me.getSuperBillGrid(),
			store = grid.getStore(),
			encounter = me.getController('patient.encounter.EncounterSign').encounter;

		grid.editingPlugin.cancelEdit();
		var records = store.add({
			pid: encounter.data.pid,
			eid: encounter.data.eid,
			units: 1,
			create_uid: app.user.id,
			date_create: new Date()
		});
		grid.editingPlugin.startEdit(records[0], 0);
	},

	onSuperCptSearchCmbSelect: function(cmb, records){
		var record = cmb.up('form').getForm().getRecord();

		record.set({
			code: records[0].data.code,
			code_type: records[0].data.code_type
		});
	},

	onRemoveService: function(grid, rowIndex, colIndex, item, e, record){
		var me = this;

		//TODO: handle the remove logic

	},

	reconfigureSupperBillGrid: function(store){
		this.getSuperBillGrid().reconfigure(store);
	},

	getServiceFormEncounterRecord: function(referenceType, referenceId){
		var encounter = this.getController('patient.encounter.Encounter').getEncounterRecord(),
			services = [];

		if(encounter == null) return services;

		var store = encounter.services(),
			records = store.data.items,
			len = store.data.items.length;

		for(var i = 0; i < len; i++){
			var record = records[i];

			if(record.data.reference_type == referenceType && record.data.reference_id == referenceId){
				Ext.Array.push(services, record);
			}
		}

		return services;
	}



});