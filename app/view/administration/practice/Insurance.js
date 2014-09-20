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

Ext.define('App.view.administration.practice.Insurance', {
	extend: 'Ext.grid.Panel',
	requires: [
		'App.ux.combo.Titles',
		'App.ux.grid.RowFormEditing',
		'App.ux.combo.TransmitMethod'
	],
	xtype: 'insurancecompaniespanel',
	title: i18n('insurance_companies'),
	store: Ext.create('App.store.administration.InsuranceCompanies'),
//	border: false,
//	frame: false,
	columnLines: true,
	plugins: [
		{
			ptype: 'rowformediting',
			autoCancel: false,
			errorSummary: false,
//			clicksToEdit: 1,
			items: [
				{
					xtype: 'container',
					layout: 'hbox',
					itemId: 'InsuranceCompanyFormContainer',
					width: 900,
					items: [
						{
							xtype: 'fieldcontainer',
//							width: 450,
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
									fieldLabel: i18n('attn'),
									name: 'attn',
									width: 385
								},
								{
									xtype: 'textfield',
									fieldLabel: i18n('address'),
									name: 'ins_address1',
									width: 385
								},
								{
									xtype: 'textfield',
									fieldLabel: i18n('address_cont'),
									name: 'ins_address2',
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
											xtype: 'textfield',
											width: 150,
											name: 'ins_city'
										},
										{
											xtype: 'textfield',
											width: 50,
											name: 'ins_state'
										},
										{
											xtype: 'textfield',
											width: 75,
											name: 'ins_zip_code'
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
			renderer: function(v){
				return app.boolRenderer(v);
			}
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
});
