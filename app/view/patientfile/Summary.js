 /**
 * summary.ejs.php
 * Description: Patient Summary
 * v0.0.1
 *
 * Author: Ernesto J Rodriguez
 * Modified: n/a
 *
 * GaiaEHR (Electronic Health Records) 2011
 *
 * @namespace Encounter.getVitals
 */
Ext.define('App.view.patientfile.Summary', {
    extend       : 'App.classes.RenderPanel',
    id           : 'panelSummary',
    pageTitle    : 'Patient Summary',
    pageLayout   : {
        type : 'hbox',
        align: 'stretch'
    },
    pid : null,
    initComponent: function() {
        var me = this;

	    me.demographicsData = null;

        me.vitalsStore = Ext.create('App.store.patientfile.Vitals');


        me.immuCheckListStore = Ext.create('App.store.patientfile.ImmunizationCheck');
        me.patientAllergiesListStore = Ext.create('App.store.patientfile.Allergies');
        me.patientMedicalIssuesStore = Ext.create('App.store.patientfile.MedicalIssues');
        me.patientSurgeryStore = Ext.create('App.store.patientfile.Surgery');
        me.patientDentalStore = Ext.create('App.store.patientfile.Dental');
        me.patientMedicationsStore = Ext.create('App.store.patientfile.Medications');
        me.patientDocumentsStore = Ext.create('App.store.patientfile.PatientDocuments');
        me.patientNotesStore = Ext.create('App.store.patientfile.Notes');
        me.patientRemindersStore = Ext.create('App.store.patientfile.Reminders');

        me.patientsDismissedAlerts = Ext.create('App.store.patientfile.DismissedAlerts');

	    me.encounterEventHistoryStore = Ext.create('App.store.patientfile.Encounters');
	    me.patientAlertsStore = Ext.create('App.store.patientfile.MeaningfulUseAlert');


        me.pageBody = [
            {
                xtype      : 'tabpanel',
                flex       : 1,
                margin     : '3 0 0 0',
                bodyPadding: 0,
                frame      : false,
                border     : false,
                plain      : true,
                itemId     : 'centerPanel',
                items      : [
                    {
                        xtype:'panel',
                        title:'Patient General',
                        autoScroll:true,
                        defaults   : { margin: 5, bodyPadding: 5, collapsible: true, titleCollapse: true },
                        items:[
                            {
                                xtype:'panel',
                                action:'balance',
                                title: 'Billing',
                                html:'Account Balance: '

                            },
                            {
                                xtype : 'form',
                                title : 'Demographics',
                                action: 'demoFormPanel',
                                itemId: 'demoFormPanel',
	                            dockedItems: [
		                            {
		                                xtype: 'toolbar',
		                                dock: 'bottom',
		                                items: [
			                                '->',
			                                {
				                                xtype: 'button',
				                                action:'readOnly',
		                                        text:'Save',
				                                minWidth: 75,
		                                        scope:me,
		                                        handler:me.formSave
		                                    },
		                                    '-',
		                                    {
		                                        xtype: 'button',
		                                        text:'Cancel',
			                                    action:'readOnly',
		                                        minWidth: 75,
		                                        scope:me,
		                                        handler:me.formCancel
		                                    }
		                                ]
		                            },
		                            {
		                                xtype: 'toolbar',
		                                dock: 'top',
		                                items: [
			                                '->',
			                                {
				                                xtype: 'button',
		                                        text:'Save',
				                                action:'readOnly',
				                                minWidth: 75,
		                                        scope:me,
		                                        handler:me.formSave
		                                    },
		                                    '-',
		                                    {
		                                        xtype: 'button',
		                                        text:'Cancel',
			                                    action:'readOnly',
		                                        minWidth: 75,
		                                        scope:me,
		                                        handler:me.formCancel
		                                    }
		                                ]
		                            }
	                            ]
                            },
                            {
                                title      : 'Notes',
                                itemId     : 'notesPanel',
                                xtype      : 'grid',
                                bodyPadding: 0,
                                store      : me.patientNotesStore,
                                columns    : [
                                    {
                                        text     : 'Date',
                                        dataIndex: 'date'
                                    },
                                    {
                                        header   : 'Type',
                                        dataIndex: 'type'
                                    },
                                    {
                                        text     : 'Note',
                                        dataIndex: 'body',
                                        flex     : 1
                                    },
                                    {
                                        text     : 'User',
                                        dataIndex: 'user_name'
                                    }
                                ]

                            },
                            {
                                title      : 'Reminders',
                                itemId     : 'remindersPanel',
                                xtype      : 'grid',
                                bodyPadding: 0,
                                store      : me.patientRemindersStore,
                                columns    : [
                                    {
                                        text     : 'Date',
                                        dataIndex: 'date'
                                    },
                                    {

                                        header   : 'Type',
                                        dataIndex: 'type'
                                    },
                                    {
                                        text     : 'Note',
                                        dataIndex: 'body',
                                        flex     : 1
                                    },
                                    {
                                        text     : 'User',
                                        dataIndex: 'user_name'
                                    }
                                ]

                            }
                        ]
                    },
                    {
                        title     : 'Vitals',
                        autoScroll: true,
                        bodyPadding: 0,
                        items     : {
                            xtype: 'vitalsdataview',
                            store: me.vitalsStore
                        }
                    },
                    {
                        title     : 'History',
                        xtype     :'grid',
	                    store     : me.encounterEventHistoryStore,
                        columns:[
                            {
                                header:'Date',
                                dataIndex:'start_date'
                            },
                            {
                                header:'Event',
                                dataIndex:'brief_description',
                                flex:true
                            },
                            {
                                header:'Visit Category',
                                dataIndex:'visit_category'
                            }
                        ]
                    },
                    {
                        title     : 'Documents',
                        xtype     :'grid',
                        store: me.patientDocumentsStore,
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
                                dataIndex:'docType'
                            },
                            {
	                            xtype:'datecolumn',
                                header:'Date',
                                dataIndex:'date',
                                format : 'Y-m-d'

                            },
                            {
                                header:'Title',
                                dataIndex:'title',
                                flex:true,
                                editor:{
                                            xtype:'textfield',
                                            action:'title'
                                        }
                            }
                        ],

                        plugins: Ext.create('Ext.grid.plugin.RowEditing', {
                        				autoCancel  : true,
                        				errorSummary: false,
                        				clicksToEdit: 2

                        }),

	                    tbar:[
		                    {
			                    xtype     : 'mitos.templatescombo',
			                    fieldLabel: 'Available Documents',
			                    width     : 320,
			                    labelWidth: 145,
			                    margin    : '10 0 0 10'

		                    },
    	                    '-',
		                    {
			                    text:'Create',
			                    scope:me,
			                    handler:me.newDoc
		                    },
		                    '->',
		                    {
			                    text:'Upload Document',
			                    scope:me,
			                    handler:me.uploadADocument
		                    },
		                    {
			                    xtype:'panel',
							    action:'upload',
							    region:'center',
							    items:[
							    me.uploadWin = Ext.create('Ext.window.Window',{
								    draggable :false,
								    closable:false,
								    closeAction:'hide',
								    items:[
									    {
										    xtype:'form',
										    bodyPadding:10,
										    width:400,
										    items:[
											    {
												    xtype: 'filefield',
												    name: 'filePath',
												    buttonText: 'Select a file...',
												    anchor:'100%'
											    }
										    ],
										    api: {
											    submit: DocumentHandler.uploadDocument
										    }
									    }
								    ],
								    buttons:[
									    {
										    text:'Cancel',
										    handler:function(){
											    me.uploadWin.close();
										    }
									    },
									    {
										    text:'Upload',
										    scope:me,
										    handler:me.onDocUpload
									    }
								    ]
							    })
						    ]
					        }
	                    ]
                    },
                    {
                        title     : 'Dismissed Preventive Care Alerts',
                        xtype     :'grid',
                        store: me.patientsDismissedAlerts,
                        columns:[

                            {
                                header:'Description',
                                dataIndex:'description'
                            },
                            {
	                            xtype:'datecolumn',
                                header:'Date',
                                dataIndex:'date',
                                format : 'Y-m-d'

                            },
                            {
                                header:'Reason',
                                dataIndex:'reason',
                                flex:true

                            },
                            {
                                header:'Observation',
                                dataIndex:'observation',
                                flex:true

                            }
                        ],

                        plugins: Ext.create('App.classes.grid.RowFormEditing', {
                            autoCancel  : false,
                            errorSummary: false,
                            clicksToEdit: 1,
                            formItems: [
                                {
                                    title  : 'general',
                                    xtype  : 'container',
                                    padding: 10,
                                    layout : 'vbox',
                                    items  : [
                                        {
                                            /**
                                             * Line one
                                             */
                                            xtype   : 'fieldcontainer',
                                            layout  : 'hbox',
                                            defaults: { margin: '0 10 5 0' },
                                            items   : [
                                                {
                                                    xtype:'textfield',
                                                    name:'reason',
                                                    fieldLabel:'Reason',
                                                    width:585,
                                                    labelWidth: 70,
                                                    action:'reason'
                                                }

                                            ]

                                        },
                                        {
                                            /**
                                             * Line two
                                             */
                                            xtype   : 'fieldcontainer',
                                            layout  : 'hbox',
                                            defaults: { margin: '0 10 5 0' },
                                            items   : [

                                                {
                                                    xtype:'textfield',
                                                    fieldLabel: 'Observation',
                                                    name      : 'observation',
                                                    width     : 250,
                                                    labelWidth: 70,
                                                    action:'observation'
                                                },
                                                {
                                                    fieldLabel: 'Date',
                                                    xtype:'datefield',
                                                    action:'date',
                                                    width     : 200,
                                                    labelWidth: 40,
                                                    format    : 'Y-m-d',
                                                    name      : 'date'

                                                },
                                                {
                                                    xtype:'checkboxfield',
                                                    name : 'dismiss',
                                                    fieldLabel : 'Dismiss Alert?'

                                                }

                                            ]

                                        }
                                    ]
                                }

                            ]
                        })
                    }
                ]
            },
            {
                xtype      : 'panel',
                width      : 250,
                bodyPadding: 0,
                frame      : false,
                border:false,
                bodyBorder     : true,
                margin     : '0 0 0 5',
                defaults   : {
                    layout: 'fit',
                    margin: '5 5 0 5'
                },
                listeners  : {
                    scope      : me,
                    render: me.rightColRender
                },
                items      : [
                    {
                        title      : 'Active Medications',
                        itemId     : 'MedicationsPanel',
                        hideHeaders: true,
                        xtype      : 'grid',
                        store      : me.patientMedicationsStore,
                        columns    : [
                            {
                                header   : 'Name',
                                dataIndex: 'medication',
                                flex     : 1
                            },
                            {
                                text     : 'Alert',
                                width    : 55,
                                dataIndex: 'alert',
                                renderer : me.boolRenderer
                            }

                        ]
                    },
                    {
                        title      : 'Immunizations',
                        itemId     : 'ImmuPanel',
                        hideHeaders: true,
                        xtype      : 'grid',
                        store      : me.immuCheckListStore,
                        region     : 'center',
                        columns    : [
                            {

                                header   : 'Name',
                                dataIndex: 'immunization_name',
                                flex     : 1
                            },
                            {
                                text     : 'Alert',
                                width    : 55,
                                dataIndex: 'alert',
                                renderer : me.alertRenderer
                            }

                        ]
                    },
                    {
                        title      : 'Allergies',
                        itemId     : 'AllergiesPanel',
                        hideHeaders: true,
                        xtype      : 'grid',
                        store      : me.patientAllergiesListStore,
                        region     : 'center',
                        columns    : [
                            {
                                header   : 'Name',
                                dataIndex: 'allergy',
                                flex     : 1
                            },
                            {
                                text     : 'Alert',
                                width    : 55,
                                dataIndex: 'alert',
                                renderer : me.boolRenderer
                            }
                        ]
                    },
                    {
                        title      : 'Active Problems',
                        itemId     : 'IssuesPanel',
                        hideHeaders: true,
                        xtype      : 'grid',
                        store      : me.patientMedicalIssuesStore,
                        columns    : [
                            {

                                header   : 'Name',
                                dataIndex: 'code',
                                flex     : 1
                            },
                            {
                                text     : 'Alert',
                                width    : 55,
                                dataIndex: 'alert',
                                renderer : me.boolRenderer
                            }

                        ]

                    },
                    {
                        title      : 'Dental',
                        itemId     : 'DentalPanel',
                        hideHeaders: true,
                        xtype      : 'grid',
                        store      : me.patientDentalStore,

                        columns: [
                            {

                                header   : 'Name',
                                dataIndex: 'title',
                                flex     : 1

                            }

                        ]

                    },
                    {
                        title      : 'Surgeries',
                        itemId     : 'SurgeryPanel',
                        hideHeaders: true,
                        xtype      : 'grid',
                        store      : me.patientSurgeryStore,

                        columns: [
                            {
                                dataIndex: 'surgery',
                                flex     : 1
                            }
                        ]
                    },
                    {
                        title: 'Appointments',
                        html : 'Panel content!'

                    }
                ]
            }
        ];

        me.listeners = {
            scope       : me,
            render: me.beforePanelRender
        };

        me.callParent(arguments);
    },

	onDocumentView:function(grid, rowIndex){
		var rec = grid.getStore().getAt(rowIndex),
			src = rec.data.url;
		app.onDocumentView(src);
	},

	uploadADocument:function(grid, rowIndex){
		var me = this,
			previewPanel = me.query('[action="upload"]')[0], win;
		me.uploadWin.show();
		me.uploadWin.alignTo(previewPanel.el.dom,'tr-tr',[-5,30])
	},
	onDocUpload:function(btn){
		var me = this,
			form = me.uploadWin.down('form').getForm(),
			win = btn.up('window');

		if(form.isValid()){
			form.submit({
				waitMsg: 'Uploading Document...',
				params:{
					pid:me.pid,
					docType:'UploadDoc'
				},
				success: function(fp, o) {
					win.close();
					me.patientDocumentsStore.load({params: {pid: me.pid}});
				},
				failure:function(fp, o){
					//say(o.result.error);

				}
			});
		}
	},

	formSave:function(btn){
		var me = this,
			form = btn.up('form').getForm(),
			record = form.getRecord(),
			values = form.getValues();

        values.pid = me.pid;
		record.set(values);
		record.store.save({
			scope:me,
			callback:function(){
				me.getPatientImgs();
                me.verifyPatientRequiredInfo();
                me.readOnlyFields(form.getFields());
			}
		});
	},

	formCancel:function(btn){
		var form = btn.up('form').getForm(),
			record = form.getRecord();
		form.loadRecord(record);
	},

	newDoc:function(btn){
		app.onNewDocumentsWin(btn.action)
	},


    getFormData: function(formpanel, callback) {

        var me = this, rFn, uFn;

        if(formpanel.itemId == 'demoFormPanel') {
	        rFn = Patient.getPatientDemographicData;
	        uFn = Patient.updatePatientDemographicData;
        }

        var formFields = formpanel.getForm().getFields().items, modelFields = [{name:'pid',type:'int'}];

        for(var i = 0; i < formFields.length; i++ ){
            if(formFields[i].xtype == 'mitos.datetime'){
                modelFields.push({name: formFields[i].name, type: 'date', dateFormat:'Y-m-d H:i:s'});
            }else{
                modelFields.push({name: formFields[i].name});
            }
        }

//        Ext.each(formFields.items, function(field) {
//	        if(field.xtype == 'datefield'){
//		        modelFields.push({name: field.name, type: 'date', dateFormat:'Y-m-d'});
//	        }else{
//		        modelFields.push({name: field.name});
//	        }
//        });

        var model = Ext.define(formpanel.itemId + 'Model', {
            extend: 'Ext.data.Model',
            fields: modelFields,
            proxy : {
                type: 'direct',
                api : {
                    read: rFn,
	                update: uFn
                }
            }
        });

        var store = Ext.create('Ext.data.Store', {
            model: model
        });

        store.load({
            scope   : me,
            callback: function(records, operation, success) {
                callback(formpanel.getForm().loadRecord(records[0]));
            }
        });

        /**
         * load the vitals store to render the vitals data view
         */
        me.vitalsStore.load();

    },
    beforePanelRender: function() {
        var me = this, demoFormPanel = me.query('[action="demoFormPanel"]')[0],
	        whoPanel,
	        insurancePanel,
	        primaryInsurancePanel,
	        secondaryInsurancePanel,
	        tertiaryInsurancePanel;
        this.getFormItems(demoFormPanel, 'Demographics', function(success) {
            if(success) {
                whoPanel = demoFormPanel.query('panel[title="Who"]')[0];
	            insurancePanel = demoFormPanel.query('panel[action="insurances"]')[0];
	            primaryInsurancePanel = insurancePanel.items.items[0];
	            secondaryInsurancePanel = insurancePanel.items.items[1];
	            tertiaryInsurancePanel = insurancePanel.items.items[2];

	            whoPanel.insert(0,Ext.create('Ext.panel.Panel',{
                    action :'patientImgs',
                    layout:'hbox',
                    style:'float:right',
		            bodyPadding: 5,
                    height:160,
                    width:255,
                    items:[
                        me.patientImg = Ext.create('Ext.container.Container', {
                            html: '<img src="ui_icons/patientPhotoId.jpg" height="119" width="119" />',
                            margin:'0 5 0 0'
                        }),
                        me.patientQRcode = Ext.create('Ext.container.Container', {
                            html: '<img src="ui_icons/patientDataQrCode.png" height="119" width="119" />',
                            margin: 0
                        })
                    ],
		            bbar:[
			            '-',
			            {
				            text:'Take Picture',
				            scope:me,
				            handler: me.getPhotoIdWindow
			            },
			            '-',
			            '->',
			            '-',
			            {
				            text:'Print QRCode',
				            scope:me,
				            handler: function(){
					            window.printQRCode(app.currPatient.pid);
				            }
			            },
			            '-'
		            ]
                }));

	            primaryInsurancePanel.insert(0,Ext.create('Ext.panel.Panel',{
                    style:'float:right',
                    height:182,
                    width:255,
                    items:[
                        me.primaryInsuranceImg = Ext.create('Ext.container.Container', {
                            html: '<img src="ui_icons/no_card.jpg" height="154" width="254" />'
                        })
                    ],
		            bbar:[
			            '->',
			            '-',
			            {
				            text:'Upload',
				            action :'primaryInsurance',
				            scope:me,
                            handler:me.uploadInsurance
			            },
			            '-'
		            ]
                }));

	            secondaryInsurancePanel.insert(0,Ext.create('Ext.panel.Panel',{
                    style:'float:right',
                    height:182,
                    width:255,
                    items:[
                        me.secondaryInsuranceImg = Ext.create('Ext.container.Container', {
                            html: '<img src="ui_icons/no_card.jpg" height="154" width="254" />'
                        })
                    ],
		            bbar:[
			            '->',
			            '-',
			            {
				            text:'Upload',
				            action :'secondaryInsurance',
				            scope:me,
                            handler:me.uploadInsurance
			            },
			            '-'
		            ]
                }));

	            tertiaryInsurancePanel.insert(0,Ext.create('Ext.panel.Panel',{
                    style:'float:right',
                    height:160,
                    width:255,
                    items:[
                        me.tertiaryInsuranceImg = Ext.create('Ext.container.Container', {
                            html: '<img src="ui_icons/no_card.jpg" height="154" width="254" />'
                        })
                    ],
		            bbar:[
			            '->',
			            '-',
			            {
				            text:'Upload',
				            action :'tertiaryInsurance',
				            scope:me,
				            handler:me.uploadInsurance
			            },
			            '-'
		            ]
                }));


            }
        });
    },

	uploadInsurance:function(btn){

		var me = this,
			ImgContainer = btn.up('panel').down('container'),
			action = btn.action;

		say(ImgContainer);
		say(action);

//		ImgContainer.update('<img src="' + settings.site_url + '/patients/' + me.pid + '/patientPhotoId.jpg?' + number + '" height="100" width="100" />');

    },

    rightColRender : function(panel) {
        var me = this;
        panel.getComponent('ImmuPanel').header.add({
            xtype  : 'button',
            text   : 'Details',
            action : 'immunization',
            scope  : me,
            handler: me.medicalWin


        });
        panel.getComponent('MedicationsPanel').header.add({
            xtype  : 'button',
            text   : 'Details',
            action : 'medications',
            scope  : me,
            handler: me.medicalWin


        });
        panel.getComponent('AllergiesPanel').header.add({
            xtype  : 'button',
            text   : 'Details',
            action : 'allergies',
            scope  : me,
            handler: me.medicalWin
        });
        panel.getComponent('IssuesPanel').header.add({
            xtype  : 'button',
            text   : 'Details',
            action : 'issues',
            scope  : me,
            handler: me.medicalWin
        });
        panel.getComponent('DentalPanel').header.add({
            xtype  : 'button',
            text   : 'Details',
            action : 'dental',
            scope  : me,
            handler: me.medicalWin
        });
        panel.getComponent('SurgeryPanel').header.add({
            xtype  : 'button',
            text   : 'Details',
            action : 'surgery',
            scope  : me,
            handler: me.medicalWin
        });
        this.doLayout();
    },
    medicalWin    : function(btn) {
        app.onMedicalWin(btn);
    },

    getPatientImgs: function() {
        var me = this,
	        number = Ext.Number.randomInt(1,1000);
        me.patientImg.update('<img src="' + settings.site_url + '/patients/' + me.pid + '/patientPhotoId.jpg?' + number + '" height="119" width="119" />');
        me.patientQRcode.update('<a ondblclick="printQRCode(app.currPatient.pid)"><img src="' + settings.site_url + '/patients/' + me.pid + '/patientDataQrCode.png?' + number + '" height="119" width="119" title="Print QRCode" /></a>');
    },

	getPhotoIdWindow: function() {
		var me = this;
		me.PhotoIdWindow = Ext.create('App.classes.PhotoIdWindow', {
			title      : 'Patient Photo Id',
			loadMask   : true,
			modal      : true
		}).show();
	},

	completePhotoId:function(){
		this.PhotoIdWindow.close();
		this.getPatientImgs();
	},

    verifyPatientRequiredInfo:function(){
        var me = this,
            formPanel = me.query('[action="demoFormPanel"]')[0],
            field;

        me.patientAlertsStore.load({
	        scope:me,
	        params: {
		        pid: me.pid
	        },
	        callback:function(records, operation, success){

                for(var i = 0; i < records.length; i++){
                    field = formPanel.getForm().findField(records[i].data.name);
                    if(records[i].data.val){
                        field.removeCls('x-field-yellow');
                    }else{
                        field.addCls('x-field-yellow');
                    }
                }
	        }

        });
    },

    onRemoveAlerts:function(grid, rowIndex, colIndex){
		var me = this,
            store = grid.getStore(),
			record = store.getAt(rowIndex);
        store.remove(record);
	},


    readOnlyFields: function(fields) {
        for(var i = 0; i < fields.items.length; i++){
            var f = fields.items[i],
                v = f.getValue(),
                n = f.name;
            if(n == 'SS' || n == 'DOB' || n == 'sex'){
                if(v == null || v == ''){
                    f.setReadOnly(false);
                }else{
                    f.setReadOnly(true);
                }
            }
        }
    },

    /**
     * This function is called from MitosAPP.js when
     * this panel is selected in the navigation panel.
     * place inside this function all the functions you want
     * to call every this panel becomes active
     */
    onActive: function(callback) {
        var me = this,
	        billingPanel = me.query('[action="balance"]')[0],
	        demographicsPanel = me.query('[action="demoFormPanel"]')[0];
        demographicsPanel.getForm().reset();

        if(me.checkIfCurrPatient()) {

            me.pid = app.currPatient.pid;

            var patient = me.getCurrPatient();
	        me.updateTitle(patient.name + ' - #' + patient.pid + ' (Patient Summary)', app.currPatient.readOnly);
	        me.setReadOnly();
	        me.setButtonsDisabled(me.query('button[action="readOnly"]'));
	        ACL.hasPermission('access_demographics',function(provider, response){
		        if (response.result){
			        demographicsPanel.show();
	                me.getFormData(demographicsPanel, function(form){
                        me.readOnlyFields(form.getFields());
                    });
		        }else{
			        demographicsPanel.hide();
		        }
	        });
	        me.getPatientImgs();
	        Fees.getPatientBalance({pid:me.pid},function(balance){
		        billingPanel.body.update('Account Balance: $' + balance);
	        });
	        me.patientNotesStore.load({params: {pid: me.pid}});
	        me.patientRemindersStore.load({params: {pid: me.pid}});
	        me.immuCheckListStore.load({params: {pid: me.pid}});
	        me.patientAllergiesListStore.load({params: {pid: me.pid}});
	        me.patientMedicalIssuesStore.load({params: {pid: me.pid}});
	        me.patientSurgeryStore.load({params: {pid: me.pid}});
	        me.patientDentalStore.load({params: {pid: me.pid}});
	        me.patientMedicationsStore.load({params: {pid: me.pid}});
	        me.patientDocumentsStore.load({params: {pid: me.pid}});

	        me.encounterEventHistoryStore.load({params: {pid: me.pid}});
	        me.patientsDismissedAlerts.load({params: {pid: me.pid}});

            me.verifyPatientRequiredInfo();



        } else {
            callback(false);
            me.pid = null;
            me.currPatientError();
        }
    }

});