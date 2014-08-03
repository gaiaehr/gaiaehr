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

Ext.define('App.view.patient.ActiveProblems', {
	extend: 'Ext.grid.Panel',
	requires: [
		'App.ux.grid.RowFormEditing',
		'App.ux.LiveSnomedProblemSearch',
		'App.ux.combo.CodesTypes',
		'App.ux.combo.Occurrence',
		'App.ux.combo.Outcome2'
	],
	xtype: 'patientactiveproblemspanel',
	title: i18n('active_problems'),
	columnLines: true,
	store: Ext.create('App.store.patient.PatientActiveProblems', {
		remoteFilter: true,
		autoSync: false
	}),
	columns: [
		{
			xtype: 'actioncolumn',
			width: 25,
			items: [
				{
					icon: 'resources/images/icons/blueInfo.png',  // Use a URL in the icon config
					tooltip: 'Get Info',
					handler: function(grid, rowIndex, colIndex, item, e, record){
						App.app.getController('InfoButton').doGetInfo(record.data.code, record.data.code_type, record.data.code_text);
					}
				}
			]
		},
		{
			header: i18n('code'),
			width: 150,
			dataIndex: 'code',
			renderer: function(value, metaDate, record){
				return value + ' (' + record.data.code_type + ')'
			}
		},
		{
			header: i18n('problem'),
			flex: 1,
			dataIndex: 'code_text'
		},
		{
			xtype: 'datecolumn',
			header: i18n('date_diagnosed'),
			width: 100,
			format: 'Y-m-d',
			dataIndex: 'begin_date'
		},
		{
			xtype: 'datecolumn',
			header: i18n('end_date'),
			width: 100,
			format: 'Y-m-d',
			dataIndex: 'end_date'
		},
		{
			header: i18n('active?'),
			width: 60,
			dataIndex: 'active',
			renderer: function(v){
				return app.boolRenderer(v);
			}
		}
	],
	plugins: Ext.create('App.ux.grid.RowFormEditing', {
		autoCancel: false,
		errorSummary: false,
		clicksToEdit: 2,
		items: [
			{
				xtype: 'container',
				padding: 10,
				layout: 'vbox',
				items: [
					{
						xtype: 'snomedliveproblemsearch',
						fieldLabel: i18n('search'),
						name: 'code',
						hideLabel: false,
						itemId: 'activeProblemLiveSearch',
						enableKeyEvents: true,
						displayField: 'ConceptId',
						valueField: 'ConceptId',
						width: 720,
						labelWidth: 70
					},
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
								xtype: 'textfield',
								fieldLabel: i18n('problem'),
								width: 510,
								labelWidth: 70,
								allowBlank: false,
								name: 'code_text',
								action: 'code_text'
							},
							{
								fieldLabel: i18n('code_type'),
								xtype: 'textfield',
								width: 200,
								labelWidth: 100,
								name: 'code_type'

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
								fieldLabel: i18n('occurrence'),
								width: 250,
								labelWidth: 70,
								xtype: 'mitos.occurrencecombo',
								name: 'occurrence'

							},
							{
								fieldLabel: i18n('status'),
								xtype: 'gaiaehr.combo',
								list: 112,
								width: 250,
								labelWidth: 70,
								name: 'status',
								allowBlank: false

							},

							{
								fieldLabel: i18n('date_diagnosed'),
								xtype: 'datefield',
								width: 200,
								labelWidth: 100,
								format: 'Y-m-d',
								name: 'begin_date'

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
								xtype: 'textfield',
								width: 510,
								labelWidth: 70,
								fieldLabel: i18n('referred_by'),
								name: 'referred_by'
							},
							{
								fieldLabel: i18n('end_date'),
								xtype: 'datefield',
								width: 200,
								labelWidth: 100,
								format: 'Y-m-d',
								name: 'end_date'

							}
						]
					}
				]
			}
		]
	}),
	tbar: [
		'->',
		{
			text: i18n('add_new'),
			action: 'encounterRecordAdd',
			itemId: 'addActiveProblemBtn',
			iconCls: 'icoAdd'
		}
	],
	bbar: ['->', {
		text: i18n('review'),
		itemId: 'review_active_problems',
		action: 'encounterRecordAdd'
	}]
});