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
	'App.view.dashboard.panel.Portlet',
	'App.view.dashboard.panel.PortalColumn',
	'App.view.dashboard.panel.PortalPanel',
	'App.view.dashboard.panel.PortalDropZone',
	'App.view.dashboard.panel.GridPortlet',
	'App.view.dashboard.panel.ChartPortlet',
	'App.view.dashboard.panel.OnotesPortlet'
]);
Ext.define('App.view.dashboard.Dashboard', 
{
	extend        : 'App.classes.RenderPanel',
	id            : 'panelDashboard',
	pageTitle     : i18n['dashboard'],
	uses          : [
		'App.view.dashboard.panel.PortalPanel',
		'App.view.dashboard.panel.PortalColumn',
		'App.view.dashboard.panel.GridPortlet',
		'App.view.dashboard.panel.ChartPortlet'
	],
	getTools      : function() 
	{
		return [
		{
			xtype  : 'tool',
			type   : 'gear',
			handler: function(e, target, panelHeader) 
			{
				var portlet = panelHeader.ownerCt;
				portlet.setLoading( i18n['working'] + '...');
				Ext.defer(function() 
				{
					portlet.setLoading(false);
				}, 2000);
			}
		}];
	},
	initComponent : function() 
	{
		var content = '<div class="portlet-content">HELLO WORLD!</div>';
		Ext.apply(this, 
		{
			pageBody: [
			{
				id    : 'app-portal',
				xtype : 'portalpanel',
				layout: 'fit',
				region: 'center',
				items : 
				[
				{
					id   : 'col-1',
					items: 
					[
                        {
//                            id       : 'portlet-onotes',
                            title    : i18n['office_notes'],
                            tools    : this.getTools(),
                            items    : Ext.create('App.view.dashboard.panel.OnotesPortlet'),
                            listeners:
                            {
                                close: Ext.bind(this.onPortletClose, this)
                            }
                        }
                            //,
                            //{
                            //	id       : 'portlet-2',
                            //	title    : 'Portlet 2',
                            //	tools    : this.getTools(),
                            //	html     : content,
                            //	listeners: {
                            //		'close': Ext.bind(this.onPortletClose, this)
                            //	}
                            //}
                        ]
					},
                    {
//                        id   : 'col-2',
                        items: [
                            {
                                title    : 'Office Visits',
                                tools    : this.getTools(),
                                items    : Ext.create('App.view.dashboard.panel.VisitsPortlet'),
                                listeners: {
                                    close: Ext.bind(this.onPortletClose, this)
                                }
                            }
                        ]
                    }
//                    ,
//                    {
//                        id   : 'col-3',
//                        items: [
//                            {
//                                id       : 'portlet-4',
//                                title    : 'Portlet 4',
//                                tools    : this.getTools(),
//                                items    : Ext.create('App.view.dashboard.panel.ChartPortlet'),
//                                listeners: {
//                                    'close': Ext.bind(this.onPortletClose, this)
//                                }
//                            }
//                        ]
//                    }
					]
				}
			]
		}, null);
		this.callParent(arguments);
	},
	
	onPortletClose: function(portlet) 
	{
		this.msg(i18n['message'] + '!', portlet.title + ' ' + i18n['was_removed']);
	},
	
	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive      : function(callback) 
	{
		callback(true);
	}
}); //ens UserPage class
