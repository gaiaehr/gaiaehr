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

Ext.define('App.view.patient.windows.Medical', {
	extend: 'App.ux.window.Window',
	title: i18n('medical_window'),
	id: 'MedicalWindow',
	layout: 'card',
	closeAction: 'hide',
	height: 700,
	width: 1200,
	bodyStyle: 'background-color:#fff',
	modal: true,
	defaults: {
		margin: 5
	},
	requires: [
		'App.view.patient.Results',
		'App.view.patient.Referrals',
		'App.store.administration.HL7Recipients',
        'App.ux.grid.RowFormEditing'
	],

	pid: null,
	eid: null,

	initComponent: function(){
		var me = this;

		//region Stores
		me.patientImmuListStore = Ext.create('App.store.patient.PatientImmunization', {
			groupField: 'vaccine_name',
			sorters: [
				'vaccine_name',
				'administered_date'
			],
			listeners: {
				scope: me,
				beforesync: me.setDefaults
			},
			autoSync: false
		});

		me.patientAllergiesListStore = Ext.create('App.store.patient.Allergies', {
			listeners: {
				scope: me,
				beforesync: me.setDefaults
			},
			autoSync: false
		});

		me.patientActiveProblemsStore = Ext.create('App.store.patient.PatientActiveProblems', {
			listeners: {
				scope: me,
				beforesync: me.setDefaults
			},
			autoSync: false
		});

		me.patientMedicationsStore = Ext.create('App.store.patient.Medications', {
			listeners: {
				scope: me,
				beforesync: me.setDefaults
			},
			autoSync: false
		});



		me.MedicationListStore = Ext.create('App.store.administration.Medications');
		//endregion

		me.immuSm = Ext.create('Ext.selection.CheckboxModel',{
			listeners:{
				scope:me,
				selectionchange: me.onImmunizationSelection
			}
		});

		me.items = [

			//region Immunization Panel
			{
				xtype:'panel',
				layout:'border',
				border:false,
				items:[
					{
						xtype: 'grid',
						region:'center',
						action: 'patientImmuListGrid',
						selModel: me.immuSm,
						store: me.patientImmuListStore,
						features: Ext.create('Ext.grid.feature.Grouping', {
							groupHeaderTpl: i18n('immunization') + ': {name} ({rows.length} Item{[values.rows.length > 1 ? "s" : ""]})'
//					        hideGroupedHeader: true
						}),
						columns: [
							{
								text: i18n('code'),
								dataIndex: 'code',
								width: 50
							},
							{
								text: i18n('immunization_name'),
								dataIndex: 'vaccine_name',
								flex: 1
							},
							{
								text: i18n('lot_number'),
								dataIndex: 'lot_number',
								width: 100
							},
							{
								text: i18n('amount'),
								dataIndex: 'administer_amount',
								width: 100
							},
							{
								text: i18n('units'),
								dataIndex: 'administer_units',
								width: 100
							},
							{
								text: i18n('notes'),
								dataIndex: 'note',
								flex: 1
							},
							{
								text: i18n('administered_by'),
								dataIndex: 'administered_by',
								width: 150
							},
							{
								xtype: 'datecolumn',
								text: i18n('date'),
								format: 'Y-m-d',
								width: 100,
								dataIndex: 'administered_date'
							}
						],
						plugins: Ext.create('App.ux.grid.RowFormEditing', {
							autoCancel: false,
							errorSummary: false,
							clicksToEdit: 2,
							formItems: [
								{

									title: 'general',
									xtype: 'container',
									layout: 'vbox',
									items: [
										{
											/**
											 * Line one
											 */
											xtype: 'fieldcontainer',
											layout: 'hbox',
											itemId: 'line1',
											defaults: {
												margin: '0 10 0 0',
												xtype: 'textfield'
											},
											items: [
												{
													xtype: 'immunizationlivesearch',
													fieldLabel: i18n('name'),
													name: 'vaccine_name',
													valueField:'name',
													hideLabel: false,
													allowBlank: false,
													enableKeyEvents: true,
													width: 570,
													listeners: {
														scope: me,
														select: me.onLiveSearchSelect
													}
												},
												{
													fieldLabel: i18n('administrator'),
													name: 'administered_by',
													width: 295,
													labelWidth: 160

												}
											]

										},
										{
											/**
											 * Line two
											 */
											xtype: 'fieldcontainer',
											layout: 'hbox',
											defaults: {
												margin: '0 10 0 0',
												xtype: 'textfield'
											},
											items: [
												{
													fieldLabel: i18n('lot_number'),
													xtype: 'textfield',
													width: 200,
													name: 'lot_number'

												},
												{

													xtype: 'numberfield',
													fieldLabel: i18n('amount'),
													name: 'administer_amount',
													labelWidth: 60,
													width: 200
												},
												{

													xtype: 'textfield',
													fieldLabel: i18n('units'),
													name: 'administer_units',
													labelWidth: 50,
													width: 150

												},
												{
													fieldLabel: i18n('info_statement_given'),
													width: 295,
													labelWidth: 160,
													xtype: 'datefield',
													format: 'Y-m-d',
													name: 'education_date'
												}
											]

										},
										{
											/**
											 * Line three
											 */
											xtype: 'fieldcontainer',
											layout: 'hbox',
											defaults: {
												margin: '0 10 0 0',
												xtype: 'textfield'
											},
											items: [
												{
													fieldLabel: i18n('notes'),
													xtype: 'textfield',
													width: 300,
													name: 'note'

												},
												me.CvxMvxCombo = Ext.create('App.ux.combo.CVXManufacturersForCvx', {
													fieldLabel: i18n('manufacturer'),
													width: 260,
													name: 'manufacturer'
												}),
												{
													fieldLabel: i18n('date_administered'),
													width: 295,
													labelWidth: 160,
													xtype: 'datefield',
													format: 'Y-m-d',
													name: 'administered_date'
												}
											]

										}
									]

								}
							]
						}),
						bbar: [
							'-',
							me.vxuBtn = Ext.widget('button',{
								text: i18n('submit_hl7_vxu'),
								scope: me,
								disabled: true,
								handler: me.onVxu
							}),
							'-',
							'->',
							{
								text: i18n('review'),
								itemId: 'review_immunizations',
								action: 'encounterRecordAdd',
								scope: me,
								handler: me.onReviewed
							}
						]
					},
					{
						xtype:'grid',
						title:i18n('immunization_list'),
						collapseMode:'mini',
						region:'east',
						collapsible:true,
						collapsed:true,
						width:300,
						split:true,
						store: Ext.create('App.store.patient.CVXCodes'),
						columns: [
							{
								text: i18n('code'),
								dataIndex: 'cvx_code',
								width: 50
							},
							{
								text: i18n('immunization_name'),
								dataIndex: 'name',
								flex: 1
							}
						],
						listeners:{
							scope:me,
							expand:me.immunizationListExpand
						}
					}
				]
			},
			//endregion

			//region Allergies Card panel
			{
				xtype: 'grid',
				action: 'patientAllergiesListGrid',
				store: me.patientAllergiesListStore,
				columns: [
					{
						header: i18n('type'),
						width: 100,
						dataIndex: 'allergy_type'
					},
					{
						header: i18n('name'),
						width: 375,
						dataIndex: 'allergy'
					},
					{
						header: i18n('location'),
						width: 100,
						dataIndex: 'location'
					},
					{
						header: i18n('severity'),
						flex: 1,
						dataIndex: 'severity'
					},
					{
						text: i18n('active'),
						width: 55,
						dataIndex: 'active',
						renderer: me.boolRenderer
					}
				],
				plugins: me.rowEditingAllergies = Ext.create('App.ux.grid.RowFormEditing', {
					autoCancel: false,
					errorSummary: false,
					clicksToEdit: 1,
					listeners: {
						scope: me,
						beforeedit: me.beforeAllergyEdit
					},
					formItems: [
						{
							title: i18n('general'),
							xtype: 'container',
							padding: '0 10',
							layout: 'vbox',
							items: [
								{
									/**
									 * Line one
									 */
									xtype: 'fieldcontainer',
									layout: 'hbox',
									defaults: {
										margin: '0 10 0 0'
									},
									items: [
										{
											xtype: 'mitos.allergiestypescombo',
											fieldLabel: i18n('type'),
											name: 'allergy_type',
											action: 'allergy_type',
											allowBlank: false,
											width: 225,
											labelWidth: 70,
											enableKeyEvents: true,
											listeners: {
												scope: me,
												change: me.onAllergyTypeCahnge
											}
										},
										me.allergieType = Ext.create('App.ux.combo.Allergies', {
											fieldLabel: i18n('allergy'),
											action: 'allergie_name',
											name: 'allergy',
											enableKeyEvents: true,
											disabled: true,
											width: 550,
											labelWidth: 70
										}),
										me.allergieMedication = Ext.widget('rxnormallergylivetsearch', {
											fieldLabel: i18n('allergy'),
											hideLabel: false,
											action: 'allergy',
											name: 'allergy',
											hidden: true,
											disabled: true,
											enableKeyEvents: true,
											width: 550,
											labelWidth: 70,
											listeners: {
												scope: me,
												select: me.onLiveSearchSelect
											}
										}),
										{
											fieldLabel: i18n('begin_date'),
											xtype: 'datefield',
											format: 'Y-m-d',
											name: 'begin_date'

										}
									]

								},
								{
									/**
									 * Line two
									 */
									xtype: 'fieldcontainer',
									layout: 'hbox',
									defaults: {
										margin: '0 10 0 0'
									},
									items: [
										{
											xtype: 'mitos.allergieslocationcombo',
											fieldLabel: i18n('location'),
											name: 'location',
											action: 'location',
											width: 225,
											labelWidth: 70,
											listeners: {
												scope: me,
												select: me.onLocationSelect
											}

										},
										me.allergiesReaction = Ext.create('App.ux.combo.AllergiesAbdominal', {
											xtype: 'mitos.allergiesabdominalcombo',
											fieldLabel: i18n('reaction'),
											name: 'reaction',
											width: 315,
											labelWidth: 70
										}),
										{
											xtype: 'mitos.allergiesseveritycombo',
											fieldLabel: i18n('severity'),
											name: 'severity',
											width: 225,
											labelWidth: 70
										},
										{
											fieldLabel: i18n('end_date'),
											xtype: 'datefield',
											format: 'Y-m-d',
											name: 'end_date'
										}
									]
								}
							]
						}
					]
				}),
				bbar: [
					{
						text: i18n('only_active'),
						enableToggle: true,
						scope: me,
						toggleHandler: me.onOnlyActiveToggle
					},
					'->',
					{
						text: i18n('review'),
						action: 'encounterRecordAdd',
						itemId: 'review_allergies',
						scope: me,
						handler: me.onReviewed
					}
				]
			},
			//endregion

			//region Active Problem Card panel
			{
				xtype: 'grid',
				action: 'patientMedicalListGrid',
				store: me.patientActiveProblemsStore,
				columns: [
					{
						header: i18n('code'),
						width: 110,
						dataIndex: 'code',
						renderer:function(value, metaDate, record){
							return value + ' (' + record.data.code_type + ')'
						}
					},
					{
						header: i18n('problem'),
						flex: 1,
						dataIndex: 'code_text'
					},
					{
						xtype: 'datecolumn',
						header: i18n('date_diagnosed'),
						width: 100,
						format: 'Y-m-d',
						dataIndex: 'begin_date'
					},
					{
						xtype: 'datecolumn',
						header: i18n('end_date'),
						width: 100,
						format: 'Y-m-d',
						dataIndex: 'end_date'
					},
					{
						header: i18n('active?'),
						width: 60,
						dataIndex: 'active',
						renderer: me.boolRenderer
					}
				],
				plugins: Ext.create('App.ux.grid.RowFormEditing', {
					autoCancel: false,
					errorSummary: false,
					clicksToEdit: 1,
					formItems: [
						{
							xtype: 'container',
							padding: 10,
							layout: 'vbox',
							items: [
								{
									xtype: 'liveicdxsearch',
									fieldLabel: i18n('search'),
									name: 'code',
									hideLabel: false,
									itemId: 'actiiveproblems',
									action: 'actiiveproblems',
									enableKeyEvents: true,
									displayField: 'code',
									valueField: 'code',
									width: 720,
									labelWidth: 70,
									listeners: {
										scope: me,
										select: me.onLiveSearchSelect
									}
								},
								{
									/**
									 * Line one
									 */
									xtype: 'fieldcontainer',
									layout: 'hbox',
									defaults: {
										margin: '0 10 0 0'
									},
									items: [
										{
											xtype: 'textfield',
											fieldLabel: i18n('problem'),
											width: 510,
											labelWidth: 70,
											allowBlank: false,
											name: 'code_text',
											action: 'code_text'
										},
										{
											fieldLabel: i18n('code_type'),
											xtype: 'textfield',
											width: 200,
											labelWidth: 100,
											name: 'code_type'

										}
									]

								},
								{
									/**
									 * Line two
									 */
									xtype: 'fieldcontainer',
									layout: 'hbox',
									defaults: {
										margin: '0 10 0 0'
									},
									items: [
										{
											fieldLabel: i18n('occurrence'),
											width: 250,
											labelWidth: 70,
											xtype: 'mitos.occurrencecombo',
											name: 'occurrence'

										},
										{
											fieldLabel: i18n('outcome'),
											xtype: 'mitos.outcome2combo',
											width: 250,
											labelWidth: 70,
											name: 'outcome'

										},

										{
											fieldLabel: i18n('date_diagnosed'),
											xtype: 'datefield',
											width: 200,
											labelWidth: 100,
											format: 'Y-m-d',
											name: 'begin_date'

										}
									]

								},
								{
									/**
									 * Line three
									 */
									xtype: 'fieldcontainer',
									layout: 'hbox',
									defaults: {
										margin: '0 10 0 0'
									},
									items: [
										{
											xtype: 'textfield',
											width: 510,
											labelWidth: 70,
											fieldLabel: i18n('referred_by'),
											name: 'referred_by'
										},
										{
											fieldLabel: i18n('end_date'),
											xtype: 'datefield',
											width: 200,
											labelWidth: 100,
											format: 'Y-m-d',
											name: 'end_date'

										}
									]
								}
							]
						}
					]
				}),
				bbar: ['->', {
					text: i18n('review'),
					itemId: 'review_active_problems',
					scope: me,
					action: 'encounterRecordAdd',
					handler: me.onReviewed
				}]
			},
			//endregion


			//region Medications panel
			{
				xtype:'panel',
				layout:'border',
				border:false,
				items:[
					{
						xtype: 'grid',
						region:'center',
						action: 'patientMedicationsListGrid',
						store: me.patientMedicationsStore,
						columns: [
							{
								header: i18n('medication'),
								flex: 1,
								minWidth:200,
								dataIndex: 'STR',
								editor: {
									xtype: 'rxnormlivetsearch',
									displayField: 'STR',
									valueField: 'STR',
									action: 'medication',
									listeners: {
										scope: me,
										select: me.onLiveSearchSelect
									}
								}
							},
							{
								header: i18n('dose'),
								width: 125,
								dataIndex: 'dose',
								sortable: false,
								hideable: false,
								editor: {
									xtype: 'textfield'
								}
							},
							{
								header: i18n('route'),
								width: 100,
								dataIndex: 'route',
								sortable: false,
								hideable: false,
								editor: {
									xtype: 'mitos.prescriptionhowto'
								}
							},
							{
								header: i18n('form'),
								width: 125,
								dataIndex: 'form',
								sortable: false,
								hideable: false,
								editor: {
									xtype: 'mitos.prescriptiontypes'
								}
							},
							{
								header: i18n('instructions'),
								width: 200,
								dataIndex: 'prescription_when',
								sortable: false,
								hideable: false,
								editor: Ext.widget('livesigssearch')
							},
							{
								xtype: 'datecolumn',
								format: globals['date_display_format'],
								header: i18n('begin_date'),
								width: 100,
								dataIndex: 'begin_date',
								sortable: false,
								hideable: false
							},
							{
								header: i18n('end_date'),
								width: 100,
								dataIndex: 'end_date',
								sortable: false,
								hideable: false,
								editor: {
									xtype: 'datefield',
									format: globals['date_display_format']
								}
							},
							{
								header: i18n('active?'),
								width: 60,
								dataIndex: 'active',
								renderer: me.boolRenderer
							}
						],
						plugins: Ext.create('Ext.grid.plugin.RowEditing', {
							autoCancel: false,
							errorSummary: false,
							clicksToEdit: 2
						}),
						bbar: [
							'->',
							{
								text: i18n('review'),
								itemId: 'review_medications',
								scope: me,
								action: 'encounterRecordAdd',
								handler: me.onReviewed
							}
						]
					},
					{
						xtype:'grid',
						title:i18n('medication_list'),
						collapseMode:'mini',
						region:'east',
						width:400,
						collapsible:true,
						collapsed:true,
						split:true,
						loadMask: true,
						selModel: {
							pruneRemoved: false
						},
						viewConfig: {
							trackOver: false
						},
						verticalScroller:{
							variableRowHeight: true
						},
						store: me.MedicationListStore,
						tbar:[
							me.MedicationListSearch = Ext.widget('triggerfield',{
								triggerCls: Ext.baseCSSPrefix+'form-search-trigger',
								fieldLabel: i18n('search'),
								flex:1,
								labelWidth: 43,
								onTriggerClick: me.onMedicationListSearch
							})
						],
						columns: [
							{
								xtype: 'rownumberer',
								width: 50,
								sortable: false
							},
							{
								text: i18n('medication'),
								dataIndex: 'STR',
								flex: 1
							}
						],
						listeners:{
							scope:me,
							expand:me.medicationListExpand
						}
					}
				]
			},
			//endregion

			//region Lab Results panel
			{

				xtype:'patientresultspanel',
				action: 'patientLabs'
			},
			//endregion

			//region Social History panel
			{
				xtype: 'patientsocialhistorypanel',
				action: 'patientSocialHistory'
			},

			//region Referrals
			{
				xtype: 'patientreferralspanel',
				action: 'patientReferrals'
			}
			//endregion
		];
		/**
		 * Docked Items
		 * @type {Array}
		 */
		me.dockedItems = [
			{
				xtype: 'toolbar',
				items: [
					{

						text: i18n('immunization'),
						enableToggle: true,
						toggleGroup: 'medicalWin',
						pressed: true,
						itemId: 'immunization',
						action: 'immunization',
						scope: me,
						handler: me.cardSwitch
					},
					'-',
					{
						text: i18n('allergies'),
						enableToggle: true,
						toggleGroup: 'medicalWin',
						itemId: 'allergies',
						action: 'allergies',
						scope: me,
						handler: me.cardSwitch
					},
					'-',
					{
						text: i18n('active_problems'),
						enableToggle: true,
						toggleGroup: 'medicalWin',
						itemId: 'issues',
						action: 'issues',
						scope: me,
						handler: me.cardSwitch
					},
					'-',
					{
						text: i18n('medications'),
						enableToggle: true,
						toggleGroup: 'medicalWin',
						itemId: 'medications',
						action: 'medications',
						scope: me,
						handler: me.cardSwitch
					},
					'-',
					{
						text: i18n('results'),
						enableToggle: true,
						toggleGroup: 'medicalWin',
						itemId: 'laboratories',
						action: 'laboratories',
						scope: me,
						handler: me.cardSwitch
					},
					'-',
					{
						text: i18n('social_history'),
						enableToggle: true,
						toggleGroup: 'medicalWin',
						itemId: 'socialhistory',
						action: 'socialhistory',
						scope: me,
						handler: me.cardSwitch
					},
					'-',
					{
						text: i18n('referrals'),
						enableToggle: true,
						toggleGroup: 'medicalWin',
						itemId: 'referrals',
						action: 'referrals',
						scope: me,
						handler: me.cardSwitch
					},
					'->',
					{
						text: i18n('add_new'),
						action: 'AddRecord',
						itemId: 'encounterRecordAdd',
						iconCls: 'icoAdd',
						scope: me,
						handler: me.onAddItem
					}
				]
			}
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
		me.listeners = {
			scope: me,
			show: me.onMedicalWinShow,
			close: me.onMedicalWinClose
		};
		me.callParent(arguments);
	},

	onReviewed: function(btn){
		var me = this, BtnId = btn.itemId, params = {
			eid: app.patient.eid,
			area: BtnId
		};
		Medical.reviewMedicalWindowEncounter(params, function(provider, response){
			me.msg('Sweet!', i18n('succefully_reviewed'));
		});
	},

	//region Medication Stuff
	onMedicationListSearch:function(){
		this.up('grid').getStore().load({
			params:{query:this.getValue()}
		});
	},

	medicationListExpand:function(grid){
		this.MedicationListSearch.reset();
		grid.getStore().load();
	},



	//endregion

	//*******************************************************
	//region Immunization Stuff
	immunizationListExpand:function(grid){
		grid.getStore().load();
	},

	onVxu: function(){
		var me = this,
			foo = me.immuSm.getSelection(),
			immunizations = [];

		me.vxuWindow = me.getVxuWindow();

		for(var i=0; i < foo.length; i++){
			immunizations.push(foo[i].data);
		}

		me.vxuWindow.getComponent('list').update(immunizations);
	},

	onImmunizationSelection: function(sm, selected){
		this.vxuBtn.setDisabled(selected.length == 0);
	},

	getVxuWindow: function(){
		var me = this;
		return Ext.widget('window',{
			title: i18n('submit_hl7_vxu'),
			closable: false,
			modal: true,
			bodyStyle:'background-color:white',
			defaults:{
				xtype:'container',
				border:false,
				margin:10
			},
			items:[
				{
					html: i18n('please_verify_the_information')+':',
					margin: '10 10 0 10'
				},
				{
					width:700,
					minHeight:50,
					maxHeight:200,
					itemId:'list',
					margin:'0 10 20 10',
					styleHtmlContent: true,
					tpl: new Ext.XTemplate(
						'<ul>',
						'<tpl for=".">',     // interrogate the kids property within the data
						'   <li>CVX:{code} - {vaccine_name} {administer_amount} {administer_units} {date_administered}</li>',
						'</tpl>' +
						'</ul>'
					)
				}
			],
			buttons:[
				me.vxuFrom = Ext.create('App.ux.combo.ActiveFacilities',{
					fieldLabel: i18n('send_from'),
					emptyText: i18n('select'),
					labelWidth: 60,
					store: Ext.create('App.store.administration.HL7Recipients',{
						filters:[
							{
								property:'active',
								value:true
							}
						]
					})
				}),
				me.vxuTo = Ext.widget('combobox',{
					xtype:'combobox',
					fieldLabel: i18n('send_to'),
					emptyText: i18n('select'),
					allowBlank: false,
					forceSelection: true,
					labelWidth: 60,
					displayField: 'recipient_application',
					valueField: 'id',
					store: Ext.create('App.store.administration.HL7Recipients',{
						filters:[
							{
								property:'active',
								value:true
							}
						]
					})
				}),
				{
					text: i18n('send'),
					scope: me,
					handler: me.doSendVxu
				},
				{
					text:i18n('cancel'),
					handler:function(){
						me.vxuWindow.close();
					}
				}
			]
		}).show();
	},

	doSendVxu:function(){
		var me = this,
			foo = me.immuSm.getSelection(),
			params = {},
			immunizations = [];
		if(me.vxuTo.isValid()){
			for(var i=0; i < foo.length; i++){
				immunizations.push(foo[i].data.id);
			}
			params.pid = me.pid;
			params.from = me.vxuFrom.getValue();
			params.to = me.vxuTo.getValue();
			params.immunizations = immunizations;

			me.vxuWindow.el.mask(i18n('sending'));
			HL7Messages.sendVXU(params, function(provider, response){
				me.vxuWindow.el.unmask();
				if(response.result.success){
					app.msg(i18n('sweet!'), i18n('message_sent'));
				}else{
					app.msg(i18n('oops!'), i18n('message_error'), true);
				}
				me.vxuWindow.close();
				me.immuSm.deselectAll();
			});
		}
	},
	//endregion

	//*******************************************************
	//region Allergy Stuff
	beforeAllergyEdit: function(editor, e){
		this.allergieMedication.setValue(e.record.data.allergy);
	},

	onOnlyActiveToggle: function(btn, pressed){
		var me = this,
			store = btn.up('grid').getStore();

		if(pressed){
			store.load({
				filters: [
					{
						property: 'pid',
						value: me.pid
					},
					{
						property: 'end_date',
						value: null
					}
				]
			})
		} else{
			store.load({
				filters: [
					{
						property: 'pid',
						value: me.pid
					}
				]
			})
		}
	},

	onAllergyTypeCahnge: function(combo){
		var me = this,
			type = combo.getValue(),
			isDrug = type == 'Drug';

		me.allergieMedication.setVisible(isDrug);
		me.allergieMedication.setDisabled(!isDrug);
		me.allergieType.setVisible(!isDrug);
		me.allergieType.setDisabled(isDrug);

		if(!isDrug) me.allergieType.store.load({params: {allergy_type: type}})

	},

	onLocationSelect: function(combo, record){
		var me = this,
			list,
			value = combo.getValue();

		if(value == 'Skin'){
			list = 80;
			me.allergiesReaction.getStore().load();
		} else if(value == 'Local'){
			list = 81;
		} else if(value == 'Abdominal'){
			list = 82;
		} else if(value == 'Systemic / Anaphylactic'){
			list = 83;
		}

		me.allergiesReaction.getStore().load({params: {list_id: list}});
	},
	//endregion

	//*********************************************************
	onLiveSearchSelect: function(combo, record){
		var me = this,
			xform = combo.up('form').getForm(),
			field,
			name;

		if(combo.name == 'vaccine_name'){


			me.CvxMvxCombo.getStore().load({
				params:{cvx_code:record[0].data.cvx_code}
			});

			xform.getRecord().set({
				code: record[0].data.cvx_code,
				code_type: 'CVX'
			});



		} else if(combo.action == 'allergy'){
			xform.getRecord().set({allergy_code: record[0].data.RXCUI});
		} else if(combo.action == 'actiiveproblems'){
			xform.findField('code_text').setValue(record[0].data.code_text);
			xform.findField('code_type').setValue(record[0].data.code_type);
//        }else if(combo.action == 'surgery'){
//            name = record[0].data.surgery;
//            field = combo.up('fieldcontainer').query('[action="idField"]')[0];
//            field.setValue(name);

		} else if(combo.action == 'medication'){
			Rxnorm.getMedicationAttributesByCODE(record[0].data.CODE, function(provider, response){
				xform.setValues({
					RXCUI: record[0].data.RXCUI,
					CODE: record[0].data.CODE,
					STR: record[0].data.STR.split(',')[0],
					route: response.result.DRT,
					dose: response.result.DST,
					form: response.result.DDF
				});
			});
		} else if(combo.action == 'cdt'){
			name = record[0].data.text;
			field = combo.up('fieldcontainer').query('[action="description"]')[0];
			field.setValue(name);
		}

	},

	onAddItem: function(){
		var me = this,
			activeItem = me.getLayout().getActiveItem(),
            grid = activeItem.xtype == 'grid' ? activeItem : activeItem.down('grid'),
			store = grid.store,
			params;

        grid.editingPlugin.cancelEdit();

		store.insert(0, {
			created_uid: app.user.id,

			uid: app.user.id,
			pid: app.patient.pid,
			eid: app.patient.eid,

			create_date: new Date(),
			begin_date: new Date()

		});
		grid.editingPlugin.startEdit(0, 0);

		if(app.patient.eid != null){
			if(grid.action == 'patientImmuListGrid'){
				params = {
					eid: app.patient.eid,
					area: 'review_immunizations'
				};
			} else if(grid.action == 'patientAllergiesListGrid'){
				params = {
					eid: app.patient.eid,
					area: 'review_allergies'
				};
			} else if(grid.action == 'patientMedicalListGrid'){
				params = {
					eid: app.patient.eid,
					area: 'review_active_problems'
				};
			} else if(grid.action == 'patientSurgeryListGrid'){
				params = {
					eid: app.patient.eid,
					area: 'review_surgery'
				};
			} else if(grid.action == 'patientDentalListGrid'){
				params = {
					eid: app.patient.eid,
					area: 'review_dental'
				};
			} else if(grid.action == 'patientMedicationsListGrid'){
				params = {
					eid: app.patient.eid,
					area: 'review_medications'
				};
			}
			Medical.reviewMedicalWindowEncounter(params);
		}
	},

	setDefaults: function(options){
		var data;
		if(options.update){
			data = options.update[0].data;
			data.updated_uid = app.user.id;
		} else if(options.create){

		}
	},

	cardSwitch: function(btn){
		var me = this,
			layout = me.getLayout(),
			addBtn = me.down('toolbar').query('[action="AddRecord"]')[0],
			p = app.patient,
			title;

		me.pid = p.pid;
		addBtn.show();

		if(btn.action == 'immunization'){
			layout.setActiveItem(0);
			title = 'Immunizations';
		} else if(btn.action == 'allergies'){
			layout.setActiveItem(1);
			title = 'Allergies';
		} else if(btn.action == 'issues'){
			layout.setActiveItem(2);
			title = 'Active Problems';
		} else if(btn.action == 'medications'){
			layout.setActiveItem(3);
			title = 'Medications';
		} else if(btn.action == 'laboratories'){
			layout.setActiveItem(4);
			title = 'Laboratories';
			addBtn.hide();
		} else if(btn.action == 'socialhistory'){
			layout.setActiveItem(5);
			title = 'Social History';
			addBtn.hide();
		} else if(btn.action == 'referrals'){
			layout.setActiveItem(6);
			title = 'Referrals';
			addBtn.hide();
		}
		me.setTitle(p.name + ' (' + title + ') ' + (p.readOnly ? '-  <span style="color:red">[Read Mode]</span>' :''));
	},

	onMedicalWinShow: function(){
		var me = this,
			reviewBts = me.query('button[action="review"]'),
			p = app.patient;

		me.pid = p.pid;
		me.eid = p.eid;
//		me.pid = 1;
//		me.eid = 1;

		me.setTitle(p.name + (p.readOnly ? ' <span style="color:red">[' + i18n('read_mode') + ']</span>' :''));

		me.setReadOnly();

//		for(var i = 0; i < reviewBts.length; i++){
//			reviewBts[i].setVisible((me.eid != null));
//		}

		app.getController('patient.Results').setResultPanel();

		me.patientImmuListStore.load({
			params: {
				pid: me.pid
			}
		});

		me.patientAllergiesListStore.load({
			filters: [
				{
					property: 'pid',
					value: me.pid
				}
			]
		});

		me.patientActiveProblemsStore.load({
			filters: [
				{
					property: 'pid',
					value: me.pid
				}
			]
		});

		me.patientMedicationsStore.load({
			params: {
				pid: me.pid
			},
			filters: [
				{
					property: 'pid',
					value: me.pid
				}
			]
		});


		me.cardSwitch({action:'laboratories'});
	},

	onMedicalWinClose: function(){
		if(app.getActivePanel().$className == 'App.view.patient.Summary'){
			app.getActivePanel().loadStores();
		}
	}
});