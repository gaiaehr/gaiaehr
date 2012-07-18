/**
 * @namespace Navigation.getNavigation
 * @namespace Patient.currPatientSet
 * @namespace Patient.currPatientUnset
 * @namespace authProcedures.unAuth
 */
Ext.define('App.view.Viewport', {
	extend  : 'Ext.Viewport',
	requires: [

		'App.model.administration.ActiveProblems',
		'App.model.administration.DefaultDocuments',
		'App.model.administration.DocumentsTemplates',
		'App.model.administration.HeadersAndFooters',
		'App.model.administration.ImmunizationRelations',
		'App.model.administration.LabObservations',
		'App.model.administration.Medications',
		'App.model.administration.PreventiveCare',
		'App.model.administration.PreventiveCareActiveProblems',
		'App.model.administration.PreventiveCareMedications',
		'App.model.administration.Services',
		'App.model.miscellaneous.OfficeNotes',
		'App.model.fees.Billing',
		'App.model.fees.Checkout',
		'App.model.fees.EncountersPayments',
		'App.model.fees.PaymentTransactions',
		'App.model.navigation.Navigation',
		'App.model.patientfile.Allergies',
		'App.model.patientfile.CptCodes',
		'App.model.patientfile.Dental',
		'App.model.patientfile.Encounter',
		'App.model.patientfile.EncounterCPTsICDs',
		'App.model.patientfile.Encounters',
		'App.model.patientfile.EventHistory',
		'App.model.patientfile.Immunization',
		'App.model.patientfile.ImmunizationCheck',
		'App.model.patientfile.LaboratoryTypes',
		'App.model.patientfile.MeaningfulUseAlert',
		'App.model.patientfile.MedicalIssues',
		'App.model.patientfile.Medications',
		'App.model.patientfile.Notes',
		'App.model.patientfile.PatientArrivalLog',
		'App.model.patientfile.PatientDocuments',
		'App.model.patientfile.PatientImmunization',
		'App.model.patientfile.PatientLabsResults',
		'App.model.patientfile.PatientsLabsOrders',
		'App.model.patientfile.PatientsPrescription',
		'App.model.patientfile.PreventiveCare',
		'App.model.patientfile.QRCptCodes',
		'App.model.patientfile.Reminders',
		'App.model.patientfile.Surgery',
		'App.model.patientfile.VectorGraph',
		'App.model.patientfile.VisitPayment',
		'App.model.patientfile.Vitals',
		'App.model.poolarea.PoolArea',
		'App.model.poolarea.PoolDropAreas',

		'App.store.administration.ActiveProblems',
		'App.store.administration.DefaultDocuments',
		'App.store.administration.DocumentsTemplates',
		'App.store.administration.HeadersAndFooters',
		'App.store.administration.ImmunizationRelations',
		'App.store.administration.LabObservations',
		'App.store.administration.Medications',
		'App.store.administration.PreventiveCare',
		'App.store.administration.PreventiveCareActiveProblems',
		'App.store.administration.PreventiveCareMedications',
		'App.store.administration.Services',
		'App.store.administration.ActiveProblems',
		'App.store.miscellaneous.OfficeNotes',
		'App.store.fees.Billing',
		'App.store.fees.Checkout',
		'App.store.fees.EncountersPayments',
		'App.store.fees.PaymentTransactions',
		'App.store.navigation.Navigation',
		'App.store.patientfile.Allergies',
		'App.store.patientfile.Dental',
		'App.store.patientfile.Encounter',
		'App.store.patientfile.EncounterCPTsICDs',
		'App.store.patientfile.EncounterEventHistory',
		'App.store.patientfile.Encounters',
		'App.store.patientfile.Immunization',
		'App.store.patientfile.ImmunizationCheck',
		'App.store.patientfile.LaboratoryTypes',
		'App.store.patientfile.MeaningfulUseAlert',
		'App.store.patientfile.MedicalIssues',
		'App.store.patientfile.Medications',
		'App.store.patientfile.Notes',
		'App.store.patientfile.PatientArrivalLog',
		'App.store.patientfile.PatientDocuments',
		'App.store.patientfile.PatientImmunization',
		'App.store.patientfile.PatientLabsResults',
		'App.store.patientfile.PatientsLabsOrders',
		'App.store.patientfile.PatientsPrescription',
		'App.store.patientfile.PreventiveCare',
		'App.store.patientfile.QRCptCodes',
		'App.store.patientfile.Reminders',
		'App.store.patientfile.Surgery',
		'App.store.patientfile.VectorGraph',
		'App.store.patientfile.VisitPayment',
		'App.store.patientfile.Vitals',
		'App.store.poolarea.PoolArea',

		'App.classes.LiveCPTSearch',
		'App.classes.LiveImmunizationSearch',
		'App.classes.LiveMedicationSearch',
		'App.classes.LiveLabsSearch',
		'App.classes.LivePatientSearch',
		'App.classes.ManagedIframe',
		'App.classes.NodeDisabled',
		'App.classes.PhotoIdWindow',

		'App.classes.RenderPanel',

		'Ext.chart.*',
		'Ext.form.*',
		'Ext.fx.target.Sprite',

		'Ext.dd.DropZone',
		'Ext.dd.DragZone',

		'Extensible.calendar.CalendarPanel',
		'Extensible.calendar.gadget.CalendarListPanel',
		'Extensible.calendar.data.MemoryCalendarStore',
		'Extensible.calendar.data.MemoryEventStore',

		'App.classes.combo.ActiveFacilities',
		'App.classes.combo.Allergies',
		'App.classes.combo.AllergiesAbdominal',
		'App.classes.combo.AllergiesLocal',
		'App.classes.combo.AllergiesLocation',
		'App.classes.combo.AllergiesSeverity',
		'App.classes.combo.AllergiesSkin',
		'App.classes.combo.AllergiesSystemic',
		'App.classes.combo.AllergiesTypes',
		'App.classes.combo.Authorizations',
		'App.classes.combo.BillingFacilities',
		'App.classes.combo.CalendarCategories',
		'App.classes.combo.CalendarStatus',
		'App.classes.combo.CodesTypes',
		'App.classes.combo.Facilities',
		'App.classes.combo.FollowUp',
		'App.classes.combo.InsurancePayerType',
		'App.classes.combo.LabObservations',
		'App.classes.combo.LabsTypes',
		'App.classes.combo.Languages',
		'App.classes.combo.Lists',
		'App.classes.combo.MedicalIssues',
		'App.classes.combo.Medications',
		'App.classes.combo.MsgNoteType',
		'App.classes.combo.MsgStatus',
		'App.classes.combo.Occurrence',
		'App.classes.combo.Outcome',
		'App.classes.combo.Outcome2',
		'App.classes.combo.PayingEntity',
		'App.classes.combo.PaymentCategory',
		'App.classes.combo.PaymentMethod',
		'App.classes.combo.Pharmacies',
		'App.classes.combo.posCodes',
		'App.classes.combo.PrescrptionHowTo',
		'App.classes.combo.PrescrptionOften',
		'App.classes.combo.PrescrptionTypes',
		'App.classes.combo.PrescrptionWhen',
		'App.classes.combo.PreventiveCareTypes',
		'App.classes.combo.ProceduresBodySites',
		'App.classes.combo.Providers',
		'App.classes.combo.Roles',
		'App.classes.combo.Sex',
		'App.classes.combo.Surgery',
		'App.classes.combo.TaxId',
		'App.classes.combo.Templates',
		'App.classes.combo.Time',
		'App.classes.combo.Titles',
		'App.classes.combo.TransmitMethod',
		'App.classes.combo.Types',
		'App.classes.combo.Units',
		'App.classes.combo.Users',

		'App.classes.form.FormPanel',
		'App.classes.form.fields.Checkbox',
		'App.classes.form.fields.Currency',
		'App.classes.form.fields.DateTime',

		'App.classes.window.Window',
		'App.classes.NodeDisabled',

		'App.view.patientfile.MedicalWindow',
		'App.view.patientfile.ChartsWindow',
		//'App.view.patientfile.PaymentEntryWindow',
		'App.view.patientfile.PreventiveCareWindow',
		'App.view.patientfile.NewDocumentsWindow',
		'App.view.patientfile.DocumentViewerWindow',
		'App.view.patientfile.ArrivalLogWindow',

		'App.view.dashboard.Dashboard',
		'App.view.calendar.Calendar',
		'App.view.messages.Messages',

		'App.view.patientfile.ItemsToReview',
		'App.view.patientfile.EncounterDocumentsGrid',
		'App.view.patientfile.encounter.ICDs',
		'App.view.patientfile.CheckoutAlertsView',
		'App.view.patientfile.Vitals',
		'App.view.patientfile.NewPatient',
		'App.view.patientfile.Summary',
		'App.view.patientfile.Visits',
		'App.view.patientfile.Encounter',
		'App.view.patientfile.MedicalWindow',
		'App.view.patientfile.VisitCheckout',

		'App.view.fees.Billing',
		'App.view.fees.PaymentEntryWindow',
		'App.view.fees.Payments',

		'App.view.administration.DataManager',
		'App.view.administration.Documents',
		'App.view.administration.Facilities',
		'App.view.administration.Globals',
		'App.view.administration.Layout',
		'App.view.administration.Lists',
		'App.view.administration.Log',
		'App.view.administration.Medications',
		'App.view.administration.Practice',
		'App.view.administration.PreventiveCare',
		'App.view.administration.Roles',
		'App.view.administration.Users',

		'App.view.miscellaneous.Addressbook',
		'App.view.miscellaneous.MyAccount',
		'App.view.miscellaneous.MySettings',
		'App.view.miscellaneous.OfficeNotes',
		'App.view.miscellaneous.Websearch'

	],

	minWidthToFullMode: 1680,
	currency          : '$',

	initComponent: function() {

		Ext.tip.QuickTipManager.init();

		var me = this;

		me.lastCardNode = null;
		me.currCardCmp = null;
		me.currPatient = null;
		me.currEncounterId = null;
		me.user = user;
		/**
		 * TaskScheduler
		 * This will run all the procedures inside the checkSession
		 */
		me.Task = {
			scope   : me,
			run     : function() {
				me.checkSession();
				me.getPatientesInPoolArea();
				CronJob.run();
			},
			interval: 5000 // 10 second
		};

		me.storeTree = Ext.create('App.store.navigation.Navigation', {
			autoLoad : true,
			listeners: {
				scope: me,
				load : me.afterNavigationLoad
			}
		});

		/**
		 * This store will handle the patient pool area
		 */
		me.patientPoolStore = Ext.create('App.store.poolarea.PoolArea');

		if(me.currency == '$') {
			me.icoMoney = 'icoDollar';
		} else if(me.currency == '€') {
			me.icoMoney = 'icoEuro';
		} else if(me.currency == '£') {
			me.icoMoney = 'icoLibra';
		} else if(me.currency == '¥') {
			me.icoMoney = 'icoYen';
		}

		/**
		 * GaiaEHR Support Page
		 */
		me.winSupport = Ext.create('Ext.window.Window', {
			title        : 'Support',
			closeAction  : 'hide',
			bodyStyle    : 'background-color: #ffffff; padding: 5px;',
			animateTarget: me.Footer,
			resizable    : false,
			draggable    : false,
			maximizable  : false,
			autoScroll   : true,
			maximized    : true,
			dockedItems  : {
				xtype: 'toolbar',
				dock : 'top',
				items: ['-', {
					text   : lang.issuesBugs,
					iconCls: 'list',
					action : 'http://GaiaEHR.org/projects/GaiaEHR001/issues',
					scope  : me,
					handler: me.showMiframe
				}, '-', {
					text   : lang.newIssueBug,
					iconCls: 'icoAddRecord',
					action : 'http://GaiaEHR.org/projects/GaiaEHR001/issues/new',
					scope  : me,
					handler: me.showMiframe
				}]
			}
		});

		/**
		 * header Panel
		 */
		me.Header = Ext.create('Ext.container.Container', {
			region     : 'north',
			height     : 44,
			split      : false,
			collapsible: false,
			collapsed  : true,
			frame      : false,
			border     : false,
			bodyStyle  : 'background: transparent',
			margins    : '0 0 0 0',
			items      : [
				//				{
				//					xtype : 'container',
				//                    itemId: 'appLogo',
				//                    width : window.innerWidth < this.minWidthToFullMode ? 35 : 200,
				//					html  : '<img src="ui_app/app_logo.png" height="40" width="200" style="float:left">',
				//					style : 'float:left',
				//					border: false
				//				},
				{
					xtype    : 'button',
					scale    : 'large',
					style    : 'float:left',
					margin   : 0,
					itemId   : 'patientButton',
					scope    : me,
					handler  : me.openPatientSummary,
					listeners: {
						scope      : me,
						afterrender: me.patientBtnRender
					},
					tpl      : me.patientBtn()
				},
				{
					xtype  : 'button',
					scale  : 'large',
					style  : 'float:left',
					margin : '0 0 0 3',
					cls    : 'headerLargeBtn',
					padding: 0,
					itemId : 'patientOpenVisits',
					iconCls: 'icoBackClock',
					scope  : me,
					handler: me.openPatientVisits,
					tooltip: 'Open Patient Visits History'
				},
				{
					xtype  : 'button',
					scale  : 'large',
					style  : 'float:left',
					margin : '0 0 0 3',
					cls    : 'headerLargeBtn',
					padding: 0,
					itemId : 'patientCreateEncounter',
					iconCls: 'icoClock',
					scope  : me,
					handler: me.createNewEncounter,
					tooltip: 'Create New Encounter'
				},
				{
					xtype  : 'button',
					scale  : 'large',
					style  : 'float:left',
					margin : '0 0 0 3',
					cls    : 'headerLargeBtn',
					padding: 0,
					itemId : 'patientCloseCurrEncounter',
					iconCls: 'icoArrowDown',
					scope  : me,
					handler: me.stowPatientRecord,
					tooltip: 'Stow Patient Record'
				},
				{
					xtype  : 'button',
					scale  : 'large',
					style  : 'float:left',
					margin : '0 0 0 3',
					cls    : 'headerLargeBtn',
					padding: 0,
					itemId : 'patientCheckOut',
					iconCls: 'icoCheckOut',
					scope  : me,
					handler: me.chargePatient,
					tooltip: 'Check Out Patient'
				},
				{
					xtype  : 'button',
					scale  : 'large',
					style  : 'float:left',
					margin : '0 0 0 3',
					cls    : 'headerLargeBtn',
					padding: 0,
					itemId : 'patientCharge',
					iconCls: me.icoMoney,
					scope  : me,
					handler: me.onPaymentEntryWindow,
					tooltip: 'Payment Entry'
				},
				{
					xtype      : 'panel',
					width      : 260,
					bodyPadding: '8 11 5 11',
					margin     : '0 0 0 3',
					style      : 'float:left',
					items      : [
						{
							xtype     : 'patienlivetsearch',
							emptyText : 'Patient Live Search...',
							fieldStyle: 'width:230',
							listeners : {
								scope : me,
								select: me.liveSearchSelect,
								blur  : function(combo) {
									combo.reset();
								}
							}
						}
					]
				},
				{
					xtype  : 'button',
					scale  : 'large',
					style  : 'float:left',
					margin : '0 0 0 3',
					padding: 4,
					itemId : 'patientNewReset',
					iconCls: 'icoAddPatient',
					scope  : me,
					handler: me.newPatient,
					tooltip: 'Create a new patient'
				},
				{
					xtype  : 'button',
					scale  : 'large',
					style  : 'float:left',
					margin : '0 0 0 3',
					cls    : 'headerLargeBtn emerBtn',
					overCls: 'emerBtnOver',
					padding: 0,
					itemId : 'createEmergency',
					iconCls: 'icoEmer',
					scope  : me,
					handler: me.createEmergency,
					tooltip: 'Create New Emergency'
				},
				{
					xtype    : 'button',
					text     : user.name,
					scale    : 'large',
					iconCls  : 'icoDoctor',
					iconAlign: 'left',
					cls      : 'drButton',
					style    : 'float:right',
					margin   : '0 0 0 3',
					menu     : [
						{
							text   : 'My account',
							iconCls: 'icoArrowRight',
							handler: function() {
								me.navigateTo('panelMyAccount');
							}
						},
						{
							text   : 'My settings',
							iconCls: 'icoArrowRight',
							handler: function() {
								me.navigateTo('panelMySettings');
							}
						},
						{
							text   : 'Logout',
							iconCls: 'icoArrowRight',
							scope  : me,
							handler: me.appLogout
						}
					]
				},
				{
					xtype  : 'button',
					scale  : 'large',
					style  : 'float:right',
					margin : '0 0 0 3',
					cls    : 'headerLargeBtn',
					padding: 0,
					itemId : 'patientCheckIn',
					iconCls: 'icoLog',
					scope  : me,
					handler: me.onPatientLog,
					tooltip: 'Arrival Log'
				},
				{
					xtype  : 'button',
					scale  : 'large',
					style  : 'float:right',
					margin : '0 0 0 3',
					cls    : 'headerLargeBtn',
					padding: 0,
					itemId : 'patientPoolArea',
					iconCls: 'icoPoolArea',
					scope  : me,
					handler: me.goToPoolAreas,
					tooltip: 'Pool Areas'
				}
			]
		});

		/**
		 * The panel definition for the the TreeMenu & the support button
		 */
		me.navColumn = Ext.create('Ext.panel.Panel', {
			title      : lang.navigation,
			stateId    : 'navColumn',
			layout     : 'border',
			region     : 'west',
			width      : 200,
			split      : true,
			collapsible: true,
			collapsed  : false,
			items      : [
				{
					xtype      : 'treepanel',
					region     : 'center',
					cls        : 'nav_tree',
					hideHeaders: true,
					rootVisible: false,
					border     : false,
					store      : me.storeTree,
					width      : 200,
					plugins    : [
						{ptype: 'nodedisabled'}
					],
					//					root       : {
					//						nodeType : 'async',
					//						draggable: false
					//					},
					listeners  : {
						scope          : me,
						selectionchange: me.onNavigationNodeSelected
					}
				},
				{
					xtype      : 'panel',
					title      : lang.patientPoolArea,
					layout     : 'fit',
					region     : 'south',
					itemId     : 'patientPoolArea',
					bodyPadding: 5,
					height     : 25,
					cls        : 'patient-pool',
					split      : true,
					collapsible: true,
					border     : false,
					items      : [
						{
							xtype            : 'dataview',
							loadMask         : false,
							cls              : 'patient-pool-view',
							tpl              : '<tpl for=".">' +

								'<div class="patient-pool-btn x-btn x-btn-default-large">' +
								'<div class="patient_btn_img"><img src="{photoSrc}" width="35" height="35"></div>' +
								'<div class="patient_btn_info">' +
								'<div class="patient-name">{shortName}</div>' +
								'<div class="patient-name">#{pid} ({poolArea})</div>' +
								'</div>' +
								'</div>' +
								'</tpl>',
							itemSelector     : 'div.patient-pool-btn',
							overItemCls      : 'patient-over',
							selectedItemClass: 'patient-selected',
							singleSelect     : true,
							store            : me.patientPoolStore,
							listeners        : {
								scope : me,
								render: me.initializeOpenEncounterDragZone
							}
						}
					]
				}
			],
			dockedItems: [
				{
					xtype  : 'toolbar',
					dock   : 'bottom',
					border : true,
					margin : '3 0 0 0',
					padding: 5,
					layout : {
						type: 'hbox',
						pack: 'center'
					},
					items  : ['-', {
						xtype  : 'button',
						frame  : true,
						text   : 'GaiaEHR ' + lang.support,
						iconCls: 'icoHelp',
						action : 'http://gaiaehr.org/',
						scope  : me,
						handler: me.showMiframe
					}, '-']
				}
			],
			listeners  : {
				scope         : me,
				beforecollapse: me.navCollapsed,
				beforeexpand  : me.navExpanded

			}
		});

		/**
		 * MainPanel is where all the pages are display
		 */
		me.MainPanel = Ext.create('Ext.container.Container', {
			region   : 'center',
			layout   : 'card',
			border   : true,
			itemId   : 'MainPanel',
			defaults : { layout: 'fit', xtype: 'container' },
			items    : [

			/**
			 * General Area
			 */
				Ext.create('App.view.dashboard.Dashboard'), // done  TODO: panels
				Ext.create('App.view.calendar.Calendar'), // done
				Ext.create('App.view.messages.Messages'), // done
				Ext.create('App.view.search.PatientSearch'), //

			/**
			 * Patient Area
			 */
				Ext.create('App.view.patientfile.NewPatient'),
				Ext.create('App.view.patientfile.Summary'),
				Ext.create('App.view.patientfile.Visits'),
				Ext.create('App.view.patientfile.Encounter'),
				Ext.create('App.view.patientfile.VisitCheckout'),

			/**
			 * Fees Area
			 */
				Ext.create('App.view.fees.Billing'),
				Ext.create('App.view.fees.Payments'),

			/**
			 * Miscellaneous
			 */
				Ext.create('App.view.miscellaneous.Addressbook'),
				Ext.create('App.view.miscellaneous.MyAccount'),
				Ext.create('App.view.miscellaneous.MySettings'),
				Ext.create('App.view.miscellaneous.OfficeNotes'),
				Ext.create('App.view.miscellaneous.Websearch'),

				me.ppdz = Ext.create('App.view.PatientPoolDropZone')

			],
			listeners: {
				scope      : me,
				afterrender: me.initializeOpenEncounterDropZone
			}
		});

		/**
		 * Add Administration Area Panels
		 */
		if(perm.access_gloabal_settings) {
			me.MainPanel.add(Ext.create('App.view.administration.Globals'));
		}
		if(perm.access_facilities) {
			me.MainPanel.add(Ext.create('App.view.administration.Facilities'));
		}
		if(perm.access_users) {
			me.MainPanel.add(Ext.create('App.view.administration.Users'));
		}
		if(perm.access_practice) {
			me.MainPanel.add(Ext.create('App.view.administration.Practice'));
		}
		if(perm.access_data_manager) {
			me.MainPanel.add(Ext.create('App.view.administration.DataManager'));
		}
		if(perm.access_preventive_care) {
			me.MainPanel.add(Ext.create('App.view.administration.PreventiveCare'));
		}
		if(perm.access_medications) {
			me.MainPanel.add(Ext.create('App.view.administration.Medications'));
		}
		if(perm.access_roles) {
			me.MainPanel.add(Ext.create('App.view.administration.Roles'));
		}
		if(perm.access_layouts) {
			me.MainPanel.add(Ext.create('App.view.administration.Layout'));
		}
		if(perm.access_lists) {
			me.MainPanel.add(Ext.create('App.view.administration.Lists'));
		}
		if(perm.access_event_log) {
			me.MainPanel.add(Ext.create('App.view.administration.Log'));
		}
		if(perm.access_documents) {
			me.MainPanel.add(Ext.create('App.view.administration.Documents'));
		}

		/**
		 * Footer Panel
		 */
		me.Footer = Ext.create('Ext.container.Container', {
			height : window.innerWidth < me.minWidthToFullMode ? 60 : 30,
			split  : false,
			padding: '3 0',
			region : 'south',
			items  : [
				{
					xtype            : 'dataview',
					margin           : '0 0 3 0',
					hidden           : true,
					hideMode         : 'offsets',
					cls              : 'patient-pool-view-footer x-toolbar x-toolbar-default x-box-layout-ct',
					tpl              : '<div class="x-toolbar-separator x-toolbar-item x-toolbar-separator-horizontal" style="float:left; margin-top:5px;" role="presentation" tabindex="-1"></div>' +
						'<tpl for=".">' +
						'<div class="patient-pool-btn-small x-btn x-btn-default-small" style="float:left">' +
						'<div class="patient_btn_info">' +
						'<div class="patient-name">{name} ({pid})</div>' +
						'</div>' +
						'</div>' +
						'<div class="x-toolbar-separator x-toolbar-item x-toolbar-separator-horizontal" style="float:left; margin-top:5px; margin-left:3px;" role="presentation" tabindex="-1"></div>' +
						'</tpl>',
					itemSelector     : 'div.patient-pool-btn-small',
					overItemCls      : 'patient-over',
					selectedItemClass: 'patient-selected',
					singleSelect     : true,
					loadMask         : false,
					store            : me.patientPoolStore,
					listeners        : {
						scope : me,
						render: me.initializeOpenEncounterDragZone
					}
				},
				{
					xtype: 'toolbar',
					dock : 'bottom',
					items: [
						{
							text    : 'Copyright (C) 2011 GaiaEHR (Electronic Health Records) |:|  Open Source Software operating under GPLv3 ',
							iconCls : 'icoGreen',
							disabled: true,
							action  : 'http://GaiaEHR.org/projects/GaiaEHR001',
							scope   : me,
							handler : me.showMiframe
						},
						'->',
						{
							text   : 'news',
							action : 'http://GaiaEHR.org/projects/GaiaEHR001/news',
							scope  : me,
							handler: me.showMiframe
						},
						'-',
						{
							text   : 'wiki',
							action : 'http://gaiaehr.org/',
							scope  : me,
							handler: me.showMiframe
						},
						'-',
						{
							text   : 'issues',
							action : 'http://gaiaehr.org/',
							scope  : me,
							handler: me.showMiframe
						},
						'-',
						{
							text   : 'forums',
							action : 'http://gaiaehr.org/',
							scope  : me,
							handler: me.showMiframe
						}
					]
				}
			]
		});

		me.MedicalWindow = Ext.create('App.view.patientfile.MedicalWindow');
		me.ChartsWindow = Ext.create('App.view.patientfile.ChartsWindow');
		me.PaymentEntryWindow = Ext.create('App.view.fees.PaymentEntryWindow');
		me.PreventiveCareWindow = Ext.create('App.view.patientfile.PreventiveCareWindow');
		me.NewDocumentsWindow = Ext.create('App.view.patientfile.NewDocumentsWindow');
		me.DocumentViewerWindow = Ext.create('App.view.patientfile.DocumentViewerWindow');
		me.layout = { type: 'border', padding: 3 };
		me.defaults = { split: true };
		me.items = [ me.Header, me.navColumn, me.MainPanel, me.Footer ];

		me.listeners = {
			afterrender : me.afterAppRender,
			beforerender: me.beforeAppRender
		};

		me.callParent(arguments);

		me.signature = Ext.create('App.view.signature.SignatureWindow');

	},

	onMedicalWin: function(btn) {
		this.MedicalWindow.show();
		this.MedicalWindow.down('toolbar').getComponent(btn.action).toggle(true);
		this.MedicalWindow.cardSwitch(btn);
	},

	onChartsWin      : function() {
		this.ChartsWindow.show();
	},
	onNewDocumentsWin: function(action) {
		this.NewDocumentsWindow.show();
		this.NewDocumentsWindow.cardSwitch(action);
	},

	onWebCamComplete: function(msg) {
		var panel = this.getActivePanel();
		if(panel.id == 'panelSummary') {
			panel.completePhotoId();
		}
		this.msg('Sweet!', 'Patient image saved');
	},

	onPatientLog: function() {
		if(this.patientArrivalLog) {
			this.patientArrivalLog.show();
		} else {
			this.patientArrivalLog = Ext.create('App.view.patientfile.ArrivalLogWindow').show();
		}
	},

	onPaymentEntryWindow: function() {
		this.PaymentEntryWindow.show();
	},

	newPatient: function() {
		var me = this;
		me.navigateTo('panelNewPatient');
	},

	createEmergency: function() {
		alert('Emergency Button Clicked');
	},

	createNewEncounter: function() {
		var me = this;
		if(perm.access_encounters && perm.add_encounters) {
			me.navigateTo('panelEncounter', function(success) {
				if(success) {
					me.currCardCmp.newEncounter();
				}
			});
		} else {
			me.accessDenied();
		}
	},

	sendPatientTo: function(btn) {
		var area = btn.action;
		alert('TODO: Patient will be sent to ' + area);
	},

	openPatientSummary: function() {
		var me = this;

		if(me.currCardCmp == Ext.getCmp('panelSummary')) {
			var same = true;
		}

		me.navigateTo('panelSummary', function() {
			if(same) {
				me.currCardCmp.onActive();
			}
		});
	},

	stowPatientRecord: function() {
		this.patientUnset();
		this.navigateTo('panelDashboard');
	},

	openCurrEncounter: function() {
		var me = this;

		me.navigateTo('panelEncounter', function(success) {
			if(success) {
				//me.currCardCmp.openEncounter(eid);
			}
		});
	},

	openEncounter: function(eid) {
		var me = this;
		if(perm.access_encounters) {
			me.navigateTo('panelEncounter', function(success) {
				if(success) {
					me.currCardCmp.openEncounter(eid);
				}
			});
		} else {
			me.accessDenied();
		}
	},

	checkOutPatient: function(eid) {
		var me = this;

		me.navigateTo('panelVisitCheckout', function(success) {
			if(success) {
				me.currCardCmp.setPanel(eid);

			}
		});
	},

	chargePatient: function() {
		this.navigateTo('panelVisitPayment');
	},

	openPatientVisits: function() {
		this.navigateTo('panelVisits');
	},

	goToPoolAreas: function() {
		this.navigateTo('panelPoolArea');
	},

	navigateTo: function(id, callback) {
		var tree = this.navColumn.down('treepanel'),
			treeStore = tree.getStore(),
			sm = tree.getSelectionModel(),
			node = treeStore.getNodeById(id);

		sm.select(node);
		if(typeof callback == 'function') {
			callback(true);
		}
	},

	navigateToDefault: function() {
		this.navigateTo('panelDashboard');
	},

	afterNavigationLoad: function() {
		window.innerWidth < this.minWidthToFullMode ? this.navColumn.collapse() : this.navColumn.expand();
		this.navigateToDefault();
		this.removeAppMask();
		Ext.TaskManager.start(this.Task);
	},

	onNavigationNodeSelected: function(model, selected) {
		var me = this;
		if(0 < selected.length) {
			if(selected[0].data.leaf) {
				var tree = me.navColumn.down('treepanel'),
					sm = tree.getSelectionModel(),
					card = selected[0].data.id,
					layout = me.MainPanel.getLayout(),
					cardCmp = Ext.getCmp(card);

				me.currCardCmp = cardCmp;
				layout.setActiveItem(card);
				cardCmp.onActive(function(success) {
					(success) ? me.lastCardNode = sm.getLastSelected() : me.goBack();
				});
			}
		}
	},

	goBack: function() {
		var tree = this.navColumn.down('treepanel'),
			sm = tree.getSelectionModel();
		sm.select(this.lastCardNode);
	},

	navCollapsed: function() {
		var navView = this.navColumn.getComponent('patientPoolArea'),
		//appLogo = this.Header.getComponent('appLogo'),
			foot = this.Footer, footView;

		if(foot) {
			footView = foot.down('dataview');
			foot.setHeight(60);
			footView.show();
		}

		//appLogo.hide();
		navView.hide();

	},

	navExpanded: function() {
		var navView = this.navColumn.getComponent('patientPoolArea'),
		//appLogo = this.Header.getComponent('appLogo'),
			foot = this.Footer, footView;

		if(foot) {
			footView = foot.down('dataview');
			foot.setHeight(30);
			footView.hide();
		}
		//appLogo.show();
		navView.show();

	},

	getActivePanel: function() {
		return this.MainPanel.getLayout().getActiveItem();
	},

	liveSearchSelect: function(combo, selection) {
		this.currEncounterId = null;
		var me = this,
			post = selection[0];
		if(post) {
			Patient.currPatientSet({pid: post.get('pid')}, function() {
				me.setCurrPatient(post.get('pid'), post.get('fullname'), function() {
					me.openPatientSummary();
				});
			});
		}
	},

	setCurrPatient: function(pid, fullname, callback) {
		var me = this,
			patientBtn = me.Header.getComponent('patientButton'),
			patientOpenVisitsBtn = me.Header.getComponent('patientOpenVisits'),
			patientCreateEncounterBtn = me.Header.getComponent('patientCreateEncounter'),
			patientCloseCurrEncounterBtn = me.Header.getComponent('patientCloseCurrEncounter'),
			patientChargeBtn = me.Header.getComponent('patientCharge'),
			patientCheckOutBtn = me.Header.getComponent('patientCheckOut');
		me.patientUnset(function() {
			Patient.currPatientSet({ pid: pid }, function(provider, response) {
				var data = response.result, msg1, msg2;
				if(data.readOnly) {
					msg1 = data.user + ' is currently working with "' + fullname + '" in "' + data.area + '" area.<br>' +
						'Override "Read Mode" will remove the patient from previous user.<br>' +
						'Do you would like to override "Read Mode"?';
					msg2 = data.user + ' is currently working with "' + fullname + '" in "' + data.area + '" area.<br>';
					Ext.Msg.show({
						title  : 'Wait!!!',
						msg    : data.overrideReadOnly ? msg1 : msg2,
						buttons: data.overrideReadOnly ? Ext.Msg.YESNO : Ext.Msg.OK,
						icon   : Ext.MessageBox.WARNING,
						fn     : function(btn) {
							continueSettingPatient(btn != 'yes');
						}
					});
				} else {
					continueSettingPatient(false);
				}
				function continueSettingPatient(readOnly) {
					me.currEncounterId = null;
					me.currPatient = {
						pid     : pid,
						name    : fullname,
						readOnly: readOnly
					};
					patientBtn.update({ pid: pid, name: fullname });
					patientBtn.enable();
					patientOpenVisitsBtn.enable();
					patientCreateEncounterBtn.enable();
					patientCloseCurrEncounterBtn.enable();
					patientChargeBtn.enable();
					patientCheckOutBtn.enable();
					if(typeof callback == 'function') {
						callback(true);
					}
				}
			});
		});
	},

	patientUnset: function(callback) {
		var me = this,
			patientBtn = me.Header.getComponent('patientButton'),
			patientOpenVisitsBtn = me.Header.getComponent('patientOpenVisits'),
			patientCreateEncounterBtn = me.Header.getComponent('patientCreateEncounter'),
			patientCloseCurrEncounterBtn = me.Header.getComponent('patientCloseCurrEncounter'),
			patientChargeBtn = me.Header.getComponent('patientCharge'),
			patientCheckOutBtn = me.Header.getComponent('patientCheckOut');
		Patient.currPatientUnset(function() {
			me.currEncounterId = null;
			me.currPatient = null;
			if(typeof callback == 'function') {
				callback(true);
			} else {
				patientCreateEncounterBtn.disable();
				patientOpenVisitsBtn.disable();
				patientCloseCurrEncounterBtn.disable();
				patientChargeBtn.disable();
				patientCheckOutBtn.disable();
				patientBtn.disable();
				patientBtn.update({ pid: 'record number', name: 'No Patient Selected'});
			}
		});
	},

	showMiframe: function(btn) {
		var src = btn.action;
		this.winSupport.remove(this.miframe);
		this.winSupport.add(this.miframe = Ext.create('App.classes.ManagedIframe', {src: src}));
		this.winSupport.show();
	},

	msg: function(title, format) {
		if(!this.msgCt) {
			this.msgCt = Ext.core.DomHelper.insertFirst(document.body, {id: 'msg-div'}, true);
		}
		this.msgCt.alignTo(document, 't-t');
		var s = Ext.String.format.apply(String, Array.prototype.slice.call(arguments, 1)),
			m = Ext.core.DomHelper.append(this.msgCt, {html: '<div class="msg"><h3>' + title + '</h3><p>' + s + '</p></div>'}, true);
		m.slideIn('t').pause(3000).ghost('t', {remove: true});
	},

	checkSession: function() {
		authProcedures.ckAuth(function(provider, response) {
			if(!response.result.authorized) {
				authProcedures.unAuth(function() {
					window.location = "./"
				});
			}
		});
	},

	patientBtn: function() {
		return Ext.create('Ext.XTemplate',
			'<div class="patient_btn">',
			'<div class="patient_btn_img"><img src="ui_icons/user_32.png"></div>',
			'<div class="patient_btn_info">',
			'<div class="patient_btn_name">{name}</div>',
			'<div class="patient_btn_record">( {pid} )</div>',
			'</div>',
			'</div>');
	},

	patientBtnRender: function(btn) {
		this.patientUnset();
		this.initializePatientPoolDragZone(btn)
	},

	getPatientesInPoolArea: function() {
		var poolArea = this.navColumn.getComponent('patientPoolArea'),
			height = 35;
		this.patientPoolStore.load({
			callback: function(records) {
				if(records.length >= 1) {
					Ext.each(records, function(record) {
						height = height + 45;
					});
				} else {
					height = 25;
				}
				height = (height >= 303) ? 303 : height;
				poolArea.down('dataview').refresh();
				poolArea.setHeight(height);
				poolArea.doLayout();
			}
		});

		this.ppdz.reloadStores();

	},

	appLogout: function() {
		Ext.Msg.show({
			title  : 'Please confirm...',
			msg    : 'Are you sure to quit GaiaEHR?',
			icon   : Ext.MessageBox.QUESTION,
			buttons: Ext.Msg.YESNO,
			fn     : function(btn) {
				if(btn == 'yes') {
					authProcedures.unAuth(function() {
						window.location = "./"
					});
				}
			}
		});
	},

	initializePatientPoolDragZone: function(panel) {
		panel.dragZone = Ext.create('Ext.dd.DragZone', panel.getEl(), {
			ddGroup    : 'patientPoolAreas',
			// On receipt of a mousedown event, see if it is within a draggable element.
			// Return a drag data object if so. The data object can contain arbitrary application
			// data, but it should also contain a DOM element in the ddel property to provide
			// a proxy to drag.
			getDragData: function() {

				var sourceEl = app.Header.getComponent('patientButton').el.dom, d;
				app.MainPanel.getLayout().setActiveItem(app.ppdz);
				app.navColumn.down('treepanel').getSelectionModel().deselectAll();

				if(sourceEl) {
					d = sourceEl.cloneNode(true);
					d.id = Ext.id();
					return panel.dragData = {
						copy    : true,
						sourceEl: sourceEl,
						repairXY: Ext.fly(sourceEl).getXY(),
						ddel    : d,
						records : [ panel.data ],
						patient : true
					};
				}
			},
			// Provide coordinates for the proxy to slide back to on failed drag.
			// This is the original XY coordinates of the draggable element.
			getRepairXY: function() {
				app.goBack();
				return this.dragData.repairXY;
			}
		});
	},

	/**
	 *
	 * @param panel
	 */
	initializeOpenEncounterDragZone: function(panel) {
		panel.dragZone = Ext.create('Ext.dd.DragZone', panel.getEl(), {
			ddGroup    : 'patient',
			// On receipt of a mousedown event, see if it is within a draggable element.
			// Return a drag data object if so. The data object can contain arbitrary application
			// data, but it should also contain a DOM element in the ddel property to provide
			// a proxy to drag.
			getDragData: function(e) {
				var sourceEl = e.getTarget(panel.itemSelector, 10), d;
				app.MainPanel.el.mask('Drop Here To Open <strong>"' + panel.getRecord(sourceEl).data.name + '"</strong> Current Encounter');
				if(sourceEl) {
					d = sourceEl.cloneNode(true);
					d.id = Ext.id();
					return panel.dragData = {
						sourceEl   : sourceEl,
						repairXY   : Ext.fly(sourceEl).getXY(),
						ddel       : d,
						patientData: panel.getRecord(sourceEl).data
					};
				}
			},
			// Provide coordinates for the proxy to slide back to on failed drag.
			// This is the original XY coordinates of the draggable element.
			getRepairXY: function() {
				app.MainPanel.el.unmask();
				return this.dragData.repairXY;
			}
		});
	},
	onDocumentView                 : function(src) {
		var me = this;
		if(me.documentViewWindow) me.DocumentViewerWindow.remove(me.documentViewWindow);
		me.DocumentViewerWindow.add(me.documentViewWindow = Ext.create('App.classes.ManagedIframe', {src: src}));
		me.DocumentViewerWindow.show();
	},
	/**
	 *
	 * @param panel
	 */
	initializeOpenEncounterDropZone: function(panel) {
		var me = this;
		panel.dropZone = Ext.create('Ext.dd.DropZone', panel.getEl(), {
			ddGroup   : 'patient',
			notifyOver: function() {
				return Ext.dd.DropZone.prototype.dropAllowed;
			},
			notifyDrop: function(dd, e, data) {
				app.MainPanel.el.unmask();

				me.setCurrPatient(data.patientData.pid, data.patientData.name, function() {
					if(data.patientData.eid && data.patientData.poolArea == 'Check Out') {
						me.checkOutPatient(data.patientData.eid);
					} else if(data.patientData.eid && perm.access_encounters) {
						me.openEncounter(data.patientData.eid);
					} else {
						me.openPatientSummary();
					}
				});

			}
		});
	},

	afterAppRender: function() {

	},

	removeAppMask: function() {
		Ext.get('mainapp-loading').remove();
		Ext.get('mainapp-loading-mask').fadeOut({remove: true});
	},

	beforeAppRender: function() {

	},

	getCurrPatient: function() {
		return this.currPatient;
	},

	getApp: function() {
		return this;
	},

	accessDenied: function() {
		Ext.Msg.show({
			title  : 'Oops!',
			msg    : 'Access Denied',
			buttons: Ext.Msg.OK,
			icon   : Ext.Msg.ERROR
		});
	}

});