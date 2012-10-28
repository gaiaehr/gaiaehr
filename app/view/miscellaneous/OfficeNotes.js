//******************************************************************************
// ofice_notes.ejs.php
// office Notes Page
// v0.0.1
//
// Author: Ernest Rodriguez
// Modified:
//
// GaiaEHR (Electronic Health Records) 2011
//******************************************************************************
Ext.define('App.view.miscellaneous.OfficeNotes',
{
	extend : 'App.ux.RenderPanel',
	id : 'panelOfficeNotes',
	pageTitle : i18n('office_notes'),
	pageLayout : 'border',
	initComponent : function()
	{
		var me = this;

		me.store = Ext.create('App.store.miscellaneous.OfficeNotes');

		me.form = Ext.create('Ext.form.FormPanel',
		{
			region : 'north',
			frame : true,
			height : 97,
			margin : '0 0 3 0',
			items : [
			{
				xtype : 'textareafield',
				allowBlank : false,
				grow : true,
				margin : 0,
				itemId : 'body',
				name : 'body',
				anchor : '100%',
				emptyText : i18n('type_new_note_here') + '...',
				listeners :
				{
					scope : me,
					validitychange : me.onValidityChange
				}
			}],
			dockedItems : [
			{
				xtype : 'toolbar',
				dock : 'top',
				items : [
				{
					text : i18n('save'),
					iconCls : 'save',
					itemId : 'cmdSave',
					disabled : true,
					scope : me,
					handler : me.onNoteSave
				}, '-',
				{
					text : i18n('hide_this_note'),
					iconCls : 'save',
					itemId : 'cmdHide',
					tooltip : i18n('hide_selected_office_note'),
					disabled : true,
					scope : me,
					handler : me.onNoteHide

				}, '-',
				{
					text : i18n('reset'),
					iconCls : 'save',
					itemId : 'cmdReset',
					disabled : true,
					scope : me,
					handler : me.onFormReset
				}]
			}]
		});

		me.grid = Ext.create('Ext.grid.Panel',
		{
			region : 'center',
			store : me.store,
			listeners :
			{
				scope : me,
				itemclick : me.onItemClick
			},
			columns : [
			{
				width : 150,
				header : i18n('date'),
				sortable : true,
				dataIndex : 'date',
				renderer : Ext.util.Format.dateRenderer('Y-m-d H:i:s')
			},
			{
				width : 150,
				header : i18n('user'),
				sortable : true,
				dataIndex : 'user'
			},
			{
				flex : 1,
				header : i18n('note'),
				sortable : true,
				dataIndex : 'body'
			}],
			tbar : Ext.create('Ext.PagingToolbar',
			{
				store : me.store,
				displayInfo : true,
				emptyMsg : i18n('no_office_notes_to_display'),
				plugins : Ext.create('Ext.ux.SlidingPager',
				{
				}),
				items : [
				{
					text : i18n('show_only_active_notes'),
					iconCls : 'save',
					enableToggle : true,
					pressed : true,
					handler : function()
					{
						//me.cmdShowAll.toggle(false);
						me.store.load(
						{
							params :
							{
								show : 'active'
							}
						});
					}
				}, '-',
				{
					text : i18n('show_all_notes'),
					iconCls : 'save',
					enableToggle : true,
					handler : function()
					{
						//me.cmdShow.toggle(false);
						me.store.load(
						{
							params :
							{
								show : 'all'
							}
						});
					}
				}]
			})
		});
		// END GRID
		me.pageBody = [me.form, me.grid];
		me.callParent(arguments);
	},

	onNoteSave : function(btn)
	{
		var form = btn.up('form').getForm(), store = this.store, record = form.getRecord(), values = form.getValues(), storeIndex = store.indexOf(record);
		if (storeIndex == -1)
		{
			store.add(values);
		}
		else
		{
			record.set(values);
		}
		store.sync();
		//store.load();
	},

	onNoteHide : function()
	{

	},

	onFormReset : function(btn)
	{
		var panel = this.form, form = panel.getForm(), toolbar = panel.down('toolbar'), savebtn = toolbar.getComponent('cmdSave'), hidebtn = toolbar.getComponent('cmdHide'), resetbtn = toolbar.getComponent('cmdReset');
		form.reset();
		savebtn.disable();
		hidebtn.disable();
		resetbtn.disable();
		savebtn.setText('Save');
	},

	onItemClick : function(grid, record)
	{
		var panel = this.form, form = panel.getForm(), toolbar = panel.down('toolbar'), savebtn = toolbar.getComponent('cmdSave'), hidebtn = toolbar.getComponent('cmdHide'), resetbtn = toolbar.getComponent('cmdReset');
		form.reset();
		form.loadRecord(record);
		savebtn.enable();
		hidebtn.enable();
		resetbtn.enable();
		savebtn.setText('Update');
	},

	onValidityChange : function()
	{
		var panel = this.form, textfield = panel.getComponent('body'), toolbar = panel.down('toolbar'), savebtn = toolbar.getComponent('cmdSave'), resetbtn = toolbar.getComponent('cmdReset');

		if (textfield.isValid())
		{
			savebtn.enable();
			resetbtn.enable();
		}
		else
		{
			savebtn.disable();
		}
	},

	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive : function(callback)
	{
		this.store.load(
		{
			params :
			{
				show : 'active'
			}
		});
		callback(true);
	}
});
//ens oNotesPage class
