/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/15/12
 * Time: 4:30 PM
 *
 * @namespace Immunization.getImmunizationsList
 * @namespace Immunization.getPatientImmunizations
 * @namespace Immunization.addPatientImmunization
 */
//noinspection JSUnresolvedFunction
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
		me.patientPrescriptionsStore = Ext.create('App.store.patient.PatientsPrescriptions');
		me.prescriptionMedicationsStore = Ext.create('App.store.patient.PatientsPrescriptionMedications');
		me.patientsLabsOrdersStore = Ext.create('App.store.patient.PatientsLabsOrders');
		//noinspection JSUnresolvedFunction,JSUnresolvedVariable
		me.items = [
			me.tabPanel = Ext.create('Ext.tab.Panel', {
				margin:5,
				items:[
					/**
					 * LAB PANEL
					 */
					{
						title:i18n('new_lab_order'),
						items:[
							{
								xtype:'grid',
								margin:5,
								store:me.patientsLabsOrdersStore,
								height:640,
								columns:[
									{
										xtype:'actioncolumn',
										width:20,
										items:[
											{
												icon:'resources/images/icons/delete.png',
												tooltip:i18n('remove'),
												scope:me,
												handler:me.onLabOrderRemoveClick
											}
										]
									},
									{
										header:i18n('lab'),
										flex:1,
										dataIndex:'laboratories'
									}
								],
								bbar:{
									xtype:'mitos.labstypescombo',
									margin:5,
									fieldLabel:i18n('add'),
									hideLabel:false,
									listeners:{
										scope:me,
										select:me.onAddLabs
									}
								}
							}
						],
						bbar:[
							'->', {
								text:i18n('create'),
								scope:me,
								handler:me.onCreateLabs
							}, {
								text:i18n('cancel'),
								scope:me,
								handler:me.onCancel
							}
						]
					},
					/**
					 * X-RAY PANEL
					 */
					{
						title:i18n('new_xray_order'),
						items:[

							{

								xtype:'grid',
								margin:5,
								store:me.prescriptionMedicationsStore,
								height:640,
								columns:[

									{
										xtype:'actioncolumn',
										width:20,
										items:[
											{
												icon:'resources/images/icons/delete.png',
												tooltip:i18n('remove'),
												scope:me,
												handler:me.onRemoveClick
											}
										]
									},
									{
										header:i18n('medication'),
										width:100,
										dataIndex:'STR'
									},
									{
										header:i18n('dispense'),
										width:100,
										dataIndex:'dispense'
									},
									{
										header:i18n('refill'),
										flex:1,
										dataIndex:'refill'
									}

								],

								bbar:{
									xtype:'textfield',
									margin:5,
									fieldLabel:i18n('add'),
									hideLabel:false,
									listeners:{
										scope:me,
										select:me.addXRay
									}
								}

							}
						],
						bbar:[
							'->', {
								text:i18n('create'),
								scope:me,
								handler:me.Create
							}, {
								text:i18n('cancel'),
								scope:me,
								handler:me.onCancel
							}
						]
					},
					/**
					 * NEW PRESCRIPTION PANEL
					 */
					{
						title:i18n('new_prescription'),
						layout:{
							type:'vbox',
							align:'stretch'
						},
						items:[
							/**
							 * Pharmacies Combo
							 */
//							{
//								xtype:'mitos.pharmaciescombo',
//								fieldLabel:i18n('pharmacies'),
//								width:250,
//								labelWidth:75,
//								margin:'5 5 0 5'
//
//							},
							/**
							 * Prescription Grid
							 */
							me.prescriptionsGrid = Ext.widget('grid',{
								title:i18n('prescriptions'),
								store:me.patientPrescriptionsStore,
								flex:1,
								margin:'5 5 0 5',
								plugins:[
									me.edditing = Ext.create('Ext.grid.plugin.RowEditing', {
										clicksToEdit:2,
										errorSummary:false
									})
								],
								columns:[
									{
										header:i18n('date'),
										xtype:'datecolumn',
										format:'Y-m-d',
										width:100,
										dataIndex:'created_date',
										action:'created_date'
									},
									{
										header:i18n('notes'),
										flex:1,
										dataIndex:'note',
										editor:{
											xtype:'textfield'
										}

									}

								],

								listeners:{
									scope:me,
									render:me.onPrescriptionsGridRender,
									itemclick:me.onPrescriptionClick
								}
							}),
							/**
							 * Medication Grid
							 */
							me.prescriptionMedicationsGrid = Ext.widget('grid',{
								title:i18n('medications'),
								store:me.prescriptionMedicationsStore,
								height:325,
								margin:5,
								columns:[
									{
										header:i18n('name'),
										flex:1,
										dataIndex:'STR'
									},
									{
										header:i18n('refill'),
										width:100,
										dataIndex:'refill'
									}

								],
								listeners:{
									scope:me,
									render:me.onPrescriptionMedicationsGridRender
								},
								plugins:Ext.create('App.ux.grid.RowFormEditing', {
									autoCancel:false,
									errorSummary:false,
									clicksToEdit:1,
									formItems:[
										{
											xtype:'container',
											layout:{
												type:'vbox',
												align:'stretch'
											},
											items:[
												{
													xtype:'fieldcontainer',
													layout:'hbox',
													margin:'0 0 0 5',
													fieldLabel:i18n('search'),
													labelWidth:80,
													items:[
														{
															xtype:'rxnormlivetsearch',
															width:570,
															listeners:{
																scope:me,
																select:me.onRxnormLiveSearchSelect
															}
														}
													]
												},
												{
													xtype:'textfield',
													name:'RXCUI',
													hidden:true
												},
												{
													xtype:'textfield',
													name:'CODE',
													hidden:true
												},
												{
													/**
													 * Line one
													 */
													xtype:'fieldcontainer',
													layout:'hbox',
													margin:'0 0 0 0',
													defaults:{ margin:'5 0 5 5'},
													items:[
														me.prescriptionMedText = Ext.widget('textfield',{
															fieldLabel:i18n('medication'),
															width:357,
															labelWidth:80,
															name:'STR'
														}),
														me.prescriptionDoseText = Ext.widget('textfield',{
															fieldLabel:i18n('dose'),
															labelWidth:40,
															name:'dose',
															width:293
														})
													]

												},
												{
													/**
													 * Line two
													 */
													xtype:'fieldcontainer',
													layout:'hbox',
													margin:'0 0 5 5',
													defaults:{ margin:'0 0 0 5'},
													fieldLabel:i18n('take'),
													labelWidth:80,
													items:[
														{
															xtype:'numberfield',
															name:'take_pills',
															allowBlank:false,
															margin:0,
															width:50,
															value:0,
															minValue:0
														},
														me.prescriptionMedTypeCmb = Ext.widget('mitos.prescriptiontypes',{
															xtype:'mitos.prescriptiontypes',
															name:'form',
															width:130
														}),
														{
															xtype:'mitos.prescriptionhowto',
															name:'route',
															width:130
														},
														{
															xtype:'mitos.prescriptionoften',
															name:'prescription_often',
															width:130
														},
														{
															xtype:'mitos.prescriptionwhen',
															name:'prescription_when',
															width:110
														}
													]
												},
												{
													/**
													 * Line three
													 */
													xtype:'fieldcontainer',
													layout:'hbox',
													margin:'0 0 0 5',
													defaults:{ margin:'0 0 5 5'},
													fieldLabel:i18n('dispense'),
													labelWidth:80,
													items:[
														{
															xtype:'numberfield',
															name:'dispense',
															margin:0,
															width:50,
															value:0,
															minValue:0
														},
														{
															fieldLabel:i18n('refill'),
															xtype:'numberfield',
															name:'refill',
															labelWidth:35,
															width:140,
															value:0,
															minValue:0
														},
														{
															fieldLabel:i18n('begin_date'),
															xtype:'datefield',
															width:190,
															labelWidth:70,
															format:globals['date_display_format'],
															name:'begin_date'

														},
														{
															fieldLabel:i18n('end_date'),
															xtype:'datefield',
															width:175,
															labelWidth:60,
															format:globals['date_display_format'],
															name:'end_date'
														}
													]

												},
												{
													xtype:'fieldcontainer',
													layout:'hbox',
													margin:'0 0 0 5',
													fieldLabel:i18n('related_dx'),
													labelWidth:80,
													items:[
														me.encounderIcdsCodes = Ext.widget('encountericdscombo',{
															name:'ICDS',
															width:570
														})
													]
												}
											]

										}
									]
								})
							})
						],
						bbar:[
							'->', {
								text:i18n('create'),
								scope:me,
								handler:me.onCreatePrescription
							},'-',{
								text:i18n('cancel'),
								scope:me,
								handler:me.onCancel
							}
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
	 * Adds a medication to the prescription
	 */
	onAddPrescriptionMedication:function(){
		var me = this,
			prescription_id = me.prescriptionsGrid.getSelectionModel().getLastSelected().data.id;
		me.prescriptionMedicationsGrid.editingPlugin.cancelEdit();
		me.prescriptionMedicationsStore.insert(0, {
			pid:me.pid,
			eid:me.eid,
			prescription_id:prescription_id,
			created_uid:app.user.id,
			begin_date:new Date(),
			create_date:new Date()
		});
		me.prescriptionMedicationsGrid.editingPlugin.startEdit(0, 0);
	},

	/**
	 * OK!
	 * Clone Logic
	 */
	onClonePrescriptions:function(btn){
		var me = this,
			grid = btn.up('grid'),
			sm = grid.getSelectionModel(),
			prescriptionStore = grid.getStore(),
			medicationsStore = me.prescriptionMedicationsStore,
			data = sm.getLastSelected().data,
			newDate = new Date(),
			newData = {};
		Ext.Msg.show({
			title:'Wait!',
			msg: 'Are you sure you want to clone this prescription?',
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn:function(btn){
				if(btn == 'yes'){
					sm.deselectAll();
					newData.pid = me.pid;
					newData.eid = me.eid;
					newData.uid = app.user.id;
					newData.created_date = newDate;
					newData.note = data.note + ' (cloned from ' + Ext.Date.format(new Date(), 'Y-m-d') + ' )';
					prescriptionStore.add(newData);
					prescriptionStore.sync({
						callback:function(batch, options){
							var newRecord = options.operations.create[0],
								newId = newRecord.data.id,
								oldMedsRecs = medicationsStore.data.items,
								newMeds = [];
							for(var i=0; i < oldMedsRecs.length; i++){
								newMeds.push(oldMedsRecs[i].data);
							}
							sm.select(newRecord);
							medicationsStore.removeAll();
							medicationsStore.commitChanges();
							for(var k=0; k < newMeds.length; k++){
								delete newMeds[k].id;
								delete newMeds[k].end_date;
								newMeds[k].eid = me.eid;
								newMeds[k].begin_date = newDate;
								newMeds[k].create_date = newDate;
								newMeds[k].prescription_id = newId;
								medicationsStore.add(newMeds[k]);
							}
							medicationsStore.sync();
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
		var formPanel = combo.up('form'),
			form = formPanel.getForm();
		combo.reset();
		formPanel.el.mask('loading_data...');
		Rxnorm.getMedicationAttributesByCODE(record[0].data.CODE, function(provider, response){
			formPanel.el.unmask();
			form.findField('RXCUI').setValue(record[0].data.RXCUI);
			form.findField('CODE').setValue(record[0].data.CODE);
			form.findField('STR').setValue(record[0].data.STR);
			form.findField('route').setValue(response.result.DRT);
			form.findField('dose').setValue(response.result.DST);
			form.findField('form').setValue(response.result.DDF);
		});
	},

	/**
	 * OK!
	 * @param grid
	 * @param record
	 */
	onPrescriptionClick:function(grid, record){
		this.fireEvent('prescriptiongridclick', grid, record);
		this.prescriptionMedicationsStore.proxy.extraParams = {prescription_id:record.data.id};
		this.prescriptionMedicationsStore.load();
		this.addMedicationBtn.setDisabled(record.data.eid != this.eid);
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
		me.patientPrescriptionsStore.load({params:{pid:app.patient.pid}});
		me.prescriptionMedicationsStore.proxy.extraParams = {};
		me.prescriptionMedicationsStore.load();
		/**
		 * Lab stuff
		 */
		me.patientsLabsOrdersStore.removeAll();
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
		if(app.currCardCmp.id == 'panelSummary'){
			app.currCardCmp.patientDocumentsStore.load({params:{pid:this.pid}});
		}
	},

	/**
	 * OK!
	 * adds the buttons to the header
	 */
	onPrescriptionsGridRender:function(){
		var me = this;
		me.prescriptionsGrid.dockedItems.items[0].add(
			Ext.widget('button',{
				text:i18n('clone_prescription'),
				iconCls:'icoAdd',
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

	/**
	 * OK!
	 * adds the buttons to the header
	 */
	onPrescriptionMedicationsGridRender:function(){
		var me = this;
		me.prescriptionMedicationsGrid.dockedItems.items[0].add(
			me.addMedicationBtn = Ext.widget('button',{
				text:i18n('add_medication'),
				scope:me,
				iconCls:'icoAdd',
				disabled:true,
				handler:me.onAddPrescriptionMedication
			})
		);
	},

	//******************************************************************************************
	//******************************************************************************************
	//******************************************************************************************
	//******************************************************************************************
	//******************************************************************************************


	onCreatePrescription:function(){
		say('hello');
		var records = this.prescriptionMedicationsStore.data.items,
			data = [];
		say(records);
		for(var i = 0; i < records.length; i++){
			data.push(records[i].data);
		}
		say('stuck');
		DocumentHandler.createDocument({medications:data, pid:app.patient.pid, docType:'Rx', documentId:5, eid:app.patient.eid}, function(provider, response){
			say(response.result);
		});
		this.close();

	},

	onCreateLabs:function(){
		var me = this,
			records = me.patientsLabsOrdersStore.data.items,
			data = [],
			params;
		for(var i = 0; i < records.length; i++){
			data.push(records[i].data);
		}
		params = {
			labs:data,
			pid:me.pid,
			eid:me.eid,
			documentId:4,
			docType:'Orders'
		};
		DocumentHandler.createDocument(params, function(provider, response){
			say(response.result);
		});
		this.close();

	},

	addXRay:function(){

	},

	onAddLabs:function(field, model){
		this.patientsLabsOrdersStore.add({
			laboratories:model[0].data.loinc_name
		});
		field.reset();
	}
});