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
Ext.define('App.ux.LiveReferringPhysicianSearch', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.referringphysicianlivetsearch',
	hideLabel: true,
	displayField: 'fullname',
	valueField: 'id',
	emptyText: _('search_for_a_physician') + '...',
	maxLength: 40,
    queryMode: 'remote',
    allowBlank: true,
	typeAhead: false,
    forceSelection: false,
    allowOnlyWhitespace: true,
	hideTrigger: true,
    validateBlank: true,
    submitValue: true,
	minChars: 0,
	queryDelay: 200,
	initComponent: function(){
		var me = this;

		Ext.define('referringPhysicianLiveSearchModel', {
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
					name: 'upin',
					type: 'string'
				},
				{
					name: 'lic',
					type: 'string'
				},
				{
					name: 'npi',
					type: 'string'
				},
                {
                    name: 'ssn',
                    type: 'string'
                },
                {
                    name: 'taxonomy',
                    type: 'string'
                },
                {
                    name: 'email',
                    type: 'string'
                },
                {
                    name: 'direct_address',
                    type: 'string'
                },
                {
                    name: 'phone_number',
                    type: 'string'
                },
                {
                    name: 'fax_number',
                    type: 'string'
                },
                {
                    name: 'cel_number',
                    type: 'string'
                }
			],
			proxy: {
				type: 'direct',
				api: {
					read: 'ReferringProviders.referringPhysicianLiveSearch'
				},
                writer:{
                    writeAllFields: true
                },
				reader: {
					totalProperty: 'totals',
					root: 'rows'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model: 'referringPhysicianLiveSearchModel',
			pageSize: 10,
			autoLoad: false
		});

		Ext.apply(me, {
			store: me.store,
			listConfig: {
				loadingText: _('searching') + '...',
				getInnerTpl: function(){
					return '<h3>{fullname}</h3> ({npi})';
				}
			},
			pageSize: 10
		});

		me.callParent();
	}
});
