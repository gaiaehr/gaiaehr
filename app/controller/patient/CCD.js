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

Ext.define('App.controller.patient.CCD', {
	extend: 'Ext.app.Controller',
	requires: [],
	refs: [
		{
			ref: 'PatientCcdPanel',
			selector: 'patientccdpanel'
		},
		{
			ref: 'PatientCcdPanelMiFrame',
			selector: 'patientccdpanel > miframe'
		},
		{
			ref: 'PatientCcdPanelEncounterCmb',
			selector: '#PatientCcdPanelEncounterCmb'
		},
		{
			ref: 'PatientCcdPanelExcludeCheckBoxGroup',
			selector: '#PatientCcdPanelExcludeCheckBoxGroup'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'patientccdpanel': {
				activate: me.onPanelActivate
			},
			'#viewCcdBtn': {
				click: me.onViewCcdBtnClick
			},
			'#archiveCcdBtn': {
				click: me.onArchiveCcdBtnClick
			},
			'#exportCcdBtn': {
				click: me.onExportCcdBtnClick
			},
			'#printCcdBtn': {
				click: me.onPrintCcdBtnClick
			},
			'#PatientCcdPanelEncounterCmb': {
				select: me.onPatientCcdPanelEncounterCmbSelect
			}
		});
	},

	eid: null,

	onPanelActivate: function(panel){
		panel.down('toolbar').down('#PatientCcdPanelEncounterCmb').setVisible(this.eid === null);
		this.onViewCcdBtnClick(panel.down('toolbar').down('button'));
	},

	onViewCcdBtnClick: function(btn){
		btn.up('panel').query('miframe')[0].setSrc(
			'dataProvider/CCDDocument.php?action=view&site=' + window.site +
			'&pid=' + app.patient.pid +
			'&eid=' + this.getEid(btn) +
			'&exclude=' + this.getExclusions(btn) +
			'&token=' + app.user.token
		);
	},

	onArchiveCcdBtnClick: function(btn){
		btn.up('panel').query('miframe')[0].setSrc(
			'dataProvider/CCDDocument.php?action=archive&site=' + window.site +
			'&pid=' + app.patient.pid +
			'&eid=' + this.getEid(btn) +
			'&exclude=' + this.getExclusions(btn) +
			'&token=' + app.user.token
		);
	},

	onExportCcdBtnClick: function(btn){
		btn.up('panel').query('miframe')[0].setSrc(
			'dataProvider/CCDDocument.php?action=export&site=' + window.site +
			'&pid=' + app.patient.pid +
			'&eid=' + this.getEid(btn) +
			'&exclude=' + this.getExclusions(btn) +
			'&token=' + app.user.token
		);
	},

	onPatientCcdPanelEncounterCmbSelect: function(cmb, records){
		cmb.selectedRecord = records[0];
		cmb.up('panel').query('miframe')[0].setSrc(
			'dataProvider/CCDDocument.php?action=view&site=' + window.site +
			'&pid=' + app.patient.pid +
			'&eid=' + this.getEid(cmb) +
			'&exclude=' + this.getExclusions(cmb) +
			'&token=' + app.user.token
		);
	},

	onPrintCcdBtnClick: function(btn){
		var cont = btn.up('panel').query('miframe')[0].frameElement.dom.contentWindow;
		cont.focus();
		cont.print();
	},

	getEid: function(cmp){
		var cmb = cmp.up('toolbar').query('#PatientCcdPanelEncounterCmb')[0];
		return cmb.selectedRecord ? cmb.selectedRecord.data.eid : this.eid;
	},

	cmbReset: function(cmp){
		var cmb = cmp.up('toolbar').query('#PatientCcdPanelEncounterCmb')[0];
		cmb.reset();
		delete cmb.selectedRecord;
	},

	getExclusions: function(cmp){
		var values = cmp.up('toolbar').query('#PatientCcdPanelExcludeCheckBoxGroup')[0].getValue(),
			excludes = values.exclude || [];
		return excludes.join ? excludes.join(',') : excludes;
	}

});
