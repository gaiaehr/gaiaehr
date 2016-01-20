Ext.define('App.ux.combo.XCombo', {
	extend: 'Ext.form.field.ComboBox',
	xtype: 'xcombo',

	trigger1Class: 'x-form-select-trigger',
	trigger2Class: 'x-form-add-trigger',
	trigger3Class: 'x-form-update-trigger',

	editable: false,

	addTooltip: 'Add Item',
	saveText: 'Save',
	cancelText: 'Cancel',
	maskText: 'Saving Data',

	windowConfig: {
		title: 'New Record',
		modal: true
	},

	formConfig: {
		width: 400,
		height: 240,
		border: false,
		html: 'Form placeholder, please add a formConfig property<br>' +
			'Exmaple:<br>' +
			'<pre>' +
			'{<br>' +
			'   ptype: "comboadd",<br>' +
			'   windowConfig: {<br>' +
			'       title: "New Record"<br>' +
			'       modal: true<br>' +
			'   }<br>' +
			'   formConfig: {<br>' +
			'       width: 600,<br>' +
			'       height: 400,<br>' +
			'       border: false,<br>' +
			'       items:[ {...},{...} ]<br>' +
			'   }<br>' +
			'}<br>' +
			'</pre>'
	},

	initComponent: function (config) {
		var me = this;

		me.addEvents(
			'cancel',
			'beforesync',
			'sync',
			'failure'
		);

		me.on('select', me.setUpdateTrigger, me);

		me.callParent(arguments);
	},

	onRender: function (ct, position) {

		var me = this,
			id = me.getId();

		me.callParent(arguments);

		me.triggerWidth = 51;

		me.triggerConfig = {
			tag: 'td',
			valign: 'top',
			cls: 'x-trigger-cell',
			style: 'width:34px',
			cn: [
				{
					tag: "img",
					src: Ext.BLANK_IMAGE_URL,
					id: "trigger1" + id,
					name: "trigger1" + id,
					style: "float:left",
					cls: "x-form-trigger " + this.trigger1Class,
					role: 'button'
				},
				{
					tag: "img",
					src: Ext.BLANK_IMAGE_URL,
					id: "trigger2" + id,
					name: "trigger2" + id,
					style: "float:left",
					cls: "x-form-trigger " + this.trigger2Class,
					role: 'button'
				},
				{
					tag: "img",
					src: Ext.BLANK_IMAGE_URL,
					id: "trigger3" + id,
					name: "trigger3" + id,
					style: "float:left;display:none",
					cls: "x-form-trigger " + this.trigger3Class,
					role: 'button'
				}
			]
		};

		me.triggerCell.replaceWith(me.triggerConfig);

		me.trigger1 = Ext.get("trigger1" + id);
		me.trigger2 = Ext.get("trigger2" + id);
		me.trigger3 = Ext.get("trigger3" + id);

		me.trigger1.on('mouseup', me.triggerClick, me);
		me.trigger2.on('mouseup', me.triggerClick, me);
		me.trigger3.on('mouseup', me.triggerClick, me);

		me.trigger1.addClsOnOver('x-form-trigger-over');
		me.trigger2.addClsOnOver('x-form-trigger-over');
		me.trigger3.addClsOnOver('x-form-trigger-over');
	},

	setUpdateTrigger: function () {
		if (!this.trigger3.isVisible()) {
			this.setWidth(this.getWidth() + 17);
			this.triggerCell.setWidth(51);
			this.trigger3.show();
		}
	},

	triggerClick: function (e) {
		var id = this.getId();
		if (e.target.name == "trigger1" + id) {
			this.onTriggerClick();
		} else if (e.target.name == "trigger2" + id) {
			this.onTriggerAddClick();
		} else if (e.target.name == "trigger3" + id) {
			this.onTriggerUpdateClick();
		}
	},

	/**
	 * Start the window
	 */
	onTriggerAddClick: function () {
		var me = this;
		me.reset();
		me.getWindow().show();
		me.uWindow.down('form').getForm().loadRecord(me.getNewRecord());
	},

	/**
	 * Start the window
	 */
	onTriggerUpdateClick: function () {
		var me = this;
		me.getWindow().show();
		me.uWindow.down('form').getForm().loadRecord(me.getSelectedRecord());
	},

	getSelectedRecord: function () {
		return this.findRecordByValue(this.getValue());
	},

	getNewRecord: function () {
		return Ext.create(this.getStore().model);
	},

	/**
	 * Creates a new window
	 */
	getWindow: function () {
		var me = this;

		me.uWindow = Ext.widget('window', {
			items: [Ext.widget('form', me.formConfig)],
			buttons: [
				{
					text: me.cancelText,
					scope: me,
					handler: me.doCancelRecord
				},
				{
					text: me.saveText,
					scope: me,
					handler: me.doSaveRecord
				}
			]
		});

		return Ext.apply(me.uWindow, me.windowConfig);
	},

	/**
	 * Saves the record and to combobox sotore everything
	 */
	doSaveRecord: function () {
		var me = this,
			panel = me.uWindow.down('form'),
			form = panel.getForm(),
			record = form.getRecord(),
			values = form.getValues(),
			index = me.store.indexOf(record);

		record.set(values);
		if (index == -1) me.store.add(record);

		if (me.store.getNewRecords().length || me.store.getUpdatedRecords().length) {
			panel.el.mask(me.maskText);
			// fires the beforesync event and add the values to the store
			me.fireEvent('beforesync', me.store, record);

			me.store.sync({
				// hanlde sync success
				success: function(batch, options) {
					me.select(record);
					me.fireEvent('sync', me.store, record, batch, options);
				},
				// handle sync failure
				failure: function() {
					me.fireEvent('failure', me.store, record, batch, options);
				},
				// handle all request
				callback: function() {
					panel.el.unmask();
					form.reset();
					me.uWindow.close();
				}
			});
		} else {
			form.reset();
			me.uWindow.close();
		}
	},

	/**
	 * Cancels everything
	 */
	doCancelRecord: function () {
		var me = this,
			form = me.uWindow.down('form').getForm();

		me.fireEvent('cancel', me, me.form, me.store);
		form.reset();
		me.uWindow.close();
	}
});
