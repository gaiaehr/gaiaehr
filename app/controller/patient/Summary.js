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
            '#PatientSummaryContactsPanel': {
                activate: me.reloadGrid
            },
			'#PatientSummaryDisclosuresPanel': {
				activate: me.reloadGrid
			},
			'#PatientSummeryNotesPanel': {
				activate: me.reloadGrid
			},
			'#PatientSummaryRemindersPanel': {
				activate: me.reloadGrid
			},
			'#PatientSummaryVitalsPanel': {
				activate: me.reloadGrid
			},
			'#PatientEncounterHistoryPanel': {
				activate: me.reloadGrid
			},
			'#PatientSummaryDocumentsPanel': {
				activate: me.reloadGrid
			},
			'#PatientSummaryPreventiveCareAlertsPanel': {
				activate: me.reloadGrid
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
			store = grid.down('vitalsdataview').getStore();
		}else{
			store = grid.getStore();
		}
		store.load({
			filters:[
				{
					property:'pid',
					value: app.patient.pid
				}
			]
		})
	}

});
