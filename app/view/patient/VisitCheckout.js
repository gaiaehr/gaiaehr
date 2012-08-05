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
	extend       : 'App.classes.RenderPanel',
	id           : 'panelVisitCheckout',
	pageTitle    : 'Visit Checkout',
	uses         : ['App.classes.GridPanel'],

    initComponent:function () {
        var me = this;

        me.serviceStore = Ext.create('Ext.data.Store', {
            model:'App.model.patient.CptCodes'
        });


        me.pageBody = Ext.create('Ext.panel.Panel', {
            itemId:'visitpayment',
            defaults:{
                bodyStyle : 'padding:15px',
                bodyBorder: true,
                labelWidth: 110
            },
            items:[
                {
                    xtype :'container',
                    layout:{
                        type : 'hbox',
                        align: 'stretch'
                    },
                    height: 400,
                    items:[
                        {
                            xtype : 'panel',
	                        title:'Co-Pay / Payment',
	                        border:true,
	                        frame:true,
	                        bodyPadding:10,
	                        bodyBorder: true,
	                        bodyStyle:'background-color:#fff',
                            margin: 5,
                            flex  : 2,
                            items: [
                                {
                                    xtype:'container',
                                    itemId:'serviceContainer',
                                    layout:'anchor',
                                    items:[
                                        {
                                            xtype  : 'grid',
                                            frame:false,
                                            border:false,
                                            flex   : 1,
                                            maxHeight:220,
                                            store: me.serviceStore,
                                            columns:[
                                                {
                                                    xtype:'actioncolumn',
                                                    width:20,
                                                    items: [
                                                        {
                                                            icon: 'ui_icons/delete.png',
                                                            tooltip: 'Remove',
                                                            scope:me,
                                                            handler: me.onRemoveService
                                                        }
                                                    ]
                                                },
                                                {
                                                    header:'Items',
                                                    flex:1,
                                                    dataIndex:'code_text',
                                                    editor: {
                                                        xtype: 'livecptsearch',
                                                        allowBlank: false
                                                    }
                                                },
                                                {
                                                    header:'Paid',
                                                    xtype:'actioncolumn',
                                                    dataIndex:'charge',
                                                    width:35

                                                },
                                                {
                                                    header: 'Charge',
                                                    width: 95,
                                                    dataIndex:'charge',
                                                    editor: {
                                                        xtype: 'textfield',
                                                        allowBlank: false
                                                    },
                                                    renderer:me.currencyRenderer
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
                                    xtype:'container',
                                    style: 'float:right',
                                    width:208,
                                    defaults: {
                                        labelWidth: 108,
                                        labelAlign:'right',
                                        action: 'receipt',
                                        width:208,
                                        margin: '1 0'
                                    },
                                    items:[
                                        {
                                            fieldLabel: 'Total',
                                            xtype     : 'mitos.currency',
                                            action    : 'totalField'
                                        },
                                        {
                                            fieldLabel: 'Amount Due',
                                            xtype     : 'mitos.currency'
                                        },

                                        {
                                            fieldLabel: 'Payment Amount',
                                            xtype     : 'mitos.currency'
                                        },
                                        {
                                            fieldLabel: 'Balance',
                                            xtype     : 'mitos.currency'
                                        }
                                    ]
                                }
                            ],
	                        buttons:[
                                {
                                    text:'Add Service',
                                    scope: me,
                                    handler:me.onNewService
                                },
                                '-',
		                        {
			                        text:'Add Co-Pay',
                                    scope:me,
			                        handler:me.onAddCoPay
		                        },
                                '->',
                                {
                                    text:'Add Payment',
                                    scope: me,
                                    handler:me.onAddPaymentClick
                                },
                                {
                                    text:'Save',
                                    scope:me,
                                    handler: me.onSave
                                }
	                        ]
                        },
                        {

	                        xtype:'documentsimplegrid',
	                        title:'Documents',
	                        frame:true,
                            margin : '5 5 5 0',
                            flex   : 1
                        }
                    ]
                },
                {
                    xtype:'container',
                    layout:'hbox',
                    defaults: { height:195 },
                    items:[
                        {
                            xtype: 'form',
                            title: 'Notes and Reminders',
	                        frame:true,
	                        flex:2,
                            action:'formnotes',
	                        bodyPadding:10,
	                        margin:'0 5 5 5',
                            bodyBorder: true,
                            bodyStyle:'background-color:#fff',
                            defaults: { anchor:'100%'},
                            items:[
                                {
                                    xtype     : 'displayfield',
                                    fieldLabel: 'Message',
                                    name: 'message'
                                },
                                {
                                    xtype     : 'textfield',
                                    fieldLabel: 'Note',
                                    name: 'new_note',
                                    action: 'notes'
                                },
                                {
                                    xtype     : 'textfield',
                                    grow      : true,
                                    fieldLabel: 'Reminders',
                                    name: 'new_reminder',
                                    action: 'notes'
                                }
                            ],
	                        buttons:[
                                {
                                    text:'Save',
                                    scope:me,
                                    handler: me.onSaveNotes
                                },
                                '-',
                                {
                                    text:'Reset',
                                    scope:me,
                                    handler:me.resetNotes
                                }
                            ]
                        },
                        {
                            xtype:'form',
                            title:'Follow-Up Information',
	                        frame:true,
	                        flex:1,
	                        margin:'0 5 5 0',
	                        bodyPadding:10,
                            bodyBorder: true,
                            bodyStyle:'background-color:#fff',
                            defaults:{
                                labelWidth:110,
                                anchor:'100%'
                            },
                            items:[
                                {
                                    fieldLabel: 'Time',
                                    xtype     : 'textfield',
                                    name     : 'followup_time'
                                },
                                {
                                    fieldLabel: 'Facility',
                                    xtype     : 'mitos.activefacilitiescombo',
	                                name:'followup_facility'
                                }
                            ],
	                        buttons:[
                                {
                                    text:'Schedule Appointment',
	                                scope:me,
                                    handler:me.scheduleAppointment
                                }
                            ]
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);


    },




    onNewService:function(btn){
        var grid = btn.up('panel').down('grid'),
            store = grid.store;

        say(grid);
        say(store);

        store.add({code_text:' ',charge:'20.00'});


    },

    onAddCoPay:function(btn){
        var grid = btn.up('panel').down('grid'),
            store = grid.store;

        store.add({code_text:'Co-Pay',charge:'00.00'});
    },

    onAddService:function(){
        var totalField = this.query('[action="totalField"]')[0];

    },

    onRemoveService:function(grid, rowIndex){
        var me = this,
            totalField = me.query('[action="totalField"]')[0],
            totalVal = totalField.getValue(),
            rec = grid.getStore().getAt(rowIndex),
            newVal;

        me.serviceStore.remove(rec);
        newVal = totalVal - rec.data.charge;
        totalField.setValue(newVal);

    },


    cancelPrint:function (btn) {
        var win = btn.up('window');
        win.close();
    },

	resetReceiptForm:function () {
        var fields = this.query('[action="receipt"]');
        Ext.each(fields, function(field){
            field.reset();
        });
    },

    resetNotes:function () {
        var fields = this.query('[action="notes"]');
   	    Ext.each(fields, function(field){
   			field.reset();
   	    });
    },

    onAddPaymentClick:function() {
        app.onPaymentEntryWindow();
    },

    currencyRenderer:function(v){
        return ('<span style="float:right; padding-right:17px">$ ' + v + '</span>');
    },

    onSaveNotes: function() {
        var me = this, form, values, container = me.query('form[action="formnotes"]');
        form = container[0].getForm();

        values = form.getFieldValues();
        values.date = Ext.Date.format(new Date(), 'Y-m-d H:i:s');
        values.pid = app.currPatient.pid;
        values.eid = me.eid;
        values.uid = app.user.id;
        values.type ='administrative';

        if(form.isValid()) {

            Patient.addPatientNoteAndReminder(values, function(provider, response){
                if(response.result.success){
	                app.msg('Sweet!','Note and Reminder');
                }else{
                    app.msg('Oops!','Note entry error');
                }
            });
        }
    },

	scheduleAppointment:function(btn){
		var form = btn.up('form').getForm(),
			time = form.findField('followup_time').getValue(),
			facility = form.findField('followup_facility').getValue(),
			calendar = Ext.getCmp('app-calendar'),
			date;


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

	getVisitOtherInfo:function(){
		var me = this, forms, fields = [];
		forms = me.query('form');

		Encounter.getEncounterFollowUpInfoByEid(me.eid, function(provider, response){
			forms[1].getForm().setValues(response.result);
		});

		Encounter.getEncounterMessageByEid(me.eid, function(provider, response){
			forms[0].getForm().setValues(response.result);
		});

		Ext.each(forms, function(form){
			fields.push(form.getForm().getFields().items);
		});
	},

	setPanel:function(eid){
		this.eid = eid || null;
		this.query('documentsimplegrid')[0].loadDocs(eid);

		this.getVisitOtherInfo();
	},

    /**
     * This function is called from MitosAPP.js when
     * this panel is selected in the navigation panel.
     * place inside this function all the functions you want
     * to call every this panel becomes active
     */
    onActive: function(callback) {
        var me = this;

        if(me.checkIfCurrPatient()) {
            var patient = me.getCurrPatient();
	        me.updateTitle(patient.name + ' - #' + patient.pid + ' (Visit Checkout)');
	        callback(true);
        } else {
            callback(false);
            me.currPatientError();
        }
    }


}); //end Checkout class
