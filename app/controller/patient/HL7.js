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
Ext.define('App.controller.patient.HL7', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: '#Adt04MessageBtn',
			selector: 'Adt04MessageBtn'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'#soapForm': {
				render: me.onSoapFormRender
			},
			'#Adt04MessageBtn': {
				click: me.onAdt04MessageBtnClick
			}
		});
	},

	onSoapFormRender: function(form){
		if(a('hl7_send_adt04')){
			form.getDockedItems()[0].insert(0, {
				xtype:'button',
				text: _('adt04'),
				itemId: 'Adt04MessageBtn'
			});
		}
	},

	onAdt04MessageBtnClick: function(){

		HL7Messages.broadcastADT({
			pid: app.patient.pid,
			eid: app.patient.eid,
			fid: app.user.facility,
			event: 'A04'
		}, function(response){
		});
	}



});
