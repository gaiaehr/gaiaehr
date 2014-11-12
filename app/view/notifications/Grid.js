/**
 * Created by ernesto on 3/19/14.
 */
Ext.define('App.view.notifications.Grid', {
	extend: 'Ext.grid.Panel',
	xtype: 'notificationsgrid',
	width: 400,
	header: false,
	hideHeaders: true,
	columns: [
		{
			text: _('description'),
			dataIndex: 'description',
			flex: 1
		}
	]

});