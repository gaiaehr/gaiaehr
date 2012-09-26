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

Ext.define('App.view.reports.ReportCenter', {
	extend       : 'App.classes.RenderPanel',
	id           : 'panelReportCenter',
	pageTitle    : i18n['report_center'],
	initComponent: function() {
		var me = this;

        me.reports = Ext.create('Ext.panel.Panel',{
            layout:'auto'
        });

		me.pageBody = [ me.reports ];
		me.callParent(arguments);
	
	},

    addCategory:function(category, width){
        var me = this;
        return me.reports.add(
            Ext.create('Ext.container.Container',{
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

    addReportByCategory:function(category, text, fn){
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

    goToReportPanel:function(){
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