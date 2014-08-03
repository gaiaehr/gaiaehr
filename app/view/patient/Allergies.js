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

Ext.define('App.view.patient.Allergies', {
	extend: 'Ext.grid.Panel',
	requires: [
		'App.store.patient.Allergies',
		'App.ux.grid.RowFormEditing',
		'App.ux.LiveRXNORMAllergySearch',
		'App.ux.LiveAllergiesSearch',

		'App.ux.combo.Allergies',
		'App.ux.combo.AllergiesAbdominal',
		'App.ux.combo.AllergiesTypes',
		'App.ux.combo.AllergiesLocation',
		'App.ux.combo.AllergiesSeverity'
	],
	xtype: 'patientallergiespanel',
	title: i18n('allergies'),
	layout:'border',
	columnLines: true,
	store: Ext.create('App.store.patient.Allergies', {
		remoteFilter: true,
		autoSync: false
	}),
	columns: [
		{
			header: i18n('type'),
			width: 100,
			dataIndex: 'allergy_type'
		},
		{
			header: i18n('name'),
			flex: 1,
			dataIndex: 'allergy',
			renderer:function(v, meta, record){
				return v + (record.data.allergy_code == '' ? '' : ' ('+ record.data.allergy_code +')');
			}
		},
		{
			header: i18n('location'),
			width: 220,
			dataIndex: 'location'
		},
		{
			header: i18n('reaction'),
			width: 220,
			dataIndex: 'reaction'
		},
		{
			header: i18n('severity'),
			width: 220,
			dataIndex: 'severity'
		},
		{
			text: i18n('status'),
			width: 55,
			dataIndex: 'status'
		}
	],
	plugins: Ext.create('App.ux.grid.RowFormEditing', {
		autoCancel: false,
		errorSummary: false,
		clicksToEdit: 1,
		items: [
			{
				title: i18n('general'),
				xtype: 'container',
				padding: '0 10',
				layout: 'vbox',
				items: [
					{
						/**
						 * Line one
						 */
						xtype: 'fieldcontainer',
						layout: 'hbox',
						defaults: {
							margin: '0 10 0 0'
						},
						items:[
							{
								xtype: 'gaiaehr.combo',
								fieldLabel: i18n('type'),
								itemId:'allergyTypeCombo',
								name: 'allergy_type',
								allowBlank: false,
								labelWidth: 70,
								width: 700,
								list: 85,
								enableKeyEvents: true
							},
							{
								xtype: 'gaiaehr.combo',
								fieldLabel: i18n('status'),
								name: 'status',
								list: 113,
								itemId: 'allergyStatusCombo',
								labelWidth: 80
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
							margin: '0 10 0 0'
						},
						items: [
							{
								xtype: 'allergieslivesearch',
								fieldLabel: i18n('allergy'),
								itemId: 'allergySearchCombo',
								name: 'allergy',
								hideLabel: false,
//								disabled: true,
								enableKeyEvents: true,
								width: 700,
								labelWidth: 70
							},
							{
								xtype:'rxnormallergylivetsearch',
								fieldLabel: i18n('allergy'),
								itemId:'allergyMedicationCombo',
								name: 'allergy',
								hideLabel: false,
								hidden: true,
								disabled: true,
								enableKeyEvents: true,
								width: 700,
								labelWidth: 70
							},
							{
								fieldLabel: i18n('begin_date'),
								xtype: 'datefield',
								format: 'Y-m-d',
								name: 'begin_date',
								labelWidth: 80

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
							margin: '0 10 0 0'
						},
						items: [
							{
								xtype: 'gaiaehr.combo',
								fieldLabel: i18n('location'),
								name: 'location',
								action: 'location',
								itemId: 'allergyLocationCombo',
								width: 225,
								list: 79,
								labelWidth: 70

							},
							{
								xtype: 'gaiaehr.combo',
								fieldLabel: i18n('reaction'),
								itemId: 'allergyReactionCombo',
								name: 'reaction',
								width: 230,
								list: 82,
								labelWidth: 70
							},
							{
								xtype: 'gaiaehr.combo',
								fieldLabel: i18n('severity'),
								name: 'severity',
								itemId: 'allergySeverityCombo',
								width: 225,
								list: 84,
								labelWidth: 70
							},
							{
								fieldLabel: i18n('end_date'),
								xtype: 'datefield',
								format: 'Y-m-d',
								name: 'end_date',
								labelWidth: 80
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
			itemId: 'addAllergyBtn',
			action: 'encounterRecordAdd',
			iconCls: 'icoAdd'
		}
	],
	bbar: [
		{
			text: i18n('only_active'),
			enableToggle: true,
			itemId: 'activeAllergyBtn'
		},
		'->',
		{
			text: i18n('review'),
			action: 'encounterRecordAdd',
			itemId: 'reviewAllergiesBtn'
		}
	]


});