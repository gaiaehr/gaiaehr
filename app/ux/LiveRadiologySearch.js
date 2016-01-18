/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2012 Ernesto Rodriguez
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
Ext.define('App.ux.LiveRadiologySearch', {
	extend: 'Ext.form.ComboBox',
	requires:['App.store.administration.CPT'],
	alias: 'widget.radiologylivetsearch',
	hideLabel: true,

	initComponent: function(){
		var me = this;

		me.store = Ext.create('App.store.administration.CPT',{
			pageSize: 10,
			autoLoad: false
		});

		me.store.proxy.extraParams = {
			onlyActive: true,
			isRadiology: true
		};

		Ext.apply(this, {
			store: me.store,
			displayField: 'code_text_medium',
			valueField: 'code_text_medium',
			emptyText: _('search') + '...',
			typeAhead: false,
			hideTrigger: true,
			minChars: 1,
			listConfig: {
				loadingText: _('searching') + '...',
				getInnerTpl: function(){
					return '<div class="search-item"><h3>{code_text_short} ({code})</h3></div>';
				}
			},
			pageSize: 10
		});

		me.callParent();
	}
});
