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

Ext.define('Modules.reportcenter.view.ReportPanel',
{
	extend       : 'App.classes.RenderPanel',
	id           : 'panelReportPanel',
	pageTitle    : i18n['report_center'],
	pageLayout   : {
        type:'vbox',
        align:'stretch'
    },
	initComponent: function()
	{
		var me = this;

		//-----------------------------------------------------------------------
		// PDF render panel
		// Just create the panel and do not display the PDF yet, until
		// the user click create report.
		//-----------------------------------------------------------------------
		me.PDFPanel = Ext.create('Ext.Component',
		{
            xtype		: 'component',
            flex:1,
            autoEl:
            {
                tag: 'iframe',
                frame: false,
                style: 'height: 100%; width: 100%; border: 1px solid #ccc; background-color:white'
            }
		}); // END PDF Panel

		//-----------------------------------------------------------------------
		// Filter panel for the report
		//-----------------------------------------------------------------------
		me.FilterForm = Ext.create('Ext.form.Panel',
		{
			height     	: 120,
			bodyPadding	: 10,
			margin     	: '0 0 3 0',
			collapsible	: true,
			buttonAlign	: 'left',
			title		: i18n['filter'],
			items      	: [{}],
			// Draw the buttons to render and clear the report panel view.
			buttons: [
				{
					text   : i18n['create_pdf'],
					iconCls: 'icoReport',
                    scope:me,
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
						}, form = me.FilterForm;

                        if(typeof form.reportFn == 'function'){
                            form.reportFn('this report has custom function');
                        }
                        me.PDFPanel.el.dom.src = 'report_layouts/ClientListReport.rpt.php?params=' + Ext.JSON.encode(jsonPayload);
					}
				},
				'-',
				{
					text   : i18n['create_html'],
					iconCls: 'icoReport',
                    scope:me,
					handler: function()
					{
						// create a veriable to then convert it to json string
						// and pass it to the report, usin payload has the
						// variable. HTML format
						var jsonPayload =
						{
							startDate: me.FilterForm.getForm().findField("from").getValue(),
							endDate: me.FilterForm.getForm().findField("to").getValue(),
							pdf: false
                        }, form = me.FilterForm;

                        if(typeof form.reportFn == 'function'){
                            form.reportFn('this report has custom function');
                        }
						me.PDFPanel.el.dom.src = 'report_layouts/ClientListReport.rpt.php?params=' + Ext.JSON.encode(jsonPayload);
					}
				},
				'-',
				{
					text   : i18n['reset'],
					iconCls: 'delete',
                    scope:me,
					handler: function()
					{
						// Simply clear the src of the iframe to clear the report.
                        me.PDFPanel.el.dom.src = '';
					}
				}
			]
		});

		me.pageBody = [ me.FilterForm, me.PDFPanel ];


		me.callParent(arguments);

        me.getPageBody().addDocked(
        {
            xtype	: 'toolbar',
            dock	:'top',
            items	: [
                {
                    text	: i18n['back'],
                    iconCls:'icoArrowLeftSmall',
                    handler	: me.goToReportCenter
                }
            ]
        });

	}, // end of initComponent

    goToReportCenter:function()
    {
        app.MainPanel.getLayout().setActiveItem('panelReportCenter');
    },

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