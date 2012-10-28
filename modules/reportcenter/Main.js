Ext.define('Modules.reportcenter.Main',
{
	extend : 'Modules.Module',
	constructor : function()
	{
		var me = this;
		/**
		 * @param panel     (Ext.component)     Component to add to MainPanel
		 */
		me.addAppPanel(Ext.create('Modules.reportcenter.view.ReportCenter'));
		me.addAppPanel(Ext.create('Modules.reportcenter.view.ReportPanel'));
		/**
		 * funtion to add navigation links
		 * @param parentId  (string)            navigation node parent ID,
		 * @param node      (object || array)   navigation node configuration properties
		 */
		me.addNavigationNodes('root',
		{
			//text	:i18n('client_list_report'),
			text : 'Report Center',
			leaf : true,
			cls : 'file',
			iconCls : 'icoReport',
			id : 'panelReportCenter'
		});
		me.callParent();
	}
}); 