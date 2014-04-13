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

		'App.ux.combo.Allergies',
		'App.ux.combo.AllergiesAbdominal',
		'App.ux.combo.AllergiesTypes',
		'App.ux.combo.AllergiesLocation',
		'App.ux.combo.AllergiesSeverity'
	],
	xtype: 'patientallergiespanel',
	title: i18n('allergies'),
	layout:'border',
	border:false,
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
			width: 375,
			dataIndex: 'allergy'
		},
		{
			header: i18n('location'),
			width: 100,
			dataIndex: 'location'
		},
		{
			header: i18n('severity'),
			flex: 1,
			dataIndex: 'severity'
		},
		{
			text: i18n('active'),
			width: 55,
			dataIndex: 'active',
			renderer: this.boolRenderer
		}
	],
	plugins: Ext.create('App.ux.grid.RowFormEditing', {
		autoCancel: false,
		errorSummary: false,
		clicksToEdit: 1,
		formItems: [
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
						items: [
							{
								xtype: 'mitos.allergiestypescombo',
								fieldLabel: i18n('type'),
								name: 'allergy_type',
								action: 'allergy_type',
								allowBlank: false,
								itemId:'allergyTypeCombo',
								width: 225,
								labelWidth: 70,
								enableKeyEvents: true
							},
							Ext.create('App.ux.combo.Allergies', {
								fieldLabel: i18n('allergy'),
								action: 'allergie_name',
								name: 'allergy',
								itemId:'allergyTypesCombo',
								enableKeyEvents: true,
								disabled: true,
								width: 550,
								labelWidth: 70
							}),
							{
								xtype:'rxnormallergylivetsearch',
								fieldLabel: i18n('allergy'),
								hideLabel: false,
								action: 'allergy',
								name: 'allergy',
								itemId:'allergyMedicationCombo',
								hidden: true,
								disabled: true,
								enableKeyEvents: true,
								width: 550,
								labelWidth: 70
							},
							{
								fieldLabel: i18n('begin_date'),
								xtype: 'datefield',
								format: 'Y-m-d',
								name: 'begin_date'

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
								xtype: 'mitos.allergieslocationcombo',
								fieldLabel: i18n('location'),
								name: 'location',
								action: 'location',
								itemId: 'allergyLocationCombo',
								width: 225,
								labelWidth: 70

							},
							Ext.create('App.ux.combo.AllergiesAbdominal', {
								xtype: 'mitos.allergiesabdominalcombo',
								fieldLabel: i18n('reaction'),
								itemId: 'allergyReactionCombo',
								name: 'reaction',
								width: 315,
								labelWidth: 70
							}),
							{
								xtype: 'mitos.allergiesseveritycombo',
								fieldLabel: i18n('severity'),
								name: 'severity',
								width: 225,
								labelWidth: 70
							},
							{
								fieldLabel: i18n('end_date'),
								xtype: 'datefield',
								format: 'Y-m-d',
								name: 'end_date'
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