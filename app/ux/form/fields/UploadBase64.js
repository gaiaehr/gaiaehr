/*
 GaiaEHR (Electronic Health Records)
 Currency.js
 UX
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
Ext.define('App.ux.form.fields.UploadBase64', {
	extend: 'Ext.window.Window',
	requires: [
		'Ext.form.field.File'
	],

	xtype: 'uploadbase64field',
	bodyPadding: 10,
	base64: '',
	ready: false,

	title: i18n('upload'),
	items: [
		{
			xtype: 'fileuploadfield',
//			inputType:'file',
			width: 300
		}
	],
	buttons: [
		{
			text: i18n('cancel')
		},
		{
			text: i18n('upload')
		}
	],

	initComponent: function(){
		var me = this;

		me.callParent();

		me.uField = me.getComponent(0);
		me.uDock = me.getDockedItems('toolbar[dock="bottom"]')[0];
		me.uCancel = me.uDock.getComponent(0);
		me.uUpload = me.uDock.getComponent(1);

		me.uCancel.on('click', me.onCancel, me);
		me.uUpload.on('click', me.onUpload, me);

	},

	doUpload: function(){
		var me = this,
			fr = new FileReader();

		me.setReady(false);
		fr.onload = function(e){
			me.base64 = e.target.result;
			me.setReady(true);
			me.fireEvent('uploadready', me, me.base64);
			me.close();
		};
		fr.readAsDataURL(me.uField.extractFileInput().files[0]);
	},

	onCancel: function(){
		return this.close();
	},

	onUpload: function(){
		this.doUpload();
	},

	isReady: function(){
		return this.ready;
	},

	setReady: function(ready){
		return this.ready = ready;
	}
});