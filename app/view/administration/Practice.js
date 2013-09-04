/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, inc.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.view.administration.Practice', {
	extend:'App.ux.RenderPanel',
	id:'panelPractice',
	pageTitle:i18n('practice_settings'),
	uses:[
		'App.ux.combo.Titles',
		'App.ux.combo.TransmitMethod',
		'App.ux.combo.InsurancePayerType'
	],
	initComponent:function(){
		var me = this;

		me.defaultCountryCode = '+1';


        // *************************************************************************************
		// Practice Model and Store
        // *************************************************************************************
		me.pharmacyStore = Ext.create('App.store.administration.Pharmacies');
		me.laboratoryStore = Ext.create('App.store.administration.Laboratories');
		me.insuranceStore = Ext.create('App.store.administration.Insurance');

		// *************************************************************************************
		// Insurance Numbers Record Structure
		// *************************************************************************************
		//		me.insuranceNumbersStore = Ext.create('App.ux.restStoreModel', {
		//			fields     : [
		//				{name: 'id', type: 'int'},
		//				{name: 'name', type: 'string'}
		//			],
		//			model      : 'insuranceNumbersModel',
		//			idProperty : 'id',
		//			url        : 'app/administration/practice/data.php',
		//			extraParams: { task: "insuranceNumbers"}
		//		});
		// *************************************************************************************
		// X12 Partners Record Structure
		// *************************************************************************************
		//		me.x12PartnersStore = Ext.create('App.ux.restStoreModel', {
		//			fields     : [
		//				{name: 'id', type: 'int'},
		//				{name: 'name', type: 'string'}
		//			],
		//			model      : 'x12PartnersModel',
		//			idProperty : 'id',
		//			url        : 'app/administration/practice/data.php',
		//			extraParams: { task: "x12Partners"}
		//		});
		// -------------------------------------------------------------------------------------
		// render function for Default Method column in the Pharmacy grid
		// -------------------------------------------------------------------------------------

		function transmit_method(val){
			if(val == '1'){
				return 'Print';
			}else if(val == '2'){
				return 'Email';
			}else if(val == '3'){
				return 'Email';
			}
			return val;
		}

		// *************************************************************************************
		// Grids
		// *************************************************************************************
		me.pharmacyGrid = Ext.create('Ext.grid.Panel', {
			title:i18n('pharmacies'),
			store:me.pharmacyStore,
			border:false,
			frame:false,
			viewConfig:{
				stripeRows:true
			},
			plugins:[
				Ext.create('App.ux.grid.RowFormEditing', {
					autoCancel:false,
					errorSummary:false,
					clicksToEdit:1,
					formItems:[
						{
							xtype:'container',
							layout:'hbox',
//							width:900,
							items:[
								{
									xtype:'container',
									width:400,
									layout:'anchor',
									items:[
										{
											xtype:'textfield',
											fieldLabel:i18n('name'),
											name:'name',
											allowBlank:true,
											width:385
										},
										{
											xtype:'textfield',
											fieldLabel:i18n('address'),
											name:'line1',
											width:385
										},
										{
											xtype:'textfield',
											fieldLabel:i18n('address_cont'),
											name:'line2',
											width:385
										},
										{
											xtype:'fieldcontainer',
											layout:'hbox',
											defaults:{
												hideLabel:true
											},
											items:[
												{
													xtype:'displayfield',
													width:105,
													value:i18n('city_state_zip')
												},
												{
													xtype:'textfield',
													width:150,
													name:'city'
												},
												{
													xtype:'displayfield',
													width:5,
													value:','
												},
												{
													xtype:'textfield',
													width:50,
													name:'state'
												},
												{
													xtype:'textfield',
													width:75,
													name:'zip'
												}
											]
										}
									]
								},
								{
									xtype:'container',
									width:300,
									layout:'anchor',
									items:[

										{
											xtype:'fieldcontainer',
											layout:'hbox',
											defaults:{
												hideLabel:true
											},
											items:[
												{
													xtype:'displayfield',
													width:100,
													value:i18n('phone')
												},
												{
													xtype:'displayfield',
													width:6,
													value:'('
												},
												{
													xtype:'textfield',
													width:40,
													name:'phone_area_code'
												},
												{
													xtype:'displayfield',
													width:6,
													value:')'
												},
												{
													xtype:'textfield',
													width:50,
													name:'phone_prefix'
												},
												{
													xtype:'displayfield',
													width:7,
													value:'-'
												},
												{
													xtype:'textfield',
													width:70,
													name:'phone_number'
												}
											]
										},
										{
											xtype:'fieldcontainer',
											layout:'hbox',
											defaults:{
												hideLabel:true
											},
											items:[
												{
													xtype:'displayfield',
													width:100,
													value:i18n('fax')
												},
												{
													xtype:'displayfield',
													width:6,
													value:'('
												},
												{
													xtype:'textfield',
													width:40,
													name:'fax_area_code'
												},
												{
													xtype:'displayfield',
													width:6,
													value:')'
												},
												{
													xtype:'textfield',
													width:50,
													name:'fax_prefix'
												},
												{
													xtype:'displayfield',
													width:7,
													value:'-'
												},
												{
													xtype:'textfield',
													width:70,
													name:'fax_number'
												}
											]
										},
										{
											xtype:'textfield',
											fieldLabel:i18n('email'),
											name:'email',
											width:385
										},
										{
											xtype:'transmitmethodcombo',
											fieldLabel:i18n('default_method'),
											labelWidth:100,
											width:275
										}
									]
								},
//								{
//									xtype:'grid',
//									width:350,
//									height:120,
//									hideHeaders: true,
//									columnLines: true,
//									tbar:[
//										'Phones',
//										'->',
//										{
//											iconCls:'icoAdd',
//											action:'pharmacy',
//											scope:me,
//											handler:me.onAddPhone
//										}
//									],
//									plugins:[
//										{
//											ptype:'cellediting'
//										}
//									],
//									columns:[
//										{
//											text:i18n('type'),
//											dataIndex:'type',
//											width:50,
//											renderer:function(v){
//												return '(' + v + ')';
//											},
//											editor:{
//												xtype:'textfield'
//											}
//										},
//										{
//											text:i18n('country_code'),
//											dataIndex:'country_code',
//											width:30,
//											editor:{
//												xtype:'textfield'
//											}
//										},
//										{
//											text:i18n('fullnumber'),
//											dataIndex:'fullnumber',
//											flex:1,
//											editor:{
//												xtype:'textfield'
//											}
//										},
//										{
//											text:i18n('active?'),
//											dataIndex:'active',
//											width:27,
//											renderer:me.boolRenderer,
//											editor:{
//												xtype:'checkbox'
//											}
//										}
//									]
//								},
								{
									xtype:'mitos.checkbox',
									fieldLabel:i18n('active'),
							        labelWidth:60,
									margin: '0 0 0 10',
									name:'active'
								}

							]
						}
					]
				})
			],
			columns:[
				{
					header:i18n('pharmacy_name'),
					width:150,
					sortable:true,
					dataIndex:'name'
				},
				{
					header:i18n('address'),
					flex:1,
					sortable:true,
					dataIndex:'address_full'
				},
				{
					header:i18n('phone'),
					width:120,
					sortable:true,
					dataIndex:'phone_full'
				},
				{
					header:i18n('fax'),
					width:120,
					sortable:true,
					dataIndex:'fax_full'
				},
				{
					header:i18n('default_method'),
					flex:1,
					sortable:true,
					dataIndex:'transmit_method',
					renderer:transmit_method
				},
				{
					header:i18n('active'),
					width:55,
					sortable:true,
					dataIndex:'active',
					renderer:me.boolRenderer
				}
			],
			tbar:[
				{
					text:i18n('add_new_pharmacy'),
					iconCls:'save',
					action:'pharmacy',
					scope:me,
					handler:me.onNewRec
				}
			]
		});

		me.laboratoryGrid = Ext.create('Ext.grid.Panel', {
			title:i18n('laboratories'),
			store:me.laboratoryStore,
			border:false,
			frame:false,
			viewConfig:{
				stripeRows:true
			},
			plugins:[
				Ext.create('App.ux.grid.RowFormEditing', {
					autoCancel:false,
					errorSummary:false,
					clicksToEdit:1,
					formItems:[
						{
							xtype:'container',
							layout:'hbox',
							width:900,
							items:[
								{
									xtype:'container',
									width:450,
									layout:'anchor',
									items:[
										{
											xtype:'textfield',
											fieldLabel:i18n('name'),
											name:'name',
											allowBlank:false,
											width:385
										},
										{
											xtype:'textfield',
											fieldLabel:i18n('address'),
											name:'line1',
											width:385
										},
										{
											xtype:'textfield',
											fieldLabel:i18n('address_cont'),
											name:'line2',
											width:385
										},
										{
											xtype:'fieldcontainer',
											layout:'hbox',
											defaults:{
												hideLabel:true
											},
											items:[
												{
													xtype:'displayfield',
													width:105,
													value:i18n('city_state_zip')
												},
												{
													xtype:'textfield',
													width:150,
													name:'city'
												},
												{
													xtype:'displayfield',
													width:5,
													value:','
												},
												{
													xtype:'textfield',
													width:50,
													name:'state'
												},
												{
													xtype:'textfield',
													width:75,
													name:'zip'
												}
											]
										}
									]
								},
								{
									xtype:'container',
									width:300,
									layout:'anchor',
									items:[
										{
											xtype:'textfield',
											fieldLabel:i18n('email'),
											name:'email',
											width:275
										},
										{
											xtype:'fieldcontainer',
											layout:'hbox',
											defaults:{
												hideLabel:true
											},
											items:[
												{
													xtype:'displayfield',
													width:100,
													value:i18n('phone')
												},
												{
													xtype:'displayfield',
													width:5,
													value:'('
												},
												{
													xtype:'textfield',
													width:40,
													name:'phone_area_code'
												},
												{
													xtype:'displayfield',
													width:5,
													value:')'
												},
												{
													xtype:'textfield',
													width:50,
													name:'phone_prefix'
												},
												{
													xtype:'displayfield',
													width:5,
													value:'-'
												},
												{
													xtype:'textfield',
													width:70,
													name:'phone_number'
												}
											]
										},
										{
											xtype:'fieldcontainer',
											layout:'hbox',
											defaults:{
												hideLabel:true
											},
											items:[
												{
													xtype:'displayfield',
													width:100,
													value:i18n('fax')
												},
												{
													xtype:'displayfield',
													width:5,
													value:'('
												},
												{
													xtype:'textfield',
													width:40,
													name:'fax_area_code'
												},
												{
													xtype:'displayfield',
													width:5,
													value:')'
												},
												{
													xtype:'textfield',
													width:50,
													name:'fax_prefix'
												},
												{
													xtype:'displayfield',
													width:5,
													value:'-'
												},
												{
													xtype:'textfield',
													width:70,
													name:'fax_number'
												}
											]
										},
										{
											xtype:'transmitmethodcombo',
											fieldLabel:i18n('default_method'),
											labelWidth:100,
											width:275
										}
									]
								},
								{
									xtype:'mitos.checkbox',
									fieldLabel:i18n('active'),
									labelWidth:60,
									name:'active'
								}
							]
						}
					]
				})
			],
			columns:[
				{
					header:i18n('pharmacy_name'),
					width:150,
					sortable:true,
					dataIndex:'name'
				},
				{
					header:i18n('address'),
					flex:1,
					sortable:true,
					dataIndex:'address_full'
				},
				{
					header:i18n('phone'),
					width:120,
					sortable:true,
					dataIndex:'phone_full'
				},
				{
					header:i18n('fax'),
					width:120,
					sortable:true,
					dataIndex:'fax_full'
				},
				{
					header:i18n('default_method'),
					flex:1,
					sortable:true,
					dataIndex:'transmit_method',
					renderer:transmit_method
				},
				{
					header:i18n('active'),
					width:55,
					sortable:true,
					dataIndex:'active',
					renderer:me.boolRenderer
				}
			],
			tbar:[
				{
					text:i18n('add_new_laboratory'),
					iconCls:'save',
					action:'laboratory',
					scope:me,
					handler:me.onNewRec
				}
			]
		});
		me.InsuranceGrid = Ext.create('Ext.grid.Panel', {
			title:i18n('insurance_companies'),
			store:me.insuranceStore,
			border:false,
			frame:false,
			viewConfig:{
				stripeRows:true
			},
			plugins:[
				Ext.create('App.ux.grid.RowFormEditing', {
					autoCancel:false,
					errorSummary:false,
					clicksToEdit:1,
					formItems:[
						{
							xtype:'container',
							layout:'hbox',
							width:900,
							items:[
								{
									xtype:'container',
									width:450,
									layout:'anchor',
									items:[
										{
											xtype:'textfield',
											fieldLabel:i18n('name'),
											name:'name',
											allowBlank:false,
											width:385
										},
										{
											xtype:'textfield',
											fieldLabel:i18n('address'),
											name:'line1',
											width:385
										},
										{
											xtype:'textfield',
											fieldLabel:i18n('address_cont'),
											name:'line2',
											width:385
										},
										{
											xtype:'fieldcontainer',
											defaults:{
												hideLabel:true
											},
											layout:'hbox',
											items:[
												{
													xtype:'displayfield',
													width:105,
													value:i18n('city_state_zip')
												},
												{
													xtype:'textfield',
													width:150,
													name:'city'
												},
												{
													xtype:'displayfield',
													width:5,
													value:','
												},
												{
													xtype:'textfield',
													width:50,
													name:'state'
												},
												{
													xtype:'textfield',
													width:75,
													name:'zip'
												}
											]
										}
									]
								},
								{
									xtype:'container',
									width:300,
									layout:'anchor',
									items:[
										{
											xtype:'fieldcontainer',
											layout:'hbox',
											defaults:{
												hideLabel:true
											},
											items:[
												{
													xtype:'displayfield',
													width:100,
													value:i18n('phone')
												},
												{
													xtype:'displayfield',
													width:5,
													value:'('
												},
												{
													xtype:'textfield',
													width:40,
													name:'phone_area_code'
												},
												{
													xtype:'displayfield',
													width:5,
													value:')'
												},
												{
													xtype:'textfield',
													width:50,
													name:'phone_prefix'
												},
												{
													xtype:'displayfield',
													width:5,
													value:'-'
												},
												{
													xtype:'textfield',
													width:70,
													name:'phone_number'
												}
											]
										},
										{
											xtype:'fieldcontainer',
											layout:'hbox',
											defaults:{
												hideLabel:true
											},
											items:[
												{
													xtype:'displayfield',
													width:100,
													value:i18n('fax')
												},
												{
													xtype:'displayfield',
													width:5,
													value:'('
												},
												{
													xtype:'textfield',
													width:40,
													name:'fax_area_code'
												},
												{
													xtype:'displayfield',
													width:5,
													value:')'
												},
												{
													xtype:'textfield',
													width:50,
													name:'fax_prefix'
												},
												{
													xtype:'displayfield',
													width:5,
													value:'-'
												},
												{
													xtype:'textfield',
													width:70,
													name:'fax_number'
												}
											]
										},
										{
											xtype:'textfield',
											fieldLabel:i18n('cms_id'),
											name:'cms_id',
											width:275
										},
										{
											xtype:'mitos.insurancepayertypecombo',
											fieldLabel:i18n('payer_type'),
											labelWidth:100,
											width:275
										},
										{
											xtype:'textfield',
											fieldLabel:'X12 Partner',
											name:'x12_default_partner_id'
										}
									]
								},
								{
									xtype:'checkbox',
									fieldLabel:i18n('active'),
									labelWidth:60,
									name:'active'
								}
							]
						}
					]

				})
			],
			columns:[
				{
					header:i18n('insurance_name'),
					width:150,
					sortable:true,
					dataIndex:'name'
				},
				{
					header:i18n('address'),
					flex:1,
					sortable:true,
					dataIndex:'address_full'
				},
				{
					header:i18n('phone'),
					width:120,
					sortable:true,
					dataIndex:'phone_full'
				},
				{
					header:i18n('fax'),
					width:120,
					sortable:true,
					dataIndex:'fax_full'
				},
				{
					header:i18n('default_x12_partner'),
					flex:1,
					sortable:true,
					dataIndex:'x12_default_partner_id'
				},
				{
					header:i18n('active'),
					width:55,
					sortable:true,
					dataIndex:'active',
					renderer:me.boolRenderer
				}
			],
			tbar:[
				{
					text:i18n('add_new_insurance'),
					iconCls:'save',
					action:'insurance',
					scope:me,
					handler:me.onNewRec
				}
			]
		});
		//		me.InsuranceNumbersGrid = Ext.create('Ext.grid.Panel', {
		//            title    : 'Insurance Numbers',
		//			//store     : me.insuranceNumbersStore,
		//			border    : false,
		//			frame     : false,
		//            viewConfig: { stripeRows: true },
		//			columns   : [
		//				{ text: 'Name', flex: 1, sortable: true, dataIndex: 'name' },
		//				{ width: 100, sortable: true, dataIndex: 'address' },
		//				{ text: 'Provider #', flex: 1, width: 100, sortable: true, dataIndex: 'phone' },
		//				{ text: 'Rendering #', flex: 1, width: 100, sortable: true, dataIndex: 'phone' },
		//				{ text: 'Group #', flex: 1, width: 100, sortable: true, dataIndex: 'phone' }
		//			]
		//
		//		});
//		me.x12ParnersGrid = Ext.create('Ext.grid.Panel', {
//			title:i18n('x12_partners_clearing_houses'),
//			//store     : me.x12PartnersStore,
//			border:false,
//			frame:false,
//			viewConfig:{
//				stripeRows:true
//			},
//			columns:[
//				{
//					text:i18n('name'),
//					flex:1,
//					sortable:true,
//					dataIndex:'name'
//				},
//				{
//					text:i18n('sender_id'),
//					flex:1,
//					width:100,
//					sortable:true,
//					dataIndex:'phone'
//				},
//				{
//					text:i18n('receiver_id'),
//					flex:1,
//					width:100,
//					sortable:true,
//					dataIndex:'phone'
//				},
//				{
//					text:i18n('version'),
//					flex:1,
//					width:100,
//					sortable:true,
//					dataIndex:'phone'
//				}
//			]
//
//		});
		// *************************************************************************************
		// Tab Panel
		// *************************************************************************************
		me.praticePanel = Ext.create('Ext.tab.Panel', {
			activeTab:0,
			items:[
				me.pharmacyGrid,
				me.laboratoryGrid,
				me.InsuranceGrid
//				me.InsuranceNumbersGrid,
//				me.x12ParnersGrid,
//				{
//					title:i18n('hl7_viewer'),
//					frame:false,
//					border:false,
//					items:[
//						{
//
//						}
//					],
//					tbar:[
//						{
//							xtype:'button',
//							text:i18n('clear_hl7_data'),
//							iconCls:'save',
//							handler:function(){
//								me.onWinOpen();
//							}
//						},
//						'-',
//						{
//							xtype:'button',
//							text:i18n('parse_hl7'),
//							iconCls:'save',
//							handler:function(){
//								me.onWinOpen();
//							}
//						}
//					]
//				}
			]
		});
		me.pageBody = [me.praticePanel];
		me.callParent(arguments);
	},

	onNewRec:function(btn){
		var grid = btn.up('grid'),
			store = grid.store,
			model = btn.action,
			plugin = grid.editingPlugin;

		plugin.cancelEdit();

		store.insert(0,{
			active:1
		});

		plugin.startEdit(0, 0);
	},

	onAddPhone:function(btn){
		say(btn.action);

		var me = this,
			grid = btn.up('grid'),
			store = grid.getStore(),
			model = Ext.create('App.model.administration.Phone',{
				country_code: me.defaultCountryCode,
				area_code: '000',
				prefix: '000',
				number: '0000',
				type:'H',
				foreign_type:btn.action,
				active:true
			});

		grid.editingPlugin.cancelEdit();
		store.insert(0, model);
		grid.editingPlugin.startEdit(0,1);
	},

	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive:function(callback){
		this.pharmacyStore.load();
		this.insuranceStore.load();
		this.laboratoryStore.load();
		//this.insuranceNumbersStore.load();
		//this.x12PartnersStore.load();
		callback(true);
	}
});
