Ext.define('App.controller.patient.Results', {
	extend: 'Ext.app.Controller',
	refs: [
		{
			ref: 'resultsPanel',
			selector: 'patientresultspanel'
		},
		{
			ref: 'ordersGrid',
			selector: 'patientresultspanel > grid[action=orders]'
		},
		{
			ref: 'resultsGrid',
			selector: 'patientresultspanel > grid[action=results]'
		}
	],

	init: function() {
		var me = this;
		me.control({
			'patientresultspanel > grid[action=orders]': {
				selectionchange: me.onOrderSelectionChange
			},
			'patientresultspanel > grid[action=results]': {
				render: me.onResultGridRender
			}
		});
	},

	setResultPanel:function(){
		var me = this,
			ordersStore = me.getOrdersGrid().getStore();

		if(app.patient){
			ordersStore.clearFilter(true);
			ordersStore.filter([
				{
					property: 'pid',
					value: app.patient.pid
				}
			]);
		}else{
			ordersStore.clearFilter(true);
			ordersStore.load();
		}
	},

	onOrderSelectionChange: function(model, record){
		var me = this,
			store = me.getResultsGrid().getStore();

		store.clearFilter(true);
		store.load({
			filters:[
				{
					property: 'order_id',
					value: record.data.id
				}
			]
		});
	},

	onResultGridRender:function(grid){
//		grid.getHeader().insert(1,{
//			xtype:'button',
//			text:i18n('view_document')
//		});
	},







	onLaboratoryPreviewRender: function(panel){
		var me = this;
		panel.dockedItems.items[0].add({
			xtype: 'button',
			text: i18n('upload'),
			disabled: true,
			action: 'uploadBtn',
			scope: me,
			handler: me.onLabUploadWind
		});
	},

	onLabUploadWind: function(){
		var me = this, previewPanel = me.query('[action="labPreviewPanel"]')[0];
		me.uploadWin.show();
		me.uploadWin.alignTo(previewPanel.el.dom, 'tr-tr', [-5, 35])
	},

	onLabUpload: function(btn){
		var me = this, formPanel = me.uploadWin.down('form'), form = formPanel.getForm(), win = btn.up('window');
		if(form.isValid()){
			formPanel.el.mask(i18n('uploading_laboratory') + '...');
			form.submit({
				//waitMsg: i18n('uploading_laboratory') + '...',
				params: {
					pid: app.patient.pid,
					docType: 'laboratory',
					eid: app.patient.eid
				},
				success: function(fp, o){
					formPanel.el.unmask();
					say(o.result);
					win.close();
					me.getLabDocument(o.result.doc.url);
					me.addNewLabResults(o.result.doc.id);
				},
				failure: function(fp, o){
					formPanel.el.unmask();
					say(o.result);
					win.close();
				}
			});
		}
	},

	onLabResultClick: function(view, model){
		var me = this, form = me.query('[action="patientLabs"]')[0].down('form').getForm();
		if(me.currDocUrl != model.data.document_url){
			form.reset();
			model.data.data.id = model.data.id;
			form.setValues(model.data.data);
			me.getLabDocument(model.data.document_url);
			me.currDocUrl = model.data.document_url;
		}
	},

	onLabResultsSign: function(){
		var me = this, form = me.query('[action="patientLabs"]')[0].down('form').getForm(), dataView = me.query('[action="lalboratoryresultsdataview"]')[0], store = dataView.store, values = form.getValues(), record = dataView.getSelectionModel().getLastSelected();
		if(form.isValid()){
			if(values.id){
				me.passwordVerificationWin(function(btn, password){
					if(btn == 'ok'){
						User.verifyUserPass(password, function(provider, response){
							if(response.result){
								say(record);
								Medical.signPatientLabsResultById(record.data.id, function(provider, response){
									store.load({
										params: {
											parent_id: me.currLabPanelId
										}
									});
								});
							} else{
								Ext.Msg.show({
									title: 'Oops!',
									msg: i18n('incorrect_password'),
									//buttons:Ext.Msg.OKCANCEL,
									buttons: Ext.Msg.OK,
									icon: Ext.Msg.ERROR,
									fn: function(btn){
										if(btn == 'ok'){
											//me.onLabResultsSign();
										}
									}
								});
							}
						});
					}
				});
			} else{
				Ext.Msg.show({
					title: 'Oops!',
					msg: i18n('nothing_to_sign'),
					//buttons:Ext.Msg.OKCANCEL,
					buttons: Ext.Msg.OK,
					icon: Ext.Msg.ERROR,
					fn: function(btn){
						if(btn == 'ok'){
							//me.onLabResultsSign();
						}
					}
				});
			}
		}
	},

	onLabResultsSave: function(btn){
		var me = this, form = btn.up('form').getForm(), dataView = me.query('[action="lalboratoryresultsdataview"]')[0], store = dataView.store, values = form.getValues(), record = dataView.getSelectionModel().getLastSelected();
		if(form.isValid()){
			Medical.updatePatientLabsResult(values, function(){
				store.load({params: {parent_id: record.data.parent_id}});
				form.reset();
			});
		}
	},


	addNewLabResults: function(docId){
		var me = this, dataView = me.query('[action="lalboratoryresultsdataview"]')[0], store = dataView.store, params = {
			parent_id: me.currLabPanelId,
			document_id: docId
		};
		Medical.addPatientLabsResult(params, function(provider, response){
			store.load({
				params: {
					parent_id: me.currLabPanelId
				}
			});
		});
	},

	onLabResultsReset: function(btn){
		var form = btn.up('form').getForm();
		form.reset();
	},

	getLabDocument: function(src){
		var panel = this.query('[action="labPreviewPanel"]')[0];
		panel.remove(this.doc);
		panel.add(this.doc = Ext.create('App.ux.ManagedIframe', {
			src: src
		}));
	},

	removeLabDocument: function(src){
		var panel = this.query('[action="labPreviewPanel"]')[0];
		panel.remove(this.doc);
	}

});