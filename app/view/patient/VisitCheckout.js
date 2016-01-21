/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
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

Ext.define('App.view.patient.VisitCheckout', {
	extend:'App.ux.RenderPanel',
	id:'panelVisitCheckout',
	pageTitle:'Visit Checkout',
    showRating:true,
	initComponent:function(){
		var me = this;

		me.VisitVoucherStore = Ext.create('App.store.account.Voucher',{
			remoteFilter:true
		});

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
							title:_('services_charges'),
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
                                                            tooltip:_('remove'),
                                                            scope:me,
                                                            handler:me.onRemoveCharge
                                                        }
                                                    ]
                                                },
                                                {
                                                    header:_('item'),
                                                    dataIndex:'name',
                                                    flex:1,
                                                    editor:{
                                                        xtype:'livecptsearch',
                                                        allowBlank:false
                                                    }
                                                },
                                                {
                                                    header:_('price'),
                                                    width:80,
                                                    dataIndex:'amountOriginal',
                                                    align:'right',
                                                    renderer:me.currencyRenderer
                                                },
                                                {
                                                    header:_('charge'),
                                                    width:80,
                                                    dataIndex:'amount',
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
                                                        me.total = Ext.widget('mitos.currency',{
                                                            fieldLabel:_('total'),
                                                            labelWidth:70,
                                                            anchor:'100%',
                                                            labelAlign:'right',
                                                            cls:'charges_total',
                                                            margin:'2 0 1 0'
                                                        }),
                                                        me.paid = Ext.widget('mitos.currency',{
                                                            fieldLabel:_('paid'),
                                                            labelWidth:70,
                                                            anchor:'100%',
                                                            labelAlign:'right',
                                                            margin:'1 0'
                                                        }),
                                                        me.balance = Ext.widget('mitos.currency',{
                                                            fieldLabel:_('balance'),
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
                                            title:_('payment'),
                                            items:[
                                                me.billingNotes = Ext.widget('textarea',{
                                                    xtype:'textarea',
                                                    anchor:'100%',
                                                    name:'notes',
                                                    columnWidth:.5,
                                                    height:85,
                                                    emptyText:_('additional_billing_notes')

                                                }),
                                                {
                                                    xtype:'container',
                                                    layout:'anchor',
                                                    columnWidth:.5,
                                                    margin:'0 0 0 15',
                                                    items:[
                                                        me.method = Ext.widget('mitos.paymentmethodcombo',{
                                                            fieldLabel: _('payment_method'),
                                                            labelWidth: 100,
                                                            name: 'method',
                                                            anchor:'100%'
                                                        }),
                                                        me.ref = Ext.widget('textfield',{
                                                            fieldLabel: _('reference_#'),
                                                            labelWidth: 100,
                                                            name: 'reference',
                                                            anchor:'100%'
                                                        }),
                                                        me.amount = Ext.widget('mitos.currency',{
                                                            fieldLabel: _('amount'),
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
									text:_('save_and_print'),
									scope:me,
                                    action:'saveprint',
									handler:me.onInvoiceSave
								},
								{
									text:_('save'),
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
						me.docsGrid = Ext.widget('encounterdocumentsgrid', {
							title:_('documents'),
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
							title:_('notes_and_reminders'),
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
									fieldLabel:_('message'),
									name:'message'
								},
								{
									xtype:'textfield',
									fieldLabel:_('note'),
									name:'new_note',
									action:'notes'
								},
								{
									xtype:'textfield',
									grow:true,
									fieldLabel:_('reminders'),
									name:'new_reminder',
									action:'notes'
								}
							],
							buttons:[
								{
									text:_('reset'),
									scope:me,
									handler:me.resetNotes
								},
								'-',
								{
									text:_('save'),
									scope:me,
									handler:me.onCheckoutSaveNotes
								}
							]
						}),
						me.followUp = Ext.widget('form', {
							title:_('followup_information'),
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
									fieldLabel:_('time'),
									xtype:'textfield',
									name:'followup_time'
								},
								{
									fieldLabel:_('facility'),
									xtype:'activefacilitiescombo',
									name:'followup_facility'
								}
							],
							buttons:[
								{
									text:_('schedule_appointment'),
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
				text:_('service'),
				iconCls:'icoAdd',
				margin:'0 5 0 0',
				scope:me,
				handler:me.onNewService
			},
			{
				xtype:'button',
				text:_('copay'),
				iconCls:'icoAdd',
				margin:'0 5 0 0',
				scope:me,
				handler:me.onAddCoPay
			}
//			{
//				xtype:'button',
//				text:_('payment'),
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

	onRemoveCharge:function(grid, rowIndex){
		var me = this,
			store = grid.getStore(),
            record = store.getAt(rowIndex);
		store.remove(record);
		me.updateTotalBalance();
	},

    //***************************************************************
    //***************************************************************
    //***************************************************************

    onInvoiceSave:function(btn){

        var me = this,
            params = {},
            print = btn.action == 'saveprint',
            servicesRec = me.VisitVoucherStore.data.items,
            lines = [];

        for(var i=0; i < servicesRec.length; i++){
            lines.push(servicesRec[i].data);
        }

        params.pid = me.pid;
        params.eid = me.eid;
        params.lines = lines;
        params.payment = {
            amount: me.amount.getValue(),
            method: me.method.getValue(),
            notes: me.billingNotes.getValue(),
            ref: me.ref.getValue()
        };

        AccBilling.setVisitVoucher(params, function(provider, response){


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

		// TODO: fix this method...

		if(form.isValid()){
			Patient.addPatientNoteAndReminder(values, function(provider, response){
				if(response.result.success){
					app.msg('Sweet!', _('note_and_reminder'));
				}else{
					app.msg('Oops!', _('note_entry_error'));
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

	setVoucher:function(){
		var me = this;
		me.docsGrid.loadDocs(me.eid);

		me.getVisitOtherInfo();

		me.VisitVoucherStore.load({
			filters:[
				{
					property:'encounterId',
					value : me.eid
				},
				{
					property: 'type',
					value: 'visit'
				}
			],
            callback:function(records, operation, success){

	            var voucher = records[0];

				if(voucher){

					voucher.voucherlines().load({
						callback:function(){
							// say('hello');
						}
					});

				}else{

					AccVoucher.getVisitCheckOutCharges({pid:me.pid,eid:me.eid},function(provicer,response){
						var charges = response.result;
						if(charges.length > 0){
							var rec = me.VisitVoucherStore.add({
									encounterId:me.eid,
									date:new Date(),
									type:'visit'
								}),
								store = rec[0].voucherlines();

							me.invoiceGrid.reconfigure(store);
							for(var i=0; i < charges.length; i++){
								store.add(charges[i]);
							}

			                me.paid.setValue(0.00);
                            me.updateTotalBalance();
						}
					});
				}
            }
		})
	},

    updateTotalBalance:function(){
        var me = this,
            amount   = me.amount.getValue(),
            paid   = me.paid.getValue(),
            records = me.invoiceGrid.getStore().data.items,
            form = me.invoicePanel.down('form'),
            total = 0.00, balance;

        for(var i=0; i < records.length; i++){
            total = eval(total) + eval(records[i].data.amount);
        }
        me.total.setValue(total);
        balance = total - paid;
        me.balance.setValue(balance);
        me.setPaid(balance == 0.00 && records.length > 0);
    },

	setPaid:function(paid){
		var form = this.invoicePanel.down('form');
		if(paid){
			form.addBodyCls('paid');
			form.down('fieldset').setVisible(false);
		}else{
			form.removeBodyCls('paid');
			form.down('fieldset').setVisible(true);
		}
	},

	setVisitPanel:function(){
		this.pid = app.patient.pid;
		this.eid = app.patient.eid;
		this.uid = app.user.id;
		this.updateTitle(app.patient.name + ' - #' + app.patient.pid + ' (' + _('visit_checkout') + ')');
		this.setVoucher();
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
			me.setVisitPanel();
			callback(true);
		}else{
			callback(false);
			me.currPatientError();
		}
	}


});
