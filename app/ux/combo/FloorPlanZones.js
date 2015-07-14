Ext.define('App.ux.combo.FloorPlanZones', {
	extend: 'Ext.form.ComboBox',
	xtype: 'floorplanazonescombo',
	editable: false,
	//queryMode: 'local',
	displayField: 'title',
	valueField: 'id',
	emptyText: _('select'),
	store: Ext.create('App.store.administration.FloorPlanZones')
});