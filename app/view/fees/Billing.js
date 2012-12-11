/**
 GaiaEHR (Electronic Health Records)
 Billing.js
 Billing Forms
 Copyright (C) 2012 Certun, inc.

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
Ext.define( 'App.view.fees.Billing',
{
	extend : 'App.ux.RenderPanel',
	id : 'panelBilling',
	pageTitle : i18n( 'billing' ),
	uses : ['App.ux.GridPanel'],
	pageLayout : 'card',

	initComponent : function()
	{
		var me = this;
		me.paymentstatus = 1;
		me.patient = null;
		me.pastDue = null;
		me.dateRange =
		{
			start : null,
			limit : null
		};

		me.patientListStore = Ext.create( 'App.store.fees.Billing' );

		/**
		 *  Encounter data grid.
		 * Gives a list of encounter based on the patient search
		 *
		 */
		me.encountersGrid = Ext.create( 'Ext.grid.Panel',
		{
			store : me.patientListStore,
			selModel : Ext.create( 'Ext.selection.CheckboxModel',
			{
				listeners :
				{
					scope : me,
					selectionchange : me.onSelectionChanged
				}
			} ),
			viewConfig :
			{
				stripeRows : true
			},
			columns : [
			{
				header : i18n( 'service_date' ),
				dataIndex : 'service_date',
				width : 200
			},
			{
				header : i18n( 'patient' ),
				dataIndex : 'patientName',
				width : 200
			},
			{
				header : i18n( 'primary_provider' ),
				dataIndex : 'primaryProvider',
				width : 200
			},
			{
				header : i18n( 'encounter_provider' ),
				dataIndex : 'encounterProvider',
				flex : 1
			},
			{
				header : i18n( 'insurance' ),
				dataIndex : 'insurance',
				width : 200
			},
			{
				header : i18n( 'billing_stage' ),
				dataIndex : 'billing_stage',
				renderer : me.stage,
				width : 135
			}],
			// ToolBar for Encounter DataGrid.
			tbar : [
			{
				xtype : 'fieldcontainer',
				itemId : 'fieldContainerPatientSearch',
				items : [
				{
					xtype : 'displayfield',
					fieldLabel : i18n( 'patient_search' )
				},
				{
					xtype : 'patienlivetsearch',
					itemId : 'patienlivetsearch',
					width : 235,
					margin : '0 5 0 0'
				}]
			},
			{
				xtype : 'fieldcontainer',
				itemId : 'fieldContainerDateRange',
				items : [
				{
					xtype : 'datefield',
					itemId : 'datefrom',
					fieldLabel : i18n( 'from' ),
					labelWidth : 35,
					width : 150,
					format : globals['date_display_format']
				},
				{
					xtype : 'datefield',
					itemId : 'dateto',
					fieldLabel : i18n( 'to' ),
					labelWidth : 35,
					padding : '0 5 0 0',
					width : 150,
					format : globals['date_display_format']
				}]
			},
			{
				xtype : 'fieldcontainer',
				itemId : 'fieldContainerInsurance',
				items : [
				{
					xtype : 'mitos.providerscombo',
					itemId : 'provider',
					labelWidth : 60,
					typeAhead : true,
					padding : '0 5 0 5',
					fieldLabel : i18n( 'provider' ),
					defaultValue : 'All'

				},
				{
					xtype : 'mitos.insurancepayertypecombo',
					itemId : 'insurance',
					labelWidth : 60,
					padding : '0 5 0 5',
					fieldLabel : i18n( 'insurance' ),
					defaultValue : 'All'

				}]
			}, '-',
			{
				xtype : 'fieldcontainer',
				itemId : 'fieldContainerSearch',
				layout : 'vbox',
				items : [
				{
					xtype : 'button',
					width : 80,
					margin : '0 0 3 0',
					text : i18n( 'search' ),
					listeners :
					{
						scope : me,
						click : me.ReloadGrid
					}
				}]
			}, '-',
			{
				xtype : 'fieldcontainer',
				itemId : 'fieldContainerGenerate1500',
				layout : 'vbox',
				items : [
				{
					xtype : 'button',
					width : 170,
					margin : '0 0 3 0',
					text : i18n( 'generate_cms1500_pdf' )
				},
				{
					xtype : 'button',
					width : 170,
					margin : '0 0 3 0',
					text : i18n( 'generate_cms1500_text' )
				}]
			}, '-',
			{
				xtype : 'fieldcontainer',
				itemId : 'fieldContainerGenerateANSI',
				layout : 'vbox',
				items : [
				{
					xtype : 'button',
					text : i18n( 'generate_x12' )
				}]
			}, '->',
			{
				xtype : 'tbtext',
				text : i18n( 'past_due' ) + ':'
			},
			{
				text : i18n( '30+' ),
				enableToggle : true,
				action : 30,
				toggleGroup : 'pastduedates',
				enableKeyEvents : true,
				scale : 'large',
				listeners :
				{
					scope : me,
					click : me.onBtnClicked
				}
			},
			{
				text : i18n( '60+' ),
				enableToggle : true,
				action : 60,
				scale : 'large',
				toggleGroup : 'pastduedates',
				listeners :
				{
					scope : me,
					click : me.onBtnClicked
				}
			},
			{
				text : i18n( '120+' ),
				enableToggle : true,
				action : 120,
				scale : 'large',
				toggleGroup : 'pastduedates',
				listeners :
				{
					scope : me,
					click : me.onBtnClicked
				}
			},
			{
				text : i18n( '180+' ),
				enableToggle : true,
				action : 180,
				scale : 'large',
				toggleGroup : 'pastduedates',
				listeners :
				{
					scope : me,
					click : me.onBtnClicked
				}
			}],
			listeners :
			{
				scope : me,
				itemdblclick : me.rowDblClicked
			}
		} );

		/**
		 * Panel: encounterBillingDetails
		 */
		me.encounterBillingDetails = Ext.create( 'Ext.panel.Panel',
		{
			defaultTitle : i18n( 'encounter_billing_details' ),
			title : i18n( 'encounter_billing_details' ),
			layout : 'border',
			bodyStyle : 'background-color:#fff',
			items : [Ext.create( 'Ext.container.Container',
			{
				region : 'center',
				layout : 'border',
				style : 'background-color:#fff',
				items : [me.icdForm = Ext.create( 'Ext.form.Panel',
				{
					region : 'north',
					border : false,
					items : [
					{
						xtype : 'fieldset',
						title : i18n( 'encounter_general_info' ),
						margin : '5 5 0 5',
						items : [
						{
							xtype : 'fieldcontainer',
							layout :
							{
								type : 'hbox'
							},
							defaults :
							{
								margin : '0 10'
							},
							hideLabel : true,
							items : [
							{
								xtype : 'datefield',
								name : 'service_date',
								fieldLabel : i18n( 'service_date' ),
								labelAlign : 'right',
								labelWidth : 80,
								format : globals['date_display_format']
							},
							{
								xtype : 'activeinsurancescombo',
								name : 'insurance',
								fieldLabel : i18n( 'insurance' ),
								labelAlign : 'right'
							},
							{
								xtype : 'textfield',
								name : 'facility',
								fieldLabel : i18n( 'facility' ),
								labelAlign : 'right',
								labelWidth : 60,
								flex : 1
							}]
						},
						{
							xtype : 'fieldcontainer',
							layout :
							{
								type : 'hbox'
							},
							defaults :
							{
								margin : '0 10'
							},
							hideLabel : true,
							items : [
							{
								xtype : 'datefield',
								name : 'hosp_date',
								fieldLabel : i18n( 'hosp_date' ),
								labelAlign : 'right',
								labelWidth : 80,
								format : globals['date_display_format']
							},
							{
								xtype : 'activeinsurancescombo',
								name : 'sec_insurance',
								fieldLabel : i18n( 'sec_insurance' ),
								labelAlign : 'right'
							},
							{
								xtype : 'mitos.providerscombo',
								name : 'provider',
								fieldLabel : i18n( 'provider' ),
								labelAlign : 'right',
								labelWidth : 60,
								flex : 1
							}]
						},
						{
							xtype : 'fieldcontainer',
							layout :
							{
								type : 'hbox'
							},
							defaults :
							{
								margin : '0 10'
							},
							hideLabel : true,
							items : [
							{
								xtype : 'mitos.authorizationscombo',
								name : 'authorization',
								fieldLabel : i18n( 'authorization' ),
								labelAlign : 'right',
								labelWidth : 80
							},
							{
								xtype : 'textfield',
								name : 'sec_authorization',
								fieldLabel : i18n( 'sec_authorization' ),
								labelAlign : 'right'
							},
							{
								xtype : 'textfield',
								name : 'referal_by',
								fieldLabel : i18n( 'referal_by' ),
								labelAlign : 'right',
								labelWidth : 60,
								flex : 1
							}]
						}]
					},
					{
						xtype : 'icdsfieldset',
						title : i18n( 'encounter_icd9' ),
						margin : '5 5 0 5'
					}]
				} ), me.cptPanel = Ext.create( 'App.view.patient.encounter.CurrentProceduralTerminology',
				{
					region : 'center'
				} )]
			} ), me.progressNote = Ext.create( 'App.view.patient.ProgressNote',
			{
				title : i18n( 'encounter_progress_note' ),
				region : 'east',
				margin : 5,
				bodyStyle : 'padding:15px',
				width : 500,
				autoScroll : true,
				collapsible : true,
				animCollapse : true,
				collapsed : false
			} )],
			buttons : [
			{
				text : i18n( 'encounters' ),
				scope : me,
				action : 'encounters',
				tooltip : i18n( 'back_to_encounter_list' ),
				handler : me.onBtnCancel
			}, '->',
			{
				xtype : 'tbtext',
				action : 'page',
				text : '( 1 of 1 )'
			},
			{
				text : i18n( 'back' ),
				scope : me,
				action : 'back',
				iconCls : 'icoArrowLeftSmall',
				tooltip : i18n( 'previous_encounter_details' ),
				handler : me.onBtnBack
			},
			{
				text : i18n( 'save' ),
				scope : me,
				action : 'save',
				tooltip : i18n( 'save_billing_details' ),
				handler : me.onBtnSave
			},
			{
				text : i18n( 'cancel' ),
				scope : me,
				action : 'cancel',
				tooltip : i18n( 'cancel_and_go_back_to_encounter_list' ),
				handler : me.onBtnCancel
			},
			{
				text : i18n( 'next' ),
				scope : me,
				action : 'next',
				iconCls : 'icoArrowRightSmall',
				iconAlign : 'right',
				tooltip : i18n( 'next_encounter_details' ),
				handler : me.onBtnNext
			}]
		} );

		me.pageBody = [me.encountersGrid, me.encounterBillingDetails];
		me.callParent( arguments );
	},

	/**
	 * Function: stage
	 */
	stage : function(val)
	{
		switch(val)
		{
			case 1:
				return '<img src="resources/images/icons/stage1.png" />';
				break;
			case 2:
				return '<img src="resources/images/icons/stage2.png" />';
				break;
			case 3:
				return '<img src="resources/images/icons/stage3.png" />';
				break;
			case 4:
				return '<img src="resources/images/icons/stage4.png" />';
				break;
			default:
				return val;
		}
	},

	/**
	 * Event: onBtnClicked
	 */
	onBtnClicked : function(btn)
	{
		var datefrom = this.query( 'datefield[itemId="datefrom"]' ), dateto = this.query( 'datefield[itemId="dateto"]' );
		if (btn.pressed)
		{
			datefrom[0].reset( );
			dateto[0].reset( );
			this.pastDue = btn.action;
		}
		else
		{
			this.pastDue = 0;
		}
		this.ReloadGrid( );

	},

	/**
	 * Event: rowDblClicked
	 */
	rowDblClicked : function()
	{
		this.goToEncounterBillingDetail( );
	},

	/**
	 * Function: goToEncounterBillingDetail
	 */
	goToEncounterBillingDetail : function()
	{
		this.getPageBody( ).getLayout( ).setActiveItem( 1 );
	},

	/**
	 * Function: goToEncounterList
	 */
	goToEncounterList : function()
	{
		this.getPageBody( ).getLayout( ).setActiveItem( 0 );
	},

	/**
	 * Event: onSelectionChanged
	 */
	onSelectionChanged : function(sm, model)
	{
		if (model[0])
		{
			var me = this, title = me.encounterBillingDetails.defaultTitle, backbtn = me.encounterBillingDetails.query( 'button[action="back"]' ), nextBtn = me.encounterBillingDetails.query( 'button[action="next"]' ), pageInfo = me.encounterBillingDetails.query( 'tbtext[action="page"]' ), rowIndex = model[0].index;

			me.pid = model[0].data.pid;
			me.eid = model[0].data.eid;

			me.updateProgressNote( me.eid );
			me.encounterBillingDetails.setTitle( title + ' ( ' + model[0].data.patientName + ' )' );

			me.getEncounterIcds( );

			me.cptPanel.encounterCptStoreLoad( me.pid, me.eid, function()
			{
				me.cptPanel.setDefaultQRCptCodes( );
			} );

			pageInfo[0].setText( '( ' + i18n( 'page' ) + ' ' + (rowIndex + 1) + ' of ' + sm.store.data.length + ' )' );
			nextBtn[0].setDisabled( rowIndex == sm.store.data.length - 1 );
			backbtn[0].setDisabled( rowIndex == 0 );
		}
	},

	/**
	 * Event: onBtnCancel
	 */
	onBtnCancel : function()
	{
		this.getPageBody( ).getLayout( ).setActiveItem( 0 );
	},

	/**
	 * Event: onBtnBack
	 */
	onBtnBack : function()
	{
		var sm = this.encountersGrid.getSelectionModel( ), currRowIndex = sm.getLastSelected( ).index, prevRowindex = currRowIndex - 1;
		sm.select( prevRowindex );
	},

	/**
	 * Event: onBtnNext
	 */
	onBtnNext : function()
	{
		var sm = this.encountersGrid.getSelectionModel( ), currRowIndex = sm.getLastSelected( ).index, nextRowindex = currRowIndex + 1;
		sm.select( nextRowindex );
	},

	/**
	 * Event: onBtnSave
	 */
	onBtnSave : function()
	{
		var me = this, form = me.icdForm.getForm( ), values = form.getValues( );

		me.updateEncounterIcds( values );
		me.msg( 'Sweet!', i18n( 'encounter_billing_data_updated' ) );
	},

	/**
	 * Function: getEncounterIcds
	 */
	getEncounterIcds : function()
	{
		var me = this;

		Encounter.getEncounterIcdxCodes(
		{
			eid : me.eid
		}, function(provider, response)
		{
			me.icdForm.down( 'icdsfieldset' ).loadIcds( response.result );
		} );
	},

	/**
	 * Function: updateEncounterIcds
	 */
	updateEncounterIcds : function(data)
	{
		var me = this;

		data.eid = me.eid;

		Encounter.updateEncounterIcdxCodes( data, function(provider, response)
		{
			say( response.result );
			return true;
		} );
	},

	/**
	 * Function: updateProgressNote
	 */
	updateProgressNote : function(eid)
	{
		var me = this;
		Encounter.getProgressNoteByEid( eid, function(provider, response)
		{
			var data = response.result;
			me.progressNote.tpl.overwrite( me.progressNote.body, data );
		} );
	},

	/**
	 * Function: Search for billing based on the search fields
	 * This function will pass all the fields to the server side 
	 * so PHP dataProvider can calculate and do the search against 
	 * the SQL Server
	 */
	ReloadGrid : function(btn)
	{
		// Declare some variables
		var topBarItems = this.encountersGrid.getDockedItems('toolbar[dock="top"]')[0],
		datefrom = topBarItems.getComponent( 'fieldContainerDateRange' ).getComponent( 'datefrom' ).getValue( ), 
		dateto = topBarItems.getComponent( 'fieldContainerDateRange' ).getComponent( 'dateto' ).getValue( );

		// Check if the dateFrom and dateTo are in use, if they are clear the pastDue variable
		if(datefrom || dateto) this.pastDue = 0;
		
		// Load the ExtJs dataStore with the new parameters
		this.patientListStore.load(
		{
			params :
			{
				datefrom : datefrom,
				dateto : dateto,
				provider : topBarItems.getComponent( 'fieldContainerInsurance' ).getComponent( 'provider' ).getValue( ),
				insurance : topBarItems.getComponent( 'fieldContainerInsurance' ).getComponent( 'insurance' ).getValue( ),
				patient : topBarItems.getComponent( 'fieldContainerPatientSearch' ).getComponent( 'patienlivetsearch' ).getValue( ),
				pastDue : this.pastDue
			}
		} );

	},

	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive : function(callback)
	{
		this.ReloadGrid( );
		callback( true );
	}
} );

