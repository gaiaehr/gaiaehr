/**
 * Created by ernesto on 4/3/14.
 */
Ext.define('App.model.calendar.Category', {
	extend: 'Ext.data.Model',
	table: {
		name: 'calendar_categories',
		comment: 'Calendar Categories'
	},
	fields: [
		{
			name: 'catid',
			type: 'int'
		},
		{
			name: 'catname',
			type: 'string',
			len: 160
		},
		{
			name: 'catcolor',
			type: 'string',
			len: 10
		},
		{
			name: 'catdesc',
			type: 'string',
			len: 255
		},
		{
			name: 'duration',
			type: 'int'
		},
		{
			name: 'cattype',
			type: 'int'
		}
	],
	idProperty: 'catid',
	proxy: {
		type: 'direct',
		api: {
			read: 'Calendar.getCategories',
			create: 'Calendar.addCategory',
			update: 'Calendar.updateCategory',
			destroy: 'Calendar.destroyCategory'
		}
	}
});