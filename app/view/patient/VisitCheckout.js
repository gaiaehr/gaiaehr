//******************************************************************************
// new.ejs.php
// New Patient Entry Form
// v0.0.1
// 
// Author: Ernest Rodriguez
// Modified: GI Technologies, 2011
// 
// GaiaEHR (Electronic Health Records) 2011
//******************************************************************************
Ext.define('App.view.patient.VisitCheckout', {
    extend: 'App.ux.RenderPanel',
    id: 'panelVisitCheckout',
    pageTitle: 'Visit Checkout',
    uses: ['App.ux.GridPanel'],
    initComponent: function(){
        var me = this;
        me.serviceStore = Ext.create('Ext.data.Store', {
            model: 'App.model.patient.CptCodes'
        });
        me.pageBody = Ext.create('Ext.panel.Panel', {
            itemId: 'visitpayment',
            defaults: {
                bodyStyle: 'padding:15px',
                bodyBorder: true,
                labelWidth: 110
            },
            items: [
                {
                    xtype: 'container',
                    layout: {
                        type: 'hbox',
                        align: 'stretch'
                    },
                    height: 400,
                    items: [
                        {
                            xtype: 'panel',
                            title: i18n('copay_payment'),
                            border: true,
                            frame: true,
                            bodyPadding: 10,
                            bodyBorder: true,
                            bodyStyle: 'background-color:#fff',
                            margin: 5,
                            flex: 2,
                            items: [
                                {
                                    xtype: 'container',
                                    itemId: 'serviceContainer',
                                    layout: 'anchor',
                                    items: [
                                        {
                                            xtype: 'grid',
                                            frame: false,
                                            border: false,
                                            flex: 1,
                                            maxHeight: 220,
                                            store: me.serviceStore,
                                            columns: [
                                                {
                                                    xtype: 'actioncolumn',
                                                    width: 20,
                                                    items: [
                                                        {
                                                            icon: 'resources/images/icons/delete.png',
                                                            tooltip: i18n('remove'),
                                                            scope: me,
                                                            handler: me.onRemoveService
                                                        }
                                                    ]
                                                },
                                                {
                                                    header: i18n('item'),
                                                    flex: 1,
                                                    dataIndex: 'code_text',
                                                    editor: {
                                                        xtype: 'livecptsearch',
                                                        allowBlank: false
                                                    }
                                                },
                                                {
                                                    header: i18n('paid'),
                                                    xtype: 'actioncolumn',
                                                    dataIndex: 'charge',
                                                    width: 35

                                                },
                                                {
                                                    header: i18n('charge'),
                                                    width: 95,
                                                    dataIndex: 'charge',
                                                    editor: {
                                                        xtype: 'textfield',
                                                        allowBlank: false
                                                    },
                                                    renderer: me.currencyRenderer
                                                }
                                            ],
                                            plugins: [
                                                Ext.create('Ext.grid.plugin.CellEditing', {
                                                    clicksToEdit: 2
                                                })
                                            ]
                                        }
                                    ]
                                },
                                {
                                    xtype: 'container',
                                    style: 'float:right',
                                    width: 208,
                                    defaults: {
                                        labelWidth: 108,
                                        labelAlign: 'right',
                                        action: 'receipt',
                                        width: 208,
                                        margin: '1 0'
                                    },
                                    items: [
                                        {
                                            fieldLabel: i18n('total'),
                                            xtype: 'mitos.currency',
                                            action: 'totalField'
                                        },
                                        {
                                            fieldLabel: i18n('amount_due'),
                                            xtype: 'mitos.currency'
                                        },
                                        {
                                            fieldLabel: i18n('payment_amount'),
                                            xtype: 'mitos.currency'
                                        },
                                        {
                                            fieldLabel: i18n('balance'),
                                            xtype: 'mitos.currency'
                                        }
                                    ]
                                }
                            ],
                            buttons: [
                                {
                                    text: i18n('add_service'),
                                    scope: me,
                                    handler: me.onNewService
                                },
                                '-',
                                {
                                    text: i18n('add_copay'),
                                    scope: me,
                                    handler: me.onAddCoPay
                                },
                                '->',
                                {
                                    text: i18n('add_payment'),
                                    scope: me,
                                    handler: me.onAddPaymentClick
                                },
                                {
                                    text: i18n('save'),
                                    scope: me,
                                    handler: me.onCheckoutSave
                                }
                            ]
                        },
                        {

                            xtype: 'documentsimplegrid',
                            title: i18n('documents'),
                            frame: true,
                            margin: '5 5 5 0',
                            flex: 1
                        }
                    ]
                },
                {
                    xtype: 'container',
                    layout: 'hbox',
                    defaults: { height: 195 },
                    items: [
                        {
                            xtype: 'form',
                            title: i18n('notes_and_reminders'),
                            frame: true,
                            flex: 2,
                            action: 'formnotes',
                            bodyPadding: 10,
                            margin: '0 5 5 5',
                            bodyBorder: true,
                            bodyStyle: 'background-color:#fff',
                            defaults: { anchor: '100%'},
                            items: [
                                {
                                    xtype: 'displayfield',
                                    fieldLabel: i18n('message'),
                                    name: 'message'
                                },
                                {
                                    xtype: 'textfield',
                                    fieldLabel: i18n('note'),
                                    name: 'new_note',
                                    action: 'notes'
                                },
                                {
                                    xtype: 'textfield',
                                    grow: true,
                                    fieldLabel: i18n('reminders'),
                                    name: 'new_reminder',
                                    action: 'notes'
                                }
                            ],
                            buttons: [
                                {
                                    text: i18n('save'),
                                    scope: me,
                                    handler: me.onCheckoutSaveNotes
                                },
                                '-',
                                {
                                    text: i18n('reset'),
                                    scope: me,
                                    handler: me.resetNotes
                                }
                            ]
                        },
                        {
                            xtype: 'form',
                            title: i18n('followup_information'),
                            frame: true,
                            flex: 1,
                            margin: '0 5 5 0',
                            bodyPadding: 10,
                            bodyBorder: true,
                            bodyStyle: 'background-color:#fff',
                            defaults: {
                                labelWidth: 110,
                                anchor: '100%'
                            },
                            items: [
                                {
                                    fieldLabel: i18n('time'),
                                    xtype: 'textfield',
                                    name: 'followup_time'
                                },
                                {
                                    fieldLabel: i18n('facility'),
                                    xtype: 'mitos.activefacilitiescombo',
                                    name: 'followup_facility'
                                }
                            ],
                            buttons: [
                                {
                                    text: i18n('schedule_appointment'),
                                    scope: me,
                                    handler: me.scheduleAppointment
                                }
                            ]
                        }
                    ]
                }
            ]
        });
        me.callParent(arguments);
    },
    onNewService: function(btn){
        var grid = btn.up('panel').down('grid'), store = grid.store;
        say(grid);
        say(store);
        store.add({code_text: ' ', charge: '20.00'});
    },
    onAddCoPay: function(btn){
        var grid = btn.up('panel').down('grid'), store = grid.store;
        store.add({code_text: 'Co-Pay', charge: '00.00'});
    },
    onAddService: function(){
        var totalField = this.query('[action="totalField"]')[0];
    },
    onRemoveService: function(grid, rowIndex){
        var me = this, totalField = me.query('[action="totalField"]')[0], totalVal = totalField.getValue(), rec = grid.getStore().getAt(rowIndex), newVal;
        me.serviceStore.remove(rec);
        newVal = totalVal - rec.data.charge;
        totalField.setValue(newVal);
    },
    cancelPrint: function(btn){
        var win = btn.up('window');
        win.close();
    },
    resetReceiptForm: function(){
        var fields = this.query('[action="receipt"]');
        for(var i = 0; i < fields.length; i++){
            fields[i].reset();
        }
    },
    resetNotes: function(){
        var fields = this.query('[action="notes"]');
        for(var i = 0; i < fields.length; i++){
            fields[i].reset();
        }
    },
    onAddPaymentClick: function(){
        app.onPaymentEntryWindow();
    },
    currencyRenderer: function(v){
        return ('<span style="float:right; padding-right:17px">$ ' + v + '</span>');
    },
    onCheckoutSaveNotes: function(){
        var me = this, form, values, container = me.query('form[action="formnotes"]');
        form = container[0].getForm();
        values = form.getFieldValues();
        values.date = Ext.Date.format(new Date(), 'Y-m-d H:i:s');
        values.pid = app.patient.pid;
        values.eid = me.eid;
        values.uid = app.user.id;
        values.type = 'administrative';
        if(form.isValid()){
            Patient.addPatientNoteAndReminder(values, function(provider, response){
                if(response.result.success){
                    app.msg('Sweet!', i18n('note_and_reminder'));
                }else{
                    app.msg('Oops!', i18n('note_entry_error'));
                }
            });
        }
    },
    scheduleAppointment: function(btn){
        var form = btn.up('form').getForm(), time = form.findField('followup_time').getValue(), facility = form.findField('followup_facility').getValue(), calendar = Ext.getCmp('app-calendar'), date;
        switch(time){
            case '1 Day':
                date = Ext.Date.add(new Date(), Ext.Date.DAY, 1);
                break;
            case '2 Days':
                date = Ext.Date.add(new Date(), Ext.Date.DAY, 2);
                break;
            case '3 Days':
                date = Ext.Date.add(new Date(), Ext.Date.DAY, 3);
                break;
            case '1 Week':
                date = Ext.Date.add(new Date(), Ext.Date.DAY, 7);
                break;
            case '2 Weeks':
                date = Ext.Date.add(new Date(), Ext.Date.DAY, 14);
                break;
            case '3 Weeks':
                date = Ext.Date.add(new Date(), Ext.Date.DAY, 21);
                break;
            case '1 Month':
                date = Ext.Date.add(new Date(), Ext.Date.MONTH, 1);
                break;
            case '2 Months':
                date = Ext.Date.add(new Date(), Ext.Date.MONTH, 2);
                break;
            case '3 Months':
                date = Ext.Date.add(new Date(), Ext.Date.MONTH, 3);
                break;
            case '4 Months':
                date = Ext.Date.add(new Date(), Ext.Date.MONTH, 4);
                break;
            case '5 Months':
                date = Ext.Date.add(new Date(), Ext.Date.MONTH, 5);
                break;
            case '6 Months':
                date = Ext.Date.add(new Date(), Ext.Date.MONTH, 6);
                break;
            case '1 Year':
                date = Ext.Date.add(new Date(), Ext.Date.YEAR, 1);
                break;
            case '2 Year':
                date = Ext.Date.add(new Date(), Ext.Date.YEAR, 2);
                break;
            default:
                date = new Date();
                break;
        }
        app.navigateTo('panelCalendar');
        calendar.facility = facility;
        calendar.setStartDate(date);
    },
    getVisitOtherInfo: function(){
        var me = this, forms, fields = [];
        forms = me.query('form');
        Encounter.getEncounterFollowUpInfoByEid(me.eid, function(provider, response){
            forms[1].getForm().setValues(response.result);
        });
        Encounter.getEncounterMessageByEid(me.eid, function(provider, response){
            forms[0].getForm().setValues(response.result);
        });
        for(var i = 0; i < forms.length; i++){
            fields.push(forms[i].getForm().getFields().items);
        }
    },
    setPanel: function(eid){
        this.eid = eid || null;
        this.query('documentsimplegrid')[0].loadDocs(eid);
        this.getVisitOtherInfo();
    },
    /**
     * This function is called from Viewport.js when
     * this panel is selected in the navigation panel.
     * place inside this function all the functions you want
     * to call every this panel becomes active
     */
    onActive: function(callback){
        var me = this;
        if(me.checkIfCurrPatient()){
            var patient = me.getCurrPatient();
            me.updateTitle(patient.name + ' - #' + patient.pid + ' (' + i18n('visit_checkout') + ')');
            callback(true);
        }else{
            callback(false);
            me.currPatientError();
        }
    }


}); //end Checkout class
