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
		// Just create the panel and do not display the PDF yet, until 
		// the user click create report.
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
			
			// Draw the buttons to render and clear the report panel view.
			buttons: [
				{
					text   : i18n['create_pdf'],
					iconCls: 'icoReport',
					handler: function() 
					{
						// create a veriable to then convert it to json string
						// and pass it to the report, usin payload has the
						// variable. PDF format
						var jsonPayload = 
						{
							startDate: me.FilterForm.getForm().findField("from").getValue(),
							endDate: me.FilterForm.getForm().findField("to").getValue(),
							pdf: true
						};
						Ext.get('pdfRender').dom.src = 'modules/reports/ClientListReport.rpt.php?params=' + Ext.JSON.encode(jsonPayload);
					}
				},
				'-',
				{
					text   : i18n['create_html'],
					iconCls: 'icoReport',
					handler: function() 
					{
						// create a veriable to then convert it to json string
						// and pass it to the report, usin payload has the
						// variable. PDF format
						var jsonPayload = 
						{
							startDate: me.FilterForm.getForm().findField("from").getValue(),
							endDate: me.FilterForm.getForm().findField("to").getValue(),
							pdf: false
						};
						Ext.get('pdfRender').dom.src = 'app/view/reports/templates/ClientListReport.rpt.php?params=' + Ext.JSON.encode(jsonPayload);
					}
				},
				'-',
				{
					text   : i18n['reset'],
					iconCls: 'delete',
					handler: function() 
					{
						// Simply clear the src of the iframe to clear the report.
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
	}

}); //ens oNotesPage class