/*
 GaiaEHR (Electronic Health Records)
 Practice.js
 Copyright (C) 2012 Ernesto Rodriguez

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
	uses:['App.ux.combo.Titles', 'App.ux.combo.TransmitMethod', 'App.ux.combo.InsurancePayerType'],
	initComponent:function(){
		var me = this;
		/**
		 * Pharmacy Model and Store
		 */
		Ext.define('pharmacyGridModel', {
			extend:'Ext.data.Model',
			fields:[
				{ name:'id', type:'int' },
				{ name:'name', type:'string' },
				{ name:'transmit_method', type:'string' },
				{ name:'email', type:'string' },
				{ name:'address_id', type:'int' },
				{ name:'line1', type:'string' },
				{ name:'line2', type:'string' },
				{ name:'city', type:'string' },
				{ name:'state', type:'string' },
				{ name:'zip', type:'string' },
				{ name:'plus_four', type:'string' },
				{ name:'country', type:'string' },
				{ name:'address_full', type:'string' },
				{ name:'phone_id', type:'int' },
				{ name:'phone_country_code', type:'string' },
				{ name:'phone_area_code', type:'string' },
				{ name:'phone_prefix', type:'string' },
				{ name:'phone_number', type:'string' },
				{ name:'phone_full', type:'string' },
				{ name:'fax_id', type:'int' },
				{ name:'fax_area_code', type:'string' },
				{ name:'fax_prefix', type:'string' },
				{ name:'fax_number', type:'string' },
				{ name:'fax_full', type:'string' },
				{ name:'active', type:'bool' }
			],
			proxy:{
				type:'direct',
				api:{
					read:Practice.getPharmacies,
					create:Practice.addPharmacy,
					update:Practice.updatePharmacy
				}
			}
		});
		me.pharmacyStore = Ext.create('Ext.data.Store', {
			model:'pharmacyGridModel',
			remoteSort:false
		});
		/**
		 * Laboratories Model and Store
		 */
		Ext.define('laboratoriesGridModel', {
			extend:'Ext.data.Model',
			fields:[
				{ name:'id', type:'int' },
				{ name:'name', type:'string' },
				{ name:'transmit_method', type:'string' },
				{ name:'email', type:'string' },
				{ name:'address_id', type:'int' },
				{ name:'line1', type:'string' },
				{ name:'line2', type:'string' },
				{ name:'city', type:'string' },
				{ name:'state', type:'string' },
				{ name:'zip', type:'string' },
				{ name:'plus_four', type:'string' },
				{ name:'country', type:'string' },
				{ name:'address_full', type:'string' },
				{ name:'phone_id', type:'int' },
				{ name:'phone_country_code', type:'string' },
				{ name:'phone_area_code', type:'string' },
				{ name:'phone_prefix', type:'string' },
				{ name:'phone_number', type:'string' },
				{ name:'phone_full', type:'string' },
				{ name:'fax_id', type:'int' },
				{ name:'fax_area_code', type:'string' },
				{ name:'fax_prefix', type:'string' },
				{ name:'fax_number', type:'string' },
				{ name:'fax_full', type:'string' },
				{ name:'active', type:'bool' }
			],
			proxy:{
				type:'direct',
				api:{
					read:Practice.getLaboratories,
					create:Practice.addLaboratory,
					update:Practice.updateLaboratory
				}
			}
		});
		me.laboratoryStore = Ext.create('Ext.data.Store', {
			model:'laboratoriesGridModel',
			remoteSort:false
		});
		// *************************************************************************************
		// Insurance Record Structure
		// *************************************************************************************
		Ext.define('insuranceGridModel', {
			extend:'Ext.data.Model',
			fields:[
				{ name:'id', type:'int' },
				{ name:'name', type:'string' },
				{ name:'attn', type:'string' },
				{ name:'cms_id', type:'string' },
				{ name:'freeb_type', type:'string' },
				{ name:'x12_receiver_id', type:'string' },
				{ name:'x12_default_partner_id', type:'string' },
				{ name:'alt_cms_id', type:'string' },
				{ name:'address_id', type:'int' },
				{ name:'line1', type:'string' },
				{ name:'line2', type:'string' },
				{ name:'city', type:'string' },
				{ name:'state', type:'string' },
				{ name:'zip', type:'string' },
				{ name:'plus_four', type:'string' },
				{ name:'country', type:'string' },
				{ name:'address_full', type:'string' },
				{ name:'phone_id', type:'int' },
				{ name:'phone_country_code', type:'string' },
				{ name:'phone_area_code', type:'string' },
				{ name:'phone_prefix', type:'string' },
				{ name:'phone_number', type:'string' },
				{ name:'phone_full', type:'string' },
				{ name:'fax_id', type:'int' },
				{ name:'fax_country_code', type:'string' },
				{ name:'fax_area_code', type:'string' },
				{ name:'fax_prefix', type:'string' },
				{ name:'fax_number', type:'string' },
				{ name:'fax_full', type:'string' },
				{ name:'active', type:'bool' }
			],
			proxy:{
				type:'direct',
				api:{
					read:Practice.getInsurances,
					create:Practice.addInsurance,
					update:Practice.updateInsurance
				}
			}
		});
		me.insuranceStore = Ext.create('Ext.data.Store', {
			model:'insuranceGridModel',
			remoteSort:false
		});
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
					header:i18n('Fax'),
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
					action:'pharmacyGridModel',
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
					header:i18n('Fax'),
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
					action:'pharmacyGridModel',
					scope:me,
					handler:me.onNewRec
				}
			]
		});
		me.insuranceGrid = Ext.create('Ext.grid.Panel', {
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
					header:i18n('Phone'),
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
					action:'insuranceGridModel',
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
		me.x12ParnersGrid = Ext.create('Ext.grid.Panel', {
			title:i18n('x12_partners_clearing_houses'),
			//store     : me.x12PartnersStore,
			border:false,
			frame:false,
			viewConfig:{
				stripeRows:true
			},
			columns:[
				{
					text:i18n('name'),
					flex:1,
					sortable:true,
					dataIndex:'name'
				},
				{
					text:i18n('sender_id'),
					flex:1,
					width:100,
					sortable:true,
					dataIndex:'phone'
				},
				{
					text:i18n('receiver_id'),
					flex:1,
					width:100,
					sortable:true,
					dataIndex:'phone'
				},
				{
					text:i18n('version'),
					flex:1,
					width:100,
					sortable:true,
					dataIndex:'phone'
				}
			]

		});
		// *************************************************************************************
		// Tab Panel
		// *************************************************************************************
		me.praticePanel = Ext.create('Ext.tab.Panel', {
			activeTab:0,
			items:[
				me.pharmacyGrid,
				me.laboratoryGrid,
				me.insuranceGrid,
//				me.InsuranceNumbersGrid,
				me.x12ParnersGrid, {
					title:i18n('hl7_viewer'),
					frame:false,
					border:false,
					items:[
						{

						}
					],
					tbar:[
						{
							xtype:'button',
							text:i18n('clear_hl7_data'),
							iconCls:'save',
							handler:function(){
								me.onWinOpen();
							}
						},
						'-',
						{
							xtype:'button',
							text:i18n('parse_hl7'),
							iconCls:'save',
							handler:function(){
								me.onWinOpen();
							}
						}
					]
				}]
		});
		me.pageBody = [me.praticePanel];
		me.callParent(arguments);
	},
	onNewRec:function(btn){
		var me = this, grid = btn.up('grid'), store = grid.store, model = btn.action, plugin = grid.editingPlugin, newModel;
		say(grid);
		say(plugin);
		say(model);
		plugin.cancelEdit();
		newModel = Ext.ModelManager.create({
			active:1
		}, model);
		say(newModel);
		store.insert(0, newModel);
		plugin.startEdit(0, 0);
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
