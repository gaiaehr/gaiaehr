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
	title: _('insurance_companies'),
	store: this._adminInsuranceCmonpanySotrie = Ext.create('App.store.administration.InsuranceCompanies'),
	//	border: false,
	//	frame: false,
	columnLines: true,
	plugins: [
		{
			ptype: 'rowformediting',
			autoCancel: false,
			errorSummary: false,
			items: [
				{
					xtype: 'container',
					layout: 'hbox',
					itemId: 'InsuranceCompanyFormContainer',
					items: [
						{
							xtype: 'fieldset',
							title: _('contact_info'),
							layout: 'hbox',
							margin: '0 10 0 0',
							items: [
								{
									xtype: 'container',
									margin: '0 10 0 0',
									layout: 'anchor',
									defaults: {
										margin: '0 10 5 0'
									},
									items: [
										{
											xtype: 'textfield',
											fieldLabel: _('name'),
											name: 'name',
											allowBlank: false,
											width: 385
										},
										{
											xtype: 'textfield',
											fieldLabel: _('attn'),
											name: 'attn',
											width: 385
										},
										{
											xtype: 'textfield',
											fieldLabel: _('address'),
											name: 'address1',
											width: 385
										},
										{
											xtype: 'textfield',
											fieldLabel: _('address_cont'),
											name: 'address2',
											width: 385
										},
										{
											xtype: 'fieldcontainer',
											margin: '0 0 10 105',
											layout: 'hbox',
											items: [
												{
													xtype: 'textfield',
													width: 150,
													name: 'city'
												},
												{
													xtype: 'textfield',
													width: 50,
													name: 'state'
												},
												{
													xtype: 'textfield',
													width: 75,
													name: 'zip_code'
												}
											]
										}
									]
								},
								{
									xtype: 'container',
									width: 300,
									layout: 'anchor',
									defaults: {
										margin: '0 10 5 0'
									},
									items: [
										{
											xtype: 'textfield',
											fieldLabel: _('phone_number'),
											name: 'phone_number'
										},
										{
											xtype: 'textfield',
											fieldLabel: _('fax_number'),
											name: 'fax_number'
										},
										{
											xtype: 'checkbox',
											fieldLabel: _('active'),
											name: 'active'
										}
									]
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
			width: 50,
			sortable: true,
			dataIndex: 'id'
		},
		{
			header: _('insurance_name'),
			width: 200,
			sortable: true,
			dataIndex: 'name'
		},
		{
			header: _('attn'),
			width: 200,
			sortable: true,
			dataIndex: 'attn'
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
			dataIndex: 'phone1'
		},
		{
			header: _('phone'),
			width: 120,
			sortable: true,
			dataIndex: 'phone2'
		},
		{
			header: _('fax'),
			width: 120,
			sortable: true,
			dataIndex: 'fax'
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
	tbar: Ext.create('Ext.PagingToolbar', {
		pageSize: 30,
		store: this._adminInsuranceCmonpanySotrie,
		displayInfo: true,
		plugins: Ext.create('Ext.ux.SlidingPager', {
		}),
		items: [
			'-',
			{
				text: _('insurance_company'),
				iconCls: 'icoAdd',
				action: 'insurance',
				itemId: 'addBtn'
			}]

	})
});
