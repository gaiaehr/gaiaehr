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

Ext.define('App.view.patient.Summary', {
	extend: 'App.ux.RenderPanel',
	pageTitle: i18n('patient_summary'),
	pageLayout: 'border',
	requires: [
		'Ext.XTemplate',
		'Ext.ux.IFrame',
		'App.view.patient.Documents',
		'App.ux.ManagedIframe'
	],
	showRating: true,
	pid: null,
	demographicsData: null,
	initComponent: function(){
		var me = this;

		me.stores = [];

		app.on('patientset', function(patient){
			if(!me.hidden){
				me.updateTitle(patient.name + ' #' + patient.pid + ' - ' + patient.age.str + ' - (' + i18n('patient_summary') + ')', app.patient.readOnly, null);
			}

		}, me);

		me.pageBody = [
			me.tabPanel = Ext.widget('tabpanel', {
				flex: 1,
				margin: '3 0 0 0',
				bodyPadding: 0,
				frame: false,
				border: false,
				plain: true,
				region: 'center',
				itemId: 'centerPanel'
			})
		];

		me.sidePanelItems = [];

		if(a('access_patient_medications')){

			me.stores.push(me.patientMedicationsStore = Ext.create('App.store.patient.Medications', {
				autoLoad: false
			}));



			Ext.Array.push(me.sidePanelItems, {
				xtype: 'grid',
				title: i18n('active_medications'),
				itemId: 'PatientSummaryMedicationsPanel',
				hideHeaders: true,
				store: me.patientMedicationsStore,
				tools:[
					{
						xtype: 'button',
						text: i18n('details'),
						action: 'medications',
						scope: me,
						handler: me.medicalWin
					}
				],
				columns: [
					{
						header: i18n('name'),
						dataIndex: 'STR',
						flex: 1
					},
					{
						text: i18n('alert'),
						width: 55,
						dataIndex: 'alert',
						renderer: me.boolRenderer
					}
				]
			});
		}

		if(a('access_patient_immunizations')){

			me.stores.push(me.immuCheckListStore = Ext.create('App.store.patient.ImmunizationCheck', {
				autoLoad: false
			}));

			Ext.Array.push(me.sidePanelItems, {
				xtype: 'grid',
				title: i18n('immunizations'),
				itemId: 'PatientSummaryImmunizationPanel',
				hideHeaders: true,
				store: me.immuCheckListStore,
				region: 'center',
				tools:[
					{
						xtype: 'button',
						text: i18n('details'),
						action: 'immunization',
						scope: me,
						handler: me.medicalWin
					}
				],
				columns: [
					{

						header: i18n('name'),
						dataIndex: 'vaccine_name',
						flex: 1
					},
					{
						text: i18n('alert'),
						width: 55,
						dataIndex: 'alert',
						renderer: me.alertRenderer
					}
				]
			});
		}

		if(a('access_patient_allergies')){

			me.stores.push(me.patientAllergiesListStore = Ext.create('App.store.patient.Allergies', {
				autoLoad: false
			}));

			Ext.Array.push(me.sidePanelItems, {
				xtype: 'grid',
				title: i18n('allergies'),
				itemId: 'PatientSummaryAllergiesPanel',
				hideHeaders: true,
				store: me.patientAllergiesListStore,
				region: 'center',
				tools:[
					{
						xtype: 'button',
						text: i18n('details'),
						action: 'allergies',
						scope: me,
						handler: me.medicalWin
					}
				],
				columns: [
					{
						header: i18n('name'),
						dataIndex: 'allergy',
						flex: 1
					},
					{
						text: i18n('alert'),
						width: 55,
						dataIndex: 'alert',
						renderer: me.boolRenderer
					}
				]
			});
		}

		if(a('access_active_problems')){

			me.stores.push(me.patientActiveProblemsStore = Ext.create('App.store.patient.PatientActiveProblems', {
				autoLoad: false
			}));

			Ext.Array.push(me.sidePanelItems, {
				xtype: 'grid',
				title: i18n('active_problems'),
				itemId: 'PatientSummaryActiveProblemsPanel',
				hideHeaders: true,
				store: me.patientActiveProblemsStore,
				tools:[
					{
						xtype: 'button',
						text: i18n('details'),
						action: 'issues',
						scope: me,
						handler: me.medicalWin
					}
				],
				columns: [
					{

						header: i18n('name'),
						dataIndex: 'code',
						flex: 1
					},
					{
						text: i18n('alert'),
						width: 55,
						dataIndex: 'alert',
						renderer: me.boolRenderer
					}
				]

			});
		}

		if(a('access_patient_calendar_events')){

			me.stores.push(me.patientCalendarEventsStore = Ext.create('App.store.patient.PatientCalendarEvents', {
				autoLoad: false
			}));

			Ext.Array.push(me.sidePanelItems, {
				xtype: 'grid',
				title: i18n('appointments'),
				itemId: 'AppointmentsPanel',
				hideHeaders: true,
				disableSelection: true,
				store: me.patientCalendarEventsStore,
				columns: [
					{
						xtype: 'datecolumn',
						format: 'F j, Y, g:i a',
						dataIndex: 'start',
						flex: 1
					}
				]
			});
		}


		if(me.sidePanelItems.length > 0){
			me.sidePanel = Ext.widget('panel', {
				width: 250,
				bodyPadding: 0,
				frame: false,
				border: false,
				bodyBorder: true,
				region: 'east',
				split: true,
				defaults: {
					layout: 'fit',
					margin: '5 5 0 5'
				},
				items: me.sidePanelItems
			});

			Ext.Array.push(me.pageBody, me.sidePanel);

		}


		if(a('access_demographics')){
			me.tabPanel.add(
				me.demographics = Ext.create('App.view.patient.Patient', {
					newPatient: false,
					title: i18n('demographics')
				})
			);
		}

		if(a('access_patient_disclosures')){
			me.tabPanel.add({
				xtype: 'grid',
				title: i18n('disclosures'),
				itemId: 'PatientSummaryDisclosuresPanel',
				bodyPadding: 0,
				store: Ext.create('App.store.patient.Disclosures', {
					autoSync: false,
					autoLoad: false
				}),
				plugins: Ext.create('Ext.grid.plugin.RowEditing', {
					autoCancel: false,
					errorSummary: false,
					clicksToEdit: 2
				}),
				columns: [
					{
						xtype: 'datecolumn',
						format: 'Y-m-d',
						text: i18n('date'),
						dataIndex: 'date'
					},
					{
						header: i18n('type'),
						dataIndex: 'type',
						editor: {
							xtype: 'textfield'
						}
					},
					{
						text: i18n('description'),
						dataIndex: 'description',
						flex: 1,
						editor: {
							xtype: 'textfield'
						}
					}
				],
				tbar: [
					{
						text: i18n('disclosure'),
						iconCls: 'icoAdd',
						action: 'disclosure',
						handler: me.onAddNew
					}
				]
			});
		}

		if(a('access_patient_notes')){
			me.tabPanel.add({
				title: i18n('notes'),
				itemId: 'PatientSummeryNotesPanel',
				xtype: 'grid',
				bodyPadding: 0,
				store: Ext.create('App.store.patient.Notes', {
					autoSync: false,
					autoLoad: false
				}),
				plugins: Ext.create('Ext.grid.plugin.RowEditing', {
					autoCancel: false,
					errorSummary: false,
					clicksToEdit: 2

				}),
				columns: [
					{
						xtype: 'datecolumn',
						text: i18n('date'),
						format: 'Y-m-d',
						dataIndex: 'date'
					},
					{
						header: i18n('type'),
						dataIndex: 'type',
						editor: {
							xtype: 'textfield'
						}
					},
					{
						text: i18n('note'),
						dataIndex: 'body',
						flex: 1,
						editor: {
							xtype: 'textfield'
						}
					},
					{
						text: i18n('user'),
						width: 225,
						dataIndex: 'user_name'
					}
				],
				tbar: [
					{
						text: i18n('add_note'),
						iconCls: 'icoAdd',
						action: 'note',
						handler: me.onAddNew
					}
				]
			});
		}

		if(a('access_patient_reminders')){
			me.tabPanel.add({
				title: i18n('reminders'),
				itemId: 'PatientSummaryRemindersPanel',
				xtype: 'grid',
				bodyPadding: 0,
				store: Ext.create('App.store.patient.Reminders', {
					autoLoad: false,
					autoSync: false
				}),
				plugins: Ext.create('Ext.grid.plugin.RowEditing', {
					autoCancel: false,
					errorSummary: false,
					clicksToEdit: 2

				}),
				columns: [
					{
						xtype: 'datecolumn',
						text: i18n('date'),
						format: 'Y-m-d',
						dataIndex: 'date'
					},
					{
						header: i18n('type'),
						dataIndex: 'type',
						editor: {
							xtype: 'textfield'
						}
					},
					{
						text: i18n('note'),
						dataIndex: 'body',
						flex: 1,
						editor: {
							xtype: 'textfield'
						}
					},
					{
						text: i18n('user'),
						width: 225,
						dataIndex: 'user_name'
					}
				],
				tbar: [
					{
						text: i18n('add_reminder'),
						iconCls: 'icoAdd',
						action: 'reminder',
						handler: me.onAddNew
					}
				]
			})
		}

//		if(a('access_patient_vitals')){
//			me.tabPanel.add({
//				xtype: 'vitalspanel'
//			})
//		}

		if(a('access_patient_history')){
			//            me.stores.push(me.encounterEventHistoryStore = Ext.create('App.store.patient.Encounters'));
			me.tabPanel.add({
				title: i18n('history'),
				xtype: 'grid',
				itemId: 'PatientEncounterHistoryPanel',
				store: Ext.create('App.store.patient.Encounters', {
					autoLoad: false
				}),
				columns: [
					{
						header: i18n('date'),
						dataIndex: 'service_date'
					},
					{
						header: i18n('event'),
						dataIndex: 'brief_description',
						flex: true
					},
					{
						header: i18n('visit_category'),
						dataIndex: 'visit_category'
					}
				]
			})
		}

		if(a('access_patient_documents')){
			//            me.stores.push(me.patientDocumentsStore = Ext.create('App.store.patient.PatientDocuments'));
			me.tabPanel.add({
				xtype: 'patientdocumentspanel',
				border: false
			})
		}

		if(a('access_patient_preventive_care_alerts')){
			me.tabPanel.add({
				title: i18n('dismissed_preventive_care_alerts'),
				xtype: 'grid',
				itemId: 'PatientSummaryPreventiveCareAlertsPanel',
				store: Ext.create('App.store.patient.DismissedAlerts', {
					//listeners
				}),
				columns: [
					{
						header: i18n('description'),
						dataIndex: 'description'
					},
					{
						xtype: 'datecolumn',
						header: i18n('date'),
						dataIndex: 'date',
						format: 'Y-m-d'

					},
					{
						header: i18n('reason'),
						dataIndex: 'reason',
						flex: true

					},
					{
						header: i18n('observation'),
						dataIndex: 'observation',
						flex: true
					},
					{
						header: i18n('dismissed'),
						dataIndex: 'dismiss',
						width: 60,
						renderer: me.boolRenderer
					}
				],
				plugins: Ext.create('App.ux.grid.RowFormEditing', {
					autoCancel: false,
					errorSummary: false,
					clicksToEdit: 1,
					items: [
						{
							title: 'general',
							xtype: 'container',
							padding: 10,
							layout: 'vbox',
							items: [
								{
									/**
									 * Line one
									 */
									xtype: 'fieldcontainer',
									layout: 'hbox',
									defaults: {
										margin: '0 10 5 0'
									},
									items: [
										{
											xtype: 'textfield',
											name: 'reason',
											fieldLabel: i18n('reason'),
											width: 585,
											labelWidth: 70,
											action: 'reason'
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
										margin: '0 10 5 0'
									},
									items: [
										{
											xtype: 'textfield',
											fieldLabel: i18n('observation'),
											name: 'observation',
											width: 250,
											labelWidth: 70,
											action: 'observation'
										},
										{
											fieldLabel: i18n('date'),
											xtype: 'datefield',
											action: 'date',
											width: 200,
											labelWidth: 40,
											format: globals['date_display_format'],
											name: 'date'

										},
										{
											xtype: 'checkboxfield',
											name: 'dismiss',
											fieldLabel: i18n('dismiss_alert')

										}
									]

								}
							]
						}
					]

				})
			})
		}

		if(a('access_patient_billing')){
			me.tabPanel.add({
				xtype: 'panel',
				action: 'balancePanel',
				itemId: 'balancePanel',
				title: i18n('billing'),
				html: i18n('account_balance') + ': '

			});
		}

		if(a('access_patient_ccd')){
			me.reportPanel = me.tabPanel.add({
				xtype: 'panel',
				title: i18n('ccd_reports'),
				layout: 'fit',
				items:[
					{
						xtype: 'miframe',
						style: 'background-color:white',
						autoMask: false,
						itemId: 'patientDocumentViewerFrame'
					}
				],
				tbar: [
					{
						xtype: 'button',
						text: i18n('view_ccr'),
						margin: '0 0 5 0',
						handler: function(){
							me.reportPanel.down('miframe').setSrc('dataProvider/CCDDocument.php?action=view&site='+ window.site +'&pid=' + me.pid + '&token=' + app.user.token);
							// GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
							app.AuditLog('Patient summary CCD viewed');
						}

					},
					'-',
					{
						text: i18n('export_ccr'),
						margin: '0 0 5 0',
						handler: function(){
							me.reportPanel.down('miframe').setSrc('dataProvider/CCDDocument.php?action=export&site='+ window.site +'&pid=' + me.pid + '&token=' + app.user.token);
							// GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
							app.AuditLog('Patient summary CCD exported');
						}
					},
					'-',
					{
						xtype: 'container',
						layout: 'vbox',
						items: [
							{
								xtype: 'patientEncounterCombo',
								name: 'filterEncounter',
								margin: 5,
								fieldLabel: i18n('filter_encounter'),
								hideLabel: false
							}
						]
					},
					'-',
					{
						text: 'Print',
						iconCls: 'icon-print',
						handler: function(){
							//                           	trg.focus();
							//                           	trg.print();
						}
					}
				]
			});
		}

		me.callParent();
	},

	onAddNew: function(btn){
		var grid = btn.up('grid'),
			store = grid.store,
			record;

		if(btn.action == 'disclosure'){
			record = {
				date: new Date(),
				pid: app.patient.pid,
				active: 1
			};
		}else if(btn.action == 'note' || btn.action == 'reminder'){
			record = {
				date: new Date(),
				pid: app.patient.pid,
				uid: app.user.id,
				eid: app.patient.eid
			};
		}

		grid.plugins[0].cancelEdit();
		store.insert(0, record);
		grid.plugins[0].startEdit(0, 0);
	},

	medicalWin: function(btn){
		app.onMedicalWin(btn.action);
	},

	/**
	 * verify the patient required info and add a yellow background if empty
	 */
	verifyPatientRequiredInfo: function(){
		var me = this, formPanel = me.query('[action="demoFormPanel"]')[0], field;
		me.patientAlertsStore.load({
			scope: me,
			params: {
				pid: me.pid
			},
			callback: function(records, operation, success){
				for(var i = 0; i < records.length; i++){
					field = formPanel.getForm().findField(records[i].data.name);
					if(records[i].data.val){
						if(field) field.removeCls('x-field-yellow');
					}else{
						if(field) field.addCls('x-field-yellow');
					}
				}
			}
		});
	},

	/**
	 * load all the stores in the summaryStores array
	 */
	loadStores: function(){
		var me = this;

		for(var i = 0; i < me.stores.length; i++){
			me.stores[i].clearFilter(true);
			me.stores[i].load({
				params: {
					pid: me.pid
				},
				filters: [
					{
						property: 'pid',
						value: me.pid
					}
				]
			});
		}
	},

	loadPatient: function(){
		var me = this,
			billingPanel;

		me.el.mask(i18n('loading...'));
		/**
		 * convenient way to refer to current pid within this panel
		 * @type {*}
		 */
		me.pid = app.patient.pid;
		/**
		 * get current set patient info
		 * @type {*}
		 */
		var patient = app.patient;
		/**
		 * update panel main title to reflect the patient name and if the patient is read only
		 */
		me.updateTitle(patient.name + ' #' + patient.pid + ' - ' + patient.age.str + ' - (' + i18n('patient_summary') + ')', app.patient.readOnly, null);
		/**
		 * verify if the patient is on read only mode
		 */
		me.setReadOnly(app.patient.readOnly);
		me.setButtonsDisabled(me.query('button[action="readOnly"]'));

		if(a('access_demographics')) me.demographics.loadPatient(me.pid);

		/**
		 * get billing info if user has access
		 */
		if(a('access_patient_billing')){
			billingPanel = me.tabPanel.getComponent('balancePanel');
			Fees.getPatientBalance({pid: me.pid},
				function(balance){
					billingPanel.update(i18n('account_balance') + ': $' + balance);
				}
			);
		}
		/**
		 * reset tab panel to the first tap
		 */
		me.tabPanel.setActiveTab(0);
		/**
		 * load all the stores
		 */
		me.loadStores();
		me.el.unmask();
	},
	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback){
		var me = this;
		if(me.checkIfCurrPatient()){
			me.loadPatient();
			if(typeof callback == 'function') callback(true);
		}else{
			callback(false);
			me.pid = null;
			me.currPatientError();
		}
	}
});