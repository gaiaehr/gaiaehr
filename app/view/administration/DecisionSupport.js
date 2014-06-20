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

Ext.define('App.view.administration.DecisionSupport', {
	extend: 'App.ux.RenderPanel',
	requires: [
		'App.ux.grid.RowFormEditing',
		'App.store.administration.DecisionSupportRules',
		'App.store.administration.DecisionSupportRulesConcepts'
	],
	itemId: 'decisionSupportAdminPanel',
	pageTitle: i18n('decision_support'),
	pageBody: [
		{
			xtype: 'grid',
			itemId: 'decisionSupportAdminGrid',
			region: 'center',
			store: Ext.create('App.store.administration.DecisionSupportRules'),
			columns: [
				{
					xtype: 'actioncolumn',
					width: 30,
					items: [
						{
							icon: 'resources/images/icons/delete.png', // Use a URL in the icon config
							tooltip: i18n('remove'),
							handler: function(grid, rowIndex, colIndex){
								var rec = grid.getStore().getAt(rowIndex);
							},
							getClass: function(){
								return 'x-grid-icon-padding';
							}
						}
					]
				},
				{
					flex: 1,
					header: i18n('description'),
					sortable: true,
					dataIndex: 'description'
				},
				{
					width: 100,
					header: i18n('age_start'),
					sortable: true,
					dataIndex: 'age_start'
				},
				{
					width: 100,
					header: i18n('age_end'),
					sortable: true,
					dataIndex: 'age_end'
				},
				{
					width: 100,
					header: i18n('sex'),
					sortable: true,
					dataIndex: 'sex'
				},
				{
					width: 100,
					header: i18n('frequency'),
					sortable: true,
					dataIndex: 'frequency'
				}
			],
			tbar: Ext.create('Ext.PagingToolbar', {
				displayInfo: true,
				emptyMsg: i18n('no_office_notes_to_display'),
				plugins: Ext.create('Ext.ux.SlidingPager'),
				items: [
					'-',
					{
						xtype: 'button',
						text: i18n('new_rule'),
						iconCls: 'icoAdd',
						itemId: 'decisionSupportRuleAddBtn'
					}
					//'-',
					//{
					//	xtype: 'mitos.preventivecaretypescombo',
					//	width: 150
					//}
				]
			}),
			plugins: Ext.create('App.ux.grid.RowFormEditing', {
				autoCancel: false,
				errorSummary: false,
				clicksToEdit: 1,
				items: [
					{
						xtype: 'tabpanel',
						action: i18n('immunizations'),
						layout: 'fit',
						items: [
							// TAB
							{
								xtype: 'container',
								title: i18n('general'),
								padding: 10,
								layout: 'vbox',
								items: [
									{
										/**
										 * line One
										 */
										xtype: 'fieldcontainer',
										layout: 'hbox',
										defaults: {
											margin: '0 10 5 0',
											action: 'field'
										},
										items: [
											{

												xtype: 'textfield',
												fieldLabel: i18n('description'),
												name: 'description',
												labelWidth: 130,
												width: 703
											},
											{
												xtype: 'gaiaehr.sexcombo',
												fieldLabel: i18n('sex'),
												name: 'sex',
												width: 100,
												labelWidth: 30

											},
											{
												fieldLabel: i18n('active'),
												xtype: 'checkboxfield',
												labelWidth: 75,
												name: 'active'
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
											margin: '0 10 5 0',
											action: 'field'
										},
										items: [
											{
												xtype: 'mitos.codestypescombo',
												fieldLabel: i18n('coding_system'),
												labelWidth: 130,
												value: 'CVX',
												name: 'coding_system',
												readOnly: true

											},
											{
												xtype: 'numberfield',
												fieldLabel: i18n('frequency'),
												margin: '0 0 5 0',
												value: 0,
												minValue: 0,
												width: 150,
												name: 'frequency'

											},
											{
												xtype: 'mitos.timecombo',
												name: 'frequency_time',
												width: 100

											},
											{
												xtype: 'numberfield',
												fieldLabel: i18n('age_start'),
												name: 'age_start',
												labelWidth: 75,
												width: 140,
												value: 0,
												minValue: 0

											},
											{
												fieldLabel: i18n('must_be_pregnant'),
												xtype: 'checkboxfield',
												labelWidth: 105,
												name: 'pregnant'

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
											margin: '0 10 5 0',
											action: 'field'
										},
										items: [
											{
												xtype: 'textfield',
												fieldLabel: i18n('code'),
												name: 'code',
												labelWidth: 130
											},
											{
												xtype: 'numberfield',
												fieldLabel: i18n('times_to_perform'),
												name: 'times_to_perform',
												width: 250,
												value: 0,
												minValue: 0,
												tooltip: i18n('greater_than_1_or_just_check_perform_once')

											},
											{

												xtype: 'numberfield',
												fieldLabel: i18n('age_end'),
												name: 'age_end',
												labelWidth: 75,
												width: 140,
												value: 0,
												minValue: 0

											},
											{
												fieldLabel: i18n('perform_only_once'),
												xtype: 'checkboxfield',
												labelWidth: 105,
												name: 'only_once'
											}
										]

									}
								]
							},
							// TAB
							{
								xtype: 'grid',
								title: i18n('active_problems'),
								action: 'problems',
								columns: [

									{
										xtype: 'actioncolumn',
										width: 20,
										items: [
											{
												icon: 'resources/images/icons/delete.png',
												tooltip: i18n('remove')
											}
										]
									},
									{
										header: i18n('code'),
										width: 100,
										dataIndex: 'code'
									},
									{
										header: i18n('description'),
										flex: 1,
										dataIndex: 'code_text'
									}
								],
								tbar: [
									'->',
									{
										xtype: 'liveicdxsearch',
										fieldLabel: i18n('add_problem'),
										hideLabel: false,
										width:600
									}
								]
							},
							// TAB
							{
								xtype: 'grid',
								title: i18n('medications'),
								action: 'medications',
								columns: [
									{
										xtype: 'actioncolumn',
										width: 20,
										items: [
											{
												icon: 'resources/images/icons/delete.png',
												tooltip: i18n('remove')
											}
										]
									},
									{
										header: i18n('code'),
										width: 100,
										dataIndex: 'code'
									},
									{
										header: i18n('description'),
										flex: 1,
										dataIndex: 'code_text'
									}
								],
								tbar: [
									'->',
									{
										xtype: 'medicationlivetsearch',
										fieldLabel: i18n('add_problem'),
										hideLabel: false,
										width:600
									}
								]
							},
							// TAB
							{
								xtype: 'grid',
								title: i18n('labs'),
								action: 'labs',
								columns: [
									{
										xtype: 'actioncolumn',
										width: 20,
										items: [
											{
												icon: 'resources/images/icons/delete.png',
												tooltip: i18n('remove')
											}
										]
									},
									{
										header: i18n('value_name'),
										flex: 1,
										dataIndex: 'value_name'
									},
									{
										header: i18n('less_than'),
										flex: 1,
										dataIndex: 'less_than',
										editor: {
											xtype: 'numberfield'
										}
									},
									{
										header: i18n('greater_than'),
										flex: 1,
										dataIndex: 'greater_than',
										editor: {
											xtype: 'numberfield'
										}
									},
									{
										header: i18n('equal_to'),
										flex: 1,
										dataIndex: 'equal_to',
										editor: {
											xtype: 'numberfield'
										}
									}
								],
								plugins: Ext.create('Ext.grid.plugin.CellEditing', {
									autoCancel: true,
									errorSummary: false,
									clicksToEdit: 2
								}),
								tbar: [
									'->',
									{
										xtype: 'labslivetsearch',
										fieldLabel: i18n('add_labs'),
										hideLabel: false,
										width:600
								    }
								]
							}
						]
					}
				]
			})
		}
	]
});