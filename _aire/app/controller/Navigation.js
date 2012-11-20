/**
 * Created with JetBrains PhpStorm.
 * User: ernesto
 * Date: 11/10/12
 * Time: 11:37 AM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.controller.Navigation', {
    extend: 'Ext.app.Controller',

    requires:['Ext.data.proxy.JsonP'],

    config: {
        control: {
            patientList: {
                initialize : 'onPatientListInit',
                select: 'onPatientListSelect',
                show: 'onPatientListShow'
            },
            mainPanel: {
                activeitemchange: 'onMainPanelActiveItemChange'
            },
            homeBtn: {
                tap: 'onHomeBtnTap'
            },
            backBtn: {
                tap: 'onBackBtnTap'
            }
        },

        refs: {
            mainTabletView: 'maintabletview',
            mainPhoneView: 'mainphoneview',
            mainPanel: 'container[action=mainPanel]',
            mainNavBar: 'titlebar[action=mainNavBar]',
            patientList: 'patientlist',
            patientSummaryPanel: 'patientSummaryPanel',
            homeBtn: 'button[action=home]',
            backBtn: 'button[action=back]'
        }
    },

    onPatientListInit:function(){
        var store = this.getPatientList().getStore();
        store.getProxy()._extraParams.uid = App.user.id;
        store.load();
    },

    onPatientListShow:function(){
        this.getPatientList().getStore().load();
    },

    onPatientListSelect: function(view, model){
        this.getMainPanel().setActiveItem(1);
        this.getMainNavBar().setTitle(model.data.name);
        this.getApplication().getController('App.controller.PatientSummary').loadPatient(model.data.pid);

    },

    onMainPanelActiveItemChange:function(card, newActiveItem){
        var bBtn = this.getBackBtn();
        var hBtn = this.getHomeBtn();
        if(newActiveItem.action == 'home'){
            bBtn.hide();
            hBtn.hide();
        }else if(newActiveItem.action == 'pSummary'){
            bBtn.hide();
            hBtn.show();
        }else{
            bBtn.show();
        }
    },

    onHomeBtnTap:function(){
        this.getPatientList().deselectAll();
        this.getMainNavBar().setTitle('Home');
        this.getMainPanel().setActiveItem(0);
    },

    onBackBtnTap:function(){
        var panel = this.getMainPanel(),
            prev = panel.items.items.indexOf(panel.getActiveItem()) - 1;
        panel.setActiveItem(prev);
    }
});