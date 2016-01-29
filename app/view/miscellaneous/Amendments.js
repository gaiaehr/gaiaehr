/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.view.miscellaneous.Amendments', {
	extend: 'App.ux.RenderPanel',
	requires: [
		'Ext.ux.SlidingPager'
	],
	itemId: 'AmendmentsPanel',
	pageTitle: _('amendments'),

	initComponent: function(){

		var me = this;

		me.controller = App.app.getController('miscellaneous.Amendments');

		me.pageBody = [
			{
				xtype:'grid',
				itemId: 'AmendmentsGrid',
				store: me.store = Ext.create('App.store.miscellaneous.Amendments',{
					remoteFilter: true,
					remoteSort: true,
					sorters:[
						{
							property: 'cancel_date',
							direction: 'DESC'
						}
					]
				}),
				columns:[
					{
						text: _('type'),
						width: 70,
						dataIndex: 'amendment_type',
						renderer: function(v, meta, record){
							var str;

							if(v === 'P'){
								str = _('patient');
							}else if(v === 'D'){
								str = _('doctor');
							}else if(v === 'O'){
								str = _('organization');
							}else{
								str = v;
							}

							return me.newRenderer(str, meta, record);
						}
					},
					{
						text: _('dates'),
						columns: [
							{
								text: _('received'),
								width: 130,
								dataIndex: 'create_date',
								renderer: me.dateNewRenderer
							},
							{
								text: _('responded'),
								width: 130,
								dataIndex: 'response_date',
								renderer: me.dateNewRenderer
							},
							{
								text: _('appended'),
								width: 130,
								dataIndex: 'response_date',
								renderer: function(v, meta, record){
									if(record.data.amendment_status == 'A'){
										return me.dateNewRenderer(v, meta, record);
									}else{
										return me.dateNewRenderer(null, meta, record);
									}
								}
							}
						]
					},
					{
						text: _('message'),
						flex: 1,
						dataIndex: 'amendment_message',
						renderer: me.newRenderer
					},
					{
						text: _('response_message'),
						flex: 1,
						dataIndex: 'response_message',
						renderer: me.newRenderer
					},
					{
						text: _('status'),
						width: 100,
						dataIndex: 'amendment_status',
						renderer: function(v, meta, record){
							var str;

							if(v === 'W'){
								str = _('waiting_response');
							}else if(v === 'A'){
								str = _('approved');
							}else if(v === 'D'){
								str = _('denied');
							}else if(v === 'C'){
								str = _('canceled');
							}else if(v === 'E'){
								str = _('error');
							}else{
								str = v;
							}

							me.controller.updateIsViewed(record);

							return me.newRenderer(str, meta, record);
						}
					},
					{
						text: _('approved_denied_by'),
						width: 200,
						dataIndex: 'responded_by',
						renderer: me.newRenderer
					}
				],
				bbar: {
					xtype: 'pagingtoolbar',
					pageSize: 25,
					store: me.store,
					displayInfo: true,
					plugins: new Ext.ux.SlidingPager()
				}
			}
		];

		me.callParent();
	},

	newRenderer: function(v, meta, record){
		if(!record.data.is_read){
			meta.style = 'font-weight:bold';
		}
		return v;
	},

	dateNewRenderer: function(v, meta, record){
		if(!record.data.is_read){
			meta.style = 'font-weight:bold';
		}
		return Ext.Date.format(v, g('date_time_display_format'));
	}

});
