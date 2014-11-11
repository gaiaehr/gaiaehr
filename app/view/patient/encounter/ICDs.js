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
	title: i18n('dx_codes'),
	padding: '10 15',
	layout: {
		type: 'vbox',
		align: 'stretch'
	},
	requires: [ 'App.ux.LiveICDXSearch' ],
	autoFormSync: true,
	dxGroup: {},
	initComponent: function(){
		var me = this;

		me.items = [
			{
				xtype:'container',
				layout:'hbox',
				items:[
					{
						xtype:'combobox',
						store: Ext.create('Ext.data.Store', {
							fields: ['option', { name:'value', type: 'int' }],
							data : [
								{ option:'DX:1', value: 1 },
								{ option:'DX:2', value: 2 },
								{ option:'DX:3', value: 3 },
								{ option:'DX:4', value: 4 },
								{ option:'DX:5', value: 5 },
								{ option:'DX:6', value: 6 },
								{ option:'DX:7', value: 7 },
								{ option:'DX:8', value: 8 },
								{ option:'DX:9', value: 9 }
							]
						}),
						width: 55,
						itemId: this.id + '-group-cmb',
						queryMode: 'local',
						displayField: 'option',
						valueField: 'value',
						value: 1,
						margin: '0 3 0 0',
						forceSelection: true,
						editable: false
					},
					{
						xtype: 'liveicdxsearch',
						itemId: 'liveicdxsearch',
						emptyText: me.emptyText,
						name: 'dxCodes',
						flex: 1,
						listeners: {
							scope: me,
							select: me.onLiveIcdSelect,
							blur: function(field){
								field.reset();
							}
						}
					}
				]
			}
		];

		me.callParent(arguments);
	},

	getGroupContainer: function(group){

		var me = this;

		if(!this.dxGroup[group]){
			this.dxGroup[group] = Ext.widget('container',{
				layout: {
					type: 'table',
					columns: 6
				},
				itemId: this.id + '-group-' + group,
				margin: '5 0 0 0',
				items:[
					{ xtype:'container', itemId: this.id + '-dx-order-1', action: 'pointer' },
					{ xtype:'container', itemId: this.id + '-dx-order-2', action: 'pointer' },
					{ xtype:'container', itemId: this.id + '-dx-order-3', action: 'pointer' },
					{ xtype:'container', itemId: this.id + '-dx-order-4', action: 'pointer' },
					{ xtype:'container', itemId: this.id + '-dx-order-5', action: 'pointer' },
					{ xtype:'container', itemId: this.id + '-dx-order-6', action: 'pointer' },
					{ xtype:'container', itemId: this.id + '-dx-order-7', action: 'pointer' },
					{ xtype:'container', itemId: this.id + '-dx-order-8', action: 'pointer' },
					{ xtype:'container', itemId: this.id + '-dx-order-9', action: 'pointer' },
					{ xtype:'container', itemId: this.id + '-dx-order-10', action: 'pointer' },
					{ xtype:'container', itemId: this.id + '-dx-order-11', action: 'pointer' },
					{ xtype:'container', itemId: this.id + '-dx-order-12', action: 'pointer' }
				],
				listeners:{
					afterrender:function(dxsContainer){

						var dxContainers = dxsContainer.items.items;

						for(var k=0; k < dxContainers.length; k++){

							new Ext.dd.DropTarget(dxContainers[k].el, {
								// must be same as for tree
								ddGroup: 'group-' + group + '-dx',
								dropPos: false,
								dxsContainer: dxsContainer,
								dxContainer: dxContainers[k],

								notifyOver: function(dd, e, data){

//									say('notifyOver');

									var dx = data.panel,
										dxContainer = this.dxContainer,
										proxy = dd.proxy;

									if(dxContainer.items.items.length == 0){
										// is over empty dx container
										return false;
									}else if(dxContainer.items.items[0] == dx){
										return false;
									}else{

										var dragDxContainerIndex = dxsContainer.items.items.indexOf(dx.ownerCt),
											dropDxContainerIndex = dxsContainer.items.items.indexOf(dxContainer);

										this.dropBefore = dragDxContainerIndex > dropDxContainerIndex;
										this.dropPos = dxsContainer.items.items.indexOf(dxContainer);
									}
									return true;
								},

								notifyDrop: function(dd, e, data){

									dd.panelProxy.hide();
									dd.proxy.hide();
									Ext.suspendLayouts();
									if(this.lastPos !== false){

										var parentDragContainer = data.panel.ownerCt,
											parentDropContainer = this.dxsContainer.items.items[this.dropPos],
											parentDropDx = this.dxsContainer.items.items[this.dropPos].items.items[0],
											parentDragDx = data.panel;


										parentDragContainer.remove(parentDragDx, false);
										parentDropContainer.remove(parentDropDx, false);

										parentDropContainer.add(parentDragDx);
										parentDragContainer.add(parentDropDx);

									}

									Ext.resumeLayouts(true);
									delete this.dropPos;

									me.onReOrder(dxsContainer);

									return true;
								}
							});
						}
					}
				}
			});
		}

		this.add(this.dxGroup[group]);

		return this.dxGroup[group];
	},

	onLiveIcdSelect: function(field, record){
		var me = this,
			soap = me.up('form').getForm().getRecord(),
			group = me.getDxGroupCombo().getValue(),
			order = me.getNextOrder(group),
			dxRecords;

		dxRecords = this.store.add({
			pid: soap.data.pid,
			eid: soap.data.eid,
			uid: app.user.id,
			code: record[0].data.code,
			dx_group: group,
			dx_order: order,
			code_type: record[0].data.code_type,
			code_text: record[0].data.code_text
		});

		me.addIcd(dxRecords[0], group, order);
		field.reset();
	},

	removeIcds: function(){

		Ext.Object.each(this.dxGroup, function(key, group){
			Ext.destroy(group);
		});

		this.dxGroup = {};
	},

	loadIcds: function(store){

		var me = this,
			dxs = store.data.items;

		me.store = store;
		me.removeIcds();
		me.loading = true;

		for(var i = 0; i < dxs.length; i++){
			me.addIcd(dxs[i], dxs[i].data.dx_group, dxs[i].data.dx_order);
		}
		me.loading = false;
		me.getIcdLiveSearch().reset();
	},

	addIcd: function(record, group, order){

		this.getDxCell(group, order).add({
			xtype: 'panel',
			closable: true,
			title: record.data.code,
			dxRecord: record,
			width: 100,
			margin: '0 5 0 0',
			name: this.name,
			editable: false,
			action: 'Dx',
			draggable: {
				moveOnDrag: false,
				ddGroup: 'group-' + group + '-dx'
			}
		});
	},

	getDxCell: function(group, order){
		return this.getGroupContainer(group).getComponent(this.id + '-dx-order-' + order);
	},

	getIcdLiveSearch: function(){
		return this.query('liveicdxsearch')[0];
	},

	getDxGroupCombo: function(){
		return this.query('#' + this.id + '-group-cmb')[0];
	},

	getNextOrder: function(group){
		var pointers = this.getGroupContainer(group).query('container[action=pointer]'),
			i, len = pointers.length;

		for(i=0; i < len; i++){
			if(pointers[i].items.items.length == 0) return (i + 1);
		}
		return false;
	},

	onReOrder: function(group){
		var orders = group.query('container[action=pointer]'),
			len;

		len = orders.length;
		for(var i=0; i < len; i++){
			if(orders[i].items.items.length > 0 && orders[i].items.items[0].action == 'Dx'){
				orders[i].items.items[0].dxRecord.set({dx_order: (i+1)});
			}
		}
	},

	sync: function(){
		this.store.sync();
	}
});