/**
 * roles.ejs.php
 * Description: Facilities Screen
 * v0.0.3
 *
 * Author: Ernesto J Rodriguez
 * Modified: n/a
 *
 * GaiaEHR (Eletronic Health Records) 2011
 *
 * @namespace Roles.getRoleForm
 * @namespace Roles.saveRolesData
 * @namespace Roles.getRolesData
 */
Ext.define('App.view.administration.Roles', {
	extend       : 'App.classes.RenderPanel',
	id           : 'panelRoles',
	pageTitle    : i18n['roles_and_permissions'],
	pageLayout   : {
		type:'vbox',
		align:'stretch'
	},
	initComponent: function() {

		var me = this;

		//******************************************************************************
		// Roles Store
		//******************************************************************************

		me.header = Ext.create('Ext.container.Container',{
			height:30,
			html : '<div class="roleHeader">' +
					'<span class="perm">' + i18n['permission'] + '</span>' +
					'<span class="role">' + i18n['front_office'] + '</span>' +
					'<span class="role">' + i18n['auditors'] + '</span>' +
					'<span class="role">' + i18n['clinician'] + '</span>' +
					'<span class="role">' + i18n['physician']+ '</span>' +
					'<span class="role">' + i18n['administrator'] + '</span>' +
					'</div>'
		});

		me.form = Ext.create('Ext.form.Panel', {
			flex:1,
			frame:true,
			bodyStyle:'background-color:white',
			bodyPadding: 10,
			items      : [
				{
					xtype      : 'fieldcontainer',
					defaultType: 'mitos.checkbox',
					layout     : 'hbox'
				}
			],
			buttons       : [
				{
					text   : i18n['save'],
					iconCls: 'save',
					margin : '0 20 0 0',
					scope  : me,
					handler: me.onSave
				}
			]
		});

		me.pageBody = [ me.header, me.form ];
		me.callParent(arguments);
	},

	onSave: function() {
		var me = this,
			form = me.form.getForm(),
			values = form.getValues(),
			record = form.getRecord(),
			changedValues;

		if(record.set(values) !== null){
			me.form.el.mask( i18n['saving_roles'] + '...');
			changedValues = record.getChanges();
			Roles.saveRolesData(changedValues, function(provider, response){
				if(response.result){
					me.form.el.unmask();
					me.msg('Sweet!', i18n['roles_updated']);
					record.commit();
				}
			});
		}
	},


	getFormData: function() {

		var me = this,
			form = me.form,
			formFields = form.getForm().getFields(),
			modelFields = [],
			model;

		for(var i=0; i < formFields.items.length; i++){
			modelFields.push({name: formFields.items[i].name, type: 'bool'});
		}

		model = Ext.define(form.itemId + 'Model', {
			extend: 'Ext.data.Model',
			fields: modelFields,
			proxy : {
				type: 'direct',
				api : {
					read: Roles.getRolesData
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model: model
		});

		me.store.load({
			scope   : this,
			callback: function(records, operation, success) {
				if(success) {
					form.getForm().loadRecord(records[0]);
					form.el.unmask();
				}
			}
		});
	},

	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback) {
		var me = this,
			form = me.form;
		form.el.mask( i18n['loading'] + '...');
		form.removeAll();
		Roles.getRoleForm(null, function(provider, response) {
			form.add(eval(response.result));
			me.getFormData();
		});

		callback(true);
	}
});