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

Ext.define('App.controller.dashboard.panel.NewResults', {
	extend: 'App.controller.dashboard.Dashboard',

	init: function(){
		if(!a('view_dashboard_new_results')) return;

		var me = this;

		me.control({
			'portalpanel':{
				render: me.onDashboardPanelBeforeRender
			},
			'#DashboardPanel':{
				activate: me.onDashboardPanelActivate
			},
			'#DashboardNewResultsGrid':{
				itemdblclick: me.onDashboardNewResultsGridItemDoubleClick
			}
		});

		me.addRef([
			{
				ref: 'DashboardRenderPanel',
				selector:'#DashboardPanel'
			},
			{
				ref: 'DashboardNewResultsGrid',
				selector:'#DashboardNewResultsGrid'
			}
		]);
	},

	onDashboardNewResultsGridItemDoubleClick: function(grid, record){
		grid.el.mask(_('please_wait'));

		app.setPatient(record.data.pid, null, function(){
			app.openPatientSummary();
			app.onMedicalWin('laboratories');
			grid.el.unmask();
		});
	},


	onDashboardPanelActivate: function(){
		this.getDashboardNewResultsGrid().getStore().load();
	},

	onDashboardPanelBeforeRender: function(){
		this.addRightPanel(_('new_results'), Ext.create('App.view.dashboard.panel.NewResults'));
	}

});