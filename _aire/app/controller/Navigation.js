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
            leftNav:{
                collapsechange:'onLeftNavCollapseChange'
            },
            leftNavCollapseBtn:{
                tap:'onLeftNavCollapseBtnTap'
            },
            patientList: {
                initialize : 'onPatientListInit',
                select: 'onPatientListSelect',
                show: 'onPatientListShow'
            },
            medicalRecordMenu: {
                select: 'onMedicalRecordMenuSelect'
            },
            mainPanel: {
                activeitemchange: 'onMainPanelActiveItemChange'
            },
            mainPanelTitleBar:{
                initialize:'onMainPanelTitleBarInitialize'
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

            leftNav: 'leftNav',
            leftNavCollapseBtn: 'button[action=leftNavCollapseBtn]',
            leftNavTitleBar: 'titlebar[action=leftNavTitleBar]',
            patientList: 'patientList',
            medicalRecordMenu: 'medicalRecordMenu',

            mainPanel: 'container[action=mainPanel]',
            mainPanelTitleBar: 'titlebar[action=mainTitleBar]',
            homeBtn: 'button[action=home]',
            backBtn: 'button[action=back]',

            patientSummaryPanel: 'patientSummaryPanel'

        }
    },

    onPatientListInit:function(){
        var store = this.getPatientList().getStore();
        store.getProxy()._extraParams.uid = App.user.id;
//        say(store);
//        store.load();
    },

    onPatientListShow:function(){
        this.getPatientList().getStore().load();
    },

    onPatientListSelect: function(view, model){
        this.getMainPanel().setActiveItem(1);
        this.getMainPanelTitleBar().setTitle(model.data.name);
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
        this.getMainPanel().setActiveItem(0);
        this.getMainPanelTitleBar().setTitle('Home');

    },

    onBackBtnTap:function(){
        var panel = this.getMainPanel(),
            prev = panel.items.items.indexOf(panel.getActiveItem()) - 1;
        panel.setActiveItem(prev);
    },

    onMedicalRecordMenuSelect:function(view, model){
        say(model.data.action + ' Clicked!');
    },

    setPatientList:function(){
        this.getLeftNav().setActiveItem(0)
    },

    setMedicalRecordMenu:function(){
        this.setNavCollapse(true);
        this.getLeftNav().setActiveItem(1);
    },

    setNavCollapse: function(collapse) {
        var nav = this.getLeftNav(),
            title = this.getLeftNavTitleBar();
        this.getLeftNavTitleBar().setTitle(collapse ? '' : 'Patients');
        nav.collapsed = collapse;
        nav.setWidth(collapse ? 85 : 350);
        nav.fireEvent('collapsechange', collapse, nav, title);
    },

    onMainPanelTitleBarInitialize:function(comp){
        comp.element.on('swipe', function(event){
            this.setNavCollapse(event.direction == 'left')
        }, this);
    },

    onLeftNavCollapseBtnTap:function(){
        var collapsed = this.getLeftNav().collapsed;
        this.setNavCollapse(!collapsed);
    },

    onLeftNavCollapseChange:function(collapse){
        this.getLeftNavCollapseBtn().setWidth(85);
    }

});