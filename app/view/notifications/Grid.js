/**
 * Created by ernesto on 3/19/14.
 */
Ext.define('App.view.notifications.Grid', {
	extend: 'Ext.grid.Panel',
	xtype: 'notificationsgrid',
	width: 300,
	title: i18n('notifications'),
	collapsible: true,
	collapsed: true,
	hideCollapseTool: true,
//	hidden: true,
	frame: true,
	hideHeaders: true,
	columns: [
		{
			text: i18n('description'),
			dataIndex: 'description',
			flex: 1
		}
	]

});