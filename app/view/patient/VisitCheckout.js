//******************************************************************************
// VisitCheckout.js
// v0.0.1
// 
// Author: Ernesto J Rodriguez
// Modified: GI Technologies, 2011
// 
// GaiaEHR (Electronic Health Records) 2011
//******************************************************************************
Ext.define('App.view.patient.VisitCheckout', {
	extend:'App.ux.RenderPanel',
	id:'panelVisitCheckout',
	pageTitle:'Visit Checkout',
	initComponent:function(){
		var me = this;

		me.VisitChargesStore = Ext.create('App.store.billing.VisitInvoice');

		me.pageBody = Ext.create('Ext.panel.Panel', {
			itemId:'visitpayment',
			defaults:{
				bodyStyle:'padding:15px',
				bodyBorder:true,
				labelWidth:110
			},
			layout:{
				type:'vbox',
				align:'stretch'
			},
			items:[
				{
					xtype:'container',
					flex:1,
					layout:{
						type:'hbox',
						align:'stretch'
					},
					items:[
						me.invoicePanel = Ext.widget('panel',{
							title:i18n('services_charges'),
							border:true,
							frame:true,
							bodyBorder:true,
							bodyStyle:'background-color:#fff',
							margin:'5 5 0 5',
							flex:2,
                            layout:{
                                type:'vbox',
                                align:'stretch'
                            },
							items:[
                                {
                                    xtype:'container',
                                    flex:1,
                                    autoScroll:true,
                                    items:[
                                        me.invoiceGrid = Ext.widget('grid', {
                                            frame:false,
                                            border:false,
                                            store:me.VisitChargesStore,
                                            enableColumnMove:false,
                                            enableColumnHide:false,
                                            sortableColumns:false,
                                            columns:[
                                                {
                                                    xtype:'actioncolumn',
                                                    width:20,
                                                    items:[
                                                        {
                                                            icon:'resources/images/icons/delete.png',
                                                            tooltip:i18n('remove'),
                                                            scope:me,
                                                            handler:me.onRemoveService
                                                        }
                                                    ]
                                                },
                                                {
                                                    header:i18n('item'),
                                                    dataIndex:'code_text_medium',
                                                    flex:1,
                                                    editor:{
                                                        xtype:'livecptsearch',
                                                        allowBlank:false
                                                    }
                                                },
                                                {
                                                    header:i18n('charge'),
                                                    width:80,
                                                    dataIndex:'charge',
                                                    align:'right',
                                                    editor:{
                                                        xtype:'textfield',
                                                        allowBlank:false
                                                    },
                                                    renderer:me.currencyRenderer
                                                }
                                            ],
                                            plugins:[
                                                Ext.create('Ext.grid.plugin.CellEditing', {
                                                    clicksToEdit:2
                                                })
                                            ]
                                        }),
                                        {
                                            xtype:'container',
                                            border:false,
                                            padding:1,
                                            height:200,
                                            items:[
                                                {
                                                    xtype:'container',
                                                    style:'float:right',
                                                    layout:'anchor',
                                                    width:150,
                                                    items:[
                                                        me.subtotal = Ext.widget('mitos.currency',{
                                                            fieldLabel:i18n('subtotal'),
                                                            labelWidth:70,
                                                            anchor:'100%',
                                                            labelAlign:'right',
                                                            margin:'1 0'
                                                        }),
                                                        me.tax = Ext.widget('mitos.percent',{
                                                            fieldLabel:i18n('tax'),
                                                            labelWidth:70,
                                                            anchor:'100%',
                                                            labelAlign:'right',
                                                            margin:'1 0'
                                                        }),
                                                        // -----------------------------------
                                                        me.total = Ext.widget('mitos.currency',{
                                                            fieldLabel:i18n('total'),
                                                            labelWidth:70,
                                                            anchor:'100%',
                                                            labelAlign:'right',
                                                            cls:'charges_total',
                                                            margin:'2 0 1 0'
                                                        }),
                                                        me.balance = Ext.widget('mitos.currency',{
                                                            fieldLabel:i18n('balance'),
                                                            labelWidth:70,
                                                            anchor:'100%',
                                                            labelAlign:'right',
                                                            cls:'charges_balance',
                                                            margin:'2 0 1 0'
                                                        })
                                                    ]
                                                }
                                            ]
                                        }
                                    ]
                                },
                                {
                                    xtype:'form',
                                    height:135,
                                    border: false,
                                    items:[
                                        {
                                            xtype:'fieldset',
                                            layout:'column',
                                            margin:'5 10',
                                            title:i18n('payment'),
                                            items:[
                                                {
                                                    xtype:'textarea',
                                                    anchor:'100%',
                                                    name:'notes',
                                                    columnWidth:.5,
                                                    height:85,
                                                    emptyText:i18n('additional_billing_notes')

                                                },
                                                {
                                                    xtype:'container',
                                                    layout:'anchor',
                                                    columnWidth:.5,
                                                    margin:'0 0 0 15',
                                                    items:[
                                                        {
                                                            fieldLabel: i18n('payment_method'),
                                                            xtype: 'mitos.paymentmethodcombo',
                                                            labelWidth: 100,
                                                            name: 'method',
                                                            anchor:'100%'
                                                        },
                                                        {
                                                            fieldLabel: i18n('reference_#'),
                                                            xtype: 'textfield',
                                                            labelWidth: 100,
                                                            name: 'reference',
                                                            anchor:'100%'
                                                        },
                                                        me.amount = Ext.widget('mitos.currency',{
                                                            fieldLabel: i18n('amount'),
                                                            xtype: 'mitos.currency',
                                                            labelWidth: 100,
                                                            name: 'amount',
                                                            anchor:'100%'
                                                        })
                                                    ]
                                                }

                                            ]
                                        }

                                    ]
                                }
							],
							buttons:[
								'->',
								{
									text:i18n('save_and_print'),
									scope:me,
                                    action:'saveprint',
									handler:me.onInvoiceSave
								},
								{
									text:i18n('save'),
									scope:me,
                                    action:'save',
									handler:me.onInvoiceSave
								}
							],
							listeners:{
								scope:me,
								render:me.onInvoicePanelRender
							}
						}),
						me.docsGrid = Ext.widget('documentsimplegrid', {
							title:i18n('documents'),
							frame:true,
							margin:'5 5 0 0',
							flex:1
						})
					]
				},
				{
					xtype:'container',
					layout:'hbox',
					defaults:{ height:170 },
					items:[
						me.notesReminders = Ext.widget('form', {
							title:i18n('notes_and_reminders'),
							frame:true,
							flex:2,
							action:'formnotes',
							bodyPadding:10,
							margin:'5 5 0 5',
							bodyBorder:true,
							bodyStyle:'background-color:#fff',
							defaults:{ anchor:'100%'},
							items:[
								{
									xtype:'displayfield',
									fieldLabel:i18n('message'),
									name:'message'
								},
								{
									xtype:'textfield',
									fieldLabel:i18n('note'),
									name:'new_note',
									action:'notes'
								},
								{
									xtype:'textfield',
									grow:true,
									fieldLabel:i18n('reminders'),
									name:'new_reminder',
									action:'notes'
								}
							],
							buttons:[
								{
									text:i18n('reset'),
									scope:me,
									handler:me.resetNotes
								},
								'-',
								{
									text:i18n('save'),
									scope:me,
									handler:me.onCheckoutSaveNotes
								}
							]
						}),
						me.followUp = Ext.widget('form', {
							title:i18n('followup_information'),
							frame:true,
							flex:1,
							margin:'5 5 5 0',
							bodyPadding:10,
							bodyBorder:true,
							bodyStyle:'background-color:#fff',
							defaults:{
								labelWidth:110,
								anchor:'100%'
							},
							items:[
								{
									fieldLabel:i18n('time'),
									xtype:'textfield',
									name:'followup_time'
								},
								{
									fieldLabel:i18n('facility'),
									xtype:'mitos.activefacilitiescombo',
									name:'followup_facility'
								}
							],
							buttons:[
								{
									text:i18n('schedule_appointment'),
									scope:me,
									handler:me.scheduleAppointment
								}
							]
						})
					]
				}
			]
		});

		me.callParent(arguments);
	},

	onInvoicePanelRender:function(grid){
		var me = this;

		grid.getHeader().add(
			{
				xtype:'button',
				text:i18n('service'),
				iconCls:'icoAdd',
				margin:'0 5 0 0',
				scope:me,
				handler:me.onNewService
			},
			{
				xtype:'button',
				text:i18n('copay'),
				iconCls:'icoAdd',
				margin:'0 5 0 0',
				scope:me,
				handler:me.onAddCoPay
			}
//			{
//				xtype:'button',
//				text:i18n('payment'),
//				iconCls:'icoAdd',
//				scope:me,
//				handler:me.onAddPaymentClick
//			}
		)
	},

	onNewService:function(btn){
		this.invoiceGrid.getStore().add({code_text:' ', charge:'20.00', ins:false});
	},

	onAddCoPay:function(btn){
		this.invoiceGrid.getStore().add({code_text:'Co-Pay', charge:'00.00', ins:false});
	},

	onAddService:function(){
		var totalField = this.query('[action="totalField"]')[0];
	},

	onRemoveService:function(grid, rowIndex){
		var me = this, totalField = me.query('[action="totalField"]')[0], totalVal = totalField.getValue(), rec = grid.getStore().getAt(rowIndex), newVal;
		me.VisitChargesStore.remove(rec);
		newVal = totalVal - rec.data.charge;
		totalField.setValue(newVal);
	},

    //***************************************************************
    //***************************************************************
    //***************************************************************

    onInvoiceSave:function(btn){

        say(this.VisitChargesStore);

        var me = this,
            params = {},
            print = btn.action == 'saveprint',
            services = me.VisitChargesStore.getRecords();

        params.pid = me.pid;
        params.eid = me.eid;
        params.services = services;



        AccBiliing.setVisitServicesVoucher(params, function(provider, respose){

            say(respose.result);

            say(print);

        });




    },



	cancelPrint:function(btn){
		var win = btn.up('window');
		win.close();
	},

	resetReceiptForm:function(){
		var fields = this.query('[action="receipt"]');
		for(var i = 0; i < fields.length; i++){
			fields[i].reset();
		}
	},

	resetNotes:function(){
		var fields = this.query('[action="notes"]');
		for(var i = 0; i < fields.length; i++){
			fields[i].reset();
		}
	},

	onAddPaymentClick:function(){
		app.onPaymentEntryWindow();
	},

	currencyRenderer:function(v){
		return ('<span style="float:right; padding-right:17px">' + app.currency + ' ' + v + '</span>');
	},

	onCheckoutSaveNotes:function(){
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
		for(var i = 0; i < forms.length; i++){
			fields.push(forms[i].getForm().getFields().items);
		}
	},

    calculatePercent:function(percent, value){
        return 100 * ( percent / value );
    },

	setPanel:function(){
		var me = this, subtotal = 0.00, total, tax = 6.00, balance = 0.00;
		me.docsGrid.loadDocs(me.eid);
		me.getVisitOtherInfo();

		me.VisitChargesStore.load({
			params:{
				pid:me.pid,
				eid:me.eid,
				uid:me.uid
			},
            callback:function(records, operation, success){

                for(var i=0; i < records.length; i++){
                    subtotal = eval(subtotal) + eval(records[i].data.charge);
                }

                me.subtotal.setValue(subtotal);
                me.tax.setValue(tax);
                me.amount.setValue(subtotal + (subtotal * tax / 100));
                me.updateTotalBalance();

            }
		})
	},

    updateTotalBalance:function(){

        say(this.tax.getValue());

        var me = this,
            total    = me.total.getValue(),
            subtotal = me.subtotal.getValue(),
            tax      = me.tax.getValue(),
            amount   = me.amount.getValue(),
            newTotal = subtotal + (subtotal * tax / 100);

        me.total.setValue(newTotal);
        me.balance.setValue(newTotal - amount);
    },

	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive:function(callback){
		var me = this;
		if(app.patient.pid && app.patient.eid){
			me.pid = app.patient.pid;
			me.eid = app.patient.eid;
			me.uid = app.user.id;
			me.updateTitle(app.patient.name + ' - #' + app.patient.pid + ' (' + i18n('visit_checkout') + ')');
			me.setPanel();
			callback(true);
		}else{
			callback(false);
			me.currPatientError();
		}
	}


});
