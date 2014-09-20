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

Ext.define('App.view.administration.practice.Laboratories', {
	extend: 'Ext.grid.Panel',
	requires: [
		'App.ux.combo.Titles',
		'App.ux.grid.RowFormEditing',
		'App.ux.combo.TransmitMethod'
	],
	xtype: 'laboratoriespanel',
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
			header: i18n('active'),
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
			text: i18n('laboratory'),
			iconCls: 'icoAdd',
			action: 'laboratory',
			itemId: 'addBtn'
		}
	]
});
