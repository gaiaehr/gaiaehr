/**
 * Created with JetBrains PhpStorm.
 * User: ernesto
 * Date: 11/10/12
 * Time: 11:37 AM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.controller.PatientSummary', {
    extend: 'Ext.app.Controller',
    config: {
        control: {
            patientSummaryHeader: {
                initialize:'onHeaderInitialize'
            }
        },

        refs: {
            patientSummaryPanel: 'patientsummarypanel',
            patientSummaryHeader: 'container[action=patientSummaryHeader]',
            patientSummaryList: 'patientsummarypanel > list'

        }
    },

    loadPatient:function(pid){
        var panel = this.getPatientSummaryPanel(),
            header = this.getPatientSummaryHeader(),
            list = this.getPatientSummaryList();
        panel.pid = pid;
        header.getTpl().overwrite(header.element, {name:'fulano', pid:pid});
        list.getStore().load();
    },

    onHeaderTap:function(){
        App.MsgOk('TODO', 'Go To Encounter Area')
    },

    onHeaderInitialize:function(container){
        var me = this,
            panel = me.getPatientSummaryPanel();
        container.element.on({
            tap: function(e, node) {
                me.getApplication().getController('App.controller.Navigation').setMedicalRecordMenu();
                //App.MsgOk('TODO', 'Go To Encounter PID: ' + panel.pid);
            }
        });
    }




});