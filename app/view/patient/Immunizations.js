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

Ext.define('App.view.patient.Immunizations', {
	extend: 'Ext.panel.Panel',
	requires: [
		'App.ux.combo.CVXManufacturersForCvx',
		'App.ux.LiveImmunizationSearch',
		'App.ux.grid.RowFormEditing',
		'App.store.patient.CVXCodes'
	],
	xtype: 'patientimmunizationspanel',
	title: i18n('immunizations'),
	layout:'border',
	border:false,
	items:[
		{
			xtype: 'grid',
			region:'center',
			itemId: 'patientImmunizationsGrid',
			selModel: Ext.create('Ext.selection.CheckboxModel'),
			columnLines: true,
			store: this.store = Ext.create('App.store.patient.PatientImmunization', {
				groupField: 'vaccine_name',
				sorters: [
					'vaccine_name',
					'administered_date'
				],
				remoteFilter: true,
				autoSync: false
			}),
			features: Ext.create('Ext.grid.feature.Grouping', {
				groupHeaderTpl: i18n('immunization') + ': {name} ({rows.length} Item{[values.rows.length > 1 ? "s" : ""]})'
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
										itemId: 'immunizationsearch',
										fieldLabel: i18n('name'),
										name: 'vaccine_name',
										valueField:'name',
										hideLabel: false,
										allowBlank: false,
										enableKeyEvents: true,
										width: 570
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
									{
										xtype: 'cvxmanufacturersforcvxcombo',
										fieldLabel: i18n('manufacturer'),
										width: 260,
										name: 'manufacturer'
									},
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
			tbar:[
				'->',
				{
					text: i18n('add_new'),
					action: 'encounterRecordAdd',
					itemId: 'addImmunizationBtn',
					iconCls: 'icoAdd'
				}
			],
			bbar: [
				'-',
				{
					xtype: 'button',
					text: i18n('submit_hl7_vxu'),
					disabled: true,
					itemId: 'submitVxuBtn'
				},
				'-',
				'->',
				{
					text: i18n('review'),
					itemId: 'reviewImmunizationsBtn',
					action: 'encounterRecordAdd'
				}
			]
		},
		{
			xtype:'grid',
			title:i18n('immunization_list'),
			itemId: 'cvxGrid',
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
			]
		}
	]
});