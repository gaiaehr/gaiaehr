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
			ref: 'AllergyTypeCombo',
			selector: '#allergyTypeCombo'
		},
		{
			ref: 'AllergyTypesCombo',
			selector: '#allergyTypesCombo'
		},
		{
			ref: 'AllergyCombo',
			selector: '#allergyCombo'
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
				change: me.onAllergyTypeComboChange
			},
			'#allergyMedicationCombo': {
				select: me.onAllergyLiveSearchSelect
			},
			'#allergyLocationCombo': {
				select: me.onAllergyLocationComboSelect
			}
		});
	},

	onAllergyLiveSearchSelect: function(cmb, records){
		var form = cmb.up('form').getForm();

		form.getRecord().set({
			allergy_code: records[0].data.RXCUI,
			allergy_code_type: records[0].data.CodeType
		});
	},

	onAllergyTypeComboChange: function(combo){
		var me = this,
			type = combo.getValue(),
			isDrug = type == 'Drug';

		me.getAllergyMedicationCombo().setVisible(isDrug);
		me.getAllergyMedicationCombo().setDisabled(!isDrug);
		me.getAllergyTypesCombo().setVisible(!isDrug);
		me.getAllergyTypesCombo().setDisabled(isDrug);

		if(isDrug){
			me.getAllergyMedicationCombo().reset();
		}else{
			me.getAllergyTypesCombo().store.load({params: {allergy_type: type}});
		}
	},

	onAllergyLocationComboSelect: function(combo, record){
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
						property: 'end_date',
						value: '0000-00-00'
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
		var params = {
			eid: app.patient.eid,
			area: 'review_allergies'
		};
		Medical.reviewMedicalWindowEncounter(params, function(provider, response){
			app.msg('Sweet!', i18n('succefully_reviewed'));
		});
	}

});