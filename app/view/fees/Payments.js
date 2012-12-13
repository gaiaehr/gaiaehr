/**
	GaiaEHR (Electronic Health Records)
	Payments.js
	New payments Forms
    Copyright (C) 2012 Ernesto J Rodriguez

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
Ext.define('App.view.fees.Payments',
{
	extend : 'App.ux.RenderPanel',
	id : 'panelPayments',
	pageTitle : i18n('payments'),
	initComponent : function()
	{
		var me = this;

		me.encountersPaymentsStore = Ext.create('App.store.fees.EncountersPayments');

		/**
		 * Search Panel Object
		 * --------------------------------------------------------------------------------------------------------------------------
		 */
		me.searchPanel = Ext.create('Ext.panel.Panel',
		{
			title : i18n('search'),
			layout : 'border',
			items : [
			{
				xtype : 'form',
				itemId : 'searchPanelForm',
				height : 145,
				region : 'north',
				bodyPadding : 10,
				bodyStyle : 'background-color:transparent',
				margin : '0 0 5 0',
				items : [
				{
					xtype : 'fieldcontainer',
					itemId : 'fieldcontainerSearchItems',
					layout : 'hbox',
					items : [
					{
						fieldLabel : i18n('paying_entity'),
						itemId : 'fieldPayingEntityCombo',
						xtype : 'mitos.payingentitycombo',
						labelWidth : 95,
						width : 230
					},
					{
						xtype : 'patienlivetsearch',
						fieldLabel : i18n('from'),
						hideLabel : false,
						itemId : 'fieldPatient',
						name : 'from',
						anchor : null,
						labelWidth : 42,
						width : 470,
						margin : '0 0 0 25'
					},
					{
						xtype : 'textfield',
						fieldLabel : i18n('no'),
						itemId : 'fieldPatientNo',
						name : 'transaction_number',
						labelWidth : 45,
						width : 230,
						labelAlign : 'right',
						margin : '0 0 0 25',
						fieldStyle : 'text-align: right;'
					}]
				},
				{
					xtype : 'fieldcontainer',
					layout : 'hbox',
					itemId : 'fieldcontainerFacility',
					items : [
					{
						xtype : 'mitos.billingfacilitiescombo',
						itemId : 'fieldFacility',
						fieldLabel : i18n('pay_to'),
						labelWidth : 95,
						width : 470
					}]
				},
				{
					xtype : 'fieldcontainer',
					itemId: 'fieldcontainerFromTo',
					layout : 'hbox',
					items : [
					{
						fieldLabel : i18n('from'),
						itemId : 'fieldFromDate',
						xtype : 'datefield',
						format: globals['date_display_format'],
						labelWidth : 95,
						width : 230
					},
					{
						fieldLabel : i18n('to'),
						xtype : 'datefield',
						itemId : 'fieldToDate',
						format: globals['date_display_format'],
						margin : '0 0 0 25',
						labelWidth : 42,
						width : 230
					}]
				}],
				buttons : [
				{
					text : i18n('search'),
					handler: me.onSearchButton,
					scope : me
				}, '-',
				{
					text : i18n('reset'),
					scope : me,
					handler: me.onFormResetButton
					// TODO: Create the function event to reset the form.
				}]
			},
			{
				xtype : 'grid',
				region : 'center',
				store : me.encountersPaymentsStore,
				columns : [
				{
					header : i18n('service_date')
				},
				{
					header : i18n('patient_name')
				},
				{
					header : i18n('insurance')
				},
				{
					header : i18n('billing_notes'),
					flex : 1
				},
				{
					header : i18n('balance_due')
				}]
			}]
		});

		/**
		 * Detail Panel Object
		 * --------------------------------------------------------------------------------------------------------------------------
		 */
		me.detailPanel = Ext.create('Ext.panel.Panel',
		{
			title : i18n('detail'),
			layout : 'border',
			items : [
			{
				xtype : 'form',
				height : 145,
				region : 'north',
				bodyPadding : 10,
				bodyStyle : 'background-color:transparent',
				margin : '0 0 5 0',
				items : [
				{
					xtype : 'fieldcontainer',
					layout : 'hbox',
					items : [
					{
						fieldLabel : i18n('paying_entity'),
						xtype : 'mitos.payingentitycombo',
						labelWidth : 95,
						width : 230
					},
					{
						xtype : 'patienlivetsearch',
						fieldLabel : i18n('from'),
						hideLabel : false,
						itemId : 'patientFrom',
						name : 'from',
						anchor : null,
						labelWidth : 42,
						width : 470,
						margin : '0 0 0 25'
					},
					{
						xtype : 'textfield',
						fieldLabel : i18n('no'),
						name : 'transaction_number',
						labelWidth : 45,
						width : 230,
						labelAlign : 'right',
						margin : '0 0 0 25',
						fieldStyle : 'text-align: right;'
					}]
				},
				{
					xtype : 'fieldcontainer',
					layout : 'hbox',
					items : [
					{
						fieldLabel : i18n('payment_method'),
						xtype : 'mitos.paymentmethodcombo',
						labelWidth : 95,
						width : 230
					},
					{
						xtype : 'mitos.billingfacilitiescombo',
						fieldLabel : i18n('pay_to'),
						labelWidth : 42,
						width : 470,
						margin : '0 0 0 25'
					},
					{
						xtype : 'mitos.currency',
						fieldLabel : i18n('amount'),
						name : 'amount',
						labelWidth : 45,
						width : 230,
						labelAlign : 'right',
						margin : '0 0 0 25',
						enableKeyEvents : true
					}]
				},
				{
					xtype : 'fieldcontainer',
					layout : 'hbox',
					items : [
					{
						fieldLabel : i18n('from'),
						xtype : 'datefield',
						format: globals['date_display_format'],
						labelWidth : 95,
						width : 230
					},
					{
						fieldLabel : i18n('to'),
						xtype : 'datefield',
						format: globals['date_display_format'],
						margin : '0 0 0 25',
						labelWidth : 42,
						width : 230
					}]
				}],
				buttons : [
				{
					text : i18n('save')
				}, '-',
				{
					text : i18n('reset')
				}, '->',
				{
					text : i18n('add_payment'),
					scope : me,
					handler : me.onAddPaymentClick

				}]
			},
			{
				xtype : 'grid',
				region : 'center',
				//store:me.encountersPaymentsStore,
				plugins : Ext.create('App.ux.grid.RowFormEditing',
				{
					autoCancel : false,
					errorSummary : false,
					clicksToEdit : 1,
					enableRemove : true,
					listeners :
					{
						scope : me,
						beforeedit : me.beforeCptEdit
					}
				}),
				columns : [
				{
					header : i18n('service_date')
				},
				{
					header : i18n('patient_name')
				},
				{
					header : i18n('insurance')
				},
				{
					header : i18n('billing_notes'),
					flex : 1
				},
				{
					header : i18n('balance_due')
				}]
			}]
		});

		me.tapPanel = Ext.create('Ext.tab.Panel',
		{
			layout : 'fit',
			items : [me.searchPanel, me.detailPanel]
		});

		me.pageBody = [me.tapPanel];
		me.callParent(arguments);
	},

	/**
	 * Shows the payment entry window. 
	 */
	onAddPaymentClick : function()
	{
		app.onPaymentEntryWindow();
	},

	/**
	 * beforeCptEdit Event
	 */
	beforeCptEdit : function(editor, e)
	{
		this.addCptFields(editor.editor, e.record.data)
	},

	/**
	 * addCptFields
	 * Add CPT
	 */
	addCptFields : function(editor, cpts)
	{

		editor.removeAll();

		var testData = this.testData();
		for (var i = 0; i < testData.length; i++)
		{
			editor.add(
			{
				xtype : 'fieldcontainer',
				layout : 'hbox',
				items : [
				{
					xtype : 'textfield',
					width : 100,
					name : 'code',
					readOnly : true,
					margin : '0 5 0 10'
				},
				{
					xtype : 'textfield',
					name : 'copay',
					readOnly : true,
					width : 400,
					margin : '0 5 0 5'
				},
				{
					xtype : 'mitos.currency',
					name : 'remaining',
					readOnly : true,
					width : 100,
					margin : '0 5 0 5'
				},
				{
					xtype : 'mitos.currency',
					name : 'allowed',
					readOnly : true,
					width : 100,
					margin : '0 5 0 5'
				},
				{
					xtype : 'mitos.currency',
					name : 'payment',
					readOnly : true,
					width : 100,
					margin : '0 5 0 5'
				},
				{
					xtype : 'mitos.currency',
					name : 'deductible',
					readOnly : true,
					width : 100,
					margin : '0 5 0 5'
				},
				{
					xtype : 'mitos.currency',
					name : 'takeback',
					readOnly : true,
					width : 100,
					margin : '0 5 0 5'
				},
				{
					xtype : 'checkbox',
					name : 'takeback',
					readOnly : true,
					width : 50,
					margin : '0 5 0 5'
				},
				{
					xtype : 'textfield',
					name : 'takeback',
					readOnly : true,
					width : 100,
					margin : '0 5 0 5'
				}]
			});
		}
	},

	/**
	 * Test Data function
	 */
	testData : function()
	{
		var data = [], i;

		floor = Math.floor((Math.random() * 6) + 1);

		for ( i = 0; i < floor; i++)
		{
			data.push(
			{
				data1 : Math.floor(Math.max((Math.random() * 100), floor)),
				data2 : Math.floor(Math.max((Math.random() * 100), floor)),
				data3 : Math.floor(Math.max((Math.random() * 100), floor)),
				data4 : Math.floor(Math.max((Math.random() * 100), floor)),
				data5 : Math.floor(Math.max((Math.random() * 100), floor)),
				data6 : Math.floor(Math.max((Math.random() * 100), floor)),
				data7 : Math.floor(Math.max((Math.random() * 100), floor)),
				data8 : Math.floor(Math.max((Math.random() * 100), floor)),
				data9 : Math.floor(Math.max((Math.random() * 100), floor))
			});
		}
		return data;
	},
	/**
	 * onBtnClick Event
	 */
	onBtnClick : function(btn)
	{
		var me = this;

		if (btn.action == 'search')
		{
			me.forms.getLayout().setActiveItem(0);
		}
		else
		if (btn.action == 'details')
		{
			me.forms.getLayout().setActiveItem(1);
		}
		else
		if (btn.action == 'new')
		{
			me.window.show();
		}
	},
	
	/**
	 * Search for patients that own money
	 * This function will pass all the fields to the server side 
	 * so PHP dataProvider can calculate and do the search against 
	 * the SQL Server
	 */
	onSearchButton: function(btn)
	{
		// Declare some variables and the values from the form
		var	searchForm =  this.searchPanel.getComponent('searchPanelForm'),
		dateFrom = searchForm.getComponent('fieldcontainerFromTo').getComponent('fieldFromDate').getValue(),
		dateTo = searchForm.getComponent('fieldcontainerFromTo').getComponent('fieldToDate').getValue();
		
		// Load the ExtJs dataStore with the new parameters
		this.encountersPaymentsStore.load(
		{
			params :
			{
				datefrom : dateFrom,
				dateto : dateTo,
				payingEntityCombo : searchForm.getComponent('fieldcontainerSearchItems').getComponent('fieldPayingEntityCombo').getValue(),
				patientSearch : searchForm.getComponent('fieldcontainerSearchItems').getComponent('fieldPatient').getValue(),
				patientNo : searchForm.getComponent('fieldcontainerSearchItems').getComponent('fieldPatientNo').getValue(),
				facility : searchForm.getComponent('fieldcontainerFacility').getComponent('fieldFacility').getValue()
			}
		} );
		//alert(payingEntityCombo);
	},
	
	/**
	 * Reset the form of search.
	 */
	onFormResetButton: function(btn)
	{
		alert('Hello there');
	},

	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive : function(callback)
	{
		this.encountersPaymentsStore.load();
		callback(true);
	}
});
//end Payments class

