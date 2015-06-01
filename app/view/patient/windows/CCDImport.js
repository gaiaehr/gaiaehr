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
	requires: [
		'App.view.patient.windows.CCDImportPreview'
	],
	xtype: 'ccdimportwindow',
	title: _('ccd_viewer_and_import'),
	bodyStyle: 'background-color:#fff',
	modal: true,
	layout: {
		type: 'vbox',
		align: 'stretch'
	},
	width: 1500,
	maxHeight: 800,
	autoScroll: true,
	ccdData: null,
	items: [
		{
			xtype: 'container',
			layout: 'column',
			padding: 5,
			items: [
				{
					xtype: 'panel',
					title: _('import_data'),
					columnWidth: 0.5,
					frame: true,
					margin: '0 5 0 0',
					layout: {
						type: 'vbox',
						align: 'stretch'
					},
					defaults: {
						xtype: 'grid',
						height: 123,
						frame: true,
						hideHeaders: true,
						columnLines: true,
						multiSelect: true,
						margin: '0 0 5 0'
					},
					items: [
						{
							xtype: 'form',
							frame: true,
							title: _('patient'),
							itemId: 'CcdImportPatientForm',
							flex: 1,
							height: 148,
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
											fieldLabel: _('rec_num'),
											name: 'record_number'
										},
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
											name: 'DOBFormatted'
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
							margin: 0,
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
						}
					]
				},
				{
					xtype: 'panel',
					title: _('system_data'),
					columnWidth: 0.5,
					frame: true,
					layout: {
						type: 'vbox',
						align: 'stretch'
					},
					tools:[
						{
							xtype: 'patienlivetsearch',
							emptyText: _('import_and_merge_with') + '...',
							itemId: 'CcdImportWindowPatientSearchField',
							width: 300,
							height: 18
						}
					],
					defaults: {
						xtype: 'grid',
						height: 123,
						frame: true,
						hideHeaders: true,
						columnLines: true,
						multiSelect: true,
						disableSelection: true,
						margin: '0 0 5 0'
					},
					items: [
						{
							xtype: 'form',
							frame: true,
							title: _('patient'),
							itemId: 'CcdPatientPatientForm',
							flex: 1,
							height: 146,
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
											fieldLabel: _('rec_num'),
											name: 'record_number'
										},
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
											name: 'DOBFormatted'
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
							title: _('active_problems'),
							store: Ext.create('App.store.patient.PatientActiveProblems'),
							itemId: 'CcdPatientActiveProblemsGrid',
							//selType: 'checkboxmodel',
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
							title: _('medications'),
							store: Ext.create('App.store.patient.Medications'),
							itemId: 'CcdPatientMedicationsGrid',
							//selType: 'checkboxmodel',
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
							itemId: 'CcdPatientAllergiesGrid',
							//selType: 'checkboxmodel',
							margin: 0,
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
				'->',
				{
					xtype: 'checkboxfield',
					fieldLabel: _('select_all'),
					labelWidth: 55,
					labelAlign: 'right',
					itemId: 'CcdImportWindowSelectAllField'
				},
				'-',
				{
					text: _('preview'),
					minWidth: 70,
					itemId: 'CcdImportWindowPreviewBtn'
				},
				'-',
				{
					text: _('close'),
					minWidth: 70,
					itemId: 'CcdImportWindowCloseBtn'
				}
			]
		}
	]
});