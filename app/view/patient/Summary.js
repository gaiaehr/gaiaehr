/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, inc.
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

Ext.define('App.view.patient.Summary', {
    extend: 'App.ux.RenderPanel',
    id: 'panelSummary',
    pageTitle: i18n('patient_summary'),
    pageLayout: {
        type: 'hbox',
        align: 'stretch'
    },
    requires: [
	   'Ext.XTemplate',
	   'Ext.ux.IFrame'
	],
    showRating:true,
    pid: null,
    demographicsData: null,
    initComponent: function(){
        var me = this;

        me.stores = [];
        me.stores.push(me.immuCheckListStore = Ext.create('App.store.patient.ImmunizationCheck'));
        me.stores.push(me.patientAllergiesListStore = Ext.create('App.store.patient.Allergies'));
        me.stores.push(me.patientMedicalIssuesStore = Ext.create('App.store.patient.MedicalIssues'));
//        me.stores.push(me.patientSurgeryStore = Ext.create('App.store.patient.Surgery'));
//        me.stores.push(me.patientDentalStore = Ext.create('App.store.patient.Dental'));
        me.stores.push(me.patientMedicationsStore = Ext.create('App.store.patient.Medications'));
        me.stores.push(me.patientCalendarEventsStore = Ext.create('App.store.patient.PatientCalendarEvents'));

        me.pageBody = [
	        me.tabPanel = Ext.widget('tabpanel',{
                flex: 1,
                margin: '3 0 0 0',
                bodyPadding: 0,
                frame: false,
                border: false,
                plain: true,
                itemId: 'centerPanel'
            }),
	        {
	            xtype: 'panel',
	            width: 250,
	            bodyPadding: 0,
	            frame: false,
	            border: false,
	            bodyBorder: true,
	            margin: '0 0 0 5',
	            defaults: {
	                layout: 'fit',
	                margin: '5 5 0 5'
	            },
	            listeners: {
	                scope: me,
	                render: me.rightColRender
	            },
	            items: [
	                {
	                    xtype: 'grid',
	                    title: i18n('active_medications'),
	                    itemId: 'MedicationsPanel',
	                    hideHeaders: true,
	                    store: me.patientMedicationsStore,
	                    columns: [
	                        {
	                            header: i18n('name'),
	                            dataIndex: 'STR',
	                            flex: 1
	                        },
	                        {
	                            text: i18n('alert'),
	                            width: 55,
	                            dataIndex: 'alert',
	                            renderer: me.boolRenderer
	                        }
	                    ]
	                },
	                {
	                    xtype: 'grid',
	                    title: i18n('immunizations'),
	                    itemId: 'ImmuPanel',
	                    hideHeaders: true,
	                    store: me.immuCheckListStore,
	                    region: 'center',
	                    columns: [
	                        {

	                            header: i18n('name'),
	                            dataIndex: 'vaccine_name',
	                            flex: 1
	                        },
	                        {
	                            text: i18n('alert'),
	                            width: 55,
	                            dataIndex: 'alert',
	                            renderer: me.alertRenderer
	                        }
	                    ]
	                },
	                {
	                    xtype: 'grid',
	                    title: i18n('allergies'),
	                    itemId: 'AllergiesPanel',
	                    hideHeaders: true,
	                    store: me.patientAllergiesListStore,
	                    region: 'center',
	                    columns: [
	                        {
	                            header: i18n('name'),
	                            dataIndex: 'allergy',
	                            flex: 1
	                        },
	                        {
	                            text: i18n('alert'),
	                            width: 55,
	                            dataIndex: 'alert',
	                            renderer: me.boolRenderer
	                        }
	                    ]
	                },
	                {
	                    xtype: 'grid',
	                    title: i18n('active_problems'),
	                    itemId: 'IssuesPanel',
	                    hideHeaders: true,
	                    store: me.patientMedicalIssuesStore,
	                    columns: [
	                        {

	                            header: i18n('name'),
	                            dataIndex: 'code',
	                            flex: 1
	                        },
	                        {
	                            text: i18n('alert'),
	                            width: 55,
	                            dataIndex: 'alert',
	                            renderer: me.boolRenderer
	                        }
	                    ]

	                },
//	                {
//	                    xtype: 'grid',
//	                    title: i18n('dental'),
//	                    itemId: 'DentalPanel',
//	                    hideHeaders: true,
//	                    store: me.patientDentalStore,
//	                    columns: [
//	                        {
//
//	                            header: i18n('name'),
//	                            dataIndex: 'description',
//	                            flex: 1
//
//	                        }
//	                    ]
//	                },
//	                {
//	                    xtype: 'grid',
//	                    title: i18n('surgeries'),
//	                    itemId: 'SurgeryPanel',
//	                    hideHeaders: true,
//	                    store: me.patientSurgeryStore,
//	                    columns: [
//	                        {
//	                            dataIndex: 'surgery',
//	                            flex: 1
//	                        }
//	                    ]
//	                },
	                {
	                    xtype: 'grid',
	                    title: i18n('appointments'),
	                    itemId: 'AppointmentsPanel',
	                    hideHeaders: true,
	                    disableSelection: true,
	                    store: me.patientCalendarEventsStore,
	                    columns: [
	                        {
	                            xtype: 'datecolumn',
	                            format: 'F j, Y, g:i a',
	                            dataIndex: 'start',
	                            flex: 1
	                        }
	                    ]
	                }
	            ]
            }
        ];
        if(acl['access_demographics']){
            me.tabPanel.add(
	            me.demographics = Ext.create('App.view.patient.Patient',{
		            newPatient:false,
		            title:i18n('demographics')
	            })
            );
        }
        if(acl['access_patient_disclosures']){
            me.stores.push(
	            me.patientDisclosuresStore = Ext.create('App.store.patient.Disclosures', {
                    autoSync: false
                })
            );
            me.tabPanel.add({
                xtype: 'grid',
                title: i18n('disclosures'),
                itemId: 'disclosuresPanel',
                bodyPadding: 0,
                store: me.patientDisclosuresStore,
                plugins: Ext.create('Ext.grid.plugin.RowEditing', {
                    autoCancel: false,
                    errorSummary: false,
                    clicksToEdit: 2
                }),
                columns: [
                    {
                        xtype: 'datecolumn',
                        format: 'Y-m-d',
                        text: i18n('date'),
                        dataIndex: 'date'
                    },
                    {
                        text: i18n('recipient_name'),
                        dataIndex: 'recipient',
                        width: 150,
                        editor: {
                            xtype: 'textfield'
                        }
                    },
                    {
                        header: i18n('type'),
                        dataIndex: 'type',
                        editor: {
                            xtype: 'textfield'
                        }
                    },
                    {
                        text: i18n('description'),
                        dataIndex: 'description',
                        flex: 1,
                        editor: {
                            xtype: 'textfield'
                        }
                    }
//                    {
//                        text: i18n('active'),
//                        dataIndex: 'active',
//                        width: 50,
//                        renderer: me.boolRenderer,
//                        editor: {
//                            xtype: 'checkbox'
//                        }
//                    }
                ],
                tbar: [
                    {
                        text: i18n('disclosure'),
                        iconCls: 'icoAdd',
	                    action:'disclosure',
                        handler: me.onAddNew
                    }
                ]
            });
        }
        if(acl['access_patient_notes']){
            me.stores.push(
	            me.patientNotesStore = Ext.create('App.store.patient.Notes', {
                    autoSync: false
                })
            );
            me.tabPanel.add({
                title: i18n('notes'),
                itemId: 'notesPanel',
                xtype: 'grid',
                bodyPadding: 0,
                store: me.patientNotesStore,
                plugins: Ext.create('Ext.grid.plugin.RowEditing', {
                    autoCancel: false,
                    errorSummary: false,
                    clicksToEdit: 2

                }),
                columns: [
                    {
                        xtype: 'datecolumn',
                        text: i18n('date'),
                        format: 'Y-m-d',
                        dataIndex: 'date'
                    },
                    {
                        header: i18n('type'),
                        dataIndex: 'type',
                        editor: {
                            xtype: 'textfield'
                        }
                    },
                    {
                        text: i18n('note'),
                        dataIndex: 'body',
                        flex: 1,
                        editor: {
                            xtype: 'textfield'
                        }
                    },
                    {
                        text: i18n('user'),
                        width: 225,
                        dataIndex: 'user_name'
                    }
                ],
                tbar: [
                    {
                        text: i18n('add_note'),
                        iconCls: 'icoAdd',
	                    action:'note',
                        handler: me.onAddNew
                    }
                ]
            });
        }
        if(acl['access_patient_reminders']){
            me.stores.push(me.patientRemindersStore = Ext.create('App.store.patient.Reminders', {
                autoSync: false
            }));
            me.tabPanel.add({
                title: i18n('reminders'),
                itemId: 'remindersPanel',
                xtype: 'grid',
                bodyPadding: 0,
                store: me.patientRemindersStore,
                plugins: Ext.create('Ext.grid.plugin.RowEditing', {
                        autoCancel: false,
                        errorSummary: false,
                        clicksToEdit: 2

                    }),
                columns: [
                    {
                        xtype: 'datecolumn',
                        text: i18n('date'),
                        format: 'Y-m-d',
                        dataIndex: 'date'
                    },
                    {
                        header: i18n('type'),
                        dataIndex: 'type',
                        editor: {
                            xtype: 'textfield'
                        }
                    },
                    {
                        text: i18n('note'),
                        dataIndex: 'body',
                        flex: 1,
                        editor: {
                            xtype: 'textfield'
                        }
                    },
                    {
                        text: i18n('user'),
                        width: 225,
                        dataIndex: 'user_name'
                    }
                ],
                tbar: [
                    {
                        text: i18n('add_reminder'),
                        iconCls: 'icoAdd',
	                    action:'reminder',
                        handler: me.onAddNew
                    }
                ]
            })
        }
        if(acl['access_patient_vitals']){
            me.stores.push(me.vitalsStore = Ext.create('App.store.patient.Vitals'));
            me.tabPanel.add({
                title: i18n('vitals'),
                autoScroll: true,
                bodyPadding: 0,
                items: {
                    xtype: 'vitalsdataview',
                    store: me.vitalsStore
                }
            })
        }
        if(acl['access_patient_history']){
            me.stores.push(me.encounterEventHistoryStore = Ext.create('App.store.patient.Encounters'));
            me.tabPanel.add({
                title: i18n('history'),
                xtype: 'grid',
                store: me.encounterEventHistoryStore,
                columns: [
                    {
                        header: i18n('date'),
                        dataIndex: 'service_date'
                    },
                    {
                        header: i18n('event'),
                        dataIndex: 'brief_description',
                        flex: true
                    },
                    {
                        header: i18n('visit_category'),
                        dataIndex: 'visit_category'
                    }
                ]
            })
        }
        if(acl['access_patient_documents']){
            me.stores.push(me.patientDocumentsStore = Ext.create('App.store.patient.PatientDocuments'));
            me.tabPanel.add({
                title: i18n('documents'),
                xtype: 'grid',
                store: me.patientDocumentsStore,
                columns: [
                    {
                        xtype: 'actioncolumn',
                        width: 60,
                        items: [

	                        {
		                        icon: 'resources/images/icons/icoLessImportant.png',
		                        tooltip: i18n('validate_file_integrity_hash'),
		                        handler: me.onDocumentHashCheck,
		                        getClass: function(){
			                        return 'x-grid-icon-padding';
		                        }
	                        },
                            {
                                icon: 'resources/images/icons/preview.png',
                                tooltip: i18n('view_document'),
                                handler: me.onDocumentView,
                                getClass: function(){
                                    return 'x-grid-icon-padding';
                                }
                            }
                        ]
                    },
                    {
                        header: i18n('type'),
                        dataIndex: 'docType'
                    },
                    {
                        xtype: 'datecolumn',
                        header: i18n('date'),
                        dataIndex: 'date',
                        format: 'Y-m-d'

                    },
                    {
                        header: i18n('title'),
                        dataIndex: 'title',
                        flex: 1,
                        editor: {
                            xtype: 'textfield',
                            action: 'title'
                        }
                    },
                    {
                        header: i18n('sha1_hash'),
                        dataIndex: 'hash',
                        width: 300
                    },
                    {
                        header: i18n('encrypted'),
                        dataIndex: 'encrypted',
                        width: 70,
	                    renderer:me.boolRenderer
                    }
                ],
                plugins: Ext.create('Ext.grid.plugin.RowEditing', {
                        autoCancel: true,
                        errorSummary: false,
                        clicksToEdit: 2

                    }),
                tbar: [
                    {
                        xtype: 'mitos.templatescombo',
                        fieldLabel: i18n('available_documents'),
                        width: 320,
                        labelWidth: 145,
                        margin: '10 0 0 10'

                    },
                    '-',
                    {
                        text: i18n('add_document'),
                        iconCls: 'icoAdd',
                        scope: me,
                        handler: me.newDoc
                    },
                    '->',
                    {
                        text: i18n('upload_document'),
                        scope: me,
                        handler: me.uploadADocument
                    },
                    {
                        xtype: 'panel',
                        action: 'upload',
                        region: 'center',
                        items: [
                            me.uploadWin = Ext.create('Ext.window.Window', {
                                draggable: false,
                                closable: false,
                                closeAction: 'hide',
                                items: [
                                    {
                                        xtype: 'form',
                                        bodyPadding: 10,
                                        width: 400,
                                        items: [
                                            {
                                                xtype: 'filefield',
                                                name: 'filePath',
                                                buttonText: i18n('select_a_file') + '...',
                                                anchor: '100%'
                                            },
                                            {
                                                xtype: 'checkbox',
                                                name: 'encrypted',
                                                boxLabel: i18n('encrypted')
                                            }
                                        ],
                                        api: {
                                            submit: DocumentHandler.uploadDocument
                                        }
                                    }
                                ],
                                buttons: [
                                    {
                                        text: i18n('cancel'),
                                        handler: function(){
                                            me.uploadWin.close();
                                        }
                                    },
                                    {
                                        text: i18n('upload'),
                                        scope: me,
                                        handler: me.onDocUpload
                                    }
                                ]
                            })
                        ]
                    }
                ]
            })
        }
        if(acl['access_patient_preventive_care_alerts']){
            me.stores.push(
	            me.patientsDismissedAlerts = Ext.create('App.store.patient.DismissedAlerts', {
                    //listeners
                })
            );
            me.tabPanel.add({
                title: i18n('dismissed_preventive_care_alerts'),
                xtype: 'grid',
                store: me.patientsDismissedAlerts,
                columns: [
                    {
                        header: i18n('description'),
                        dataIndex: 'description'
                    },
                    {
                        xtype: 'datecolumn',
                        header: i18n('date'),
                        dataIndex: 'date',
                        format: 'Y-m-d'

                    },
                    {
                        header: i18n('reason'),
                        dataIndex: 'reason',
                        flex: true

                    },
                    {
                        header: i18n('observation'),
                        dataIndex: 'observation',
                        flex: true
                    },
                    {
                        header: i18n('dismissed'),
                        dataIndex: 'dismiss',
                        width: 60,
                        renderer: me.boolRenderer
                    }
                ],
                plugins: Ext.create('App.ux.grid.RowFormEditing', {
                    autoCancel: false,
                    errorSummary: false,
                    clicksToEdit: 1,
                    formItems: [
                        {
                            title: 'general',
                            xtype: 'container',
                            padding: 10,
                            layout: 'vbox',
                            items: [
                                {
                                    /**
                                     * Line one
                                     */
                                    xtype: 'fieldcontainer',
                                    layout: 'hbox',
                                    defaults: {
                                        margin: '0 10 5 0'
                                    },
                                    items: [
                                        {
                                            xtype: 'textfield',
                                            name: 'reason',
                                            fieldLabel: i18n('reason'),
                                            width: 585,
                                            labelWidth: 70,
                                            action: 'reason'
                                        }
                                    ]

                                },
                                {
                                    /**
                                     * Line two
                                     */
                                    xtype: 'fieldcontainer',
                                    layout: 'hbox',
                                    defaults: {
                                        margin: '0 10 5 0'
                                    },
                                    items: [
                                        {
                                            xtype: 'textfield',
                                            fieldLabel: i18n('observation'),
                                            name: 'observation',
                                            width: 250,
                                            labelWidth: 70,
                                            action: 'observation'
                                        },
                                        {
                                            fieldLabel: i18n('date'),
                                            xtype: 'datefield',
                                            action: 'date',
                                            width: 200,
                                            labelWidth: 40,
                                            format: globals['date_display_format'],
                                            name: 'date'

                                        },
                                        {
                                            xtype: 'checkboxfield',
                                            name: 'dismiss',
                                            fieldLabel: i18n('dismiss_alert')

                                        }
                                    ]

                                }
                            ]
                        }
                    ]

                })
            })
        }
        if(acl['access_patient_billing']){
            me.tabPanel.add({
                xtype: 'panel',
                action: 'balancePanel',
                itemId: 'balancePanel',
                title: i18n('billing'),
                html: i18n('account_balance') + ': '

            });
        }

        //if(acl['access_patient_reports']){
            me.reportPanel = me.tabPanel.add({
                xtype: 'panel',
                title: i18n('reports'),
                tbar:[
                    {
                        xtype:'container',
                        layout:'vbox',
                        defaultType:'button',
                        items:[
                            {
                                text:'View CCR',
                                margin:'0 0 5 0',
                                handler:function(){
                                    me.reportPanel.remove(me.miframe);
                                    me.reportPanel.add(me.miframe = Ext.create('App.ux.ManagedIframe',{
                                        src: 'http://localhost/gaiaehr/dataProvider/CCR.php?action=generate&raw=no&pid=' + me.pid
                                    }));
                                    // GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
                                    app.AuditLog('Patient summary CCR viewed');
                                }
                            },
                            {
                                text:'View CCD',
                                handler:function(){
                                    me.reportPanel.remove(me.miframe);
                                    me.reportPanel.add(me.miframe = Ext.create('App.ux.ManagedIframe',{
                                        src: 'http://localhost/gaiaehr/dataProvider/CCR.php?action=viewccd&raw=no&pid=' + me.pid
                                    }));
                                    // GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
                                    app.AuditLog('Patient summary CCD viewed');
                                }
                            }
                        ]
                    },'-',
                    {
                        xtype:'container',
                        layout:'vbox',
                        defaultType:'button',
                        items:[
                            {
                                text:'Raw CCR',
                                margin:'0 0 5 0',
                                handler:function(){
                                    me.reportPanel.remove(me.miframe);
                                    me.reportPanel.add(me.miframe = Ext.create('App.ux.ManagedIframe',{
                                        src: 'http://localhost/gaiaehr/dataProvider/CCR.php?action=generate&raw=yes&pid=' + me.pid
                                    }));
                                    // GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
                                    app.AuditLog('Patient summary raw CCR viewed');
                                }
                            },
                            {
                                text:'Raw CCD',
                                handler:function(){
                                    me.reportPanel.remove(me.miframe);
                                    me.reportPanel.add(me.miframe = Ext.create('App.ux.ManagedIframe',{
                                        src: 'http://localhost/gaiaehr/dataProvider/CCR.php?action=viewccd&raw=yes&pid=' + me.pid
                                    }));
                                    // GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
                                    app.AuditLog('Patient summary raw CCD viewed');
                                }
                            }
                        ]
                    },'-',
                    {
                        xtype:'container',
                        layout:'vbox',
                        defaultType:'button',
                        items:[
                            {
                                text:'Export CCR',
                                margin:'0 0 5 0',
                                handler:function(){
                                    me.reportPanel.remove(me.miframe);
                                    me.reportPanel.add(me.miframe = Ext.create('App.ux.ManagedIframe',{
                                        src: 'http://localhost/gaiaehr/dataProvider/CCR.php?action=generate&raw=yes&pid=' + me.pid
                                    }));
                                    // GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
                                    app.AuditLog('Patient summary CCR exported');
                                }
                            },
                            {
                                text:'Export CCD',
                                handler:function(){
                                    me.reportPanel.remove(me.miframe);
                                    me.reportPanel.add(me.miframe = Ext.create('App.ux.ManagedIframe',{
                                        src: 'http://localhost/gaiaehr/dataProvider/CCR.php?action=viewccd&raw=yes&pid=' + me.pid
                                    }));
                                    // GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
                                    app.AuditLog('Patient summary CCD exported');
                                }
                            }
                        ]
                    },'-',
                    {
                        text: 'Print',
                        iconCls: 'icon-print',
                        handler : function(){
//                           	trg.focus();
//                           	trg.print();
                        }
                    }
                ]
             });
        //}

        me.callParent();
    },

	onAddNew:function(btn){
		var grid = btn.up('grid'),
			store = grid.store,
			record;

		if(btn.action == 'disclosure'){
			record = {
				date: new Date(),
				pid: app.patient.pid,
				active: 1
			};
		}else if(btn.action == 'note' || btn.action == 'reminder'){
			record = {
				date: new Date(),
				pid: app.patient.pid,
				uid: app.user.id,
				eid: app.patient.eid
			};
		}

		grid.plugins[0].cancelEdit();
		store.insert(0,record);
		grid.plugins[0].startEdit(0, 0);
	},

	onDocumentHashCheck: function(grid, rowIndex){
        var rec = grid.getStore().getAt(rowIndex),
	        success;
		DocumentHandler.checkDocHash(rec.data, function(provider, response){
			success = response.result.success;
			app.msg(
				i18n(success ? 'sweet':'oops'),
				i18n(success ? 'hash_validation_passed':'hash_validation_failed') + '<br>'+ response.result.msg,
				!success
			);

        });
    },

    onDocumentView: function(grid, rowIndex){
        var rec = grid.getStore().getAt(rowIndex);
        app.onDocumentView(rec.data.id);
    },

    uploadADocument: function(){
        var me = this, previewPanel = me.query('[action="upload"]')[0];
        me.uploadWin.show();
        me.uploadWin.alignTo(previewPanel.el.dom, 'tr-tr', [-5, 30])
    },

    onDocUpload: function(btn){
        var me = this,
	        form = me.uploadWin.down('form').getForm(),
	        win = btn.up('window'),
	        params = {
			    pid: me.pid,
		        encrypted: form.findField('encrypted').getValue()? 1 : 0,
			    docType: 'UploadDoc'
		    };

        if(form.isValid()){
            form.submit({
                waitMsg: i18n('uploading_document') + '...',
                params: params,
                success: function(fp, o){
                    win.close();
                    me.patientDocumentsStore.load({params:{pid: me.pid}});
                }
            });
        }
    },


    newDoc: function(btn){
        app.onNewDocumentsWin(btn.action)
    },

    rightColRender: function(panel){
        var me = this;
        panel.getComponent('ImmuPanel').header.add(
	        {
                xtype: 'button',
                text: i18n('details'),
                action: 'immunization',
                scope: me,
                handler: me.medicalWin

            }
        );
        panel.getComponent('MedicationsPanel').header.add(
	        {
                xtype: 'button',
                text: i18n('details'),
                action: 'medications',
                scope: me,
                handler: me.medicalWin

            }
        );
        panel.getComponent('AllergiesPanel').header.add(
	        {
                xtype: 'button',
                text: i18n('details'),
                action: 'allergies',
                scope: me,
                handler: me.medicalWin
            }
        );
        panel.getComponent('IssuesPanel').header.add(
	        {
                xtype: 'button',
                text: i18n('details'),
                action: 'issues',
                scope: me,
                handler: me.medicalWin
            }
        );
//        panel.getComponent('DentalPanel').header.add(
//	        {
//                xtype: 'button',
//                text: i18n('details'),
//                action: 'dental',
//                scope: me,
//                handler: me.medicalWin
//            }
//        );
//        panel.getComponent('SurgeryPanel').header.add(
//	        {
//                xtype: 'button',
//                text: i18n('details'),
//                action: 'surgery',
//                scope: me,
//                handler: me.medicalWin
//            }
//        );
    },
    medicalWin: function(btn){
        app.onMedicalWin(btn);
    },


    /**
     * verify the patient required info and add a yellow background if empty
     */
    verifyPatientRequiredInfo: function(){
        var me = this, formPanel = me.query('[action="demoFormPanel"]')[0], field;
        me.patientAlertsStore.load({
            scope: me,
            params: {
                pid: me.pid
            },
            callback: function(records, operation, success){
                for(var i = 0; i < records.length; i++){
                    field = formPanel.getForm().findField(records[i].data.name);
                    if(records[i].data.val){
                        if(field) field.removeCls('x-field-yellow');
                    }else{
                        if(field) field.addCls('x-field-yellow');
                    }
                }
            }
        });
    },

    /**
     * load all the stores in the summaryStores array
     */
    loadStores: function(){
        var me = this;
        for(var i = 0; i < me.stores.length; i++){
	        me.stores[i].load({
                params: {
                    pid: me.pid
                },
		        filters:[
			        {
				        property: 'pid',
				        value: me.pid
			        }
		        ],
		        callback:function(){
			        me.el.unmask();
		        }
            });
        }
    },
    /**
     * This function is called from Viewport.js when
     * this panel is selected in the navigation panel.
     * place inside this function all the functions you want
     * to call every this panel becomes active
     */
    onActive: function(callback){
        var me = this,
	        billingPanel;

        if(me.checkIfCurrPatient()){
	        me.el.mask(i18n('loading...'));
            /**
             * convenient way to refer to current pid within this panel
             * @type {*}
             */
            me.pid = app.patient.pid;
            /**
             * get current set patient info
             * @type {*}
             */
            var patient = app.patient;
            /**
             * update panel main title to reflect the patient name and if the patient is read only
             */
            me.updateTitle(patient.name + ' #' + patient.pid + ' - ' + patient.age.str + ' - (' + i18n('patient_summary') + ')', app.patient.readOnly, null);
            /**
             * verify if the patient is on read only mode
             */
            me.setReadOnly(app.patient.readOnly);
            me.setButtonsDisabled(me.query('button[action="readOnly"]'));

            if(acl['access_demographics']) me.demographics.loadPatient(me.pid);

            /**
             * get billing info if user has access
             */
            if(acl['access_patient_billing']){
                billingPanel = me.tabPanel.getComponent('balancePanel');
                Fees.getPatientBalance(
	                {
                        pid: me.pid
                    },
	                function(balance){
                        billingPanel.update(i18n('account_balance') + ': $' + balance);
                    }
                );
            }
            /**
             * reset tab panel to the first tap
             */
            me.tabPanel.setActiveTab(0);
            /**
             * load all the stores
             */
            me.loadStores();

            if(typeof callback == 'function') callback(true);
        }else{
            callback(false);
            me.pid = null;
            me.currPatientError();
        }
    }
});