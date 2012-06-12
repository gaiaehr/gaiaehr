/**
 * Encounter.js
 * Encounter Panel
 *
 * This class renders all the panel used  inside the Encounter Panel
 *
 * v0.1.0
 *
 * Author: Ernesto J. Rodriguez
 *
 * GaiaEHR (Electronic Health Records) 2011
 *
 * This are all the Ext.direct methods used in this class
 * @namespace Encounter.getEncounter
 * @namespace Encounter.createEncounter
 * @namespace Encounter.updateEncounter
 * @namespace Encounter.checkOpenEncounters
 * @namespace Encounter.closeEncounter
 * @namespace Encounter.getVitals
 * @namespace Encounter.addVitals
 * @namespace Encounter.saveReviewOfSystem
 * @namespace Encounter.saveReviewOfSystemsChecks
 * @namespace Encounter.saveSOAP
 * @namespace Encounter.saveSpeechDictation
 * @namespace Encounter.updateReviewOfSystemsById
 * @namespace Encounter.updateReviewOfSystemsChecksById
 * @namespace Encounter.updateSoapById
 * @namespace Encounter.updateDictationById
 * @namespace Encounter.getProgressNoteByEid
 * @namespace User.verifyUserPass
 * @namespace ACL.hasPermission
 */
Ext.define('App.view.patientfile.Encounter', {
    extend:'App.classes.RenderPanel',
    id:'panelEncounter',
    pageTitle:'Encounter',
    pageLayout:'border',
    requires:[
        'App.store.patientfile.Encounter', 'App.store.patientfile.Vitals'
    ],
    initComponent:function () {
        var me = this;

        me.currEncounterStartDate = null;

        me.timerTask = {
            scope:me,
            run:function () {
                me.encounterTimer();
            },
            interval:1000 //1 second
        };
        me.encounterStore = Ext.create('App.store.patientfile.Encounter', {
            listeners:{
                scope:me,
                datachanged:me.updateProgressNote
            }
        });

        me.encounterEventHistoryStore = Ext.create('App.store.patientfile.EncounterEventHistory');



        /**
         * New Encounter Panel this panel is located hidden at
         * the top of the Visit panel and will slide down if
         * the "New Encounter" button is pressed.
         */
        me.newEncounterWindow = Ext.create('Ext.window.Window', {
            title      : 'New Encounter Form',
            closeAction: 'hide',
            modal      : true,
            closable   : false,
            width:660,
            items  : [
                {
                    xtype:'form',
                    border:false,
                    bodyPadding:'10 10 0 10'
                }
            ],
            buttons:[
                {
                    text:'Create Encounter',
                    action:'encounter',
                    scope:me,
                    handler:me.onSave
                },
                {
                    text:'Cancel',
                    handler:me.cancelNewEnc

                }
            ]
        });
	    me.EncounterOrdersStore = Ext.create('App.store.patientfile.EncounterCPTsICDs');
	    me.patientDocumentsStore = Ext.create('App.store.patientfile.PatientDocuments');
        me.checkoutWindow = Ext.create('Ext.window.Window', {
            title:'Checkout and Signing',
            closeAction:'hide',
            modal:true,
            closable:false,
            layout:'border',
            width:1000,
            height:660,
            bodyPadding:5,
            items:[
                {
                    xtype:'grid',
                    title:'Services / Diagnostics',
                    region:'center',
	                store: me.EncounterOrdersStore,
                    columns:[
                        {
                            header:'Code',
                            width:60,
                            dataIndex:'code'
                        },
                        {
                            header:'Description',
                            flex:1,
                            dataIndex:'code_text'
                        },
                        {
                            header:'Type',
                            flex:1,
                            dataIndex:'type'
                        }
                    ]
                },
                {
                    xtype:'grid',
                    title:'Documents',
                    region:'east',
	                store: me.patientDocumentsStore,
                    split:true,
                    width:485,
                    columns:[
	                    {
		                    xtype: 'actioncolumn',
		                    width:26,
		                    items: [
			                    {
				                    icon: 'ui_icons/preview.png',
				                    tooltip: 'View Document',
				                    handler: me.onDocumentView,
				                    getClass:function(){
					                    return 'x-grid-icon-padding';
				                    }
			                    }
		                    ]
	                    },
                        {
                            header:'Type',
                            flex:1,
                            dataIndex:'docType'
                        }
                    ]
                },
                {
                    xtype:'form',
                    title:'Additional Info',
                    region:'south',
                    split:true,
                    height:227,
	                layout:'column',
	                defaults:{
                        xtype:'fieldset',
                        padding:8
                    },
                    items:[
                        {
	                        xtype:'fieldcontainer',
	                        columnWidth:.5,
	                        defaults:{
                                xtype:'fieldset',
                                padding:8
                            },
	                        items:[
		                        {
		                            title:'Meaningful Use Measures',
		                            margin:'5 1 5 5',
		                            items:[
		                                {
		                                    xtype:'checkboxgroup',
		                                    defaults:{
		                                        xtype:'checkboxfield'
		                                    },
		                                    items:[
		                                        {
		                                            boxLabel:'Clinical Summary Provided'
		                                        },
		                                        {
		                                            boxLabel:'Elegibility Confirmed'
		                                        }
		                                    ]
		                                },
		                                {
		                                    xtype:'checkboxgroup',
		                                    defaults:{
		                                        xtype:'checkboxfield'
		                                    },
		                                    items:[
		                                        {
		                                            boxLabel:'Medical Reconciliation'
		                                        },
		                                        {
		                                            boxLabel:'Push to Exchange'
		                                        }
		                                    ]
		                                }
		                            ]
		                        },
		                        {
		                            title:'Follow Up',
			                        margin:'5 1 5 5',
		                            //height:90,
		                            defaults:{
		                                anchor:'100%'
		                            },
		                            items:[
		                                {
		                                    xtype:'textfield',
		                                    fieldLabel:'Time'
		                                },
		                                {
		                                    fieldLabel:'Facility',
		                                    xtype:'mitos.facilitiescombo'
		                                }
		                            ]
		                        }
	                        ]
                        },
                        {
	                        xtype:'fieldcontainer',
	                        columnWidth:.5,
	                        defaults:{
                                xtype:'fieldset',
                                padding:8
                            },
	                        items:[
		                        {
		                            xtype:'fieldset',
		                            margin:5,
		                            padding:8,
			                        columnWidth:.5,
			                        height:96,
		                            title:'Patient Notes and Reminders',
		                            items:[
		                                {
		                                    xtype:'textfield',
		                                    name:'reminder',
		                                    fieldLabel:'Reminder',
		                                    anchor:'100%'
		                                },
		                                {
		                                    xtype:'textfield',
		                                    grow:true,
		                                    name:'note',
		                                    fieldLabel:'Note',
		                                    anchor:'100%'
		                                }
		                            ]
		                        },
		                        {
			                        xtype:'fieldset',
                                    margin:5,
                                    padding:8,
                                    columnWidth:.5,
			                        height:88,
                                    title:'Alert Area',
			                        html:'<span style="color:green">Sweet! No Alerts Found</span>'
		                        }
                            ]
                        }
                    ]
                }
            ],
            buttons:[
                {
                    text:'Co-Sign',
                    action:'encounter',
                    scope:me,
                    handler:me.coSignEncounter
                },
                {
                    text:'Sign',
                    action:'encounter',
                    scope:me,
                    handler:me.signEncounter
                },
                {
                    text:'Cancel',
                    handler:me.cancelCheckout

                }
            ],
	        listeners:{
		        scope:me,
		        show:function(){
			        me.EncounterOrdersStore.load({params: {eid: app.currEncounterId}});
			        me.patientDocumentsStore.load({params: {eid: app.currEncounterId}});
		        }

	        }
	          
        });


        /**
         * Tap Panel panels and forms
         */
        me.MiscBillingOptionsPanel = Ext.create('Ext.form.Panel', {
            autoScroll:true,
            title:'Misc. Billing Options HCFA',
            html:'<h1>Misc. Billing Options HCFA form placeholder!</h1>'
        });

        me.CurrentProceduralTerminology = Ext.create('App.view.patientfile.encounter.CurrentProceduralTerminology',{
            title:'Current Procedural Terminology'
        });


        me.EncounterEventHistory = Ext.create('App.classes.grid.EventHistory', {
            bodyStyle:0,
            title:'Encounter History',
            store:me.encounterEventHistoryStore
        });


        me.reviewSysPanel = Ext.create('Ext.form.Panel', {
            autoScroll:true,
            action:'encounter',
            title:'Review of Systems',
            fieldDefaults:{ msgTarget:'side' },
            dockedItems:{
                xtype:'toolbar',
                dock:'top',
                items:[
                    {
                        text:'Save',
                        iconCls:'save',
                        action:'reviewOfSystems',
                        scope:me,
                        handler:me.onSave
                    }
                ]
            }
        });
        me.reviewSysCkPanel = Ext.create('Ext.form.Panel', {
            autoScroll:true,
            action:'encounter',
            title:'Review of Systems Checks',
            fieldDefaults:{ msgTarget:'side' },
            dockedItems:{
                xtype:'toolbar',
                dock:'top',
                items:[
                    {
                        text:'Save',
                        iconCls:'save',
                        action:'reviewOfSystemsChecks',
                        scope:me,
                        handler:me.onSave
                    }
                ]
            }
        });
        me.soapPanel = Ext.create('Ext.form.Panel', {
            autoScroll:true,
            title:'SOAP',
            action:'encounter',
            fieldDefaults:{ msgTarget:'side' },
            dockedItems:{
                xtype:'toolbar',
                dock:'top',
                items:[
                    {
                        text:'Save',
                        iconCls:'save',
                        action:'soap',
                        scope:me,
                        handler:me.onSave
                    }
                ]
            }
        });
        me.speechDicPanel = Ext.create('Ext.form.Panel', {
            autoScroll:true,
            title:'Speech Dictation',
            action:'encounter',
            fieldDefaults:{ msgTarget:'side' },
            dockedItems:{
                xtype:'toolbar',
                dock:'top',
                items:[
                    {
                        text:'Save',
                        iconCls:'save',
                        action:'speechDictation',
                        scope:me,
                        handler:me.onSave
                    }
                ]
            }
        });
        me.vitalsPanel = Ext.create('Ext.panel.Panel', {
            title:'Vitals',
            action:'encounter',
            cls:'vitals-panel',
            bodyPadding:'5 10',
	        overflowY: 'auto',
            layout:{
                type:'hbox',
                stretch:true
            },
            items:[
                {
                    xtype:'form',
                    width:313,
	                margin:0,
                    border:false,
                    layout:'anchor',
                    fieldDefaults:{ msgTarget:'side', labelAlign:'right' },
                    buttons:[
                        {
                            text:'Reset',
                            width:40,
                            scope:me,
                            handler:me.resetVitalsForm
                        },
                        {
                            text:'Sign',
                            width:40,
                            scope:me,
                            handler:me.onVitalsSign
                        },
                        {
                            text:'Save',
                            action:'vitals',
                            width:40,
                            scope:me,
                            handler:me.onSave
                        }
                    ]
                },
                {
                    xtype:'vitalsdataview',
	                flex:1,
	                autoScroll:true,
	                listeners:{
		                scope:me,
		                itemdblclick:me.onVitalsClick
	                }
                }
            ],
            dockedItems:{
                xtype:'toolbar',
                dock:'top',
                items:[
                    '->',
                    {
                        text:'Vector Charts',
                        iconCls:'icoChart',
                        scope:me,
                        handler:me.onChartWindowShow
                    }
                ]
            }

        });


        /**
         * Encounter panel
         */
        me.centerPanel = Ext.create('Ext.tab.Panel', {
            region:'center',
            margin:'1 0 0 0',
            bodyPadding:5,
            items:[
                {
                    xtype:'tabpanel',
                    title:'Encounter',
                    itemId:'encounter',
                    plain:true,
                    activeItem:0,
                    defaults:{
                        bodyStyle:'padding:15px',
                        bodyBorder:true,
                        layout:'fit'
                    },
                    items:[
                        me.vitalsPanel, me.reviewSysPanel, me.reviewSysCkPanel, me.soapPanel, me.speechDicPanel
                    ]
                },
                {
                    xtype:'tabpanel',
                    title:'Administrative',
                    itemId:'administrative',
                    plain:true,
                    activeItem:0,
                    defaults:{
                        bodyStyle:'padding:15px',
                        bodyBorder:true,
                        layout:'fit'
                    },
                    items:[
                        me.MiscBillingOptionsPanel, me.CurrentProceduralTerminology, me.EncounterEventHistory
                    ]
                }
            ],
            listeners:{
                render:function () {
                    this.items.each(function (i) {
                        i.tab.on('click', function () {
                            me.onTapPanelChange(this);
                        });
                    });
                }
            }
        });

        /**
         * Progress Note
         */
        me.progressNote = Ext.create('App.view.patientfile.ProgressNote', {
            title:'Encounter Progress Note',
            region:'east',
            margin:'0 0 0 2',
            bodyStyle:'padding:15px',
            width:500,
            collapsible:true,
            animCollapse:true,
            collapsed:true,
            listeners:{
                scope:this,
                collapse:me.progressNoteCollapseExpand,
                expand:me.progressNoteCollapseExpand
            },
            tbar:[
                {
                    text:'View (CCD)',
                    tooltip:'View (Continuity of Care Document)',
                    handler:function () {
                        // refresh logic
                    }
                },
                '-',
                {
                    text:'Print (CCD)',
                    tooltip:'Print (Continuity of Care Document)',
                    handler:function () {
                        // refresh log

                    }
                },
                '->',
                {
                    text:'Export (CCD)',
                    tooltip:'Export (Continuity of Care Document)',
                    handler:function () {
                        // refresh log

                    }
                }
            ]
        });

        me.pageBody = [ me.centerPanel, me.progressNote ];

        me.listeners = {
            beforerender:me.beforePanelRender
        };
        me.callParent(arguments);

        me.down('panel').addDocked({
            xtype:'toolbar',
            dock:'top',
            defaults:{
                scope:me,
                handler:me.onMedicalWin
            },
            items:[
                {
                    text:'Immunizations ',
                    action:'immunization'
                },
                '-',
                {
                    text:'Allergies ',
                    action:'allergies'
                },
                '-',
                {
                    text:'Active Problems ',
                    action:'issues'
                },
                '-',
                {
                    text:'Surgery ',
                    action:'surgery'
                },
                '-',
                {
                    text:'Dental ',
                    action:'dental'
                },'-',
                {
                    text:'Medications ',
                    action:'medications'
                },'-',
                {
                    text:'Laboratories ',
                    action:'laboratories'
                },'-',
	            {
		            text:'New Lab Order',
		            action:'lab',
		            scope:me,
		            handler:me.newDoc
	            },
	            '-',
	            {
		            text:'New X-Ray Order',
		            action:'xRay',
		            scope:me,
		            handler:me.newDoc
	            },
	            '-',
	            {
		            text:'New Prescription',
		            action:'prescription',
		            scope:me,
		            handler:me.newDoc
	            },
	            '-',
	            {
		            text:'New Doctors Note',
		            action:'notes',
		            scope:me,
		            handler:me.newDoc
	            },
                '->',
                {
                    text:'Checkout',
                    handler:me.onCheckout
                }
            ]
        });

    },
	newDoc:function(btn){
		app.onNewDocumentsWin(btn.action)
	},
    /**
     * opens the Medical window
     * @param btn
     */
    onMedicalWin:function (btn) {
        app.onMedicalWin(btn);
    },
    /**
     * opens the Chart window
     */
    onChartWindowShow:function () {
        app.onChartsWin();
    },
    /**
     * Checks for opened encounters, if open encounters are
     * found alert the user, if not then open the
     * new encounter window
     */
    newEncounter:function () {
        var me = this, form, model;
        Encounter.checkOpenEncounters(function (provider, response) {
            /** @namespace response.result.encounter */
            if (response.result.encounter) {
                Ext.Msg.show({
                    title:'Oops! Open Encounters Found...',
                    msg:'Do you want to <strong>continue creating the New Encounters?</strong><br>"Click No to review Encounter History"',
                    buttons:Ext.Msg.YESNO,
                    icon:Ext.Msg.QUESTION,
                    fn:function (btn) {
                        if (btn == 'yes') {
                            form = me.newEncounterWindow.down('form');
                            form.getForm().reset();
                            model = Ext.ModelManager.getModel('App.model.patientfile.Encounter');
                            model = Ext.ModelManager.create({
                                start_date:new Date()
                            }, model);
                            form.getForm().loadRecord(model);
                            me.newEncounterWindow.show();
                        } else {
                            app.openPatientVisits();
                        }
                    }
                });
            } else {
                form = me.newEncounterWindow.down('form');
                form.getForm().reset();
                model = Ext.ModelManager.getModel('App.model.patientfile.Encounter');
                model = Ext.ModelManager.create({
                    start_date:new Date()
                }, model);
                form.getForm().loadRecord(model);
                me.newEncounterWindow.show();
            }

        });
    },

    onCheckout:function () {
        var me = this, win = me.checkoutWindow, patient = me.getCurrPatient();
        win.setTitle(patient.name + ' - ' + Ext.Date.format(me.currEncounterStartDate, 'F j, Y, g:i:s a') + ' (Checkout)');
        win.show();
    },

    coSignEncounter:function () {

    },

    signEncounter:function () {


	    this.closeEncounter();
	    this.checkoutWindow.close();
    },

    cancelCheckout:function (btn) {
        var win = btn.up('window'), form = win.down('form').getForm();
        win.close();
        form.reset();
    },


    /**
     * Sends the data to the server to be saved.
     * This function needs the button action to determine
     * which form  to save.
     * @param SaveBtn
     */
    onSave:function (SaveBtn) {
        var me = this, panel = me.centerPanel.getActiveTab().getActiveTab(), form;

        if (SaveBtn.action == "encounter") {
            form = me.newEncounterWindow.down('form').getForm();
        } else if (SaveBtn.action == 'vitals') {
            form = panel.down('form').getForm();
        } else {
            form = panel.getForm();
        }

        if (form.isValid()) {
            var values = form.getValues(), store, record, storeIndex;

            if (SaveBtn.action == 'encounter') {
                ACL.hasPermission('add_encounters', function (provider, response) {
                    if (response.result) {
                        store = me.encounterStore;
                        record = form.getRecord();
                        storeIndex = store.indexOf(record);

                        if (storeIndex == -1) {
                            store.add(values);
                            record = store.last();
                        } else {
                            record.set(values);
                        }
                        record.save({
                            callback:function (store) {
                                me.openEncounter(store.data.eid);
                                SaveBtn.up('window').close();
                            }
                        });
                    } else {
                        SaveBtn.up('window').close();
                        app.accessDenied();
                    }
                });
            } else if (SaveBtn.action == 'vitals') {
                ACL.hasPermission('add_vitals', function (provider, response) {
                    if (response.result) {
                        //noinspection JSUnresolvedFunction
                        store = me.encounterStore.getAt(0).vitals();
                        record = form.getRecord();
                        values = me.addDefaultData(values);
                        storeIndex = store.indexOf(record);

                        if(storeIndex == -1) {
                            store.insert(0,values);
                        } else {
                            record.set(values);
                        }
                        store.sync({
	                        scope:me,
	                        success:function(){
		                        me.msg('Sweet!', 'Vitals Saved');
		                        me.updateProgressNote();
		                        me.vitalsPanel.down('vitalsdataview').refresh();
		                        me.resetVitalsForm();
	                        }
                        });
                    } else {
                        app.accessDenied();
                    }
                });
            } else {
                ACL.hasPermission('edit_encounters', function (provider, response) {
                    if (response.result) {
                        store = me.encounterStore;

                        if (SaveBtn.action == 'reviewOfSystems') {
                            //noinspection JSUnresolvedFunction
                            record = store.getAt(0).reviewofsystems().getAt(0);
                        } else if (SaveBtn.action == 'reviewOfSystemsChecks') {
                            //noinspection JSUnresolvedFunction
                            record = store.getAt(0).reviewofsystemschecks().getAt(0);
                        } else if (SaveBtn.action == 'soap') {
                            //noinspection JSUnresolvedFunction
                            record = store.getAt(0).soap().getAt(0);
                        } else if (SaveBtn.action == 'speechDictation') {
                            //noinspection JSUnresolvedFunction
                            record = store.getAt(0).speechdictation().getAt(0);
                        }
                        values = me.addDefaultData(values);

                        say(form);
                        say(values);
                        say(record);


                        record.set(values);
                        record.save({
                            callback:function () {
                                me.updateProgressNote();
                            }
                        });
                        me.msg('Sweet!', 'Encounter Updated');
                        me.encounterEventHistoryStore.load({params: {eid: app.currEncounterId}});
                    } else {
                        app.accessDenied();
                    }
                });
            }
        }
    },

    onVitalsSign:function(){
        var me = this,
            form = me.vitalsPanel.down('form').getForm(),
            store = me.encounterStore.getAt(0).vitals(),
            record = form.getRecord();

        if (form.isValid()) {
            me.passwordVerificationWin(function (btn, password) {
                if (btn == 'ok') {
                    User.verifyUserPass(password, function (provider, response) {
                        if (response.result) {
                            record.set({auth_uid: user.id});
                            store.sync();
                            form.reset();
                            me.msg('Sweet!', 'Vitals Signed');
                            me.vitalsPanel.down('vitalsdataview').refresh();
                            me.updateProgressNote();
                            me.resetVitalsForm();
                        } else {
                            Ext.Msg.show({
                                title:'Oops!',
                                msg:'Incorrect password',
                                buttons:Ext.Msg.OKCANCEL,
                                icon:Ext.Msg.ERROR,
                                fn:function (btn) {
                                    if (btn == 'ok') {
                                        me.onVitalsSign();
                                    }
                                }
                            });
                        }
                    });
                }
            });
        }
    },

    /**
     * Takes the form data to be send and adds the default
     * data used by every encounter form. For example
     * pid (Patient ID), eid (Encounter ID), uid (User ID),
     * and date (Current datetime as 00-00-00 00:00:00)
     * @param data
     */
    addDefaultData:function (data) {
        data.pid = app.currPatient.pid;
        data.eid = app.currEncounterId;
        //noinspection JSUnresolvedVariable
        data.uid = user.id;
        data.date = Ext.Date.format(new Date(), 'Y-m-d H:i:s');
        return data;
    },

    /**
     * Cancels the New Encounter process, closing the window
     * and send the user to the Patient Summary panel
     * @param btn
     */
    cancelNewEnc:function (btn) {
        btn.up('window').close();
        app.openPatientSummary();
    },

    /**
     *
     * @param eid
     */
    openEncounter:function (eid) {
        var me = this, vitals = me.vitalsPanel.down('vitalsdataview');

        app.currEncounterId = eid;
        me.encounterStore.getProxy().extraParams.eid = eid;
        me.encounterStore.load({
            scope:me,
            callback:function (record) {
                var data = record[0].data;
                me.currEncounterStartDate = data.start_date;
                if (!data.close_date) {
                    me.startTimer();
                } else {
                    if (me.stopTimer()) {
                        var timer = me.timer(data.start_date, data.close_date), patient = me.getCurrPatient();
                        me.updateTitle(patient.name + ' - ' + Ext.Date.format(me.currEncounterStartDate, 'F j, Y, g:i:s a') + ' (Closed Encounter) <span class="timer">' + timer + '</span>');
                    }
                }
	            me.resetVitalsForm();
                vitals.store = record[0].vitalsStore;
                vitals.refresh();
                //noinspection JSUnresolvedFunction
                me.reviewSysPanel.getForm().loadRecord(record[0].reviewofsystems().getAt(0));
                //noinspection JSUnresolvedFunction
                me.reviewSysCkPanel.getForm().loadRecord(record[0].reviewofsystemschecks().getAt(0));
                //noinspection JSUnresolvedFunction
                me.soapPanel.getForm().loadRecord(record[0].soap().getAt(0));
                //noinspection JSUnresolvedFunction
                me.speechDicPanel.getForm().loadRecord(record[0].speechdictation().getAt(0));

                me.encounterEventHistoryStore.load({params:{eid:eid}});

                me.soapPanel.query('icdsfieldset')[0].loadIcds(record[0].soap().getAt(0).data.icdxCodes);

                me.CurrentProceduralTerminology.encounterCptStoreLoad(record[0].data.pid, eid, function(){
                    me.CurrentProceduralTerminology.setDefaultQRCptCodes();
                });
	            PreventiveCare.activePreventiveCareAlert({pid:record[0].data.pid},function(provider,response){
		            if(response.result.success){

			            app.PreventiveCareWindow.show();

		            }
	            });
            }
        });
    },

    /**
     * Function to close the encounter..
     */
    closeEncounter:function () {
        var me = this;
        me.passwordVerificationWin(function (btn, password) {
            if (btn == 'ok') {
                var params = {
                    eid       : app.currEncounterId,
                    close_date: Ext.Date.format(new Date(), 'Y-m-d H:i:s'),
                    signature : password
                };
                Encounter.closeEncounter(params, function (provider, response) {
                    if (response.result.success) {
                        if (me.stopTimer()) {
                            app.openPatientVisits();
                            me.msg('Sweet!', 'Encounter Closed');
                        }
                    } else {
                        Ext.Msg.show({
                            title:'Oops!',
                            msg:'Incorrect password',
                            buttons:Ext.Msg.OKCANCEL,
                            icon:Ext.Msg.ERROR,
                            fn:function (btn) {
                                if (btn == 'ok') {
                                    me.closeEncounter();
                                }
                            }
                        });
                    }
                });
            }
        });

    },
    /**
     * listen for the progress note panel and runs the
     * doLayout function to re-adjust the dimensions.
     */
    progressNoteCollapseExpand:function () {
        this.centerPanel.doLayout();
    },

    updateProgressNote:function () {
        var me = this;
        Encounter.getProgressNoteByEid(app.currEncounterId, function(provider, response) {
            var data = response.result;
            me.progressNote.tpl.overwrite(me.progressNote.body, data);
        });
    },


    onTapPanelChange:function (panel) {
        if (panel.card.itemId == 'encounter') {
            this.isProgressNoteCollapsed(true);
        } else {
            this.isProgressNoteCollapsed(true);
        }
    },

    isProgressNoteCollapsed:function (ans) {
        ans ? this.progressNote.collapse() : this.progressNote.expand();

    },


	onVitalsClick:function(view, record, e){
		var me = this,
			form = me.vitalsPanel.down('form').getForm();
            form.reset();
        if(!record.data.auth_uid){
            form.loadRecord(record);
        }else{
            Ext.Msg.show({
                title:'Oops!',
                msg: 'This column can not be modified because it has been signed by '+record.data.auth_uid,
                buttons: Ext.Msg.OK,
                icon: Ext.Msg.WARNING,
                animateTarget: e
            });
        }
	},

	resetVitalsForm:function(){
		var form = this.vitalsPanel.down('form').getForm(),
            model = Ext.ModelManager.getModel('App.model.patientfile.Vitals'),
            newModel = Ext.ModelManager.create({}, model);
        form.loadRecord(newModel);
	},



    //***************************************************************************************************//
    //***************************************************************************************************//
    //*********    *****  ******    ****** **************************************************************//
    //*********  *  ****  ****  ***  ***** **************************************************************//
    //*********  **  ***  ***  *****  **** **************************************************************//
    //*********  ***  **  ***  *****  **** **************************************************************//
    //*********  ****  *  ****  ***  ********************************************************************//
    //*********  *****    *****    ******* **************************************************************//
    //***************************************************************************************************//
    //***************************************************************************************************//


    /**
     * Start the timerTask
     */
    startTimer:function () {
        Ext.TaskManager.start(this.timerTask);
        return true;
    },
    /**
     * stops the timerTask
     */
    stopTimer:function () {
        Ext.TaskManager.stop(this.timerTask);
        return true;
    },

    /**
     * This will update the timer every sec
     */
    encounterTimer:function () {
        var me = this;
        var timer = me.timer(me.currEncounterStartDate, new Date()), patient = me.getCurrPatient();
        me.updateTitle(patient.name + ' - ' + Ext.Date.format(me.currEncounterStartDate, 'F j, Y, g:i:s a') + ' (Opened Encounter) <span class="timer">' + timer + '</span>');
    },

    /**
     * This function use the "start time" and "stop time"
     * and gets the time elapsed between the two then
     * returns it as a timer (00:00:00)  or (1 day(s) 00:00:00)
     * if more than 24 hrs
     *
     * @param start
     * @param stop
     */
    timer:function (start, stop) {
        var ms = Ext.Date.getElapsed(start, stop), t, sec = Math.floor(ms / 1000);

        function twoDigit(d) {
            return (d >= 10) ? d : '0' + d;
        }

        var min = Math.floor(sec / 60);
        sec = sec % 60;
        t = twoDigit(sec);

        var hr = Math.floor(min / 60);
        min = min % 60;
        t = twoDigit(min) + ":" + t;

        var day = Math.floor(hr / 24);
        hr = hr % 24;
        t = twoDigit(hr) + ":" + t;

        t = (day == 0 ) ? '<span class="time">' + t + '</span>' : '<span class="day">' + day + ' day(s)</span><span class="time">' + t + '</span>';
        return t;
    },

    /**
     * Convert Celsius to Fahrenheit
     * @param field
     * @param e
     */
    cf:function (field, e) {
        var v = field.getValue(), temp = 9 * v / 5 + 32, res = Ext.util.Format.round(temp, 1);
        if (e.getKey() != e.TAB) {
            field.up('form').getForm().findField('temp_f').setValue(res);
        }

    },
    /**
     * Convert Fahrenheit to Celsius
     * @param field
     * @param e
     */
    fc:function (field, e) {
        var v = field.getValue(), temp = (v - 32) * 5 / 9, res = Ext.util.Format.round(temp, 1);
        if (e.getKey() != e.TAB) {
            field.up('form').getForm().findField('temp_c').setValue(res);
        }
    },

    /**
     * Convert Lbs to Kg
     * @param field
     * @param e
     */
    lbskg:function (field, e) {
        var v = field.getValue(), weight = v / 2.2, res = Ext.util.Format.round(weight, 1);
        if (e.getKey() != e.TAB) {
            field.up('form').getForm().findField('weight_kg').setValue(res);
        }
    },
    /**
     * Convert Kg to Lbs
     * @param field
     * @param e
     */
    kglbs:function (field, e) {
        var v = field.getValue(), weight = v * 2.2, res = Ext.util.Format.round(weight, 1);
        if (e.getKey() != e.TAB) {
            field.up('form').getForm().findField('weight_lbs').setValue(res);
        }
    },
    /**
     * Convert Inches to Centimeter
     * @param field
     * @param e
     */
    incm:function (field, e) {
        var v = field.getValue(), weight = v * 2.54, res = Ext.util.Format.round(weight, 1);
        if (e.getKey() != e.TAB) {
            if (field.name == 'head_circumference_in') {
                field.up('form').getForm().findField('head_circumference_cm').setValue(res);
            } else if (field.name == 'waist_circumference_in') {
                field.up('form').getForm().findField('waist_circumference_cm').setValue(res);
            } else if (field.name == 'height_in') {
                field.up('form').getForm().findField('height_cm').setValue(res);
            }
        }
    },
    /**
     * Convert Centimeter to Inches
     * @param field
     * @param e
     */
    cmin:function (field, e) {
        var v = field.getValue(), weight = v * 0.39, res = Ext.util.Format.round(weight, 1);
        if (e.getKey() != e.TAB) {
            if (field.name == 'head_circumference_cm') {
                field.up('form').getForm().findField('head_circumference_in').setValue(res);
            } else if (field.name == 'waist_circumference_cm') {
                field.up('form').getForm().findField('waist_circumference_in').setValue(res);
            } else if (field.name == 'height_cm') {
                field.up('form').getForm().findField('height_in').setValue(res);
            }
        }
    },

    /**
     * After this panel is render add the forms and listeners for conventions
     */
    beforePanelRender:function () {
        var me = this, form, dafaultFields = function () {
            return [
                {name:'id', type:'int'},
                {name:'pid', type:'int'},
                {name:'eid', type:'int'},
                {name:'uid', type:'int'}
            ]
        };

        /**
         * Get 'Vitals' Form Fields and add listeners to convert values
         */
        this.getFormItems(me.vitalsPanel.down('form'), 'Vitals', function () {
            form = me.vitalsPanel.down('form').getForm();
            form.findField('temp_c').addListener('keyup', me.cf, me);
            form.findField('temp_f').addListener('keyup', me.fc, me);
            form.findField('weight_lbs').addListener('keyup', me.lbskg, me);
            form.findField('weight_kg').addListener('keyup', me.kglbs, me);
            form.findField('height_cm').addListener('keyup', me.cmin, me);
            form.findField('height_in').addListener('keyup', me.incm, me);
            form.findField('head_circumference_cm').addListener('keyup', me.cmin, me);
            form.findField('head_circumference_in').addListener('keyup', me.incm, me);
            form.findField('waist_circumference_cm').addListener('keyup', me.cmin, me);
            form.findField('waist_circumference_in').addListener('keyup', me.incm, me);
            //me.vitalsPanel.doLayout();
        });

        /**
         * Get 'Review of Systems' Form and define the Model using the form fields
         */
        this.getFormItems(me.reviewSysPanel, 'Review of Systems', function () {
            var formFields = me.reviewSysPanel.getForm().getFields(), modelFields = new dafaultFields;

            Ext.each(formFields.items, function (field) {
                modelFields.push({name:field.name, type:'auto'});
            });

            Ext.define('App.model.patientfile.ReviewOfSystems', {
                extend:'Ext.data.Model',
                fields:modelFields,
                proxy:{
                    type:'direct',
                    api:{
                        update:Encounter.updateReviewOfSystemsById
                    }
                },
                belongsTo:{ model:'App.model.patientfile.Encounter', foreignKey:'eid' }
            });
        });
        /**
         * Get 'SOAP' Form and define the Model using the form fields
         */
        this.getFormItems(me.soapPanel, 'SOAP', function () {
            var formFields = me.soapPanel.getForm().getFields(), modelFields = new dafaultFields;


            Ext.each(formFields.items, function (field) {
                modelFields.push({name:field.name, type:'auto'});
            });

            Ext.define('App.model.patientfile.SOAP', {
                extend:'Ext.data.Model',
                fields:modelFields,
                proxy:{
                    type:'direct',
                    api:{
                        update:Encounter.updateSoapById
                    }
                },
                belongsTo:{ model:'App.model.patientfile.Encounter', foreignKey:'eid' }
            });
        });
        /**
         * Get 'Speech Dictation' Form and define the Model using the form fields
         */
        this.getFormItems(me.speechDicPanel, 'Speech Dictation', function () {
            var formFields = me.speechDicPanel.getForm().getFields(), modelFields = new dafaultFields;


            Ext.each(formFields.items, function (field) {
                modelFields.push({name:field.name, type:'auto'});
            });

            Ext.define('App.model.patientfile.SpeechDictation', {
                extend:'Ext.data.Model',
                fields:modelFields,
                proxy:{
                    type:'direct',
                    api:{
                        update:Encounter.updateDictationById
                    }
                },
                belongsTo:{ model:'App.model.patientfile.Encounter', foreignKey:'eid' }
            });
        });
        /**
         * Get 'Review of Systems Check' Form and define the Model using the form fields
         */
        this.getFormItems(me.reviewSysCkPanel, 'Review of Systems Check', function () {
            var formFields = me.reviewSysCkPanel.getForm().getFields(), modelFields = new dafaultFields;


            Ext.each(formFields.items, function (field) {
                modelFields.push({name:field.name, type:'auto'});
            });

            Ext.define('App.model.patientfile.ReviewOfSystemsCheck', {
                extend:'Ext.data.Model',
                fields:modelFields,
                proxy:{
                    type:'direct',
                    api:{
                        update:Encounter.updateReviewOfSystemsChecksById
                    }
                },
                belongsTo:{ model:'App.model.patientfile.Encounter', foreignKey:'eid' }
            });
        });

        this.getFormItems(me.newEncounterWindow.down('form'), 'New Encounter');


    },

	onDocumentView:function(grid, rowIndex){
		var rec = grid.getStore().getAt(rowIndex),
			src = rec.data.url;
		app.onDocumentView(src);
	},
    /**
     * This function is called from MitosAPP.js when
     * this panel is selected in the navigation panel.
     * place inside this function all the functions you want
     * to call every this panel becomes active
     */
    onActive:function (callback) {
        var me = this;
        if (me.checkIfCurrPatient()) {
            var patient = me.getCurrPatient();
            me.updateTitle(patient.name + ' (Visits)');
            callback(true);
        } else {
            callback(false);
            me.currPatientError();
        }
    }
});
