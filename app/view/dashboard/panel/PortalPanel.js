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

Ext.define('App.view.dashboard.panel.PortalPanel', {
	extend: 'Ext.panel.Panel',
	alias: 'widget.portalpanel',
	requires: [
		'Ext.layout.container.Column',

		'App.view.dashboard.panel.PortalDropZone',
		'App.view.dashboard.panel.PortalColumn'
	],

	cls: 'x-portal',
	bodyCls: 'x-portal-body',
	defaultType: 'portalcolumn',
	//componentLayout: 'body',
	autoScroll: true,

	manageHeight: false,

	initComponent: function(){
		var me = this;

		// Implement a Container beforeLayout call from the layout to this Container
		this.layout = {
			type: 'column'
		};
		this.callParent();

		this.addEvents({
			validatedrop: true,
			beforedragover: true,
			dragover: true,
			beforedrop: true,
			drop: true
		});
	},

	// Set columnWidth, and set first and last column classes to allow exact CSS targeting.
	beforeLayout: function(){
		var items = this.layout.getLayoutItems(),
			len = items.length,
			firstAndLast = ['x-portal-column-first', 'x-portal-column-last'],
			i, item, last;

		for(i = 0; i < len; i++){
			item = items[i];
			item.columnWidth = 1 / len;
			last = (i == len - 1);

			if(!i){ // if (first)
				if(last){
					item.addCls(firstAndLast);
				}else{
					item.addCls('x-portal-column-first');
					item.removeCls('x-portal-column-last');
				}
			}else if(last){
				item.addCls('x-portal-column-last');
				item.removeCls('x-portal-column-first');
			}else{
				item.removeCls(firstAndLast);
			}
		}

		return this.callParent(arguments);
	},

	// private
	initEvents: function(){
		this.callParent();
		this.dd = Ext.create('App.view.dashboard.panel.PortalDropZone', this, this.dropConfig);
	},

	// private
	beforeDestroy: function(){
		if(this.dd){
			this.dd.unreg();
		}
		this.callParent();
	}
});
