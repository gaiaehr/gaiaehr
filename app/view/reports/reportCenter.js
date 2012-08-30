/**
 * reportCenter.js
 * Report Center Panel
 *
 * This class renders all the panel used inside the Report Center Panel
 *
 * v0.1.0
 *
 * Author: Gino Rivera Falú (GI Technologies)
 *
 * GaiaEHR (Electronic Health Records) 2012
 *
 */
Ext.define('App.view.reports.reportCenter', 
{
    extend              : 'App.classes.RenderPanel',
    id                  : 'panelReport',
    pageTitle           : i18n['reportCenter'],
    initComponent		: function() 
    {
        var me = this;
        me.pageBody = [
        {
        	xtype	: 'panel',
        	layout	: 'fit',
        	update	: {loadMask: 'Loading',url: 'app/app/view/reports/reportCenterLayout.html', scripts: true},
			//html	: '<iframe src="app/view/reports/reportCenterLayout.html" height="100%" width="100%" scrolling="no" frameborder="0"></iframe>',       	
        }
        ];
        //me.pageBody.load({loadMask: 'Loading',url: 'app/app/view/reports/reportCenterLayout.html', scripts: true});
        me.callParent(arguments);

	},
	/**
	 * This function is called from app.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive      : function(callback) 
	{
		callback(true);
	}

});
