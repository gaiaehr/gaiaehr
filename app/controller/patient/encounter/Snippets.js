Ext.define('App.controller.patient.encounter.Snippets', {
	extend: 'Ext.app.Controller',
	requires: [
		'App.view.patient.encounter.Snippets'
	],
	refs: [
		{
			ref: 'SnippetsTreePanel',
			selector: '#SnippetsTreePanel'
		},
		{
			ref: 'SnippetWindow',
			selector: '#SnippetWindow'
		},
		{
			ref: 'SnippetForm',
			selector: '#SnippetForm'
		},
		{
			ref: 'SnippetFormTextField',
			selector: '#SnippetFormTextField'
		},
		{
			ref: 'SnippetCancelBtn',
			selector: '#SnippetCancelBtn'
		},
		{
			ref: 'SnippetSaveBtn',
			selector: '#SnippetSaveBtn'
		}
	],

	init: function(){
		var me = this;

		this.control({
			'#SnippetSaveBtn': {
				click: me.onSnippetSaveBtnClick
			},
			'#SnippetCancelBtn': {
				click: me.onSnippetCancelBtnClick
			},
			'#SnippetCategoryAddBtn': {
				click: me.onSnippetCategoryAddBtnClick
			}
		});
	},

	onSnippetAddBtnClick: function(grid, rowIndex, colIndex, actionItem, event, record){
		var me = this,
			win = me.getSnippetEditWindow(),
			form = me.getSnippetForm(),
			newRecord = Ext.create('App.model.patient.encounter.snippetTree', {
				parentId: record.data.id,
				leaf: true
			});

		win.parentRecord = record;
		form.getForm().loadRecord(newRecord);
	},

	onSnippetSaveBtnClick: function(){
		var me = this,
			win = me.getSnippetWindow(),
			store = me.getSnippetsTreePanel().getStore(),
			form = me.getSnippetForm().getForm(),
			values = form.getValues(),
			record = form.getRecord(),
			isNew = record.data.id == '';

		record.set(values);

		if(form.isValid()){

			if(isNew) win.parentRecord.appendChild(record);

			store.sync({
				success: function(){
					app.msg(i18n('sweet'), i18n('record_saved'));
				},
				failure: function(){
					app.msg(i18n('oops'), i18n('record_error'), true);
				}
			});

			me.getSnippetWindow().close();
		}
	},

	onSnippetCancelBtnClick: function(){
		var record = this.getSnippetForm().getForm().getRecord();

		if(record.data.id == '') record.destroy();
		this.getSnippetWindow().close();
	},

	getSnippetEditWindow: function(){
		var me = this;

		if(me.getSnippetWindow()){
			return me.getSnippetWindow().show();
		}else{
			return Ext.widget('snippetswindow').show();
		}
	},

	onSnippetCategoryAddBtnClick: function(){
		var me = this,
			win = me.getSnippetEditWindow(),
			tree = me.getSnippetsTreePanel(),
			store =  tree.getStore(),
			selection = tree.getSelectionModel().getSelection(),
			newRecord,
			parentRecord;

		me.getSnippetFormTextField().hide();
		me.getSnippetFormTextField().disable();

		if(selection.length == 0){
			parentRecord = store.getRootNode();
		}else if(selection[0].data.leaf){
			parentRecord = selection[0].parentNode;
		}else{
			parentRecord = selection[0];
		}

		newRecord = Ext.create('App.model.patient.encounter.snippetTree', {
			parentId: parentRecord.data.id,
			category: tree.action,
			leaf: false
		});

		win.parentRecord = parentRecord;
		me.getSnippetForm().getForm().loadRecord(newRecord);
	},

	onSnippetBtnEdit: function(grid, rowIndex, colIndex, actionItem, event, record){
		this.getSnippetEditWindow();
		this.getSnippetForm().getForm().loadRecord(record);
	}

});