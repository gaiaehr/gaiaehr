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

Ext.define('App.view.dashboard.panel.NewResults', {
	extend: 'Ext.grid.Panel',
	itemId: 'DashboardNewResultsGrid',
	requires: [
		'Ext.ux.SlidingPager'
	],
	maxHeight: 200,
	columnLines: true,
	disableSelection: true,
	initComponent: function(){
		var me = this;

		me.store = Ext.create('App.store.patient.PatientsOrderResults');

		me.bbar = {
			xtype: 'pagingtoolbar',
				pageSize: 10,
				store: me.store,
				displayInfo: true,
				plugins: Ext.create('Ext.ux.SlidingPager')
		};

		me.columns = [
			{
				text: _('signed'),
				dataIndex: 'signed_uid',
				width: 60,
				renderer: function(v){
					return app.boolRenderer(v);
				}
			},
			{
				xtype: 'datecolumn',
				text: _('received'),
				dataIndex: 'create_date'
			},
			{
				text: _('description'),
				dataIndex: 'code_text',
				flex: 1
			},
			{
				text:  _('status'),
				renderer: this.pctChange,
				dataIndex: 'result_status'
			}
		];

		me.callParent(arguments);
	},



	/**
	 * Custom function used for column renderer
	 * @param {Object} val
	 */
	change: function(val){
		if(val > 0){
			return '<span style="color:green;">' + val + '</span>';
		}else if(val < 0){
			return '<span style="color:red;">' + val + '</span>';
		}
		return val;
	},

	/**
	 * Custom function used for column renderer
	 * @param {Object} val
	 */
	pctChange: function(val){
		if(val > 0){
			return '<span style="color:green;">' + val + '%</span>';
		}else if(val < 0){
			return '<span style="color:red;">' + val + '%</span>';
		}
		return val;
	},

});
