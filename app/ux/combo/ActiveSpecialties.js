Ext.define('App.ux.combo.ActiveSpecialties', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.activespecialtiescombo',
	displayField: 'text_details',
	valueField: 'id',
	editable: false,
	emptyText: i18n('select'),
	queryMode: 'local',
	store: Ext.create('App.store.administration.Specialties',{
		filters: [
			{
				property:'active',
				value: true
			}
		],
		pageSize: 500,
		autoLoad: true
	})
});