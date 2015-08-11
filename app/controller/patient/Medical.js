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

Ext.define('App.controller.patient.Medical', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'MedicalWindow',
			selector: '#MedicalWindow'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'viewport': {
				'navkey': me.onNavKey
			},
			'#MedicalWindow #immunization': {
				'show': me.onPanelShow
			},
			'#MedicalWindow #allergies': {
				'show': me.onPanelShow
			},
			'#MedicalWindow #activeproblems': {
				'show': me.onPanelShow
			},
			'#MedicalWindow #medications': {
				'show': me.onPanelShow
			},
			'#MedicalWindow #laboratories': {
				'show': me.onPanelShow
			},
			'#MedicalWindow #socialhistory': {
				'show': me.onPanelShow
			},
			'#MedicalWindow #referrals': {
				'show': me.onPanelShow
			}
		});

	},

	onNavKey: function(e, key){
		if(!app.patient.pid) {
			app.msg(_('oops'), _('patient_error'), true);
			return;
		}
		var win = this.getMedicalWindow().show();

		switch(key){
			case e.ONE:
				win.cardSwitch('immunization');
				break;
			case e.TWO:
				win.cardSwitch('allergies');
				break;
			case e.THREE:
				win.cardSwitch('activeproblems');
				break;
			case e.FOUR:
				win.cardSwitch('medications');
				break;
			case e.FIVE:
				win.cardSwitch('laboratories');
				break;
			case e.SIX:
				win.cardSwitch('socialhistory');
				break;
			case e.SEVEN:
				win.cardSwitch('referrals');
				break;
		}
	},

	onPanelShow:function(panel){
		this.setWindowTitle(panel.title);
	},

	setWindowTitle:function(title){
		this.getMedicalWindow().setTitle(
            app.patient.name +
            ' (' + title + ') ' +
            (app.patient.readOnly ? '-  <span style="color:red">[Read Mode]</span>' :'')
        );
	}


});