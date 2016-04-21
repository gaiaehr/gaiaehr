/*

 This file is part of Ext JS 4

 Copyright (c) 2011 Sencha Inc

 Contact:  http://www.sencha.com/contact

 GNU General Public License Usage
 This file may be used under the terms of the GNU General Public License version 3.0 as published by the
 Free Software Foundation and appearing in the file LICENSE included in the packaging of this file.

 Please review the following information to ensure the GNU General Public License version 3.0 requirements
 will be met: http://www.gnu.org/copyleft/gpl.html.

 If you are unsure which license is appropriate for your use, please contact the sales
 department at http://www.sencha.com/contact.

 */
/**
 * The Ext.grid.plugin.RowEditing plugin injects editing at a row level for a Grid. When editing begins,
 * a small floating dialog will be shown for the appropriate row. Each editable column will show a field
 * for editing. There is a button to save or cancel all changes for the edit.
 *
 * The field that will be used for the editor is defined at the
 * {@link Ext.grid.column.Column#editor editor}. The editor can be a field instance or a field configuration.
 * If an editor is not specified for a particular column then that column won't be editable and the value of
 * the column will be displayed.
 *
 * The editor may be shared for each column in the grid, or a different one may be specified for each column.
 * An appropriate field type should be chosen to match the data structure that it will be editing. For example,
 * to edit a date, it would be useful to specify {@link Ext.form.field.Date} as the editor.
 *
 *     @example
 *     Ext.create('Ext.data.Store', {
 *         storeId:'simpsonsStore',
 *         fields:['name', 'email', 'phone'],
 *         data: [
 *             {"name":"Lisa", "email":"lisa@simpsons.com", "phone":"555-111-1224"},
 *             {"name":"Bart", "email":"bart@simpsons.com", "phone":"555--222-1234"},
 *             {"name":"Homer", "email":"home@simpsons.com", "phone":"555-222-1244"},
 *             {"name":"Marge", "email":"marge@simpsons.com", "phone":"555-222-1254"}
 *         ]
 *     });
 *
 *     Ext.create('Ext.grid.Panel', {
 *         title: 'Simpsons',
 *         store: Ext.data.StoreManager.lookup('simpsonsStore'),
 *         columns: [
 *             {header: 'Name',  dataIndex: 'name', editor: 'textfield'},
 *             {header: 'Email', dataIndex: 'email', flex:1,
 *                 editor: {
 *                     xtype: 'textfield',
 *                     allowBlank: false
 *                 }
 *             },
 *             {header: 'Phone', dataIndex: 'phone'}
 *         ],
 *         selType: 'rowmodel',
 *         plugins: [
 *             Ext.create('Ext.grid.plugin.RowEditing', {
 *                 clicksToEdit: 1
 *             })
 *         ],
 *         height: 200,
 *         width: 400,
 *         renderTo: Ext.getBody()
 *     });
 */
