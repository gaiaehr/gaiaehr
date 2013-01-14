/**
 * Created with JetBrains PhpStorm.
 * User: Plushy
 * Date: 7/6/12
 * Time: 5:54 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.patient.ItemsToReview', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.itemstoreview',
    layout: 'column',
    frame: true,
    bodyPadding: 5,
    bodyBorder: true,
    bodyStyle: 'background-color:white',
    eid: null,
    initComponent: function(){
        var me = this;
        me.patientImmuListStore = Ext.create('App.store.patient.PatientImmunization');
        me.patientAllergiesListStore = Ext.create('App.store.patient.Allergies');
        me.patientMedicalIssuesStore = Ext.create('App.store.patient.MedicalIssues');
        me.patientSurgeryStore = Ext.create('App.store.patient.Surgery');
        me.patientDentalStore = Ext.create('App.store.patient.Dental');
        me.patientMedicationsStore = Ext.create('App.store.patient.Medications');
        me.column1 = Ext.create('Ext.container.Container', {
            columnWidth: 0.3333,
            defaults: {
                xtype: 'grid',
                margin: '0 5 5 0'
            },
            items: [
                {
                    title: i18n('immunizations'),
                    frame: true,
                    height: 180,
                    store: me.patientImmuListStore,
                    columns: [
                        {
                            header: i18n('immunization'),
                            width: 250,
                            dataIndex: 'immunization_name'
                        },
                        {
                            header: i18n('date'),
                            width: 90,
                            xtype: 'datecolumn',
                            format: 'Y-m-d',
                            dataIndex: 'administered_date'
                        },
                        {
                            header: i18n('notes'),
                            flex: 1,
                            dataIndex: 'note'
                        }
                    ]
                },
                {
                    title: i18n('allergies'),
                    frame: true,
                    height: 180,
                    store: me.patientAllergiesListStore,
                    columns: [
                        {
                            header: i18n('type'),
                            width: 100,
                            dataIndex: 'allergy_type'
                        },
                        {
                            header: i18n('name'),
                            width: 100,
                            dataIndex: 'allergy'
                        },
                        {
                            header: i18n('severity'),
                            flex: 1,
                            dataIndex: 'severity'
                        }
                    ]
                }
            ]
        });
        me.column2 = Ext.create('Ext.container.Container', {
            columnWidth: 0.3333,
            defaults: {
                xtype: 'grid',
                margin: '0 5 5 0'
            },
            items: [
                {
                    title: i18n('active_problems'),
                    frame: true,
                    height: 180,
                    store: me.patientMedicalIssuesStore,
                    columns: [
                        {
                            header: i18n('problem'),
                            width: 250,
                            dataIndex: 'code'
                        },
                        {
                            xtype: 'datecolumn',
                            header: i18n('begin_date'),
                            width: 90,
                            format: 'Y-m-d',
                            dataIndex: 'begin_date'
                        },
                        {
                            xtype: 'datecolumn',
                            header: i18n('end_date'),
                            flex: 1,
                            format: 'Y-m-d',
                            dataIndex: 'end_date'
                        }
                    ]
                },
                {
                    title: i18n('surgery'),
                    frame: true,
                    height: 180,
                    store: me.patientSurgeryStore,
                    columns: [
                        {
                            header: i18n('type'),
                            width: 250,
                            dataIndex: 'type'
                        },
                        {
                            xtype: 'datecolumn',
                            header: i18n('begin_date'),
                            width: 90,
                            format: 'Y-m-d',
                            dataIndex: 'begin_date'
                        },
                        {
                            xtype: 'datecolumn',
                            header: i18n('end_date'),
                            flex: 1,
                            format: 'Y-m-d',
                            dataIndex: 'end_date'
                        }
                    ]
                }
            ]
        });
        me.column3 = Ext.create('Ext.container.Container', {
            columnWidth: 0.3333,
            defaults: {
                xtype: 'grid',
                margin: '0 0 5 0'
            },
            items: [
                {
                    title: i18n('dental'),
                    frame: true,
                    height: 180,
                    store: me.patientDentalStore,
                    columns: [
                        {
                            header: i18n('title'),
                            width: 250,
                            dataIndex: 'title'
                        },
                        {
                            xtype: 'datecolumn',
                            header: i18n('begin_date'),
                            width: 90,
                            format: 'Y-m-d',
                            dataIndex: 'begin_date'
                        },
                        {
                            xtype: 'datecolumn',
                            header: i18n('end_date'),
                            flex: 1,
                            format: 'Y-m-d',
                            dataIndex: 'end_date'
                        }
                    ]
                },
                {
                    title: i18n('medications'),
                    frame: true,
                    height: 180,
                    store: me.patientMedicationsStore,
                    columns: [
                        {
                            header: i18n('medication'),
                            width: 250,
                            dataIndex: 'STR'
                        },
                        {
                            xtype: 'datecolumn',
                            header: i18n('begin_date'),
                            width: 90,
                            format: 'Y-m-d',
                            dataIndex: 'begin_date'
                        },
                        {
                            xtype: 'datecolumn',
                            header: i18n('end_date'),
                            flex: 1,
                            format: 'Y-m-d',
                            dataIndex: 'end_date'
                        }
                    ]
                }
            ]
        });

        me.column4 = Ext.create('Ext.form.Panel', {
            columnWidth: 0.329,
            border: false,
            items: [
	            {
		            xtype:'fieldset',
		            title:i18n('live_styles'),
		            items:[
			            {
				            fieldLabel: i18n('smoking_status'),
				            xtype: 'mitos.smokingstatuscombo',
				            labelWidth: 100,
				            width: 325,
				            name: 'review_smoke'
			            },
			            {
				            fieldLabel: i18n('alcohol'),
				            xtype: 'mitos.yesnocombo',
				            labelWidth: 100,
				            width: 325,
				            name: 'review_alcohol'
			            },
			            {
				            fieldLabel: i18n('pregnant'),
				            xtype: 'mitos.yesnonacombo',
				            labelWidth: 100,
				            width: 325,
				            name: 'review_pregnant'
			            }
		            ]
	            }

            ]
        });
        me.items = [ me.column1, me.column2, me.column3 , me.column4 ];
        me.buttons = [
            {
                text: i18n('review_all'),
                name: 'review',
                scope: me,
                handler: me.onReviewAll
            }
        ];
        me.listeners = {
            show: me.storesLoad
        };
        me.callParent(arguments);
    },
    storesLoad: function(){
        var me = this;
        me.patientImmuListStore.load({params: {pid: app.patient.pid}});
        me.patientAllergiesListStore.load({params: {pid: app.patient.pid}});
        me.patientMedicalIssuesStore.load({params: {pid: app.patient.pid}});
        me.patientSurgeryStore.load({params: {pid: app.patient.pid}});
        me.patientDentalStore.load({params: {pid: app.patient.pid}});
        me.patientMedicationsStore.load({params: {pid: app.patient.pid}});
        Medical.getEncounterReviewByEid(app.patient.eid, function(provider, response){
            me.column4.getForm().setValues(response.result);
        });
    },
    onReviewAll: function(){
        var me = this,
            panel = me.down('form'),
            form = panel.getForm(),
            values = form.getFieldValues();

        values.eid = app.patient.eid;
        if(form.isValid()){
            Medical.reviewAllMedicalWindowEncounter(values, function(provider, response){
                if(response.result.success){
                    app.msg('Sweet!', i18n('items_to_review_save_and_review'))
                }else{
                    app.msg('Oops!', i18n('items_to_review_entry_error'))
                }
            });
        }
    }
});