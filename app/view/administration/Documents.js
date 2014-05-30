/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, LLC.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.view.administration.Documents', {
	extend: 'App.ux.RenderPanel',
	id: 'panelDocuments',
	pageTitle: i18n('document_template_editor'),
	pageLayout: 'border',
	requires: [
		'App.ux.grid.Button',
		'App.ux.GridPanel'
	],
	initComponent: function(){

		var me = this;

		// *************************************************************************************
		// Documents Stores
		// *************************************************************************************
		me.templatesDocumentsStore = Ext.create('App.store.administration.DocumentsTemplates');
		me.defaultsDocumentsStore = Ext.create('App.store.administration.DefaultDocuments');
		me.tokenStore = Ext.create('App.store.administration.DocumentToken');

		//		me.HeaderFootergrid = Ext.create('Ext.grid.Panel', {
		//			title      : i18n('header_footer_templates'),
		//			region     : 'south',
		//			height     : 250,
		//			split      : true,
		//			hideHeaders: true,
		//			store      : me.headersAndFooterStore,
		//			columns    : [
		//				{
		//					flex     : 1,
		//					sortable : true,
		//					dataIndex: 'title',
		//                    editor:{
		//                        xtype:'textfield',
		//                        allowBlank:false
		//                    }
		//				},
		//				{
		//					icon: 'resources/images/icons/delete.png',
		//					tooltip: i18n('remove'),
		//					scope:me,
		//					handler: me.onRemoveDocument
		//				}
		//			],
		//			listeners  : {
		//				scope    : me,
		//				itemclick: me.onDocumentsGridItemClick
		//			},
		//			tbar       :[
		//                '->',
		//                {
		//                    text : i18n('new'),
		//                    scope: me,
		//                    handler: me.newHeaderOrFooterTemplate
		//                }
		//            ],
		//            plugins:[
		//                me.rowEditor2 = Ext.create('Ext.grid.plugin.RowEditing', {
		//                    clicksToEdit: 2
		//                })
		//
		//            ]
		//		});

		me.DocumentsDefaultsGrid = Ext.create('Ext.grid.Panel', {
			title: i18n('documents_defaults'),
			region: 'north',
			width: 250,
			border: true,
			split: true,
			store: me.defaultsDocumentsStore,
			hideHeaders: true,
			columns: [
				{
					flex: 1,
					sortable: true,
					dataIndex: 'title',
					editor: {
						xtype: 'textfield',
						allowBlank: false
					}
				},
				{
					icon: 'resources/images/icons/delete.png',
					tooltip: i18n('remove'),
					scope: me,
					handler: me.onRemoveDocument
				}
			],
			listeners: {
				scope: me,
				itemclick: me.onDocumentsGridItemClick
			},
			tbar: ['->',
				{
					text: i18n('new'),
					scope: me,
					handler: me.newDefaultTemplates
				}],
			plugins: [me.rowEditor3 = Ext.create('Ext.grid.plugin.RowEditing',
				{
					clicksToEdit: 2
				})]
		});

		me.DocumentsGrid = Ext.create('Ext.grid.Panel', {
			title: i18n('document_templates'),
			region: 'center',
			width: 250,
			border: true,
			split: true,
			store: me.templatesDocumentsStore,
			hideHeaders: true,
			columns: [
				{
					flex: 1,
					sortable: true,
					dataIndex: 'title',
					editor: {
						xtype: 'textfield',
						allowBlank: false
					}
				},
				{
					icon: 'resources/images/icons/delete.png',
					tooltip: i18n('remove'),
					scope: me,
					handler: me.onRemoveDocument
				}
			],
			listeners: {
				scope: me,
				itemclick: me.onDocumentsGridItemClick
			},
			tbar: ['->',
				{
					text: i18n('new'),
					scope: me,
					handler: me.newDocumentTemplate
				}],
			plugins: [me.rowEditor = Ext.create('Ext.grid.plugin.RowEditing',
				{
					clicksToEdit: 2
				})]
		});

		me.LeftCol = Ext.create('Ext.container.Container', {
			region: 'west',
			layout: 'border',
			width: 250,
			border: false,
			split: true,
			items: [me.DocumentsDefaultsGrid, me.DocumentsGrid]
		});

		me.TeamplateEditor = Ext.create('Ext.form.Panel', {
			title: i18n('document_editor'),
			region: 'center',
			layout: 'fit',
			autoScroll: false,
			border: true,
			split: true,
			hideHeaders: true,
			items: {
				xtype: 'htmleditor',
				enableFontSize: false,
				name: 'body',
				margin: 5
			},
			buttons: [
				{
					text: i18n('save'),
					scope: me,
					handler: me.onSaveEditor
				},
				{
					text: i18n('cancel'),
					scope: me,
					handler: me.onCancelEditor
				}
			]
		});

		me.TokensGrid = Ext.create('App.ux.GridPanel', {
			title: i18n('available_tokens'),
			region: 'east',
			width: 250,
			border: true,
			split: true,
			hideHeaders: true,
			store: me.tokenStore,
			disableSelection: true,
			viewConfig: {
				stripeRows: false
			},
			columns: [
				{
					flex: 1,
					sortable: false,
					dataIndex: 'token'
				},
				{
					xtype: 'actioncolumn',
					width: 50,
					items: [
						{
							icon: 'resources/images/icons/copy.png',
							tooltip: i18n('copy'),
							margin: '0 5 0 0',
							handler: function(grid, rowIndex, colIndex, item, e, record){


//								btn.btnEl.set({
//									'data-clipboard-text': btn.record.data.token
//								});
//								AppClipboard.clip(btn.btnEl.dom);
							}
						}
					]
				}
//				{
//					xtype:'gridbutton',
//					width: 35,
//					items:[
//						{
//							xtype:'button',
//							icon:'resources/images/icons/copy.png',
//							listeners:{
//								render:function(btn){
//									btn.btnEl.set({
//										'data-clipboard-text': btn.record.data.token
//									});
//									AppClipboard.clip(btn.btnEl.dom);
//								}
//							}
//						}
//					]
//
//				}
//				{
//					dataIndex: 'token',
//					width: 30,
//					xtype: "templatecolumn",
//					tpl: new Ext.XTemplate("" +
//						"<object id='clipboard{token}' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0' width='16' height='16' align='middle'>",
//						"<param name='allowScriptAccess' value='always' />",
//						"<param name='allowFullScreen' value='false' />",
//						"<param name='movie' value='lib/ClipBoard/clipboard.swf' />",
//						"<param name='quality' value='high' />", "<param name='bgcolor' value='#ffffff' />",
//						"<param name='flashvars' value='callback=copyToClipBoard&callbackArg={token}' />",
//						"<embed src='lib/ClipBoard/clipboard.swf' flashvars='callback=copyToClipBoard&callbackArg={token}' quality='high' bgcolor='#ffffff' width='16' height='16' name='clipboard{token}' align='middle' allowscriptaccess='always' allowfullscreen='false' type='application/x-shockwave-flash' pluginspage='http://www.adobe.com/go/getflashplayer' />",
//						"</object>", null)
//				}
			]
		});

		me.pageBody = [me.LeftCol, me.TeamplateEditor, me.TokensGrid];
		me.callParent();
	},
	/**
	 * Delete logic
	 */
	onDelete: function(){

	},

	onTokensGridItemClick: function(){

	},

	onSaveEditor: function(){
		var me = this,
			form = me.down('form').getForm(),
			record = form.getRecord(),
			values = form.getValues();
		record.set(values);
		app.msg(i18n('sweet'), i18n('record_saved'));
	},

	onCancelEditor: function(){
		var me = this, form = me.down('form').getForm(), grid = me.DocumentsGrid;
		form.reset();
		grid.getSelectionModel().deselectAll();
	},

	onDocumentsGridItemClick: function(grid, record){
		var me = this;
		var form = me.down('form').getForm();
		form.loadRecord(record);

	},
	newDocumentTemplate: function(){
		var me = this, store = me.templatesDocumentsStore;
		me.rowEditor.cancelEdit();
		store.insert(0,
			{
				title: i18n('new_document'),
				template_type: 'documenttemplate',
				date: new Date(),
				type: 1
			});
		me.rowEditor.startEdit(0, 0);

	},

	newDefaultTemplates: function(){
		var me = this, store = me.defaultsDocumentsStore;
		me.rowEditor3.cancelEdit();
		store.insert(0,
			{
				title: i18n('new_defaults'),
				template_type: 'defaulttemplate',
				date: new Date(),
				type: 1
			});
		me.rowEditor3.startEdit(0, 0);

	},

	//	newHeaderOrFooterTemplate:function(){
	//        var me = this,
	//            store = me.headersAndFooterStore;
	//        me.rowEditor2.cancelEdit();
	//        store.insert(0,{
	//            title: i18n('new_header_or_footer'),
	//	        template_type:'headerorfootertemplate',
	//            date: new Date(),
	//	        type: 2
	//        });
	//        me.rowEditor2.startEdit(0, 0);
	//
	//    },

	copyToClipBoard: function(grid, rowIndex, colIndex){
		var rec = grid.getStore().getAt(rowIndex), text = rec.get('token');
	},

	onRemoveDocument: function(){

	},

	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback){
		var me = this;
		me.templatesDocumentsStore.load();
		//        me.headersAndFooterStore.load();
		me.defaultsDocumentsStore.load();
		callback(true);
	}
});
