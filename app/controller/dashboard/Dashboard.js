/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2012 Ernesto Rodriguez
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

Ext.define('App.controller.dashboard.Dashboard', {
	extend: 'Ext.app.Controller',
	refs: [
		{
			ref: 'DashboardRenderPanel',
			selector: '#DashboardPanel'
		},
		{
			ref: 'DashboardPanel',
			selector: 'portalpanel'
		},
		{
			ref: 'DashboardLeftColumn',
			selector: '#DashboardColumn1'
		},
		{
			ref: 'DashboardRightColumn',
			selector: '#DashboardColumn2'
		}
	],

	addLeftPanel: function(title, item, index){
		var panel;
		if(index){
			panel = this.getDashboardLeftColumn().insert(index, {
				xtype: 'portlet',
				title: title,
				items: [item]
			});
		}else{
			panel = this.getDashboardLeftColumn().add({
				xtype: 'portlet',
				title: title,
				items: [item]
			});
		}
		return panel;
	},

	addRightPanel: function(title, item, index){
		var panel;
		if(index){
			panel = this.getDashboardRightColumn().insert(index, {
				xtype: 'portlet',
				title: title,
				items: [item]
			});
		}else{
			panel = this.getDashboardRightColumn().add({
				xtype: 'portlet',
				title: title,
				items: [item]
			});
		}
		return panel;
	},

	getColumns: function(){
		return this.getDashboardPanel().items;
	}

});