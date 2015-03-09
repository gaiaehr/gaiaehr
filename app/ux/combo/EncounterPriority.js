Ext.define('App.ux.combo.EncounterPriority', {
	extend: 'App.ux.combo.Combo',
	xtype: 'encounterprioritycombo',
	editable: false,
	queryMode: 'local',
	displayField: 'option_name',
	valueField: 'option_value',
	emptyText: _('priority'),
	list: 94,
	loadStore: true
});