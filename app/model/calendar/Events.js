/**
 * Created by ernesto on 4/3/14.
 */
Ext.define('App.model.calendar.Events', {
	extend: 'Ext.data.Model',
	table: {
		name: 'calendar_events',
		comment: 'Calendar Events'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'pid',
			type: 'int',
			comment: 'patient id of the event patient'
		},
		{
			name: 'uid',
			type: 'int',
			comment: 'user id of the event owner'
		},
		{
			name: 'category',
			type: 'int'
		},
		{
			name: 'facility',
			type: 'int'
		},
		{
			name: 'billing_facility',
			type: 'int'
		},
		{
			name: 'title',
			type: 'string',
			len: 180
		},
		{
			name: 'status',
			type: 'string',
			len: 80
		},
		{
			name: 'start',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'end',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'rrule',
			type: 'string',
			len: 80
		},
		{
			name: 'loc',
			type: 'string',
			len: 160
		},
		{
			name: 'notes',
			type: 'string',
			len: 600
		},
		{
			name: 'url',
			type: 'string',
			len: 180
		},
		{
			name: 'ad',
			type: 'string',
			len: 80
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Calendar.getEvents',
			create: 'Calendar.addEvent',
			update: 'Calendar.updateEvent',
			destroy: 'Calendar.destroyEvent'
		}
	}
});