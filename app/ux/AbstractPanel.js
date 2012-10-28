/*
 GaiaEHR (Electronic Health Records)
 AbstractPanel.js
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

Ext.define('App.ux.AbstractPanel', {

	setReadOnly:function(readOnly){
		var forms = this.query('form');

		for(var j = 0; j < forms.length; j++) {
			var form = forms[j], items;
			if(form.readOnly != readOnly){
				form.readOnly = readOnly;
				items = form.getForm().getFields().items;
				for(var k = 0; k < items.length; k++){
					items[k].setReadOnly(readOnly);
				}
			}
		}
		return readOnly;
	},

	setButtonsDisabled:function(buttons, disabled){
		var disable = disabled || app.patient.readOnly;
		for(var i = 0; i < buttons.length; i++) {
			var btn = buttons[i];
			if(btn.disabled != disable){
				btn.disabled = disable;
				btn.setDisabled(disable)
			}
		}
	},

	goBack: function() {
		app.goBack();
	},

	checkIfCurrPatient: function() {
		return app.getCurrPatient();
	},

	patientInfoAlert: function() {
		var patient = app.getCurrPatient();

		Ext.Msg.alert(i18n('status'), i18n('patient') + ': ' + patient.name + ' (' + patient.pid + ')');
	},

	currPatientError: function() {
		Ext.Msg.show({
			title  : 'Oops! ' + i18n('no_patient_selected'),
			msg    : i18n('select_patient_patient_live_search'),
			scope  : this,
			buttons: Ext.Msg.OK,
			icon   : Ext.Msg.ERROR,
			fn     : function() {
				this.goBack();
			}
		});
	},

    getFormItems: function(formPanel, formToRender, callback) {
        if(formPanel){
            formPanel.removeAll();
            FormLayoutEngine.getFields({formToRender: formToRender}, function(provider, response) {
                var items = eval(response.result), form;
                form = formPanel.add(items);
                if(typeof callback == 'function') {
                    callback(formPanel, items, true);
                }
                return form;
            });
        }
   	},

	boolRenderer: function(val) {
		if(val == '1' || val == true || val == 'true') {
			return '<img style="padding-left: 13px" src="resources/images/icons/yes.gif" />';
		} else if(val == '0' || val == false || val == 'false') {
			return '<img style="padding-left: 13px" src="resources/images/icons/no.gif" />';
		}
		return val;
	},

	alertRenderer: function(val) {
		if(val == '1' || val == true || val == 'true') {
			return '<img style="padding-left: 13px" src="resources/images/icons/no.gif" />';
		} else if(val == '0' || val == false || val == 'false') {
			return '<img style="padding-left: 13px" src="resources/images/icons/yes.gif" />';
		}
		return val;
	},

    warnRenderer:function(val, metaData, record){
	    say(record);
	    var toolTip = record.data.warningMsg ? record.data.warningMsg : '';

        if(val == '1' || val == true || val == 'true') {
            return '<img src="resources/images/icons/icoImportant.png" ' + toolTip + ' />';
        }else{
            return val;
        }
    },

	onExpandRemoveMask: function(cmb) {
		cmb.picker.loadMask.destroy()
	},

	strToLowerUnderscores: function(str) {
		return str.toLowerCase().replace(/ /gi, "_");
	},

	getCurrPatient: function() {
		return app.getCurrPatient();
	},

	getApp: function() {
		return app.getApp();
	},

	msg: function(title, format) {
		app.msg(title, format)
	},

	alert:function(msg, icon) {
		app.alert(msg,icon)
	},

    passwordVerificationWin:function(callback){
        var msg = Ext.Msg.prompt(i18n('password_verification'), i18n('please_enter_your_password') + ':', function(btn, password) {
            callback(btn, password);
        });
        var f = msg.textField.getInputId();
        document.getElementById(f).type = 'password';
        return msg;
    },
    getPageHeader:function(){
        return this.getComponent('RenderPanel-header');
    },
    getPageBodyContainer:function(){
        return this.getComponent('RenderPanel-body-container');
    },
    getPageBody:function(){
        return this.getPageBodyContainer().down('panel');
    }

});
