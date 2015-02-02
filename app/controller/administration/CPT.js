/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
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

Ext.define('App.controller.administration.CPT', {
	extend: 'Ext.app.Controller',

	refs: [
		{
			ref: 'CptAdminGrid',
			selector: 'cptadmingrid'
		},
		{
			ref: 'AdminCpt4CodeOnlyActiveBtn',
			selector: '#adminCpt4CodeOnlyActiveBtn'
		}
	],

	init: function(){
		var me = this;

		me.control({
			'cptadmingrid': {
				activate: me.onCptAdminGridActive
			},
			'#adminCpt4CodeSearchField': {
				keyup: me.onAdminCpt4CodeSearchFieldKeyUp
			}
		});
	},

	onCptAdminGridActive: function(grid){
		grid.getStore().load();
	},

	onAdminCpt4CodeSearchFieldKeyUp: function(field){
		var me = this,
			store = me.getCptAdminGrid().getStore();
		me.dataQuery = field.getValue();
		store.proxy.extraParams = {
			onlyActive: me.getAdminCpt4CodeOnlyActiveBtn().pressed,
			query: me.dataQuery
		};

		store.loadPage(1);
	}

});