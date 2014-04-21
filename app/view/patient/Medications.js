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

Ext.define('App.view.patient.Medications', {
	extend: 'Ext.panel.Panel',
	requires: [
		'App.store.patient.Medications',
		'App.store.administration.Medications',
		'Ext.form.field.Trigger',
		'Ext.grid.plugin.RowEditing',
		'App.ux.LiveRXNORMSearch',
		'App.ux.combo.PrescriptionHowTo',
		'App.ux.combo.PrescriptionTypes',
		'App.ux.LiveSigsSearch'
	],
	xtype: 'patientmedicationspanel',
	title: i18n('medications'),
	layout: 'border',
	border: false,
	columnLines: true,
	items: [
		{
			xtype: 'grid',
			region: 'center',
			action: 'patientMedicationsListGrid',
			itemId: 'patientMedicationsGrid',
			columnLines: true,
			store: Ext.create('App.store.patient.Medications', {
				remoteFilter: true,
				autoSync: false
			}),
			columns: [
				{
					header: i18n('medication'),
					flex: 1,
					minWidth: 200,
					dataIndex: 'STR',
					editor: {
						xtype: 'rxnormlivetsearch',
						itemId: 'patientMedicationLiveSearch',
						displayField: 'STR',
						valueField: 'STR',
						action: 'medication'
					}
				},
				{
					header: i18n('dose'),
					width: 125,
					dataIndex: 'dose',
					sortable: false,
					hideable: false,
					editor: {
						xtype: 'textfield'
					}
				},
				{
					header: i18n('route'),
					width: 100,
					dataIndex: 'route',
					sortable: false,
					hideable: false,
					editor: {
						xtype: 'mitos.prescriptionhowto'
					}
				},
				{
					header: i18n('form'),
					width: 125,
					dataIndex: 'form',
					sortable: false,
					hideable: false,
					editor: {
						xtype: 'mitos.prescriptiontypes'
					}
				},
				{
					header: i18n('instructions'),
					width: 200,
					dataIndex: 'prescription_when',
					sortable: false,
					hideable: false,
					editor: {
						xtype: 'livesigssearch'
					}
				},
				{
					xtype: 'datecolumn',
					format: globals['date_display_format'],
					header: i18n('begin_date'),
					width: 100,
					dataIndex: 'begin_date',
					sortable: false,
					hideable: false
				},
				{
					header: i18n('end_date'),
					width: 100,
					dataIndex: 'end_date',
					sortable: false,
					hideable: false,
					editor: {
						xtype: 'datefield',
						format: g('date_display_format')
					}
				},
				{
					header: i18n('active?'),
					width: 60,
					dataIndex: 'active',
					renderer: this.boolRenderer
				}
			],
			plugins: Ext.create('Ext.grid.plugin.RowEditing', {
				autoCancel: false,
				errorSummary: false,
				clicksToEdit: 2
			}),
			bbar: [
				'->',
				{
					text: i18n('review'),
					itemId: 'review_medications',
					action: 'encounterRecordAdd'
				}
			]
		},
		{
			xtype: 'grid',
			title: i18n('medication_list'),
			itemId: 'medicationsListGrid',
			collapseMode: 'mini',
			region: 'east',
			width: 400,
			collapsible: true,
			collapsed: true,
			split: true,
			loadMask: true,
			selModel: {
				pruneRemoved: false
			},
			viewConfig: {
				trackOver: false
			},
			verticalScroller: {
				variableRowHeight: true
			},
			store: Ext.create('App.store.administration.Medications'),
			tbar: [
				{
					xtype: 'triggerfield',
					triggerCls: Ext.baseCSSPrefix + 'form-search-trigger',
					fieldLabel: i18n('search'),
					flex: 1,
					labelWidth: 43
				}
			],
			columns: [
				{
					xtype: 'rownumberer',
					width: 50,
					sortable: false
				},
				{
					text: i18n('medication'),
					dataIndex: 'STR',
					flex: 1
				}
			]
		}
	],
	tbar: [
		'->',
		{
			text: i18n('add_new'),
			itemId: 'addPatientMedicationBtn',
			action: 'encounterRecordAdd',
			iconCls: 'icoAdd'
		}
	]


});