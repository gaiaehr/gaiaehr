/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
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
	extend: 'App.ux.window.Window',
	requires:[

	],
	title: i18n('order_window'),
	closeAction: 'hide',
	height: 700,
	width: 1300,
	layout: 'fit',
	bodyStyle: 'background-color:#fff',
	modal: true,

	pid: null,
	eid: null,

	initComponent: function(){
		var me = this;
		me.patientPrescriptionsStore = Ext.create('App.store.patient.Medications', {
			groupField: 'date_ordered',
			sorters: [
				{
					property: 'date_ordered',
					direction: 'DESC'
				}
			]
		});

		me.patientsLabsOrdersStore = Ext.create('App.store.patient.PatientsOrders', {
			groupField: 'date_ordered',
			filters: [
				{
					property: 'order_type',
					value: 'lab'
				}
			],
			sorters: [
				{
					property: 'date_ordered',
					direction: 'DESC'
				}
			]
		});

		me.PatientsXrayCtOrdersStore = Ext.create('App.store.patient.PatientsOrders', {
			groupField: 'date_ordered',
			filters: [
				{
					property: 'order_type',
					value: 'rad'
				}
			],
			sorters: [
				{
					property: 'date_ordered',
					direction: 'DESC'
				}
			]
		});

		me.items = [
			me.tabPanel = Ext.create('Ext.tab.Panel', {
				margin: 5,
				plain: true,
				items: [
				/**
				 * LAB ORDERS PANEL
				 */
					me.labGrid = Ext.widget('grid', {
						title: i18n('lab_orders'),
						action: 'lab',
						store: me.patientsLabsOrdersStore,
						selModel: Ext.create('Ext.selection.CheckboxModel', {
							showHeaderCheckbox: false
						}),
						features: [
							{
								ftype: 'grouping'
							}
						],
						plugins: [
							{
								ptype: 'rowediting',
								clicksToEdit: 2
							}
						],
						columns: [
							{
								xtype: 'actioncolumn',
								width: 20,
								items: [
									{
										icon: 'resources/images/icons/cross.png',
										tooltip: i18n('remove'),
										scope: me,
										handler: me.onRemoveClick
									}
								]
							},
							{
								header: i18n('status'),
								width: 75,
								dataIndex: 'status',
								editor: {
									xtype: 'gaiaehr.combo',
									list: 40
								},
								renderer: me.statusRenderer
							},
							{
								xtype: 'datecolumn',
								header: i18n('date_ordered'),
								width: 100,
								dataIndex: 'date_ordered',
								format: 'Y-m-d',
								editor: {
									xtype: 'datefield'
								}
							},
							{
								header: i18n('code'),
								width: 100,
								dataIndex: 'code'
							},
							{
								header: i18n('description'),
								flex: 1,
								dataIndex: 'description',
								editor: {
									xtype: 'labslivetsearch',
									listeners: {
										scope: me,
										select: me.onLoincSearchSelect
									}
								}
							},
							{
								header: i18n('notes'),
								flex: 1,
								dataIndex: 'note',
								editor: {
									xtype: 'textfield'
								}
							},
							{
								header: i18n('priority'),
								width: 100,
								dataIndex: 'priority',
								editor: {
									xtype: 'gaiaehr.combo',
									list: 98
								}
							},
							{
								xtype: 'datecolumn',
								header: i18n('date_collected'),
								width: 100,
								dataIndex: 'date_collected',
								format: 'Y-m-d',
								editor: {
									xtype: 'datefield'
								}
							}
						],
						tbar: [
							me.eLabBtn = Ext.widget('button', {
								text: i18n('eLab'),
								iconCls: 'icoSend',
								scope: me,
								handler: function(){
									alert('TODO...');
								}
							}),
							'-',
							'->',
							'-',
							{
								xtype: 'button',
								text: i18n('new_order'),
								iconCls: 'icoAdd',
								scope: me,
								action: 'lab',
								itemId: 'encounterRecordAdd',
								handler: me.onAddOrder
							},
							'-',
							me.labPrintBtn = Ext.widget('button', {
								text: i18n('print'),
								iconCls: 'icoPrint',
								disabled: true,
								margin: '0 5 0 0',
								scope: me,
								handler: me.onPrintOrder
							})
						],
						listeners: {
							scope: me,
							selectionchange: me.onSelectionChange
						}
					}),
				/**
				 * X-RAY PANEL
				 */
					me.xGrid = Ext.widget('grid', {
						title: i18n('xray_ct_orders'),
						store: me.PatientsXrayCtOrdersStore,
						action: 'rad',
						selModel: Ext.create('Ext.selection.CheckboxModel', {
							showHeaderCheckbox: false
						}),
						features: [
							{
								ftype: 'grouping'
							}
						],
						plugins: [
							{
								ptype: 'rowediting',
								clicksToEdit: 2
							}
						],
						columns: [
							{
								xtype: 'actioncolumn',
								width: 20,
								items: [
									{
										icon: 'resources/images/icons/cross.png',
										tooltip: i18n('remove'),
										scope: me,
										handler: me.onRemoveClick
									}
								]
							},
							{
								header: i18n('status'),
								width: 75,
								dataIndex: 'status',
								editor: {
									xtype: 'gaiaehr.combo',
									list: 40
								},
								renderer: me.statusRenderer
							},
							{
								xtype: 'datecolumn',
								header: i18n('date_ordered'),
								width: 100,
								dataIndex: 'date_ordered',
								format: 'Y-m-d',
								editor: {
									xtype: 'datefield'
								}
							},
							{
								header: i18n('code'),
								width: 100,
								dataIndex: 'code'
							},
							{
								header: i18n('description'),
								flex: 1,
								dataIndex: 'description',
								editor: {
									xtype: 'radiologylivetsearch',
									listeners: {
										scope: me,
										select: me.onLoincSearchSelect
									}
								}
							},
							{
								header: i18n('notes'),
								flex: 1,
								dataIndex: 'note',
								editor: {
									xtype: 'textfield'
								}
							},
							{
								header: i18n('priority'),
								width: 100,
								dataIndex: 'priority',
								editor: {
									xtype: 'gaiaehr.combo',
									list: 98
								}
							},
							{
								xtype: 'datecolumn',
								header: i18n('date_collected'),
								width: 100,
								dataIndex: 'date_collected',
								format: 'Y-m-d',
								editor: {
									xtype: 'datefield'
								}
							}
						],
						tbar: [
							me.eRadBtn = Ext.widget('button', {
								text: i18n('eRad'),
								iconCls: 'icoSend',
								scope: me,
								handler: function(){
									alert('TODO...');
								}
							}),
							'-',
							'->',
							'-',
							{
								xtype: 'button',
								text: i18n('new_order'),
								iconCls: 'icoAdd',
								scope: me,
								action: 'rad',
								itemId: 'encounterRecordAdd',
								handler: me.onAddOrder
							},
							'-',
							me.radPrintBtn = Ext.widget('button', {
								text: i18n('print'),
								iconCls: 'icoPrint',
								disabled: true,
								margin: '0 5 0 0',
								scope: this,
								handler: me.onPrintOrder
							})
						],
						listeners: {
							scope: me,
							selectionchange: me.onSelectionChange
						}
					}),
				/**
				 * PRESCRIPTION PANEL
				 */
					me.prescriptionsGrid = Ext.widget('grid', {
						title: i18n('rx_orders'),
						border: false,
						action: 'rx',
						store: me.patientPrescriptionsStore,
						selModel: Ext.create('Ext.selection.CheckboxModel', {
							showHeaderCheckbox: false
						}),
						features: [
							{
								ftype: 'grouping'
								//										hideGroupedHeader: true
							}
						],
						plugins: [
							{
								ptype: 'rowediting',
								clicksToEdit: 2
							}
						],
						columns: [
							{
								xtype: 'actioncolumn',
								width: 20,
								items: [
									{
										icon: 'resources/images/icons/cross.png',
										tooltip: i18n('remove'),
										scope: me,
										handler: me.onRemoveClick
									}
								]
							},
							{
								xtype: 'datecolumn',
								header: i18n('date_ordered'),
								dataIndex: 'date_ordered',
								format: globals['date_display_format'],
								editor: {
									xtype: 'datefield',
									format: globals['date_display_format']
								}
							},
							{
								header: i18n('medication'),
								flex: 1,
								dataIndex: 'STR',
								editor: {
									xtype: 'rxnormlivetsearch',
									listeners: {
										scope: me,
										select: me.onRxnormLiveSearchSelect
									}
								}
							},
							{
								header: i18n('dose'),
								width: 125,
								dataIndex: 'dose',
								editor: {
									xtype: 'textfield'
								}
							},
							{
								header: i18n('route'),
								width: 100,
								dataIndex: 'route',
								editor: {
									xtype: 'mitos.prescriptionhowto'
								}
							},
							{
								header: i18n('form'),
								width: 75,
								dataIndex: 'form',
								editor: {
									xtype: 'mitos.prescriptiontypes'
								}
							},
							{
								header: i18n('instructions') + ' (Sig)',
								width: 150,
								dataIndex: 'prescription_when',
								editor: Ext.widget('livesigssearch')
							},
							{
								header: i18n('dispense'),
								width: 60,
								dataIndex: 'dispense',
								editor: {
									xtype: 'numberfield'
								}
							},
							{
								header: i18n('refill'),
								width: 50,
								dataIndex: 'refill',
								editor: {
									xtype: 'numberfield'
								}
							},
							{
								header: i18n('related_dx'),
								width: 150,
								dataIndex: 'ICDS',
								editor: me.encounderIcdsCodes = Ext.widget('encountericdscombo')
							},
							{
								xtype: 'datecolumn',
								format: globals['date_display_format'],
								header: i18n('begin_date'),
								width: 75,
								dataIndex: 'begin_date'
							},
							{
								xtype: 'datecolumn',
								header: i18n('end_date'),
								width: 75,
								format: globals['date_display_format'],
								dataIndex: 'end_date',
								editor: {
									xtype: 'datefield',
									format: globals['date_display_format']
								}
							}

						],
						tbar: [
							'->',
							'-',
							{
								xtype:'button',
								text: i18n('new_order'),
								iconCls: 'icoAdd',
								scope: me,
								action: 'encounterRecordAdd',
								handler: me.onNewPrescription

							},
							'-',
							me.cloneRxBtn = Ext.widget('button', {
								text: i18n('clone_order'),
								iconCls: 'icoAdd',
								disabled: true,
								scope: me,
								margin: '0 5 0 0',
								itemId: 'encounterRecordAdd',
								handler: me.onClonePrescriptions
							}),
							'-',
							me.rxPrintBtn = Ext.widget('button', {
								text: i18n('print'),
								iconCls: 'icoPrint',
								disabled: true,
								scope: me,
								margin: '0 5 0 0',
								handler: me.onPrintOrder
							})
						],
						listeners: {
							scope: me,
							selectionchange: me.onSelectionChange
						}
					}),
				/**
				 * DOCTORS NOTE
				 */
					{
						title: i18n('new_doctors_note'),
						layout: {
							type: 'vbox',
							align: 'stretch'
						},
						items: [
							me.doctorsNoteTplCombo = Ext.widget('mitos.templatescombo', {
								fieldLabel: i18n('template'),
								action: 'template',
								labelWidth: 75,
								margin: '5 5 0 5',
								enableKeyEvents: true,
								listeners: {
									scope: me,
									select: me.onTemplateTypeSelect
								}
							}),
							me.doctorsNoteBody = Ext.widget('htmleditor', {
								name: 'body',
								action: 'body',
								itemId: 'body',
								enableFontSize: false,
								flex: 1,
								margin: '5 5 8 5'
							})
						],
						bbar: [
							'->', {
								text: i18n('create_doctors_notes'),
								scope: me,
								itemId: 'encounterRecordAdd',
								handler: me.onCreateDoctorsNote
							}
						]
					}
				]

			})
		];

		me.buttons = [
			{
				text: i18n('close'),
				scope: me,
				handler: function(){
					me.close();
				}
			}
		];
		/**
		 * windows listeners
		 * @type {{scope: *, show: Function, hide: Function}}
		 */
		me.listeners = {
			scope: me,
			show: me.onWinShow,
			hide: me.onWinHide
		};
		me.callParent(arguments);
	},

	/**
	 * OK!
	 * @param action
	 */
	cardSwitch: function(action){
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
	onClonePrescriptions: function(btn){
		var me = this,
			grid = btn.up('grid'),
			sm = grid.getSelectionModel(),
			store = grid.getStore(),
			records = sm.getSelection(),
			newDate = new Date(),
			data;

		Ext.Msg.show({
			title: 'Wait!',
			msg: 'Are you sure you want to clone this prescription?',
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn: function(btn){
				if(btn == 'yes'){
					grid.editingPlugin.cancelEdit();
					sm.deselectAll();
					for(var i = 0; i < records.length; i++){
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
						success: function(){
							app.msg(i18n('sweet'), i18n('record_added'));
						},
						failure: function(){
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
	onRemoveClick: function(grid, rowIndex){
		var store = grid.getStore(),
			record = store.getAt(rowIndex),
			elapsedTime;
		if(grid.editingPlugin) grid.editingPlugin.cancelEdit();

		elapsedTime = new Date().getTime() - record.data.date_ordered.getTime();

		if(elapsedTime > (24 * 60 * 60 * 1000)){
			app.msg(i18n('oops'), i18n('record_error') + ', ' + i18n('time_constrain_of_one_day'), true);
			return;
		}

		if(record.data.uid != app.user.id){
			User.getUserFullNameById(record.data.uid, function(provider, response){
				app.msg(i18n('oops'), i18n('record_error') + ', ' + i18n('same_user_constrain') + ' - ' + response.result, true);
			});
			return;
		}

		Ext.Msg.show({
			title: i18n('wait'),
			msg: i18n('delete_this_record'),
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn: function(btn){
				if(btn == 'yes'){
					store.remove(record);
					store.sync({
						success: function(){
							app.msg(i18n('sweet'), i18n('record_removed'));
						},
						failure: function(){
							app.msg(i18n('oops'), i18n('record_error'), true);
						}
					});
				}
			}
		});
	},

	/**
	 * OK!
	 * Adds a prescription to the encounter
	 * @param btn
	 */
	onNewPrescription: function(btn){
		var me = this,
			grid = btn.up('grid');

		grid.editingPlugin.cancelEdit();

		me.patientPrescriptionsStore.insert(0, {
			pid: me.pid,
			eid: me.eid,
			uid: app.user.id,
			refill: 0,
			date_ordered: new Date(),
			begin_date: new Date(),
			created_date: new Date()
		});

		grid.editingPlugin.startEdit(0, 0);
	},

	/**
	 * OK!
	 * @param combo
	 * @param record
	 */
	onRxnormLiveSearchSelect: function(combo, record){
		var form = combo.up('form').getForm();

		Rxnorm.getMedicationAttributesByCODE(record[0].data.CODE, function(provider, response){
			form.setValues({
				RXCUI: record[0].data.RXCUI,
				CODE: record[0].data.CODE,
				STR: record[0].data.STR.split(',')[0],
				route: response.result.DRT,
				dose: response.result.DST,
				form: response.result.DDF
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
	onTemplateTypeSelect: function(combo, record){
		combo.up('panel').getComponent('body').setValue(record[0].data.body);
	},

	/**
	 * OK!
	 * On doctors note create
	 */
	onCreateDoctorsNote: function(){
		var me = this,
			params = {
				pid: eval(me.pid),
				eid: eval(me.eid),
				templateId: eval(me.doctorsNoteTplCombo.getValue()),
				docType: 'DoctorsNotes',
				body: me.doctorsNoteBody.getValue()
			};

		DocumentHandler.createDocument(params, function(provider, response){
			app.msg('Sweet!', 'Document Created');
			say(response.result);
			this.close();
		});
	},

	/**
	 *
	 * @param btn
	 */
	onPrintOrder: function(btn){

		var me = this,
			grid = btn.up('grid'),
			items = grid.getSelectionModel().getSelection(),
			params = {},
			data,
			i;

		params.pid = me.pid;
		params.eid = me.eid;
		params.orderItems = [ ];
		params.docType = grid.action;

		if(params.docType == 'rx'){
			params.templateId = 5;
			params.orderItems.push(['Description', 'Instructions', 'Dispense', 'Refill', 'Dx']);
			for(i = 0; i < items.length; i++){
				data = items[i].data;
				params.orderItems.push([
					data.STR + ' [' + data.RXCUI + '] ' + data.dose + ' ' + data.route + ' ' + data.form,
					data.prescription_when,
					data.dispense,
					data.refill,
					data.ICDS
				]);
			}
		}else if(params.docType == 'rad'){
			params.templateId = 6;
			params.orderItems.push(['Description', 'Notes']);
			for(i = 0; i < items.length; i++){
				data = items[i].data;
				params.orderItems.push([
					data.description + ' [' + data.code_type + ':' + data.code + ']',
					data.note
				]);
			}
		}else if(params.docType == 'lab'){
			params.templateId = 4;
			params.orderItems.push(['Description', 'Notes']);
			for(i = 0; i < items.length; i++){
				data = items[i].data;

				params.orderItems.push([
					data.description + ' [' + data.code_type + ':' + data.code + ']',
					data.note
				]);
			}
		}

		DocumentHandler.createDocument(params, function(provider, response){
			app.onDocumentView(response.result.doc.id);
		});
	},

	/**
	 *
	 * @param sm
	 * @param selected
	 */
	onSelectionChange: function(sm, selected){
		var grid = sm.views[0].panel;
		this[grid.action + 'PrintBtn'].setDisabled(selected.length == 0);

		if(grid.action == 'rx'){
			this.cloneRxBtn.setDisabled(selected.length == 0);
			//this.eRxBtn.setDisabled(selected.length == 0);
		}
	},

	/**
	 *
	 * @param btn
	 */
	onAddOrder: function(btn){
		var me = this,
			grid = btn.up('grid'),
			store = grid.getStore();
		grid.editingPlugin.cancelEdit();
		store.insert(0, {
			pid: me.pid,
			eid: me.eid,
			uid: app.user.id,
			date_ordered: new Date(),
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
	onLoincSearchSelect: function(cmb, records){
		var form = cmb.up('form').getForm();
		form.getRecord().set({code: records[0].data.loinc_number});
		form.findField('code').setValue(records[0].data.loinc_number);
		form.findField('note').focus(false, 200);
	},

	/**
	 *
	 * @param v
	 * @returns {string}
	 */
	statusRenderer: function(v){
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
		return '<div style="color:' + color + '">' + v + '</div>';
	},

	/**
	 * OK!
	 * On window shows
	 */
	onWinShow: function(){
		var me = this,
			dock,
			visible;
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
		me.setTitle(app.patient.name + ' - ' + i18n('orders') + (app.patient.readOnly ? ' - <span style="color:red">[' + i18n('read_mode') + ']</span>' : ''));
		me.setReadOnly(app.patient.readOnly);

		/**
		 * Prescription stuff
		 */
		me.patientPrescriptionsStore.load({
			filters: [
				{
					property: 'pid',
					value: me.pid
				}
			]
		});
		/**
		 * Lab stuff
		 */
		me.patientsLabsOrdersStore.load({
			filters: [
				{
					property: 'pid',
					value: me.pid
				}
			]
		});
		me.PatientsXrayCtOrdersStore.load({
			filters: [
				{
					property: 'pid',
					value: me.pid
				}
			]
		});
		/**
		 * Doctors Notes stuff
		 */
		me.doctorsNoteBody.reset();
		me.doctorsNoteTplCombo.reset();
		/**
		 * This will hide encounter panels and
		 * switch to notes panel if eid is null
		 */
		dock = this.tabPanel.getDockedItems()[0];
		visible = this.eid != null;
		dock.items.items[0].setVisible(visible);
		dock.items.items[1].setVisible(visible);
		dock.items.items[2].setVisible(visible);
		if(visible) me.encounderIcdsCodes.getStore().load({params: {eid: me.eid}});
		if(!visible) me.cardSwitch('notes');
	},

	/**
	 * OK!
	 * Loads patientDocumentsStore with new documents
	 */
	onWinHide: function(){
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
		/**
		 * clear grid stores
		 */
		me.patientPrescriptionsStore.removeAll();
		me.patientsLabsOrdersStore.removeAll();
		me.PatientsXrayCtOrdersStore.removeAll();
	}

});