/**
 * Created with JetBrains PhpStorm.
 * User: Plushy
 * Date: 7/6/12
 * Time: 5:54 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.patientfile.ItemsToReview',{
    extend:'Ext.panel.Panel',
    layout:'column',
	eid: null,
    initComponent:function () {
        var me = this;
       me.patientImmuListStore = Ext.create('App.store.patientfile.PatientImmunization');
       me.patientAllergiesListStore = Ext.create('App.store.patientfile.Allergies');
       me.patientMedicalIssuesStore = Ext.create('App.store.patientfile.MedicalIssues');
       me.patientSurgeryStore = Ext.create('App.store.patientfile.Surgery');
       me.patientDentalStore = Ext.create('App.store.patientfile.Dental');
       me.patientMedicationsStore = Ext.create('App.store.patientfile.Medications');



        me.column1 = Ext.create('Ext.container.Container',{
            columnWidth: 0.3333,
            defaults:{
                xtype:'grid',
                margin:'0 5 5 0'
            },
            items:[
                {
                    title:'Immunizations',
                    frame:true,
                    height:300,
                    store   : me.patientImmuListStore,
                    columns:[
                         {
                             header   : 'Immunization',
                             width    : 100,
                             dataIndex: 'immunization_name'
                         },
                         {
                             header   : 'Date',
                             width    : 100,
                             xtype:'datecolumn',
                             format : 'Y-m-d',
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
                    title:'Allergies',
                    frame:true,
                    height:300,
                    store   : me.patientAllergiesListStore,
                    columns:[
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

        me.column2 = Ext.create('Ext.container.Container',{
            columnWidth: 0.3333,
            defaults:{
                xtype:'grid',
                margin:'0 5 5 0'
            },
            items:[
                {
                    title:'Active Problems',
                    frame:true,
                    height:300,
                    store   : me.patientMedicalIssuesStore,
                    columns:[
                        {
                            header   : 'Problem',
                            width    : 100,
                            dataIndex: 'code'
                        },
                        {
                            xtype:'datecolumn',
                            header   : 'Begin Date',
                            width    : 100,
                            format : 'Y-m-d',
                            dataIndex: 'begin_date'
                        },
                        {
                            xtype:'datecolumn',
                            header   : 'End Date',
                            flex     : 1,
                            format : 'Y-m-d',
                            dataIndex: 'end_date'
                        }
                    ]
                },
                {
                    title:'Surgery',
                    frame:true,
                    height:300,
                    store   : me.patientSurgeryStore,
                    columns:[
                        {
                            header   : 'Type',
                            width    : 100,
                            dataIndex: 'type'
                        },
                        {
                            xtype:'datecolumn',
                            header   : 'Begin Date',
                            width    : 100,
                            format : 'Y-m-d',
                            dataIndex: 'begin_date'
                        },
                        {
                            xtype:'datecolumn',
                            header   : 'End Date',
                            flex     : 1,
                            format : 'Y-m-d',
                            dataIndex: 'end_date'
                        }
                    ]
                }
            ]
        });

        me.column3 = Ext.create('Ext.container.Container',{
            columnWidth: 0.3333,
            defaults:{
                xtype:'grid',
                margin:'0 0 5 0'
            },
            items:[
                {
                    title:'Dental',
                    frame:true,
                    height:300,
                    store   : me.patientDentalStore,
                    columns:[
                        {
                            header   : 'Title',
                            width    : 100,
                            dataIndex: 'title'
                        },
                        {
                            xtype:'datecolumn',
                            header   : 'Begin Date',
                            width    : 100,
                            format : 'Y-m-d',
                            dataIndex: 'begin_date'
                        },
                        {
                            xtype:'datecolumn',
                            header   : 'End Date',
                            flex     : 1,
                            format : 'Y-m-d',
                            dataIndex: 'end_date'
                        }
                    ]
                },
                {
                    title:'Medications',
                    frame:true,
                    height:300,
                    store   : me.patientMedicationsStore,
                    columns:[
                        {
                            header   : 'Medication',
                            width    : 100,
                            dataIndex: 'medication'
                        },
                        {
                            xtype:'datecolumn',
                            header   : 'Begin Date',
                            width    : 100,
                            format : 'Y-m-d',
                            dataIndex: 'begin_date'
                        },
                        {
                            xtype:'datecolumn',
                            header   : 'End Date',
                            flex     : 1,
                            format : 'Y-m-d',
                            dataIndex: 'end_date'
                        }
                    ]
                }
            ]
        });

        me.items = [ me.column1, me.column2, me.column3 ];


        me.listeners = {

            show:me.storesLoad
        };


        me.callParent(arguments);

    },

    storesLoad: function(){
        var me = this;
        me.patientImmuListStore.load({params: {pid: app.currPatient.pid}});
        me.patientAllergiesListStore.load({params: {pid: app.currPatient.pid}});
        me.patientMedicalIssuesStore.load({params: {pid: app.currPatient.pid}});
        me.patientSurgeryStore.load({params: {pid: app.currPatient.pid}});
        me.patientDentalStore.load({params: {pid: app.currPatient.pid}});
        me.patientMedicationsStore.load({params: {pid: app.currPatient.pid}});


    }
});