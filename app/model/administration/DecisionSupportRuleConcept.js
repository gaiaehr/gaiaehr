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

Ext.define('App.model.administration.DecisionSupportRuleConcept', {
	extend: 'Ext.data.Model',
	table: {
		name: 'support_rule_concepts'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'rule_id',
			type: 'int',
			comment: 'support_rule.id'
		},
		{
			name: 'concept_type',
			type: 'string',
			len: 10,
			comment: 'PROC PROB MEDI ALLE LAB VITA'
		},
		{
			name: 'concept_text',
			type: 'string',
			len: 25
		},
		{
			name: 'concept_code',
			type: 'string',
			len: 25
		},
		{
			name: 'concept_code_type',
			type: 'string',
			len: 10
		},
		{
			name: 'frequency',
			type: 'int',
			len: 2
		},
		{
			name: 'frequency_interval',
			type: 'string',
			len: 2,
			comment: '1D = one day 2M = two month 1Y = one year'
		},
		{
			name: 'frequency_operator',
			type: 'string',
			len: 5,
			comment: '== != <= >= < >'
		},
		{
			name: 'value',
			type: 'string',
			len: 10
		},
		{
			name: 'value_operator',
			type: 'string',
			len: 5,
			comment: '== != <= >= < >'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'DecisionSupport.getDecisionSupportRuleConcepts',
			create: 'DecisionSupport.addDecisionSupportRuleConcept',
			update: 'DecisionSupport.updateDecisionSupportRuleConcept',
			destroy: 'DecisionSupport.deleteDecisionSupportRuleConcept'
		}
	}
});