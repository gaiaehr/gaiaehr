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
		'App.ux.combo.AllergiesReaction',
		'App.ux.combo.AllergiesTypes',
		'App.ux.combo.AllergiesLocation',
		'App.ux.combo.AllergiesSeverity'
	],
	xtype: 'patientallergiespanel',
	title: _('allergies'),
	columnLines: true,
	store: Ext.create('App.store.patient.Allergies', {
		remoteFilter: true,
		autoSync: false
	}),
	columns: [
		{
			text: _('type'),
			width: 100,
			dataIndex: 'allergy_type'
		},
		{
			text: _('name'),
			flex: 1,
			dataIndex: 'allergy',
			renderer:function(v, meta, record){
				var codes = '';
				if(record.data.allergy_code != ''){
					codes += ' ( <b>'+ record.data.allergy_code_type + ':</b> ' + record.data.allergy_code +' )';
				}
				return v + codes;
			}
		},
		{
			text: _('location'),
			width: 220,
			dataIndex: 'location'
		},
		{
			text: _('reaction'),
			width: 220,
			dataIndex: 'reaction'
		},
		{
			text: _('severity'),
			width: 220,
			dataIndex: 'severity'
		},
		{
			text: _('status'),
			width: 55,
			dataIndex: 'status'
		}
	],
	plugins: Ext.create('App.ux.grid.RowFormEditing', {
		autoCancel: false,
        itemId: 'allergiesGridRowEditor',
		errorSummary: false,
		clicksToEdit: 1,
		items: [
			{
				title: _('general'),
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
								fieldLabel: _('type'),
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
								fieldLabel: _('status'),
								name: 'status',
								list: 113,
								itemId: 'allergyStatusCombo',
								labelWidth: 80,
								allowBlank: false
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
								fieldLabel: _('allergy'),
								itemId: 'allergySearchCombo',
								name: 'allergy',
								hideLabel: false,
								enableKeyEvents: true,
								width: 700,
								labelWidth: 70,
								allowBlank: false
							},
							{
								xtype:'rxnormallergylivetsearch',
								fieldLabel: _('allergy'),
								itemId:'allergyMedicationCombo',
								name: 'allergy',
								hideLabel: false,
								hidden: true,
								disabled: true,
								enableKeyEvents: true,
								width: 700,
								labelWidth: 70,
								allowBlank: false
							},
							{
								fieldLabel: _('begin_date'),
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
								fieldLabel: _('location'),
								name: 'location',
								action: 'location',
								itemId: 'allergyLocationCombo',
								width: 225,
								list: 79,
								labelWidth: 70
							},
							{
								xtype: 'gaiaehr.combo',
								fieldLabel: _('reaction'),
								itemId: 'allergyReactionCombo',
								name: 'reaction',
								width: 230,
								queryMode : 'local',
								labelWidth: 70,
								allowBlank: false
							},
							{
								xtype: 'gaiaehr.combo',
								fieldLabel: _('severity'),
								name: 'severity',
								itemId: 'allergySeverityCombo',
								width: 225,
								list: 84,
								labelWidth: 70,
								allowBlank: false
							},
							{
								fieldLabel: _('end_date'),
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
			text: _('add_new'),
			itemId: 'addAllergyBtn',
			action: 'encounterRecordAdd',
			iconCls: 'icoAdd'
		}
	],
	bbar: [
		{
			text: _('only_active'),
			enableToggle: true,
			itemId: 'activeAllergyBtn'
		},
		'->',
		{
			text: _('review'),
			action: 'encounterRecordAdd',
			itemId: 'reviewAllergiesBtn'
		}
	]
});
