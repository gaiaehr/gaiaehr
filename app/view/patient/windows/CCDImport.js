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

Ext.define('App.view.patient.windows.CCDImport', {
	extend: 'Ext.window.Window',
	xtype: 'ccdimportwindow',
	title: i18n('ccd_viewer_and_import'),
	bodyStyle: 'background-color:#fff',
	modal: true,
	layout: {
		type: 'vbox',
		align: 'stretch'
	},
	width: 1200,
	maxHeight: 700,
	autoScroll: true,
	ccdData: null,
	items: [
		{
			xtype: 'container',
			layout: 'hbox',
			items: [
				{
					xtype: 'form',
					frame: true,
					margin: '5 5 0 5',
					title: i18n('patient'),
					itemId: 'CcdImportPatientForm',
					flex: 1,
					height: 145,
					autoScroll: true,
					layout: 'column',
					items: [
						{
							xtype: 'container',
							defaults: {
								xtype: 'displayfield',
								labelWidth: 45,
								labelAlign: 'right',
								margin: 0
							},
							columnWidth: 0.5,
							items: [
								{
									fieldLabel: i18n('name'),
									name: 'fullname'
								},
								{
									fieldLabel: i18n('sex'),
									name: 'sex'
								},
								{
									fieldLabel: i18n('dob'),
									name: 'DOB'
								},
								{
									fieldLabel: i18n('race'),
									name: 'race_text'
								}
							]
						},
						{
							xtype: 'container',
							defaults: {
								xtype: 'displayfield',
								labelWidth: 60,
								labelAlign: 'right',
								margin: 0
							},
							columnWidth: 0.5,
							items: [
								{
									fieldLabel: i18n('ethnicity'),
									name: 'ethnicity_text'
								},
								{
									fieldLabel: i18n('language'),
									name: 'language'
								},
								{
									fieldLabel: i18n('address'),
									name: 'fulladdress',
									value: 'fulladdress'
								},
								{
									fieldLabel: i18n('phones'),
									name: 'phones',
									value: '000-000-000 (H)'
								}
							]
						}
					]
				},
				{
					xtype: 'form',
					frame: true,
					margin: '5 5 0 0',
					title: i18n('encounter'),
					itemId: 'CcdImportEncounterForm',
					height: 145,
					overflowY: 'auto',
					flex: 1,
					defaults: {
						xtype: 'displayfield',
						labelWidth: 75,
						labelAlign: 'right',
						margin: 0
					},
					items: [
						{
							fieldLabel: i18n('date'),
							name: 'service_date'
						},
						{
							fieldLabel: i18n('cc'),
							name: 'brief_description'
						},
						{
							xtype: 'checkboxgroup',
							fieldLabel: i18n('assessment'),
							columns: 1,
							layout: 'anchor',
							itemId: 'CcdImportEncounterAssessmentContainer'
						}
					]
				}
			]
		},
		{
			xtype: 'container',
			layout: 'column',
			margin: '0 0 5 0',
			items: [
				{
					xtype: 'container',
					columnWidth: 0.5,
					layout: {
						type: 'vbox',
						align: 'stretch'
					},
					defaults: {
						xtype: 'grid',
						height: 123,
						frame: true,
						hideHeaders: true,
						selType: 'checkboxmodel',
						columnLines: true,
						multiSelect: true,
						margin: '5 5 0 5'
					},
					items: [
						{
							title: i18n('medications'),
							store: Ext.create('App.store.patient.Medications'),
							itemId: 'CcdImportMedicationsGrid',
							columns: [
								{
									dataIndex: 'STR',
									flex: 1
								},
								{
									xtype: 'datecolumn',
									dataIndex: 'begin_date',
									width: 100,
									format: g('date_display_format')
								},
								{
									xtype: 'datecolumn',
									dataIndex: 'end_date',
									width: 100,
									format: g('date_display_format')
								}
							]
						},
						{
							title: i18n('allergies'),
							store: Ext.create('App.store.patient.Allergies'),
							itemId: 'CcdImportAllergiesGrid',
							columns: [
								{
									dataIndex: 'allergy',
									flex: 1
								},
								{
									dataIndex: 'reaction',
									width: 150
								},
								{
									dataIndex: 'severity',
									width: 100
								},
								{
									dataIndex: 'status',
									width: 60
								}
							]
						},
						{
							title: i18n('procedures'),
							store: Ext.create('App.store.patient.encounter.Procedures'),
							itemId: 'CcdImportProceduresGrid',
							columns: [
								{
									text: i18n('description'),
									dataIndex: 'code_text',
									flex: 1
								},
								{
									xtype: 'datecolumn',
									dataIndex: 'procedure_date',
									width: 100,
									format: g('date_display_format')
								}
							]
						}
					]
				},
				{
					xtype: 'container',
					columnWidth: 0.5,
					layout: {
						type: 'vbox',
						align: 'stretch'
					},
					defaults: {
						xtype: 'grid',
						height: 123,
						frame: true,
						hideHeaders: true,
						selType: 'checkboxmodel',
						columnLines: true,
						multiSelect: true,
						margin: '5 5 0 0'
					},
					items: [
						{
							title: i18n('active_problems'),
							store: Ext.create('App.store.patient.PatientActiveProblems'),
							itemId: 'CcdImportActiveProblemsGrid',
							columns: [
								{
									dataIndex: 'code_text',
									flex: 1
								},
								{
									xtype: 'datecolumn',
									dataIndex: 'begin_date',
									width: 100,
									format: g('date_display_format')
								},
								{
									xtype: 'datecolumn',
									dataIndex: 'end_date',
									width: 100,
									format: g('date_display_format')
								},
								{
									dataIndex: 'status',
									width: 60
								}
							]
						},
						{
							title: i18n('results'),
							store: Ext.create('App.store.patient.PatientsOrderResults'),
							itemId: 'CcdImportOrderResultsGrid',
							columns: [
								{
									text: i18n('results'),
									dataIndex: 'code_text',
									flex: 1
								},
								{
									xtype: 'datecolumn',
									dataIndex: 'result_date',
									width: 100,
									format: g('date_display_format')
								},
								{
									xtype: 'actioncolumn',
									width: 40,
									items: [
										{
											icon: 'resources/images/icons/icoMore.png',
											margin: '0 5',
											altText: 'More',
											getClass: function(v, metadata){
												return 'x-grid-center-icon';
											},
											handler: function(grid, rowIndex, colIndex, item, event, record){
												App.app.getController('patient.CCDimport').doResultShowObservations(record.observations());
											}
										}
									]
								}
							]
						},
						{
							title: i18n('encounters'),
							store: Ext.create('App.store.patient.Encounters'),
							itemId: 'CcdImportEncountersGrid',
							columns: [
								{
									text: i18n('results'),
									flex: 1
								}
							]
						}
					]
				}
			]
		}
	],
	dockedItems: [
		{
			xtype: 'toolbar',
			dock: 'bottom',
			ui: 'footer',
			items: [
				{
					text: i18n('view_raw_ccd'),
					itemId: 'CcdImportWindowViewRawCcdBtn'
				},
				'-',
				{
					xtype: 'patienlivetsearch',
					emptyText: i18n('import_and_merge_with') + '...',
					itemId: 'CcdImportWindowPatientSearchField',
					width: 300
				},
				{
					xtype: 'checkboxfield',
					fieldLabel: i18n('select_all'),
					labelWidth: 55,
					labelAlign: 'right',
					itemId: 'CcdImportWindowSelectAllField'
				},
				{
					text: i18n('import'),
					minWidth: 70,
					itemId: 'CcdImportWindowImportBtn'
				},
				'-',
				'->',
				{
					text: i18n('close'),
					minWidth: 70,
					itemId: 'CcdImportWindowCloseBtn'
				}
			]
		}
	]
});