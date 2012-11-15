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
            patienLlist: {
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
            patienLlist: 'patientlist',
            homeBtn: 'button[action=home]',
            backBtn: 'button[action=back]'
        }
    },

    onPatientListShow:function(){
        this.getPatienLlist().getStore().load();
    },

    onPatientListSelect: function(view, model){
        this.getMainPanel().setActiveItem(1);
        Ext.Msg.alert('Patient', model.data.name, Ext.emptyFn);
    },

    onMainPanelActiveItemChange:function(card, newActiveItem){
        var hBtn = this.getHomeBtn(),
            bBtn = this.getBackBtn();
        say(newActiveItem.action);
        if(newActiveItem.action == 'home'){
//            hBtn.hide();
            bBtn.hide();
        }else if(newActiveItem.action == 'pSummary'){
//            hBtn.show();
            bBtn.show();
        }else{
//            hBtn.show();
            bBtn.show();
        }
    },

    onHomeBtnTap:function(){
        this.getMainPanel().setActiveItem(0);
    },

    onBackBtnTap:function(){
        this.getMainPanel().setActiveItem(0);
    }
});