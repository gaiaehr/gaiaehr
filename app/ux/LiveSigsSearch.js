/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.ux.LiveSigsSearch', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.livesigssearch',

	initComponent: function(){
		var me = this;

		Ext.define('liveSigsSearchModel', {
			extend: 'Ext.data.Model',
			fields: [
				{ name: 'option_value', type: 'string' },
				{ name: 'option_name', type: 'string'    }
			],
			proxy: {
				type: 'direct',
				api: {
					read: 'Prescriptions.getSigCodesByQuery'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model: 'liveSigsSearchModel',
			pageSize: 25,
			autoLoad: false
		});

		Ext.apply(me, {
			store: me.store,
			displayField: 'option_value',
			valueField: 'option_value',
			emptyText: _('search') + '...',
			typeAhead: false,
			hideTrigger: true,
			minChars: 1,
			anchor: '100%',
			listConfig: {
				loadingText: _('searching') + '...',
				getInnerTpl: function(){
					return '<div class="search-item">{option_value} ({option_name})</div>';
				}
			},
			//				pageSize:25,
			listeners: {
				scope: me,
				beforeselect: me.onBeforeSigSelect,
				select: me.onBeSigSelect
			}
		});

		me.callParent(arguments);
	},

	onBeforeSigSelect: function(cmb, record){
		//say(cmb);
		//say(record);
	},

	onBeSigSelect: function(cmb, record){
		//say(cmb);
		//say(record);
	}


});
