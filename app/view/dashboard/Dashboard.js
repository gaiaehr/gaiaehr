/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, LLC.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.view.dashboard.Dashboard', {
	extend: 'App.ux.RenderPanel',
	requires: [
		'App.view.dashboard.panel.OnotesPortlet',
		'App.view.dashboard.panel.VisitsPortlet'
	],
	pageTitle: i18n('dashboard'),
	getTools: function(){
		return [
			{
				xtype: 'tool',
				type: 'gear',
				handler: function(e, target, panelHeader){
					var portlet = panelHeader.ownerCt;
					portlet.setLoading(i18n('working') + '...');
					Ext.defer(function(){
						portlet.setLoading(false);
					}, 2000);
				}
			}
		];
	},

	initComponent: function(){
		var me = this;

		Ext.apply(me, {
			pageBody: [
				{
					xtype: 'portalpanel',
					layout: 'fit',
					region: 'center',
					items: [
						{
							itemId: 'dashboard-col-1'
//							items: [
//								{
//									title: i18n('office_notes'),
//									tools: this.getTools(),
//									items: { xtype: 'onotesportlet' },
//									listeners: {
//										close: Ext.bind(this.onPortletClose, this)
//									}
//								}
//							]
						},
						{
							itemId: 'dashboard-col-2',
							items: [
								{
									title: 'Office Visits',
									tools: this.getTools(),
									items: { xtype: 'visitsportlet' },
									listeners: {
										close: Ext.bind(this.onPortletClose, this)
									}
								}
							]
						}
					]
				}
			]
		});

		me.callParent();

		me.listeners = {
			scope: me,
			show: me.doReloadStores
		};
	},

	onPortletClose: function(portlet){
		this.msg(i18n('message') + '!', portlet.title + ' ' + i18n('was_removed'));
	},

	doReloadStores: function(){
		Ext.ComponentQuery.query('visitsportlet')[0].load();
	},

	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback){
		callback(true);
	}
}); //ens UserPage class
