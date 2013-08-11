/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.view.patient.windows.NewDocuments', {
	extend:'App.ux.window.Window',
	title:i18n('order_window'),
	closeAction:'hide',
	height:750,
	width:1200,
	layout:'fit',
	bodyStyle:'background-color:#fff',
	modal:true,
	pid:null,
	eid:null,
	initComponent:function(){
		var me = this;
		me.patientPrescriptionsStore = Ext.create('App.store.patient.Medications',{
			groupField: 'date_ordered'
		});

		me.patientsLabsOrdersStore = Ext.create('App.store.patient.PatientsOrders',{
			groupField: 'date_ordered',
			filters:[
				{
					property:'order_type',
					value:'lab'
				}
			]
		});

		me.PatientsXrayCtOrdersStore = Ext.create('App.store.patient.PatientsOrders',{
			groupField: 'date_ordered',
			filters:[
				{
					property:'order_type',
					value:'rad'
				}
			]
		});


		me.items = [
			me.tabPanel = Ext.create('Ext.tab.Panel', {
				margin:5,
				plain:true,
				items:[
					/**
					 * LAB ORDERS PANEL
					 */
					{
						title:i18n('lab_orders'),
						layout:'fit',
						items:[
							me.labGrid = Ext.widget('grid',{
								title:' ',
								border:false,
								action:'lab',
								store:me.patientsLabsOrdersStore,
								features: [
									{
										ftype:'grouping'
									}
								],
								plugins:[
									{
										ptype:'rowediting',
										clicksToEdit: 2
									}
								],
								columns:[
									{
										header:i18n('status'),
										width:100,
										dataIndex:'status',
										editor:{
											xtype:'gaiaehr.combo',
											list:40
										},
										renderer:me.statusRenderer
									},
									{
										xtype: 'datecolumn',
										header:i18n('date_ordered'),
										width:100,
										dataIndex:'date_ordered',
										format:'Y-m-d',
										editor:{
											xtype:'datefield'
										}
									},
									{
										header:i18n('code'),
										width:100,
										dataIndex:'code'
									},
									{
										header:i18n('description'),
										flex:1,
										dataIndex:'description',
										editor:{
											xtype: 'labslivetsearch',
											listeners:{
												scope:me,
												select:me.onLoincSearchSelect
											}
										}
									},
									{
										header:i18n('notes'),
										flex:1,
										dataIndex:'note',
										editor:{
											xtype:'textfield'
										}
									},
									{
										header:i18n('priority'),
										width:100,
										dataIndex:'priority',
										editor:{
											xtype:'gaiaehr.combo',
											list:98
										}
									},
									{
										xtype: 'datecolumn',
										header:i18n('date_collected'),
										width:100,
										dataIndex:'date_collected',
										format:'Y-m-d',
										editor:{
											xtype:'datefield'
										}
									}
								],
								listeners:{
									scope:me,
									render:me.onLabGridRender
								}
							})
						]
					},
					/**
					 * X-RAY PANEL
					 */
					{
						title:i18n('xray_ct_orders'),
						layout:'fit',
						items:[
							me.xGrid = Ext.widget('grid',{
								title:' ',
								border:false,
								store:me.PatientsXrayCtOrdersStore,
								action:'rad',
								features: [
									{
										ftype:'grouping'
									}
								],
								plugins:[
									{
										ptype:'rowediting',
										clicksToEdit: 2
									}
								],
								columns:[
									{
										header:i18n('status'),
										width:100,
										dataIndex:'status',
										editor:{
											xtype:'gaiaehr.combo',
											list:40
										},
										renderer:me.statusRenderer
									},
									{
										xtype: 'datecolumn',
										header:i18n('date_ordered'),
										width:100,
										dataIndex:'date_ordered',
										format:'Y-m-d',
										editor:{
											xtype:'datefield'
										}
									},
									{
										header:i18n('code'),
										width:100,
										dataIndex:'code'
									},
									{
										header:i18n('description'),
										flex:1,
										dataIndex:'description',
										editor:{
											xtype: 'radiologylivetsearch',
											listeners:{
												scope:me,
												select:me.onLoincSearchSelect
											}
										}
									},
									{
										header:i18n('notes'),
										flex:1,
										dataIndex:'note',
										editor:{
											xtype:'textfield'
										}
									},
									{
										header:i18n('priority'),
										width:100,
										dataIndex:'priority',
										editor:{
											xtype:'gaiaehr.combo',
											list:98
										}
									},
									{
										xtype: 'datecolumn',
										header:i18n('date_collected'),
										width:100,
										dataIndex:'date_collected',
										format:'Y-m-d',
										editor:{
											xtype:'datefield'
										}
									}
								],
								listeners:{
									scope:me,
									render:me.onLabGridRender
								}

							})
						]
					},
					/**
					 * NEW PRESCRIPTION PANEL
					 */
					{
						title:i18n('prescriptions'),
						layout:'fit',
						items:[
							/**
							 * Prescription Grid
							 */
							me.prescriptionsGrid = Ext.widget('grid',{
								title:' ',
								border:false,
								action:'rx',
								store:me.patientPrescriptionsStore,
								selModel: me.prescriptionsGridSM = Ext.create('Ext.selection.CheckboxModel',{
									showHeaderCheckbox:false
								}),
								features: [
									{
										ftype:'grouping'
									}
								],
								plugins:[
									{
										ptype:'rowediting',
										clicksToEdit: 2
									}
								],
								columns:[
									{
										xtype:'actioncolumn',
										width:20,
										items:[
											{
												icon:'resources/images/icons/cross.png',
												tooltip:i18n('remove'),
												scope:me,
												handler:me.onRemoveClick
											}
										]
									},
									{
										header:i18n('medication'),
										flex:1,
										dataIndex:'STR',
										sortable:false,
										hideable: false,
										editor:{
											xtype:'rxnormlivetsearch',
											listeners:{
												scope:me,
												select: me.onRxnormLiveSearchSelect
											}
										}
									},
									{
										header:i18n('dose'),
										width:125,
										dataIndex:'dose',
										sortable:false,
										hideable: false,
										editor:{
											xtype:'textfield'
										}
									},
									{
										header:i18n('route'),
										width:100,
										dataIndex:'route',
										sortable:false,
										hideable: false,
										editor:{
											xtype:'mitos.prescriptionhowto'
										}
									},
									{
										header:i18n('form'),
										width:75,
										dataIndex:'form',
										sortable:false,
										hideable: false,
										editor:{
											xtype:'mitos.prescriptiontypes'
										}
									},
									{
										header:i18n('instructions'),
										width:100,
										dataIndex:'prescription_when',
										sortable:false,
										hideable: false,
										editor:Ext.widget('livesigssearch')
									},
									{
										header:i18n('refill'),
										width:50,
										dataIndex:'refill',
										sortable:false,
										hideable: false,
										editor:{
											xtype:'numberfield'
										}
									},
									{
										header:i18n('related_dx'),
										width:150,
										dataIndex:'ICDS',
										sortable:false,
										hideable: false,
										editor:me.encounderIcdsCodes = Ext.widget('encountericdscombo')
									},
									{
										xtype:'datecolumn',
										format:globals['date_display_format'],
										header:i18n('begin_date'),
										width:75,
										dataIndex:'begin_date',
										sortable:false,
										hideable: false
									},
									{
										xtype:'datecolumn',
										header:i18n('end_date'),
										width:75,
										format:globals['date_display_format'],
										dataIndex:'end_date',
										sortable:false,
										hideable: false,
										editor:{
											xtype:'datefield',
											format:globals['date_display_format']
										}
									}

								],
								listeners:{
									scope:me,
									render:me.onPrescriptionsGridRender,
									selectionchange:me.onPrescriptionsSelectionChange
								}
							})
						]
					},
					/**
					 * DOCTORS NOTE
					 */
					{
						title:i18n('new_doctors_note'),
						layout:{
							type:'vbox',
							align:'stretch'
						},
						items:[
							me.doctorsNoteTplCombo = Ext.widget('mitos.templatescombo',{
								fieldLabel:i18n('template'),
								action:'template',
								labelWidth:75,
								margin:'5 5 0 5',
								enableKeyEvents:true,
								listeners:{
									scope:me,
									select:me.onTemplateTypeSelect
								}
							}),
							me.doctorsNoteBody = Ext.widget('htmleditor',{
								name:'body',
								action:'body',
								itemId:'body',
								enableFontSize:false,
								flex:1,
								margin:'5 5 8 5'
							})
						],
						bbar:[
							'->', {
								text:i18n('create_doctors_notes'),
								scope:me,
								handler:me.onCreateDoctorsNote
							}
						]
					}
				]

			})
		];

		me.buttons = [
			{
				text:i18n('close'),
				scope:me,
				handler:function(){
					me.close();
				}
			}
		];
		/**
		 * windows listeners
		 * @type {{scope: *, show: Function, hide: Function}}
		 */
		me.listeners = {
			scope:me,
			show:me.onWinShow,
			hide:me.onWinHide
		};
		me.callParent(arguments);
	},

	/**
	 * OK!
	 * @param action
	 */
	cardSwitch:function(action){
		var layout = this.tabPanel.getLayout();
		if(action == 'lab'){
			layout.setActiveItem(0);
		}else if(action == 'xRay'){
			layout.setActiveItem(1);
		}else if(action == 'prescription'){
			layout.setActiveItem(2);
		}else if(action == 'notes'){
			layout.setActiveItem(3);
		}
	},

	/**
	 * OK!
	 * Clone Logic
	 */
	onClonePrescriptions:function(btn){
		var me = this,
			grid = btn.up('grid'),
			sm = grid.getSelectionModel(),
			store = grid.getStore(),
			records = sm.getSelection(),
			newDate = new Date(),
			data;

		Ext.Msg.show({
			title:'Wait!',
			msg: 'Are you sure you want to clone this prescription?',
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn:function(btn){
				if(btn == 'yes'){
					grid.editingPlugin.cancelEdit();
					sm.deselectAll();
					for(var i=0; i < records.length; i++){
						data = Ext.clone(records[i].data);
						data.id = null;
						data.pid = me.pid;
						data.eid = me.eid;
						data.uid = app.user.id;
						data.date_ordered = newDate;
						data.begin_date = newDate;
						data.created_date = newDate;
						store.insert(0, data);
					}
					store.sync({
						success:function(){
							app.msg(i18n('sweet'), i18n('record_added'));
						},
						failure:function(){
							app.msg(i18n('oops'), i18n('record_error'), true);
						}
					});
				}
			}
		});
	},

	/**
	 * OK!
	 * @param grid
	 * @param rowIndex
	 */
	onRemoveClick:function(grid, rowIndex){
		var store = grid.getStore(),
			record = store.getAt(rowIndex);
		if(grid.editingPlugin) grid.editingPlugin.cancelEdit();
		store.remove(record);
	},

	/**
	 * OK!
	 * Adds a prescription to the encounter
	 * @param btn
	 */
	onNewPrescription:function(btn){
		var me = this,
			grid = btn.up('grid');

		grid.editingPlugin.cancelEdit();

		me.patientPrescriptionsStore.insert(0, {
			pid:me.pid,
			eid:me.eid,
			uid:app.user.id,
			refill:0,
			date_ordered:new Date(),
			created_date:new Date()
		});

		grid.editingPlugin.startEdit(0, 0);
	},

	/**
	 * OK!
	 * @param combo
     * @param record
	 */
	onRxnormLiveSearchSelect:function(combo, record){
		var form = combo.up('form').getForm();

		Rxnorm.getMedicationAttributesByCODE(record[0].data.CODE, function(provider, response){
			form.setValues({
				RXCUI:record[0].data.RXCUI,
				CODE:record[0].data.CODE,
				STR:record[0].data.STR.split(',')[0],
				route:response.result.DRT,
				dose:response.result.DST,
				form:response.result.DDF
			});

			form.findField('prescription_when').focus(false, 200);
		});
	},

	/**
	 * OK!
	 * This will set the htmleditor value
	 * @param combo
	 * @param record
	 */
	onTemplateTypeSelect:function(combo, record){
		combo.up('panel').getComponent('body').setValue(record[0].data.body);
	},

	/**
	 * OK!
	 * On doctors note create
	 */
	onCreateDoctorsNote:function(){
		var me = this,
			value = me.doctorsNoteBody.getValue(),
			params = {
				DoctorsNote:value,
				pid:me.pid,
				eid:me.eid,
				docType:'DoctorsNotes'
			};
		DocumentHandler.createDocument(params, function(provider, response){
			app.msg('Sweet!','Document Created');
			say(response.result);
			this.close();
		});
	},



	/**
	 * OK!
	 * Loads patientDocumentsStore with new documents
	 */
	onWinHide:function(){
		var me = this;
		me.pid = null;
		me.eid = null;
		/**
		 * Fire Event
		 */
		me.fireEvent('orderswindowhide', me);
		if(app.getActivePanel().$className == 'App.view.patient.Summary'){
			app.getActivePanel().loadStores();
		}
	},

	/**
	 * OK!
	 * adds the buttons to the header
	 */
	onPrescriptionsGridRender:function(){
		var me = this;
		me.prescriptionsGrid.dockedItems.items[0].add(
			me.printRxBtn = Ext.widget('button',{
				text:i18n('print'),
				iconCls:'icoPrint',
				disabled:true,
				scope:me,
				margin:'0 5 0 0',
				handler:me.onPrintPrescription
			}),
			me.cloneRxBtn = Ext.widget('button',{
				text:i18n('clone_prescription'),
				iconCls:'icoAdd',
				disabled:true,
				scope:me,
				margin:'0 5 0 0',
				handler:me.onClonePrescriptions
			}),
			Ext.widget('button',{
				text:i18n('new_prescription'),
				iconCls:'icoAdd',
				scope:me,
				handler:me.onNewPrescription

			})
		);
	},

	onPrintPrescription:function(){
		say(this.prescriptionsGridSM.getSelection());
	},

	onPrescriptionsSelectionChange:function(grid, selected){
		this.printRxBtn.setDisabled(selected.length == 0);
		this.cloneRxBtn.setDisabled(selected.length == 0);
	},

	//******************************************************************************************
	//******************************************************************************************
	//******************************************************************************************
	//******************************************************************************************
	//******************************************************************************************

	/**
	 *
	 * @param grid
	 * @param rowIndex
	 */
	onDocumentView:function(grid, rowIndex){
		var rec = grid.getStore().getAt(rowIndex),
			src = rec.data.docUrl;
		if(src != '' && typeof src != 'undefined'){
			app.onDocumentView(src);
		}else{
			app.msg('Oops!','No document created yet', true);
		}

	},

	/**
	 *
	 * @param btn
	 */
	onAddOrder:function(btn){
		var me = this,
			grid = btn.up('grid'),
			store = grid.getStore();
		grid.editingPlugin.cancelEdit();
		store.insert(0, {
			pid:me.pid,
			eid:me.eid,
			uid:app.user.id,
			date_ordered:new Date(),
			order_type: btn.action,
			status: 'Pending',
			priority: 'Normal'
		});
		grid.editingPlugin.startEdit(0, 0);
	},

	/**
	 *
	 * @param cmb
	 * @param records
	 */
	onLoincSearchSelect:function(cmb, records){
		var form = cmb.up('form').getForm();
		form.getRecord().set({code:records[0].data.loinc_number});
		form.findField('note').focus(false, 200);
	},

	/**
	 *
	 * @param grid
	 */
	onLabGridRender:function(grid){
		var me = this;
		grid.dockedItems.items[0].add({
			xtype:'button',
			text:i18n('new_order'),
			iconCls:'icoAdd',
			scope:me,
			action: grid.action,
			handler:me.onAddOrder
		});
	},

	/**
	 *
	 * @param v
	 * @returns {string}
	 */
	statusRenderer:function(v){
		var color = 'black';
		if(v == 'Canceled'){
			color = 'red';
		}else if(v == 'Pending'){
			color = 'orange';
		}else if(v == 'Routed'){
			color = 'blue';
		}else if(v == 'Complete'){
			color = 'green';
		}
		return '<div style="color:'+color+'">'+v+'</div>';
	},

	/**
	 * OK!
	 * On window shows
	 */
	onWinShow:function(){
		var me = this, dock, visible;
		/**
		 * Fire Event
		 */
		me.fireEvent('orderswindowhide', me);
		/**
		 * set current patient data to panel
		 */
		me.pid = app.patient.pid;
		me.eid = app.patient.eid;
		/**
		 * read only stuff
		 */
		me.setTitle(app.patient.name + (app.patient.readOnly ? ' - <span style="color:red">[' + i18n('read_mode') + ']</span>' : ''));
		me.setReadOnly(app.patient.readOnly);

		/**
		 * Prescription stuff
		 */
		me.patientPrescriptionsStore.load({
			filters:[
				{
					property:'pid',
					value:me.pid
				}
			]
		});
		/**
		 * Lab stuff
		 */
		me.patientsLabsOrdersStore.load({params:{pid:app.patient.pid}});
		me.PatientsXrayCtOrdersStore.load({params:{pid:app.patient.pid}});
		/**
		 * Doctors Notes stuff
		 */
		me.doctorsNoteBody.reset();
		me.doctorsNoteTplCombo.reset();
		/**
		 * This will hide encounter panels and
		 * switch to notes panel if eid is null
		 */
		dock    = this.tabPanel.getDockedItems()[0];
		visible = this.eid != null;
		dock.items.items[0].setVisible(visible);
		dock.items.items[1].setVisible(visible);
		dock.items.items[2].setVisible(visible);
		if(visible) me.encounderIcdsCodes.getStore().load({params:{eid:me.eid}});
		if(!visible) me.cardSwitch('notes');
	}

});