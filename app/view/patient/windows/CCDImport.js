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
	autoScroll: true,
	ccdData: null,
	items: [
		{
			xtype: 'form',
			layout: 'hbox',
			frame: true,
			margin: '5 5 0 5',
			itemId: 'CcdImportForm',
			items: [
				{
					xtype: 'fieldset',
					title: i18n('patient'),
					flex: 2,
					margin: 5,
					defaults: {
						margin: '0 5 0 0',
						height: 120
					},
					layout: 'column',
					items: [
						{
							xtype: 'container',
							defaults: {
								xtype: 'displayfield',
								labelWidth: 45,
								labelAlign: 'right'
							},
							columnWidth: 0.5,
							items: [
								{
									fieldLabel: i18n('name'),
									name: 'fullname',
									value: 'fullname'
								},
								{
									fieldLabel: i18n('sex'),
									name: 'sex',
									value: 'sex'
								},
								{
									fieldLabel: i18n('dob'),
									name: 'DOB',
									value: 'DOB'
								},
								{
									fieldLabel: i18n('race'),
									name: 'race',
									value: 'race'
								}
							]
						},
						{
							xtype: 'container',
							defaults: {
								xtype: 'displayfield',
								labelWidth: 60,
								labelAlign: 'right'
							},
							columnWidth: 0.5,
							items: [
								{
									fieldLabel: i18n('ethnicity'),
									name: 'ethnicity',
									value: 'ethnicity'
								},
								{
									fieldLabel: i18n('language'),
									name: 'language',
									value: 'language'
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
					xtype: 'fieldset',
					title: i18n('author'),
					flex: 1,
					margin: '5 5 5 0',
					height: 120
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
						height: 120,
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
									text: i18n('description'),
									flex: 1
								}
							]
						},
						{
							title: i18n('allergies'),
							store: Ext.create('App.store.patient.Allergies'),
							itemId: 'CcdImportAllergiesGrid',
							columns: [
								{
									text: i18n('description'),
									flex: 1
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
									flex: 1
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
						height: 120,
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
									text: i18n('active_problems'),
									flex: 1
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
									flex: 1
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
		//		{
		//			xtype: 'grid',
		//			height: 100,
		//			frame: true,
		//			hideHeaders: true,
		//			title: i18n('procedures'),
		//			selType: 'checkboxmodel',
		//			columnLines: true,
		//			multiSelect: true,
		//			margin: '0 5 0 5',
		//			columns: [
		//				{
		//					text: i18n('active_problems'),
		//					flex: 1
		//				}
		//			]
		//		},
		//		{
		//			xtype: 'grid',
		//			height: 100,
		//			frame: true,
		//			hideHeaders: true,
		//			title: i18n('encounters'),
		//			selType: 'checkboxmodel',
		//			columnLines: true,
		//			multiSelect: true,
		//			margin: 5,
		//			columns: [
		//				{
		//					text: i18n('active_problems'),
		//					flex: 1
		//				}
		//			]
		//		}
	],
	dockedItems: [
		{
			xtype: 'toolbar',
			dock: 'bottom',
			ui: 'footer',
			defaults: { minWidth: 70 },
			items: [
				{
					text: i18n('import'),
					itemId: 'CcdImportWindowImportBtn'
				},
				{
					xtype: 'patienlivetsearch',
					emptyText: i18n('import_and_merge_with') + '...',
					itemId: 'CcdImportWindowPatientSearchField',
					width: 300
				},
				'->',
				{
					text: i18n('close'),
					itemId: 'CcdImportWindowCloseBtn'
				}
			]
		}
	]
});