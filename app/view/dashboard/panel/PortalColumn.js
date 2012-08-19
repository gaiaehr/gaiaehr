/**
 * @class Ext.ux.PortalColumn
 * @extends Ext.container.Container
 * A layout column class used internally be {@link Ext.app.PortalPanel}.
 */
Ext.define('App.view.dashboard.panel.PortalColumn', {
	extend     : 'Ext.container.Container',
	alias      : 'widget.portalcolumn',

    requires: [
        'Ext.layout.container.Anchor',
        'App.view.dashboard.panel.Portlet'
    ],

    layout: 'anchor',
    defaultType: 'portlet',
    cls: 'x-portal-column'
	//
	// This is a class so that it could be easily extended
	// if necessary to provide additional behavior.
	//
});