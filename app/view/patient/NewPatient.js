/**
 * NewPatient.Js
 * Patient Layout Panel
 * v0.0.5
 *
 *
 * GaiaEHR (Eletronic Health Records) 2011
 *
 * Author   : GI Technologies, 2011
 * Modified : Ernesto J Rodriguez (Certun) 10/25/2011
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
	 * @param callback
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
	 */
	onActive: function(callback) {
        var me = this;
		this.confirmationWin(function(btn) {
			if(btn == 'yes') {
                me.newPatientPanel.loadNew();
				app.unsetPatient(null, true);
				callback(true);
			} else {
				callback(false);
			}
		});
	}
});