/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 10:46 PM
 */
Ext.define('App.view.patient.windows.NewEncounter', {
    extend:'Ext.window.Window',
    title:i18n('new_encounter_form'),
    closeAction:'hide',
    closable:false,
    modal:true,
    width:660,

    initComponent:function(){
        var me = this;

        me.store = Ext.create('App.store.patient.Encounter');
        Ext.apply(me,{
            items:[
                me.encForm = Ext.create('Ext.form.Panel',{
                    border:false,
                    bodyPadding:'10 10 0 10'
                })
            ],
            buttons:[
                {
                    text:i18n('create_encounter'),
                    action:'encounter',
                    scope:me,
                    handler:me.createNewEncounter
                },
                {
                    text:i18n('cancel'),
                    scope:me,
                    handler:me.cancelNewEnc
                }
            ],
            listeners:{
                show:me.checkValidation
            }
        }, me);

        me.getFormItems(this.encForm, 5);
        me.callParent(arguments);
    },

    checkValidation:function(){
        if(app.patient.pid){
            if(acl['add_encounters']){
                var me = this,
                    form = me.down('form').getForm();
                me.setForm(form);
                Encounter.checkOpenEncounters(function(provider, response){
                    if(response.result.encounter){
                        Ext.Msg.show({
                            title:'Oops! ' + i18n('open_encounters_found') + '...',
                            msg:i18n('do_you_want_to') + ' <strong>' + i18n('continue_creating_the_new_encounters') + '</strong><br>"' + i18n('click_no_to_review_encounter_history') + '"',
                            buttons:Ext.Msg.YESNO,
                            icon:Ext.Msg.QUESTION,
                            fn:function(btn){
                                if(btn != 'yes'){
                                    me.hide();
                                    form.reset();
                                }
                            }
                        });
                    }
                });
            }else{
                app.accessDenied();
            }
        }else{
            app.currPatientError();
        }
    },

    setForm:function(form){
        var model;
        form.reset();
        model = Ext.ModelManager.getModel('App.model.patient.Encounter');
        model = Ext.ModelManager.create({
            pid:app.patient.pid,
            service_date:new Date()
        }, model);
        return form.loadRecord(model);
    },

    createNewEncounter:function(btn){
        var me = this,
            form = me.encForm.getForm(),
            values = form.getValues();

        if(form.isValid()){
            if(acl['add_encounters']){
                values.pid = app.patient.pid;
                me.store.add(values);
                me.store.sync({
                    callback:function(batch, options){
                        if(options.operations.create){
                            var data = options.operations.create[0].data;
                            app.patientButtonRemoveCls();
                            app.patientBtn.addCls(data.priority);
                            app.openEncounter(data.eid);
                            me.close();
                        }
                    }
                });
            }else{
                btn.up('window').close();
                app.accessDenied();
            }
        }
    },


    cancelNewEnc:function(){
        this.close();
        app.openPatientSummary();
    }

});
