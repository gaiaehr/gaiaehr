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

Ext.define('App.view.patient.NewPatient', {
	extend       : 'App.ux.RenderPanel',
	id           : 'panelNewPatient',
	pageTitle    : i18n('patient_entry_form'),
	initComponent: function() {

		var me = this;

		me.pageBody = [
			me.newPatientPanel = Ext.create('App.view.patient.Patient') ];
		me.callParent(arguments);

	},
	/**
	 *
	 * @param {function} callback
	 */
	confirmationWin: function(callback) {
		Ext.Msg.show({
			title  : i18n('please_confirm') + '...',
			msg    : i18n('do_you_want_to_create_a_new_patient'),
			icon   : Ext.MessageBox.QUESTION,
			buttons: Ext.Msg.YESNO,
			scope  : this,
			fn     : function(btn) {
				callback(btn);
			}
		});
	},

	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 * @param {function} [callback] - callback
	 */
	onActive: function(callback) {
        var me = this;
		this.confirmationWin(function(btn) {
			if(btn == 'yes') {
                me.newPatientPanel.loadNew();
				app.unsetPatient(null, true);
				callback(true);
			} else {
				app.nav.goBack();
				callback(false);
			}
		});
	}
});