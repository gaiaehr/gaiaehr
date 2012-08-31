//******************************************************************************
// Users.ejs.php
// Description: Users Screen
// v0.0.4
//
// Author: Ernesto J Rodriguez
// Modified: n/a
//
// GaiaEHR (Eletronic Health Records) 2011
//******************************************************************************
Ext.define('App.view.reports.items.PatientList', 
{
	extend       : 'App.classes.RenderPanel',
	id           : 'panelPatientList',
	pageTitle    : i18n['national_library'],
	pageLayout   : 'border',
	uses         : [
		'App.classes.GridPanel'
	],
	initComponent: function() 
	{

        var me = this;
        me.pageBody = [
        {
        	xtype	: 'panel',
        	layout	: 'fit',
			loader	: 
			{
				autoLoad:true,
				scripts:true
				//url :'app/view/reports/reportCenterLayout.php'
			}
        }
        ];
        me.callParent(arguments);
        
	}, // end of initComponent
	
	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive     : function(callback) 
	{
		callback(true);
	}
}); //ens UserPage class