//******************************************************************************
// ReportCenter.js
// This is the Report Center Main Panel ths will contain categories and list
// of the available reports in GaiaEHR App
// v0.0.1
// 
// Author: Gino Rivera Falu (GI Technologies)
// Modified:
// 
// GaiaEHR (Electronic Health Records) 2012
//******************************************************************************

Ext.define('App.view.reports.ReportCenter', 
{
	extend       : 'App.classes.RenderPanel',
	id           : 'panelReportCenter',
	pageTitle    : i18n['report_center'],
	
	initComponent: function() 
	{
		var me = this;

        me.reports = Ext.create('Ext.panel.Panel',
        {
            layout:'auto'
        });

		me.pageBody = [ me.reports ];
		
		/*
		 * Patient Reports List
		 */
		me.patientCategory = me.addCategory(i18n['patient_reports'], 250);
		me.link1 = me.addReportByCategory(me.patientCategory, i18n['prescriptions_and_dispensations'], function(btn) 
        {
            me.goToReportPanel('panelReportPanel');
        });
        me.link2 = me.addReportByCategory(me.patientCategory, i18n['clinical'], function(btn) 
        {
            me.goToReportPanel('panelReportPanel');
        });
        me.link3 = me.addReportByCategory(me.patientCategory, i18n['referrals'], function(btn) 
        {
            me.goToReportPanel('panelReportPanel');
        });
        me.link4 = me.addReportByCategory(me.patientCategory, i18n['immunization_registry'], function(btn) 
        {
            me.goToReportPanel('panelReportPanel');
        });

		/*
		 * Clinic Reports List
		 */        
        me.clinicCategory = me.addCategory(i18n['clinic_reports'], 260);
        me.link5 = me.addReportByCategory(me.clinicCategory, i18n['standard_measures'], function(btn) 
        {
            me.goToReportPanel('panelReportPanel');
        });
        me.link6 = me.addReportByCategory(me.clinicCategory, i18n['clinical_quality_measures_cqm'], function(btn) 
        {
            me.goToReportPanel('panelReportPanel');
        });
        me.link7 = me.addReportByCategory(me.clinicCategory, i18n['automated_measure_calculations_amc'], function(btn) 
        {
            me.goToReportPanel('panelReportPanel');
        });
        me.link8 = me.addReportByCategory(me.clinicCategory, i18n['automated_measure_calculations_tracking'], function(btn) 
        {
            me.goToReportPanel('panelReportPanel');
        });
        
		me.callParent(arguments);
	
	},

	/*
	 * Function to add categories with the respective with to the
	 * Report Center Panel
	 */
    addCategory:function(category, width){
        var me = this;
        return me.reports.add(
            Ext.create('Ext.container.Container',
            {
                cls:'CategoryContainer',
                width:width,
                layout:'anchor',
                items:[
                    {
                        xtype:'container',
                        cls:'title',
                        margin:'0 0 5 0',
                        html:category
                    }
                ]
            })
        );
    },

	/*
	 * Function to add Items to the category
	 */
    addReportByCategory:function(category, text, fn)
    {
        return category.add(
            Ext.create('Ext.button.Button',{
            	cls: 'CategoryContainerItem',
                anchor:'100%',
                margin:'0 0 5 0',
                textAlign:'left',
                text:text,
                handler:fn
            })
        );
    },

	/*
	 * Function to call the report panel.
	 * Remember the report fields are dynamically rendered.
	 */
    goToReportPanel:function()
    {
        app.MainPanel.getLayout().setActiveItem('panelReportPanel');
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