Ext.define('App.ux.grid.RowFormEditing', {
	extend: 'Ext.grid.plugin.Editing',
	alias: 'plugin.rowformediting',
	requires: [
		'App.ux.grid.RowFormEditor'
	],

	lockableScope: 'top',

	editStyle: 'row',

	enableRemove: false,
	enableAddBtn: false,
	addBtnText: 'Add',
	addBtnIconCls: null,
	toolbarDock: 'top',

	fieldDefaults: {},

	saveBtnEnabled: false,
	/**
	 * @cfg {Boolean} autoSync
	 * True to automatically Sync any pending changes during complete edit method.
	 * False to force the user to explicitly sync all pending changes. Defaults to true.
	 */
	autoSync: true,
	/**
	 * @cfg {Boolean} autoCancel
	 * True to automatically cancel any pending changes when the row editor begins editing a new row.
	 * False to force the user to explicitly cancel the pending changes. Defaults to true.
	 */
	autoCancel: true,

	/**
	 * @cfg {Number} clicksToMoveEditor
	 * The number of clicks to move the row editor to a new row while it is visible and actively editing another row.
	 * This will default to the same value as {@link Ext.grid.plugin.Editing#clicksToEdit clicksToEdit}.
	 */

	/**
	 * @cfg {Boolean} errorSummary
	 * True to show a {@link Ext.tip.ToolTip tooltip} that summarizes all validation errors present
	 * in the row editor. Set to false to prevent the tooltip from showing. Defaults to true.
	 */
	errorSummary: false,

	/**
	 * @event beforeedit
	 * Fires before row editing is triggered.
	 *
	 * @param {Ext.grid.plugin.Editing} editor
	 * @param {Object} e An edit event with the following properties:
	 *
	 * - grid - The grid this editor is on
	 * - view - The grid view
	 * - store - The grid store
	 * - record - The record being edited
	 * - row - The grid table row
	 * - column - The grid {@link Ext.grid.column.Column Column} defining the column that initiated the edit
	 * - rowIdx - The row index that is being edited
	 * - colIdx - The column index that initiated the edit
	 * - cancel - Set this to true to cancel the edit or return false from your handler.
	 */

	/**
	 * @event canceledit
	 * Fires when the user has started editing a row but then cancelled the edit
	 * @param {Object} grid The grid
	 */

	/**
	 * @event edit
	 * Fires after a row is edited. Usage example:
	 *
	 *     grid.on('edit', function(editor, e) {
     *         // commit the changes right after editing finished
     *         e.record.commit();
     *     };
	 *
	 * @param {Ext.grid.plugin.Editing} editor
	 * @param {Object} e An edit event with the following properties:
	 *
	 * - grid - The grid this editor is on
	 * - view - The grid view
	 * - store - The grid store
	 * - record - The record being edited
	 * - row - The grid table row
	 * - column - The grid {@link Ext.grid.column.Column Column} defining the column that initiated the edit
	 * - rowIdx - The row index that is being edited
	 * - colIdx - The column index that initiated the edit
	 */
	/**
	 * @event validateedit
	 * Fires after a cell is edited, but before the value is set in the record. Return false to cancel the change. The
	 * edit event object has the following properties
	 *
	 * Usage example showing how to remove the red triangle (dirty record indicator) from some records (not all). By
	 * observing the grid's validateedit event, it can be cancelled if the edit occurs on a targeted row (for example)
	 * and then setting the field's new value in the Record directly:
	 *
	 *     grid.on('validateedit', function(editor, e) {
     *       var myTargetRow = 6;
     *
     *       if (e.rowIdx == myTargetRow) {
     *         e.cancel = true;
     *         e.record.data[e.field]
     *
     * - grid - The grid this editor is on
     * - view - The grid view
     * - store - The grid store
     * - record - The record being edited
     * - row - The grid table row
     * - column - The grid {@link Ext.grid.column.Column Column} defining the column that initiated the edit
	 * - rowIdx - The row index that is being edited
	 * - colIdx - The column index that initiated the edit
	 * - cancel - Set this to true to cancel the edit or return false from your handler.
	 */

	constructor: function(){
		var me = this;
		me.callParent(arguments);

		if(!me.clicksToMoveEditor){
			me.clicksToMoveEditor = me.clicksToEdit;
		}

		me.autoCancel = !!me.autoCancel;
	},

	init: function(grid){
		var me = this,
            t;
		me.callParent(arguments);

		if(me.enableAddBtn){
			t = grid.getDockedItems('toolbar[dock="' + me.toolbarDock + '"]')[0] ||
				grid.addDocked({ xtype: 'toolbar', dock: me.toolbarDock })[0];

			t.add({
				xtype: 'button',
				text: me.addBtnText,
				iconCls: me.addBtnIconCls,
				handler: me.doAddRecord,
				scope: me
			});

		}

		me.grid.on('beforeselect', me.editHandler, me);
		me.grid.on('beforecellclick', me.editHandler, me);
		me.grid.on('beforecelldblclick', me.editHandler, me);
		me.grid.on('beforecellmousedown', me.editHandler, me);
	},

	editHandler: function(){
		return !this.editing;
	},

	doAddRecord: function(){
		var me = this,
			grid = me.grid,
			store = grid.store;

		me.cancelEdit();
		store.insert(0, {});
		me.startEdit(0, 0);

	},

	//    init: function(grid) {
	//        this.callParent([grid]);
	//    },

	/**
	 * @private
	 * AbstractComponent calls destroy on all its plugins at destroy time.
	 */
	destroy: function(){
		var me = this;
		Ext.destroy(me.editor);
		me.callParent(arguments);
	},

	/**
	 * Starts editing the specified record, using the specified Column definition to define which field is being edited.
	 * @param {Ext.data.Model} record The Store data record which backs the row to be edited.
	 * @param {Ext.data.Model} columnHeader The Column object defining the column to be edited.
	 * @return {Boolean} `true` if editing was started, `false` otherwise.
	 */
	startEdit: function(record, columnHeader){
		var me = this,
			editor = me.getEditor(),
			context;

		if(editor.beforeEdit() !== false){
			context = me.callParent(arguments);
			if(context){
				me.context = context;

				// If editing one side of a lockable grid, cancel any edit on the other side.
				if(me.lockingPartner){
					me.lockingPartner.cancelEdit();
				}
				editor.startEdit(context.record, context.column, context);
				return true;
			}
		}
		return false;
	},

	// private
	cancelEdit: function(){
		var me = this;

		if(me.editing){
			me.getEditor().cancelEdit();
			me.callParent(arguments);

			if(me.autoCancel) me.view.store.rejectChanges();

			me.fireEvent('canceledit', me.context);
			return;
		}
		// If we aren't editing, return true to allow the event to bubble
		return true;
	},

	// private
	completeEdit: function(){
		var me = this;

		if(me.editing && me.validateEdit()){
			me.editing = false;
			me.fireEvent('edit', me, me.context);
		}
	},

	completeRemove: function(){
		var me = this;

		if(me.editing){
			me.getEditor().completeRemove();
			me.fireEvent('completeremove', me, me.context);
		}

	},

	// private
	validateEdit: function(){
		var me = this,
			editor = me.editor,
			context = me.context,
			record = context.record,
			originalValues = {},
			newValues = editor.getForm().getValues();


		Ext.Object.each(newValues, function(key){
			originalValues[key] = record.get(key);
		});

		Ext.apply(context, {
			newValues: newValues,
			originalValues: originalValues
		});

		return me.fireEvent('validateedit', me, context) !== false && !context.cancel && me.getEditor().completeEdit();
	},

	// private
	getEditor: function(){
		var me = this;

		if(!me.editor){
			me.editor = me.initEditor();
		}
		return me.editor;
	},

	// @private
	initEditor: function(){
		return new App.ux.grid.RowFormEditor(this.initEditorConfig());
	},

	initEditorConfig: function(){
		var me = this,
			grid = me.grid,
			view = me.view,
			headerCt = grid.headerCt,
			btns = ['saveBtnText', 'cancelBtnText', 'errorsText', 'dirtyText'],
			b,
			bLen = btns.length,
			cfg = {
				autoCancel: me.autoCancel,
				errorSummary: me.errorSummary,
				saveBtnEnabled: me.disableValidation,
				fields: headerCt.getGridColumns(),
				hidden: true,
				view: view,
				// keep a reference..
				editingPlugin: me,
				renderTo: view.el
			},
			item;

		for(b = 0; b < bLen; b++){
			item = btns[b];

			if(Ext.isDefined(me[item])){
				cfg[item] = me[item];
			}
		}
		return cfg;
	},

	// private
	initEditTriggers: function(){
		var me = this,
			moveEditorEvent = me.clicksToMoveEditor === 1 ? 'click' : 'dblclick';

		me.callParent(arguments);

		if(me.clicksToMoveEditor !== me.clicksToEdit){
			me.mon(me.view, 'cell' + moveEditorEvent, me.moveEditorByClick, me);
		}
	},

	startEditByClick: function(){
		var me = this;
		if(!me.editing || me.clicksToMoveEditor === me.clicksToEdit){
			me.callParent(arguments);
		}
	},

	moveEditorByClick: function(){
		var me = this;
		if(me.editing){
			me.superclass.onCellClick.apply(me, arguments);
		}
	},

	onCellClick: function(view, cell, colIdx, record, row, rowIdx, e){
		var me = this;

		if(me.autoCancel){
			me.view.store.rejectChanges();
			if(me.editor) me.editor.rejectChildStoresChanges();
		}
		me.callParent(arguments);
	},

	// private
	setColumnField: function(column, field){
		var me = this;
		editor.removeField(column);
		me.callParent(arguments);
		me.getEditor().setField(column.field, column);
	}
});
