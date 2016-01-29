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

Ext.define('App.controller.dashboard.panel.DailyVisits', {
	extend: 'App.controller.dashboard.Dashboard',

	init: function(){
		if(!a('view_dashboard_daily_visits')) return;

		var me = this;

		me.control({
			'portalpanel': {
				render: me.onDashboardPanelBeforeRender
			},
			'#DashboardPanel': {
				activate: me.onDashboardPanelActivate
			}
		});

		me.addRef([
			{
				ref: 'DashboardRenderPanel',
				selector: '#DashboardPanel'
			},
			{
				ref: 'DashboardDailyVisitsChart',
				selector: '#DashboardDailyVisitsChart'
			}
		]);
	},

	onDashboardPanelActivate: function(){
		this.loadChart();
	},

	onDashboardPanelBeforeRender: function(){
		this.addRightPanel(_('daily_visits'), Ext.create('App.view.dashboard.panel.DailyVisits'));
	},

	loadChart: function(){
		var me = this,
			store = me.getDashboardDailyVisitsChart().getStore(),
			data = [],
			time,
			i,
			j;

		Encounter.getTodayEncounters(function(response){

			var encounters = response;
			for(i=0; i < encounters.length; i++){
				time = Ext.Date.parse(encounters[i].service_date, 'Y-m-d H:i:s').setMinutes(0,0,0);
				var found = false;

				for(j=0; j < data.length; j++){
					if(data[j].time == time){
						data[j].total = data[j].total + 1;
						found = true;
					}
				}

				if(!found){
					data.push({
						total: 1,
						time: time
					});
				}
			}

			store.loadData(data);
		});
	}

});