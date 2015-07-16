/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, LLC.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('Modules.reportcenter.view.ReportCenter', {
	extend: 'App.ux.RenderPanel',
	id: 'panelReportCenter',
	pageTitle: _('report_center'),

	requires:[
		
	],

	initComponent: function(){
		var me = this;

		me.reports = Ext.create('Ext.panel.Panel', {
			layout: 'auto',
			itemId: 'ReportCenterPanel'
		});
		me.pageBody = [ me.reports ];

		/**
		 * Patient Reports List
		 * TODO: Pass the report indicator telling what report should be rendering
		 * this indicator will also be the logic for field rendering.
		 */
		me.patientCategory = me.addCategory(_('patient_reports'), 260);

		me.ClientListReport = me.addReportByCategory(me.patientCategory, _('client_list_report'), function(btn){


			if(!me.clientListStore) me.clientListStore = Ext.create('Modules.reportcenter.store.ClientList');

			me.goToReportPanelAndSetPanel({
				title: _('client_list_report'),
				layout: 'anchor',
				height: 100,
				items: [
					{
						xtype: 'datefield',
						fieldLabel: _('from'),
						name: 'from',
						format: 'Y-m-d'
					},
					{
						xtype: 'datefield',
						fieldLabel: _('to'),
						name: 'to',
						format: 'Y-m-d'
					},
					{
						xtype: 'patienlivetsearch',
						fieldLabel: _('name'),
						hideLabel: false,
						name: 'pid',
						width: 350
					}
				],
				fn: 'ClientList.CreateClientList',
				store: me.clientListStore,
				columns: [
					{
						text: _('service_date'),
						xtype: 'datecolumn',
						format: 'Y-m-d',
						dataIndex: 'start_date'
					},
					{
						text: _('name'),
						width: 200,
						dataIndex: 'fullname'
					},
					{
						text: _('address'),
						flex: 1,
						dataIndex: 'fulladdress'
					},
					{
						text: _('home_phone'),
						dataIndex: 'home_phone'
					}
				]
			});
		});


		me.Rx = me.addReportByCategory(me.patientCategory, _('rx'), function(btn){
			if(!me.medicationStore) me.medicationStore = Ext.create('Modules.reportcenter.store.MedicationReport');

			me.goToReportPanelAndSetPanel({
				title: _('rx'),
				layout: 'anchor',
				height: 100,
				items: [
					{
						xtype: 'fieldcontainer',
						layout: 'hbox',
						defaults: { margin: '0 10 5 0' },
						items: [

							{
								xtype: 'datefield',
								fieldLabel: _('from'),
								name: 'from',
								format: 'Y-m-d',
								width: 275
							},
							{
								xtype: 'patienlivetsearch',
								fieldLabel: _('name'),
								hideLabel: false,
								name: 'pid',
								width: 350
							}
						]

					},
					{
						xtype: 'fieldcontainer',
						layout: 'hbox',
						defaults: { margin: '0 10 5 0' },
						items: [
							{
								xtype: 'datefield',
								fieldLabel: _('to'),
								name: 'to',
								format: 'Y-m-d',
								width: 275
							},
							{
								xtype: 'medicationlivetsearch',
								fieldLabel: _('drug'),
								hideLabel: false,
								name: 'drug',
								width: 350
							}

						]

					}
				],
				fn: 'Rx.createPrescriptionsDispensations',
				store: me.medicationStore,
				columns: [
					{
						text: _('name'),
						width: 250,
						dataIndex: 'fullname'
					},
					{
						text: _('medication'),
						width: 250,
						dataIndex: 'medication'
					},
					{
						text: _('type'),
						width: 150,
						dataIndex: 'type'
					},
					{
						text: _('instructions'),
						flex: 1,
						dataIndex: 'instructions'
					}
				]
			});
		});

		/**
		 * Clinical Report v0.0.1
		 * This report will generate a list of patient filtered by demographics, laboratories, medical, ect.
		 * This to comply with the Certification
		 * TODO: Load the report dynamically by file, also dynamically add the reports found on the list.
		 * this would be done in the future.
		 * @type {*}
		 */
		me.ClinicalReport = me.addReportByCategory(me.patientCategory, _('clinical'), function(btn){
			if(!me.clinicalStore) me.clinicalStore = Ext.create('Modules.reportcenter.store.Clinical');
			me.goToReportPanelAndSetPanel({
				title: _('clinical'),
				action: 'clientListReport',
				height: 270,
				bodyStyle: 'padding:0px 0px 0',
				border: false,
				items: [
					{
						xtype: 'fieldset',
						layout: 'vbox',
						title: _('patient_demographic'),
						collapsed: false,
						columnWidth: 0.25,
						checkboxToggle: true,
						border: false,
						labelWidth: 60,
						collapsible: true,
						items: [
							{
								xtype: 'patienlivetsearch',
								fieldLabel: _('patient'),
								hideLabel: false,
								name: 'pid',
								width: 280
							},
							{
								xtype: 'gaiaehr.sexcombo',
								fieldLabel: _('sex'),
								name: 'sex',
								width: 275,
								minValue: 0

							},
							{
								xtype: 'gaiaehr.racecombo',
								fieldLabel: _('race'),
								name: 'race',
								action: 'race',
								hideLabel: false,
								width: 275
							},
							{
								xtype: 'fieldcontainer',
								layout: 'hbox',
								labelWidth: 90,
								items: [
									{
										xtype: 'datefield',
										fieldLabel: _('date_from'),
										format: 'Y-m-d',
										width: 200,
										name: 'from'
									},
									{
										xtype: 'datefield',
										labelWidth: 60,
										margin: '0 0 0 5',
										fieldLabel: _('date_to'),
										format: 'Y-m-d',
										width: 160,
										name: 'to'
									}
								]
							},
							{
								xtype: 'fieldcontainer',
								layout: 'hbox',
								labelWidth: 90,
								items: [
									{
										xtype: 'numberfield',
										fieldLabel: _('age_from'),
										name: 'age_from',
										width: 160,
										minValue: 1
									},
									{
										xtype: 'numberfield',
										margin: '0 0 0 5',
										fieldLabel: _('age_to'),
										name: 'age_to',
										width: 160,
										minValue: 1
									}
								]
							},
							{
								xtype: 'gaiaehr.ethnicitycombo',
								fieldLabel: _('ethnicity'),
								name: 'ethnicity',
								action: 'ethnicity',
								hideLabel: false,
								width: 275
							}
						]

					},
					{
						xtype: 'fieldset',
						layout: 'vbox',
						title: _('patient_problems'),
						collapsed: true,
						columnWidth: 0.25,
						checkboxToggle: true,
						border: false,
						labelWidth: 60,
						collapsible: true,
						items: [
							{
								xtype: 'liveicdxsearch',
								fieldLabel: _('problem_dx'),
								hideLabel: false,
								name: 'problem',
								width: 350
							}
						]
					},
					{
						xtype: 'fieldset',
						layout: 'vbox',
						title: _('patient_medication'),
						collapsed: true,
						columnWidth: 0.25,
						checkboxToggle: true,
						border: false,
						labelWidth: 60,
						collapsible: true,
						items: [
							{
								xtype: 'medicationlivetsearch',
								fieldLabel: _('drug'),
								hideLabel: false,
								name: 'medication',
								width: 350
							}
						]
					},
					{
						xtype: 'fieldset',
						layout: 'vbox',
						title: _('patient_laboratory'),
						collapsed: true,
						columnWidth: 0.25,
						checkboxToggle: true,
						border: false,
						labelWidth: 60,
						collapsible: true,
						items: [
							{
								xtype: 'labslivetsearch',
								margin: 5,
								fieldLabel: _('laboratory_result'),
								hideLabel: false,
								width: 350
							}
						]
					}
				],
				fn: 'Clinical.createClinicalReport',
				store: me.clinicalStore,
				columns: [
					{
						xtype: 'datecolumn',
						text: _('created'),
						dataIndex: 'create_date',
						width: 160,
						format: 'Y-m-d H:i:s'
					},
					{
						text: _('name'),
						width: 200,
						dataIndex: 'fullname'
					},
					{
						text: _('age'),
						width: 75,
						dataIndex: 'age'
					},
					{
						text: _('sex'),
						dataIndex: 'sex'
					},
					{
						text: _('race'),
						width: 250,
						dataIndex: 'race'
					},
					{
						text: _('ethnicity'),
						flex: 1,
						dataIndex: 'ethnicity'
					}
				]
			});
		});

		me.ImmunizationReport = me.addReportByCategory(me.patientCategory, _('immunization_registry'), function(btn){
			if(!me.immunizationReportStore) me.immunizationReportStore = Ext.create('Modules.reportcenter.store.ImmunizationsReport');
			me.goToReportPanelAndSetPanel({
				title: _('immunization_registry'),
				action: 'clientListReport',
				layout: 'anchor',
				height: 100,
				items: [
					{
						xtype: 'datefield',
						fieldLabel: _('from'),
						name: 'from',
						format: 'Y-m-d',
						width: 350
					},
					{
						xtype: 'datefield',
						fieldLabel: _('to'),
						name: 'to',
						format: 'Y-m-d',
						width: 350
					},
					{
						xtype: 'immunizationlivesearch',
						fieldLabel: _('immunization'),
						hideLabel: false,
						name: 'immu',
						width: 350
					}
				],
				fn: 'ImmunizationsReport.createImmunizationsReport',
				store: me.immunizationReportStore,
				columns: [
					{
						text: _('name'),
						width: 200,
						dataIndex: 'fullname'
					},
					{
						text: _('immunization_id'),
						dataIndex: 'immunization_id'
					},
					{
						text: _('immunization_name'),
						dataIndex: 'immunization_name',
						flex: 1
					},
					{
						text: _('administered_date'),
						dataIndex: 'administered_date',
						xtype: 'datecolumn',
						format: 'Y-m-d'
					}
				]
			});
		});

		/**
		 * Clinic Reports List
		 * TODO: Pass the report indicator telling what report should be rendering
		 * this indicator will also be the logic for field rendering.
		 */
		me.clinicCategory = me.addCategory(_('clinic_reports'), 270);
		me.link5 = me.addReportByCategory(me.clinicCategory, _('standard_measures'), function(btn){
			me.goToReportPanelAndSetPanel({
				title: _('standard_measures'),
				action: 'clientListReport',
				layout: 'anchor',
				height: 100,
				items: [
					{
						xtype: 'datefield',
						fieldLabel: _('from'),
						name: 'from'
					},
					{
						xtype: 'datefield',
						fieldLabel: _('to'),
						name: 'to'
					}
				]
			});
		});
		me.link6 = me.addReportByCategory(me.clinicCategory, _('clinical_quality_measures_cqm'), function(btn){
			me.goToReportPanelAndSetPanel({
				title: _('clinical_quality_measures_cqm'),
				action: 'clientListReport',
				layout: 'anchor',
				height: 100,
				items: [
					{
						xtype: 'datefield',
						fieldLabel: _('from'),
						name: 'from'
					},
					{
						xtype: 'datefield',
						fieldLabel: _('to'),
						name: 'to'
					}
				]
			});
		});
		me.link7 = me.addReportByCategory(me.clinicCategory, _('automated_measure_calculations_amc'), function(btn){
			me.goToReportPanelAndSetPanel({
				title: _('automated_measure_calculations_amc'),
				action: 'clientListReport',
				layout: 'anchor',
				height: 100,
				items: [
					{
						xtype: 'datefield',
						fieldLabel: _('from'),
						name: 'from'
					},
					{
						xtype: 'datefield',
						fieldLabel: _('to'),
						name: 'to'
					}
				]
			});
		});
		me.link8 = me.addReportByCategory(me.clinicCategory, _('automated_measure_calculations_tracking'), function(btn){
			me.goToReportPanelAndSetPanel({
				title: _('automated_measure_calculations_tracking'),
				action: 'clientListReport',
				layout: 'anchor',
				height: 100,
				items: [
					{
						xtype: 'datefield',
						fieldLabel: _('from'),
						name: 'from'
					},
					{
						xtype: 'datefield',
						fieldLabel: _('to'),
						name: 'to'
					}
				],
				fn: function(){

				}
			});
		});

		/**
		 * Visits Category List
		 * TODO: Pass the report indicator telling what report should be rendering
		 * this indicator will also be the logic for field rendering.
		 */
		me.visitCategory = me.addCategory(_('visit_reports'), 260);
		me.link9 = me.addReportByCategory(me.visitCategory, _('super_bill'), function(btn){
			me.goToReportPanelAndSetPanel({
				title: _('super_bill'),
				layout: 'anchor',
				height: 100,
				items: [
					{
						xtype: 'patienlivetsearch',
						fieldLabel: _('name'),
						hideLabel: false,
						name: 'pid',
						width: 570
					},
					{
						xtype: 'datefield',
						fieldLabel: _('from'),
						allowBlank: false,
						name: 'from',
						format: 'Y-m-d'
					},
					{
						xtype: 'datefield',
						fieldLabel: _('to'),
						name: 'to',
						format: 'Y-m-d'
					}
				],
				fn: 'SuperBill.CreateSuperBill'
			});
		});

		me.link10 = me.addReportByCategory(me.visitCategory, _('appointments'), function(btn){
			if(!me.appointmentsReportStore) me.appointmentsReportStore = Ext.create('Modules.reportcenter.store.Appointment');
			me.goToReportPanelAndSetPanel({
				title: _('appointments'),
				layout: 'anchor',
				height: 100,
				items: [
					{
						xtype: 'fieldcontainer',
						layout: 'hbox',
						defaults: { margin: '0 10 5 0' },
						items: [
							{
								xtype: 'datefield',
								fieldLabel: _('from'),
								name: 'from',
								format: 'Y-m-d',
								width: 275
							},
							{
								xtype: 'mitos.facilitiescombo',
								fieldLabel: _('facility'),
								name: 'facility',
								hideLabel: false,
								width: 300,
								labelWidth: 70

							}
						]

					},
					{
						xtype: 'fieldcontainer',
						layout: 'hbox',
						defaults: { margin: '0 10 5 0' },
						items: [
							{
								xtype: 'datefield',
								fieldLabel: _('to'),
								name: 'to',
								format: 'Y-m-d',
								width: 275
							},
							{
								xtype: 'mitos.providerscombo',
								fieldLabel: _('provider'),
								name: 'provider',
								hideLabel: false,
								width: 300,
								labelWidth: 70

							}

						]

					}
				],
				fn: 'Appointments.CreateAppointmentsReport',
				store: me.appointmentsReportStore,
				columns: [
					{
						text: _('provider'),
						width: 200,
						dataIndex: 'provider'
					},
					{
						text: _('patient'),
						width: 200,
						dataIndex: 'fullname'
					},
					{
						text: _('date'),
						dataIndex: 'start',
						xtype: 'datecolumn',
						format: 'Y-m-d'
					},
					{
						text: _('time'),
						dataIndex: 'start_time',
						xtype: 'datecolumn',
						format: 'h:i a'
					},
					{
						text: _('category'),
						dataIndex: 'catname',
						width: 200
					},
					{
						text: _('facility'),
						dataIndex: 'facility',
						width: 250
					},
					{
						text: _('notes'),
						dataIndex: 'notes',
						flex: 1
					}
				]
			});
		});

		me.callParent(arguments);
	},


	/**
	 * Function to call the report panel.
	 * Remember the report fields are dynamically rendered.
	 */
	goToReportPanelAndSetPanel: function(config){
		var nav = app.getController('Navigation');

		nav.navigateTo('Modules.reportcenter.view.ReportPanel');

		Ext.Function.defer(function(){
			var panel = nav.getPanelByCls('Modules.reportcenter.view.ReportPanel');
			panel.setReportPanel(config);
		}, 200);
	}
});