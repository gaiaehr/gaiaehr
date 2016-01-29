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

Ext.define('App.controller.patient.Reminders', {
	extend: 'Ext.app.Controller',
	requires: [],
	refs: [
		{
			ref: 'RemindersAddBtn',
			selector: '#RemindersAddBtn'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'viewport': {
				encounterload: me.onEncounterOpen,
				patientset: me.onPatientSet
			},
			'#RemindersAddBtn': {
				click: me.onRemindersAddBtnClick
			},
			'#RemindersAlertOkBtn': {
				click: me.onRemindersAlertOkBtnClick
			},
			'#RemindersAlertCancelBtn': {
				click: me.onRemindersAlertCancelBtnClick
			}
		});
	},

	onRemindersAddBtnClick: function(btn){
		var grid = btn.up('grid'),
			store = grid.store;

		grid.plugins[0].cancelEdit();
		store.insert(0, {
			date: new Date(),
			pid: app.patient.pid,
			uid: app.user.id,
			eid: app.patient.eid
		});
		grid.plugins[0].startEdit(0, 0);
	},

	onRemindersAlertOkBtnClick: function(btn){
		var win = btn.up('window'),
			store = win.down('grid').getStore();

		store.sync();
		win.close();
	},

	onRemindersAlertCancelBtnClick: function(btn){
		var win = btn.up('window'),
			store = win.down('grid').getStore();

		store.rejectChanges();
		win.close();
	},

	onPatientSet: function(patient){
		this.getPatientReminders('Administrative', patient.pid);
	},

	onEncounterOpen: function(encounterRecord){
		this.getPatientReminders('Clinical', encounterRecord.data.pid);
	},

	getPatientReminders: function(type, pid){
		var me = this,
			action = 'RemindersAlertWindow' + type,
			query = Ext.ComponentQuery.query('window[action=' + action + ']'),
			store,
			win;

		if(query.length === 0){
			win = Ext.create('App.view.patient.RemindersAlert',{
				title: _('reminders') + ' (' + _(type.toLowerCase()) + ')',
				action: action
			});
		}else{
			win = query[0];
		}

		store = win.down('grid').getStore();
		win.close();
		store.load({
			filters: [
				{
					property: 'pid',
					value: pid
				},
				{
					property: 'type',
					value: type
				},
				{
					property: 'active',
					value: true
				}
			],
			callback: function(records){
				if(records.length > 0){
					me.playSound();
					win.show();
				}
			}
		});
	},

	playSound: function(){
		if(!this.alertAudio){
			this.alertAudio = Ext.core.DomHelper.append(Ext.getBody(), {
				html: '<audio autoplay id="reminderAlert" ><source src="resources/audio/sweetalertsound4.wav" type="audio/wav"></audio>'
			}, true);
		}else{
			this.alertAudio.dom.firstChild.currentTime=0;
			this.alertAudio.dom.firstChild.play();
		}
	}

});