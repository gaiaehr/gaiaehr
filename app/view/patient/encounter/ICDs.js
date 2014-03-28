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

Ext.define('App.view.patient.encounter.ICDs', {
	extend: 'Ext.form.FieldSet',
	alias: 'widget.icdsfieldset',
	title: i18n('icds_live_search'),
	padding: '10 15',
	margin: '0 0 3 0',
	layout: 'anchor',
	requires: [ 'App.ux.LiveICDXSearch' ],
	autoFormSync: true,
	initComponent: function(){
		var me = this;

		me.items = [
			{
				xtype: 'liveicdxsearch',
				itemId: 'liveicdxsearch',
				emptyText: me.emptyText,
				name: 'dxCodes',
				listeners: {
					scope: me,
					select: me.onLiveIcdSelect,
					blur: function(field){
						field.reset();
					}
				}
			},
			{
				xtype: 'container',
				itemId: 'idcsContainer',
				action: 'idcsContainer'
			}
		];

		me.callParent(arguments);
	},

	onLiveIcdSelect: function(field, record){
		var me = this,
			soap = me.up('form').getForm().getRecord(),
			dxRecords;

		dxRecords = this.store.add({
			pid: soap.data.pid,
			uid: app.user.id,
			code: record[0].data.code,
			code_type: record[0].data.code_type,
			code_text: record[0].data.code_text
		});

		me.addIcd(dxRecords[0]);
		field.reset();
	},

	removeIcds: function(){
		this.getIcdContainer().removeAll();
	},

	loadIcds: function(store){
		var me = this,
			dxs = store.data.items;

		me.store = store;
		me.removeIcds();
		me.loading = true;

		for(var i = 0; i < dxs.length; i++){
			me.addIcd(dxs[i]);
		}
		me.loading = false;
		me.getIcdLiveSearch().reset();
	},

	addIcd: function(record){
		var me = this;
		me.getIcdContainer().add({
			xtype: 'customtrigger',
			value: record.data.code,
			dxRecord: record,
			width: 100,
			style: 'float:left',
			margin: '0 5 0 0',
			name: me.name,
			editable: false,
			listeners: {
				afterrender: function(btn){
					this.toolTip = Ext.create('Ext.tip.ToolTip', {
						target: btn.id,
						html: record.data.code_text
					});
					if(me.autoFormSync && !me.loading) record.store.sync();

				},
				destroy: function(){
					this.toolTip.destroy();
					me.store.remove(this.dxRecord);
					if(me.autoFormSync) me.store.sync();
				}
			}
		});
	},

	getIcdContainer: function(){
		return this.getComponent('idcsContainer');
	},

	getIcdLiveSearch: function(){
		return this.getComponent('liveicdxsearch');
	}

});