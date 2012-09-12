//******************************************************************************
// ClientListReport.js
// Client List (Patient) Report
// v0.0.1
// 
// Author: Gino Rivera Falu (GI Technologies)
// Modified:
// 
// GaiaEHR (Electronic Health Records) 2012
//******************************************************************************

Ext.define('App.view.reports.ClientListReport', {
	extend       : 'App.classes.RenderPanel',
	id           : 'panelClientListReport',
	pageTitle    : i18n['client_list_report'],
	pageLayout   : 'border',
	uses         : [
		'App.classes.GridPanel'
	],
	initComponent: function() {
		var me = this;
		
		//-----------------------------------------------------------------------
		// PDF render panel
		//-----------------------------------------------------------------------
		me.PDFPanel = Ext.create('Ext.Component', 
		{
			region		: 'center',
            xtype		: 'component',
            id			: 'pdfRender',
            autoEl: 
            {
                tag: 'iframe',
                frame: false,
                style: 'height: 100%; width: 100%; border: none'
            }
		}); // END PDF Panel
		
		//-----------------------------------------------------------------------
		// Filter panel for the report
		//-----------------------------------------------------------------------
		me.FilterForm = Ext.create('Ext.form.FormPanel', 
		{
			region     	: 'north',
			height     	: 120,
			bodyPadding	: 10,
			margin     	: '0 0 3 0',
			collapsible	: true,
			buttonAlign	: 'left',
			title		: i18n['filter'],
			items      	: [
				{
					xtype     : 'fieldcontainer',
					fieldLabel: i18n['visits'],
					layout    : 'hbox',
					defaults  : { margin: '0 5 0 0' },
					items     : [
						{
							xtype    : 'datefield',
							emptyText: i18n['from'],
							name     : 'from'
						},
						{
							xtype    : 'datefield',
							emptyText: i18n['to'],
							name     : 'to'
						}
					]
				}
			],
	
			buttons: [
				{
					text   : i18n['search'],
					iconCls: 'save',
					handler: function() 
					{
						// TODO: Pass variables to the report.
						//Ext.get('pdfRender').dom.extraParams.to = 'Hello';
						console.log(this.to.getValue());
						//Ext.get('pdfRender').dom.src = 'app/view/reports/templates/ClientListReport.rpt.php';
					}
				},
				'-',
				{
					text   : i18n['reset'],
					iconCls: 'delete',
					handler: function() 
					{
						Ext.get('pdfRender').dom.src = '';
					}
				}
			]
		});
		
		me.pageBody = [ me.FilterForm, me.PDFPanel ];
		me.callParent(arguments);
	
	}, // end of initComponent
	
	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback) 
	{
		callback(true);
	},

}); //ens oNotesPage class