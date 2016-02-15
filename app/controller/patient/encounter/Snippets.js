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
			ref: 'SnippetDeleteBtn',
			selector: '#SnippetDeleteBtn'
		},
		{
			ref: 'SnippetCancelBtn',
			selector: '#SnippetCancelBtn'
		},
		{
			ref: 'SnippetSaveBtn',
			selector: '#SnippetSaveBtn'
		},

		// templates specialties combo
		{
			ref: 'SoapTemplateSpecialtiesCombo',
			selector: '#SoapTemplateSpecialtiesCombo'
		}
	],

	init: function(){
		var me = this;

		this.control({
			'#SnippetDeleteBtn': {
				click: me.onSnippetDeleteBtnClick
			},
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

	onSnippetDeleteBtnClick: function(){
		var me = this,
			form = me.getSnippetForm().getForm(),
			record = form.getRecord();

		if(record.childNodes.length > 0){
			app.msg(_('oops'),_('snippet_delete_child_error'), true);
			return;
		}

		record.remove(true);
		form.reset();
		me.getSnippetWindow().close()
	},

	onSnippetAddBtnClick: function(grid, rowIndex, colIndex, actionItem, event, record){
		var me = this,
			win = me.getSnippetEditWindow(),
			form = me.getSnippetForm(),
			newRecord = Ext.create('App.model.patient.encounter.snippetTree', {
				parentId: record.data.id,
				specialty_id: me.getSoapTemplateSpecialtiesCombo().getValue(),
				leaf: true
			});

		win.parentRecord = record;
		form.getForm().loadRecord(newRecord);
	},

	onSnippetSaveBtnClick: function(){
		var me = this,
			win = me.getSnippetWindow(),
			form = me.getSnippetForm().getForm(),
			values = form.getValues(),
			record = form.getRecord(),
			isNew = record.data.id === '' || record.data.id === 0;

		if(form.isValid()){

			record.set(values);

			if(isNew) win.parentRecord.appendChild(record);

			record.save({
				success: function(record, reuqest){
					record.set({ id: reuqest.response.result.id });
					record.commit();
					app.msg(_('sweet'), _('record_saved'));
				},
				failure: function(){
					app.msg(_('oops'), _('record_error'), true);
				}
			});


			me.getSnippetWindow().close();
		}
	},

	onSnippetCancelBtnClick: function(){
		var record = this.getSnippetForm().getForm().getRecord();

		if(record.data.id === '' || record.data.id === 0) record.destroy();
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
			category = tree.action.split('-'),
			newRecord,
			parentRecord;

		me.getSnippetFormTextField().hide();
		me.getSnippetFormTextField().disable();

		if(selection.length === 0){
			parentRecord = store.getRootNode();
		}else if(selection[0].data.leaf){
			parentRecord = selection[0].parentNode;
		}else{
			parentRecord = selection[0];
		}

		newRecord = Ext.create('App.model.patient.encounter.snippetTree', {
			parentId: parentRecord.data.id,
			category: (category.length > 1 ? category[0] : category[1]),
			specialty_id: me.getSoapTemplateSpecialtiesCombo().getValue(),
			leaf: false
		});

		win.parentRecord = parentRecord;

		me.getSnippetForm().getForm().loadRecord(newRecord);
	},

	onSnippetBtnEdit: function(grid, rowIndex, colIndex, actionItem, event, record){

		this.getSnippetEditWindow();

		var me = this,
			field = me.getSnippetFormTextField(),
			win = me.getSnippetWindow(),
			form = me.getSnippetForm().getForm();

		if(record.get('leaf')){
			win.setTitle(_('title') + ' (' + _('required') + ')');
			field.show();
			field.enable();
		}else{
			win.setTitle(_('title') + ' (' + _('optional') + ')');
			field.hide();
			field.disable();
		}

		form.loadRecord(record);
	}

});