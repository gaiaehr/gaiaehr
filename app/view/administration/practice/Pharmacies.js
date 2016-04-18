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

Ext.define('App.view.administration.practice.Pharmacies', {
	extend: 'Ext.grid.Panel',
	requires: [
		'App.ux.combo.Titles',
		'App.ux.grid.RowFormEditing',
		'App.ux.combo.TransmitMethod'
	],
	xtype: 'pharmaciespanel',
	title: _('pharmacies'),
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
									fieldLabel: _('name'),
									name: 'name',
									allowBlank: true,
									width: 385
								},
								{
									xtype: 'textfield',
									fieldLabel: _('address'),
									name: 'address',
									width: 385
								},
								{
									xtype: 'textfield',
									fieldLabel: _('address_cont'),
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
											value: _('city_state_zip')
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
											value: _('phone')
										},
                                        {
                                            xtype: 'textfield',
                                            width: 20,
                                            name: 'phone_country_code',
                                            maxLength: 2
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
											width: 40,
											name: 'phone_prefix'
										},
										{
											xtype: 'displayfield',
											width: 10,
											value: '-'
										},
										{
											xtype: 'textfield',
											width: 50,
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
											value: _('fax')
										},
                                        {
                                            xtype: 'textfield',
                                            width: 20,
                                            name: 'fax_country_code',
                                            maxLength: 2
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
											width: 40,
											name: 'fax_prefix'
										},
										{
											xtype: 'displayfield',
											width: 10,
											value: '-'
										},
										{
											xtype: 'textfield',
											width: 50,
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
			header: _('pharmacy_name'),
			width: 150,
			sortable: true,
			dataIndex: 'name'
		},
		{
			header: _('address'),
			flex: 1,
			sortable: true,
			dataIndex: 'address_full'
		},
		{
			header: _('phone'),
			width: 120,
			sortable: true,
			dataIndex: 'phone_full'
		},
		{
			header: _('fax'),
			width: 120,
			sortable: true,
			dataIndex: 'fax_full'
		},
		{
			header: _('default_method'),
			flex: 1,
			sortable: true,
			dataIndex: 'transmit_method',
			renderer: function (val){
				if(val == '1'){
					return 'Print';
				}else if(val == '2'){
					return 'Email';
				}else if(val == '3'){
					return 'Email';
				}
				return val;
			}
		},
		{
			header: _('active'),
			width: 55,
			sortable: true,
			dataIndex: 'active',
			renderer: function(v){
				return app.boolRenderer(v);
			}
		}
	],
	tbar: [
		'->',
		{
			text: _('pharmacy'),
			iconCls: 'icoAdd',
			action: 'pharmacy',
			itemId: 'addBtn'
		}
	]
});
