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
Ext.define('App.ux.LiveUserSearch', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.userlivetsearch',
	hideLabel: true,
	displayField: 'fullname',
	valueField: 'id',
	emptyText: _('search_for_a_user') + '...',
	maxLength: 40,
	typeAhead: false,
	hideTrigger: true,
	minChars: 1,
	queryDelay: 200,
	forceSelection:true,
	acl: null,
	initComponent: function(){
		var me = this;

		Ext.define('userLiveSearchModel', {
			extend: 'Ext.data.Model',
			fields: [
				{
					name: 'id',
					type: 'int'
				},
				{
					name: 'title',
					type: 'string'
				},
				{
					name: 'role',
					type: 'string'
				},
				{
					name: 'fname',
					type: 'string'
				},
				{
					name: 'mname',
					type: 'string'
				},
				{
					name: 'lname',
					type: 'string'
				},
				{
					name: 'fullname',
					type: 'string',
					convert: function(v, record){
						return record.data.fname + ' ' + record.data.mname + ' ' + record.data.lname
					}
				}
			]
		});

		me.store = Ext.create('Ext.data.Store', {
			model: 'userLiveSearchModel',
			pageSize: 10,
			autoLoad: false,
			proxy: {
				type: 'direct',
				api: {
					read: 'User.userLiveSearch'
				},
				extraParams: {
					acl: me.acl
				},
				reader: {
					root: 'data'
				}
			}

		});

		Ext.apply(me, {
			store: me.store,
			listConfig: {
				loadingText: _('searching') + '...',
				//emptyText	: 'No matching posts found.',
				//---------------------------------------------------------------------
				// Custom rendering template for each item
				//---------------------------------------------------------------------

				getInnerTpl: function(){
					var pid = (eval(g('display_pubpid')) ? 'pubpid' : 'pid');
					return '<div class="search-item">{fullname} <b>({role})</b></div>'
				}
			},
			pageSize: 10
		});

		me.callParent();
	}
});