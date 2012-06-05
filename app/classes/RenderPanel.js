/**
 * Render panel
 *
 * @namespace FormLayoutEngine.getFields
 */
Ext.define('App.classes.RenderPanel', {
	extend       : 'Ext.container.Container',
	alias        : 'widget.renderpanel',
	cls          : 'RenderPanel',
	layout       : 'border',
	frame        : false,
	border       : false,
	pageLayout   : 'fit',
	pageBody     : [],
	pageTitle    : '',
	initComponent: function() {
		var me = this;
		Ext.apply(me, {
			items: [
				{
					cls   : 'RenderPanel-header',
					itemId: 'RenderPanel-header',
					xtype : 'container',
					region: 'north',
					layout: 'fit',
					height: 33,
					html  : '<div class="dashboard_title">' + me.pageTitle + '</div>'

				},
				{
					cls    : 'RenderPanel-body-container',
                    itemId : 'RenderPanel-body-container',
					xtype  : 'container',
					region : 'center',
					layout : 'fit',
					padding: 5,
					items  : [
						{
							cls     : 'RenderPanel-body',
							xtype   : 'panel',
							frame   : true,
							layout  : this.pageLayout,
							border  : false,
                            itemId  : 'pageLayout',
							defaults: {frame: false, border: false, autoScroll: true},
							items   : me.pageBody
						}
					]
				}
			]
		}, this);
		me.callParent(arguments);
	},

	updateTitle: function(pageTitle) {
		this.getComponent('RenderPanel-header').update('<div class="dashboard_title">' + pageTitle + '</div>');
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
			title  : 'Oops! No Patient Selected',
			msg    : 'Please select a patient using the <strong>"Patient Live Search"</strong> or <strong>"Patient Pool Area"</strong>',
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
		/**
		 * Ext.direct function
		 */
		FormLayoutEngine.getFields({formToRender: formToRender}, function(provider, response) {
			formPanel.add(eval(response.result));
			//formPanel.doLayout();

			if(typeof callback == 'function') {
				callback(true);
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

    warnRenderer:function(val){
        if(val == '1' || val == true || val == 'true') {
            return '<img src="ui_icons/icoImportant.png" />';
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

	getMitosApp: function() {
		return app.getMitosApp();
	},

	msg: function(title, format) {
		app.msg(title, format)
	},

    passwordVerificationWin:function(callback){
        var msg = Ext.Msg.prompt('Password Verification', 'Please enter your password:', function(btn, password) {
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
