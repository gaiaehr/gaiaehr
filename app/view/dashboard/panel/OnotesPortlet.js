Ext.define('App.view.dashboard.panel.OnotesPortlet', {

	extend       : 'Ext.grid.Panel',
	alias        : 'widget.onotesportlet',
	height       : 250,
	initComponent: function() {
		var me = this;
		Ext.define('OnotesPortletModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id', type: 'int'},
				{name: 'date', type: 'date', dateFormat: 'c'},
				{name: 'body', type: 'string'},
				{name: 'user', type: 'string'},
				{name: 'facility_id', type: 'string'},
				{name: 'activity', type: 'string'}
			],
			proxy : {
				type: 'direct',
				api : {
					read: OfficeNotes.getOfficeNotes
				}
			}
		});
		me.store = Ext.create('Ext.data.Store', {
			model   : 'OnotesPortletModel',
			autoLoad: true
		});

		Ext.apply(this, {
			height     : this.height,
			store      : this.store,
			stripeRows : true,
			columnLines: true,
			columns    : [
				{
					id       : 'user',
					text     : 'From',
					sortable : true,
					dataIndex: 'user'
				},
				{
					text     : 'Note',
					sortable : true,
					dataIndex: 'body',
					flex     : 1
				}
			]
		}, null);

		this.callParent(arguments);
	}
});
