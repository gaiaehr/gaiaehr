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
Ext.define('App.ux.LivePatientSearch', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.patienlivetsearch',
	hideLabel: true,

	initComponent: function(){
		var me = this;

		Ext.define('patientLiveSearchModel', {
			extend: 'Ext.data.Model',
			fields: [
				{
					name: 'pid',
					type: 'int'
				},
				{
					name: 'pubpid',
					type: 'int'
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
				},
				{
					name: 'DOB',
					type: 'string'
				},
				{
					name: 'SS',
					type: 'string'
				}
			],
			proxy: {
				type: 'direct',
				api: {
					read: 'Patient.patientLiveSearch'
				},
				reader: {
					totalProperty: 'totals',
					root: 'rows'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model: 'patientLiveSearchModel',
			pageSize: 10,
			autoLoad: false
		});

		Ext.apply(me, {
			store: me.store,
			displayField: 'fullname',
			valueField: 'pid',
			emptyText: i18n('search_for_a_patient') + '...',
			typeAhead: false,
			hideTrigger: true,
			minChars: 1,
			queryDelay: 200,
			listConfig: {
				loadingText: i18n('searching') + '...',
				//emptyText	: 'No matching posts found.',
				//---------------------------------------------------------------------
				// Custom rendering template for each item
				//---------------------------------------------------------------------
				getInnerTpl: function(){
					return '<div class="search-item"><h3><span>{fullname}</span>&nbsp;&nbsp;({pid})</h3>DOB:&nbsp;{DOB}&nbsp;SS:&nbsp;{SS}</div>';
				}
			},
			pageSize: 10
		});

		me.callParent();
	}
});