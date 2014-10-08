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

Ext.define('App.controller.patient.Summary', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'PatientSummaryPanel',
			selector: 'PatientSummaryPanel'
		},
		{
			ref: 'PatientDocumentPanel',
			selector: 'patientdocumentspanel'
		},
		{
			ref: 'PatientCcdPanel',
			selector: 'patientccdpanel'
		},
		{
			ref: 'ReferralPanelGrid',
			selector: 'patientreferralspanel'
		},
		{
			ref: 'AddReferralBtn',
			selector: 'button[action=addReferralBtn]'
		},
		{
			ref: 'PrintReferralBtn',
			selector: '#printReferralBtn'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'#PatientSummaryPanel': {
				activate: me.onPatientSummaryPanel
			},
			'#PatientSummaryEncountersPanel': {
				itemdblclick: me.onPatientSummaryEncounterDblClick
			},
			'#PatientSummaryDisclosuresPanel': {
				show: me.reloadGrid
			},
			'#PatientSummeryNotesPanel': {
				show: me.reloadGrid
			},
			'#PatientSummaryRemindersPanel': {
				show: me.reloadGrid
			},
			'#PatientSummaryVitalsPanel': {
				show: me.reloadGrid
			},
			'#PatientEncounterHistoryPanel': {
				show: me.reloadGrid
			},
			'#PatientSummaryDocumentsPanel': {
				show: me.reloadGrid
			},
			'#PatientSummaryPreventiveCareAlertsPanel': {
				show: me.reloadGrid
			}
		});

		me.nav = me.getController('Navigation');
	},

	onPatientSummaryPanel: function(panel){
		var params = this.nav.getExtraParams();

		if(params){
			if(params.document){
				panel.down('tabpanel').setActiveTab(this.getPatientDocumentPanel());
			}else if(params.ccd){
				panel.down('tabpanel').setActiveTab(this.getPatientCcdPanel());
			}
		}
	},

	onPatientSummaryEncounterDblClick: function(grid, record){
		app.openEncounter(record.data.eid);
	},

	reloadGrid:function(grid){
		var store;
		if(grid.itemId == 'PatientSummaryVitalsPanel'){
			say(grid);

			store = grid.down('vitalsdataview').getStore();
		}else{
			store = grid.getStore();
		}

		store.clearFilter(true);
		store.load({
			params: {
				pid: app.patient.pid
			},
			filters:[
				[
					{
						property:'pid',
						value: app.patient.pid
					}
				]
			]
		})
	}

});