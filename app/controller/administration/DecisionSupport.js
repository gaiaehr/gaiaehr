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

Ext.define('App.controller.administration.DecisionSupport', {
	extend: 'Ext.app.Controller',

	refs: [
		{
			ref: 'DecisionSupportAdminPanel',
			selector: 'decisionSupportAdminPanel'
		},
		{
			ref: 'DecisionSupportAdminGrid',
			selector: '#decisionSupportAdminGrid'
		},
		{
			ref: 'DecisionSupportRuleAddBtn',
			selector: '#decisionSupportRuleAddBtn'
		},
		{
			ref: 'DecisionSupportEditorTabPanel',
			selector: '#decisionSupportEditorTabPanel'
		},

		// editor grids
		{
			ref: 'DecisionSupportProcGrid',
			selector: 'grid[action=PROC]'
		},
		{
			ref: 'DecisionSupportProBGrid',
			selector: 'grid[action=PROB]'
		},
		{
			ref: 'DecisionSupportSociGrid',
			selector: 'grid[action=SOCI]'
		},
		{
			ref: 'DecisionSupportMediGrid',
			selector: 'grid[action=MEDI]'
		},
		{
			ref: 'DecisionSupportAlleGrid',
			selector: 'grid[action=ALLE]'
		},
		{
			ref: 'DecisionSupportLabGrid',
			selector: 'grid[action=LAB]'
		},
		{
			ref: 'DecisionSupportVitaGrid',
			selector: 'grid[action=VITA]'
		},
		{
			ref: 'DecisionSupportVitalCombo',
			selector: '#DecisionSupportVitalCombo'
		},
		{
			ref: 'DecisionSupportSocialHistoryCombo',
			selector: '#DecisionSupportSocialHistoryCombo'
		}
	],

	init: function(){
		var me = this;

		me.control({
			'#decisionSupportAdminPanel': {
				activate: me.onDecisionSupportAdminPanelActive
			},
			'#decisionSupportRuleAddBtn': {
				click: me.onDecisionSupportRuleAddBtnClick
			},

			'#decisionSupportAdminGrid': {
				beforeedit: me.onDecisionSupportAdminGridBeforeEdit
			},

			'#DecisionSupportProcedureCombo': {
				select: me.onDecisionSupportProcedureComboSelect
			},
			'#DecisionSupportProblemCombo': {
				select: me.onDecisionSupportProblemComboSelect
			},
			'#DecisionSupportMedicationCombo': {
				select: me.onDecisionSupportMedicationComboSelect
			},
			'#DecisionSupportMedicationAllergyCombo': {
				select: me.onDecisionSupportMedicationAllergyComboSelect
			},
			'#DecisionSupportLabCombo': {
				select: me.onDecisionSupportLabComboSelect
			},
			'#DecisionSupportVitalAddBtn': {
				click: me.onDecisionSupportVitalAddBtnClick
			},
			'#DecisionSupportSocialHistoryAddBtn': {
				click: me.onDecisionSupportSocialHistoryAddBtnClick
			},
			'#DecisionSupportSocialHistoryCombo': {
				beforerender: me.onDecisionSupportSocialHistoryComboBeforeRender
			}
		});
	},

	onDecisionSupportAdminPanelActive: function(){
		this.getDecisionSupportAdminGrid().getStore().load();
	},

	onDecisionSupportRuleAddBtnClick: function(btn){
		var grid = btn.up('grid');

		grid.editingPlugin.cancelEdit();
		grid.getStore().insert(0, {
			create_date: new Date(),
			update_date: new Date(),
			create_uid: app.user.id,
			update_uid: app.user.id,
			active: 1
		});
		grid.editingPlugin.startEdit(0, 0);
	},

	onDecisionSupportAdminGridBeforeEdit: function(plugin, context){
		var editor = plugin.editor,
			record = context.record,
			grids = editor.query('grid'),
            grid,
            store,
            i;

		this.getDecisionSupportEditorTabPanel().setActiveTab(0);

		for(i = 0; i < grids.length; i++){
			grid = grids[i],
				store = grid.getStore();
			store.grid = grid;
			store.load({
				filters: [
					{
						property: 'rule_id',
						value: record.data.id
					},
					{
						property: 'concept_type',
						value: grid.action
					}
				],
				callback: function(records, operation, success){
					this.grid.setTitle(this.grid.initialConfig.title + ' (' + records.length + ')')
				}
			});
		}
	},

	onDecisionSupportProcedureComboSelect: function(cmb, records){
		var grid = cmb.up('grid'),
			store = grid.getStore(),
            foo;

		grid.editingPlugin.cancelEdit();
		foo = store.add({
			rule_id: this.getRuleId(),
			concept_type: grid.action,
			concept_code: records[0].data.code,
			concept_text: records[0].data.code_text,
			concept_code_type: records[0].data.code_type
		});
		grid.editingPlugin.startEdit(foo[0], 2);
	},

	onDecisionSupportProblemComboSelect: function(cmb, records){
		var grid = cmb.up('grid'),
			store = grid.getStore(),
            foo;

		grid.editingPlugin.cancelEdit();
		foo = store.add({
			rule_id: this.getRuleId(),
			concept_type: grid.action,
			concept_code: records[0].data.ConceptId,
			concept_text: records[0].data.FullySpecifiedName,
			concept_code_type: records[0].data.CodeType
		});
		grid.editingPlugin.startEdit(foo[0], 2);
	},

	onDecisionSupportMedicationComboSelect: function(cmb, records){
		var grid = cmb.up('grid'),
			store = grid.getStore(),
            foo;

		grid.editingPlugin.cancelEdit();
		foo = store.add({
			rule_id: this.getRuleId(),
			concept_type: grid.action,
			concept_code: records[0].data.RXCUI,
			concept_text: records[0].data.STR,
			concept_code_type: records[0].data.CodeType
		});
		grid.editingPlugin.startEdit(foo[0], 2);
	},

	onDecisionSupportMedicationAllergyComboSelect: function(cmb, records){
		var grid = cmb.up('grid'),
			store = grid.getStore(),
            foo;

		grid.editingPlugin.cancelEdit();
		foo = store.add({
			rule_id: this.getRuleId(),
			concept_type: grid.action,
			concept_code: records[0].data.RXCUI,
			concept_text: records[0].data.STR,
			concept_code_type: records[0].data.CodeType
		});
		grid.editingPlugin.startEdit(foo[0], 2);
	},

	onDecisionSupportLabComboSelect: function(cmb, records){
		var grid = cmb.up('grid'),
			store = grid.getStore(),
            foo;

		grid.editingPlugin.cancelEdit();
		foo = store.add({
			rule_id: this.getRuleId(),
			concept_type: grid.action,
			concept_code: records[0].data.loinc_number,
			concept_text: records[0].data.loinc_name,
			concept_code_type: 'LOINC'
		});
		grid.editingPlugin.startEdit(foo[0], 2);
	},

	onDecisionSupportVitalAddBtnClick: function(){
		var cmb = this.getDecisionSupportVitalCombo(),
			cmcStore = cmb.getStore(),
			record = cmcStore.findRecord('option_value', cmb.getValue()),
			grid = cmb.up('grid'),
			store = grid.getStore(),
            foo;

		grid.editingPlugin.cancelEdit();
		foo = store.add({
			rule_id: this.getRuleId(),
			concept_type: grid.action,
			concept_code: record.data.code,
			concept_text: record.data.option_name,
			concept_code_type: record.data.code_type
		});
		grid.editingPlugin.startEdit(foo[0], 2);
	},

	onDecisionSupportSocialHistoryAddBtnClick: function(){
		var cmb = this.getDecisionSupportSocialHistoryCombo(),
			cmcStore = cmb.getStore(),
			record = cmcStore.findRecord('option_value', cmb.getValue()),
			grid = cmb.up('grid'),
			store = grid.getStore(),
            foo;

		grid.editingPlugin.cancelEdit();
		foo = store.add({
			rule_id: this.getRuleId(),
			concept_type: grid.action,
			concept_code: record.data.code,
			concept_text: record.data.option_name,
			concept_code_type: record.data.code_type
		});
		grid.editingPlugin.startEdit(foo[0], 2);
	},

	onDecisionSupportSocialHistoryComboBeforeRender: function(cmb){
		cmb.getStore().on('load', function(store){
			store.insert(0,{
				code: 'smoking_status',
				option_name: _('smoking_status'),
				option_value: 'smoking_status',
				code_type: ''
			});
		});
	},

	getRuleId: function(){
		return this.getDecisionSupportEditorTabPanel().up('form').getForm().getRecord().data.id;
	},

	doRemoveRule: function(record){
		record.store.remove(record);
	},

	doRemoveRuleConcept: function(record){
		record.store.remove(record);
	}

});
