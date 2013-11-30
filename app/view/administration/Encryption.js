/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.view.administration.Encryption', {
	extend:'App.ux.RenderPanel',
	pageTitle:i18n('encryption'),
	initComponent:function(){
		var me = this;

		me.container = Ext.widget('container',{
			bodyPadding:10,
			layout:'fit',
			items:[
				me.textbox = Ext.widget('textarea')
			]

		});

		me.pageTBar = [
			{
				text:i18n('encrypt'),
				enableToggle:true,
				toggleGroup:'encryption',
				scope:me,
				handler:me.onEncrypt
			},
			'-',
			{
				text:i18n('decrypt'),
				enableToggle:true,
				toggleGroup:'encryption',
				scope:me,
				handler:me.onDecrypt
			}
		];

		me.pageBody = [me.container];
		me.callParent(arguments);
	},

	onEncrypt:function(){
		var me = this,
			value = me.textbox.getValue();

		say(value);
		Encryption.Encrypt(value, function(provider, response){
			say(response);

			me.textbox.setValue(response.result);
		});
	},

	onDecrypt:function(){
		var me = this,
			value = me.textbox.getValue();

		say(value);
		Encryption.Decrypt(value, function(provider, response){
			say(response);

			me.textbox.setValue(response.result);
		});
	},

	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive:function(callback){
		callback(true);
	}
});
