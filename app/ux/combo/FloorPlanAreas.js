Ext.define('App.ux.combo.FloorPlanAreas', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.floorplanareascombo',
	initComponent: function() {
		var me = this;

		Ext.define('FloorPlanAreasModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'title', type: 'string' },
				{name: 'id', type: 'int' }
			],
			proxy : {
				type       : 'direct',
				api        : {
					read: CombosData.getFloorPlanAreas
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'FloorPlanAreasModel',
			autoLoad:true
		});

		Ext.apply(this, {
			editable    : false,
			queryMode: 'local',
			displayField: 'title',
			valueField  : 'id',
			emptyText   : i18n('select'),
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});