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

Ext.define('App.view.administration.Practice', {
	extend: 'App.ux.RenderPanel',
	xtype: 'practicepanel',
	pageTitle: i18n('practice_settings'),
	requires: [
		'App.ux.combo.Titles',
		'App.ux.combo.TransmitMethod',
		'App.ux.combo.InsurancePayerType',
		'App.ux.grid.RowFormEditing',

		'App.view.administration.ReferringProviders',
		'App.view.administration.Specialities'
	],
	initComponent: function(){
		var me = this;

		me.defaultCountryCode = '+1';

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

		me.pageBody = [
			{
				xtype: 'tabpanel',
				activeTab: 0,
				items: [

					{
						xtype: 'grid',
						title: i18n('pharmacies'),
						store: Ext.create('App.store.administration.Pharmacies'),
						border: false,
						frame: false,
						columnLines: true,
						plugins: [
							{
								ptype: 'rowformediting',
								autoCancel: false,
								errorSummary: false,
								clicksToEdit: 1,
								items: [
									{
										xtype: 'container',
										layout: 'hbox',
										items: [
											{
												xtype: 'container',
												width: 400,
												layout: 'anchor',
												items: [
													{
														xtype: 'textfield',
														fieldLabel: i18n('name'),
														name: 'name',
														allowBlank: true,
														width: 385
													},
													{
														xtype: 'textfield',
														fieldLabel: i18n('address'),
														name: 'address',
														width: 385
													},
													{
														xtype: 'textfield',
														fieldLabel: i18n('address_cont'),
														name: 'address_cont',
														width: 385
													},
													{
														xtype: 'fieldcontainer',
														layout: 'hbox',
														defaults: {
															hideLabel: true
														},
														items: [
															{
																xtype: 'displayfield',
																width: 105,
																value: i18n('city_state_zip')
															},
															{
																xtype: 'textfield',
																width: 150,
																name: 'city'
															},
															{
																xtype: 'displayfield',
																width: 5,
																value: ','
															},
															{
																xtype: 'textfield',
																width: 50,
																name: 'state'
															},
															{
																xtype: 'textfield',
																width: 75,
																name: 'zip'
															}
														]
													}
												]
											},
											{
												xtype: 'container',
												width: 300,
												layout: 'anchor',
												items: [

													{
														xtype: 'fieldcontainer',
														layout: 'hbox',
														defaults: {
															hideLabel: true
														},
														items: [
															{
																xtype: 'displayfield',
																width: 100,
																value: i18n('phone')
															},
															{
																xtype: 'textfield',
																width: 40,
																name: 'phone_area_code'
															},
															{
																xtype: 'displayfield',
																width: 10,
																value: '-'
															},
															{
																xtype: 'textfield',
																width: 50,
																name: 'phone_prefix'
															},
															{
																xtype: 'displayfield',
																width: 10,
																value: '-'
															},
															{
																xtype: 'textfield',
																width: 70,
																name: 'phone_number'
															}
														]
													},
													{
														xtype: 'fieldcontainer',
														layout: 'hbox',
														defaults: {
															hideLabel: true
														},
														items: [
															{
																xtype: 'displayfield',
																width: 100,
																value: i18n('fax')
															},
															{
																xtype: 'textfield',
																width: 40,
																name: 'fax_area_code'
															},
															{
																xtype: 'displayfield',
																width: 10,
																value: '-'
															},
															{
																xtype: 'textfield',
																width: 50,
																name: 'fax_prefix'
															},
															{
																xtype: 'displayfield',
																width: 10,
																value: '-'
															},
															{
																xtype: 'textfield',
																width: 70,
																name: 'fax_number'
															}
														]
													},
													{
														xtype: 'textfield',
														fieldLabel: _('email'),
														name: 'email',
														width: 385
													},
													{
														xtype: 'transmitmethodcombo',
														fieldLabel: _('default_method'),
														labelWidth: 100,
														width: 275
													}
												]
											},
											{
												xtype: 'checkbox',
												fieldLabel: _('active'),
												labelWidth: 60,
												margin: '0 0 0 10',
												name: 'active'
											}

										]
									}
								]
							}
						],
						columns: [
							{
								header: i18n('pharmacy_name'),
								width: 150,
								sortable: true,
								dataIndex: 'name'
							},
							{
								header: i18n('address'),
								flex: 1,
								sortable: true,
								dataIndex: 'address_full'
							},
							{
								header: i18n('phone'),
								width: 120,
								sortable: true,
								dataIndex: 'phone_full'
							},
							{
								header: i18n('fax'),
								width: 120,
								sortable: true,
								dataIndex: 'fax_full'
							},
							{
								header: i18n('default_method'),
								flex: 1,
								sortable: true,
								dataIndex: 'transmit_method',
								renderer: transmit_method
							},
							{
								header: i18n('active'),
								width: 55,
								sortable: true,
								dataIndex: 'active',
								renderer: me.boolRenderer
							}
						],
						tbar: [
							'->',
							{
								text: i18n('pharmacy'),
								iconCls: 'icoAdd',
								action: 'pharmacy',
								itemId: 'addBtn'
							}
						]
					},
					{
						xtype: 'grid',
						title: i18n('laboratories'),
						store: Ext.create('App.store.administration.Laboratories'),
						border: false,
						frame: false,
						columnLines: true,
						plugins: [
							{
								ptype: 'rowformediting',
								autoCancel: false,
								errorSummary: false,
								clicksToEdit: 1,
								items: [
									{
										xtype: 'container',
										layout: 'hbox',
										width: 900,
										items: [
											{
												xtype: 'container',
												width: 450,
												layout: 'anchor',
												items: [
													{
														xtype: 'textfield',
														fieldLabel: i18n('name'),
														name: 'name',
														allowBlank: false,
														width: 385
													},
													{
														xtype: 'textfield',
														fieldLabel: i18n('address'),
														name: 'line1',
														width: 385
													},
													{
														xtype: 'textfield',
														fieldLabel: i18n('address_cont'),
														name: 'line2',
														width: 385
													},
													{
														xtype: 'fieldcontainer',
														layout: 'hbox',
														defaults: {
															hideLabel: true
														},
														items: [
															{
																xtype: 'displayfield',
																width: 105,
																value: i18n('city_state_zip')
															},
															{
																xtype: 'textfield',
																width: 150,
																name: 'city'
															},
															{
																xtype: 'displayfield',
																width: 5,
																value: ','
															},
															{
																xtype: 'textfield',
																width: 50,
																name: 'state'
															},
															{
																xtype: 'textfield',
																width: 75,
																name: 'zip'
															}
														]
													}
												]
											},
											{
												xtype: 'container',
												width: 300,
												layout: 'anchor',
												items: [
													{
														xtype: 'textfield',
														fieldLabel: i18n('email'),
														name: 'email',
														width: 275
													},
													{
														xtype: 'fieldcontainer',
														layout: 'hbox',
														defaults: {
															hideLabel: true
														},
														items: [
															{
																xtype: 'displayfield',
																width: 100,
																value: i18n('phone')
															},
															{
																xtype: 'textfield',
																width: 40,
																name: 'phone_area_code'
															},
															{
																xtype: 'displayfield',
																width: 10,
																value: '-'
															},
															{
																xtype: 'textfield',
																width: 50,
																name: 'phone_prefix'
															},
															{
																xtype: 'displayfield',
																width: 10,
																value: '-'
															},
															{
																xtype: 'textfield',
																width: 70,
																name: 'phone_number'
															}
														]
													},
													{
														xtype: 'fieldcontainer',
														layout: 'hbox',
														defaults: {
															hideLabel: true
														},
														items: [
															{
																xtype: 'displayfield',
																width: 100,
																value: i18n('fax')
															},
															{
																xtype: 'textfield',
																width: 40,
																name: 'fax_area_code'
															},
															{
																xtype: 'displayfield',
																width: 10,
																value: '-'
															},
															{
																xtype: 'textfield',
																width: 50,
																name: 'fax_prefix'
															},
															{
																xtype: 'displayfield',
																width: 10,
																value: '-'
															},
															{
																xtype: 'textfield',
																width: 70,
																name: 'fax_number'
															}
														]
													},
													{
														xtype: 'transmitmethodcombo',
														fieldLabel: i18n('default_method'),
														labelWidth: 100,
														width: 275
													}
												]
											},
											{
												xtype: 'checkbox',
												fieldLabel: i18n('active'),
												labelWidth: 60,
												name: 'active'
											}
										]
									}
								]
							}
						],
						columns: [
							{
								header: i18n('pharmacy_name'),
								width: 150,
								sortable: true,
								dataIndex: 'name'
							},
							{
								header: i18n('address'),
								flex: 1,
								sortable: true,
								dataIndex: 'address_full'
							},
							{
								header: i18n('phone'),
								width: 120,
								sortable: true,
								dataIndex: 'phone_full'
							},
							{
								header: i18n('fax'),
								width: 120,
								sortable: true,
								dataIndex: 'fax_full'
							},
							{
								header: i18n('default_method'),
								flex: 1,
								sortable: true,
								dataIndex: 'transmit_method',
								renderer: transmit_method
							},
							{
								header: i18n('active'),
								width: 55,
								sortable: true,
								dataIndex: 'active',
								renderer: me.boolRenderer
							}
						],
						tbar: [
							'->',
							{
								text: i18n('laboratory'),
								iconCls: 'icoAdd',
								action: 'laboratory',
								itemId: 'addBtn'
							}
						]
					},
					{
						xtype: 'grid',
						title: i18n('insurance_companies'),
						store: Ext.create('App.store.administration.InsuranceCompanies'),
						border: false,
						frame: false,
						columnLines: true,
						plugins: [
							{
								ptype: 'rowformediting',
								autoCancel: false,
								errorSummary: false,
								clicksToEdit: 1,
								items: [
									{
										xtype: 'container',
										layout: 'hbox',
										width: 900,
										items: [
											{
												xtype: 'container',
												width: 450,
												layout: 'anchor',
												items: [
													{
														xtype: 'textfield',
														fieldLabel: i18n('name'),
														name: 'name',
														allowBlank: false,
														width: 385
													},
													{
														xtype: 'textfield',
														fieldLabel: i18n('address'),
														name: 'line1',
														width: 385
													},
													{
														xtype: 'textfield',
														fieldLabel: i18n('address_cont'),
														name: 'line2',
														width: 385
													},
													{
														xtype: 'fieldcontainer',
														defaults: {
															hideLabel: true
														},
														layout: 'hbox',
														items: [
															{
																xtype: 'displayfield',
																width: 105,
																value: i18n('city_state_zip')
															},
															{
																xtype: 'textfield',
																width: 150,
																name: 'city'
															},
															{
																xtype: 'displayfield',
																width: 5,
																value: ','
															},
															{
																xtype: 'textfield',
																width: 50,
																name: 'state'
															},
															{
																xtype: 'textfield',
																width: 75,
																name: 'zip'
															}
														]
													}
												]
											},
											{
												xtype: 'container',
												width: 300,
												layout: 'anchor',
												items: [
													{
														xtype: 'fieldcontainer',
														layout: 'hbox',
														defaults: {
															hideLabel: true
														},
														items: [
															{
																xtype: 'displayfield',
																width: 100,
																value: i18n('phone')
															},
															{
																xtype: 'textfield',
																width: 40,
																name: 'phone_area_code'
															},
															{
																xtype: 'displayfield',
																width: 10,
																value: '-'
															},
															{
																xtype: 'textfield',
																width: 50,
																name: 'phone_prefix'
															},
															{
																xtype: 'displayfield',
																width: 10,
																value: '-'
															},
															{
																xtype: 'textfield',
																width: 70,
																name: 'phone_number'
															}
														]
													},
													{
														xtype: 'fieldcontainer',
														layout: 'hbox',
														defaults: {
															hideLabel: true
														},
														items: [
															{
																xtype: 'displayfield',
																width: 100,
																value: i18n('fax')
															},
															{
																xtype: 'textfield',
																width: 40,
																name: 'fax_area_code'
															},
															{
																xtype: 'displayfield',
																width: 10,
																value: '-'
															},
															{
																xtype: 'textfield',
																width: 50,
																name: 'fax_prefix'
															},
															{
																xtype: 'displayfield',
																width: 10,
																value: '-'
															},
															{
																xtype: 'textfield',
																width: 70,
																name: 'fax_number'
															}
														]
													},
													{
														xtype: 'textfield',
														fieldLabel: i18n('cms_id'),
														name: 'cms_id',
														width: 275
													},
													{
														xtype: 'mitos.insurancepayertypecombo',
														fieldLabel: i18n('payer_type'),
														labelWidth: 100,
														width: 275
													},
													{
														xtype: 'textfield',
														fieldLabel: 'X12 Partner',
														name: 'x12_default_partner_id'
													}
												]
											},
											{
												xtype: 'checkbox',
												fieldLabel: i18n('active'),
												labelWidth: 60,
												name: 'active'
											}
										]
									}
								]

							}
						],
						columns: [
							{
								header: i18n('insurance_name'),
								width: 150,
								sortable: true,
								dataIndex: 'name'
							},
							{
								header: i18n('address'),
								flex: 1,
								sortable: true,
								dataIndex: 'address_full'
							},
							{
								header: i18n('phone'),
								width: 120,
								sortable: true,
								dataIndex: 'phone_full'
							},
							{
								header: i18n('fax'),
								width: 120,
								sortable: true,
								dataIndex: 'fax_full'
							},
							{
								header: i18n('default_x12_partner'),
								flex: 1,
								sortable: true,
								dataIndex: 'x12_default_partner_id'
							},
							{
								header: i18n('active'),
								width: 55,
								sortable: true,
								dataIndex: 'active',
								renderer: me.boolRenderer
							}
						],
						tbar: [
							'->',
							{
								text: i18n('insurance_company'),
								iconCls: 'icoAdd',
								action: 'insurance',
								itemId: 'addBtn'
							}
						]
					},
					{
						xtype: 'grid',
						title: i18n('insurance_numbers'),
						store: Ext.create('App.store.administration.InsuranceNumbers'),
						border: false,
						frame: false,
						columnLines: true,
						features: [
							{
								ftype: 'grouping',
								groupHeaderTpl: '{name}'
							}
						],
						plugins: [
							{
								ptype: 'rowformediting',
								autoCancel: false,
								errorSummary: false,
								clicksToEdit: 1,
								items: [
									{
										xtype: 'container',
										layout: 'column',
										items: [
											{
												xtype: 'container',
												defaults:{
													labelWidth: 140
												},
												margin: '0 10 0 0',
												items: [
													{
														xtype: 'textfield',
														fieldLabel: i18n('provider'),
														name: 'provider_id'
													},
													{
														xtype: 'textfield',
														fieldLabel: i18n('provider_number'),
														name: 'provider_number'
													},
													{
														xtype: 'textfield',
														fieldLabel: i18n('provider_number_type'),
														name: 'provider_number_type'
													}
												]
											},
											{
												xtype: 'container',
												defaults:{
													labelWidth: 140
												},
												margin: '0 10 0 0',
												items: [
													{
														xtype: 'textfield',
														fieldLabel: i18n('insurance_company'),
														name: 'insurance_company_id'
													},
													{
														xtype: 'textfield',
														fieldLabel: i18n('rendering_number'),
														name: 'rendering_provider_number'
													},
													{
														xtype: 'textfield',
														fieldLabel: i18n('rendering_number_type'),
														name: 'rendering_provider_number_type'
													}
												]
											},
											{
												xtype: 'container',
												defaults:{
													labelWidth: 140
												},
												items: [
													{
														xtype: 'textfield',
														fieldLabel: i18n('group_number'),
														name: 'group_number'
													}
												]
											}
										]
									}
								]
							}
						],
						columns: [
							{
								text: i18n('provider'),
								flex: 1,
								sortable: true,
								dataIndex: 'provider_id_text'
							},
							{
								text: i18n('insurance'),
								flex: 1,
								sortable: true,
								dataIndex: 'insurance_company_id_text'
							},
							{
								text: i18n('provider_number'),
								flex: 1,
								sortable: true,
								dataIndex: 'provider_number'
							},
							{
								text: i18n('rendering_number'),
								flex: 1,
								sortable: true,
								dataIndex: 'rendering_number'
							},
							{
								text: i18n('group_number'),
								flex: 1,
								sortable: true,
								dataIndex: 'phone'
							}
						],
						tbar: [
							i18n('group_by'),
							{
								text: i18n('provider'),
								enableToggle: true,
								toggleGroup: 'insurance_number_group',
								action: 'provider_id_text'
							},
							{
								text: i18n('insurance'),
								enableToggle: true,
								toggleGroup: 'insurance_number_group',
								action: 'insurance_company_id_text'
							},
							'-',
							'->',
							{
								text: i18n('insurance_number'),
								iconCls: 'icoAdd',
								action: 'insurance',
								itemId: 'addBtn'
							}
						]

					},
					{
						xtype: 'referringproviderspanel'
					},
					{
						xtype: 'specialitiespanel'
					}
				]

			}
		];

		me.callParent(arguments);
	}
});
