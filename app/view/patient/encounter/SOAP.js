/**
 * Created with IntelliJ IDEA.
 * User: ernesto
 * Date: 1/17/13
 * Time: 8:34 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.patient.encounter.SOAP', {
	extend:'Ext.panel.Panel',
	title:i18n('soap'),
	layout:'border',
	frame:true,
	initComponent:function () {
		var me = this;

//		Ext.define('TemplatesSOAP', {
//			extend: 'Ext.data.Model',
//			fields: [
//				{name: 'text', type: 'string'},
//				{name: 'template',  type: 'string'}
//			],
//
//		});

		me.tplStore =  Ext.create('Ext.data.TreeStore', {
			proxy : {
				type: 'direct',
				api : {
					read: Snippets.getSoapSnippetsByCategory
				}
			}
		});


		me.templates = Ext.create('Ext.tree.Panel', {
			title:i18n('snippets'),
			collapsible:true,
			collapsed:true,
			split:true,
			width:250,
			region:'west',
			hideHeaders:true,
			useArrows: true,
			rootVisible: false,
			store: me.tplStore,
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
					text: 'Edit',
					width: 40,
					menuDisabled: true,
					xtype: 'actioncolumn',
					tooltip: 'Edit task',
					align: 'center',
					icon: 'resources/images/icons/edit.png',
					handler: me.onSnippetBtnEdit
				}
			],
			plugins:[
				Ext.create('App.ux.grid.RowFormEditing',{
					clicksToMoveEditor:1,
//					autoSync:false,
//					autoCancel:false,
					enabled:false,
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
			listeners:{
				scope:me,
				itemdblclick:me.onSnippetDblClick,
				beforeedit:me.onSnippetBeforeEdit,
				canceledit:me.onSnippetCancelEdit,
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
						me.dxField = Ext.widget('icdsfieldset')
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
				//beforesync:me.formRBeforeSync
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
					grow:true
				}
			],
			buttons:[
				{
					text:i18n('done')
				},
				{
					text:i18n('cancel')
				}
			]
		});

		Ext.apply(me,{
			items: [ me.templates, me.form ]
		});

		me.callParent();
	},

	onSoapSave:function(btn){
		this.templates.collapse(false);
		this.enc.onEncounterUpdate(btn)
	},

	formRecordLoaded:function(form, record){
		this.dxField.loadIcds(record.data.icdxCodes);
	},

	onFieldFocus:function(field){
		this.templates.setTitle(Ext.String.capitalize(field.name) +' '+ i18n('templates'));
		this.templates.expand(false);
		if(this.templates.action != field.name) this.tplStore.load();
		this.templates.action = field.name;
	},

	snippetTextRenderer:function(v, meta, record){
		var toolTip = record.data.text ? ' data-qtip="'+record.data.text+'" ' : '';
		return '<span '+toolTip+'>'+v+'</span>'
	},

	onSnippetDblClick:function(view, record){
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

	onSnippetEdit:function(plugin){
		plugin.enabled = false;
	},

	onSnippetBeforeEdit:function(plugin){
		return plugin.enabled;
	},

	onSnippetCancelEdit:function(plugin){
		plugin.enabled = false;
	}

});