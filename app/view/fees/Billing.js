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
	pageTitle    : i18n['billing'],
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
					header   : i18n['service_date'],
					dataIndex: 'start_date',
					width    : 200
				},
				{
					header   : i18n['patient'],
					dataIndex: 'patientName',
					width    : 200
				},
				{
					header   : i18n['primary_provider'],
					dataIndex: 'primaryProvider',
					width    : 200
				},
				{
					header   : i18n['encounter_provider'],
					dataIndex: 'encounterProvider',
					flex     : 1
				},
				{
					header   : i18n['insurance'],
					dataIndex: 'insurance',
					width    : 200
				},
				{
					header   : i18n['billing_stage'],
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
							fieldLabel: i18n['patient_search']
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
							fieldLabel: i18n['from'],
							labelWidth: 35,
							action    : 'datefrom',
							width     : 150
						},
						{
							xtype     : 'datefield',
							fieldLabel: i18n['to'],
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

							fieldLabel  : i18n['provider'],
							defaultValue: 'All'

						},
						{
							xtype       : 'mitos.insurancepayertypecombo',
							labelWidth  : 60,
							padding     : '0 5 0 5',
							fieldLabel  : i18n['insurance'],
							defaultValue: 'All'


						}
					]
				},
				'->',
				{
					xtype: 'tbtext',
					text : i18n['past_due'] + ':'
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
			defaultTitle: i18n.encounter_billing_details,
			title       : i18n.encounter_billing_details,
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
									title : i18n['encounter_general_info'],
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
													fieldLabel: i18n['service_date'],
													labelAlign: 'right',
													labelWidth: 80
												},
												{
													xtype     : 'textfield',
													name      : 'insurance',
													fieldLabel: i18n['insurance'],
													labelAlign: 'right'
												},
												{
													xtype     : 'textfield',
													name      : 'facility',
													fieldLabel: i18n['facillity'],
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
													fieldLabel: i18n['hosp_date'],
													labelAlign: 'right',
													labelWidth: 80
												},
												{
													xtype     : 'textfield',
													name      : 'sec_insurance',
													fieldLabel: i18n['sec_insurance'],
													labelAlign: 'right'
												},
												{
													xtype     : 'textfield',
													name      : 'provider',
													fieldLabel: i18n['provider'],
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
													fieldLabel: i18n.authorization,
													labelAlign: 'right',
													labelWidth: 80
												},
												{
													xtype     : 'textfield',
													name      : 'sec_authorization',
													fieldLabel: i18n.sec_authorization,
													labelAlign: 'right'
												},
												{
													xtype     : 'textfield',
													name      : 'referal_by',
													fieldLabel: i18n.referal_by,
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
									title : i18n.encounter_icd9,
									margin: '5 5 0 5'
								}
							]
						}), me.cptPanel = Ext.create('App.view.patient.encounter.CurrentProceduralTerminology', {
							region: 'center'
						})
					]
				}),

				me.progressNote = Ext.create('App.view.patient.ProgressNote', {
					title       : i18n.encounter_progress_note,
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
					text   : i18n.encounters,
					scope  : me,
					action : 'encounters',
					tooltip: i18n.back_to_encounter_list,
					handler: me.onBtnCancel
				},
				'->',
				{
					xtype : 'tbtext',
					action: 'page',
					text  : '( 1 of 1 )'
				},
				{
					text   : '<<<  ' + i18n.back,
					scope  : me,
					action : 'back',
					tooltip: i18n.previous_encounter_details,
					handler: me.onBtnBack
				},
				{
					text   : i18n.save,
					scope  : me,
					action : 'save',
					tooltip: i18n.save_billing_details,
					handler: me.onBtnSave
				},

				{
					text   : i18n.cancel,
					scope  : me,
					action : 'cancel',
					tooltip: i18n.cancel_and_go_back_to_encounter_list,
					handler: me.onBtnCancel
				},
				{
					text   : i18n.next + '  >>>',
					scope  : me,
					action : 'next',
					tooltip: i18n.next_encounter_details,
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

			pageInfo[0].setText( '( ' + i18n.page + ' (rowIndex + 1) of ' + sm.store.data.length + ' )' );
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
		me.msg('Sweet!', i18n.encounter_billing_data_updated);
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