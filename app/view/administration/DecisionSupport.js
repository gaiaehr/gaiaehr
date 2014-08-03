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
		'Ext.grid.plugin.CellEditing',
		'App.store.administration.DecisionSupportRules',
		'App.store.administration.DecisionSupportRulesConcepts',
		'App.ux.combo.Combo',
		'App.ux.LiveCPTSearch',
		'App.ux.LiveSnomedProblemSearch',
		'App.ux.LiveRXNORMSearch',
		'App.ux.LiveRXNORMAllergySearch'
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
							handler: function(grid, rowIndex, colIndex, item, e, record){
								App.app.getController('administration.DecisionSupport').doRemoveRule(record);
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
				},
				{
					width: 50,
					header: i18n('active'),
					dataIndex: 'active',
					renderer: function(v){
						return app.boolRenderer(v);
					}
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
				]
			}),
			plugins: Ext.create('App.ux.grid.RowFormEditing', {
				autoCancel: false,
				errorSummary: false,
				clicksToEdit: 2,
				items: [
					{
						xtype: 'tabpanel',
						action: i18n('immunizations'),
						layout: 'fit',
						itemId: 'decisionSupportEditorTabPanel',
						items: [
							// TAB General
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
											margin: '0 10 0 0',
											action: 'field'
										},
										items: [
											{
												xtype: 'textfield',
												fieldLabel: i18n('description'),
												name: 'description',
												width: 703
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
											margin: '0 10 0 0',
											action: 'field'
										},
										items: [
											{
												xtype: 'mitos.codestypescombo',
												fieldLabel: i18n('coding_system'),
												name: 'service_code_type'
											},
											{
												xtype: 'textfield',
												fieldLabel: i18n('code'),
												width: 458,
												name: 'service_code'
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
											action: 'field'
										},
										items: [
											{
												xtype: 'numberfield',
												fieldLabel: i18n('age_start'),
												name: 'age_start',

												value: 0,
												minValue: 0

											},
											{
												xtype: 'textfield',
												fieldLabel: i18n('reference'),
												name: 'reference',
												width: 458
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
											action: 'field'
										},
										items: [
											{
												xtype: 'numberfield',
												fieldLabel: i18n('age_end'),
												name: 'age_end',
												value: 0,
												minValue: 0
											},
											{
												xtype: 'gaiaehr.sexcombo',
												fieldLabel: i18n('sex'),
												name: 'sex',
												margin: '0 10 5 0'
											}
										]
									}
								]
							},
							// TAB Procedures
							{
								xtype: 'grid',
								title: i18n('procedures'),
								store: Ext.create('App.store.administration.DecisionSupportRulesConcepts'),
								action: 'PROC',
								columns: [
									{
										xtype: 'actioncolumn',
										width: 20,
										items: [
											{
												icon: 'resources/images/icons/delete.png',
												tooltip: i18n('remove'),
												handler: function(grid, rowIndex, colIndex, item, e, record){
													App.app.getController('administration.DecisionSupport').doRemoveRuleConcept(record);
												}
											}
										]
									},
									{
										header: i18n('concept'),
										flex: 1,
										dataIndex: 'concept_text'
									},
									{
										text: i18n('frequency'),
										columns: [
											{
												header: i18n('operator'),
												dataIndex: 'frequency_operator',
												width: 180,
												editor: {
													xtype: 'gaiaehr.combo',
													list: 111
												}
											},
											{
												header: i18n('frequency'),
												dataIndex: 'frequency',
												editor: {
													xtype: 'numberfield'
												}
											},
											{
												header: i18n('interval'),
												dataIndex: 'frequency_interval',
												editor: {
													xtype: 'textfield'
												}
											}
										]
									}
								],
								plugins: [
									{
										ptype: 'cellediting',
										autoCancel: true,
										errorSummary: false,
										clicksToEdit: 2
									}
								],
								tbar: [
									{
										xtype: 'livecptsearch',
										fieldLabel: i18n('add_procedure'),
										hideLabel: false,
										margin: '0 0 0 5',
										flex: 1,
										itemId: 'DecisionSupportProcedureCombo'
									}
								]
							},
							// TAB Active Problems
							{
								xtype: 'grid',
								title: i18n('active_problems'),
								store: Ext.create('App.store.administration.DecisionSupportRulesConcepts'),
								action: 'PROB',
								columns: [
									{
										xtype: 'actioncolumn',
										width: 20,
										items: [
											{
												icon: 'resources/images/icons/delete.png',
												tooltip: i18n('remove'),
												handler: function(grid, rowIndex, colIndex, item, e, record){
													App.app.getController('administration.DecisionSupport').doRemoveRuleConcept(record);
												}
											}
										]
									},
									{
										header: i18n('concept'),
										flex: 1,
										dataIndex: 'concept_text'
									},
									{
										text: i18n('frequency'),
										columns: [
											{
												header: i18n('operator'),
												dataIndex: 'frequency_operator',
												width: 180,
												editor: {
													xtype: 'gaiaehr.combo',
													list: 111
												}
											},
											{
												header: i18n('frequency'),
												dataIndex: 'frequency',
												editor: {
													xtype: 'numberfield'
												}
											},
											{
												header: i18n('interval'),
												dataIndex: 'frequency_interval',
												editor: {
													xtype: 'textfield'
												}
											}
										]
									}
								],
								plugins: [
									{
										ptype: 'cellediting',
										autoCancel: true,
										errorSummary: false,
										clicksToEdit: 2
									}
								],
								tbar: [
									{
										xtype: 'snomedliveproblemsearch',
										fieldLabel: i18n('add_problem'),
										hideLabel: false,
										margin: '0 0 0 5',
										flex: 1,
										itemId: 'DecisionSupportProblemCombo'
									}
								]
							},
							// TAB Social Lifestyle
							{
								xtype: 'grid',
								title: i18n('social_lifestyle'),
								store: Ext.create('App.store.administration.DecisionSupportRulesConcepts'),
								action: 'SOCI',
								columns: [
									{
										xtype: 'actioncolumn',
										width: 20,
										items: [
											{
												icon: 'resources/images/icons/delete.png',
												tooltip: i18n('remove'),
												handler: function(grid, rowIndex, colIndex, item, e, record){
													App.app.getController('administration.DecisionSupport').doRemoveRuleConcept(record);
												}
											}
										]
									},
									{
										header: i18n('concept'),
										flex: 1,
										dataIndex: 'concept_text'
									},
									{
										text: i18n('frequency'),
										columns: [
											{
												header: i18n('operator'),
												dataIndex: 'frequency_operator',
												width: 180,
												editor: {
													xtype: 'gaiaehr.combo',
													list: 111
												}
											},
											{
												header: i18n('frequency'),
												dataIndex: 'frequency',
												editor: {
													xtype: 'numberfield'
												}
											},
											{
												header: i18n('interval'),
												dataIndex: 'frequency_interval',
												editor: {
													xtype: 'textfield'
												}
											}
										]
									},
									{
										text: i18n('value'),
										columns: [
											{
												header: i18n('operator'),
												dataIndex: 'value_operator',
												width: 180,
												editor: {
													xtype: 'gaiaehr.combo',
													list: 111
												}
											},
											{
												header: i18n('value'),
												dataIndex: 'value',
												editor: {
													xtype: 'textfield'
												}
											}
										]
									}
								],
								plugins: [
									{
										ptype: 'cellediting',
										autoCancel: true,
										errorSummary: false,
										clicksToEdit: 2
									}
								],
								tbar: [
									{
										xtype: 'gaiaehr.combo',
										fieldLabel: i18n('add_social_lifestyle'),
										itemId: 'DecisionSupportSocialHistoryCombo',
										margin: '0 0 0 5',
										width: 350,
										list: 101
									},
									{
										xtype: 'button',
										iconCls: 'icoAdd',
										itemId: 'DecisionSupportSocialHistoryAddBtn'
									}
								]
							},
							// TAB Medications
							{
								xtype: 'grid',
								title: i18n('medications'),
								store: Ext.create('App.store.administration.DecisionSupportRulesConcepts'),
								action: 'MEDI',
								columns: [
									{
										xtype: 'actioncolumn',
										width: 20,
										items: [
											{
												icon: 'resources/images/icons/delete.png',
												tooltip: i18n('remove'),
												handler: function(grid, rowIndex, colIndex, item, e, record){
													App.app.getController('administration.DecisionSupport').doRemoveRuleConcept(record);
												}
											}
										]
									},
									{
										header: i18n('concept'),
										flex: 1,
										dataIndex: 'concept_text'
									},
									{
										text: i18n('frequency'),
										columns: [
											{
												header: i18n('operator'),
												dataIndex: 'frequency_operator',
												width: 180,
												editor: {
													xtype: 'gaiaehr.combo',
													list: 111
												}
											},
											{
												header: i18n('frequency'),
												dataIndex: 'frequency',
												editor: {
													xtype: 'numberfield'
												}
											},
											{
												header: i18n('interval'),
												dataIndex: 'frequency_interval',
												editor: {
													xtype: 'textfield'
												}
											}
										]
									},
									{
										text: i18n('value'),
										columns: [
											{
												header: i18n('operator'),
												dataIndex: 'value_operator',
												width: 180,
												editor: {
													xtype: 'gaiaehr.combo',
													list: 111
												}
											},
											{
												header: i18n('value'),
												dataIndex: 'value',
												editor: {
													xtype: 'textfield'
												}
											}
										]
									}
								],
								plugins: [
									{
										ptype: 'cellediting',
										autoCancel: true,
										errorSummary: false,
										clicksToEdit: 2
									}
								],
								tbar: [
									{
										xtype: 'rxnormlivetsearch',
										fieldLabel: i18n('add_medication'),
										hideLabel: false,
										margin: '0 0 0 5',
										flex: 1,
										itemId: 'DecisionSupportMedicationCombo'
									}
								]
							},
							// TAB Medication Allergies
							{
								xtype: 'grid',
								title: i18n('medication_allergies'),
								store: Ext.create('App.store.administration.DecisionSupportRulesConcepts'),
								action: 'ALLE',
								columns: [
									{
										xtype: 'actioncolumn',
										width: 20,
										items: [
											{
												icon: 'resources/images/icons/delete.png',
												tooltip: i18n('remove'),
												handler: function(grid, rowIndex, colIndex, item, e, record){
													App.app.getController('administration.DecisionSupport').doRemoveRuleConcept(record);
												}
											}
										]
									},
									{
										header: i18n('concept'),
										flex: 1,
										dataIndex: 'concept_text'
									},
									{
										text: i18n('frequency'),
										columns: [
											{
												header: i18n('operator'),
												dataIndex: 'frequency_operator',
												width: 180,
												editor: {
													xtype: 'gaiaehr.combo',
													list: 111
												}
											},
											{
												header: i18n('frequency'),
												dataIndex: 'frequency',
												editor: {
													xtype: 'numberfield'
												}
											},
											{
												header: i18n('interval'),
												dataIndex: 'frequency_interval',
												editor: {
													xtype: 'textfield'
												}
											}
										]
									},
									{
										text: i18n('value'),
										columns: [
											{
												header: i18n('operator'),
												dataIndex: 'value_operator',
												width: 180,
												editor: {
													xtype: 'gaiaehr.combo',
													list: 111
												}
											},
											{
												header: i18n('value'),
												dataIndex: 'value',
												editor: {
													xtype: 'textfield'
												}
											}
										]
									}
								],
								plugins: [
									{
										ptype: 'cellediting',
										autoCancel: true,
										errorSummary: false,
										clicksToEdit: 2
									}
								],
								tbar: [
									{
										xtype: 'rxnormallergylivetsearch',
										fieldLabel: i18n('add_medication'),
										hideLabel: false,
										margin: '0 0 0 5',
										flex: 1,
										itemId: 'DecisionSupportMedicationAllergyCombo'
									}
								]
							},
							// TAB LAB
							{
								xtype: 'grid',
								title: i18n('labs'),
								store: Ext.create('App.store.administration.DecisionSupportRulesConcepts'),
								action: 'LAB',
								columns: [
									{
										xtype: 'actioncolumn',
										width: 20,
										items: [
											{
												icon: 'resources/images/icons/delete.png',
												tooltip: i18n('remove'),
												handler: function(grid, rowIndex, colIndex, item, e, record){
													App.app.getController('administration.DecisionSupport').doRemoveRuleConcept(record);
												}
											}
										]
									},
									{
										header: i18n('concept'),
										flex: 1,
										dataIndex: 'concept_text'
									},
									{
										text: i18n('frequency'),
										columns: [
											{
												header: i18n('operator'),
												dataIndex: 'frequency_operator',
												width: 180,
												editor: {
													xtype: 'gaiaehr.combo',
													list: 111
												}
											},
											{
												header: i18n('frequency'),
												dataIndex: 'frequency',
												editor: {
													xtype: 'numberfield'
												}
											},
											{
												header: i18n('interval'),
												dataIndex: 'frequency_interval',
												editor: {
													xtype: 'textfield'
												}
											}
										]
									},
									{
										text: i18n('value'),
										columns: [
											{
												header: i18n('operator'),
												dataIndex: 'value_operator',
												width: 180,
												editor: {
													xtype: 'gaiaehr.combo',
													list: 111
												}
											},
											{
												header: i18n('value'),
												dataIndex: 'value',
												editor: {
													xtype: 'textfield'
												}
											}
										]
									}
								],
								plugins: [
									{
										ptype: 'cellediting',
										autoCancel: true,
										errorSummary: false,
										clicksToEdit: 2
									}
								],
								tbar: [
									{
										xtype: 'labslivetsearch',
										fieldLabel: i18n('add_labs'),
										hideLabel: false,
										margin: '0 0 0 5',
										flex: 1,
										itemId: 'DecisionSupportLabCombo'
									}
								]
							},
							// TAB
							{
								xtype: 'grid',
								title: i18n('vitals'),
								store: Ext.create('App.store.administration.DecisionSupportRulesConcepts'),
								action: 'VITA',
								columns: [
									{
										xtype: 'actioncolumn',
										width: 20,
										items: [
											{
												icon: 'resources/images/icons/delete.png',
												tooltip: i18n('remove'),
												handler: function(grid, rowIndex, colIndex, item, e, record){
													App.app.getController('administration.DecisionSupport').doRemoveRuleConcept(record);
												}
											}
										]
									},
									{
										header: i18n('concept'),
										flex: 1,
										dataIndex: 'concept_text'
									},
									{
										text: i18n('frequency'),
										columns: [
											{
												header: i18n('operator'),
												dataIndex: 'frequency_operator',
												width: 180,
												editor: {
													xtype: 'gaiaehr.combo',
													list: 111
												}
											},
											{
												header: i18n('frequency'),
												dataIndex: 'frequency',
												editor: {
													xtype: 'numberfield'
												}
											},
											{
												header: i18n('interval'),
												dataIndex: 'frequency_interval',
												editor: {
													xtype: 'textfield'
												}
											}
										]
									},
									{
										text: i18n('value'),
										columns: [
											{
												header: i18n('operator'),
												dataIndex: 'value_operator',
												width: 180,
												editor: {
													xtype: 'gaiaehr.combo',
													list: 111
												}
											},
											{
												header: i18n('value'),
												dataIndex: 'value',
												editor: {
													xtype: 'textfield'
												}
											}
										]
									}
								],
								plugins: [
									{
										ptype: 'cellediting',
										autoCancel: true,
										errorSummary: false,
										clicksToEdit: 2
									}
								],
								tbar: [
									{
										xtype: 'gaiaehr.combo',
										fieldLabel: i18n('add_vital'),
										labelWidth: 60,
										hideLabel: false,
										margin: '0 0 0 5',
										list: 110,
										width: 350,
										itemId: 'DecisionSupportVitalCombo'
									},
									{
										xtype: 'button',
										iconCls: 'icoAdd',
										itemId: 'DecisionSupportVitalAddBtn'
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