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

Ext.define('App.view.administration.practice.FacilityConfig', {
	extend: 'Ext.panel.Panel',
	requires: [

	],
	xtype: 'facilityconfigpanel',
	title: _('facility_configuration'),
	itemId: 'FacilityStructurePanel',
	layout: {
		type: 'hbox',
		align: 'stretch'
	},
	items: [
		{
			xtype: 'container',
			margin: 5,
			flex: 1,
			layout: {
				type: 'vbox',
				align: 'stretch'
			},
			items: [
				{
					xtype: 'grid',
					title: _('departments'),
					hideHeaders: true,
					frame: true,
					margin: '0 0 5 0',
					store: Ext.create('App.store.administration.Departments', {
						autoLoad: true
					}),
					viewConfig: {
						plugins: {
							ptype: 'gridviewdragdrop',
							dragGroup: 'facilitygroup1',
							dropGroup: 'facilitygroup2',
							onViewRender: function(view){
								var me = this,
									scrollEl;

								if(me.enableDrag){
									if(me.containerScroll){
										scrollEl = view.getEl();
									}

									me.dragZone = new Ext.view.DragZone({
										view: view,
										copy: true,
										ddGroup: me.dragGroup || me.ddGroup,
										dragText: me.dragText,
										containerScroll: me.containerScroll,
										scrollEl: scrollEl
									});
								}

								if(me.enableDrop){
									me.dropZone = new Ext.grid.ViewDropZone({
										view: view,
										ddGroup: me.dropGroup || me.ddGroup
									});
								}
							}
						},
						listeners: {
							drop: function(node, data, dropRec, dropPosition){

								say(node);
								say(data);
								say(dropRec);
								say(dropPosition);

								//var dropOn = dropRec ? ' ' + dropPosition + ' ' + dropRec.get('name') : ' on empty view';
								//Ext.example.msg('Drag from right to left', 'Dropped ' + data.records[0].get('name') + dropOn);
							}
						}
					},
					columns: [
						{
							text: 'title',
							dataIndex: 'title',
							flex: 1
						}
					]
				},
				{
					xtype: 'grid',
					title: _('specialties'),
					//					hideHeaders: true,
					frame: true,
					flex: 1,
					store: this._sepecialtyStore = Ext.create('App.store.administration.Specialties', {
						autoLoad: true
					}),
					viewConfig: {
						plugins: {
							ptype: 'gridviewdragdrop',
							dragGroup: 'facilitygroup1',
							dropGroup: 'facilitygroup2'
						},
						listeners: {
							drop: function(node, data, dropRec, dropPosition){

								say(node);
								say(data);
								say(dropRec);
								say(dropPosition);

								//var dropOn = dropRec ? ' ' + dropPosition + ' ' + dropRec.get('name') : ' on empty view';
								//Ext.example.msg('Drag from right to left', 'Dropped ' + data.records[0].get('name') + dropOn);
							}
						}
					},
					columns: [
						{
							width: 200,
							text: _('title'),
							dataIndex: 'title',
							sortable: true,
							flex: 1,
							editor: {
								xtype: 'textfield'
							}
						},
						{
							text: _('taxonomy'),
							sortable: true,
							dataIndex: 'taxonomy',
							flex: 1,
							editor: {
								xtype: 'textfield'
							}
						},
						{
							text: _('modality'),
							sortable: true,
							dataIndex: 'modality',
							flex: 1,
							editor: {
								xtype: 'textfield'
							}
						},
						{
							text: _('active'),
							sortable: true,
							dataIndex: 'active',
							renderer: function(v){
								return app.boolRenderer(v);
							},
							editor: {
								xtype: 'checkboxfield'
							}
						}
					],
					plugins: [
						{
							ptype: 'rowediting',
							clicksToEdit: 2
						}
					],
					tools: [
						{
							xtype: 'button',
							text: _('specialty'),
							iconCls: 'icoAdd',
							itemId: 'specialitiesAddBtn'
						}
					],
					bbar: Ext.create('Ext.PagingToolbar', {
						pageSize: 10,
						store: this._sepecialtyStore,
						displayInfo: true,
						plugins: Ext.create('Ext.ux.SlidingPager', {})
					})
				}
			]
		},
		{
			xtype: 'treepanel',
			title: _('facility_structure'),
			itemId: 'FacilityStructureTreePanel',
			store: Ext.create('App.store.administration.FacilityStructures', {
				autoLoad: true
			}),
			columnLines: true,
			rootVisible: false,
			hideHeaders: true,
			frame: true,
			margin: '5 5 5 0',
			flex: 1,
//			useArrows:true,
			viewConfig: {
				plugins: {
					ptype: 'treeviewdragdrop',
					dragGroup: 'facilitygroup1',
					dropGroup: 'facilitygroup1',
					expandDelay: 0,
					allowParentInsert: true,
					allowContainerDrops: true,
					onViewRender: function(view){
						var me = this,
							scrollEl;

						if(me.enableDrag){
							if(me.containerScroll){
								scrollEl = view.getEl();
							}
							me.dragZone = new Ext.tree.ViewDragZone({
								view: view,
								ddGroup: me.dragGroup || me.ddGroup,
								dragText: me.dragText,
								displayField: me.displayField,
								repairHighlightColor: me.nodeHighlightColor,
								repairHighlight: me.nodeHighlightOnRepair,
								scrollEl: scrollEl
							});
						}

						if(me.enableDrop){
							me.dropZone = new Ext.tree.ViewDropZone({
								view: view,
								ddGroup: me.dropGroup || me.ddGroup,
								allowContainerDrops: me.allowContainerDrops,
								appendOnly: me.appendOnly,
								allowParentInserts: me.allowParentInserts,
								expandDelay: me.expandDelay,
								dropHighlightColor: me.nodeHighlightColor,
								dropHighlight: me.nodeHighlightOnDrop,
								sortOnDrop: me.sortOnDrop,
								containerScroll: me.containerScroll,
								handleNodeDrop: function(data, targetNode, position){

									var me = this,
										targetView = me.view,
										parentNode = targetNode ? targetNode.parentNode : targetView.panel.getRootNode(),
										Model = targetView.getStore().treeStore.model,
										records, i, len, record,
										insertionMethod, argList,
										needTargetExpand,
										transferData,
										isDepartment = data.records[0] instanceof App.model.administration.Department,
										isSpecialty = data.records[0] instanceof App.model.administration.Specialty,
										workedRecords = [];

									if(isDepartment || isSpecialty){

										for(i = 0, len = data.records.length; i < len; i++){
											Ext.Array.push(workedRecords, new Model({
												id: '',
												fid: 1,
												foreign_id: data.records[i].data.id,
												foreign_type: isDepartment ? 'D' : 'S',
												text: data.records[i].data.title,
												active: false
											}));
										}
										data.records = workedRecords;

									}

									// If the copy flag is set, create a copy of the models
									if(data.copy){
										records = data.records;
										data.records = [];
										for(i = 0, len = records.length; i < len; i++){
											record = records[i];

											if(record.isNode){
												data.records.push(record.copy(undefined, true));
											}else{
												// If it's not a node, make a node copy
												data.records.push(new Model(record.data, record.getId()));
											}
										}
									}

									// Cancel any pending expand operation
									me.cancelExpand();

									// Grab a reference to the correct node insertion method.
									// Create an arg list array intended for the apply method of the
									// chosen node insertion method.
									// Ensure the target object for the method is referenced by 'targetNode'
									if(position == 'before'){
										insertionMethod = parentNode.insertBefore;
										argList = [null, targetNode];
										targetNode = parentNode;
									}
									else if(position == 'after'){
										if(targetNode.nextSibling){
											insertionMethod = parentNode.insertBefore;
											argList = [null, targetNode.nextSibling];
										}
										else{
											insertionMethod = parentNode.appendChild;
											argList = [null];
										}
										targetNode = parentNode;
									}
									else{
										if(!(targetNode.isExpanded() || targetNode.isLoading())){
											needTargetExpand = true;
										}
										insertionMethod = targetNode.appendChild;
										argList = [null];
									}

									// A function to transfer the data into the destination tree
									transferData = function(){
										var color,
											n;

										// Coalesce layouts caused by node removal, appending and sorting
										Ext.suspendLayouts();

										targetView.getSelectionModel().clearSelections();

										// Insert the records into the target node
										for(i = 0, len = data.records.length; i < len; i++){
											record = data.records[i];
											if(!record.isNode){
												if(record.isModel){
													record = new Model(record.data, record.getId());
												}else{
													record = new Model(record);
												}

											}

											record.save({
												callback: function(rec, operation){
													rec.set({'id': operation.response.result.id});
													rec.save();
												}
											});

											data.records[i] = record;

											argList[0] = record;
											insertionMethod.apply(targetNode, argList);
										}

										// If configured to sort on drop, do it according to the TreeStore's comparator
										if(me.sortOnDrop){
											targetNode.sort(targetNode.getOwnerTree().store.generateComparator());
										}

										Ext.resumeLayouts(true);

										// Kick off highlights after everything's been inserted, so they are
										// more in sync without insertion/render overhead.
										// Element.highlight can handle highlighting table nodes.
										if(Ext.enableFx && me.dropHighlight){
											color = me.dropHighlightColor;

											for(i = 0; i < len; i++){
												n = targetView.getNode(data.records[i]);
												if(n){
													Ext.fly(n).highlight(color);
												}
											}
										}
									};

									// If dropping right on an unexpanded node, transfer the data after it is expanded.
									if(needTargetExpand){
										targetNode.expand(false, transferData);
									}
									// If the node is waiting for its children, we must transfer the data after the expansion.
									// The expand event does NOT signal UI expansion, it is the SIGNAL for UI expansion.
									// It's listened for by the NodeStore on the root node. Which means that listeners on the target
									// node get notified BEFORE UI expansion. So we need a delay.
									// TODO: Refactor NodeInterface.expand/collapse to notify its owning tree directly when it needs to expand/collapse.
									else if(targetNode.isLoading()){
										targetNode.on({
											expand: transferData,
											delay: 1,
											single: true
										});
									}
									// Otherwise, call the data transfer function immediately
									else{
										transferData();
									}
								}
							})
						}
					}
				}
			},
			columns: [
				{
					xtype: 'treecolumn',
					text: 'Config',
					flex: 2,
					sortable: true,
					dataIndex: 'text'
				},
				{
					xtype: 'actioncolumn',
					width: 20,
					icon: 'resources/images/icons/delete.png',
					tooltip: _('delete'),
					handler: function(grid, rowIndex, colIndex, item, e, record){

						if(record.childNodes.length > 0){
							app.msg(_('oops'), _('please_remove_child_records_first'), true);
							return;
						}

						Ext.Msg.show({
							title: _('wait'),
							msg: _('delete_this_record'),
							buttons: Ext.Msg.YESNO,
							icon: Ext.Msg.QUESTION,
							fn: function(btn){
								if(btn == 'yes'){
									record.destroy();
								}

							}
						});
					},
					getClass: function(value, metadata, record){
						if(record.data.id[0] != 'f'){
							return 'x-grid-center-icon';
						}else{
							return 'x-hide-display';
						}
					}
				}
			]
		}
	]
});
