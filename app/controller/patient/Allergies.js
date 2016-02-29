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

Ext.define('App.controller.patient.Allergies', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'AllergiesGrid',
			selector: 'patientallergiespanel'
		},
		{
			ref: 'AddAllergyBtn',
			selector: 'patientallergiespanel #addAllergyBtn'
		},
		{
			ref: 'ReviewAllergiesBtn',
			selector: 'patientallergiespanel #reviewAllergiesBtn'
		},
		{
			ref: 'ActiveAllergyBtn',
			selector: 'patientallergiespanel #activeAllergyBtn'
		},
		{
			ref: 'AllergyCombo',
			selector: '#allergyCombo'
		},
		{
			ref: 'AllergyTypesCombo',
			selector: '#allergyTypesCombo'
		},

		{
			ref: 'AllergySearchCombo',
			selector: '#allergySearchCombo'
		},
		{
			ref: 'AllergyMedicationCombo',
			selector: '#allergyMedicationCombo'
		},
		{
			ref: 'AllergyReactionCombo',
			selector: '#allergyReactionCombo'
		},
		{
			ref: 'AllergySeverityCombo',
			selector: '#allergySeverityCombo'
		},
		{
			ref: 'AllergyLocationCombo',
			selector: '#allergyLocationCombo'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'patientallergiespanel': {
				activate: me.onAllergiesGridActivate
			},
			'patientallergiespanel #addAllergyBtn': {
				click: me.onAddAllergyBtnClick
			},
			'patientallergiespanel #activeAllergyBtn': {
				toggle: me.onActiveAllergyBtnToggle
			},
			'patientallergiespanel #reviewAllergiesBtn': {
				toggle: me.onReviewAllergiesBtnClick
			},
			'#allergyTypeCombo': {
				select: me.onAllergyTypeComboSelect
			},
			'#allergyMedicationCombo': {
				select: me.onAllergyLiveSearchSelect
			},
			'#allergyLocationCombo': {
				change: me.onAllergyLocationComboChange
			},
			'#allergySearchCombo': {
				select: me.onAllergySearchComboSelect
			},
			'#allergyReactionCombo': {
				select: me.onAllergyReactionComboSelect
			},
			'#allergySeverityCombo': {
				select: me.onAllergySeverityComboSelect
			},
			'#allergyStatusCombo': {
				select: me.onAllergyStatusComboSelect
			}
		});
	},

	onAllergySearchComboSelect:function(cmb, records){
		var record = cmb.up('form').getForm().getRecord();
		record.set({
			allergy_code: records[0].data.allergy_code,
			allergy_code_type: records[0].data.allergy_code_type
		});
	},

	onAllergyReactionComboSelect:function(cmb, records){
		var record = cmb.up('form').getForm().getRecord();
		record.set({
			reaction_code: records[0].data.code,
			reaction_code_type: records[0].data.code_type
		});
	},

	onAllergySeverityComboSelect:function(cmb, records){
		var record = cmb.up('form').getForm().getRecord();
		record.set({
			severity_code: records[0].data.code,
			severity_code_type: records[0].data.code_type
		});
	},

	onAllergyStatusComboSelect:function(cmb, records){
		var record = cmb.up('form').getForm().getRecord();

		record.set({
			status_code: records[0].data.code,
			status_code_type: records[0].data.code_type
		});
	},

	onAllergyLiveSearchSelect: function(cmb, records){
		var form = cmb.up('form').getForm();

		form.getRecord().set({
			allergy: records[0].data.STR,
			allergy_code: records[0].data.RXCUI,
			allergy_code_type: records[0].data.CodeType
		});
	},

	onAllergyTypeComboSelect: function(combo, records){

		var me = this,
			record = records[0],
			code = record.data.code,
			isDrug = code == '419511003' || code == '416098002' || code == '59037007';

		me.getAllergyMedicationCombo().setVisible(isDrug);
		me.getAllergyMedicationCombo().setDisabled(!isDrug);

		me.getAllergySearchCombo().setVisible(!isDrug);
		me.getAllergySearchCombo().setDisabled(isDrug);

		if(isDrug){
			me.getAllergyMedicationCombo().reset();
		}else{
			me.getAllergySearchCombo().store.load();
		}

		combo.up('form').getForm().getRecord().set({
			allergy_type_code: record.data.code,
			allergy_type_code_type: record.data.code_type
		});
	},

	onAllergyLocationComboChange: function(combo, record){
		var me = this,
			list,
			value = combo.getValue();

		if(value == 'Skin'){
			list = 80;
		}else if(value == 'Local'){
			list = 81;
		}else if(value == 'Abdominal'){
			list = 82;
		}else if(value == 'Systemic / Anaphylactic'){
			list = 83;
		}

		me.getAllergyReactionCombo().getStore().load({
			params: {
				list_id: list
			}
		});
	},

	onAllergiesGridActivate: function(){
		var store = this.getAllergiesGrid().getStore();
		store.clearFilter(true);
		store.filter([
			{
				property: 'pid',
				value: app.patient.pid
			}
		]);
	},

	onAddAllergyBtnClick: function(){
		var me = this,
			grid = me.getAllergiesGrid(),
			store = grid.getStore();

		grid.editingPlugin.cancelEdit();
		store.insert(0, {
			created_uid: app.user.id,
			uid: app.user.id,
			pid: app.patient.pid,
			eid: app.patient.eid,
			create_date: new Date(),
			begin_date: new Date()
		});
		grid.editingPlugin.startEdit(0, 0);
	},

	onActiveAllergyBtnToggle: function(btn, pressed){
		var me = this,
			store = me.getAllergiesGrid().getStore();

		if(pressed){
			store.load({
				filters: [
					{
						property: 'pid',
						value: app.patient.pid
					},
					{
						property: 'status',
						value: 'Active'
					}
				]
			})
		}else{
			store.load({
				filters: [
					{
						property: 'pid',
						value: app.patient.pid
					}
				]
			})
		}
	},

	beforeAllergyEdit: function(editor, e){
		this.allergieMedication.setValue(e.record.data.allergy);
	},

	onReviewAllergiesBtnClick: function(){
		var encounter = this.getController('patient.encounter.Encounter').getEncounterRecord();
		encounter.set({review_allergies:true});
		encounter.save({
			success: function(){
				app.msg('Sweet!', _('items_to_review_save_and_review'));
			},
			failure: function(){
				app.msg('Oops!', _('items_to_review_entry_error'));
			}
		});
	}

});
