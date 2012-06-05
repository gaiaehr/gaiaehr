Ext.define('App.classes.combo.LabsTypes', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.labstypescombo',
	initComponent: function() {
		var me = this;

		Ext.define('LabsTypesComboModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id'},
				{name: 'code_text_short' },
				{name: 'parent_name', type: 'string' },
				{name: 'loinc_name', type: 'string' }
			],
			proxy : {
				type       : 'direct',
				api        : {
					read: Services.getActiveLaboratoryTypes
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'LabsTypesComboModel',
			autoLoad: false
		});

		Ext.apply(this, {
			editable    : false,
			//queryMode   : 'local',
			displayField: 'loinc_name',
			valueField  : 'loinc_name',
			emptyText   : 'Select',
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});