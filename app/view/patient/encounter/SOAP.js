/**
 * Created with IntelliJ IDEA.
 * User: ernesto
 * Date: 1/17/13
 * Time: 8:34 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.patient.encounter.SOAP', {
	extend:'Ext.panel.Panel',
	action:['patient.encounter.soap'],
	title:i18n('soap'),
	layout:'border',
	frame:true,
	initComponent:function () {
		var me = this;

		Ext.define('snippetTreeModel', {
			extend: 'Ext.data.Model',
			fields: [
				{ name: 'id', type: 'string' },
				{ name: 'text', type: 'string' },
				{ name: 'index', type: 'int' },
				{ name: 'leaf', type: 'bool' },
				{ name: 'category', type: 'string' }
			],
			proxy : {
				type: 'direct',
				api : {
					read: Snippets.getSoapSnippetsByCategory,
					create: Snippets.addSoapSnippets,
					update: Snippets.updateSoapSnippets,
					destroy: Snippets.deleteSoapSnippets
				}
			}
		});

		me.snippetStore = Ext.create('Ext.data.TreeStore',{
			model:'snippetTreeModel'
		});

		me.snippets = Ext.create('Ext.tree.Panel', {
			title:i18n('snippets'),
			collapsible:true,
			collapsed:true,
			split:true,
			width:300,
			region:'west',
			hideHeaders:true,
			useArrows: true,
			rootVisible: false,
			store: me.snippetStore,
			singleExpand: true,
			columns: [
				{
					xtype: 'treecolumn', //this is so we know which column will show the tree
					text: 'Template',
					flex: 1,
					dataIndex: 'text',
					renderer:me.snippetTextRenderer
				},
				{
					text: i18n('add'),
					width: 25,
					menuDisabled: true,
					xtype: 'actioncolumn',
					tooltip: i18n('add_snippet'),
					align: 'center',
					icon: 'resources/images/icons/add.gif',
					scope:me,
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
					expandDelay:500,
					dragText: i18n('drag_and_drop_reorganize')
				},
				listeners: {
					scope: me,
					drop: me.onSnippetDrop
				}
			},
			plugins:[
				Ext.create('App.ux.grid.RowFormEditing',{
					clicksToMoveEditor:1,
					enabled:false,
					enableRemove:true,
					formItems:[
						{
							xtype:'textarea',
							height:100,
							anchor:'100%',
							name:'text'
						}
					]
				})
			],
			buttons:[
				{
					text:i18n('add_category'),
					flex:1,
					scope:me,
					handler:me.onAddSnippetCategory
				}
			],
			listeners:{
				scope:me,
				itemclick:me.onSnippetClick,
				itemdblclick:me.onSnippetDblClick,
				beforeedit:me.onSnippetBeforeEdit,
				canceledit:me.onSnippetCancelEdit,
				beforeremove:me.onSnippetBeforeRemove,
				remove:me.onSnippetRemove,
				edit:me.onSnippetEdit
			}
		});

		me.form = Ext.create('Ext.form.Panel', {
			autoScroll:true,
			action:'encounter',
			bodyStyle:'background-color:white',
			bodyPadding:5,
			region:'center',
			fieldDefaults:{
				msgTarget:'side'
			},
			plugins: {
				ptype:'advanceform',
				autoSync:globals['autosave'],
				syncAcl:acl['edit_encounters']
			},
			items:[
				{
					xtype:'fieldset',
					title:i18n('subjective'),
					items:[
						me.sField = Ext.widget('textarea',{
							name:'subjective',
							anchor:'100%',
							enableKeyEvents:true,
							listeners:{
								scope:me,
								focus:me.onFieldFocus
							}
						})
					]
				},
				{
					xtype:'fieldset',
					title:i18n('objective'),
					items:[
						me.oField = Ext.widget('textarea',{
							name:'objective',
							anchor:'100%',
							listeners:{
								scope:me,
								focus:me.onFieldFocus
							}
						})
					]
				},
				{
					xtype:'fieldset',
					title:i18n('assessment'),
					items:[
						me.aField = Ext.widget('textarea',{
							name:'assessment',
							anchor:'100%',
							listeners:{
								scope:me,
								focus:me.onFieldFocus
							}
						}),
						me.dxField = Ext.widget('icdsfieldset',{
							name:'icdxCodes'
						})
					]
				},
				{
					xtype:'fieldset',
					title:i18n('plan'),
					items:[
						me.pField = Ext.widget('textarea',{
							name:'plan',
							anchor:'100%',
							listeners:{
								scope:me,
								focus:me.onFieldFocus
							}
						})
					]
				}
			],
			buttons:[
				{
					text:i18n('save'),
					iconCls:'save',
					scope:me,
					handler:me.onSoapSave
				}
			],
			listeners:{
				scope:me,
				recordloaded:me.formRecordLoaded
			}
		});

		me.phWindow = Ext.widget('window',{
			title:i18n('complete_snippet'),
			closeAction:'hide',
			bodyPadding:0,
			bodyBorder:false,
			border:false,
			items:[
				{
					xtype:'textarea',
					border:false,
					width:500,
					height:150,
					margin:0,
					grow:true,
					enableKeyEvents:true,
					listeners:{
						scope:me,
						specialkey:me.onPhTextAreaKey
					}
				}
			],
			buttons:[
				{
					xtype:'tbtext',
					text:i18n('shift_enter_submit')
				},
				'->',
				{
					text:i18n('submit'),
					scope:me,
					handler:me.onPhWindowSubmit
				},
				{
					text:i18n('cancel'),
					handler:me.onPhWindowCancel
				}
			]
		});

		Ext.apply(me,{
			items: [ me.snippets, me.form ]
		});

		me.callParent();
	},

	onSoapSave:function(btn){
		this.snippets.collapse(false);
		this.enc.onEncounterUpdate(btn)
	},

	formRecordLoaded:function(form, record){
		this.dxField.loadIcds(record.data.icdxCodes);
	},

	onFieldFocus:function(field){
		this.snippets.setTitle(Ext.String.capitalize(field.name) +' '+ i18n('templates'));
		this.snippets.expand(false);
		if(this.snippets.action != field.name) this.snippetStore.load({params:{category:field.name}});
		this.snippets.action = field.name;
	},

	snippetTextRenderer:function(v, meta, record){
		var toolTip = record.data.text ? ' data-qtip="'+record.data.text+'" ' : '';
		return '<span '+toolTip+'>'+v+'</span>'
	},

	onSnippetClick:function(view, record){
		if(!record.data.leaf) record.expand();
	},

	onSnippetDblClick:function(view, record){
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
				field.setValue(me.closeSentence(value) + ' ' + me.closeSentence(text));
			}else{
				me.phWindow.show();
				textArea.setValue(text);
				Ext.Function.defer(function(){
					textArea.selectText(PhIndex, PhIndex+2)
				}, 300);
			}
		}else{
			record.expand();
		}
	},

	onPhWindowSubmit:function(){
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

	onPhWindowCancel:function(btn){
		btn.up('window').close();
	},

	onPhTextAreaKey:function(field, e){
		if (e.getKey() == e.ENTER) this.onPhWindowSubmit();
	},

	/**
	 * This will add a period to the end of the sentence if last character is not a . ? or
	 * @param sentence
	 * @return {*|String|String|String|String|String|String|String|String|String|String}
	 */
	closeSentence:function(sentence){
		var v = Ext.String.trim(sentence),
			c = v.charAt(v.length - 1);
		return ((c == '.' || c == '!' || c == '?') ? v : v + '.');
	},

	onSnippetBtnEdit: function(grid, rowIndex, colIndex, actionItem, event, record, row) {
		grid.editingPlugin.enabled = true;
		grid.editingPlugin.startEdit(rowIndex,0);
	},

	onSnippetRemove:function(editor, store, record){
		var me = this;
		store.sync({
			callback:function(){
				me.msg('Sweet!','Record removed.');
			}
		});
	},

	onSnippetBeforeRemove:function(editor, store, record){
		var me = this;
		if(record.childNodes[0]){
			me.msg('Oops!','Unable to remove category "'+record.data.text+'". Please delete snippets first.', true);
			return false;
		}else{
			return true;
		}
	},

	onSnippetEdit:function(plugin){
		this.snippetStore.sync();
		plugin.enabled = false;
	},

	onSnippetBeforeEdit:function(plugin){
		return plugin.enabled;
	},

	onSnippetCancelEdit:function(plugin){
		plugin.enabled = false;
	},

	onSnippetDrop:function(node, data, overModel){
		var me = this, pos = 10;
		for(var i = 0; i < overModel.parentNode.childNodes.length; i++){
			overModel.parentNode.childNodes[i].set({pos:pos});
			pos = pos + 10;
		}
		me.snippetStore.sync();
	},

	onSnippetBtnAdd:function(grid, rowIndex, colIndex, actionItem, event, record){
		var me = this, rec;
		me.origScope.snippets.editingPlugin.cancelEdit();
		rec = me.origScope.snippetStore.getNodeById(record.data.id).appendChild({
			text:'New Snippet',
			parentId:record.data.id,
			leaf:true
		});
		me.origScope.snippetStore.sync({
			callback:function(batch){
				rec.set({id:batch.proxy.reader.rawData.id});
				rec.commit();
				me.origScope.msg('Sweet!', 'Record Added');
				me.origScope.snippets.editingPlugin.enabled = true;
				me.origScope.snippets.editingPlugin.startEdit(rec,0);
			}
		});
	},

	onAddSnippetCategory:function(){
		var me = this, rec;
		me.snippets.editingPlugin.cancelEdit();
		rec = me.snippetStore.getRootNode().appendChild({
			text:'New Category',
			parentId:'root',
			leaf:false,
			category:me.snippets.action
		});
		me.snippetStore.sync({
			callback:function(batch){
				rec.set({id:batch.proxy.reader.rawData.id});
				rec.commit();
				me.msg('Sweet!', 'Record Added');
				me.snippets.editingPlugin.enabled = true;
				me.snippets.editingPlugin.startEdit(rec,0);
			}
		});
	}
});