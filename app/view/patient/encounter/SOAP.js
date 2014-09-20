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
		'App.ux.grid.RowFormEditing',
		'App.view.patient.encounter.CarePlanGoals',
		'App.view.patient.encounter.CarePlanGoalsNewWindow'
	],
	action: 'patient.encounter.soap',
	itemId: 'soapPanel',
	title: i18n('soap'),
	layout: 'border',
	frame: true,

	pid: null,
	eid: null,

	initComponent: function(){
		var me = this;

		me.snippetStore = Ext.create('App.store.patient.encounter.snippetTree', {
			autoLoad: false
		});

		me.procedureStore = Ext.create('App.store.patient.encounter.Procedures');

		var snippetCtrl = App.app.getController('patient.encounter.Snippets');

		me.snippets = Ext.create('Ext.tree.Panel', {
			title: i18n('snippets'),
			itemId: 'SnippetsTreePanel',
			region: 'west',
			width: 300,
			split: true,
			animate: false,
			hideHeaders: true,
			useArrows: true,
			rootVisible: false,
			singleExpand: true,
			collapsed: !eval(g('enable_encounter_soap_templates')),
			collapsible: true,
			collapseMode: 'mini',
			hideCollapseTool: true,
			store: me.snippetStore,
			tools: [
				{
					xtype: 'button',
					text: i18n('category'),
					iconCls: 'icoAdd',
					itemId: 'SnippetCategoryAddBtn'
				}
			],
			columns: [
				{
					xtype: 'treecolumn', //this is so we know which column will show the tree
					text: 'Template',
					flex: 1,
					dataIndex: 'title',
					renderer: function(v, meta, record){
						var toolTip = record.data.text ? ' data-qtip="' + record.data.text + '" ' : '';

						return '<span ' + toolTip + '>' + (v != '' ? v : record.data.text) + '</span>'
					}
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
					handler: function(grid, rowIndex, colIndex, actionItem, event, record){
						snippetCtrl.onSnippetAddBtnClick(grid, rowIndex, colIndex, actionItem, event, record);
					},
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
					handler: function(grid, rowIndex, colIndex, actionItem, event, record){
						snippetCtrl.onSnippetBtnEdit(grid, rowIndex, colIndex, actionItem, event, record);
					}
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
			listeners: {
				scope: me,
				itemclick: me.onSnippetClick,
				itemdblclick: me.onSnippetDblClick
			}
		});

		me.form = Ext.create('Ext.form.Panel', {
			autoScroll: true,
			action: 'encounter',
			bodyStyle: 'background-color:white',
			region: 'center',
			itemId: 'soapForm',
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
					itemId: 'soapProcedureWindow',
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
							itemId: 'encounterRecordAdd',
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
							margin: '5 0 10 0'
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
							anchor: '100%'
						}),
						me.pGrid = Ext.widget('grid', {
							frame: true,
							name: 'procedures',
							emptyText: i18n('no_procedures'),
							margin: '5 0 10 0',
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
									]
								}

							]
						})
					]
				},
				{
					xtype: 'fieldset',
					title: i18n('assessment'),
					margin: 5,
					items: [
						me.aField = Ext.widget('textarea', {
							name: 'assessment',
							anchor: '100%'
						}),
						me.dxField = Ext.widget('icdsfieldset', {
							name: 'dxCodes',
							margin: '5 0 10 0'
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
							anchor: '100%'
						}),
						{
							xtype: 'careplangoalsgrid',
							margin: '0 0 10 0'
						}
					]
				}
			],
			buttons: [
				{
					text: i18n('save'),
					iconCls: 'save',
					action: 'soapSave',
					scope: me,
					itemId: 'encounterRecordAdd',
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

		me.callParent(arguments);

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
			code: record[0].data.code,
			code_type: record[0].data.code_type,
			code_text: record[0].data.code_text
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
		this.pWin.close();
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
		this.pWin.close();
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
			this.pWin.setTitle(record.data.code_text + ' [' + record.data.code + ']');
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
	}
});