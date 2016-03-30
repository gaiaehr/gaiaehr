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
	requires: [],
	refs: [
		{
			ref: 'MedicationsPanel',
			selector: 'patientmedicationspanel'
		},
		{
			ref: 'PatientMedicationsGrid',
			selector: '#patientMedicationsGrid'
		},
		{
			ref: 'addPatientMedicationBtn',
			selector: '#addPatientMedicationBtn'
		},
		{
			ref: 'PatientMedicationReconciledBtn',
			selector: '#PatientMedicationReconciledBtn'
		},
		{
			ref: 'PatientMedicationUserLiveSearch',
			selector: '#PatientMedicationUserLiveSearch'
		},

		// administer refs
		{
			ref: 'AdministeredMedicationsGrid',
			selector: '#AdministeredMedicationsGrid'
		},
		{
			ref: 'AdministeredMedicationsLiveSearch',
			selector: '#AdministeredMedicationsLiveSearch'
		},
		{
			ref: 'AdministeredMedicationsUserLiveSearch',
			selector: '#AdministeredMedicationsUserLiveSearch'
		},
		{
			ref: 'AdministeredMedicationsAddBtn',
			selector: '#AdministeredMedicationsAddBtn'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'viewport': {
				encounterload: me.onViewportEncounterLoad
			},
            'patientmedicationspanel': {
                activate: me.onMedicationsPanelActive
            },
			'#patientMedicationsGrid': {
				beforeedit: me.onPatientMedicationsGridBeforeEdit
			},
			'#addPatientMedicationBtn': {
				click: me.onAddPatientMedicationBtnClick
			},
			'#patientMedicationLiveSearch': {
				select: me.onMedicationLiveSearchSelect
			},
			'#PatientMedicationReconciledBtn': {
				click: me.onPatientMedicationReconciledBtnClick
			},
			'#PatientMedicationUserLiveSearch': {
				select: me.onPatientMedicationUserLiveSearchSelect,
                reset: me.onPatientMedicationUserLiveSearchReset
			},

			// administer controls
			'#AdministeredMedicationsGrid': {
				beforeedit: me.onAdministeredMedicationsGridBeforeEdit
			},
			'#AdministeredMedicationsLiveSearch': {
				select: me.onAdministeredMedicationsLiveSearchSelect
			},
			'#AdministeredMedicationsUserLiveSearch': {
				select: me.onAdministeredMedicationsUserLiveSearchSelect
			},
			'#AdministeredMedicationsAddBtn': {
				click: me.onAdministeredMedicationsAddBtnClick
			}
		});
	},

	onViewportEncounterLoad: function(encounter){

	},

	onAdministeredMedicationsGridBeforeEdit: function(plugin, context){
		var me = this,
			field = me.getAdministeredMedicationsUserLiveSearch();

		field.forceSelection = false;
		field.setValue(context.record.data.administered_by);
		Ext.Function.defer(function(){
			field.forceSelection = true;
		}, 200);

	},

	onAdministeredMedicationsLiveSearchSelect: function(cmb, records){
		var form = cmb.up('form').getForm();

		form.getRecord().set({
			RXCUI: records[0].data.RXCUI,
			CODE: records[0].data.CODE,
            GS_CODE: records[0].data.GS_CODE,
			NDC: records[0].data.NDC
		});
	},

	onAdministeredMedicationsUserLiveSearchSelect: function(cmb, records){
		var administered_by = records[0],
			record = cmb.up('form').getForm().getRecord();

		record.set({administered_uid: administered_by.data.id});
	},

	onAdministeredMedicationsAddBtnClick: function(){
		var me = this,
			grid = me.getAdministeredMedicationsGrid(),
			store = grid.getStore();

		grid.editingPlugin.cancelEdit();
		store.insert(0, {
			pid: app.patient.pid,
			eid: app.patient.eid,
			uid: app.user.id,
			created_uid: app.user.id,
			created_date: new Date(),
			begin_date: new Date(),
			end_date: new Date(),
			administered_date: new Date(),
			administered_uid: app.user.id,
			title: app.user.title,
			fname: app.user.fname,
			mname: app.user.mname,
			lname: app.user.lname
		});

		grid.editingPlugin.startEdit(0, 0);
	},

	onPatientMedicationsGridBeforeEdit: function(plugin, context){
		var me = this,
			field = me.getPatientMedicationUserLiveSearch();

		field.forceSelection = false;
		field.setValue(context.record.data.administered_by);
		Ext.Function.defer(function(){
			field.forceSelection = true;
		}, 200);
	},

	onPatientMedicationUserLiveSearchSelect: function(cmb, records){
		var user = records[0],
			record = cmb.up('form').getForm().getRecord();
        record.set({fname: user.data.fname});
        record.set({lname: user.data.lname});
        record.set({mname: user.data.mname});
        record.set({title: user.data.title});
		record.set({administered_uid: user.data.id});
	},

    onPatientMedicationUserLiveSearchReset: function(cmb){
        var record = cmb.up('form').getForm().getRecord();
        record.set({fname: ''});
        record.set({lname: ''});
        record.set({mname: ''});
        record.set({title: ''});
        record.set({administered_uid: ''});
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

		form.getRecord().set({
			RXCUI: records[0].data.RXCUI,
			CODE: records[0].data.CODE,
            GS_CODE: records[0].data.GS_CODE,
			NDC: records[0].data.NDC
		});
	},

	onPatientMedicationReconciledBtnClick: function(){
		this.onMedicationsPanelActive();
	},

    onMedicationsPanelActive: function(){
        var store = this.getPatientMedicationsGrid().getStore(),
            reconciled = this.getPatientMedicationReconciledBtn().pressed;

        store.clearFilter(true);
        store.load({
            filters: [
                {
                    property: 'pid',
                    value: app.patient.pid
                }
            ],
            params: {
                reconciled: reconciled
            }
        });
    }

});
