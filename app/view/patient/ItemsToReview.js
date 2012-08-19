/**
 * Created with JetBrains PhpStorm.
 * User: Plushy
 * Date: 7/6/12
 * Time: 5:54 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.patient.ItemsToReview', {
    extend       : 'Ext.panel.Panel',
    layout       : 'column',
    frame        : true,
    bodyPadding  : 5,
    bodyBorder   : true,
    bodyStyle    : 'background-color:white',
    eid          : null,
    initComponent: function() {
        var me = this;
        me.patientImmuListStore = Ext.create('App.store.patient.PatientImmunization');
        me.patientAllergiesListStore = Ext.create('App.store.patient.Allergies');
        me.patientMedicalIssuesStore = Ext.create('App.store.patient.MedicalIssues');
        me.patientSurgeryStore = Ext.create('App.store.patient.Surgery');
        me.patientDentalStore = Ext.create('App.store.patient.Dental');
        me.patientMedicationsStore = Ext.create('App.store.patient.Medications');

        me.column1 = Ext.create('Ext.container.Container', {
            columnWidth: 0.3333,
            defaults   : {
                xtype : 'grid',
                margin: '0 5 5 0'
            },
            items      : [
                {
                    title  : 'Immunizations',
                    frame  : true,
                    height : 180,
                    store  : me.patientImmuListStore,
                    columns: [
                        {
                            header   : 'Immunization',
                            width    : 250,
                            dataIndex: 'immunization_name'
                        },
                        {
                            header   : 'Date',
                            width    : 90,
                            xtype    : 'datecolumn',
                            format   : 'Y-m-d',
                            dataIndex: 'administered_date'
                        },
                        {
                            header   : 'Notes',
                            flex     : 1,
                            dataIndex: 'note'
                        }
                    ]
                },
                {
                    title  : 'Allergies',
                    frame  : true,
                    height : 180,
                    store  : me.patientAllergiesListStore,
                    columns: [
                        {
                            header   : 'Type',
                            width    : 100,
                            dataIndex: 'allergy_type'
                        },
                        {
                            header   : 'Name',
                            width    : 100,
                            dataIndex: 'allergy'
                        },
                        {
                            header   : 'Severity',
                            flex     : 1,
                            dataIndex: 'severity'
                        }
                    ]
                }
            ]
        });

        me.column2 = Ext.create('Ext.container.Container', {
            columnWidth: 0.3333,
            defaults   : {
                xtype : 'grid',
                margin: '0 5 5 0'
            },
            items      : [
                {
                    title  : 'Active Problems',
                    frame  : true,
                    height : 180,
                    store  : me.patientMedicalIssuesStore,
                    columns: [
                        {
                            header   : 'Problem',
                            width    : 250,
                            dataIndex: 'code'
                        },
                        {
                            xtype    : 'datecolumn',
                            header   : 'Begin Date',
                            width    : 90,
                            format   : 'Y-m-d',
                            dataIndex: 'begin_date'
                        },
                        {
                            xtype    : 'datecolumn',
                            header   : 'End Date',
                            flex     : 1,
                            format   : 'Y-m-d',
                            dataIndex: 'end_date'
                        }
                    ]
                },
                {
                    title  : 'Surgery',
                    frame  : true,
                    height : 180,
                    store  : me.patientSurgeryStore,
                    columns: [
                        {
                            header   : 'Type',
                            width    : 250,
                            dataIndex: 'type'
                        },
                        {
                            xtype    : 'datecolumn',
                            header   : 'Begin Date',
                            width    : 90,
                            format   : 'Y-m-d',
                            dataIndex: 'begin_date'
                        },
                        {
                            xtype    : 'datecolumn',
                            header   : 'End Date',
                            flex     : 1,
                            format   : 'Y-m-d',
                            dataIndex: 'end_date'
                        }
                    ]
                }
            ]
        });

        me.column3 = Ext.create('Ext.container.Container', {
            columnWidth: 0.3333,
            defaults   : {
                xtype : 'grid',
                margin: '0 0 5 0'
            },
            items      : [
                {
                    title  : 'Dental',
                    frame  : true,
                    height : 180,
                    store  : me.patientDentalStore,
                    columns: [
                        {
                            header   : 'Title',
                            width    : 250,
                            dataIndex: 'title'
                        },
                        {
                            xtype    : 'datecolumn',
                            header   : 'Begin Date',
                            width    : 90,
                            format   : 'Y-m-d',
                            dataIndex: 'begin_date'
                        },
                        {
                            xtype    : 'datecolumn',
                            header   : 'End Date',
                            flex     : 1,
                            format   : 'Y-m-d',
                            dataIndex: 'end_date'
                        }
                    ]
                },
                {
                    title  : 'Medications',
                    frame  : true,
                    height : 180,
                    store  : me.patientMedicationsStore,
                    columns: [
                        {
                            header   : 'Medication',
                            width    : 250,
                            dataIndex: 'medication'
                        },
                        {
                            xtype    : 'datecolumn',
                            header   : 'Begin Date',
                            width    : 90,
                            format   : 'Y-m-d',
                            dataIndex: 'begin_date'
                        },
                        {
                            xtype    : 'datecolumn',
                            header   : 'End Date',
                            flex     : 1,
                            format   : 'Y-m-d',
                            dataIndex: 'end_date'
                        }
                    ]
                }
            ]
        });
        me.column4 = Ext.create('Ext.form.Panel', {
            columnWidth: 0.3333,
            border     : false,
            items      : [

                {
                    fieldLabel: 'Smoking Status',
                    xtype     : 'mitos.smokingstatuscombo',
                    labelWidth: 100,
                    width     : 325,
                    name      : 'review_smoke'


                },
                {
                    fieldLabel: 'Alcohol?',
                    xtype     : 'mitos.yesnocombo',
                    labelWidth: 100,
                    width     : 325,
                    name      : 'review_alcohol'


                },
                {
                    fieldLabel: 'Pregnant?',
                    xtype     : 'mitos.yesnonacombo',
                    labelWidth: 100,
                    width     : 325,
                    name      : 'review_pregnant'


                }

            ]
        });

        me.items = [ me.column1, me.column2, me.column3 , me.column4 ];
        me.buttons = [
            {
                text   : 'Review All',
                name   : 'review',
                scope  : me,
                handler: me.onSave
            }
        ];

        me.listeners = {
            show: me.storesLoad
        };
        me.callParent(arguments);
    },

    storesLoad: function() {
        var me = this;
        me.patientImmuListStore.load({params: {pid: app.currPatient.pid}});
        me.patientAllergiesListStore.load({params: {pid: app.currPatient.pid}});
        me.patientMedicalIssuesStore.load({params: {pid: app.currPatient.pid}});
        me.patientSurgeryStore.load({params: {pid: app.currPatient.pid}});
        me.patientDentalStore.load({params: {pid: app.currPatient.pid}});
        me.patientMedicationsStore.load({params: {pid: app.currPatient.pid}});

        Medical.getEncounterReviewByEid(app.currEncounterId, function(provider, response) {

            me.column4.getForm().setValues(response.result);
        });

    },

    onSave: function() {
        var me = this, panel = me.down('form'), form = panel.getForm(), values = form.getFieldValues(), params = {
                eid: app.currEncounterId
            };
        values.eid = app.currEncounterId;

        Medical.reviewAllMedicalWindowEncounter(params, function(provider, response) {
        });
        if(form.isValid()) {

            Encounter.onSaveItemsToReview(values, function(provider, response) {
                if(response.result.success) {
                    app.msg('Sweet!', 'Items To Review Save and Review')
                } else {
                    app.msg('Oops!', 'Items To Review entry error')
                }

            });
        }
    }
});