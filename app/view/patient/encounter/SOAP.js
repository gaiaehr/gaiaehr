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

Ext.define('App.view.patient.encounter.SOAP', {
	extend: 'Ext.panel.Panel',
	requires: [
		'App.ux.grid.RowFormEditing'
	],
	action: ['patient.encounter.soap'],
	title: i18n('soap'),
	layout: 'border',
	frame: true,

	pid: null,
	eid: null,

	initComponent: function(){
		var me = this;

		me.snippetStore = Ext.create('App.store.patient.encounter.snippetTree',{
			autoLoad: false
		});
		me.procedureStore = Ext.create('App.store.patient.encounter.Procedures');

		me.snippets = Ext.create('Ext.tree.Panel', {
			title: i18n('snippets'),
			region: 'west',
			width: 300,
			split: true,
			hideHeaders: true,
			useArrows: true,
			rootVisible: false,
			singleExpand: true,
			store: me.snippetStore,
			tools: [
				{
					xtype: 'button',
					text: i18n('category'),
					scope: me,
					iconCls: 'icoAdd',
					handler: me.onAddSnippetCategory
				}
			],
			columns: [
				{
					xtype: 'treecolumn', //this is so we know which column will show the tree
					text: 'Template',
					flex: 1,
					dataIndex: 'text',
					renderer: me.snippetTextRenderer
				},
				{
					text: i18n('add'),
					width: 25,
					menuDisabled: true,
					xtype: 'actioncolumn',
					tooltip: i18n('add_snippet'),
					align: 'center',
					icon: 'resources/images/icons/add.gif',
					scope: me,
					handler: me.onSnippetBtnAdd,
					getClass: function(value, metadata, record){
						if(!record.data.leaf){
							return 'x-grid-center-icon';
						}else{
							return 'x-hide-display';
						}
					}
				},
				{
					text: i18n('edit'),
					width: 25,
					menuDisabled: true,
					xtype: 'actioncolumn',
					tooltip: 'Edit task',
					align: 'center',
					icon: 'resources/images/icons/edit.png',
					handler: me.onSnippetBtnEdit
				}
			],
			viewConfig: {
				plugins: {
					ptype: 'treeviewdragdrop',
					expandDelay: 500,
					dragText: i18n('drag_and_drop_reorganize')
				},
				listeners: {
					scope: me,
					drop: me.onSnippetDrop
				}
			},
			plugins: [
				{
					ptype: 'rowformediting',
					clicksToMoveEditor: 1,
					enabled: false,
					enableRemove: true,
					formItems: [
						{
							xtype: 'textarea',
							height: 100,
							anchor: '100%',
							name: 'text'
						}
					]
				}
			],
			//			buttons:[
			//				{
			//					text:i18n('add_category'),
			//					flex:1,
			//					scope:me,
			//					handler:me.onAddSnippetCategory
			//				}
			//			],
			listeners: {
				scope: me,
				itemclick: me.onSnippetClick,
				itemdblclick: me.onSnippetDblClick,
				beforeedit: me.onSnippetBeforeEdit,
				canceledit: me.onSnippetCancelEdit,
				beforeremove: me.onSnippetBeforeRemove,
				remove: me.onSnippetRemove,
				edit: me.onSnippetEdit
			}
		});

		me.form = Ext.create('Ext.form.Panel', {
			autoScroll: true,
			action: 'encounter',
			bodyStyle: 'background-color:white',
			region: 'center',
			fieldDefaults: {
				msgTarget: 'side'
			},
			plugins: {
				ptype: 'advanceform',
				autoSync: globals['autosave'],
				syncAcl: acl['edit_encounters']
			},
			items: [
				me.pWin = Ext.widget('window', {
					title: i18n('procedure'),
					maximized: true,
					closable: false,
					constrain: true,
					closeAction: 'hide',
					layout: 'fit',
					items: [
						me.pForm = Ext.widget('form', {
							bodyPadding: 10,
							layout: {
								type: 'vbox',
								align: 'stretch'
							},
							items: [
								{
									xtype: 'livecptsearch',
									name: 'code',
									displayField: 'code',
									valueField: 'code',
									listeners: {
										scope: me,
										select: me.onProcedureSelect
									}
								},
								{
									xtype: 'textfield',
									name: 'code_text'
								},
								{
									xtype: 'textareafield',
									name: 'observation',
									flex: 1
								}
							]
						})
					],
					buttons: [
						{
							text: i18n('cancel'),
							scope: me,
							handler: me.onProcedureCancel
						},
						{
							text: i18n('save'),
							scope: me,
							handler: me.onProcedureSave
						}
					]

				}),
				{
					xtype: 'fieldset',
					title: i18n('subjective'),
					margin: 5,
					items: [
						me.sField = Ext.widget('textarea', {
							name: 'subjective',
							anchor: '100%',
							enableKeyEvents: true,
							listeners: {
								scope: me,
								focus: me.onFieldFocus
							}
						})
					]
				},
				{
					xtype: 'fieldset',
					title: i18n('objective'),
					margin: 5,
					items: [
						me.oField = Ext.widget('textarea', {
							name: 'objective',
							anchor: '100%',
							listeners: {
								scope: me,
								focus: me.onFieldFocus
							}
						})
					]
				},
				me.pGrid = Ext.widget('grid', {
					frame: true,
					name: 'procedures',
					emptyText: i18n('no_procedures'),
					margin: 5,
					store: me.procedureStore,
					columns: [
						{
							text: i18n('code'),
							dataIndex: 'code'
						},
						{
							text: i18n('description'),
							dataIndex: 'code_text',
							flex: 1
						}
					],
					listeners: {
						scope: me,
						itemdblclick: me.procedureEdit
					},
					dockedItems: [
						{
							xtype: 'toolbar',
							items: [
								{
									xtype: 'tbtext',
									text: i18n('procedures')
								},
								'->',
								{
									text: i18n('new_procedure'),
									scope: me,
									handler: me.onProcedureAdd,
									iconCls: 'icoAdd'
								}
							],
							listeners: {
								scope: me,
								render: function(toolbar){
									toolbar.container.on('click', me.onFieldFocus, me);
								}
							}
						}

					]
				}),
				{
					xtype: 'fieldset',
					title: i18n('assessment'),
					margin: 5,
					items: [
						me.aField = Ext.widget('textarea', {
							name: 'assessment',
							anchor: '100%',
							listeners: {
								scope: me,
								focus: me.onFieldFocus
							}
						}),
						me.dxField = Ext.widget('icdsfieldset', {
							name: 'dxCodes'
						})
					]
				},
				{
					xtype: 'fieldset',
					title: i18n('plan'),
					margin: 5,
					items: [
						me.pField = Ext.widget('textarea', {
							name: 'plan',
							anchor: '100%',
							listeners: {
								scope: me,
								focus: me.onFieldFocus
							}
						})
					]
				}
			],
			buttons: [
				{
					text: i18n('save'),
					iconCls: 'save',
					action: 'soapSave',
					scope: me,
					handler: me.onSoapSave
				}
			],
			listeners: {
				scope: me,
				recordloaded: me.formRecordLoaded
			}
		});

		me.phWindow = Ext.widget('window', {
			title: i18n('complete_snippet'),
			closeAction: 'hide',
			bodyPadding: 0,
			bodyBorder: false,
			border: false,
			items: [
				{
					xtype: 'textarea',
					border: false,
					width: 500,
					height: 150,
					margin: 0,
					grow: true,
					enableKeyEvents: true,
					listeners: {
						scope: me,
						specialkey: me.onPhTextAreaKey
					}
				}
			],
			buttons: [
				{
					xtype: 'tbtext',
					text: i18n('shift_enter_submit')
				},
				'->',
				{
					text: i18n('submit'),
					scope: me,
					handler: me.onPhWindowSubmit
				},
				{
					text: i18n('cancel'),
					handler: me.onPhWindowCancel
				}
			]
		});

		Ext.apply(me, {
			items: [ me.snippets, me.form ]
		});

		me.callParent();
	},

	/**
	 *
	 * @param cmb
	 * @param record
	 */
	onProcedureSelect: function(cmb, record){
		var me = this,
			form = me.pForm.getForm(),
			procedure = form.getRecord();

		procedure.set({
			code:record[0].data.code,
			code_type:record[0].data.code_type,
			code_text:record[0].data.code_text
		});

		form.findField('code_text').setValue(record[0].data.code_text);
	},

	/**
	 *
	 */
	onProcedureAdd: function(){
		var me = this,
			rec;
		rec = Ext.create('App.model.patient.encounter.Procedures', {
			pid: me.pid,
			eid: me.eid,
			create_uid: app.user.id,
			update_uid: app.user.id,
			create_date: new Date(),
			update_date: new Date()
		});

		me.procedureStore.add(rec);
		me.procedureEdit(null, rec);
	},

	/**
	 *
	 */
	onProcedureCancel: function(){
		this.procedureStore.rejectChanges();
		this.pWin.hide(this.pGrid.el);
		this.query('button[action=soapSave]')[0].enable();
		this.pWin.setTitle(i18n('procedure'));
	},

	/**
	 *
	 */
	onProcedureSave: function(){
		var me = this,
			form = me.pForm.getForm(),
			record = form.getRecord(),
			values = form.getValues();

		record.set(values);

		this.procedureStore.sync();
		this.pWin.hide(this.pGrid.el);
		this.query('button[action=soapSave]')[0].enable();
		this.pWin.setTitle(i18n('procedure'));
	},

	/**
	 *
	 */
	onShow: function(){
		var me = this;
		me.callParent();

		me.sField.focus();

		if(me.eid != app.patient.eid){
			me.pid = app.patient.pid;
			me.eid = app.patient.eid;
			me.procedureStore.load({
				filters: [
					{
						property: 'eid',
						value: me.eid
					}
				]
			});
		}
	},

	/**
	 *
	 * @param view
	 * @param record
	 */
	procedureEdit: function(view, record){
		if(record.data.code_text != '' || record.data.code != ''){
			this.pWin.setTitle('[' + record.data.code + '] ' + record.data.code_text);
		}else{
			this.pWin.setTitle(i18n('new_procedure'));
		}

		this.pForm.getForm().loadRecord(record);
		this.pWin.show(this.pGrid.el);
		this.query('button[action=soapSave]')[0].disable();
	},

	/**
	 *
	 * @param btn
	 */
	onSoapSave: function(btn){
		this.enc.onEncounterUpdate(btn)
	},

	/**
	 *
	 * @param form
	 * @param record
	 */
	formRecordLoaded: function(form, record){
		var store = record.dxCodes();
		store.on('write', function(){
			record.store.fireEvent('write');
		});
		this.dxField.loadIcds(record.dxCodes());
	},

	/**
	 *
	 * @param field
	 */
	onFieldFocus: function(field){
		if(typeof field.name == 'undefined') field.name = 'procedure';
		this.snippets.setTitle(i18n(field.name) + ' ' + i18n('templates'));
		//		this.snippets.expand(false);
		if(this.snippets.action != field.name){
			this.snippets.getSelectionModel().deselectAll();
			this.snippetStore.load({params: {category: field.name}});
		}
		this.snippets.action = field.name;
	},

	/**
	 *
	 * @param v
	 * @param meta
	 * @param record
	 * @returns {string}
	 */
	snippetTextRenderer: function(v, meta, record){
		var toolTip = record.data.text ? ' data-qtip="' + record.data.text + '" ' : '';
		return '<span ' + toolTip + '>' + v + '</span>'
	},

	/**
	 *
	 * @param view
	 * @param record
	 */
	onSnippetClick: function(view, record){
		if(!record.data.leaf) record.expand();
	},

	/**
	 *
	 * @param view
	 * @param record
	 */
	onSnippetDblClick: function(view, record){
		if(record.data.leaf){
			var me = this,
				form = me.form.getForm(),
				action = view.panel.action,
				field = form.findField(action),
				text = record.data.text,
				value = field.getValue(),
				PhIndex = text.indexOf('??'),
				textArea = me.phWindow.down('textarea');

			if(PhIndex == -1){
				field.setValue(value + me.closeSentence(text));
			}else{
				me.phWindow.show();
				textArea.setValue(text);
				Ext.Function.defer(function(){
					textArea.selectText(PhIndex, PhIndex + 2)
				}, 300);
			}
		}else{
			record.expand();
		}
	},

	/**
	 *
	 */
	onPhWindowSubmit: function(){
		var me = this,
			textArea = me.phWindow.down('textarea'),
			form = me.form.getForm(),
			action = me.snippets.action,
			field = form.findField(action),
			value = field.getValue(),
			text = textArea.getValue();

		field.setValue(me.closeSentence(value) + ' ' + me.closeSentence(text));
		me.phWindow.close();
		textArea.reset();
	},

	/**
	 *
	 * @param btn
	 */
	onPhWindowCancel: function(btn){
		btn.up('window').close();
	},

	/**
	 *
	 * @param field
	 * @param e
	 */
	onPhTextAreaKey: function(field, e){
		if(e.getKey() == e.ENTER) this.onPhWindowSubmit();
	},

	/**
	 * This will add a period to the end of the sentence if last character is not a . ? or
	 * @param sentence
	 * @return {*|String|String|String|String|String|String|String|String|String|String}
	 */
	closeSentence: function(sentence){
		var v = Ext.String.trim(sentence),
			c = v.charAt(v.length - 1);
		if(v == '') return v;
		return ((c == '.' || c == '!' || c == '?') ? v : v + '. ');
	},

	/**
	 *
	 * @param grid
	 * @param rowIndex
	 */
	onSnippetBtnEdit: function(grid, rowIndex){
		grid.editingPlugin.enabled = true;
		grid.editingPlugin.startEdit(rowIndex, 0);
	},

	/**
	 *
	 * @param editor
	 * @param store
	 * @param record
	 */
	onSnippetRemove: function(editor, store, record){
		var me = this;
		store.sync({
			callback: function(){
				me.msg('Sweet!', 'Record removed.');
				// GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
				app.AuditLog('SOAP removed');
			}
		});
	},

	/**
	 *
	 * @param editor
	 * @param store
	 * @param record
	 * @returns {boolean}
	 */
	onSnippetBeforeRemove: function(editor, store, record){
		var me = this;
		if(record.childNodes[0]){
			me.msg('Oops!', 'Unable to remove category "' + record.data.text + '". Please delete snippets first.', true);
			return false;
		}else{
			return true;
		}
	},

	/**
	 *
	 * @param plugin
	 */
	onSnippetEdit: function(plugin){
		this.snippetStore.sync();
		plugin.enabled = false;
	},

	/**
	 *
	 * @param plugin
	 * @returns {boolean|enabled|Ext.Logger.enabled|Ext.draw.modifier.Highlight.enabled|Ext.chart.interactions.Abstract.enabled|Ext.resizer.SplitterTracker.enabled|*}
	 */
	onSnippetBeforeEdit: function(plugin){
		return plugin.enabled;
	},

	/**
	 *
	 * @param plugin
	 */
	onSnippetCancelEdit: function(plugin){
		plugin.enabled = false;
	},

	/**
	 *
	 * @param node
	 * @param data
	 * @param overModel
	 */
	onSnippetDrop: function(node, data, overModel){
		var me = this, pos = 10;
		for(var i = 0; i < overModel.parentNode.childNodes.length; i++){
			overModel.parentNode.childNodes[i].set({pos: pos});
			pos = pos + 10;
		}
		me.snippetStore.sync();
	},

	/**
	 *
	 * @param grid
	 * @param rowIndex
	 * @param colIndex
	 * @param actionItem
	 * @param event
	 * @param record
	 */
	onSnippetBtnAdd: function(grid, rowIndex, colIndex, actionItem, event, record){
		var me = this,
			rec;

		me.origScope.snippets.editingPlugin.cancelEdit();
		rec = me.origScope.snippetStore.getNodeById(record.data.id).appendChild({
			text: 'New Snippet',
			parentId: record.data.id,
			leaf: true
		});

		me.origScope.snippetStore.sync({
			callback: function(batch){
				rec.set({id: batch.proxy.reader.rawData.id});
				rec.commit();
				me.origScope.msg('Sweet!', 'Record Added');
				me.origScope.snippets.editingPlugin.enabled = true;
				me.origScope.snippets.editingPlugin.startEdit(rec, 0);
			}
		});
	},

	/**
	 *
	 */
	onAddSnippetCategory: function(){
		var me = this,
			selection = me.snippets.getSelectionModel().getSelection(),
			baseNode,
			record;

		if(selection.length == 0){
			baseNode = me.snippetStore.getRootNode();
		}else if(selection[0].data.leaf){
			baseNode = selection[0].parentNode;
		}else{
			baseNode = selection[0];
		}

		me.snippets.editingPlugin.cancelEdit();
		record = baseNode.appendChild({
			text: 'New Category',
			parentId: baseNode.data.id,
			category: me.snippets.action,
			leaf: false
		});

		me.snippetStore.sync({
			callback: function(batch){
				record.set({id: batch.proxy.reader.rawData.id});
				record.commit();
				me.msg('Sweet!', 'Record Added');
				me.snippets.editingPlugin.enabled = true;
				me.snippets.editingPlugin.startEdit(record, 0);
				// GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
				// app.AuditLog('SOAP added');
			}
		});
	}
});