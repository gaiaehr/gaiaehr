Ext.define('App.controller.patient.encounter.EncounterDocuments', {
	extend: 'Ext.app.Controller',
	requires: [],
	refs: [],

	init: function(){
		var me = this;

		this.control({
			'#EncounterDocumentsPrintBtn': {
				click: me.onEncounterDocumentsPrintBtnClick
			}
		});
	},

	onEncounterDocumentsPrintBtnClick: function(btn){
		var grid = btn.up('grid'),
			selections = grid.getSelectionModel().getSelection(),
			groups = {};

		for(var i = 0; i < selections.length; i++){
			var data = selections[i].data;

			if(!groups[data.document_type]){
				groups[data.document_type] = {};
				groups[data.document_type]['controller'] = data.controller;
				groups[data.document_type]['method'] = data.method;
				groups[data.document_type]['items'] = [];
			}

			Ext.Array.push(groups[data.document_type]['items'], data.record_id);
		}

		this.doEncounterDocumentsPrint(groups);
	},

	doEncounterDocumentsPrint: function(groups){
		var me = this, store, filters, i;

		Ext.Object.each(groups, function(group, data){

			filters = [];

			if(group.toUpperCase() == 'NOTE'){
				store = Ext.data.StoreManager.lookup('DoctorsNotesStore');

				for(i = 0; i < data.items.length; i++){
					Ext.Array.push(filters, {
						property: 'id',
						value: data.items[i]
					});

					store.load({
						filters: filters,
						callback: function(records){
							me.getController(data.controller)[data.method](records[0]);
						}
					});
				}
			}else{

				if(group.toUpperCase() == 'RX'){
					store = Ext.data.StoreManager.lookup('RxOrderStore');
				}else if(group.toUpperCase() == 'RAD'){
					store = Ext.data.StoreManager.lookup('LabOrderStore');
				}else if(group.toUpperCase() == 'LAB'){
					store = Ext.data.StoreManager.lookup('RadOrderStore');
				}

				for(i = 0; i < data.items.length; i++){
					Ext.Array.push(filters, {
						property: 'id',
						value: data.items[i]
					});
				}

				store.load({
					filters: filters,
					callback: function(records){
						me.getController(data.controller)[data.method](records);
					}
				});
			}
		});
	},

	onDocumentView: function(grid, rowIndex){
		say('onDocumentView');

	},

	loadDocumentsByEid: function(grid, eid){
		var me = this,
			store = grid.getStore();

		store.removeAll();

		Encounter.getEncounterPrintDocumentsByEid(eid, function(results){
			var data = [];

			for(var i = 0; i < results.length; i++){
				var document = results[i];

				if(document.document_type == 'rx'){
					document.controller = 'patient.RxOrders';
					document.method = 'onPrintRxOrderBtnClick';
				}else if(document.document_type == 'rad'){
					document.controller = 'patient.RadOrders';
					document.method = 'onPrintRadOrderBtnClick';
				}else if(document.document_type == 'lab'){
					document.controller = 'patient.LabOrders';
					document.method = 'onPrintLabOrderBtnClick';
				}else if(document.document_type == 'note'){
					document.controller = 'patient.DoctorsNotes';
					document.method = 'onPrintDoctorsNoteBtn';
				}

				document.document_type = Ext.String.capitalize(document.document_type);

				Ext.Array.push(data, document);
			}

			if(data.length > 0){
				store.loadRawData(data);
			}
		});

	}

});