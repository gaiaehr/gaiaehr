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
        extend   : 'App.classes.RenderPanel',
        id       : 'panelReportCenter',
        pageTitle: i18n['report_center'],

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
            me.patientCategory = me.addCategory(i18n['patient_reports'], 260);
            me.ClientListReport = me.addReportByCategory(me.patientCategory, i18n['client_list_report'], function(btn) {
                me.goToReportPanelAndSetForm({
                    title:i18n['client_list_report'],
                    items : [
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n['from'],
                            name      : 'from',
                            format:'Y-m-d'
                        },
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n['to'],
                            name      : 'to',
                            format:'Y-m-d'
                        }
                    ],
                    fn:ClientList.CreateClientList
                });
            });
            me.Rx = me.addReportByCategory(me.patientCategory, i18n['rx'], function(btn) {
                me.goToReportPanelAndSetForm({
                    title:i18n['rx'],
                    items : [
                        {
                            xtype   : 'fieldcontainer',
                            layout  : 'hbox',
                            defaults: { margin: '0 10 5 0' },
                            items   : [

                                {
                                    xtype     : 'datefield',
                                    fieldLabel: i18n['from'],
                                    name      : 'from',
                                    format:'Y-m-d',
                                    width     : 275
                                },{
                                    xtype          : 'patienlivetsearch',
                                    fieldLabel     : i18n['name'],
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
                                    fieldLabel: i18n['to'],
                                    name      : 'to',
                                    format    :'Y-m-d',
                                    width     : 275
                                },
                                {
                                    xtype     : 'medicationlivetsearch',
                                    fieldLabel     : i18n['drug'],
                                    hideLabel      : false,
                                    name           : 'drug',
                                    width          : 350
                                }

                            ]

                        }
                    ],
                    fn:Rx.createPrescriptionsDispensations
                });
            });
            me.link2 = me.addReportByCategory(me.patientCategory, i18n['clinical'], function(btn) {
                me.goToReportPanelAndSetForm({
                    title:i18n['clinical'],
                    action: 'clientListReport',
                    items : [

                        {
                            title  : i18n['general'],
                            xtype  : 'container',
                            padding: 10,
                            layout : 'vbox',
                            items  : [
                                {
                                    xtype   : 'fieldcontainer',
                                    layout  : 'hbox',
                                    defaults: { margin: '0 10 5 0' },
                                    items   : [

                                        {
                                            xtype     : 'textfield',
                                            fieldLabel: i18n['patient_id'],
                                            name      : 'pid',
                                            width     : 280

                                        },
                                        {
                                            xtype     : 'mitos.sexcombo',
                                            fieldLabel: i18n['sex'],
                                            name      : 'sex',
                                            labelWidth: 75,
                                            width     : 140,
                                            minValue  : 0

                                        },
                                        {
                                            xtype     : 'mitos.allergieslocationcombo',
                                            fieldLabel: i18n['location'],
                                            name      : 'location',
                                            action    : 'location',
                                            width     : 225,
                                            labelWidth: 70

                                        },
                                        {
                                            fieldLabel: i18n['begin_date'],
                                            xtype     : 'datefield',
                                            format    : 'Y-m-d',
                                            name      : 'begin_date'

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
                                            fieldLabel: i18n['date_from'],
                                            format    :'Y-m-d',
                                            name      : 'from'
                                        },
                                        {
                                            xtype     : 'numberfield',
                                            fieldLabel: i18n['age_from'],
                                            name: 'age_from',
                                            labelWidth: 75,
                                            width     : 140,
                                            minValue  : 0

                                        },
                                        {
                                            xtype          : 'medicationlivetsearch',
                                            fieldLabel     : i18n['allergy'],
                                            hideLabel      : false,
                                            action         : 'drug_name',
                                            name           : 'allergy',
                                            hidden         : true,
                                            disabled       : true,
                                            enableKeyEvents: true,
                                            width          : 225,
                                            labelWidth     : 70
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
                                            fieldLabel: i18n['date_to'],
                                            format    :'Y-m-d',
                                            name      : 'to'
                                        },
                                        {
                                            xtype     : 'numberfield',
                                            fieldLabel: i18n['age_to'],
                                            name: 'age_to',
                                            labelWidth: 75,
                                            width     :140,
                                            minValue  : 0

                                        }


                                    ]
                                }
                            ]
                        }
                    ]
                });
            });
            me.ImmunizationReport = me.addReportByCategory(me.patientCategory, i18n['immunization_registry'], function(btn) {
                me.goToReportPanelAndSetForm({
                    title:i18n['immunization_registry'],
                    action: 'clientListReport',
                    items : [
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n['from'],
                            name      : 'from',
                            format    :'Y-m-d',
                            width     : 350
                        },
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n['to'],
                            name      : 'to',
                            format    :'Y-m-d',
                            width     : 350
                        },
                        {
                            xtype          : 'immunizationlivesearch',
                            fieldLabel     : i18n['immunization'],
                            hideLabel      : false,
                            name           : 'immu',
                            width          : 350
                        }
                    ],
                    fn:ImmunizationsReport.createImmunizationsReport
                });
            });

            /*
             * Clinic Reports List
             * TODO: Pass the report indicator telling what report should be rendering
             * this indicator will also be the logic for field rendering.
             */
            me.clinicCategory = me.addCategory(i18n['clinic_reports'], 260);
            me.link5 = me.addReportByCategory(me.clinicCategory, i18n['standard_measures'], function(btn) {
                me.goToReportPanelAndSetForm({
                    title:i18n['standard_measures'],
                    action: 'clientListReport',
                    items : [
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n['from'],
                            name      : 'from'
                        },
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n['to'],
                            name      : 'to'
                        }
                    ]
                });
            });
            me.link6 = me.addReportByCategory(me.clinicCategory, i18n['clinical_quality_measures_cqm'], function(btn) {
                me.goToReportPanelAndSetForm({
                    title:i18n['clinical_quality_measures_cqm'],
                    action: 'clientListReport',
                    items : [
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n['from'],
                            name      : 'from'
                        },
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n['to'],
                            name      : 'to'
                        }
                    ]
                });
            });
            me.link7 = me.addReportByCategory(me.clinicCategory, i18n['automated_measure_calculations_amc'], function(btn) {
                me.goToReportPanelAndSetForm({
                    title:i18n['automated_measure_calculations_amc'],
                    action: 'clientListReport',
                    items : [
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n['from'],
                            name      : 'from'
                        },
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n['to'],
                            name      : 'to'
                        }
                    ]
                });
            });
            me.link8 = me.addReportByCategory(me.clinicCategory, i18n['automated_measure_calculations_tracking'], function(btn) {
                me.goToReportPanelAndSetForm({
                    title:i18n['automated_measure_calculations_tracking'],
                    action: 'clientListReport',
                    items : [
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n['from'],
                            name      : 'from'
                        },
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n['to'],
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
            me.visitCategory = me.addCategory(i18n['visit_reports'], 260);
            me.link9 = me.addReportByCategory(me.visitCategory, i18n['super_bill'], function(btn) {
                me.goToReportPanelAndSetForm({
                    title: i18n['super_bill'],
                    items : [
                        {
	                        xtype          : 'patienlivetsearch',
	                        fieldLabel     : i18n['name'],
	                        hideLabel      : false,
	                        name           : 'pid',
	                        width          : 570
                        },
	                    {
                            xtype     : 'datefield',
                            fieldLabel: i18n['from'],
                            allowBlank: false,
                            name      : 'from',
                            format:'Y-m-d'
                        },
                        {
                            xtype     : 'datefield',
                            fieldLabel: i18n['to'],
                            name      : 'to',
                            format:'Y-m-d'
                        }
                    ],
                    fn:SuperBill.CreateSuperBill
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
        goToReportPanelAndSetForm: function(formConfig) {
            var panel = app.MainPanel.getLayout().setActiveItem('panelReportPanel'),
                form = panel.down('form');
            form.setTitle(formConfig.title);
            form.action = formConfig.action;
            form.reportFn = formConfig.fn;
            form.removeAll();
            form.add(formConfig.items);
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