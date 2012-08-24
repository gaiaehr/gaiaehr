//******************************************************************************
// Billing.ejs.php
// Billing Forms
// v0.0.1
// Author: Emmanuel J. Carrasquillo
// Modified:
// GaiaEHR (Electronic Health Records) 2011
//******************************************************************************
Ext.define('App.view.fees.Billing', {
	extend       : 'App.classes.RenderPanel',
	id           : 'panelBilling',
	pageTitle    : i18n.billing,
	uses         : [ 'App.classes.GridPanel' ],
	pageLayout   : 'card',
	initComponent: function() {
		var me = this;
		me.paymentstatus = 1;
		me.patient = null;
		me.pastDue = null;
		me.dateRange = { start: null, limit: null };

		me.patientListStore = Ext.create('App.store.fees.Billing');

		me.encountersGrid = Ext.create('Ext.grid.Panel', {
			store     : me.patientListStore,
			selModel  : Ext.create('Ext.selection.CheckboxModel', {
				listeners: {
					scope          : me,
					selectionchange: me.onSelectionChanged
				}
			}),
			viewConfig: {
				stripeRows: true
			},
			columns   : [
				{
					header   : i18n.service_date,
					dataIndex: 'start_date',
					width    : 200
				},
				{
					header   : i18n.patient,
					dataIndex: 'patientName',
					width    : 200
				},
				{
					header   : i18n.primary_provider,
					dataIndex: 'primaryProvider',
					width    : 200
				},
				{
					header   : i18n.encounter_provider,
					dataIndex: 'encounterProvider',
					flex     : 1
				},
				{
					header   : 'Insurance',
					dataIndex: 'insurance',
					width    : 200
				},
				{
					header   : 'Billing Stage',
					dataIndex: 'billing_stage',
					renderer : me.stage,
					width    : 135
				}
			],
			tbar      : [
				{
					xtype: 'fieldcontainer',
					items: [
						{
							xtype     : 'displayfield',
							fieldLabel: 'Patient Search'
						},
						{
							xtype: 'patienlivetsearch',

							width : 235,
							margin: '0 5 0 0'

						}
					]
				},
				{
					xtype: 'fieldcontainer',
					items: [
						{
							xtype     : 'datefield',
							fieldLabel: 'From',
							labelWidth: 35,
							action    : 'datefrom',
							width     : 150
						},
						{
							xtype     : 'datefield',
							fieldLabel: 'To',
							labelWidth: 35,
							action    : 'dateto',
							padding   : '0 5 0 0',
							width     : 150
						}
					]
				},
				{
					xtype: 'fieldcontainer',
					items: [
						{
							xtype     : 'mitos.providerscombo',
							labelWidth: 60,
							typeAhead : true,
							padding   : '0 5 0 5',

							fieldLabel  : 'Provider',
							defaultValue: 'All'

						},
						{
							xtype       : 'mitos.insurancepayertypecombo',
							labelWidth  : 60,
							padding     : '0 5 0 5',
							fieldLabel  : 'Insurance',
							defaultValue: 'All'


						}
					]
				},
				'->',
				{
					xtype: 'tbtext',
					text : 'Past due:'
				},
				{
					text           : '30+',
					enableToggle   : true,
					action         : 30,
					toggleGroup    : 'pastduedates',
					enableKeyEvents: true,
					scale          : 'large',
					listeners      : {
						scope: me,
						click: me.onBtnClicked
					}
				},
				{
					text        : '60+',
					enableToggle: true,
					action      : 60,
					scale       : 'large',
					toggleGroup : 'pastduedates',
					listeners   : {
						scope: me,
						click: me.onBtnClicked
					}
				},
				{
					text        : '120+',
					enableToggle: true,
					action      : 120,
					scale       : 'large',
					toggleGroup : 'pastduedates',
					listeners   : {
						scope: me,
						click: me.onBtnClicked
					}
				},
				{
					text        : '180+',
					enableToggle: true,
					action      : 180,
					scale       : 'large',
					toggleGroup : 'pastduedates',
					listeners   : {
						scope: me,
						click: me.onBtnClicked
					}
				}
			],
			listeners : {
				scope       : me,
				itemdblclick: me.rowDblClicked
			}
		});

		me.encounterBillingDetails = Ext.create('Ext.panel.Panel', {
			defaultTitle: 'Encounter Billing Details',
			title       : 'Encounter Billing Details',
			layout      : 'border',
			bodyStyle   : 'background-color:#fff',
			items       : [
				Ext.create('Ext.container.Container', {
					region: 'center',
					layout: 'border',
					style : 'background-color:#fff',
					items : [
						me.icdForm = Ext.create('Ext.form.Panel', {
							region: 'north',
							border: false,
							items : [
								{
									xtype : 'fieldset',
									title : 'Encounter General Info',
									margin: '5 5 0 5',
									items : [
										{
											xtype    : 'fieldcontainer',
											layout   : {
												type: 'hbox'
											},
											defaults : {
												margin: '0 10'
											},
											hideLabel: true,
											items    : [
												{
													xtype     : 'textfield',
													name      : 'service_date',
													fieldLabel: 'Service Date',
													labelAlign: 'right',
													labelWidth: 80
												},
												{
													xtype     : 'textfield',
													name      : 'insurance',
													fieldLabel: 'Insurance',
													labelAlign: 'right'
												},
												{
													xtype     : 'textfield',
													name      : 'facility',
													fieldLabel: 'Facillity',
													labelAlign: 'right',
													labelWidth: 60,
													flex      : 1
												}
											]
										},
										{
											xtype    : 'fieldcontainer',
											layout   : {
												type: 'hbox'
											},
											defaults : {
												margin: '0 10'
											},
											hideLabel: true,
											items    : [
												{
													xtype     : 'textfield',
													name      : 'hosp_date',
													fieldLabel: 'Hosp Date',
													labelAlign: 'right',
													labelWidth: 80
												},
												{
													xtype     : 'textfield',
													name      : 'sec_insurance',
													fieldLabel: 'Sec. Insurance',
													labelAlign: 'right'
												},
												{
													xtype     : 'textfield',
													name      : 'provider',
													fieldLabel: 'Provider',
													labelAlign: 'right',
													labelWidth: 60,
													flex      : 1
												}
											]
										},
										{
											xtype    : 'fieldcontainer',
											layout   : {
												type: 'hbox'
											},
											defaults : {
												margin: '0 10'
											},
											hideLabel: true,
											items    : [
												{
													xtype     : 'textfield',
													name      : 'authorization',
													fieldLabel: 'Autthorization',
													labelAlign: 'right',
													labelWidth: 80
												},
												{
													xtype     : 'textfield',
													name      : 'sec_authorization',
													fieldLabel: 'SecAuthorization',
													labelAlign: 'right'
												},
												{
													xtype     : 'textfield',
													name      : 'referal_by',
													fieldLabel: 'Referal By',
													labelAlign: 'right',
													labelWidth: 60,
													flex      : 1
												}
											]
										}
									]
								},
								{
									xtype : 'icdsfieldset',
									title : 'Encounter ICD9s',
									margin: '5 5 0 5'
								}
							]
						}), me.cptPanel = Ext.create('App.view.patient.encounter.CurrentProceduralTerminology', {
							region: 'center'
						})
					]
				}),

				me.progressNote = Ext.create('App.view.patient.ProgressNote', {
					title       : 'Encounter Progress Note',
					region      : 'east',
					margin      : 5,
					bodyStyle   : 'padding:15px',
					width       : 500,
					autoScroll  : true,
					collapsible : true,
					animCollapse: true,
					collapsed   : false
				})
			],
			buttons     : [
				{
					text   : 'Encounters',
					scope  : me,
					action : 'encounters',
					tooltip: 'Back to Encounter List',
					handler: me.onBtnCancel
				},
				'->',
				{
					xtype : 'tbtext',
					action: 'page',
					text  : '( 1 of 1 )'
				},
				{
					text   : '<<<  Back',
					scope  : me,
					action : 'back',
					tooltip: 'Previous Encounter Details',
					handler: me.onBtnBack
				},
				{
					text   : 'Save',
					scope  : me,
					action : 'save',
					tooltip: 'Save Billing Details',
					handler: me.onBtnSave
				},

				{
					text   : 'Cancel',
					scope  : me,
					action : 'cancel',
					tooltip: 'Cancel and Go Back to Encounter List',
					handler: me.onBtnCancel
				},
				{
					text   : 'Next  >>>',
					scope  : me,
					action : 'next',
					tooltip: 'Next Encounter Details',
					handler: me.onBtnNext
				}
			]
		});

		me.pageBody = [ me.encountersGrid, me.encounterBillingDetails ];
		me.callParent(arguments);
	}, // end of initComponent


	stage: function(val) {
		if(val == '1') {
			return '<img src="ui_icons/stage1.png" />';
		} else if(val == '2') {
			return '<img src="ui_icons/stage2.png" />';
		} else if(val == '3') {
			return '<img src="ui_icons/stage3.png" />';
		} else if(val == '4') {
			return '<img src="ui_icons/stage4.png" />';
		}
		return val;
	},

	onBtnClicked: function(btn) {
		var datefrom = this.query('datefield[action="datefrom"]'), dateto = this.query('datefield[action="dateto"]');
		if(btn.pressed) {
			datefrom[0].reset();
			dateto[0].reset();
			this.pastDue = btn.action;
		} else {
			this.pastDue = 0;
		}
		this.reloadGrid();

	},

	rowDblClicked: function() {
		this.goToEncounterBillingDetail();
	},

	goToEncounterBillingDetail: function() {
		this.getPageBody().getLayout().setActiveItem(1);
	},

	goToEncounterList: function() {
		this.getPageBody().getLayout().setActiveItem(0);
	},

	onSelectionChanged: function(sm, model) {
		if(model[0]) {
			var me = this, title = me.encounterBillingDetails.defaultTitle, backbtn = me.encounterBillingDetails.query('button[action="back"]'), nextBtn = me.encounterBillingDetails.query('button[action="next"]'), pageInfo = me.encounterBillingDetails.query('tbtext[action="page"]'), rowIndex = model[0].index;

			me.pid = model[0].data.pid;
			me.eid = model[0].data.eid;

			me.updateProgressNote(me.eid);
			me.encounterBillingDetails.setTitle(title + ' ( ' + model[0].data.patientName + ' )');

			me.getEncounterIcds();

			me.cptPanel.encounterCptStoreLoad(me.pid, me.eid, function() {
				me.cptPanel.setDefaultQRCptCodes();
			});

			pageInfo[0].setText('( Page ' + (rowIndex + 1) + ' of ' + sm.store.data.length + ' ) ');
			nextBtn[0].setDisabled(rowIndex == sm.store.data.length - 1);
			backbtn[0].setDisabled(rowIndex == 0);
		}
	},

	onBtnCancel: function() {
		this.getPageBody().getLayout().setActiveItem(0);
	},

	onBtnBack: function() {
		var sm = this.encountersGrid.getSelectionModel(), currRowIndex = sm.getLastSelected().index, prevRowindex = currRowIndex - 1;
		sm.select(prevRowindex);
	},

	onBtnNext: function() {
		var sm = this.encountersGrid.getSelectionModel(), currRowIndex = sm.getLastSelected().index, nextRowindex = currRowIndex + 1;
		sm.select(nextRowindex);
	},

	onBtnSave: function() {
		var me = this, form = me.icdForm.getForm(), values = form.getValues();

		me.updateEncounterIcds(values);
		me.msg('Sweet!', 'Encounter Billing Data Updated');
	},

	getEncounterIcds: function() {
		var me = this;

		Encounter.getEncounterIcdxCodes({eid: me.eid}, function(provider, response) {
			me.icdForm.down('icdsfieldset').loadIcds(response.result);
		});
	},

	updateEncounterIcds: function(data) {
		var me = this;

		data.eid = me.eid;

		Encounter.updateEncounterIcdxCodes(data, function(provider, response) {
			say(response.result);
			return true;
		});
	},

	reloadGrid: function() {
		this.patientListStore.load({
			params: {
				query: {
					patient  : this.patient,
					pastDue  : this.pastDue,
					dateRange: this.dateRange
				}
			}
		});
	},

	updateProgressNote: function(eid) {
		var me = this;
		Encounter.getProgressNoteByEid(eid, function(provider, response) {
			var data = response.result;
			me.progressNote.tpl.overwrite(me.progressNote.body, data);
		});
	},

	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback) {
		this.reloadGrid();
		callback(true);
	}
}); //ens oNotesPage class