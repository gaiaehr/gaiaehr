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

Ext.define('App.view.administration.practice.ProviderNumbers', {
	extend: 'Ext.grid.Panel',
	requires: [
		'App.ux.combo.Titles',
		'App.ux.grid.RowFormEditing',
		'App.ux.combo.TransmitMethod'
	],
	xtype: 'providersnumberspanel',
	title: i18n('provider_numbers'),
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
});
