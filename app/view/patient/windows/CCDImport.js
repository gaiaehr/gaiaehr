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
	title: _('ccd_viewer_and_import'),
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
					title: _('patient'),
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
									fieldLabel: _('name'),
									name: 'fullname'
								},
								{
									fieldLabel: _('sex'),
									name: 'sex'
								},
								{
									fieldLabel: _('dob'),
									name: 'DOB'
								},
								{
									fieldLabel: _('race'),
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
									fieldLabel: _('ethnicity'),
									name: 'ethnicity_text'
								},
								{
									fieldLabel: _('language'),
									name: 'language'
								},
								{
									fieldLabel: _('address'),
									name: 'fulladdress',
									value: 'fulladdress'
								},
								{
									fieldLabel: _('phones'),
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
					title: _('encounter'),
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
							fieldLabel: _('date'),
							name: 'service_date'
						},
						{
							fieldLabel: _('cc'),
							name: 'brief_description'
						},
						{
							xtype: 'checkboxgroup',
							fieldLabel: _('assessment'),
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
//						selType: 'checkboxmodel',
						columnLines: true,
						multiSelect: true,
						margin: '5 5 0 5'
					},
					items: [
						{
							title: _('medications'),
							store: Ext.create('App.store.patient.Medications'),
							itemId: 'CcdImportMedicationsGrid',
							selType: 'checkboxmodel',
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
							title: _('allergies'),
							store: Ext.create('App.store.patient.Allergies'),
							itemId: 'CcdImportAllergiesGrid',
							selType: 'checkboxmodel',
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
							title: _('procedures'),
							store: Ext.create('App.store.patient.encounter.Procedures'),
							itemId: 'CcdImportProceduresGrid',
							disableSelection: true,
							columns: [
								{
									text: _('description'),
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
//						selType: 'checkboxmodel',
						columnLines: true,
						multiSelect: true,
						margin: '5 5 0 0'
					},
					items: [
						{
							title: _('active_problems'),
							store: Ext.create('App.store.patient.PatientActiveProblems'),
							itemId: 'CcdImportActiveProblemsGrid',
							selType: 'checkboxmodel',
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
							title: _('results'),
							store: Ext.create('App.store.patient.PatientsOrderResults'),
							itemId: 'CcdImportOrderResultsGrid',
							disableSelection: true,
							columns: [
								{
									text: _('results'),
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
											handler: function(grid, rowIndex, colIndex, item, event, record, row){
												App.app.getController('patient.CCDImport').doResultShowObservations(Ext.get(row), record.observations());
											}
										}
									]
								}
							]
						},
						{
							title: _('encounters'),
							store: Ext.create('App.store.patient.Encounters'),
							itemId: 'CcdImportEncountersGrid',
							disableSelection: true,
							columns: [
								{
									text: _('results'),
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
					text: _('view_raw_ccd'),
					itemId: 'CcdImportWindowViewRawCcdBtn'
				},
				'-',
				{
					xtype: 'patienlivetsearch',
					emptyText: _('import_and_merge_with') + '...',
					itemId: 'CcdImportWindowPatientSearchField',
					width: 300
				},
				{
					xtype: 'checkboxfield',
					fieldLabel: _('select_all'),
					labelWidth: 55,
					labelAlign: 'right',
					itemId: 'CcdImportWindowSelectAllField'
				},
				{
					text: _('import'),
					minWidth: 70,
					itemId: 'CcdImportWindowImportBtn'
				},
				'-',
				'->',
				{
					text: _('close'),
					minWidth: 70,
					itemId: 'CcdImportWindowCloseBtn'
				}
			]
		}
	]
});