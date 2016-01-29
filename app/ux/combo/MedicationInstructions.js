Ext.define('App.ux.combo.MedicationInstructions', {
	extend: 'Ext.form.ComboBox',
	xtype: 'medicationinstructionscombo',
	queryMode: 'local',
	displayField: 'instruction',
	valueField: 'instruction',
	store: Ext.create('App.store.administration.MedicationInstructions')
});