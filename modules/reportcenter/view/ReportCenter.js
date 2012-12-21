//******************************************************************************
// ReportCenter.js
// This is the Report Center Main Panel ths will contain categories and list
// of the available reports in GaiaEHR App
// v0.0.1
// 
// Author: Gino Rivera Falu (GI Technologies)
// Modified:
// 
// GaiaEHR (Electronic Health Records) 2012
//******************************************************************************
Ext.define('Modules.reportcenter.view.ReportCenter', {
        extend   : 'App.ux.RenderPanel',
        id       : 'panelReportCenter',
        pageTitle: i18n('report_center'),

        initComponent            : function() {
            var me = this;

            me.reports = Ext.create('Ext.panel.Panel', {
                layout: 'auto'
            });
            me.pageBody = [ me.reports ];
            /*
             * Patient Reports List
             * TODO: Pass the report indicator telling what report should be rendering
             * this indicator will also be the logic for field rendering.
             */
            me.patientCategory = me.addCategory(i18n('patient_reports'), 260);
            me.ClientListReport = me.addReportByCategory(me.patientCategory, i18n('client_list_report'), function(btn) {

                if(!me.clientListStore) me.clientListStore = Ext.create('Modules.reportcenter.store.ClientList');

                me.goToReportPanelAndSetPanel({
                    title:i18n('client_list_report'),
                    items : [
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n('from'),
                            name      : 'from',
                            format:'Y-m-d'
                        },
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n('to'),
                            name      : 'to',
                            format:'Y-m-d'
                        },{
                            xtype          : 'patienlivetsearch',
                            fieldLabel     : i18n('name'),
                            hideLabel      : false,
                            name           : 'pid',
                            width          : 350
                        }
                    ],
                    fn:ClientList.CreateClientList,
                    store:me.clientListStore,
                    columns:[
                        {
                            text:i18n('service_date'),
                            xtype:'datecolumn',
                            format:'Y-m-d',
                            dataIndex:'start_date'
                        },
                        {
                            text:i18n('name'),
                            width:200,
                            dataIndex:'fullname'
                        },
                        {
                            text:i18n('address'),
                            flex:1,
                            dataIndex:'fulladdress'
                        },
                        {
                            text:i18n('home_phone'),
                            dataIndex:'home_phone'
                        }
                    ]
                });
            });

            me.Rx = me.addReportByCategory(me.patientCategory, i18n('rx'), function(btn) {
                if(!me.medicationStore) me.medicationStore = Ext.create('Modules.reportcenter.store.MedicationReport');

                me.goToReportPanelAndSetPanel({
                    title:i18n('rx'),
                    items : [
                        {
                            xtype   : 'fieldcontainer',
                            layout  : 'hbox',
                            defaults: { margin: '0 10 5 0' },
                            items   : [

                                {
                                    xtype     : 'datefield',
                                    fieldLabel: i18n('from'),
                                    name      : 'from',
                                    format:'Y-m-d',
                                    width     : 275
                                },{
                                    xtype          : 'patienlivetsearch',
                                    fieldLabel     : i18n('name'),
                                    hideLabel      : false,
                                    name           : 'pid',
                                    width          : 350
                                }
                            ]

                        },
                        {
                            xtype   : 'fieldcontainer',
                            layout  : 'hbox',
                            defaults: { margin: '0 10 5 0' },
                            items   : [
                                {
                                    xtype     : 'datefield',
                                    fieldLabel: i18n('to'),
                                    name      : 'to',
                                    format    :'Y-m-d',
                                    width     : 275
                                },
                                {
                                    xtype     : 'medicationlivetsearch',
                                    fieldLabel     : i18n('drug'),
                                    hideLabel      : false,
                                    name           : 'drug',
                                    width          : 350
                                }

                            ]

                        }
                    ],
                    fn:Rx.createPrescriptionsDispensations,
                    store:me.medicationStore,
                    columns:[
                        {
                            text:i18n('name'),
                            width:250,
                            dataIndex:'fullname'
                        },
                        {
                            text:i18n('medication'),
                            width:250,
                            dataIndex:'medication'
                        },
                        {
                            text:i18n('take'),
                            width:75,
                            dataIndex:'take_pills'
                        },
                        {
                            text:i18n('type'),
                            width:150,
                            dataIndex:'type'
                        },
                        {
                            text:i18n('instructions'),
                            flex:1,
                            dataIndex:'instructions'
                        }
                    ]
                });
            });
            me.ClinicalReport = me.addReportByCategory(me.patientCategory, i18n('clinical'), function(btn) {
                if(!me.clinicalStore) me.clinicalStore = Ext.create('Modules.reportcenter.store.Clinical');
                me.goToReportPanelAndSetPanel({
                    title:i18n('clinical'),
                    action: 'clientListReport',
                    items : [

                        {
                            title  : i18n('general'),
                            xtype  : 'container',
                            layout : 'vbox',
                            items  : [
                                {
                                    xtype   : 'fieldcontainer',
                                    layout  : 'hbox',
                                    defaults: { margin: '0 10 0 0' },
                                    items   : [

                                        {
                                            xtype          : 'patienlivetsearch',
                                            fieldLabel     : i18n('patient'),
                                            hideLabel      : false,
                                            name           : 'pid',
                                            width          : 280
                                        },
                                        {
                                            xtype     : 'mitos.sexcombo',
                                            fieldLabel: i18n('sex'),
                                            name      : 'sex',
                                            labelWidth: 75,
                                            width     : 140,
                                            minValue  : 0

                                        },
                                        {
                                            xtype     : 'mitos.racecombo',
                                            fieldLabel: i18n('race'),
                                            name      : 'race',
                                            action    : 'race',
                                            hideLabel : false,
                                            width     : 275,
                                            labelWidth: 70

                                        }

                                    ]

                                },
                                {
                                    xtype   : 'fieldcontainer',
                                    layout  : 'hbox',
                                    defaults: { margin: '0 10 0 0' },
                                    items   : [
                                        {
                                            xtype     : 'datefield',
                                            fieldLabel: i18n('date_from'),
                                            format    :'Y-m-d',
                                            name      : 'from'
                                        },
                                        {
                                            xtype     : 'numberfield',
                                            fieldLabel: i18n('age_from'),
                                            name: 'age_from',
                                            labelWidth: 75,
                                            width     : 140,
                                            minValue  : 0

                                        },
                                        {
                                            xtype     : 'mitos.ethnicitycombo',
                                            fieldLabel: i18n('ethnicity'),
                                            name      : 'ethnicity',
                                            action    : 'ethnicity',
                                            hideLabel : false,
                                            width     : 275,
                                            labelWidth: 70

                                        }

                                    ]

                                },
                                {
                                    xtype   : 'fieldcontainer',
                                    layout  : 'hbox',
                                    defaults: { margin: '0 10 0 0' },
                                    items   : [

                                        {
                                            xtype     : 'datefield',
                                            fieldLabel: i18n('date_to'),
                                            format    :'Y-m-d',
                                            name      : 'to'
                                        },
                                        {
                                            xtype     : 'numberfield',
                                            fieldLabel: i18n('age_to'),
                                            name: 'age_to',
                                            labelWidth: 75,
                                            width     :140,
                                            minValue  : 0

                                        }/*,
                                        {
                                            xtype     : 'liveicdxsearch',
                                            fieldLabel: i18n('problem_dx'),
                                            name      : 'icd',
                                            hideLabel : false,
                                            action    : 'icd',
                                            width     : 225,
                                            labelWidth: 70

                                        }*/


                                    ]
                                }
                            ]
                        }
                    ],
                    fn:Clinical.createClinicalReport,
                    store:me.clinicalStore,
                    columns:[
                        {
                            text:i18n('name'),
                            width:200,
                            dataIndex:'fullname'
                        },
                        {
                            text:i18n('age'),
                            width:75,
                            dataIndex:'age'
                        },
                        {
                            text:i18n('sex'),
                            dataIndex:'sex'
                        },
                        {
                            text:i18n('race'),
                            width:250,
                            dataIndex:'race'
                        },
                        {
                            text:i18n('Ethnicity'),
                            flex:1,
                            dataIndex:'ethnicity'
                        }
                    ]
                });
            });
            me.ImmunizationReport = me.addReportByCategory(me.patientCategory, i18n('immunization_registry'), function(btn) {
                if(!me.immunizationReportStore) me.immunizationReportStore = Ext.create('Modules.reportcenter.store.ImmunizationsReport');
                me.goToReportPanelAndSetPanel({
                    title:i18n('immunization_registry'),
                    action: 'clientListReport',
                    items : [
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n('from'),
                            name      : 'from',
                            format    :'Y-m-d',
                            width     : 350
                        },
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n('to'),
                            name      : 'to',
                            format    :'Y-m-d',
                            width     : 350
                        },
                        {
                            xtype          : 'immunizationlivesearch',
                            fieldLabel     : i18n('immunization'),
                            hideLabel      : false,
                            name           : 'immu',
                            width          : 350
                        }
                    ],
                    fn:ImmunizationsReport.createImmunizationsReport,
                    store:me.immunizationReportStore,
                        columns:[
                            {
                                text:i18n('name'),
                                width:200,
                                dataIndex:'fullname'
                            },
                            {
                                text:i18n('immunization_id'),
                                dataIndex:'immunization_id'
                            },
                            {
                                text:i18n('immunization_name'),
                                dataIndex:'immunization_name',
                                flex:1
                            },
                            {
                                text:i18n('administered_date'),
                                dataIndex:'administered_date',
                                xtype:'datecolumn',
                                format:'Y-m-d'
                            }
                        ]
                });
            });

            /*
             * Clinic Reports List
             * TODO: Pass the report indicator telling what report should be rendering
             * this indicator will also be the logic for field rendering.
             */
            me.clinicCategory = me.addCategory(i18n('clinic_reports'), 260);
            me.link5 = me.addReportByCategory(me.clinicCategory, i18n('standard_measures'), function(btn) {
                me.goToReportPanelAndSetPanel({
                    title:i18n('standard_measures'),
                    action: 'clientListReport',
                    items : [
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n('from'),
                            name      : 'from'
                        },
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n('to'),
                            name      : 'to'
                        }
                    ]
                });
            });
            me.link6 = me.addReportByCategory(me.clinicCategory, i18n('clinical_quality_measures_cqm'), function(btn) {
                me.goToReportPanelAndSetPanel({
                    title:i18n('clinical_quality_measures_cqm'),
                    action: 'clientListReport',
                    items : [
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n('from'),
                            name      : 'from'
                        },
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n('to'),
                            name      : 'to'
                        }
                    ]
                });
            });
            me.link7 = me.addReportByCategory(me.clinicCategory, i18n('automated_measure_calculations_amc'), function(btn) {
                me.goToReportPanelAndSetPanel({
                    title:i18n('automated_measure_calculations_amc'),
                    action: 'clientListReport',
                    items : [
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n('from'),
                            name      : 'from'
                        },
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n('to'),
                            name      : 'to'
                        }
                    ]
                });
            });
            me.link8 = me.addReportByCategory(me.clinicCategory, i18n('automated_measure_calculations_tracking'), function(btn) {
                me.goToReportPanelAndSetPanel({
                    title:i18n('automated_measure_calculations_tracking'),
                    action: 'clientListReport',
                    items : [
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n('from'),
                            name      : 'from'
                        },
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n('to'),
                            name      : 'to'
                        }
                    ],
                    fn:function(){

                    }
                });
            });

            /*
             * Visits Category List
             * TODO: Pass the report indicator telling what report should be rendering
             * this indicator will also be the logic for field rendering.
             */
            me.visitCategory = me.addCategory(i18n('visit_reports'), 260);
            me.link9 = me.addReportByCategory(me.visitCategory, i18n('super_bill'), function(btn) {
                me.goToReportPanelAndSetPanel({
                    title: i18n('super_bill'),
                    items : [
                        {
	                        xtype          : 'patienlivetsearch',
	                        fieldLabel     : i18n('name'),
	                        hideLabel      : false,
	                        name           : 'pid',
	                        width          : 570
                        },
	                    {
                            xtype     : 'datefield',
                            fieldLabel: i18n('from'),
                            allowBlank: false,
                            name      : 'from',
                            format:'Y-m-d'
                        },
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n('to'),
                            name      : 'to',
                            format:'Y-m-d'
                        }
                    ],
                    fn:SuperBill.CreateSuperBill
                });
            });

            me.link10 = me.addReportByCategory(me.visitCategory, i18n('appointments'), function(btn) {
                if(!me.appointmentsReportStore) me.appointmentsReportStore = Ext.create('Modules.reportcenter.store.Appointment');
                me.goToReportPanelAndSetPanel({
                    title: i18n('appointments'),
                    items : [
                        {
                            xtype   : 'fieldcontainer',
                            layout  : 'hbox',
                            defaults: { margin: '0 10 5 0' },
                            items   : [
                                {
                                    xtype     : 'datefield',
                                    fieldLabel: i18n('from'),
                                    name      : 'from',
                                    format:'Y-m-d',
                                    width     : 275
                                },
                                {
                                    xtype     : 'mitos.facilitiescombo',
                                    fieldLabel: i18n('facility'),
                                    name      : 'facility',
                                    hideLabel : false,
                                    width     : 300,
                                    labelWidth: 70

                                }
                            ]

                        },
                        {
                            xtype   : 'fieldcontainer',
                            layout  : 'hbox',
                            defaults: { margin: '0 10 5 0' },
                            items   : [
                                {
                                    xtype     : 'datefield',
                                    fieldLabel: i18n('to'),
                                    name      : 'to',
                                    format    :'Y-m-d',
                                    width     : 275
                                },
                                {
                                    xtype     : 'mitos.providerscombo',
                                    fieldLabel: i18n('provider'),
                                    name      : 'provider',
                                    hideLabel : false,
                                    width     : 300,
                                    labelWidth: 70

                                }

                            ]

                        }
                    ],
                    fn:Appointments.CreateAppointmentsReport,
                        store:me.appointmentsReportStore,
                            columns:[
                                {
                                    text:i18n('provider'),
                                    width:200,
                                    dataIndex:'provider'
                                },
                                {
                                    text:i18n('patient'),
                                    width:200,
                                    dataIndex:'fullname'
                                },
                                {
                                    text:i18n('date'),
                                    dataIndex:'start',
                                    xtype:'datecolumn',
                                    format:'Y-m-d'
                                },
                                {
                                    text:i18n('time'),
                                    dataIndex:'start_time',
                                    xtype:'datecolumn',
                                    format:'h:i a'
                                },
                                {
                                    text:i18n('category'),
                                    dataIndex:'catname',
                                    width:200
                                },
                                {
                                    text:i18n('facility'),
                                    dataIndex:'facility',
                                    width:250
                                },
                                {
                                    text:i18n('notes'),
                                    dataIndex:'notes',
                                    flex:1
                                }
                            ]
                });
            });

            me.callParent(arguments);

        },

        /*
         * Function to add categories with the respective with to the
         * Report Center Panel
         */
        addCategory              : function(category, width) {
            var me = this;
            return me.reports.add(Ext.create('Ext.container.Container', {
                    cls   : 'CategoryContainer',
                    width : width,
                    layout: 'anchor',
                    items : [
                        {
                            xtype : 'container',
                            cls   : 'title',
                            margin: '0 0 5 0',
                            html  : category
                        }
                    ]
                }));
        },

        /*
         * Function to add Items to the category
         */
        addReportByCategory      : function(category, text, fn) {
            return category.add(Ext.create('Ext.button.Button', {
                    cls      : 'CategoryContainerItem',
                    anchor   : '100%',
                    margin   : '0 0 5 0',
                    textAlign: 'left',
                    text     : text,
                    handler  : fn
                }));
        },

        /*
         * Function to call the report panel.
         * Remember the report fields are dynamically rendered.
         */
        goToReportPanelAndSetPanel: function(config) {
            var panel = app.MainPanel.getLayout().setActiveItem('panelReportPanel');
            panel.setReportPanel(config);



//            formPanel.setTitle(config.title);
//            formPanel.action = config.action;
//            formPanel.reportFn = config.fn;
//            formPanel.removeAll();
//            formPanel.add(config.items);
        },

        /**
         * This function is called from MitosAPP.js when
         * this panel is selected in the navigation panel.
         * place inside this function all the functions you want
         * to call every this panel becomes active
         */
        onActive: function(callback) {
            callback(true);
        }

    }); //ens oNotesPage class