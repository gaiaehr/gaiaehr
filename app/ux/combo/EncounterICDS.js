Ext.define('App.ux.combo.EncounterICDS', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.encountericdscombo',
	initComponent: function() {
		var me = this;

		Ext.define('EncounterICDXComboModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'code', type: 'string' },
				{name: 'short_desc', type: 'string' }
			],
			proxy : {
				type       : 'direct',
				api        : {
					read: 'Encounter.getEncounterDxs'
				}
			}
		});

		Ext.apply(this, {
			queryMode   : 'local',
			editable    : false,
			multiSelect : true,
			displayField: 'code',
			valueField  : 'code',
			emptyText   : i18n('select'),
			store       : Ext.create('Ext.data.Store', {
				model   : 'EncounterICDXComboModel',
				autoLoad: false
			}),
			listConfig  : {
				getInnerTpl: function() {
					return '<span style="font-weight:bold">{code}</span> - {short_desc}</div>';
				}
			}
		});

		me.callParent(arguments);
	}
});