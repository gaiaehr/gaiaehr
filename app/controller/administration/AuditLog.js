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

Ext.define('App.controller.administration.AuditLog', {
	extend: 'Ext.app.Controller',

	requires: [

	],

	refs: [
		{
			ref: 'AuditLogPanel',
			selector: '#AuditLogPanel'
		},
		{
			ref: 'AuditLogGrid',
			selector: '#AuditLogGrid'
		},
		{
			ref: 'AuditLogGridFromDateField',
			selector: '#AuditLogGridFromDateField'
		},
		{
			ref: 'AuditLogGridToDateField',
			selector: '#AuditLogGridToDateField'
		},
		{
			ref: 'AuditLogGridPatientLiveSearch',
			selector: '#AuditLogGridPatientLiveSearch'
		},
		{
			ref: 'AuditLogGridFilterBtn',
			selector: '#AuditLogGridFilterBtn'
		},
		{
			ref: 'AuditLogGridResetBtn',
			selector: '#AuditLogGridResetBtn'
		}
	],

	init: function(){
		var me = this;

		me.control({
			'#AuditLogPanel': {
				activate: me.onAuditLogPanelActivate
			},
			'#AuditLogGrid': {
				itemdblclick: me.onAuditLogGridItemDblClick
			},
			'#AuditLogGridPatientLiveSearch': {
				select: me.onAuditLogGridPatientLiveSearchSelect
			},
			'#AuditLogGridFilterBtn': {
				click: me.onAuditLogGridFilterBtnClick
			},
			'#AuditLogGridResetBtn': {
				click: me.onAuditLogGridResetBtnClick
			}
		});

	},

	onAuditLogGridItemDblClick: function(grid, record){

		//say(record);

	},

	onAuditLogPanelActivate: function(panel){
		this.doFilterAuditGrid(panel.query('#AuditLogGridFilterBtn')[0]);
	},

	onAuditLogGridPatientLiveSearchSelect: function(){

	},

	onAuditLogGridFilterBtnClick: function(btn){
		this.doFilterAuditGrid(btn);
	},

	onAuditLogGridResetBtnClick: function(btn){
		btn.up('toolbar').query('#AuditLogGridFromDateField')[0].setRawValue('');
		btn.up('toolbar').query('#AuditLogGridToDateField')[0].setValue(new Date());
		btn.up('toolbar').query('#AuditLogGridPatientLiveSearch')[0].reset();
		this.doFilterAuditGrid(btn);
	},

	doFilterAuditGrid: function(btn){

		var fromField = btn.up('toolbar').query('#AuditLogGridFromDateField')[0],
			toField =  btn.up('toolbar').query('#AuditLogGridToDateField')[0],
			patient = btn.up('toolbar').query('#AuditLogGridPatientLiveSearch')[0].getValue(),
			store = btn.up('grid').getStore(),
			filters = [
				{
					property: 'date',
					operator: '>=',
					value: fromField.getRawValue() + ' 00:00:00'

				},
				{
					property: 'date',
					operator: '<=',
					value: toField.getRawValue() + ' 23:59:59'
				}
			];

		if(patient){
			Ext.Array.push(filters, {
				property: 'pid',
				value: patient
			});
		}

		if(fromField.isValid() && toField.isValid()){
			store.clearFilter(true);
			store.filter(filters);
		}


	}

});
