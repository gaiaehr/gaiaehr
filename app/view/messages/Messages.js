/*
 GaiaEHR (Electronic Health Records)
 Messages.js
 Messages Panel
 Copyright (C) 2012 Ernesto Rodriguez

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
Ext.define('App.view.messages.Messages',
{
	extend : 'App.ux.RenderPanel',
	id : 'panelMessages',
	pageTitle : i18n('messages') + ' (' + i18n('inbox') + ')',
	pageLayout : 'border',
	defaults :
	{
		split : true
	},
	uses : ['App.ux.GridPanel', 'App.ux.LivePatientSearch', 'App.ux.combo.MsgStatus', 'App.ux.combo.MsgNoteType', 'App.ux.combo.Users'],
	initComponent : function()
	{

		var me = this;
		/**
		 * Message Store
		 */
		Ext.define('MessagesModel',
		{
			extend : 'Ext.data.Model',
			fields : [
			{
				name : 'id',
				type : 'int'
			},
			{
				name : 'date',
				type : 'string'
			},
			{
				name : 'body',
				type : 'string'
			},
			{
				name : 'pid',
				type : 'string'
			},
			{
				name : 'patient_name',
				type : 'string'
			},
			{
				name : 'from_user',
				type : 'string'
			},
			{
				name : 'to_user',
				type : 'string'
			},
			{
				name : 'subject',
				type : 'string'
			},
			{
				name : 'facility_id',
				type : 'string'
			},
			{
				name : 'authorized',
				type : 'string'
			},
			{
				name : 'to_id',
				type : 'string'
			},
			{
				name : 'from_id',
				type : 'string'
			},
			{
				name : 'message_status',
				type : 'string'
			},
			{
				name : 'note_type',
				type : 'string'
			}]

		});

		me.storeMsgs = Ext.create('Ext.data.Store',
		{
			model : 'MessagesModel',
			proxy :
			{
				type : 'direct',
				api :
				{
					read : Messages.getMessages,
					create : Messages.sendNewMessage,
					update : Messages.replyMessage,
					destroy : Messages.deleteMessage
				},
				reader :
				{
					type : 'json',
					root : 'messages',
					totalProperty : 'totals'
				}
			},
			autoLoad : false
		});

		/**
		 * Message GridPanel
		 */
		me.msgGrid = Ext.create('App.ux.GridPanel',
		{
			store : me.storeMsgs,
			region : 'center',
			border : true,
			viewConfig :
			{
				forceFit : true,
				stripeRows : true
			},
			listeners :
			{
				scope : this,
				itemclick : this.onItemClick
			},
			columns : [
			{
				header : i18n('status'),
				sortable : true,
				dataIndex : 'message_status',
				width : 70
			},
			{
				header : i18n('from'),
				sortable : true,
				dataIndex : 'from_user',
				width : 200
			},
			{
				header : i18n('to'),
				sortable : true,
				dataIndex : 'to_user',
				width : 200
			},
			{
				header : i18n('patient'),
				sortable : true,
				dataIndex : 'patient_name',
				width : 200
			},
			{
				header : i18n('subject'),
				sortable : true,
				dataIndex : 'subject',
				flex : 1
			},
			{
				header : i18n('type'),
				sortable : true,
				dataIndex : 'note_type',
				width : 100
			}],
			tbar : Ext.create('Ext.PagingToolbar',
			{
				store : me.storeMsgs,
				displayInfo : true,
				emptyMsg : i18n('no_office_notes_to_display'),
				plugins : Ext.create('Ext.ux.SlidingPager',
				{
				}),
				items : ['-',
				{
					text : i18n('delete'),
					cls : 'winDelete',
					iconCls : 'delete',
					itemId : 'deleteMsg',
					disabled : true,
					scope : me,
					handler : me.onDelete
				}, '-',
				{
					text : i18n('inbox'),
					action : 'inbox',
					enableToggle : true,
					toggleGroup : 'message',
					pressed : true,
					scope : me,
					handler : me.messagesType
				}, '-',
				{
					text : i18n('sent'),
					action : 'sent',
					enableToggle : true,
					toggleGroup : 'message',
					scope : me,
					handler : me.messagesType
				}, '-',
				{
					text : i18n('trash'),
					action : 'trash',
					enableToggle : true,
					toggleGroup : 'message',
					scope : me,
					handler : me.messagesType
				}, '-']
			}),
			bbar : [
			{
				text : i18n('new_message'),
				iconCls : 'newMessage',
				itemId : 'newMsg',
				handler : function()
				{
					me.onNewMessage();
				}
			}, '-',
			{
				text : i18n('reply'),
				iconCls : 'edit',
				itemId : 'replyMsg',
				disabled : true,
				handler : function()
				{
					me.action('reply');
				}
			}, '-']
		});
		/**
		 * Form to send and replay messages
		 */
		me.msgForm = Ext.create('Ext.form.Panel',
		{
			region : 'south',
			height : 340,
			cls : 'msgForm',
			layout :
			{
				type : 'vbox',
				align : 'stretch'
			},
			fieldDefaults :
			{
				labelWidth : 60,
				margin : 5,
				anchor : '100%'
			},
			items : [
			{
				xtype : 'container',
				height : 95,
				cls : 'message-form-header',
				padding : '5 0',
				layout : 'anchor',
				items : [
				{
					xtype : 'container',
					layout : 'column',
					items : [
					{
						xtype : 'container',
						layout : 'anchor',
						columnWidth : '.50',
						items : [
						{
							xtype : 'patienlivetsearch',
							fieldLabel : i18n('patient'),
							emptyText : i18n('no_patient_selected'),
							itemId : 'patientCombo',
							name : 'pid',
							hideLabel : false
						},
						{
							xtype : 'textfield',
							fieldLabel : i18n('patient'),
							itemId : 'patientField',
							name : 'patient_name',
							readOnly : true,
							hidden : true
						},
						{
							xtype : 'userscombo',
							name : 'to_id',
							fieldLabel : i18n('to'),
							validateOnChange : false,
							allowBlank : false
						}]
					},
					{
						xtype : 'container',
						layout : 'anchor',
						columnWidth : '.50',
						items : [
						{
							xtype : 'msgnotetypecombo',
							name : 'note_type',
							fieldLabel : i18n('type'),
							listeners :
							{
								scope : me,
								select : me.onChange
							}
						},
						{
							xtype : 'msgstatuscombo',
							name : 'message_status',
							fieldLabel : i18n('status'),
							listeners :
							{
								scope : me,
								select : me.onChange
							}
						}]
					}]
				},
				{
					xtype : 'textfield',
					fieldLabel : i18n('subject'),
					name : 'subject',
					margin : '0 5 5 5'
				}]
			},
			{
				xtype : 'htmleditor',
				name : 'body',
				itemId : 'bodyMsg',
				flex : 1,
				readOnly : true,
				allowBlank : false
			},
			//				{
			//					xtype           : 'htmleditor',
			//					name            : 'curr_msg',
			//					itemId          : 'currMsg',
			//					height          : 204,
			//					allowBlank      : false,
			//					validateOnChange: false,
			//					hidden          : true
			//				},
			{
				xtype : 'textfield',
				hidden : true,
				name : 'id'
			},
			{
				xtype : 'textfield',
				hidden : true,
				name : 'pid'
			},
			{
				xtype : 'textfield',
				hidden : true,
				name : 'reply_id'
			}],
			bbar : [
			{
				text : i18n('send'),
				iconCls : 'save',
				itemId : 'sendMsg',
				scope : me,
				handler : me.onSend
			}, '-',
			{
				text : i18n('delete'),
				cls : 'winDelete',
				iconCls : 'delete',
				itemId : 'deleteMsg',
				margin : '0 3 0 0',
				disabled : true,
				scope : me,
				handler : me.onDelete
			}],
			listeners :
			{
				scope : me,
				afterrender : me.onFormRender

			}
		});
		me.pageBody = [me.msgGrid, me.msgForm];
		me.callParent(arguments);

	}, // End initComponent

	messagesType : function(btn)
	{
		this.updateTitle('Messages (' + Ext.String.capitalize(btn.action) + ')');
		this.storeMsgs.proxy.extraParams =
		{
			get : btn.action
		};
		this.storeMsgs.load();

	},

	onFormRender : function()
	{
		this.msgForm.getComponent('bodyMsg').setReadOnly(true);
		this.onNewMessage();
	},
	/**
	 * onNewMessage will reset the form and load a new model
	 * with message_status value set to New, and
	 * note_type value set to Unassigned
	 */
	onNewMessage : function()
	{
		var form = this.msgForm;
		form.getForm().reset();
		var model = Ext.ModelManager.getModel('MessagesModel'), newModel = Ext.ModelManager.create(
		{
			message_status : i18n('new'),
			note_type : i18n('unassigned')
		}, model);
		form.getForm().loadRecord(newModel);
		this.action('new');
	},
	/**
	 *
	 * @param btn
	 */
	onSend : function(btn)
	{
		var form = btn.up('form').getForm(), store = this.storeMsgs;

		if (form.isValid())
		{
			var record = form.getRecord(), values = form.getValues(), storeIndex = store.indexOf(record);

			if (storeIndex == -1)
			{
				store.add(values);
			}
			else
			{
				record.set(values);
			}
			store.sync();
			store.load();
			this.onNewMessage();
			this.msg('Sweet!', i18n('message_sent'));
		}
		else
		{
			this.msg('Oops!', i18n('please_complete_all_required_fields') + '.');
		}
	},
	/**
	 *
	 * onDelete will show an alert msg to confirm,
	 * delete the message and prepare the form for a new message
	 */
	onDelete : function()
	{
		var form = this.msgForm.getForm(), store = this.storeMsgs;
		Ext.Msg.show(
		{
			title : i18n('please_confirm') + '...',
			icon : Ext.MessageBox.QUESTION,
			msg : i18n('are_you_sure_to_delete_this_message'),
			buttons : Ext.Msg.YESNO,
			scope : this,
			fn : function(btn)
			{
				if (btn == 'yes')
				{
					var currentRec = form.getRecord();
					store.remove(currentRec);
					store.destroy();
					this.onNewMessage();
					this.msg('Sweet!', i18n('sent_to_trash'));
				}
			}
		});
	},
	onChange : function(combo, record)
	{
		var me = this, form = combo.up('form').getForm();

		if (form.isValid())
		{

			var id = form.getRecord().data.id, col = combo.name, val = record[0].data.option_id, params =
			{
				id : id,
				col : col,
				val : val
			};

			/**
			 * Ext.direct function
			 */
			Messages.updateMessage(params, function()
			{
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
	onItemClick : function(view, record)
	{
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
	action : function(action)
	{
		var sm = this.msgGrid.getSelectionModel(), form = this.msgForm, patientCombo = form.query('combo[itemId="patientCombo"]')[0], patientField = form.query('textfield[itemId="patientField"]')[0], bodyMsg = form.getComponent('bodyMsg'), currMsg = form.getComponent('currMsg'), deletebtn1 = this.query('button[itemId="deleteMsg"]')[0], deletebtn2 = this.query('button[itemId="deleteMsg"]')[1], replybtn = this.query('button[itemId="replyMsg"]')[0], sendbtn = this.query('button[itemId="sendMsg"]')[0];
		if (action == 'new')
		{
			bodyMsg.setReadOnly(false);
			patientCombo.show();
			patientField.hide();
			deletebtn1.disable();
			deletebtn2.disable();
			replybtn.disable();
			sendbtn.enable();
			sm.deselectAll();
		}
		else
		if (action == 'old')
		{
			bodyMsg.setReadOnly(true);
			patientCombo.hide();
			patientField.show();
			deletebtn1.enable();
			deletebtn2.enable();
			replybtn.enable();
			sendbtn.disable();
		}
		else
		if (action == 'reply')
		{
			var msg = bodyMsg.getValue();
			bodyMsg.setValue('<br><br><br><qoute style="margin-left: 10px; padding-left: 10px; border-left: solid 3px #cccccc; display: block;">' + msg + '</quote>');
			bodyMsg.setReadOnly(false);
			sendbtn.enable();
			patientCombo.hide();
			patientField.show();
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
		this.storeMsgs.load();
		callback(true);
	}
});
