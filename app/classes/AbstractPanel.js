/**
 * Render panel
 *
 * @namespace FormLayoutEngine.getFields
 */
Ext.define('App.classes.AbstractPanel', {

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
		var disable = disabled || app.currPatient.readOnly;
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

		Ext.Msg.alert('Status', 'Patient: ' + patient.name + ' (' + patient.pid + ')');
	},

	currPatientError: function() {
		Ext.Msg.show({
			title  : 'Oops! ' + i18n['no_patient_selected'],
			msg    : i18n['select_patient_patient_live_search'],
			scope  : this,
			buttons: Ext.Msg.OK,
			icon   : Ext.Msg.ERROR,
			fn     : function() {
				this.goBack();
			}
		});
	},

	getFormItems: function(formPanel, formToRender, callback) {
		formPanel.removeAll();

		FormLayoutEngine.getFields({formToRender: formToRender}, function(provider, response) {
			var items = eval(response.result);
            formPanel.add(items);
			if(typeof callback == 'function') {
				callback(formPanel, items, true);
			}
		});
	},

	boolRenderer: function(val) {
		if(val == '1' || val == true || val == 'true') {
			return '<img style="padding-left: 13px" src="ui_icons/yes.gif" />';
		} else if(val == '0' || val == false || val == 'false') {
			return '<img style="padding-left: 13px" src="ui_icons/no.gif" />';
		}
		return val;
	},

	alertRenderer: function(val) {
		if(val == '1' || val == true || val == 'true') {
			return '<img style="padding-left: 13px" src="ui_icons/no.gif" />';
		} else if(val == '0' || val == false || val == 'false') {
			return '<img style="padding-left: 13px" src="ui_icons/yes.gif" />';
		}
		return val;
	},

    warnRenderer:function(val, metaData, record){
	    say(record);
	    var toolTip = record.data.warningMsg ? record.data.warningMsg : '';

        if(val == '1' || val == true || val == 'true') {
            return '<img src="ui_icons/icoImportant.png" ' + toolTip + ' />';
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
        var msg = Ext.Msg.prompt(i18n['password_verification'], i18n['please_enter_your_password'] + ':', function(btn, password) {
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
