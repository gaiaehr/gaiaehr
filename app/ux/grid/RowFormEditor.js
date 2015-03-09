/*

 This file is part of Ext JS 4

 Copyright (c) 2011 Sencha Inc

 Contact:  http://www.sencha.com/contact

 GNU General Public License Usage
 This file may be used under the terms of the GNU General Public License version 3.0 as published by the Free Software Foundation and appearing in the file LICENSE included in the packaging of this file.  Please review the following information to ensure the GNU General Public License version 3.0 requirements will be met: http://www.gnu.org/copyleft/gpl.html.

 If you are unsure which license is appropriate for your use, please contact the sales department at http://www.sencha.com/contact.

 */
// Currently has the following issues:
// - Does not handle postEditValue
// - Fields without editors need to sync with their values in Store
// - starting to edit another record while already editing and dirty should probably prevent it
// - aggregating validation messages
// - tabIndex is not managed bc we leave elements in dom, and simply move via positioning
// - layout issues when changing sizes/width while hidden (layout bug)

/**
 * @class Ext.grid.RowEditor
 * @extends Ext.form.Panel
 *
 * Internal utility class used to provide row editing functionality. For developers, they should use
 * the RowEditing plugin to use this functionality with a grid.
 *
 * @ignore
 */
Ext.define('App.ux.grid.RowFormEditor', {
	extend: 'Ext.form.Panel',
	requires: [
		'Ext.tip.ToolTip',
		'Ext.util.HashMap',
		'Ext.util.KeyNav'
	],

	saveBtnText  : 'Update',
	cancelBtnText: 'Cancel',
	removeBtnText: 'Remove',
	errorsText: 'Errors',
	dirtyText: 'Commit Cancel Your Changes',

	lastScrollLeft: 0,
	lastScrollTop: 0,
	bodyPadding: 5,
	padding:'0 0 5 0',
	border: false,
	saveBtnEnabled:false,
	buttonAlign:'center',
	// Change the hideMode to offsets so that we get accurate measurements when
	// the roweditor is hidden for laying out things like a TriggerField.
	hideMode: 'offsets',
	errorSummary: false,

	style:'background-color:#E0E0E0',

	initComponent: function () {
		var me = this,
			form, plugin;

		me.cls = Ext.baseCSSPrefix + 'grid-row-editor grid-row-form-editor';
		me.currRowH = null;
		plugin = me.editingPlugin;
		me.items = plugin.items;
		me.fieldDefaults = plugin.fieldDefaults;

		var buttons = [{
			action: 'update',
			xtype: 'button',
			itemId: 'update',
			handler: plugin.completeEdit,
			scope: plugin,
			text: me.saveBtnText,
			disabled: !me.isValid
		},
			{
				xtype: 'button',
				itemId: 'cancel',
				handler: plugin.cancelEdit,
				scope: plugin,
				text: me.cancelBtnText
			}];
		if (plugin.enableRemove) {
			buttons.push({
				xtype: 'button',
				itemId: 'remove',
				handler: plugin.completeRemove,
				scope: plugin,
				text: me.removeBtnText
			});
		}

		me.dockedItems = [{
			xtype: 'toolbar',
			dock: 'bottom',
			ui: 'footer',
			margin: 0,
			layout:{
				pack: 'center'
			},
			cls: 'x-grid-row-editor x-panel-body',
			style:'border-top:none !important',
			defaults: {
				minWidth: Ext.panel.Panel.prototype.minButtonWidth
			},
			items: buttons
		}];


		//        me.buttons = [{
		//            action: 'update',
		//            xtype: 'button',
		//            handler: plugin.completeEdit,
		//            scope: plugin,
		//            text: me.saveBtnText,
		//            disabled: !me.isValid,
		//            minWidth: Ext.panel.Panel.prototype.minButtonWidth
		//        },
		//        {
		//            xtype: 'button',
		//            handler: plugin.cancelEdit,
		//            scope: plugin,
		//            text: me.cancelBtnText,
		//            minWidth: Ext.panel.Panel.prototype.minButtonWidth
		//        }];
		//        if(plugin.enableRemove){
		//            me.buttons.push({
		//                xtype: 'button',
		//                handler: plugin.completeRemove,
		//                scope: plugin,
		//                text: me.removeBtnText,
		//                minWidth: Ext.panel.Panel.prototype.minButtonWidth
		//            });
		//        }

		me.callParent(arguments);
		form = me.getForm();
		me.setFields();
		form.trackResetOnLoad = true;
		me.floatingButtons = me.getDockedItems('toolbar[dock="bottom"]')[0];
	},

	onFieldValueChange: function() {
		var me = this,
			form = me.getForm(),
			valid = form.isValid(), btn;

		if (me.errorSummary && me.isVisible()) {
			me[valid ? 'hideToolTip' : 'showToolTip']();
		}

		btn = me.query('button[action="update"]')[0];
		if (btn){
			btn.setDisabled(!valid);
		}
		me.isValid = valid;
	},

	afterRender: function() {
		var me = this,
			plugin = me.editingPlugin,
			grid = plugin.grid,
			view = grid.lockable ? grid.normalGrid.view : grid.view;

		me.callParent(arguments);


		me.scrollingView = view;
		me.scrollingViewEl = view.el;
		me.mon(me.renderTo, 'scroll', me.onCtScroll, me, { buffer: 100 });

		// Prevent from bubbling click events to the grid view
		me.mon(me.el, {
			click: Ext.emptyFn,
			stopPropagation: true
		});

		me.el.swallowEvent([
			'keypress',
			'keydown'
		]);

		me.keyNav = new Ext.util.KeyNav(me.el, {
			//enter: plugin.completeEdit,
			esc: plugin.onEscKey,
			scope: plugin
		});

		me.mon(plugin.view, {
			beforerefresh: me.onBeforeViewRefresh,
			refresh: me.onViewRefresh,
			scope: me
		});
	},

	onBeforeViewRefresh: function(view) {
		var me = this,
			viewDom = view.el.dom;

		if (me.el.dom.parentNode === viewDom) {
			viewDom.removeChild(me.el.dom);
		}
	},

	onViewRefresh: function(view) {
		var me = this,
			viewDom = view.el.dom,
			context = me.context,
			idx;

		viewDom.appendChild(me.el.dom);

		// Recover our row node after a view refresh
		if (context && (idx = context.store.indexOf(context.record)) >= 0) {
			context.row = view.getNode(idx);
			me.reposition();
			if (me.tooltip && me.tooltip.isVisible()) {
				me.tooltip.setTarget(context.row);
			}
		} else {
			me.editingPlugin.cancelEdit();
		}
	},

	onCtScroll: function(e, target) {
		var me = this,
			scrollTop  = target.scrollTop,
			scrollLeft = target.scrollLeft;

		if (scrollTop !== me.lastScrollTop) {
			me.lastScrollTop = scrollTop;
			if ((me.tooltip && me.tooltip.isVisible()) || me.hiddenTip) {
				me.repositionTip();
			}
		}
		if (scrollLeft !== me.lastScrollLeft) {
			me.lastScrollLeft = scrollLeft;
			me.reposition();
		}
	},

	reposition: function (animateConfig) {

		if(this.currRowH) this.currRow.setHeight(this.currRowH);

		var me = this,
			context = me.context,
			row = context && Ext.get(context.row),
		//btns = me.getFloatingButtons(),
		//btnEl = btns.el,
			grid = me.editingPlugin.grid,
			viewEl = grid.view.el,
			scroller = grid.verticalScroller,


		// always get data from ColumnModel as its what drives
		// the GridView's sizing
			mainBodyWidth = grid.headerCt.getFullWidth(),
			scrollerWidth = grid.getWidth(),

		// use the minimum as the columns may not fill up the entire grid
		// width
			width = Math.min(mainBodyWidth, scrollerWidth),
			scrollLeft = grid.view.el.dom.scrollLeft,
		//btnWidth = btns.getWidth(),
		//left = (width - btnWidth) / 2 + scrollLeft,
			y, rowH, newHeight,

			invalidateScroller = function() {
				if (scroller) {
					scroller.invalidate();
					btnEl.scrollIntoView(viewEl, false);
				}
				if (animateConfig && animateConfig.callback) {
					animateConfig.callback.call(animateConfig.scope || me);
				}
			};

		// need to set both top/left
		if (row && Ext.isElement(row.dom)) {
			// Bring our row into view if necessary, so a row editor that's already
			// visible and animated to the row will appear smooth
			row.scrollIntoView(viewEl, false);

			// Get the y position of the row relative to its top-most static parent.
			// offsetTop will be relative to the table, and is incorrect
			// when mixed with certain grid features (e.g., grouping).
			y = row.getXY()[1] + 19;


			me.currRowH = row.getHeight();
			me.currRow = row;

			row.setHeight(me.getHeight() + 19);

			// IE doesn't set the height quite right.
			// This isn't a border-box issue, it even happens
			// in IE8 and IE7 quirks.
			// TODO: Test in IE9!
			if (Ext.isIE) {
				newHeight += 2;
			}

			if (animateConfig) {
				var animObj = {
					to: {
						y: y
					},
					duration: animateConfig.duration || 125,
					listeners: {
						afteranimate: function() {
							invalidateScroller();
							y = row.getXY()[1] + 19;
							me.el.setY(y);
						}
					}
				};
				me.animate(animObj);
			} else {
				me.el.setY(y);
				invalidateScroller();
			}
		}
		if (me.getWidth() != mainBodyWidth) {
			me.setWidth(mainBodyWidth);
		}
		//btnEl.setLeft(left);
	},

	resizeEditor:function(){

		if(this.currRowH) this.currRow.setHeight(this.currRowH);

		var me = this,
			context = me.context,
			row = context && Ext.get(context.row),
		//btns = me.getFloatingButtons(),
		//btnEl = btns.el,
			grid = me.editingPlugin.grid,
			viewEl = grid.view.el,
			scroller = grid.verticalScroller,


		// always get data from ColumnModel as its what drives
		// the GridView's sizing
			mainBodyWidth = grid.headerCt.getFullWidth(),
			scrollerWidth = grid.getWidth(),

		// use the minimum as the columns may not fill up the entire grid
		// width
			width = Math.min(mainBodyWidth, scrollerWidth),
			scrollLeft = grid.view.el.dom.scrollLeft,
		//btnWidth = btns.getWidth(),
		//left = (width - btnWidth) / 2 + scrollLeft,
			y, rowH, newHeight;


		// need to set both top/left
		if (row && Ext.isElement(row.dom)) {
			// Bring our row into view if necessary, so a row editor that's already
			// visible and animated to the row will appear smooth
			row.scrollIntoView(viewEl, false);

			// Get the y position of the row relative to its top-most static parent.
			// offsetTop will be relative to the table, and is incorrect
			// when mixed with certain grid features (e.g., grouping).
			y = row.getXY()[1] + 19;


			me.currRowH = row.getHeight();
			me.currRow = row;

			row.setHeight(me.getHeight() + 19);

			// IE doesn't set the height quite right.
			// This isn't a border-box issue, it even happens
			// in IE8 and IE7 quirks.
			// TODO: Test in IE9!
			if (Ext.isIE) {
				newHeight += 2;
			}

		}
		if (me.getWidth() != mainBodyWidth) {
			me.setWidth(mainBodyWidth);
		}
	},

	getGridStores:function(){
		var me = this,
			grids = me.query('grid'),
			stores = [];
		for(var i=0; i < grids.length; i++){
			stores.push(grids[i].store);
		}
		return stores;
	},

	syncChildStoresChanges:function(){
		var me = this,
			stores = me.getGridStores();
		for(var i=0; i < stores.length; i++){
			stores[i].sync();
		}
	},

	rejectChildStoresChanges:function(){
		var me = this,
			stores = me.getGridStores();
		for(var i=0; i < stores.length; i++){
			stores[i].rejectChanges();
		}
	},

	getEditor: function(fieldInfo) {
		var me = this;

		if (Ext.isNumber(fieldInfo)) {
			// Query only form fields. This just future-proofs us in case we add
			// other components to RowEditor later on.  Don't want to mess with
			// indices.
			return me.query('>[isFormField]')[fieldInfo];
		} else if (fieldInfo instanceof Ext.grid.column.Column) {
			return fieldInfo.getEditor();
		}
		return false;
	},

	setFields: function(column) {
		var me = this,
			form = me.getForm(),
			fields = form.getFields().items,
			containers = me.query('container');
		for(var i=0; i < fields.length; i++){
			me.mon(fields[i], 'change', me.onFieldValueChange, me);
		}
		for(var k=0; k < containers.length; k++){
			me.mon(containers[k], 'resize', me.resizeEditor, me);
		}
	},

	loadRecord: function(record) {
		var me = this,
			form = me.getForm(),
			updateBtn = me.query('button[action="update"]')[0],
			saveTxt = record.phantom ? 'Save' : 'Update';

		me.editingPlugin.fireEvent('beforerecordload', me, record);

		form.loadRecord(record);

		me.editingPlugin.fireEvent('recordload', me, record);

		// change the save btn text to update is the record is a phantom (new)
		updateBtn.setText(saveTxt);

		if(me.saveBtnEnabled) updateBtn.setDisabled(!me.saveBtnEnabled);

		if(this.errorSummary){
			if (form.isValid()) {
				me.hideToolTip();
			} else {
				me.showToolTip();
			}
		}


		// render display fields so they honor the column renderer/template
		Ext.Array.forEach(me.query('>displayfield'), function(field) {
			me.renderColumnData(field, record);
		}, me);
	},

	renderColumnData: function(field, record) {
		var me = this,
			grid = me.editingPlugin.grid,
			headerCt = grid.headerCt,
			view = grid.view,
			store = view.store,
			form = me.getForm();

		form.loadRecord(record);
	},

	beforeEdit: function() {
		var me = this;

		me.getGridStores();

		if (me.isVisible() && !me.autoCancel && me.isDirty()) {
			me.showToolTip();
			return false;
		}
	},

	/**
	 * Start editing the specified grid at the specified position.
	 * @param {Ext.data.Model} record The Store data record which backs the row to be edited.
	 * @param {Ext.data.Model} columnHeader The Column object defining the column to be edited.
	 */
	startEdit: function(record, columnHeader) {
		var me = this,
			grid = me.editingPlugin.grid,
			view = grid.getView(),
			store = grid.store,
			context = me.context = Ext.apply(me.editingPlugin.context, {
				view: grid.getView(),
				store: store
			});

//		make sure our row is selected before editing
		context.grid.getSelectionModel().select(record);

		// Reload the record data
		me.loadRecord(record);

		if (!me.isVisible()) {
			me.show();
			me.focusContextCell();
		} else {
			me.reposition({
				callback: this.focusContextCell
			});
		}
	},

	// Focus the cell on start edit based upon the current context
	focusContextCell: function() {
		var field = this.getEditor(this.context.colIdx);
		if (field && field.focus) {
			field.focus();
		}
	},

	cancelEdit: function() {
		var me = this,
			form = me.getForm();
		me.rejectChildStoresChanges();
		me.hide();
		form.clearInvalid();
		form.reset();
	},

	completeEdit: function() {
		var me = this,
			form = me.getForm();

		if (!form.isValid()) {
			return;
		}

		me.context.record.set(me.context.newValues);

		if(me.editingPlugin.autoSync){
			me.context.record.store.sync({
				callback:function(){
					me.fireEvent('sync', me, me.context);
				}
			});
		}
		me.syncChildStoresChanges();
		me.hide();
		return true;
	},

	completeRemove:function(){
		var me = this,
			form = me.getForm(),
			view = me.context.view,
			store = me.context.store,
			record = view.getSelectionModel().getLastSelected();
		if(view.panel.fireEvent('beforeremove', me, store, record)){
			Ext.Msg.show({
				title:'WAIT!!!',
				msg: 'Are you sure you want to remove this record?',
				buttons: Ext.Msg.YESNO,
				icon: Ext.Msg.QUESTION,
				scope:me,
				fn: function(btn){
					if (btn == 'yes'){
						if(record.parentNode){
							record.parentNode.removeChild(record);
						}else{
							store.remove(record);
						}
						view.panel.fireEvent('remove', me, store, record);
						me.hide();
						form.clearInvalid();
						form.reset();
						if(me.editingPlugin.autoSync){
							store.sync({
								callback:function(){
									me.fireEvent('sync', me, me.context);
								}
							});
						}
					}
				}
			});
		}
	},

	onShow: function() {
		var me = this;
		me.callParent(arguments);
		me.reposition();
	},

	onHide: function() {
		var me = this;
		me.callParent(arguments);
		me.hideToolTip();
		me.invalidateScroller();
		if (me.context) {
			me.context.view.focus();
			me.context = null;
		}
		me.currRow.setHeight(me.currRowH);
		me.currRowH = null;
	},

	isDirty: function() {
		var me = this,
			form = me.getForm();
		return form.isDirty();
	},

	getToolTip: function() {
		return this.tooltip || (this.tooltip = new Ext.tip.ToolTip({
			cls: Ext.baseCSSPrefix + 'grid-row-editor-errors',
			title: this.errorsText,
			autoHide: false,
			closable: true,
			closeAction: 'disable',
			anchor: 'left',
			anchorToTarget: false
		}));
	},

	hideToolTip: function() {
		var me = this,
			tip = me.getToolTip();
		if (tip.rendered) {
			tip.disable();
		}
		me.hiddenTip = false;
	},

	showToolTip: function() {
		var me = this,
			tip = me.getToolTip();

		tip.showAt([0, 0]);
		tip.update(me.getErrors());
		me.repositionTip();
		tip.enable();
	},

	repositionTip: function() {
		var me = this,
			tip = me.getToolTip(),
			context = me.context,
			row = Ext.get(context.row),
			viewEl = me.scrollingViewEl,
			viewHeight = viewEl.dom.clientHeight,
			viewTop = me.lastScrollTop,
			viewBottom = viewTop + viewHeight,
			rowHeight = row.getHeight(),
			rowTop = row.getOffsetsTo(me.context.view.body)[1],
			rowBottom = rowTop + rowHeight;

		if (rowBottom > viewTop && rowTop < viewBottom) {
			tip.showAt(tip.getAlignToXY(viewEl, 'tl-tr', [15, row.getOffsetsTo(viewEl)[1]]));
			me.hiddenTip = false;
		} else {
			tip.hide();
			me.hiddenTip = true;
		}
	},

	getErrors: function() {
		var me        = this,
			errors    = [],
			fields    = me.query('[isFormField]'),
			length    = fields.length,
			i;

		for (i = 0; i < length; i++) {
			errors = errors.concat(
				Ext.Array.map(fields[i].getErrors(), me.createErrorListItem)
			);
		}

		// Only complain about unsaved changes if all the fields are valid
		if (!errors.length && !me.autoCancel && me.isDirty()) {
			errors[0] = me.createErrorListItem(me.dirtyText);
		}

		return '<ul class="' + Ext.plainListCls + '">' + errors.join('') + '</ul>';
	},

	createErrorListItem: function(e) {
		return '<li class="' + Ext.baseCSSPrefix + 'grid-row-editor-errors-item">' + e + '</li>';
	},

	invalidateScroller: function() {
		var me = this,
			context = me.context,
			scroller = context.grid.verticalScroller;

		if (scroller) {
			scroller.invalidate();
		}
	}
});