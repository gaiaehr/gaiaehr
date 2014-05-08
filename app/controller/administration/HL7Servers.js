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

Ext.define('App.controller.administration.HL7Servers', {
    extend: 'Ext.app.Controller',

	refs: [
		{
			ref:'HL7ServersPanel',
			selector:'hl7serverspanel'
		},
		{
			ref:'HL7ServersGrid',
			selector:'#hl7serversgrid'
		}
	],

	init: function() {
		var me = this;

		me.control({
			'hl7serverspanel':{
				show: me.onHL7ServersPanelShow
			},
			'#hl7serversgrid':{
				beforeedit: me.onHL7ServersGridBeforeEdit,
				validateedit: me.onHL7ServersGridValidateEdit
			},
			'#hl7serversgrid #addHL7ServerBtn':{
				click: me.onAddHL7ServerBtnClick
			},
			'#hl7serversgrid #removeHL7ServerBtn':{
				click: me.onRemoveHL7ServerBtnClick
			}
		});

	},

	onAddHL7ServerBtnClick: function(){
		var me = this,
			grid = me.getHL7ServersGrid(),
			store = grid.getStore();

		grid.editingPlugin.cancelEdit();
		store.insert(0, {

		});
		grid.editingPlugin.startEdit(0, 0);


	},

	onRemoveHL7ServerBtnClick: function(){

	},

	serverStartHandler: function(record){
		HL7ServerHandler.start({ ip: record.data.ip, port: record.data.port }, function(provider, response){
			say(response.result);
			record.set({'online':response.result.online});
			record.commit();
		});
	},

	serverStopHandler: function(record){
		HL7ServerHandler.stop({ ip: record.data.ip, port: record.data.port }, function(provider, response){
			say(response.result);
			record.set({'online':response.result.online});
			record.commit();
		});
	},

	onHL7ServersPanelShow: function(){
		this.reloadStore();
	},

	reloadStore:function(){
		this.getHL7ServersGrid().getStore().load();
	},

	onHL7ServersGridBeforeEdit: function(plugin, e){
		var multiField = plugin.editor.query('multitextfield')[0],
			data = e.record.data.allow_ips;

		Ext.Function.defer(function(){
			multiField.setValue(data);
		}, 10);
	},

	onHL7ServersGridValidateEdit: function(plugin, e){
		var multiField = plugin.editor.query('multitextfield')[0],
			values = multiField.getValue();
		e.record.set({allow_ips: values});
	},




});