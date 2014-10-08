Ext.define('App.ux.combo.Specialties', {
	extend: 'App.ux.combo.ComboResettable',
	alias: 'widget.specialtiescombo',
	displayField: 'text_details',
	valueField: 'id',
	editable: false,
	emptyText: i18n('select'),
	queryMode: 'local',
	store: Ext.create('App.store.administration.Specialties',{
		pageSize: 500,
		autoLoad: true
	})
});