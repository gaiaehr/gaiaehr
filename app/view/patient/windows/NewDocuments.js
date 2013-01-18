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
		me.patientsLabsOrdersStore = Ext.create('App.store.patient.PatientsLabsOrders');
		me.PatientsXrayCtOrdersStore = Ext.create('App.store.patient.PatientsXrayCtOrders');
		//noinspection JSUnresolvedFunction,JSUnresolvedVariable
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
								store:me.patientsLabsOrdersStore,
								flex:1,
								viewConfig:{
									markDirty:false
								},
								columns:[
									{
										xtype:'actioncolumn',
										width:20,
										items:[
											{
												icon:'resources/images/icons/cross.png',
												tooltip:i18n('remove'),
												scope:me,
												handler:me.onLabOrderRemoveClick
											}
										]
									},
									{
										header:i18n('date'),
										xtype: 'datecolumn',
										format:'Y-m-d H:i a',
										width:130,
										dataIndex:'date_created'
									},
									{
										header:i18n('description'),
										flex:1,
										dataIndex:'description'
									},
									{
										xtype:'actioncolumn',
										width:30,
										items:[
											{
												icon: 'resources/images/icons/preview.png',
												tooltip: i18n('view_document'),
												handler: me.onDocumentView,
												getClass:function(){
													return 'x-grid-icon-padding';
												}
											}
										]
									}
								],
								listeners:{
									scope:me,
									render:me.onLabGridRender,
									beforeedit:me.beforeLabOrderEdit,
									validateedit:me.beforeLabOrderValidEdit
								},
								plugins:Ext.create('App.ux.grid.RowFormEditing', {
									autoCancel:false,
									autoSync: true,
									errorSummary:false,
									saveBtnEnabled:true,
									clicksToEdit:2,
									formItems:[
										{

											xtype:'container',
											layout:{
												type:'vbox',
												align:'stretch'
											},
											items:[
												{
													xtype:'textfield',
													name:'loincs',
													hidden:true
												},
												{
													xtype:'grid',
													frame:true,
													title:i18n('items'),
													flex:1,
													store:Ext.create('Ext.data.ArrayStore', {
														model: 'App.model.patient.PatientsLabOrderItems'
													}),
													columns:[
														{
															xtype:'actioncolumn',
															width:20,
															items:[
																{
																	icon:'resources/images/icons/cross.png',
																	tooltip:i18n('remove'),
																	scope:me,
																	handler:me.onLabOrderItemRemoveClick
																}
															]
														},
														{
															header:i18n('loinc'),
															width:100,
															dataIndex:'loinc'
														},
														{
															header:i18n('description'),
															flex:1,
															dataIndex:'title'
														}
													],
													listeners:{
														scope:me,
														render:me.onOrderLabItemsGridRender
													}
												}
											]

										}
									]
								})
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
								flex:1,
								columns:[
									{
										xtype:'actioncolumn',
										width:20,
										items:[
											{
												icon:'resources/images/icons/cross.png',
												tooltip:i18n('remove'),
												scope:me,
												handler:me.onLabOrderRemoveClick
											}
										]
									},
									{
										header:i18n('date'),
										xtype: 'datecolumn',
										format:'Y-m-d H:i a',
										width:130,
										dataIndex:'date_created'
									},
									{
										header:i18n('description'),
										flex:1,
										dataIndex:'description'
									},
									{
										xtype:'actioncolumn',
										width:30,
										items:[
											{
												icon: 'resources/images/icons/preview.png',
												tooltip: i18n('view_document'),
												handler: me.onDocumentView,
												getClass:function(){
													return 'x-grid-icon-padding';
												}
											}
										]
									}
								],
								listeners:{
									scope:me,
									render:me.onLabGridRender,
									beforeedit:me.beforeXrayCtEdit,
									validateedit:me.beforeXrayCtValidEdit
								},
								plugins:Ext.create('App.ux.grid.RowFormEditing', {
									autoCancel:false,
									autoSync: true,
									errorSummary:false,
									saveBtnEnabled:true,
									clicksToEdit:2,
									formItems:[
										{

											xtype:'tabpanel',
											plain:true,
											defaults:{ bodyPadding:10 },
											items:[
												{
													title: i18n('ct_vascular_studies'),
													layout:'hbox',
													defaults:{ width:175 },
													items :[
														{
															xtype:'container',
															layout:'anchor',
															margin:'0 0 0 5',
															defaultType:'checkbox',
															items:[
																{
																	boxLabel: 'CTA Abdominal',
																	name: 'xorder_items',
																	inputValue: '74175'
																},
																{
																	boxLabel: 'CTA Pelvis',
																	name: 'xorder_items',
																	inputValue: '72191'
																},
																{
																	boxLabel: 'CTA Aorta / Iliacs ABD',
																	name: 'xorder_items',
																	inputValue: '74175-1'
																},
																{
																	boxLabel: 'CTA Aorta / Iliacs PEL',
																	name: 'xorder_items',
																	inputValue: '72191-1'
																}
															]
														},
														{
															xtype:'container',
															layout:'anchor',
															defaultType:'checkbox',
															items:[
																{
																	boxLabel: 'CTA Brain',
																	name: 'xorder_items',
																	inputValue: '70496'
																},
																{
																	boxLabel: 'CTA Carotid',
																	name: 'xorder_items',
																	inputValue: '70498'
																},
																{
																	boxLabel: 'CTA Chest',
																	name: 'xorder_items',
																	inputValue: '71275'
																},
																{
																	boxLabel: 'CTA Neck',
																	name: 'xorder_items',
																	inputValue: '71498'
																}
															]
														},
														{
															xtype:'container',
															layout:'anchor',
															width:400,
															defaultType:'checkbox',
															items:[
																{
																	boxLabel: 'CTA Thorax',
																	name: 'xorder_items',
																	inputValue: '71275-1'
																},
																{
																	boxLabel: 'CTA Run off Upper Extremity',
																	name: 'xorder_items',
																	inputValue: '73206'
																},
																{
																	boxLabel: 'CTA Run off Lower Extremity',
																	name: 'xorder_items',
																	inputValue: '73206-1'
																}
															]
														}
													]
												},
												{
													title: i18n('ct'),
													layout:'hbox',
													defaults:{ width:330 },
													items :[
														{
															xtype:'container',
															layout:'anchor',
															defaults:{
																width:330,
																labelWidth:140,
																defaultType:'checkbox',
																layout: 'hbox'
															},
															items:[
																{
																	xtype:'fieldcontainer',
																	fieldLabel: 'CT Abdomen & Pelvis',
																	defaults:{ margin:'0 5' },
																	items:[
																		{
																			boxLabel: 'W',
																			name: 'xorder_items',
																			inputValue: '74177'
																		},
																		{
																			boxLabel: 'WO',
																			name: 'xorder_items',
																			inputValue: '74176'
																		},
																		{
																			boxLabel: 'W/WO',
																			name: 'xorder_items',
																			inputValue: '74178'
																		}
																	]
																},
																{
																	xtype:'fieldcontainer',
																	fieldLabel: 'CT Abdominal',
																	defaults:{ margin:'0 5' },
																	items:[
																		{
																			boxLabel: 'W',
																			name: 'xorder_items',
																			inputValue: '74160'
																		},
																		{
																			boxLabel: 'WO',
																			name: 'xorder_items',
																			inputValue: '74150'
																		},
																		{
																			boxLabel: 'W/WO',
																			name: 'xorder_items',
																			inputValue: '74170'
																		}
																	]
																},
																{
																	xtype:'fieldcontainer',
																	fieldLabel: 'CT Pelvis',
																	defaults:{ margin:'0 5' },
																	items:[
																		{
																			boxLabel: 'W',
																			name: 'xorder_items',
																			inputValue: '72193'
																		},
																		{
																			boxLabel: 'WO',
																			name: 'xorder_items',
																			inputValue: '72192'
																		},
																		{
																			boxLabel: 'W/WO',
																			name: 'xorder_items',
																			inputValue: '72192'
																		}
																	]
																},
																{
																	xtype:'fieldcontainer',
																	fieldLabel: 'CT Cervivcal Spine',
																	defaults:{ margin:'0 5' },
																	items:[
																		{
																			boxLabel: 'W',
																			name: 'xorder_items',
																			inputValue: '72126'
																		},
																		{
																			boxLabel: 'WO',
																			name: 'xorder_items',
																			inputValue: '72125'
																		},
																		{
																			boxLabel: 'W/WO',
																			name: 'xorder_items',
																			inputValue: '72127'
																		}
																	]
																},
																{
																	xtype:'fieldcontainer',
																	fieldLabel: 'CT Chest',
																	defaults:{ margin:'0 5' },
																	items:[
																		{
																			boxLabel: 'W',
																			name: 'xorder_items',
																			inputValue: '71260'
																		},
																		{
																			boxLabel: 'WO',
																			name: 'xorder_items',
																			inputValue: '71250'
																		},
																		{
																			boxLabel: 'W/WO',
																			name: 'xorder_items',
																			inputValue: '71270'
																		}
																	]
																},
																{
																	xtype:'fieldcontainer',
																	fieldLabel: 'CT Head/Brain',
																	defaults:{ margin:'0 5' },
																	items:[
																		{
																			boxLabel: 'W',
																			name: 'xorder_items',
																			inputValue: '70460'
																		},
																		{
																			boxLabel: 'WO',
																			name: 'xorder_items',
																			inputValue: '70450'
																		},
																		{
																			boxLabel: 'W/WO',
																			name: 'xorder_items',
																			inputValue: '70470'
																		}
																	]
																}
															]
														},
														{
															xtype:'container',
															layout:'anchor',
															defaults:{
																width:330,
																labelWidth:140,
																defaultType:'checkbox',
																layout: 'hbox'
															},
															items:[
																{
																	xtype:'fieldcontainer',
																	fieldLabel: 'CT Lower Extremity',
																	defaults:{ margin:'0 5' },
																	items:[
																		{
																			boxLabel: 'W',
																			name: 'xorder_items',
																			inputValue: '73701'
																		},
																		{
																			boxLabel: 'WO',
																			name: 'xorder_items',
																			inputValue: '73700'
																		},
																		{
																			boxLabel: 'W/WO',
																			name: 'xorder_items',
																			inputValue: '73702'
																		}
																	]
																},
																{
																	xtype:'fieldcontainer',
																	fieldLabel: 'CT Lumbar Spine',
																	defaults:{ margin:'0 5' },
																	items:[
																		{
																			boxLabel: 'W',
																			name: 'xorder_items',
																			inputValue: '72132'
																		},
																		{
																			boxLabel: 'WO',
																			name: 'xorder_items',
																			inputValue: '72131'
																		},
																		{
																			boxLabel: 'W/WO',
																			name: 'xorder_items',
																			inputValue: '72133'
																		}
																	]
																},
																{
																	xtype:'fieldcontainer',
																	fieldLabel: 'CT Maxillofacial',
																	defaults:{ margin:'0 5' },
																	items:[
																		{
																			boxLabel: 'W',
																			name: 'xorder_items',
																			inputValue: '70487'
																		},
																		{
																			boxLabel: 'WO',
																			name: 'xorder_items',
																			inputValue: '70486'
																		},
																		{
																			boxLabel: 'W/WO',
																			name: 'xorder_items',
																			inputValue: '70488'
																		}
																	]
																},
																{
																	xtype:'fieldcontainer',
																	fieldLabel: 'CT Neck . Soft Tissue',
																	defaults:{ margin:'0 5' },
																	items:[
																		{
																			boxLabel: 'W',
																			name: 'xorder_items',
																			inputValue: '70491'
																		},
																		{
																			boxLabel: 'WO',
																			name: 'xorder_items',
																			inputValue: '70490'
																		},
																		{
																			boxLabel: 'W/WO',
																			name: 'xorder_items',
																			inputValue: '70492'
																		}
																	]
																},
																{
																	xtype:'fieldcontainer',
																	fieldLabel: 'CT Orbits',
																	defaults:{ margin:'0 5' },
																	items:[
																		{
																			boxLabel: 'W',
																			name: 'xorder_items',
																			inputValue: '70481'
																		},
																		{
																			boxLabel: 'WO',
																			name: 'xorder_items',
																			inputValue: '70480'
																		},
																		{
																			boxLabel: 'W/WO',
																			name: 'xorder_items',
																			inputValue: '70482'
																		}
																	]
																},
																{
																	xtype:'fieldcontainer',
																	fieldLabel: 'CT Sinus Parannasel',
																	defaults:{ margin:'0 5' },
																	items:[
																		{
																			boxLabel: 'W',
																			name: 'xorder_items',
																			inputValue: '70487'
																		},
																		{
																			boxLabel: 'WO',
																			name: 'xorder_items',
																			inputValue: '70486'
																		},
																		{
																			boxLabel: 'W/WO',
																			name: 'xorder_items',
																			inputValue: '70488'
																		}
																	]
																}
															]
														},
														{
															xtype:'container',
															layout:'anchor',
															defaults:{
																width:330,
																labelWidth:140,
																defaultType:'checkbox',
																layout: 'hbox'
															},
															items:[
																{
																	xtype:'fieldcontainer',
																	fieldLabel: 'CT Temporal Bone/AIC',
																	defaults:{ margin:'0 5' },
																	items:[
																		{
																			boxLabel: 'W',
																			name: 'xorder_items',
																			inputValue: '70481'
																		},
																		{
																			boxLabel: 'WO',
																			name: 'xorder_items',
																			inputValue: '70483'
																		},
																		{
																			boxLabel: 'W/WO',
																			name: 'xorder_items',
																			inputValue: '70482'
																		}
																	]
																},
																{
																	xtype:'fieldcontainer',
																	fieldLabel: 'CT Thoracic Spine',
																	defaults:{ margin:'0 5' },
																	items:[
																		{
																			boxLabel: 'W',
																			name: 'xorder_items',
																			inputValue: '72129'
																		},
																		{
																			boxLabel: 'WO',
																			name: 'xorder_items',
																			inputValue: '72128'
																		},
																		{
																			boxLabel: 'W/WO',
																			name: 'xorder_items',
																			inputValue: '72130'
																		}
																	]
																},
																{
																	xtype:'fieldcontainer',
																	fieldLabel: 'CT Upper Extremity',
																	defaults:{ margin:'0 5' },
																	items:[
																		{
																			boxLabel: 'W',
																			name: 'xorder_items',
																			inputValue: '73201'
																		},
																		{
																			boxLabel: 'WO',
																			name: 'xorder_items',
																			inputValue: '73200'
																		},
																		{
																			boxLabel: 'W/WO',
																			name: 'xorder_items',
																			inputValue: '73202'
																		}
																	]
																}
															]
														}
													]
												},
												{
													title: i18n('xray'),
													layout:'hbox',
													defaults:{ width:330 },
													items :[
														{
															xtype:'container',
															layout:'anchor',
															margin:'0 0 0 5',
															defaultType:'checkbox',
															items:[
																{
																	boxLabel: 'X-RAY Abdomen Flat And Erect',
																	name: 'xorder_items',
																	inputValue: '74020'
																},
																{
																	boxLabel: 'X-RAY Cervical Spine 3 Views',
																	name: 'xorder_items',
																	inputValue: '72040'
																},
																{
																	boxLabel: 'X-RAY Cervical Spine 5 Views',
																	name: 'xorder_items',
																	inputValue: '72050'
																},
																{
																	boxLabel: 'X-RAY Cervical Spine 7 Views',
																	name: 'xorder_items',
																	inputValue: '72052'
																},
																{
																	boxLabel: 'X-RAY Chest PA/LAT',
																	name: 'xorder_items',
																	inputValue: '71020'
																},
																{
																	boxLabel: 'X-RAY Kub',
																	name: 'xorder_items',
																	inputValue: '74000'
																},
																{
																	boxLabel: 'X-RAY Neck - Soft Tissue',
																	name: 'xorder_items',
																	inputValue: '70360'
																}
															]
														},
														{
															xtype:'container',
															layout:'anchor',
															defaultType:'checkbox',
															items:[
																{
																	boxLabel: 'X-RAY Lumbar 3 Views',
																	name: 'xorder_items',
																	inputValue: '72100-3'
																},
																{
																	boxLabel: 'X-RAY Lumbar 5 Views',
																	name: 'xorder_items',
																	inputValue: '72100-5'
																},
																{
																	boxLabel: 'X-RAY Lumbar 7 Views',
																	name: 'xorder_items',
																	inputValue: '72100-7'
																},
																{
																	boxLabel: 'X-RAY Orbit',
																	name: 'xorder_items',
																	inputValue: '70200'
																},
																{
																	boxLabel: 'X-RAY Pelvis 1 View',
																	name: 'xorder_items',
																	inputValue: '72170-1'
																},
																{
																	boxLabel: 'X-RAY Pelvis 2 View',
																	name: 'xorder_items',
																	inputValue: '72170-2'
																},
																{
																	boxLabel: 'X-RAY Sinus',
																	name: 'xorder_items',
																	inputValue: '70220'
																}
															]
														},
														{
															xtype:'container',
															layout:'anchor',
															width:400,
															defaultType:'checkbox',
															items:[
																{
																	boxLabel: 'X-RAY Skull 1 View',
																	name: 'xorder_items',
																	inputValue: '70250-1'
																},
																{
																	boxLabel: 'X-RAY Skull 2 View',
																	name: 'xorder_items',
																	inputValue: '70250-2'
																},
																{
																	boxLabel: 'X-RAY Skull 3 View',
																	name: 'xorder_items',
																	inputValue: '70250-3'
																},
																{
																	boxLabel: 'X-RAY Ribs - Unilateral',
																	name: 'xorder_items',
																	inputValue: '71101'
																},
																{
																	boxLabel: 'X-RAY Ribs - Bilateral',
																	name: 'xorder_items',
																	inputValue: '71111'
																},
																{
																	boxLabel: 'X-RAY Thoracic Spine',
																	name: 'xorder_items',
																	inputValue: '72070'
																}
															]
														}
													]
												}
											]

										},
										{
											xtype:'textfield',
											fieldLabel: i18n('other'),
											name: 'other_items',
											margin:'5 0 0 5',
											labelWidth:50,
											anchor:'100%',
											emptyText:i18n('other_order_item_help_text')
										},
										{
											xtype:'textfield',
											fieldLabel:i18n('notes'),
											name:'note',
											margin:'5 0 0 5',
											labelWidth:50,
											anchor:'100%'
										}
									]
								})
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
								store:me.patientPrescriptionsStore,
								flex:1,
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

									},
									{
										xtype:'actioncolumn',
										width:30,
										items:[
											{
												icon: 'resources/images/icons/preview.png',
												tooltip: i18n('view_document'),
												handler: me.onDocumentView,
												getClass:function(){
													return 'x-grid-icon-padding';
												}
											}
										]
									}

								],
								listeners:{
									scope:me,
									render:me.onPrescriptionsGridRender,
									beforeedit:me.onPrescriptionBeforeEdit,
									validateedit:me.onPrescriptionValidEdit,
									selectionchange:me.onPrescriptionSelectionChange

								},
                                plugins:Ext.create('App.ux.grid.RowFormEditing', {
                                    autoCancel:false,
                                    autoSync: true,
                                    errorSummary:false,
                                    saveBtnEnabled:true,
                                    clicksToEdit:2,
                                    formItems:[
	                                    {
		                                    xtype:'textfield',
		                                    fieldLabel:'Note',
		                                    anchor:'100%',
		                                    labelWidth:60,
		                                    name:'note'
	                                    },
                                        me.prescriptionMedicationsGrid = Ext.widget('grid',{
                                            title:i18n('medications'),
                                            store:Ext.create('Ext.data.ArrayStore', {
	                                            model: 'App.model.patient.PatientsPrescriptionMedications'
                                            }),
                                            frame:true,
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
                                                    hideable: false
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
                                                    width:200,
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
		                                            width:100,
		                                            dataIndex:'begin_date',
		                                            sortable:false,
		                                            hideable: false
	                                            },
	                                            {
		                                            header:i18n('end_date'),
		                                            width:100,
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
                                                render:me.onPrescriptionMedicationsGridRender
                                            },
                                            plugins:[
                                                Ext.create('Ext.grid.plugin.CellEditing', {
                                                    clicksToEdit:2,
                                                    errorSummary:false
                                                })
                                            ]
                                        })
                                    ]
                                })
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
	 * Adds a medication to the prescription
	 */
	addPrescriptionMedication:function(){
		var me = this,
			prescription_id = me.prescriptionsGrid.getSelectionModel().getLastSelected().data.id;
		me.prescriptionMedicationsGrid.editingPlugin.cancelEdit();
		return me.prescriptionMedicationsGrid.getStore().add({
			pid:me.pid,
			eid:me.eid,
			prescription_id:prescription_id,
			created_uid:app.user.id,
			begin_date:new Date(),
			create_date:new Date()
		});
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
			data = sm.getLastSelected().data,
			newDate = new Date(),
			addedRec, newData;

		Ext.Msg.show({
			title:'Wait!',
			msg: 'Are you sure you want to clone this prescription?',
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn:function(btn){
				if(btn == 'yes'){
					grid.editingPlugin.cancelEdit();
					sm.deselectAll();
					newData = Ext.clone(data);
					newData.id = null;
					newData.pid = me.pid;
					newData.eid = me.eid;
					newData.uid = app.user.id;
					newData.created_date = newDate;
					newData.note = data.note + ' (cloned from ' + Ext.Date.format(new Date(), 'Y-m-d') + ' )';

					addedRec = store.insert(0, newData);
					grid.editingPlugin.startEdit(0, 0);
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
		var grid = combo.up('grid'), rec;

		rec = this.addPrescriptionMedication();
		combo.reset();
		Rxnorm.getMedicationAttributesByCODE(record[0].data.CODE, function(provider, response){
			rec[0].set({
				RXCUI:record[0].data.RXCUI,
				CODE:record[0].data.CODE,
				STR:record[0].data.STR.split(',')[0],
				route:response.result.DRT,
				dose:response.result.DST,
				form:response.result.DDF
			});
		});
	},

	/**
	 * OK!
	 * @param plugin
	 * @param e
	 */
	onPrescriptionBeforeEdit:function(plugin, e){
		var me = this,
			meds = e.record.data.medications,
			medGrid = me.prescriptionMedicationsGrid,
			medStore = medGrid.getStore();

		this.fireEvent('prescriptiongridclick', e.grid, e.record);
		plugin.editor.query('button[action="update"]')[0].enable();
		medStore.removeAll();
		if(meds != '') medStore.add(meds);
	},

	/**
	 * OK!
	 * @param plugin
	 * @param e
	 */
	onPrescriptionValidEdit:function(plugin, e){
		var me = this,
			medGrid = me.prescriptionMedicationsGrid,
			medStore = medGrid.getStore(),
			records = medStore.data.items,
			meds = [];

		for(var i=0; i < records.length; i++){
			meds.push(records[i].data);
		}
		e.record.set({medications:meds});
	},

	onPrescriptionSelectionChange:function(grid, selected){
		this.cloneRxBtn.setDisabled(selected.length == 0);
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

	/**
	 * OK!
	 * adds the buttons to the header
	 */
	onPrescriptionMedicationsGridRender:function(){
		var me = this;
		me.prescriptionMedicationsGrid.dockedItems.items[0].add(
//			me.addMedicationBtn = Ext.widget('button',{
//				text:i18n('add_medication'),
//				scope:me,
//				iconCls:'icoAdd',
//				disabled:true,
//				handler:me.onAddPrescriptionMedication
//			}),
            {
                xtype:'rxnormlivetsearch',
                width:1070,
                listeners:{
                    scope:me,
                    select:me.onRxnormLiveSearchSelect
                }
            }
		);
	},

	//******************************************************************************************
	//******************************************************************************************
	//******************************************************************************************
	//******************************************************************************************
	//******************************************************************************************

	/**
	 *
	 * @param plugin
	 * @param e
	 */
	beforeXrayCtValidEdit:function(plugin, e){
		var items = plugin.editor.getForm().getValues().xorder_items,
			itemsArray = [],
			descriptionArray = [];

		for(var i=0; i < items.length; i++){
			if(items[i] != 0){
				itemsArray.push(items[i]);
				var f = plugin.editor.query('[inputValue="'+items[i]+'"]')[0];
				descriptionArray.push(f.boxLabel ? f.boxLabel : f.fieldLabel);
			}
		}
		e.record.set({order_items:itemsArray.join(','),description:descriptionArray.join(',')});
	},

	/**
	 *
	 * @param plugin
	 * @param e
	 */
	beforeXrayCtEdit:function(plugin, e){
		var itemsArray = e.record.data.order_items.split(','),
			form = plugin.editor,
			checkboxes = form.query('checkbox'),
			item;

		for(var i=0; i < checkboxes.length; i++){
			checkboxes[i].setValue(Ext.Array.indexOf(itemsArray, checkboxes[i].inputValue) != -1);
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
			date_created:new Date()
		});
		grid.editingPlugin.startEdit(0, 0);
	},

	/**
	 *
	 * @param grid
	 * @param rowIndex
	 */
	onLabOrderItemRemoveClick:function(grid, rowIndex){
		var store = grid.getStore(),
			record = store.getAt(rowIndex);
		store.remove(record);
	},

	/**
	 *
	 * @param plugin
	 * @param e
	 */
	beforeLabOrderEdit:function(plugin, e){
		var itemsGrid = plugin.editor.down('grid'),
			store = itemsGrid.getStore(),
			data = e.record.data,
			itemsArray = data.order_items ? data.order_items.split(',') : '',
			descriptionArray = data.description ? data.description.split(',') : '';

		store.removeAll();
		for(var i=0; i < itemsArray.length; i++){
			store.add({loinc:itemsArray[i], title:descriptionArray[i]});
		}
		plugin.editor.query('button[action="update"]')[0].enable();
	},

	/**
	 *
	 * @param plugin
	 * @param e
	 */
	beforeLabOrderValidEdit:function(plugin, e){
		var editor = plugin.editor,
			itemsGrid = editor.down('grid'),
			itemsRecords = itemsGrid.getStore().data.items,
			itemsArray = [],
			descriptionArray = [];

		for(var i=0; i < itemsRecords.length; i++){
			itemsArray.push(itemsRecords[i].data.loinc);
			descriptionArray.push(itemsRecords[i].data.title);
		}

		e.record.set({order_items:itemsArray.join(','), description:descriptionArray.join(',')});
	},

	onOrderLabItemsGridRender:function(grid){
		var me = this, cmb;

		cmb = Ext.create('App.ux.combo.LabsTypes',{
			width:300,
			hideLabel:false,
			labelWidth:65,
			fieldLabel:i18n('add_item'),
			listeners:{
				scope:me,
				select:function(cmb, records){
					grid.getStore().add({loinc:records[0].data.id, title:records[0].data.loinc_name});
					cmb.reset();
				}
			}
		});
		grid.dockedItems.items[0].add(cmb);
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
			handler:me.onAddOrder
		});
	},

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

	}
});