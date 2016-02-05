/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 10/31/11
 * Time: 3:21 PM
 */
Ext.define('App.ux.window.Window', {
	extend       : 'Ext.window.Window',
	autoHeight   : true,
	modal        : true,
	border       : true,
	autoScroll   : true,
	resizable    : false,
	closeAction  : 'hide',
	initComponent: function() {
		this.callParent(arguments);
	},

	updateTitle: function(pageTitle, readOnly) {
		this.setTitle(pageTitle + (readOnly ? '[ Read Only ]' : ''));
	},

	setReadOnly: function() {
		var forms = this.query('form'),
			readOnly = app.patient.readOnly,
            j,
            form,
            k,
            items;
		for(j = 0; j < forms.length; j++) {
			form = forms[j], items;
			if(form.readOnly != readOnly){
				form.readOnly = readOnly;
				items = form.getForm().getFields().items;
				for(k = 0; k < items.length; k++) {
					items[k].setReadOnly(readOnly);
				}
			}
		}
		return readOnly;
	},

	setButtonsDisabled:function(buttons){
		var disable = app.patient.readOnly,
            i,
            btn;
		for(i = 0; i < buttons.length; i++) {
			btn = buttons[i];
			if(btn.disabled != disable){
				btn.disabled = disable;
				btn.setDisabled(disable)
			}
		}
	},

	checkIfCurrPatient: function() {
		return app.getCurrPatient();
	},

	patientInfoAlert: function() {
		var patient = app.getCurrPatient();

		Ext.Msg.alert(_('status'), _('patient') + ': ' + patient.name + ' (' + patient.pid + ')');
	},

	currPatientError: function() {
		Ext.Msg.show({
			title  : 'Oops! ' + _('no_patient_selected'),
			msg    : _('select_patient_patient_live_search'),
			scope  : this,
			buttons: Ext.Msg.OK,
			icon   : Ext.Msg.ERROR,
			fn     : function() {
				this.goBack();
			}
		});
	},

	getFormItems: function(formPanel, formToRender, callback) {
        var items;

		formPanel.removeAll();
		FormLayoutEngine.getFields({formToRender: formToRender}, function(provider, response) {
			items = eval(response.result);
			formPanel.add(items);
			if(typeof callback == 'function') {
				callback(formPanel, items, true);
			}
		});
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
        var toolTip = record.data.warningMsg ? ' data-qtip="'+record.data.warningMsg+'" ' : '';
        if(val == '1' || val == true || val == 'true') {
            return '<img src="resources/images/icons/icoImportant.png" ' + toolTip + ' />';
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

	passwordVerificationWin: function(callback) {
        var msg, f;
		msg = Ext.Msg.prompt(_('password_verification'), _('please_enter_your_password') + ':', function(btn, password) {
			callback(btn, password);
		});
		f = msg.textField.getInputId();
		document.getElementById(f).type = 'password';
		return msg;
	}
});
