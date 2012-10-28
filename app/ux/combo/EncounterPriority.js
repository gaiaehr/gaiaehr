Ext.define('App.ux.combo.EncounterPriority', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.encounterprioritycombo',
	initComponent: function() {
		var me = this;

		Ext.define('EncounterPriorityModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'option_name', type: 'string' },
				{name: 'option_value', type: 'string' }
			],
			proxy : {
				type       : 'direct',
				api        : {
					read: CombosData.getOptionsByListId
				},
				extraParams: {
					list_id: 94
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'EncounterPriorityModel',
			autoLoad: true
		});

		Ext.apply(this, {
			editable    : false,
			queryMode   : 'local',
			displayField: 'option_name',
			valueField  : 'option_value',
			emptyText   : i18n('priority'),
			store       : me.store,
			listConfig  : {
				getInnerTpl: function() {
					return '<span class="{option_name}">{option_name}</span></div>';
				}
			}
		}, null);

		me.on('change', function(cmb ,newValue){
			var bgColor, color;
			if(newValue == 'Minimal'){
				bgColor = '#008000';
				color = '#ffffff';
			}else if(newValue == 'Delayed'){
				bgColor = '#ffff00';
				color = '#000000';
			}else if(newValue == 'Immediate'){
				bgColor = '#ff0000';
				color = '#ffffff';
			}else if(newValue == 'Expectant'){
				bgColor = '#808080';
				color = '#ffffff';
			}else if(newValue == 'Deceased'){
				bgColor = '#000000';
				color = '#ffffff';
			}

			this.inputEl.setStyle({
				'background-color':bgColor,
				'background-image':'none',
				'color':color
			})
		}, me);
		me.callParent(arguments);
	}
});