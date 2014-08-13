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

Ext.define('App.controller.patient.Patient', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'PossiblePatientDuplicatesWindow',
			selector: '#PossiblePatientDuplicatesWindow'
		},
		{
			ref: 'PatientDemographicForm',
			selector: '#PatientDemographicForm'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'#PossiblePatientDuplicatesWindow': {
				close: me.onPossiblePatientDuplicatesWindowCLose
			},
			'#PossiblePatientDuplicatesWindow > grid': {
				itemdblclick: me.onPossiblePatientDuplicatesGridItemDblClick
			},
			'#PatientPossibleDuplicatesBtn': {
				click: me.onPatientPossibleDuplicatesBtnClick
			}
		});
	},

	onPossiblePatientDuplicatesGridItemDblClick: function(grid, record){
		app.setPatient(record.data.pid, null, function(){
			app.openPatientSummary();
			grid.up('window').close();
		});
	},

	onPossiblePatientDuplicatesWindowCLose: function(window){
		var store = window.down('grid').getStore();
		store.removeAll();
		store.commitChanges();
	},

	checkForPossibleDuplicates: function(cmp){
		var me = this,
			form = cmp.isPanel ? cmp.getForm() : cmp.up('form').getForm();

		if(form.isValid()){
			if(!me.getPossiblePatientDuplicatesWindow()){
				Ext.create('App.view.patient.windows.PossibleDuplicates');
			}

			var params = {
				fname: form.findField('fname').getValue(),
				lname: form.findField('lname').getValue(),
				sex: form.findField('sex').getValue(),
				DOB: form.findField('DOB').getValue()
			};

			if(form.getRecord()){
				params.pid = form.getRecord().data.pid;
			}

			me.lookForPossibleDuplicates(params);
		}else{

		}app.msg(i18n('oops'), i18n('required_fields_missing'), true);
	},

	lookForPossibleDuplicates: function(params){
		var me = this,
			store = me.getPossiblePatientDuplicatesWindow().down('grid').getStore();

		store.getProxy().extraParams = params;

		store.load({
			callback: function(records){
				if(records.length > 0){
					me.getPossiblePatientDuplicatesWindow().show();
				}else{
					app.msg(i18n('sweet'), i18n('no_possible_duplicates_found'));
				}
			}
		});
	},

	onPatientPossibleDuplicatesBtnClick: function(btn){
		this.checkForPossibleDuplicates(btn.up('panel').down('form'));
	}

});