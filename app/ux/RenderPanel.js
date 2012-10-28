/*
 GaiaEHR (Electronic Health Records)
 RenderPanel.js
 UX
 Copyright (C) 2012 Ernesto Rodriguez

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
Ext.define('App.ux.RenderPanel', {
	extend       : 'Ext.container.Container',
	alias        : 'widget.renderpanel',
	cls          : 'RenderPanel',
	layout       : 'border',
	frame        : false,
	border       : false,
	pageLayout   : 'fit',
	pageBody     : [],
	pageTitle    : '',
	initComponent: function() {
		var me = this;
		Ext.apply(me, {
			items: [
				{
					cls   : 'RenderPanel-header',
					itemId: 'RenderPanel-header',
					xtype : 'container',
					region: 'north',
					layout: 'fit',
					height: 33,
					html  : '<div class="panel_title">' + me.pageTitle + '</div>'

				},
				{
					cls    : 'RenderPanel-body-container',
                    itemId : 'RenderPanel-body-container',
					xtype  : 'container',
					region : 'center',
					layout : 'fit',
					padding: 5,
					items  : [
						{
							cls     : 'RenderPanel-body',
							xtype   : 'panel',
							frame   : true,
							layout  : this.pageLayout,
							border  : false,
                            itemId  : 'pageLayout',
							defaults: {frame: false, border: false, autoScroll: true},
							items   : me.pageBody
						}
					]
				}
			]
		}, this);
		me.callParent(arguments);
	},

	updateTitle: function(pageTitle, readOnly, timer) {
		
		var readOnlyDiv = '<div class="readOnly">' + i18n('read_only') + '</div>',
			timerDiv = '<span class="timer">' + timer + '</span>';
		this.getComponent('RenderPanel-header').update('<div class="panel_title">' + pageTitle + '</div>' + (readOnly ? readOnlyDiv : '') + (timer ?  timerDiv : ''));
	},

    getPageHeader:function(){
        return this.getComponent('RenderPanel-header');
    },
    getPageBodyContainer:function(){
        return this.getComponent('RenderPanel-body-container');
    },
    getPageBody:function(){
        return this.getPageBodyContainer().down('panel');
    }

});
