/*
 GaiaEHR (Electronic Health Records)
 PaymentEntryWindow.js
 Payment Entry Window
 Copyright (C) 2012 certun, Inc.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
Ext.define('App.view.fees.PaymentEntryWindow', {
    extend: 'Ext.window.Window',
    title: i18n('add_new_payment'),
    closeAction: 'hide',
    modal: true,
    initComponent: function(){
        var me = this;
        me.items = [
            {
                xtype: 'form',
                defaults: {
                    margin: 5
                },
                border: false,
                height: 163,
                width: 747,
                items: [
                    {
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        items: [
                            {
                                fieldLabel: i18n('paying_entity'),
                                xtype: 'mitos.payingentitycombo',
                                name: 'paying_entity',
                                action: 'new_payment',
                                labelWidth: 98,
                                width: 220
                            },
                            {
                                xtype: 'patienlivetsearch',
                                fieldLabel: i18n('from'),
                                hideLabel: false,
                                name: 'payer_id',
                                action: 'new_payment',
                                anchor: null,
                                labelWidth: 42,
                                width: 300,
                                margin: '0 0 0 25'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: i18n('no'),
                                action: 'new_payment',
                                name: 'check_number',
                                labelWidth: 47,
                                width: 167,
                                margin: '0 0 0 25'
                            }
                        ]
                    },
                    {
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        items: [
                            {
                                fieldLabel: i18n('payment_method'),
                                xtype: 'mitos.paymentmethodcombo',
                                action: 'new_payment',
                                labelWidth: 98,
                                name: 'payment_method',
                                width: 220
                            },
                            {
                                xtype: 'mitos.billingfacilitiescombo',
                                fieldLabel: i18n('pay_to'),
                                action: 'new_payment',
                                labelWidth: 42,
                                name: 'pay_to',
                                width: 300,
                                margin: '0 0 0 25'
                            },
                            {
                                xtype: 'mitos.currency',
                                fieldLabel: i18n('amount'),
                                action: 'new_payment',
                                name: 'amount',
                                labelWidth: 47,
                                width: 167,
                                margin: '0 0 0 25',
                                enableKeyEvents: true
                            }
                        ]
                    },
                    {
                        fieldLabel: i18n('post_to_date'),
                        xtype: 'datefield',
                        name: 'post_to_date',
                        action: 'new_payment',
                        format: globals['date_display_format'],
                        labelWidth: 98,
                        width: 220
                    },
                    {
                        fieldLabel: i18n('note'),
                        xtype: 'textareafield',
                        grow: true,
                        action: 'new_payment',
                        name: 'note',
                        labelWidth: 98,
                        anchor: '100%'
                    }
                ]
            }
        ];
        me.buttons = [
            {
                text: i18n('save'),
                scope: me,
                handler: me.onPaymentSave
            },
            '-',
            {
                text: i18n('reset'),
                scope: me,
                handler: me.resetNewPayment
            }
        ];
        me.callParent(arguments);
    },
    onPaymentSave: function(){
        var me = this, panel, form, values;
        panel = me.down('form');
        form = panel.getForm();
        values = form.getFieldValues();
        values.date_created = Ext.Date.format(new Date(), 'Y-m-d H:i:s');
        if(form.isValid()){
            Fees.addPayment(values, function(provider, response){
                if(response.result.success){
                    form.reset();
                    me.hide();
                }else{
                    app.msg('Oops!', i18n('payment_entry_error'))
                }
            });
        }
    },
    resetNewPayment: function(){
        var fields = this.query('[action="new_payment"]');
        for(var i = 0; i < fields.length; i++){
            fields[i].reset();
        }
    }
});
