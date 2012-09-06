//******************************************************************************
// ofice_notes.ejs.php
// office Notes Page
// v0.0.1
// 
// Author: Ernest Rodriguez
// Modified:
// 
// GaiaEHR (Electronic Health Records) 2011
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
		// Filter panel for the report
		//-----------------------------------------------------------------------
		me.FilterForm = Ext.create('Ext.form.FormPanel', {
			region     : 'north',
			height     : 100,
			bodyPadding: 10,
			margin     : '0 0 3 0',
			buttonAlign: 'left',
			items      : [
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
					handler: function() {

					}
				},
				'-',
				{
					text   : i18n['reset'],
					iconCls: 'save',
					tooltip: i18n['hide_selected_office_note'],
					handler: function() {

					}
				}
			]
		});
		
		//-----------------------------------------------------------------------
		// PDF render panel
		//-----------------------------------------------------------------------
		me.PDFPanel = Ext.create('Ext.Component', 
		{
			region	: 'center',
            xtype	: 'component',
            autoEl: 
            {
                tag: 'iframe',
                style: 'height: 100%; width: 100%; border: none',
                src: 'app/view/reports/templates/ClientListReport.rpt.php'
            }
		}); // END PDF Panel
		me.pageBody = [ me.FilterForm, me.PDFPanel ];
		me.callParent(arguments);
	}, // end of initComponent
	
	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive     : function(callback) {
		callback(true);
	}
}); //ens oNotesPage class