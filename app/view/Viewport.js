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

Ext.define('App.view.Viewport', {
    extend: 'Ext.Viewport',
    // app settings
    user: window.user, // array defined on _app.php
    version: window.version, // string defined on _app.php
    minWidthToFullMode: 1700, // full mode = nav expanded 1585
    currency: g('gbl_currency_symbol'), // currency used
	patientImage:'resources/images/patientPhotoPlaceholder.jpg',
	enablePoolAreaFadeInOut: eval(g('enable_poolarea_fade_in_out')),

	// end app settings
    initComponent: function(){

	    Ext.state.Manager.setProvider(Ext.create('Ext.state.CookieProvider'));
	    Ext.tip.QuickTipManager.init();
        var me = this;

	    me.user.getFullName = function(){
		    var fullname = me.user.title + ' ' + me.user.fname + ' ' + me.user.mname + ' ' + me.user.lname;
			return fullname.replace('  ', ' ').trim();
	    };

	    me.nav = me.getController('Navigation');
	    me.cron = me.getController('Cron');
	    me.log = me.getController('LogOut');
	    me.dual = me.getController('DualScreen');
	    me.notification = me.getController('Notification');

	    me.logged = true;

        if(eval(g('enable_dual_monitor'))) me.dual.startDual();

	    me.lastCardNode = null;
        me.prevNode = null;
        me.fullMode = window.innerWidth >= me.minWidthToFullMode;

	    me.patient = {
	        pid: null,
	        pubpid: null,
	        name: null,
	        pic: null,
	        sex: null,
	        sexSymbol: null,
	        dob: null,
	        age: null,
	        eid: null,
	        priority: null,
	        readOnly: false,
	        rating: null
        };

        /**
         * This store will handle the patient pool area
         */
        me.patientPoolStore = Ext.create('App.store.areas.PatientAreas');
        /*
         * TODO: this should be managed by the language files
         * The language file has a definition for this.
         */
        if(me.currency == '$'){
            me.icoMoney = 'icoDollar';
        }else if(me.currency == '€'){
            me.icoMoney = 'icoEuro';
        }else if(me.currency == '£'){
            me.icoMoney = 'icoLibra';
        }else if(me.currency == '¥'){
            me.icoMoney = 'icoYen';
        }

        /**
         * header Panel
         */
        me.Header = Ext.create('Ext.container.Container', {
            region: 'north',
            height: 44,
            split: false,
            collapsible: false,
            collapsed: false,
            frame: false,
            border: false,
	        cls: 'appHeader',
            bodyStyle: 'background: transparent',
            margins: '0 0 0 0',
	        layout: 'hbox'
        });

		me.HeaderLeft = Ext.widget('container', {
			margin: 0,
			flex: 1,
			layout: 'hbox',
			itemId: 'AppHeaderLeft'
		});

		me.HeaderRight = Ext.widget('container',{
			margin: 0,
			layout: 'hbox',
			itemId: 'AppHeaderRight'
		});

	    me.HeaderLeft.add({
		    xtype: 'button',
		    scale: 'large',
		    margin: '0 3 0 0',
		    cls: 'headerLargeBtn',
		    padding: 0,
		    iconCls: 'icoHome',
		    scope: me,
		    handler: me.openDashboard,
		    tooltip: _('patient_visits_history')
        });

	    me.HeaderLeft.add({
		    xtype: 'button',
		    scale: 'large',
		    margin: '0 3 0 0',
		    cls: 'headerLargeBtn',
		    padding: 0,
		    iconCls: 'icoCalendar2',
		    scope: me,
		    handler: me.openCalendar,
		    tooltip: _('patient_visits_history')
        });

	    me.HeaderLeft.add({ xtype: 'tbseparator' });

	    me.patientBtn = me.HeaderLeft.add({
            xtype: 'button',
            scale: 'large',
		    margin: '0 3 0 0',
		    style: 'height: 42px',
            tooltip: _('patient_btn_drag'),
            listeners: {
                scope: me,
                afterrender: me.patientBtnRender
            },
            tpl: me.patientBtnTpl()
        });

	    me.HeaderLeft.add({ xtype: 'tbseparator' });

	    me.patientSummaryBtn = me.HeaderLeft.add({
            xtype: 'button',
            scale: 'large',
            margin: '0 3 0 0',
            cls: 'headerLargeBtn',
            padding: 0,
            iconCls: 'icoPatientInfo',
            scope: me,
            handler: me.openPatientSummary,
            tooltip: _('patient_summary')
        });

	    if(a('access_patient_visits')){
		    me.patientOpenVisitsBtn = me.HeaderLeft.add({
			    xtype: 'button',
			    scale: 'large',
			    margin: '0 3 0 0',
			    cls: 'headerLargeBtn',
			    padding: 0,
			    iconCls: 'icoBackClock',
			    scope: me,
			    handler: me.openPatientVisits,
			    tooltip: _('patient_visits_history')
		    });
	    }

	    if(a('add_encounters')){
            me.patientCreateEncounterBtn = me.HeaderLeft.add({
                xtype: 'button',
                scale: 'large',
	            margin: '0 3 0 0',
                cls: 'headerLargeBtn',
                padding: 0,
                iconCls: 'icoClock',
                scope: me,
                handler: me.createNewEncounter,
                tooltip: _('new_encounter')
            });
        }

	    me.patientCloseCurrEncounterBtn = me.HeaderLeft.add({
            xtype: 'button',
            scale: 'large',
		    margin: '0 3 0 0',
            cls: 'headerLargeBtn',
            padding: 0,
            iconCls: 'icoArrowDown',
            scope: me,
            handler: me.stowPatientRecord,
            tooltip: _('stow_patient_record')
        });

//	    if(a('access_patient_visit_checkout')){
//		    me.patientCheckOutBtn = me.HeaderLeft.add({
//			    xtype: 'button',
//			    scale: 'large',
//			    margin: '0 3 0 0',
//			    cls: 'headerLargeBtn',
//			    padding: 0,
//			    iconCls: 'icoCheckOut',
//			    scope: me,
//			    handler: me.checkOutPatient,
//			    tooltip: _('visit_check_out')
//		    });
//	    }


//	    me.patientChargeBtn = me.Header.add({
//            xtype: 'button',
//            scale: 'large',
//            style: 'float:left',
//            margin: '0 0 0 3',
//            cls: 'headerLargeBtn',
//            padding: 0,
//            iconCls: me.icoMoney,
//            scope: me,
//            handler: me.onPaymentEntryWindow,
//            tooltip: _('payment_entry')
//        });

	    if(a('access_patient_search')){
		    me.HeaderLeft.add({
			    xtype: 'panel',
			    bodyPadding: '8 11 5 11',
			    margin: '0 3 0 0',
			    items: [
				    {
					    xtype: 'patienlivetsearch',
					    emptyText: _('patient_live_search') + '...',
					    width: 300,
					    listeners: {
						    scope: me,
						    select: me.liveSearchSelect,
						    blur: function(combo){
							    combo.reset();
						    }
					    }
				    }
			    ]
		    });
	    }

	    if(a('add_patient')){
		    me.HeaderLeft.add({
			    xtype: 'button',
			    scale: 'large',
			    margin: '0 3 0 0',
			    padding: 4,
			    itemId: 'patientNewReset',
			    iconCls: 'icoAddPatient',
			    scope: me,
			    handler: me.newPatient,
			    tooltip: _('create_a_new_patient')
		    });
	    }

	    if(a('create_emergency_encounter')){
		    me.HeaderLeft.add({
			    xtype: 'button',
			    scale: 'large',
			    margin: '0 3 0 0',
			    cls: 'headerLargeBtn emerBtn',
			    overCls: 'emerBtnOver',
			    padding: 0,
			    itemId: 'createEmergency',
			    iconCls: 'icoEmer',
			    scope: me,
			    handler: me.createEmergency,
			    tooltip: _('create_new_emergency')
		    });
	    }

	    if(a('access_floor_plan_panel')){
		    me.HeaderRight.add({
			    xtype: 'button',
			    scale: 'large',
			    margin: '0 3 0 0',
			    cls: 'headerLargeBtn',
			    padding: 0,
			    itemId: 'floorPlans',
			    iconCls: 'icoZoneAreasBig',
			    scope: me,
			    handler: me.goToFloorPlans,
			    tooltip: _('floor_plans')
		    });
	    }

	    if(a('access_pool_areas_panel')){
		    me.HeaderRight.add({
			    xtype: 'button',
			    scale: 'large',
			    margin: '0 3 0 0',
			    cls: 'headerLargeBtn',
			    padding: 0,
			    itemId: 'patientPoolArea',
			    iconCls: 'icoPoolArea',
			    scope: me,
			    handler: me.goToPoolAreas,
			    tooltip: _('pool_areas')
		    });
	    }

	    if(a('access_poolcheckin')){
		    me.HeaderRight.add({
			    xtype: 'button',
			    scale: 'large',
			    margin: '0 3 0 0',
			    cls: 'headerLargeBtn',
			    padding: 0,
			    itemId: 'patientCheckIn',
			    iconCls: 'icoCheckIn',
			    scope: me,
			    handler: me.onPatientLog,
			    tooltip: _('arrival_log')
		    });
	    }

	    me.userSplitBtn = me.HeaderRight.add({
		    xtype: 'button',
		    text: me.user.title + ' ' + me.user.lname,
		    scale: 'large',
		    iconCls: isEmerAccess ? 'icoUnlocked32' : 'icoDoctor',
		    iconAlign: 'left',
		    style: 'height: 42px',
		    plugins:[
			    {
				    ptype:'badgetext',
				    defaultText: 0
			    }
		    ],
		    itemId:'userSplitButton',
		    cls: 'drButton',
		    margin: 0,
		    menu: [
			    {
				    text: _('my_account'),
				    iconCls: 'icoUser',
				    handler: function(){
					    me.nav.navigateTo('App.view.miscellaneous.MyAccount');
				    }
			    },
			    {
				    text: _('logout'),
				    iconCls: 'icoLogout',
				    action:'logout'
			    }
		    ]
	    });

	    if(a('emergency_access')){
		    me.userSplitBtn.menu.insert(0,{
			    text:_('emergency_access'),
			    cls: 'emergency',
			    iconCls:'icoUnlocked',
			    scope:me,
			    handler:me.onEmergencyAccessClick
		    });
	    }

	    me.Header.add([me.HeaderLeft, me.HeaderRight]);

        /**
         * The panel definition for the the TreeMenu & the support button
         */
        me.navColumn = Ext.create('Ext.panel.Panel', {
            title: _('navigation'),
            action: 'mainNavPanel',
            layout: 'border',
            region: g('main_navigation_menu_left'),
            width: parseFloat(g('gbl_nav_area_width')),
            split: true,
            collapsible: true,
            collapsed: false,
	        stateId: 'mainNavPanel',
	        stateful: true,
            items: [
                {
                    xtype: 'treepanel',
                    region: 'center',
	                action:'mainNav',
                    cls: 'nav_tree',
                    hideHeaders: true,
                    rootVisible: false,
                    border: false,
                    width: parseFloat(g('gbl_nav_area_width')),
	                store: Ext.create('App.store.navigation.Navigation', {
		                autoLoad: true
	                })
                },
                me.patientPoolArea = Ext.create('Ext.Panel', {
                    title: _('patient_pool_areas'),
                    region: 'south',
	                action:'patientPoolArea',
                    bodyPadding: 5,
                    height: 25,
                    cls: 'patient-pool',
                    split: true,
                    collapsible: true,
                    border: false,
	                overflowY: 'auto',
                    items: [
                        {
                            xtype: 'dataview',
                            loadMask: false,
                            cls: 'patient-pool-view',
                            tpl: new Ext.XTemplate(
	                            '<tpl for=".">' +
                                '<div class="patient-pool-btn x-btn x-btn-default-large {priority}">' +
                                '<div class="patient_btn_img">' +
	                            '<img src="{[this.getPatientImage(values.patient)]}" width="50" height="50">' +
	                            '</div>' +
                                '<div class="patient_btn_info">' +
                                '<div class="patient-name">{shortName}</div>' +
                                '<div class="patient-name">({poolArea})</div>' +
                                '</div>' +
                                '</div>' +
                                '</tpl>',
	                            {
		                            getPatientImage:function(patient){
										return patient.image ? patient.image : me.patientImage;
		                            }
	                            }
                            ),
                            itemSelector: 'div.patient-pool-btn',
                            overItemCls: 'patient-over',
                            selectedItemClass: 'patient-selected',
                            singleSelect: true,
                            store: me.patientPoolStore,
                            listeners: {
                                scope: me,
                                render: me.onEncounterDragZoneRender
                            }
                        }
                    ]
                })
            ],
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'bottom',
                    border: true,
                    margin: '3 0 0 0',
                    padding: 5,
                    layout: {
                        type: 'hbox',
                        pack: 'center'
                    },
                    items: ['-', {
                        xtype: 'button',
                        frame: true,
                        text: 'GaiaEHR Support',
                        iconCls: 'icoHelp',
	                    action: 'supportBtn',
	                    src: 'http://gaiaehr.org/forums/'
                    }, '-']
                }
            ]
        });

        /**
         * MainPanel is where all the pages are displayed
         */
        me.MainPanel = Ext.create('Ext.container.Container', {
            region: 'center',
            layout: 'card',
            border: true,
            itemId: 'MainPanel',
	        deferredRender: true,
            defaults: {
                layout: 'fit',
                xtype: 'container'
            },
            listeners: {
                scope: me,
                afterrender: me.initializeOpenEncounterDropZone
            }
        });

        /**
         * General Area
         */
        me.nav['App_view_dashboard_Dashboard'] = me.MainPanel.add(Ext.create('App.view.dashboard.Dashboard'));
        me.nav['App_view_areas_FloorPlan'] = me.MainPanel.add(Ext.create('App.view.areas.FloorPlan'));
        me.nav['App_view_areas_PatientPoolAreas'] = me.MainPanel.add(Ext.create('App.view.areas.PatientPoolAreas'));

        /**
         * Footer Panel
         */
        me.Footer = Ext.create('Ext.container.Container', {
            height: 30,
            split: false,
            padding: '3 0',
            region: 'south',
	        action:'appFooter',
            items: [
                {
                    xtype: 'dataview',
                    margin: '0 0 3 0',
                    hidden: true,
                    hideMode: 'offsets',
                    cls: 'patient-pool-view-footer x-toolbar x-toolbar-default x-box-layout-ct',
                    tpl: '<div class="x-toolbar-separator x-toolbar-item x-toolbar-separator-horizontal" style="float:left; margin-top:5px;" role="presentation" tabindex="-1"></div>' + '<tpl for=".">' + '<div class="patient-pool-btn-small x-btn x-btn-default-small {priority}" style="float:left">' + '<div class="patient_btn_info">' + '<div class="patient-name">{name} ({pid})</div>' + '</div>' + '</div>' + '<div class="x-toolbar-separator x-toolbar-item x-toolbar-separator-horizontal" style="float:left; margin-top:5px; margin-left:3px;" role="presentation" tabindex="-1"></div>' + '</tpl>',
                    itemSelector: 'div.patient-pool-btn-small',
                    overItemCls: 'patient-over',
                    selectedItemClass: 'patient-selected',
                    singleSelect: true,
                    loadMask: false,
                    store: me.patientPoolStore,
                    listeners: {
	                    scope: me,
                        render: me.onEncounterDragZoneRender
                    }
                },
                {
                    xtype: 'toolbar',
                    dock: 'bottom',
                    items: [
	                    {
		                    xtype:'activefacilitiescombo',
		                    emptyText:'Facilities',
		                    width: parseFloat(g('gbl_nav_area_width')) - 4,
		                    hidden: !eval(a('access_to_other_facilities')),
		                    listeners:{
			                    scope: me,
			                    select: me.onFacilitySelect
		                    }
	                    },
	                    '-',
                        {
                            text: 'Copyright (C) 2011 GaiaEHR (Electronic Health Records) |:|  Open Source Software operating under GPLv3 |:| v' + me.version,
                            iconCls: 'icoGreen',
                            disabled: true
                        },
                        '->',
                        {
                            text: _('news'),
	                        action: 'supportBtn',
	                        src: 'http://GaiaEHR.org/projects/GaiaEHR001/news'
                        },
                        '-',
                        {
                            text: _('wiki'),
	                        action: 'supportBtn',
	                        src: 'http://gaiaehr.org/'
                        },
                        '-',
                        {
                            text: _('issues'),
	                        action: 'supportBtn',
                            src: 'http://gaiaehr.org:8181/issues/?jql='
                        },
                        '-',
                        {
                            text: _('forums'),
	                        action: 'supportBtn',
	                        src: 'http://gaiaehr.org/forums/'
                        }
                    ]
                }
            ]
        });

	    me.FacilityCmb = me.Footer.query('activefacilitiescombo')[0];
		me.FacilityCmb.getStore().on('load', me.onFacilityComboLoad, me);

        me.MedicalWindow = Ext.create('App.view.patient.windows.Medical');
        me.ChartsWindow = Ext.create('App.view.patient.windows.Charts');
        me.PaymentEntryWindow = Ext.create('App.view.fees.PaymentEntryWindow');
        me.newEncounterWindow = Ext.create('App.view.patient.windows.NewEncounter');

        if(a('access_encounter_checkout')){
            me.checkoutWindow = Ext.create('App.view.patient.windows.EncounterCheckOut');
        }

        me.layout = {
            type: 'border',
            padding: 3
        };

        me.defaults = {
            split: true
        };

	    me.items = [me.Header, me.navColumn, me.MainPanel, me.Footer];

	    me.listeners = {
	        scope: me,
            render: me.appRender,
            beforerender: me.beforeAppRender
        };
        me.callParent(arguments);

	    me.signature = Ext.create('App.view.signature.SignatureWindow');
    },

	getUserFullname: function(){
		return this.user.title + ' ' + this.user.fname + ' ' + this.user.mname + ' ' + this.user.lname
	},

	getController:function(controller){
		return App.Current.getController(controller);
	},

	onFacilitySelect:function(cmb, records){
		var me = this;
		Facilities.setFacility(records[0].data.option_value, function(provider, response){
			if(records[0].data.option_value == response.result){
				// set user global facility value
				app.user.facility = records[0].data.option_value;

				me.msg(_('sweet'), _('facility') + ' ' + records[0].data.option_name);
				me.setWindowTitle(records[0].data.option_name);
				me.nav['App_view_areas_PatientPoolDropZone'].reRenderPoolAreas();
				me.nav['App_view_areas_FloorPlan'].renderZones();
				me.getPatientsInPoolArea();
			}
		});
	},

	onFacilityComboLoad:function(store, records){
		var rec = store.findRecord('option_value', this.user.facility);
		this.FacilityCmb.setValue(rec);
		this.setWindowTitle(rec.data.option_name)
	},

	setWindowTitle:function(facility){
		window.document.title = 'GaiaEHR :: ' + facility;
	},

    /**
     * Show the medical window dialog.
     */
    onMedicalWin: function(action){
        this.MedicalWindow.show();
        this.MedicalWindow.cardSwitch(action);
    },

    /**
     * Show the Charts window dialog.
     */
    onChartsWin: function(){
        this.ChartsWindow.show();
    },

    onWebCamComplete: function(msg){
        var panel = this.getActivePanel();
        if(panel.id == 'panelSummary'){
            panel.demographics.completePhotoId();
        }
        this.msg('Sweet!', _('patient_image_saved'));
    },

	onPatientLog: function(){
        if(this.patientArrivalLog){
            this.patientArrivalLog.show();
        }else{
            this.patientArrivalLog = Ext.create('App.view.patient.windows.ArrivalLog').show();
        }
    },

    /**
     * Show the Payment Entry window dialog.
     */
    onPaymentEntryWindow: function(){
        this.PaymentEntryWindow.show();
    },

    /**
     * Show the new patient form panel.
     */
    newPatient: function(){
        this.nav.navigateTo('App.view.patient.NewPatient');
    },

    /**
     * EMERGENCY STUFF
     */
	createEmergency: function(){
        var me = this,
	        emergency;

        Ext.Msg.show({
            title: _('wait') + '!!!',
            msg: _('are_you_sure_you_want_to_create_a_new') + ' <span style="color: red">"' + _('emergency') + '"</span>?',
            buttons: Ext.Msg.YESNO,
            icon: Ext.Msg.WARNING,
            fn: function(btn){
                if(btn == 'yes'){
                    Emergency.createNewEmergency(function(provider, response){
                        emergency = response.result.emergency;
                        if(response.result.success){

                            me.setPatient(emergency.pid, emergency.eid, null, function(){
                                me.openEncounter(emergency.eid);
                            });
                            me.msg('Sweet!', emergency.name + ' ' + _('created'))
                        }
                    });
                }
            }
        });
    },

	onEmergencyAccessClick:function(){
		var me = this;

		Ext.Msg.show({
			title:_('wait'),
			msg: _('emergency_access_question') + '<br>' + _('emergency_access_disclaimer'),
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn: function(btn){
				if(btn == 'yes') me.doEmergencyAccess();
			}
		});
	},

	doEmergencyAccess:function(){
		ACL.emergencyAccess(app.user.id, function(success){
			if(success){
				window.location = './';
				return;
			}
			Ext.Msg.alert(_('oops'), _('emergency_access_error'));
		});
	},

	/**
	 * Show the Create New Encounter panel.
	 */
    createNewEncounter: function(){
        var me = this;

        if(a('access_encounters') && a('add_encounters')){
            me.newEncounterWindow.show();
        }else{
            me.accessDenied();
        }
    },

    updateEncounter: function(record){
        var me = this;

        if(a('access_encounters') && a('edit_encounters')){
	        me.newEncounterWindow.loadRecord(record);
            me.newEncounterWindow.show();
        } else{
            me.accessDenied();
        }
    },

	openPatientSummary: function(){
        var me = this,
	        cls = me.nav.getNavRefByClass('App.view.patient.Summary'),
	        panel =  me.nav[cls];
		if(panel && panel == me.nav.activePanel) panel.loadPatient();
        me.nav.navigateTo('App.view.patient.Summary');
    },

	openDashboard: function(){
        var me = this,
	        cls = me.nav.getNavRefByClass('App.view.dashboard.Dashboard'),
	        panel =  me.nav[cls];
		if(panel && panel == me.nav.activePanel) panel.loadPatient();
        me.nav.navigateTo('App.view.dashboard.Dashboard');
    },

	openCalendar: function(){
        var me = this,
	        cls = me.nav.getNavRefByClass('Modules.appointments.view.Calendar'),
	        panel =  me.nav[cls];
		//if(panel && panel == me.nav.activePanel) panel.loadPatient();
        me.nav.navigateTo('Modules.appointments.view.Calendar');
    },

	stowPatientRecord: function(){
        this.unsetPatient(null, true);
        this.nav.navigateTo('App.view.dashboard.Dashboard');
    },

	openEncounter: function(eid){
        var me = this;
        if(a('access_encounters')){
            app.patient.eid = eid;
            me.nav.navigateTo('App.view.patient.Encounter', function(success){
                if(success){
	                Ext.Function.defer(function() {
		                me.nav.getPanelByCls('App.view.patient.Encounter').openEncounter(eid);
	                }, 100);
                }
            });
        }else{
            me.accessDenied();
        }
    },

	checkOutPatient: function(eid){
		var me = this;
        this.nav.navigateTo('App.view.patient.VisitCheckout', function(success){
	        if(success){
		        Ext.Function.defer(function() {
			        me.nav.getPanelByCls('App.view.patient.VisitCheckout').setVisitPanel();
		        }, 100);
	        }
        });
    },

	openPatientVisits: function(){
        this.nav.navigateTo('App.view.patient.Visits');
    },

	goToPoolAreas: function(){
        this.nav.navigateTo('App.view.areas.PatientPoolAreas');
    },

	goToFloorPlans: function(){
        this.nav.navigateTo('App.view.areas.FloorPlan');
    },

    /**
     * Function to get the current active panel.
     * NOTE: This may be used on all the application.
     */
    getActivePanel: function(){
        return this.MainPanel.getLayout().getActiveItem();
    },

    liveSearchSelect: function(combo, selection){
        var me = this,
	        post = selection[0];

        if(post){
            me.setPatient(post.get('pid'), null, null, function(){
	            combo.reset();
                me.openPatientSummary();
            });
        }
    },

	setEncounterClose:function(close){
		this.patient.encounterIsClose = close;
		var buttons = Ext.ComponentQuery.query('#encounterRecordAdd, button[action=encounterRecordAdd]');
		for(var i=0; i < buttons.length; i++){
			buttons[i].setDisabled(close || app.patient.eid == null);
		}
	},

    setPatient: function(pid, eid, site, callback){
        var me = this;
	    me.unsetPatient(null, true);

        Patient.getPatientSetDataByPid({ pid:pid, prevPid:me.patient.pid, site:site }, function(provider, response){
            var data = response.result,
                msg1,
                msg2;

            if(data.readOnly){

                msg1 = data.user + ' ' + _('is_currently_working_with') + ' "' +
                data.patient.name + '" ' + _('in') + ' "' + data.area + '" ' + _('area') +
                '.<br>' + _('override_read_mode_will_remove_the_patient_from_previous_user') + '.<br>' +
                _('do_you_would_like_to_override_read_mode');

                msg2 = data.user + ' ' + _('is_currently_working_with') + ' "' + data.patient.name + '" ' + _('in') +
                ' "' + data.area + '" ' + _('area') + '.<br>';

	            Ext.Msg.show({
                        title: _('wait') + '!!!',
                        msg: data.overrideReadOnly ? msg1 : msg2,
                        buttons: data.overrideReadOnly ? Ext.Msg.YESNO : Ext.Msg.OK,
                        icon: Ext.MessageBox.WARNING,
                        fn: function(btn){
                            continueSettingPatient(btn != 'yes');
                        }
                    });
            }else{
                continueSettingPatient(false);
            }

            function continueSettingPatient(readOnly){
                me.patient = {
                    pid: data.patient.pid,
                    pubpid: data.patient.pubpid,
                    name: data.patient.name,
                    pic: data.patient.pic,
                    sex: data.patient.sex,
	                sexSymbol: data.patient.sex == 'F' ? '&#9792' : '&#9794',
                    dob: Ext.Date.parse(data.patient.dob, "Y-m-d H:i:s"),
                    age: data.patient.age,
                    eid: eid,
                    priority: data.patient.priority,
                    readOnly: readOnly,
                    rating: data.patient.rating,
	                record: Ext.create('App.model.patient.Patient', data.patient.record)
                };

                // fire global event
                me.fireEvent('patientset', me.patient);

                var panels = me.MainPanel.items.items;
                for(var i=0; i<panels.length; i++) if(panels[i].pageRankingDiv) panels[i].pageRankingDiv.setValue(me.patient.rating);
                me.patientButtonSet(me.patient);
                if(me.patientSummaryBtn) me.patientSummaryBtn.enable();
                if(me.patientOpenVisitsBtn) me.patientOpenVisitsBtn.enable();
                if(me.patientCreateEncounterBtn) me.patientCreateEncounterBtn.enable();
                if(me.patientCloseCurrEncounterBtn) me.patientCloseCurrEncounterBtn.enable();
//                if(me.patientChargeBtn) me.patientChargeBtn.enable();
                if(me.patientCheckOutBtn) me.patientCheckOutBtn.enable();
                if(typeof callback == 'function') callback(me.patient);
            }

        });
    },

    unsetPatient: function(callback, sendRequest){
        var me = this;
	    if(sendRequest) Patient.unsetPatient(me.patient.pid);
	    me.currEncounterId = null;

	    if(me.patient.record) delete me.patient.record;

	    me.patient = {
		    pid: null,
		    pubpid: null,
		    name: null,
		    pic: null,
		    sex: null,
		    sexSymbol: null,
		    dob: null,
		    age: null,
		    eid: null,
		    priority: null,
		    readOnly: false,
		    rating: null,
		    record: null
	    };

	    me.patientButtonRemoveCls();
	    if(typeof callback == 'function'){
		    callback(true);
	    }else{
			// fire global event
		    me.fireEvent('patientunset');

		    var panels = me.MainPanel.items.items;
		    for(var i=0; i<panels.length; i++){
			    if(panels[i].pageRankingDiv) panels[i].pageRankingDiv.setValue(0);
		    }

		    if(me.patientCreateEncounterBtn) me.patientCreateEncounterBtn.disable();
		    if(me.patientSummaryBtn) me.patientSummaryBtn.disable();
		    if(me.patientOpenVisitsBtn) me.patientOpenVisitsBtn.disable();
		    if(me.patientCloseCurrEncounterBtn) me.patientCloseCurrEncounterBtn.disable();
		    if(me.patientChargeBtn) me.patientChargeBtn.disable();
		    if(me.patientCheckOutBtn) me.patientCheckOutBtn.disable();
		    me.patientButtonSet();
	    }
    },

    patientButtonSet: function(data){
        var me = this,
            patient = data || {},
	        displayPid = (eval(g('display_pubpid')) ? patient.pubpid : patient.pid);

	    if(displayPid == null || displayPid == ''){
		    displayPid = patient.pid;
	    }

        me.patientBtn.update({
            displayPid: displayPid || 'record number',
            pid: patient.pid,
	        pic: patient.pic || me.patientImage,
            name: patient.name || _('no_patient_selected')
        });

	    me.patientButtonRemoveCls();
        if(patient.priority) me.patientBtn.addCls(data.priority);
        me.patientBtn.setDisabled(!patient.pid);
    },

    patientButtonRemoveCls: function(){
        var me = this;
        me.patientBtn.removeCls('Minimal');
        me.patientBtn.removeCls('Delayed');
        me.patientBtn.removeCls('Immediate');
        me.patientBtn.removeCls('Expectant');
        me.patientBtn.removeCls('Deceased');
    },

    patientBtnTpl: function(){
        return Ext.create('Ext.XTemplate',
            '<div class="patient_btn  {priority}">',
            '   <div class="patient_btn_img">' +
            '       <img src="{pic}" width="50" height="50">' +
            '   </div>',
            '   <div class="patient_btn_info">',
            '       <div class="patient_btn_name">{name}</div>',
            '       <div class="patient_btn_record">( {displayPid} )</div>',
            '   </div>',
            '</div>');
    },

    patientBtnRender: function(btn){
        this.patientButtonSet();
        this.initializePatientPoolDragZone(btn)
    },

    getPatientsInPoolArea: function(){
        var me = this,
	        poolArea = me.patientPoolArea,
	        height = 35;

	    this.patientPoolStore.load({
            extraPrams:{ uid:me.user.id },
            callback: function(records){

	            if(records.length >= 1){
                    for(var i = 0; i < records.length; i++){
                        height = height + 45;
                    }
                }else{
                    height = 25;
                }

                if(me.navColumn.collapsed === false && !me.navColumn.isCollapsingOrExpanding){
                    height = (height > 300) ? 300 : height;
                    poolArea.down('dataview').refresh();
                    poolArea.setHeight(height);
                }
            }
        });

	    if(me.nav['App_view_areas_PatientPoolAreas'].isVisible()) me.nav['App_view_areas_PatientPoolAreas'].reloadStores();
    },

    initializePatientPoolDragZone: function(panel){
        panel.dragZone = Ext.create('Ext.dd.DragZone', panel.getEl(), {
            ddGroup: 'patientPoolAreas',
            getDragData: function(){

	            if(app.patient.pid){
                    var sourceEl = panel.getEl().dom,
	                    msgDiv, msg;

                    if(sourceEl){
	                    msgDiv = document.createElement('div');
	                    msgDiv.id = Ext.id();
	                    msgDiv.innerHTML = _('drag_patient_to_new_area');

	                    return panel.dragData = {
                            copy: true,
                            sourceEl: sourceEl,
                            repairXY: Ext.fly(sourceEl).getXY(),
                            ddel: msgDiv,
                            records: [ panel.data ],
                            patient: true
                        };
                    }
                }
            },

            getRepairXY: function(){
//                app.nav.goBack();
                return this.dragData.repairXY;
            },

	        onBeforeDrag:function(){
		        app.nav.navigateTo('App.view.areas.PatientPoolAreas');
		        return true;
            }
        });
    },

	onEncounterDragZoneRender:function(panel){
		this.initializeOpenEncounterDragZone(panel);

		if(this.enablePoolAreaFadeInOut){
			panel.el.setStyle({
				opacity: 0.1
			});

			panel.el.on('mouseenter', function(event, el){
				Ext.create('Ext.fx.Animator', {
					target: el,
					duration: 200,
					keyframes: {
						0: { opacity: 0.1 },
						100: { opacity: 1 }
					}
				});
			});

			panel.el.on('mouseleave', function(event, el){
				Ext.create('Ext.fx.Animator', {
					target: el,
					duration: 200,
					keyframes: {
						0: { opacity: 1 },
						100: { opacity: 0.1 }
					}
				});
			});
		}
	},

    /**
     *
     * @param panel
     */
    initializeOpenEncounterDragZone: function(panel){
        panel.dragZone = Ext.create('Ext.dd.DragZone', panel.getEl(), {
            ddGroup: 'patient',
            newGroupReset: true,
            b4MouseDown: function(e){
                if(this.newGroupReset){
                    var sourceEl = e.getTarget(panel.itemSelector, 10), patientData = panel.getRecord(sourceEl).data;
                    this.removeFromGroup(this.ddGroup);
//                    say('drag record:');
//                    say(patientData);
                    if(patientData.floorPlanId != 0 && patientData.patientZoneId == 0){
                        app.nav.navigateTo('App.view.areas.FloorPlan');
                        this.ddGroup = 'patientPoolAreas';
                    }else{
                        this.ddGroup = 'patient';
                        app.MainPanel.el.mask(_('drop_here_to_open') + ' <strong>"' + panel.getRecord(sourceEl).data.name + '"</strong> ' + _('current_encounter'));
                    }
                    this.addToGroup(this.ddGroup);
                    this.newGroupReset = false;
                }
                this.autoOffset(e.getPageX(), e.getPageY());
            },
            endDrag: function(e){
                this.newGroupReset = true;
            },
            getDragData: function(e){
                var sourceEl = e.getTarget(panel.itemSelector, 10),
	                d,
	                patientData = panel.getRecord(sourceEl).data;

                if(sourceEl){
                    d = sourceEl.cloneNode(true);
                    d.id = Ext.id();
                    return panel.dragData = {
                        sourceEl: sourceEl,
                        repairXY: Ext.fly(sourceEl).getXY(),
                        ddel: d,
                        patientData: patientData
                    };
                }
                return false;
            },
            getRepairXY: function(){
                app.MainPanel.el.unmask();
                this.newGroupReset = true;
                return this.dragData.repairXY;
            }
        });
    },

    onDocumentView: function(id, type, site){
	    app.getController('DocumentViewer').doDocumentView(id, type, site);
    },

    /**
     *
     * @param panel
     */
    initializeOpenEncounterDropZone: function(panel){
        var me = this;
        panel.dropZone = Ext.create('Ext.dd.DropZone', panel.getEl(), {
            ddGroup: 'patient',

            notifyOver: function(dd, e, data){
                return Ext.dd.DropZone.prototype.dropAllowed;
            },

            notifyDrop: function(dd, e, data){
                app.MainPanel.el.unmask();

	            if(data.patientData.eid && data.patientData.poolArea == 'Check Out'){
                    //...
	            }else if(data.patientData.eid && a('access_encounters')){
	            }else if(data.patientData.floorPlanId === null || data.patientData.floorPlanId === 0){
	            }

	            me.setPatient(data.patientData.pid, data.patientData.eid, null, function(){
                    // if encounter id is set and pool area is check out....  go to Patient Checkout panel
                    if(data.patientData.eid && data.patientData.poolArea == 'Checkout'){
                        me.checkOutPatient(data.patientData.eid);
                    // if encounter id is set and and user has access to encounter area... go to Encounter panel
                    // and open the encounter
                    }else if(data.patientData.eid && a('access_encounters')){
                        me.openEncounter(data.patientData.eid);
                    // else go to patient summary
                    }else{
                        me.openPatientSummary();
                    }
                });
            }
        });
    },

	/**
     * When the application finishes loading all the GaiaEHR core.
     * Then it will load all the modules.
     */
    appRender: function(){
        this.loadModules();
    },

    /**
     * Load all the modules on the modules folder.
     * This folder will hold modules created by third-party.
     */
    loadModules: function(){
        Modules.getEnabledModules(function(provider, response){
            var modules = response.result;
            for(var i = 0; i < modules.length; i++){
	            try{
		            App.app.getController('Modules.' + modules[i].dir + '.Main');
	            }catch(error){
					app.msg(_('oops'), (_('unable_to_load_module') + ' ' + modules[i].title + '<br>Error: ' +  error), true);
	            }
            }
	        app.doLayout();
        });
    },

    removeAppMask: function(){
        if(Ext.get('mainapp-loading')) Ext.get('mainapp-loading').remove();
        if(Ext.get('mainapp-loading-mask')) Ext.get('mainapp-loading-mask').fadeOut({
            remove: true
        });
    },

    beforeAppRender: function(){
	    var me = this,
		    params = me.nav.getUrlParams();
		if(params[1]){
			me.setPatient(params[1], null, null, function(){
				Ext.Function.defer(function(){
					me.nav.navigateTo('App.view.patient.Summary');
				}, 500);

			});
		}else{
			me.unsetPatient(null, false);
		}
    },

    getCurrPatient: function(){
        return this.patient.pid;
    },

    getApp: function(){
        return this;
    },

    /**
     * Access denied massage.
     */
    accessDenied: function(){
        Ext.Msg.show({
            title: _('oops'),
            msg: _('access_denied'),
            buttons: Ext.Msg.OK,
            icon: Ext.Msg.ERROR
        });
    },

	msg: function(title, format, error, persistent) {
		var msgBgCls = (error === true) ? 'msg-red' : 'msg-green';

		if(typeof error === 'string') msgBgCls = 'msg-' + error;

		this.msgCt = Ext.get('msg-div');
		if(!this.msgCt) this.msgCt = Ext.fly('msg-div');

		var s = Ext.String.format.apply(String, Array.prototype.slice.call(arguments, 1)),
			m = Ext.core.DomHelper.append(this.msgCt, {
				html: '<div class="flyMsg ' + msgBgCls + '"><h3>' + (title || '') + '</h3><p>' + s + '</p></div>'
			}, true);

		this.msgCt.alignTo(document, 't-t');

		// if persitent return the message element without the fade animation
		if (persistent === true) return m;

		m.addCls('fadeded');

		Ext.create('Ext.fx.Animator', {
			target: m,
			duration: error === true ? 8000 : 3000,
			keyframes: {
				0: { opacity: 0 },
				10: { opacity: 1 },
				80: { opacity: 1 },
				100: { opacity: 0, height: 0 }
			},
			listeners: {
				afteranimate: function(anim) {
					anim.target.target.destroy();
				}
			}
		});

		m.on('click', function(){
			m.applyStyles('visibility:hidden; display:none');
		});

		return true;
	},

    alert: function(msg, icon){
        if(icon == 'error'){
            icon = Ext.Msg.ERROR;
        }else if(icon == 'warning'){
            icon = Ext.Msg.WARNING;
        }else if(icon == 'question'){
            icon = Ext.Msg.QUESTION;
        }else{
            icon = Ext.Msg.INFO;
        }

        Ext.Msg.show({
            msg: msg,
            buttons: Ext.Msg.OK,
            icon: icon,
	        maxWidth: 1200,
	        modal: false
        });
    },

	fullname: function(title, fname, name, lname){
		var foo = '';
		if(title){
			foo += title + ' ';
		}
		if(fname){
			foo += fname + ' ';
		}
		if(name){
			foo += name + ' ';
		}
		if(lname){
			foo += lname + ' ';
		}
		return foo;
	}

});
