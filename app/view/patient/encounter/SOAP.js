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
		'App.ux.combo.Specialties',
		'App.ux.grid.RowFormEditing',
		'App.ux.grid.RowFormEditing',
		'App.view.patient.encounter.CarePlanGoals',
		'App.view.patient.encounter.CarePlanGoalsNewWindow',
		'App.ux.LiveSnomedProcedureSearch',
		'App.view.patient.encounter.AdministeredMedications',
		'App.view.patient.encounter.AppointmentRequestGrid'
	],
	action: 'patient.encounter.soap',
	itemId: 'soapPanel',
	title: _('soap'),
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
			title: _('snippets'),
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
					text: _('category'),
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

						return '<span ' + toolTip + '>' + (v !== '' ? v : record.data.text) + '</span>'
					}
				},
				{
					text: _('add'),
					width: 25,
					menuDisabled: true,
					xtype: 'actioncolumn',
					tooltip: _('add_snippet'),
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
					text: _('edit'),
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
			bbar:[
				{
					xtype: 'specialtiescombo',
					itemId: 'SoapTemplateSpecialtiesCombo',
					flex: 1
				}
			],
			viewConfig: {
				plugins: {
					ptype: 'treeviewdragdrop',
					expandDelay: 500,
					dragText: _('drag_and_drop_reorganize')
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
				autoSync: g('autosave'),
				syncAcl: a('edit_encounters')
			},
			items: [
				me.pWin = Ext.widget('window', {
					title: _('procedure'),
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
									xtype: 'snomedliveproceduresearch',
									name: 'code_text',
									displayField: 'FullySpecifiedName',
									valueField: 'FullySpecifiedName',
									listeners: {
										scope: me,
										select: me.onProcedureSelect
									}
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
							text: _('cancel'),
							scope: me,
							handler: me.onProcedureCancel
						},
						{
							text: _('save'),
							scope: me,
							itemId: 'encounterRecordAdd',
							handler: me.onProcedureSave
						}
					]
				}),
				{
					xtype: 'fieldset',
					title: _('subjective'),
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
					title: _('objective'),
					margin: 5,
					items: [
						me.oField = Ext.widget('textarea', {
							name: 'objective',
							anchor: '100%'
						}),
						me.pGrid = Ext.widget('grid', {
							frame: true,
							name: 'procedures',
							emptyText: _('no_procedures'),
							margin: '5 0 10 0',
							store: me.procedureStore,
							columns: [
								{
									text: _('code'),
									dataIndex: 'code'
								},
								{
									text: _('description'),
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
											text: _('procedures')
										},
										'->',
										{
											text: _('new_procedure'),
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
					title: _('assessment'),
					margin: 5,
					items: [
						me.aField = Ext.widget('textarea', {
							name: 'assessment',
							anchor: '100%'
						}),
						me.dxField = Ext.widget('icdsfieldset', {
							name: 'dxCodes',
							margin: '5 0 10 0',
							itemId: 'SoapDxCodesField'
						})
					]
				},
				{
					xtype: 'fieldset',
					title: _('plan'),
					margin: 5,
					items: [
						me.pField = Ext.widget('textarea', {
							fieldLabel: _('instructions'),
							labelAlign: 'top',
							name: 'instructions',
							margin: '0 0 10 0',
							anchor: '100%'
						}),
						{
							xtype: 'appointmentrequestgrid',
							margin: '0 0 10 0'
						},
						{
							xtype: 'careplangoalsgrid',
							margin: '0 0 10 0'
						}
					]
				}
			],
			buttons: [
				{
					text: _('save'),
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
			title: _('complete_snippet'),
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
					text: _('shift_enter_submit')
				},
				'->',
				{
					text: _('cancel'),
					handler: me.onPhWindowCancel
				},
				{
					text: _('submit'),
					scope: me,
					handler: me.onPhWindowSubmit
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
			code: record[0].data.ConceptId,
			code_type: record[0].data.CodeType,
			code_text: record[0].data.FullySpecifiedName
		});
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
		this.pWin.setTitle(_('procedure'));
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
		this.pWin.setTitle(_('procedure'));
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
		if(record.data.code_text !== '' || record.data.code !== ''){
			this.pWin.setTitle(record.data.code_text + ' [' + record.data.code + ']');
		}else{
			this.pWin.setTitle(_('new_procedure'));
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
				action = view.panel.action.split('-'),
				field = form.findField(action[0]),
				text = record.data.text,
				value = field.getValue(),
				PhIndex = text.indexOf('??'),
				textArea = me.phWindow.down('textarea'),
				glue = value.substr(value.length - 1) == ' ' ? '' : ' ';

			if(PhIndex == -1){
				field.setValue(value + glue + text);
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
			action = me.snippets.action.split('-'),
			field = form.findField(action[0]),
			value = field.getValue(),
			text = textArea.getValue(),
			glue = value.substr(value.length - 1) == ' ' ? '' : ' ';

		field.setValue(value + glue + text);
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
