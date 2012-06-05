//******************************************************************************
// Users.ejs.php
// Description: Users Screen
// v0.0.4
// 
// Author: Ernesto J Rodriguez
// Modified: n/a
// 
// GaiaEHR (Electronic Health Records) 2011
//******************************************************************************
Ext.require([
	'Ext.layout.container.*',
	'Ext.resizer.Splitter',
	'Ext.fx.target.Element',
	'Ext.fx.target.Component',
	'App.classes.dashboard.Portlet',
	'App.classes.dashboard.PortalColumn',
	'App.classes.dashboard.PortalPanel',
	'App.classes.dashboard.PortalDropZone',
	'App.classes.dashboard.GridPortlet',
	'App.classes.dashboard.ChartPortlet',
	'App.classes.dashboard.OnotesPortlet'
]);
Ext.define('App.view.dashboard.Dashboard', {
	extend        : 'App.classes.RenderPanel',
	id            : 'panelDashboard',
	pageTitle     : 'Dashboard',
	uses          : [
		'App.classes.dashboard.PortalPanel',
		'App.classes.dashboard.PortalColumn',
		'App.classes.dashboard.GridPortlet',
		'App.classes.dashboard.ChartPortlet'
	],
	getTools      : function() {
		return [
			{
				xtype  : 'tool',
				type   : 'gear',
				handler: function(e, target, panelHeader) {
					var portlet = panelHeader.ownerCt;
					portlet.setLoading('Working...');
					Ext.defer(function() {
						portlet.setLoading(false);
					}, 2000);
				}
			}
		];
	},
	initComponent : function() {
		var content = '<div class="portlet-content">HELLO WORLD!</div>';
		Ext.apply(this, {
			pageBody: [
				{
					id    : 'app-portal',
					xtype : 'portalpanel',
					layout: 'fit',
					region: 'center',
					items : [
						{
							id   : 'col-1',
							items: [
								{
									id       : 'portlet-onotes',
									title    : 'Office Notes',
									tools    : this.getTools(),
									items    : Ext.create('App.classes.dashboard.OnotesPortlet'),
									listeners: {
										'close': Ext.bind(this.onPortletClose, this)
									}
								},
								{
									id       : 'portlet-2',
									title    : 'Portlet 2',
									tools    : this.getTools(),
									html     : content,
									listeners: {
										'close': Ext.bind(this.onPortletClose, this)
									}
								}
							]
						},
						{
							id   : 'col-2',
							items: [
								{
									id       : 'portlet-1',
									title    : 'Grid Portlet',
									tools    : this.getTools(),
									items    : Ext.create('App.classes.dashboard.GridPortlet'),
									listeners: {
										'close': Ext.bind(this.onPortletClose, this)
									}
								}
							]
						},
						{
							id   : 'col-3',
							items: [
								{
									id       : 'portlet-4',
									title    : 'Portlet 4',
									tools    : this.getTools(),
									items    : Ext.create('App.classes.dashboard.ChartPortlet'),
									listeners: {
										'close': Ext.bind(this.onPortletClose, this)
									}
								}
							]
						}
					]
				}
			]
		}, null);
		this.callParent(arguments);
	},
	onPortletClose: function(portlet) {
		this.msg('Message!', portlet.title + ' was removed');
	},
	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive      : function(callback) {
		callback(true);
	}
}); //ens UserPage class
