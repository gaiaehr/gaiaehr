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

    loadPatient:function(){
        var panel = this.getPatientSummaryPanel(),
            header = this.getPatientSummaryHeader(),
            list = this.getPatientSummaryList();
        header.getTpl().overwrite(header.element, {name:'fulano', pid:App.pid});
        list.getStore().load();
    },

    onHeaderTap:function(){
        this.getApplication().getController('App.controller.Navigation').goToPanel('patientrecordpanel');
    },

    onHeaderInitialize:function(container){
        var me = this;
        container.element.on({
            tap:function(){
                me.onHeaderTap();
            }
        });
    }

});