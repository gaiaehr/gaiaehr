/**
 * Users.ejs.php
 * Description: Users Screen
 * v0.0.4
 *
 * Author: Ernesto J Rodriguez (Certun)
 * Modified: n/a
 *
 * GaiaEHR (Electronic Health Records) 2011
 *
 * @namespace User.getUsers
 * @namespace User.addUser
 * @namespace User.updateUser
 * @namespace User.chechPasswordHistory
 */
Ext.define('App.view.administration.Users', {
	extend       : 'App.classes.RenderPanel',
	id           : 'panelUsers',
	pageTitle    : 'Users',
	uses         : [
		'App.classes.GridPanel'
	],
	initComponent: function() {

		var me = this;

		Ext.define('UserModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id', type: 'int'},
				{name: 'username', type: 'string'},
				{name: 'password', type: 'auto'},
				{name: 'authorized', type: 'string'},
				{name: 'active', type: 'string'},
				{name: 'info', type: 'string'},
				{name: 'source', type: 'int'},
				{name: 'fname', type: 'string'},
				{name: 'mname', type: 'string'},
				{name: 'lname', type: 'string'},
				{name: 'fullname', type: 'string'},
				{name: 'federaltaxid', type: 'string'},
				{name: 'federaldrugid', type: 'string'},
				{name: 'upin', type: 'string'},
				{name: 'facility_id', type: 'int'},
				{name: 'see_auth', type: 'auto'},
				{name: 'active', type: 'auto'},
				{name: 'npi', type: 'string'},
				{name: 'title', type: 'string'},
				{name: 'specialty', type: 'string'},
				{name: 'cal_ui', type: 'string'},
				{name: 'taxonomy', type: 'string'},
				{name: 'calendar', type: 'auto'},
				{name: 'abook_type', type: 'string'},
				{name: 'default_warehouse', type: 'string'},
				{name: 'role_id', type: 'int'}
			]

		});

		me.userStore = Ext.create('Ext.data.Store', {
			model   : 'UserModel',
			proxy   : {
				type: 'direct',
				api : {
					read  : User.getUsers,
					create: User.addUser,
					update: User.updateUser
				}
			},
			autoLoad: false
		});

		function authCk(val) {
			if(val == '1') {
				return '<img src="ui_icons/yes.gif" />';
			} else if(val == '0') {
				return '<img src="ui_icons/no.gif" />';
			}
			return val;
		}

		// *************************************************************************************
		// Create the GridPanel
		// *************************************************************************************
		me.userGrid = Ext.create('App.classes.GridPanel', {
			store      : me.userStore,
			columns    : [
				{ text: 'id', sortable: false, dataIndex: 'id', hidden: true},
				{ width: 100, text: 'Username', sortable: true, dataIndex: 'username' },
				{ width: 200, text: 'Name', sortable: true, dataIndex: 'fullname' },
				{ flex: 1, text: 'Aditional info', sortable: true, dataIndex: 'info' },
				{ text: 'Active?', sortable: true, dataIndex: 'active', renderer: authCk },
				{ text: 'Authorized?', sortable: true, dataIndex: 'authorized', renderer: authCk },
				{ text: 'Calendar?', sortable: true, dataIndex: 'calendar', renderer: authCk }
			],
			listeners  : {
				scope       : me,
				itemdblclick: function(view, record) {
					me.onItemdblclick(me.userStore, record, 'Edit User');
				}
			},
			dockedItems: [
				{
					xtype: 'toolbar',
					dock : 'top',
					items: [
						{
							xtype  : 'button',
							text   : 'Add New User',
							iconCls: 'save',
							handler: function() {
								var form = me.win.down('form');
								me.onNew(form, 'UserModel', 'Add New User');
							}
						}
					]
				}
			]
		});

		// *************************************************************************************
		// Window User Form
		// *************************************************************************************
		me.win = Ext.create('App.classes.window.Window', {
			width    : 600,
			items    : [
				{
					xtype        : 'mitos.form',
					fieldDefaults: { msgTarget: 'side', labelWidth: 100 },
					defaultType  : 'textfield',
					//hideLabels      : true,
					defaults     : { labelWidth: 89, anchor: '100%',
						layout                 : { type: 'hbox', defaultMargins: {top: 0, right: 5, bottom: 0, left: 0} }
					},
					items        : [
						{
							xtype : 'textfield',
							hidden: true, name: 'id'
						},
						{
							xtype    : 'fieldcontainer',
							defaults : { hideLabel: true },
							msgTarget: 'under',
							items    : [
								{ width: 100, xtype: 'displayfield', value: 'Username: '},
								{ width: 100, xtype: 'textfield', name: 'username' },
								{ width: 100, xtype: 'displayfield', value: 'Password: '},
								{ width: 105, xtype: 'textfield', name: 'password', inputType: 'password' }
							]
						},
						{
							xtype    : 'fieldcontainer',
							defaults : { hideLabel: true },
							msgTarget: 'under',
							items    : [
								{ width: 100, xtype: 'displayfield', value: 'First, Middle, Last: '},
								{ width: 50, xtype: 'mitos.titlescombo', name: 'title' },
								{ width: 80, xtype: 'textfield', name: 'fname' },
								{ width: 65, xtype: 'textfield', name: 'mname' },
								{ width: 105, xtype: 'textfield', name: 'lname' }
							]
						},
						{
							xtype    : 'fieldcontainer',
							msgTarget: 'under',
							items    : [
								{ width: 150, xtype: 'mitos.checkbox', fieldLabel: 'Active?', name: 'active' },
								{ width: 150, xtype: 'mitos.checkbox', fieldLabel: 'Authorized?', name: 'authorized' },
								{ width: 150, xtype: 'mitos.checkbox', fieldLabel: 'Calendar?', name: 'calendar' }
							]
						},
						{
							xtype    : 'fieldcontainer',
							defaults : { hideLabel: true },
							msgTarget: 'under',
							items    : [
								{ width: 100, xtype: 'displayfield', value: 'Default Facility: '},
								{ width: 100, xtype: 'mitos.facilitiescombo', name: 'facility_id' },
								{ width: 100, xtype: 'displayfield', value: 'Authorizations: '},
								{ width: 105, xtype: 'mitos.authorizationscombo', name: 'see_auth' }
							]
						},
						{
							xtype   : 'fieldcontainer',
							defaults: { hideLabel: true },
							items   : [
								{ width: 100, xtype: 'displayfield', value: 'Access Control: '},
								{ width: 100, xtype: 'mitos.rolescombo', name: 'role_id' },
								// not implemented yet
								{ width: 100, xtype: 'displayfield', value: 'Taxonomy: '},
								{ width: 105, xtype: 'textfield', name: 'taxonomy' }
							]
						},
						{
							xtype   : 'fieldcontainer',
							defaults: { hideLabel: true },
							items   : [
								{ width: 100, xtype: 'displayfield', value: 'Federal Tax ID: '},
								{ width: 100, xtype: 'textfield', name: 'federaltaxid' },
								{ width: 100, xtype: 'displayfield', value: 'Fed Drug ID: '},
								{ width: 105, xtype: 'textfield', name: 'federaldrugid' }
							]
						},
						{
							xtype   : 'fieldcontainer',
							defaults: { hideLabel: true },
							items   : [
								{ width: 100, xtype: 'displayfield', value: 'UPIN: '},
								{ width: 100, xtype: 'textfield', name: 'upin' },
								{ width: 100, xtype: 'displayfield', value: 'NPI: '},
								{ width: 105, xtype: 'textfield', name: 'npi' }
							]
						},
						{
							xtype   : 'fieldcontainer',
							defaults: { hideLabel: true },
							items   : [
								{ width: 100, xtype: 'displayfield', value: 'Job Description: '},
								{ width: 315, xtype: 'textfield', name: 'specialty' }
							]
						},
						{
							width    : 410,
							height   : 50,
							xtype    : 'textfield',
							name     : 'info',
							emptyText: 'Additional Info'
						}
					]
				}
			],
			buttons  : [
				{
					text   : 'Save',
					cls    : 'winSave',
					handler: function() {
						var form = me.win.down('form').getForm();
						if(form.isValid()) {
							me.onSave(form, me.userStore);

						}
					}
				},
				'-',
				{
					text   : 'Cancel',
					scope  : me,
					handler: function(btn) {
						btn.up('wiindow').close();
					}
				}
			],
			listeners: {
				scope: me,
				close: function() {
					me.action('close');
				}
			}
		}); // END WINDOW
		me.pageBody = [ me.userGrid ];
		me.callParent(arguments);
	}, // end of initComponent

	onNew: function(form, model, title) {
		this.setForm(form, title);
		form.getForm().reset();
		var newModel = Ext.ModelManager.create({}, model);
		form.getForm().loadRecord(newModel);
		this.action('new');
		this.win.show();
	},

	onSave: function(form, store) {
		var me = this,
			password = form.findField('password').getValue(),
			id = form.findField('id').getValue();

		if(password != '') {
			User.chechPasswordHistory({password: password, id: id}, function(provider, response) {
				if(response.result.error) {
					Ext.Msg.alert('Opps!', 'This password is currently in used, or has been used before.<br>Please use a different password.');
				} else {
					me.save(form, store);
				}
			});
		} else {
			me.save(form, store);
		}

	},

	save: function(form, store) {
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
		this.win.close();
	},


	onItemdblclick: function(store, record, title) {
		var form = this.win.down('form');
		this.setForm(form, title);
		form.getForm().loadRecord(record);
		this.action('old');
		this.win.show();
	},

	setForm: function(form, title) {
		form.up('window').setTitle(title);
	},

	openWin: function() {
		this.win.show();
	},

	action: function(action) {
		var win = this.win,
			form = win.down('form');

		if(action == 'close') {
			form.getForm().reset();
		}
	},

	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback) {
		this.userStore.load();
		callback(true);
	}
}); //ens UserPage class