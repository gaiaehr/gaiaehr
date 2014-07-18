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
	requires: [

	],
	refs: [
		{
			ref: 'PatientCcdPanel',
			selector: 'patientccdpanel'
		},
		{
			ref: 'PatientCcdPanelMiFrame',
			selector: 'patientccdpanel > miframe'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'patientccdpanel':{
				activate: me.onViewCcdBtnClick
			},
			'#viewCcdBtn':{
				click: me.onViewCcdBtnClick
			},
			'#exportCcdBtn':{
				click: me.onExportCcdBtnClick
			}
		});
	},

	onViewCcdBtnClick:function(){
		this.getPatientCcdPanelMiFrame().setSrc('dataProvider/CCDDocument.php?action=view&site='+ window.site +'&pid=' + app.patient.pid + '&token=' + app.user.token);
		// GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
		app.AuditLog('Patient summary CCD viewed');
	},

	onExportCcdBtnClick:function(){
		this.getPatientCcdPanelMiFrame().setSrc('dataProvider/CCDDocument.php?action=export&site='+ window.site +'&pid=' + app.patient.pid + '&token=' + app.user.token);
		// GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
		app.AuditLog('Patient summary CCD exported');
	}


});