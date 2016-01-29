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
	title: _('active_problems'),
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
			header: _('problem'),
			flex: 1,
			dataIndex: 'code_text',
			renderer: function(v, meta, record){
				return v + ' (' + record.data.code + ')';
			}
		},
		{
			header: _('occurrence'),
			width: 200,
			dataIndex: 'occurrence'
		},
		{
			xtype: 'datecolumn',
			header: _('begin_date'),
			width: 100,
			format: 'Y-m-d',
			dataIndex: 'begin_date'
		},
		{
			xtype: 'datecolumn',
			header: _('end_date'),
			width: 100,
			format: 'Y-m-d',
			dataIndex: 'end_date'
		},
		{
			header: _('status'),
			width: 80,
			dataIndex: 'status'
		}
	],
	plugins: Ext.create('App.ux.grid.RowFormEditing', {
		autoCancel: false,
		errorSummary: false,
		clicksToEdit: 2,
		items: [
			{
				xtype:'container',
				layout:{
					type:'hbox',
					align:'stretch'
				},
				items:[
					{
						xtype: 'container',
						padding: 10,
						layout: 'vbox',
						items: [
							{
								xtype: 'snomedliveproblemsearch',
								fieldLabel: _('problem'),
								name: 'code_text',
								hideLabel: false,
								itemId: 'activeProblemLiveSearch',
								enableKeyEvents: true,
								displayField: 'FullySpecifiedName',
								valueField: 'FullySpecifiedName',
								width: 720,
								labelWidth: 70,
								margin: '0 10 5 0',
								allowBlank: false
							},
							{
								xtype: 'fieldcontainer',
								layout: 'hbox',
								defaults: {
									margin: '0 10 0 0'
								},
								items: [
									{
										fieldLabel: _('occurrence'),
										width: 250,
										labelWidth: 70,
										xtype: 'mitos.occurrencecombo',
										name: 'occurrence',
										allowBlank: false
									},
									{
										xtype: 'textfield',
										width: 460,
										labelWidth: 70,
										fieldLabel: _('referred_by'),
										name: 'referred_by'
									}
								]
							},
							{
								fieldLabel: _('note'),
								xtype: 'textfield',
								width: 720,
								labelWidth: 70,
								name: 'note'
							}
						]
					},
					{
						xtype: 'container',
						padding: 10,
						layout: 'vbox',
						defaults: {
							labelWidth: 70,
							margin: '0 0 5 0',
							width: 200
						},
						items: [
							{
								fieldLabel: _('status'),
								xtype: 'gaiaehr.combo',
								list: 112,
								itemId: 'ActiveProblemStatusCombo',
								name: 'status',
								allowBlank: false
							},
							{
								fieldLabel: _('begin_date'),
								xtype: 'datefield',
								format: 'Y-m-d',
								name: 'begin_date'
							},
							{
								fieldLabel: _('end_date'),
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
	tbar: [
		'->',
		{
			text: _('add_new'),
			action: 'encounterRecordAdd',
			itemId: 'addActiveProblemBtn',
			iconCls: 'icoAdd'
		}
	],
	bbar: ['->', {
		text: _('review'),
		itemId: 'review_active_problems',
		action: 'encounterRecordAdd'
	}]
});