/**
 * NewPatient.Js
 * Patient Layout Panel
 * v0.0.5
 *
 * This panel is generated dinamically, using the values from layout_options
 * Because this panel is dynamically generated, the user can edit or add more
 * fields to this form. To modify this panel you have to work with the
 * layoutEngine.class.php
 *
 * GaiaEHR (Eletronic Health Records) 2011
 *
 * Author   : GI Technologies, 2011
 * Modified : Ernesto J Rodriguez (Certun) 10/25/2011
 */
Ext.define('App.view.patient.NewPatient', {
	extend       : 'App.ux.RenderPanel',
	id           : 'panelNewPatient',
	pageTitle    : i18n('patient_entry_form'),
	uses         : [ 'App.ux.PhotoIdWindow' ],
	initComponent: function() {

		var me = this;

		me.formTitle = i18n('demographics');
		me.formToRender = 1;

		me.form = Ext.create('Ext.form.Panel', {
			title        : me.formTitle,
			bodyStyle    : 'padding: 5px',
			layout       : 'anchor',
			fieldDefaults: { msgTarget: 'side' },
			buttons: [
                {
                    text   : i18n('reset'),
                    handler: function(){
                        me.form.getForm().reset();
                    }
                },
                {
                    text   : i18n('save'),
                    iconCls: 'save',
                    scope  : me,
                    handler: me.onNewPatientSave
                }
            ]

		});
		me.pageBody = [ me.form ];

        me.listeners = {
            beforerender:me.beforePanelRender
        };

		me.callParent(arguments);
	},

	onNewPatientSave: function() {
		var me = this,
            form = me.form.getForm(),
            values = form.getFieldValues();

		if(form.isValid()) {
            values.date_created = Ext.Date.format(new Date(), 'Y-m-d H:i:s');
            Patient.createNewPatient(values, function(provider, response){
                if(response.result.success){
                    me.msg('Sweet!', i18n('patient') + ' "' + response.result.patient.fullname + '" ' + i18n('created') + '... ');
                    app.setPatient(response.result.patient.pid, null, function(success) {
                        if(success) {
                            form.reset();
                            app.openPatientSummary();
                        }
                    });
                }else{
                    me.msg('Oops!', i18n('something_went_wrong_saving_the_patient'), true);
                }
            });
		}
	},

	confirmationWin: function(callback) {
		Ext.Msg.show({
			title  : i18n('please_confirm') + '...',
			msg    : i18n('do_you_want_to_create_a_new_patient'),
			icon   : Ext.MessageBox.QUESTION,
			buttons: Ext.Msg.YESNO,
			scope  : this,
			fn     : function(btn) {
				callback(btn);
			}
		});
	},

    beforePanelRender:function(){
        var me = this;
        me.getFormItems(this.form, this.formToRender, function(formPanel, items){
            var primary = formPanel.getForm().findField('primary_subscriber_relationship');
            primary.on('select', me.copyData, me);
            var secondary = formPanel.getForm().findField('secondary_subscriber_relationship');
            secondary.on('select', me.copyData, me);
            var tertiary = formPanel.getForm().findField('tertiary_subscriber_relationship');
            tertiary.on('select', me.copyData, me);
        });
    },

    copyData:function(combo, records){
        var form = combo.up('form').getForm();
        if(combo.value == 'self'){
            var values = form.getValues(),
                patientData = {
                    primary_subscriber_title:values.title,
                    primary_subscriber_fname:values.fname,
                    primary_subscriber_mname:values.mname,
                    primary_subscriber_lname:values.lname,
                    primary_subscriber_street:values.address,
                    primary_subscriber_city:values.city,
                    primary_subscriber_state:values.state,
                    primary_subscriber_country:values.country,
                    primary_subscriber_zip_code:values.zipcode,
                    primary_subscriber_phone:values.home_phone,
                    primary_subscriber_employer:values.employer_name,
                    primary_subscriber_employer_street:values.employer_address,
                    primary_subscriber_employer_city:values.employer_city,
                    primary_subscriber_employer_state:values.employer_state,
                    primary_subscriber_employer_country:values.employer_country,
                    primary_subscriber_employer_zip_code:values.employer_postal_code
                };
            form.setValues(patientData);
        }
    },

	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback) {
        var me = this;
		this.confirmationWin(function(btn) {
			if(btn == 'yes') {
                //me.form.getForm().reset();
				app.unsetPatient();
				callback(true);
			} else {
				callback(false);
			}
		});
	}
});