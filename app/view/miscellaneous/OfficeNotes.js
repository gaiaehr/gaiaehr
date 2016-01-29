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

Ext.define('App.view.miscellaneous.OfficeNotes',
{
	extend : 'App.ux.RenderPanel',
	id : 'panelOfficeNotes',
	pageTitle : _('office_notes'),
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
				emptyText : _('type_new_note_here') + '...',
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
					text : _('save'),
					iconCls : 'save',
					itemId : 'cmdSave',
					disabled : true,
					scope : me,
					handler : me.onNoteSave
				}, '-',
				{
					text : _('hide_this_note'),
					iconCls : 'save',
					itemId : 'cmdHide',
					tooltip : _('hide_selected_office_note'),
					disabled : true,
					scope : me,
					handler : me.onNoteHide

				}, '-',
				{
					text : _('reset'),
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
				header : _('date'),
				sortable : true,
				dataIndex : 'date',
				renderer : Ext.util.Format.dateRenderer('Y-m-d H:i:s')
			},
			{
				width : 150,
				header : _('user'),
				sortable : true,
				dataIndex : 'user'
			},
			{
				flex : 1,
				header : _('note'),
				sortable : true,
				dataIndex : 'body'
			}],
			tbar : Ext.create('Ext.PagingToolbar',
			{
				store : me.store,
				displayInfo : true,
				emptyMsg : _('no_office_notes_to_display'),
				plugins : Ext.create('Ext.ux.SlidingPager',
				{
				}),
				items : [
				{
					text : _('show_only_active_notes'),
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
					text : _('show_all_notes'),
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
