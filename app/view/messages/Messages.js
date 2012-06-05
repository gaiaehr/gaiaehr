/**
 *
 * @namespace Messages.getMessages
 * @namespace Messages.sendNewMessage
 * @namespace Messages.replyMessage
 * @namespace Messages.deleteMessage
 * @namespace Messages.updateMessage
 */
Ext.define('App.view.messages.Messages', {
	extend       : 'App.classes.RenderPanel',
	id           : 'panelMessages',
	pageTitle    : 'Messages (Inbox)',
	pageLayout   : 'border',
	defaults     : {split: true},
	uses         : [
		'App.classes.GridPanel',
		'App.classes.LivePatientSearch',
		'App.classes.combo.MsgStatus',
		'App.classes.combo.MsgNoteType',
		'App.classes.combo.Users'
	],
	initComponent: function() {

		var me = this;
		/**
		 * Message Store
		 */
		Ext.define('MessagesModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id', type: 'int'},
				{name: 'date', type: 'string'},
				{name: 'body', type: 'string'},
				{name: 'curr_msg', type: 'string'},
				{name: 'pid', type: 'string'},
				{name: 'patient_name', type: 'string'},
				{name: 'from_user', type: 'string'},
				{name: 'to_user', type: 'string'},
				{name: 'subject', type: 'string'},
				{name: 'facility_id', type: 'string'},
				{name: 'authorized', type: 'string'},
				{name: 'to_id', type: 'string'},
				{name: 'from_id', type: 'string'},
				{name: 'message_status', type: 'string'},
				{name: 'note_type', type: 'string'}
			]

		});

		me.storeMsgs = Ext.create('Ext.data.Store', {
			model   : 'MessagesModel',
			proxy   : {
				type: 'direct',
				api : {
					read   : Messages.getMessages,
					create : Messages.sendNewMessage,
					update : Messages.replyMessage,
					destroy: Messages.deleteMessage
				},
                reader     : {
                    type: 'json',
                    root: 'messages',
                    totalProperty:'totals'
                }
			},
			autoLoad: false
		});

		/**
		 * Message GridPanel
		 */
		me.msgGrid = Ext.create('App.classes.GridPanel', {
			store     : me.storeMsgs,
			region    : 'center',
			border    : true,
			viewConfig: {forceFit: true, stripeRows: true},
			listeners : {
				scope    : this,
				itemclick: this.onItemClick
			},
			columns   : [
				{ header: 'Status', sortable: true, dataIndex: 'message_status', width: 70   },
				{ header: 'From', sortable: true, dataIndex: 'from_user', width: 200  },
				{ header: 'To', sortable: true, dataIndex: 'to_user', width: 200  },
				{ header: 'Patient', sortable: true, dataIndex: 'patient_name', width: 200  },
				{ header: 'Subject', sortable: true, dataIndex: 'subject', flex: 1    },
				{ header: 'Type', sortable: true, dataIndex: 'note_type', width: 100  }
			],
			tbar      : Ext.create('Ext.PagingToolbar', {
				store      : me.storeMsgs,
				displayInfo: true,
				emptyMsg   : "No Office Notes to display",
				plugins    : Ext.create('Ext.ux.SlidingPager', {}),
				items      : ['-', {
					text    : 'Delete',
					cls     : 'winDelete',
					iconCls : 'delete',
					itemId  : 'deleteMsg',
					disabled: true,
					scope   : me,
					handler : me.onDelete
				}, '-', {
					text        : 'Inbox',
					action      : 'inbox',
					enableToggle: true,
					toggleGroup : 'message',
					pressed     : true,
					scope       : me,
					handler     : me.messagesType
				}, '-', {
					text        : 'Sent',
					action      : 'sent',
					enableToggle: true,
					toggleGroup : 'message',
					scope       : me,
					handler     : me.messagesType
				}, '-', {
					text        : 'Trash',
					action      : 'trash',
					enableToggle: true,
					toggleGroup : 'message',
					scope       : me,
					handler     : me.messagesType
				}, '-']
			}),
			bbar      : [
				{
					text   : 'New message',
					iconCls: 'newMessage',
					itemId : 'newMsg',
					handler: function() {
						me.onNew();
					}
				},
				'-',
				{
					text    : 'Reply',
					iconCls : 'edit',
					itemId  : 'replyMsg',
					disabled: true,
					handler : function() {
						me.action('reply');
					}
				},
				'-'
			]
		});
		/**
		 * Form to send and replay messages
		 */
		me.msgForm = Ext.create('Ext.form.Panel', {
			region       : 'south',
			height       : 340,
			cls          : 'msgForm',
			fieldDefaults: { labelWidth: 60, margin: 5, anchor: '100%' },
			items        : [
				{
					xtype  : 'container',
					cls    : 'message-form-header',
					padding: '5 0',
					layout : 'anchor',
					items  : [
						{
							xtype : 'container',
							layout: 'column',
							items : [
								{
									xtype      : 'container',
									layout     : 'anchor',
									columnWidth: '.50',
									items      : [
										{
											xtype     : 'patienlivetsearch',
											fieldLabel: 'Patient',
											emptyText : 'No patient selected',
											itemId    : 'patientCombo',
											name      : 'pid',
											hideLabel : false
										},
										{
											xtype     : 'textfield',
											fieldLabel: 'Patient',
											itemId    : 'patientField',
											name      : 'patient_name',
											readOnly  : true,
											hidden    : true
										},
										{
											xtype           : 'userscombo',
											name            : 'to_id',
											fieldLabel      : 'To',
											validateOnChange: false,
											allowBlank      : false
										}
									]
								},
								{
									xtype      : 'container',
									layout     : 'anchor',
									columnWidth: '.50',
									items      : [
										{
											xtype     : 'msgnotetypecombo',
											name      : 'note_type',
											fieldLabel: 'Type',
											listeners : {
												scope : me,
												select: me.onChange
											}
										},
										{
											xtype     : 'msgstatuscombo',
											name      : 'message_status',
											fieldLabel: 'Status',
											listeners : {
												scope : me,
												select: me.onChange
											}
										}
									]
								}
							]
						},
						{
							xtype     : 'textfield',
							fieldLabel: 'Subject',
							name      : 'subject',
							margin    : '0 5 5 5'
						}
					]
				},
				{
					xtype   : 'htmleditor',
					name    : 'body',
					itemId  : 'bodyMsg',
					height  : 204,
					readOnly: true

				},
				{
					xtype           : 'htmleditor',
					name            : 'curr_msg',
					itemId          : 'currMsg',
					height          : 204,
					allowBlank      : false,
					validateOnChange: false,
					hidden          : true
				},
				{
					xtype : 'textfield',
					hidden: true,
					name  : 'id'
				},
				{
					xtype : 'textfield',
					hidden: true,
					name  : 'pid'
				},
				{
					xtype : 'textfield',
					hidden: true,
					name  : 'reply_id'
				}
			],
			bbar         : [
				{
					text   : 'Send',
					iconCls: 'save',
					cls    : 'winSave',
					itemId : 'sendMsg',
					scope  : me,
					handler: me.onSend
				},
				'-',
				{
					text    : 'Delete',
					cls     : 'winDelete',
					iconCls : 'delete',
					itemId  : 'deleteMsg',
					margin  : '0 3 0 0',
					disabled: true,
					scope   : me,
					handler : me.onDelete
				}
			],
			listeners    : {
				scope      : me,
				afterrender: me.onFormRender

			}
		});
		me.pageBody = [ me.msgGrid, me.msgForm ];
		me.callParent(arguments);


	}, // End initComponent

	messagesType: function(btn) {
		this.updateTitle('Messages (' + Ext.String.capitalize(btn.action) + ')');
		this.storeMsgs.proxy.extraParams = {get: btn.action};
		this.storeMsgs.load();

	},

	onFormRender: function() {
		this.msgForm.getComponent('bodyMsg').getToolbar().hide();
		this.onNew();
	},
	/**
	 * onNew will reset the form and load a new model
	 * with message_status value set to New, and
	 * note_type value set to Unassigned
	 */
	onNew       : function() {
		var form = this.msgForm;
		form.getForm().reset();
		var model = Ext.ModelManager.getModel('MessagesModel'),
			newModel = Ext.ModelManager.create({
				message_status: 'New',
				note_type     : 'Unassigned'
			}, model);
		form.getForm().loadRecord(newModel);
		this.action('new');
	},
	/**
	 *
	 * @param btn
	 */
	onSend      : function(btn) {
		var form = btn.up('form').getForm(),
			store = this.storeMsgs;

		if(form.isValid()) {
			var record = form.getRecord(),
				values = form.getValues(),
				storeIndex = store.indexOf(record);

			if(storeIndex == -1) {
				store.add(values);
			} else {
				record.set(values);
			}
			store.sync();
			store.load();
			this.onNew();
			this.msg('Sweet!', 'Message Sent');
		} else {
			this.msg('Oops!', 'Please, complete all required fields.');
		}
	},
	/**
	 *
	 * onDelete will show an alert msg to confirm,
	 * delete the message and prepare the form for a new message
	 */
	onDelete    : function() {
		var form = this.msgForm.getForm(),
			store = this.storeMsgs;
		Ext.Msg.show({
			title  : 'Please confirm...',
			icon   : Ext.MessageBox.QUESTION,
			msg    : 'Are you sure to delete this message?',
			buttons: Ext.Msg.YESNO,
			scope  : this,
			fn     : function(btn) {
				if(btn == 'yes') {
					var currentRec = form.getRecord();
					store.remove(currentRec);
					store.destroy();
					this.onNew();
					this.msg('Sweet!', 'Sent to Trash');
				}
			}
		});
	},
	onChange    : function(combo, record) {
		var me = this,
			form = combo.up('form').getForm();

		if(form.isValid()) {

			var id = form.getRecord().data.id,
				col = combo.name,
				val = record[0].data.option_id,
				params = {
					id : id,
					col: col,
					val: val
				};

			/**
			 * Ext.direct function
			 */
			Messages.updateMessage(params, function() {
				me.storeMsgs.load();
			});

		}

	},
	/**
	 * On item click check if msgPreView is already inside the container.
	 * if not, remove the item inside the container, add msgPreView and update it with record data.
	 * if yes, just update the msgPreView with the new record data
	 *
	 * @param view
	 * @param record
	 * @namespace record.data.from_id
	 */
	onItemClick : function(view, record) {
		record.data.to_id = record.data.from_id;
		this.msgForm.getForm().loadRecord(record);
		this.action('old');
	},

	/**
	 * This function is use to disable/enabled and hide/show buttons and fields
	 * according to the action
	 *
	 * @param action
	 */
	action  : function(action) {
		var sm = this.msgGrid.getSelectionModel(),
			form = this.msgForm,
			patientCombo = form.query('combo[itemId="patientCombo"]')[0],
			patientField = form.query('textfield[itemId="patientField"]')[0],
			bodyMsg = form.getComponent('bodyMsg'),
			currMsg = form.getComponent('currMsg'),
			deletebtn1 = this.query('button[itemId="deleteMsg"]')[0],
			deletebtn2 = this.query('button[itemId="deleteMsg"]')[1],
			replybtn = this.query('button[itemId="replyMsg"]')[0],
			sendbtn = this.query('button[itemId="sendMsg"]')[0];
		if(action == 'new') {
			bodyMsg.hide();
			currMsg.show();
			patientCombo.show();
			patientField.hide();
			deletebtn1.disable();
			deletebtn2.disable();
			replybtn.disable();
			sendbtn.enable();
			sm.deselectAll();
		} else if(action == 'old') {
			bodyMsg.show();
			currMsg.hide();
			patientCombo.hide();
			patientField.show();
			deletebtn1.enable();
			deletebtn2.enable();
			replybtn.enable();
			sendbtn.disable();
			bodyMsg.getToolbar().hide();
		} else if(action == 'reply') {
			var msg = bodyMsg.getValue();
			currMsg.setValue('<br><br><br><qoute style="margin-left: 10px; padding-left: 10px; border-left: solid 3px #cccccc; display: block;">' + msg + '</quote>');
			bodyMsg.hide();
			currMsg.show();
			sendbtn.enable();
			patientCombo.hide();
			patientField.show();
		}
	},
	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback) {
		this.storeMsgs.load();
		callback(true);
	}
});