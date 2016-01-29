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

Ext.define('App.view.patient.AdvanceDirectives', {
	extend: 'Ext.grid.Panel',
	requires: [

	],
	xtype: 'patientadvancedirectivepanel',
	title: _('advance_directives'),
	columnLines: true,
	store: Ext.create('App.store.patient.AdvanceDirectives', {
		remoteFilter: true,
		autoSync: false
	}),
	columns: [
		{
			text: _('directive'),
			flex: 1,
			dataIndex: 'code_text',
			editor:{
				xtype:'gaiaehr.combo',
				list: 129,
				loadStore: true
			}
		},
		{
			text: _('status'),
			dataIndex: 'status_code_text',
			flex: 1,
			editor:{
				xtype:'gaiaehr.combo',
				list: 128,
				loadStore: true
			}
		},
		{
			text: _('notes'),
			flex: 2,
			dataIndex: 'notes',
			editor:{
				xtype:'textfield'
			}
		},
		{
			xtype: 'datecolumn',
			text: _('start_date'),
			dataIndex: 'start_date',
			editor:{
				xtype:'datefield'
			}
		},
		{
			xtype: 'datecolumn',
			text: _('end_date'),
			dataIndex: 'end_date',
			editor:{
				xtype:'datefield'
			}
		},
		{
			xtype: 'datecolumn',
			text: _('verified_date'),
			dataIndex: 'verified_date',
			editor:{
				xtype:'datefield'
			}
		}
	],
	plugins: [
		{
			ptype:'rowediting',
			errorSummary: false
		}
	],
	tbar:[
		'->',
		{
			text: _('add_new'),
			itemId: 'AdvanceDirectiveAddBtn',
			action: 'encounterRecordAdd',
			iconCls: 'icoAdd'
		}
	],
	bbar: [
		'->',
		{
			text: _('review'),
			itemId: 'AdvanceDirectiveReviewBtn',
			action: 'encounterRecordAdd'
		}
	]


});