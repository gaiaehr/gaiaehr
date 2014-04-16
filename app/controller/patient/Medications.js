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

Ext.define('App.controller.patient.Medications', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'MedicationsPanel',
			selector: 'patientmedicationspanel'
		},
		{
			ref: 'PatientMedicationsGrid',
			selector: 'patientmedicationspanel #patientMedicationsGrid'
		},
		{
			ref: 'MedicationsListGrid',
			selector: 'patientmedicationspanel #medicationsListGrid'
		},
		{
			ref: 'addPatientMedicationBtn',
			selector: 'patientmedicationspanel #addPatientMedicationBtn'
		},
		{
			ref: 'MedicationsListGridSearchField',
			selector: 'patientmedicationspanel #medicationsListGrid triggerfield'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'patientmedicationspanel': {
				activate: me.onMedicationsPanelActive
			},
			'patientmedicationspanel #medicationsListGrid': {
				expand: me.onMedicationsListGridExpand
			},
			'patientmedicationspanel #medicationsListGrid triggerfield': {
				beforerender: me.onMedicationsListGridTriggerFieldBeforeRender
			},
			'patientmedicationspanel #addPatientMedicationBtn': {
				click: me.onAddPatientMedicationBtnClick
			},
			'#patientMedicationLiveSearch': {
				select: me.onMedicationLiveSearchSelect
			}
		});
	},

	onMedicationsListGridExpand: function(grid){
		this.getMedicationsListGridSearchField().reset();
		grid.getStore().load();
	},

	onMedicationsListGridTriggerFieldBeforeRender: function(field){
		var me = this;

		field.onTriggerClick = function(){
			me.getMedicationsListGrid().getStore().load({
				params: {query: this.getValue()}
			});
		}
	},

	onAddPatientMedicationBtnClick: function(){
		var me = this,
			grid = me.getPatientMedicationsGrid(),
			store = grid.getStore();

		grid.editingPlugin.cancelEdit();
		store.insert(0, {
			pid: app.patient.pid,
			eid: app.patient.eid,
			uid: app.user.id,
			created_uid: app.user.id,
			create_date: new Date(),
			begin_date: new Date()
		});
		grid.editingPlugin.startEdit(0, 0);
	},

	onMedicationLiveSearchSelect: function(cmb, records){
		var form = cmb.up('form').getForm();

		Rxnorm.getMedicationAttributesByCODE(records[0].data.CODE, function(provider, response){
			form.getRecord().set({
				RXCUI: record[0].data.RXCUI,
				CODE: record[0].data.CODE
			});
			form.setValues({
				STR: records[0].data.STR.split(',')[0],
				route: response.result.DRT,
				dose: response.result.DST,
				form: response.result.DDF
			});
		});
	},

	onMedicationsPanelActive: function(){
		var store = this.getPatientMedicationsGrid().getStore();

		store.clearFilter(true);
		store.filter([
			{
				property: 'pid',
				value: app.patient.pid
			}
		]);
	}
});