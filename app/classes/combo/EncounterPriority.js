Ext.define('App.classes.combo.EncounterPriority', {
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
					list_id: 92
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
			emptyText   : 'Select',
			store       : me.store,
			listConfig  : {
				getInnerTpl: function() {
					return '<span class="{option_name}">{option_name}</span></div>';
				}
			},
			listeners:{
				select:function(cmb, record){


					var val = record[0].data.option_value, bgColor, color;

					if(val == 'Minimal'){
						bgColor = '#008000';
						color = '#ffffff';
					}else if(val == 'Delayed'){
						bgColor = '#ffff00';
						color = '#000000';
					}else if(val == 'Immediate'){
						bgColor = '#ff0000';
						color = '#ffffff';
					}else if(val == 'Expectant'){
						bgColor = '#808080';
						color = '#ffffff';
					}else if(val == 'Deceased'){
						bgColor = '#000000';
						color = '#ffffff';
					}

					this.inputEl.setStyle({
						'background-color':bgColor,
						'background-image':'none',
						'color':color
					})
				}
			}
		}, null);
		me.callParent(arguments);
	}
});