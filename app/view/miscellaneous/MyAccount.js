/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun

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

Ext.define('App.view.miscellaneous.MyAccount',
{
	extend : 'App.ux.RenderPanel',
	id : 'panelMyAccount',
	pageTitle : i18n('my_account'),
	uses : ['App.ux.combo.Titles', 'App.ux.window.Window', 'App.ux.combo.Facilities', 'App.ux.combo.Authorizations'],
	initComponent : function()
	{
		var me = this;

		me.store = Ext.create('App.store.miscellaneous.UsersModel');

		// *************************************************************************************
		// User Settings Form
		// Add or Edit purpose
		// *************************************************************************************
		me.myAccountForm = Ext.create('App.ux.form.Panel',
		{
			cls : 'form-white-bg',
			frame : true,
			hideLabels : true,
			defaults :
			{
				labelWidth : 89,
				layout :
				{
					type : 'hbox',
					defaultMargins :
					{
						top : 0,
						right : 5,
						bottom : 0,
						left : 0
					}
				}
			},
			items : [
			{
				xtype : 'textfield',
				hidden : true,
				name : 'id'
			},
			{
				xtype : 'fieldset',
				title : i18n('personal_info'),
				defaultType : 'textfield',
				layout : 'anchor',
				defaults :
				{
					labelWidth : 89,
					anchor : '100%',
					layout :
					{
						type : 'hbox',
						defaultMargins :
						{
							top : 0,
							right : 5,
							bottom : 0,
							left : 0
						}
					}
				},
				items : [
				{
					xtype : 'fieldcontainer',
					defaults :
					{
						hideLabel : true
					},
					msgTarget : 'under',
					items : [
					{
						width : 110,
						xtype : 'displayfield',
						value : 'First, Middle, Last: '
					},
					{
						width : 55,
						xtype : 'mitos.titlescombo',
						name : 'title'
					},
					{
						width : 105,
						xtype : 'textfield',
						name : 'fname'
					},
					{
						width : 100,
						xtype : 'textfield',
						name : 'mname'
					},
					{
						width : 175,
						xtype : 'textfield',
						name : 'lname'
					}]
				}]
			},
			{
				xtype : 'fieldset',
				title : i18n('login_info'),
				defaultType : 'textfield',
				layout : 'anchor',
				defaults :
				{
					labelWidth : 89,
					anchor : '100%',
					layout :
					{
						type : 'hbox',
						defaultMargins :
						{
							top : 0,
							right : 5,
							bottom : 0,
							left : 0
						}
					}
				},
				items : [
				{
					xtype : 'fieldcontainer',
					defaults :
					{
						hideLabel : true
					},
					msgTarget : 'under',
					items : [
					{
						width : 110,
						xtype : 'displayfield',
						value : 'Username: '
					},
					{
						width : 150,
						xtype : 'textfield',
						name : 'username'
					},
					{
						width : 120,
						xtype : 'displayfield',
						value : 'Password: '
					},
					{
						width : 175,
						xtype : 'textfield',
						name : 'password',
						inputType : 'password',
						disabled : true
					}]
				}]
			},
			{
				xtype : 'fieldset',
				title : i18n('other_info'),
				defaultType : 'textfield',
				layout : 'anchor',
				defaults :
				{
					labelWidth : 89,
					anchor : '100%',
					layout :
					{
						type : 'hbox',
						defaultMargins :
						{
							top : 0,
							right : 5,
							bottom : 0,
							left : 0
						}
					}
				},
				items : [
				{
					xtype : 'fieldcontainer',
					defaults :
					{
						hideLabel : true
					},
					msgTarget : 'under',
					items : [
					{
						width : 110,
						xtype : 'displayfield',
						value : 'Default Facility: '
					}, Ext.create('App.ux.combo.Facilities',
					{
						width : 170
					}),
					{
						width : 100,
						xtype : 'displayfield',
						value : 'Authorizations: '
					}, Ext.create('App.ux.combo.Authorizations',
					{
						width : 175
					})]
				},
				{
					xtype : 'fieldcontainer',
					defaults :
					{
						hideLabel : true
					},
					items : [
					{
						width : 110,
						xtype : 'displayfield',
						value : 'Access Control: '
					}, Ext.create('App.ux.combo.Roles',
					{
						width : 170
					}),
					{
						width : 100,
						xtype : 'displayfield',
						value : 'Taxonomy: '
					},
					{
						width : 175,
						xtype : 'textfield',
						name : 'taxonomy'
					}]
				},
				{
					xtype : 'fieldcontainer',
					defaults :
					{
						hideLabel : true
					},
					items : [
					{
						width : 110,
						xtype : 'displayfield',
						value : 'Federal Tax ID: '
					},
					{
						width : 170,
						xtype : 'textfield',
						name : 'federaltaxid'
					},
					{
						width : 100,
						xtype : 'displayfield',
						value : 'Fed Drug ID: '
					},
					{
						width : 175,
						xtype : 'textfield',
						name : 'federaldrugid'
					}]
				},
				{
					xtype : 'fieldcontainer',
					defaults :
					{
						hideLabel : true
					},
					items : [
					{
						width : 110,
						xtype : 'displayfield',
						value : 'UPIN: '
					},
					{
						width : 170,
						xtype : 'textfield',
						name : 'upin'
					},
					{
						width : 100,
						xtype : 'displayfield',
						value : 'NPI: '
					},
					{
						width : 175,
						xtype : 'textfield',
						name : 'npi'
					}]
				},
				{
					xtype : 'fieldcontainer',
					defaults :
					{
						hideLabel : true
					},
					items : [
					{
						width : 110,
						xtype : 'displayfield',
						value : 'Job Description: '
					},
					{
						width : 455,
						xtype : 'textfield',
						name : 'specialty'
					}]
				}]
			}],
			dockedItems : [
			{
				xtype : 'toolbar',
				dock : 'top',
				items : [
				{
					text : i18n('save'),
					iconCls : 'save',
					handler : function()
					{
						//                        var record =  me.store.getAt('0');
						//                        var fieldValues = me.myAccountForm.getForm().getValues();
						//                        for ( var k=0; k <= record.fields.getCount()-1; k++) {
						//                            var i = record.fields.get(k).name;
						//                            record.set( i, fieldValues[i] );
						//                        }
						//                        me.store.sync();	// Save the record to the dataStore
						//                        me.store.load();	// Reload the dataSore from the database
						//
						//                        me.msg('Sweet!', 'Your Account have been updated.');
					}
				},
				{
					text : i18n('change_password'),
					iconCls : 'save',
					scope : me,
					handler : me['onPasswordChange']
				}]
			}],
			listeners :
			{
				afterrender :
				{
					fn : function()
					{
						//me.store.load();
					}
				}
			}
		});

		me.win = Ext.create('App.ux.window.Window',
		{
			width : 420,
			title : i18n('change_you_password'),
			items : [
			{
				xtype : 'form',
				bodyPadding : 15,
				defaultType : 'textfield',
				defaults :
				{
					labelWidth : 130,
					width : 380,
					inputType : 'password'
				},
				items : [
				{
					name : 'id',
					hidden : true
				},
				{
					fieldLabel : i18n('old_password'),
					name : 'oPassword',
					allowBlank : false
				},
				{
					fieldLabel : i18n('new_password'),
					name : 'nPassword',
					allowBlank : false,
					id : 'myAccountPage_nPassword'
				},
				{
					fieldLabel : i18n('re_type_password'),
					name : 'vPassword',
					allowBlank : false,
					vtype : 'password',
					initialPassField : 'myAccountPage_nPassword',
					validateOnChange : true
				}]
			}],
			buttons : [
			{
				text : i18n('save'),
				scope : me,
				handler : me.onPasswordSave
			},
			{
				text : i18n('cancel'),
				scope : me,
				handler : me.onCancel
			}],
			listeners :
			{
				scope : me,
				close : me.onClose
			}

		});
		me.pageBody = [me.myAccountForm];
		me.callParent(arguments);
	},

	onPasswordSave : function()
	{

	},

	onPasswordChange : function()
	{
		this.win.show();
	},

	onCancel : function()
	{
		this.win.close();
	},

	onClose : function()
	{
		this.win.down('form').getForm().reset();
	},

	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive : function(callback)
	{
		var me = this, form = me.myAccountForm.getForm();

		this.store.load(
		{
			scope : me,
			callback : function(record)
			{
				form.loadRecord(record[0]);
			}
		});
		callback(true);
	}
}); 
