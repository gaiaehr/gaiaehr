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
Ext.define('App.view.patientfile.VisitCheckout', {
	extend       : 'App.classes.RenderPanel',
	id           : 'panelVisitCheckout',
	pageTitle    : 'Visit Checkout',
	uses         : ['App.classes.GridPanel'],

    initComponent:function () {
        var me = this;

        me.serviceStore = Ext.create('Ext.data.Store', {
            model:'App.model.patientfile.CptCodes'
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
                    height: 410,
                    items:[
                        {
                            xtype : 'form',
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
                            xtype  : 'grid',
                            title  : 'Orders',
	                        frame:true,
                            margin : '5 5 5 0',
                            flex   : 1,
                            columns:[
                                {
                                    header:'Code'
                                },
                                {
                                    header: 'Description',
                                    flex  : 1
                                }
                            ]
                        }
                    ]
                },
                {
                    xtype:'container',
                    layout:'hbox',
                    defaults: { height:185 },
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
                                    xtype     : 'textfield',
                                    fieldLabel: 'Note',
                                    name: 'note_body',
                                    action: 'notes'
                                },
                                {
                                    xtype     : 'textareafield',
                                    grow      : true,
                                    fieldLabel: 'Reminders',
                                    name: 'reminder_body',
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
                                    xtype     : 'textfield'
                                },
                                {
                                    fieldLabel: 'Facility',
                                    xtype     : 'mitos.facilitiescombo'
                                }
                            ],
	                        buttons:[
                                {
                                    text:'Schedule Appointment',
                                    handler:function(){
                                        //app.onExtCalWin();
                                    }
                                }
                            ]
                        }
                    ]
                }
            ]
        });

        me.printWindow = Ext.create('Ext.window.Window', {
            title      : 'Printing Options',
            closeAction: 'hide',
            closable   : false,
            modal      : true,
            items:[
                {
                    xtype   :'form',
                    height  : 200,
                    width   : 300,
                    defaults: { margin:5 },
                    columnWidth:.5,
                    border  : false,
                    items:[
 	                    {
                            xtype   :'checkboxgroup',
                            width   : 200,
                            height  : 40,
                            defaults:{
                                xtype:'checkboxfield'
                            },
                            items:[
                                {
                                    boxLabel: 'Receipt'
                                },
                                {
                                    boxLabel: 'Orders'
                                }
                            ]
 	                    }
 	                ],
 	                buttons:[
                        '->',
 	                    {
 	                        text:'Print'
                        },
 	                    '-',
 	                    {
 	                        text:'Cancel',
                            scope:me,
                            handler:me.cancelPrint
 	                    }
 	                ]
 	            }
            ]
        });

        me.callParent(arguments);


    },




    onNewService:function(btn){
        var me = this,
            grid = btn.up('panel').down('grid'),
            store = grid.store;

        say(grid);
        say(store);

            store.add({code_text:' ',charge:'20.00'});



//        var me = this,
//            container = me.down('form').getComponent('serviceContainer'),
//            serviceField;
//
//        serviceField = Ext.create('Ext.form.FieldContainer',{
//            layout:'hbox',
//            items: [{
//                xtype: 'textfield',
//                flex: 1
//            }, {
//                xtype: 'textfield'
//            }]
//        });

        //container.add(serviceField)

    },

    onAddCoPay:function(btn){
        var me = this,
            grid = btn.up('panel').down('grid'),
            store = grid.store;

        store.add({code_text:'Co-Pay',charge:'00.00'});
    },

    onAddService:function(){
        var totalField = this.query('[action="totalField"]')[0];

    },

    onRemoveService:function(grid, rowIndex, colIndex){
        var me = this,
            totalField = me.query('[action="totalField"]')[0],
            totalVal = totalField.getValue(),
            rec = grid.getStore().getAt(rowIndex),
            newVal;

        me.serviceStore.remove(rec);
        newVal = totalVal - rec.data.charge;
        totalField.setValue(newVal);

    },

    onPrintClick:function () {
        this.printWindow.show();
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
        var me = this, form, values, container = this.query('form[action="formnotes"]');
        form = container[0].getForm();

        values = form.getFieldValues();
        values.date= Ext.Date.format(new Date(), 'Y-m-d H:i:s');
        values.pid= app.currPatient.pid;
        values.eid= app.currEncounterId;
        values.uid= app.user.id;
        values.type='administrative';

        if(form.isValid()) {

            Patient.addNote(values, function(provider, response){
                if(response.result.success){
                    form.reset();
                }else{
                    app.msg('Oops!','Notes entry error')
                }
            });
        }
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
