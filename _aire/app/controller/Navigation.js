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
            navPanel:{
                collapsechange:'onNavPanelCollapseChange'
            },
            navPanelCollapseBtn:{
                tap:'onNavPanelCollapseBtnTap'
            },
            patientListNav: {
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

            navPanel: 'navpanel',
            navPanelCollapseBtn: 'button[action=leftNavCollapseBtn]',
            navPanelTitleBar: 'titlebar[action=leftNavTitleBar]',
            patientListNav: 'patientlistnav',
            medicalRecordMenu: 'medicalrecordmenu',

            mainPanel: 'container[action=mainPanel]',
            mainPanelTitleBar: 'titlebar[action=mainTitleBar]',
            homeBtn: 'button[action=home]',
            backBtn: 'button[action=back]',

            patientSummaryPanel: 'patientSummaryPanel'

        }
    },

    onPatientListInit:function(){
        var store = this.getPatientListNav().getStore();
        store.getProxy()._extraParams.uid = App.user.id;
    },

    onPatientListShow:function(){
        this.getPatientListNav().getStore().load();
    },



    onMainPanelActiveItemChange:function(card, newActiveItem){
        if(newActiveItem){
            var nav = newActiveItem.config.nav,
                tier = newActiveItem.config.tier;

            if(nav){
                this.setNavMenu(nav);
            }else{
                say('No nav property found in panel config');
            }
            if(tier){
               this.setBackBtn(tier);
            }else{
                say('No tier property found in panel config');
            }
        }

    },

    setBackBtn:function(tier){
        tier == 1 ? this.getBackBtn().hide() : this.getBackBtn().show();
    },

    onHomeBtnTap:function(){
        this.getPatientListNav().deselectAll();
        this.getMainPanel().setActiveItem(0);
        this.getMainPanelTitleBar().setTitle('Home');
    },
    onBackBtnTap:function(){
        var panel = this.getMainPanel(),
            tier = panel.getActiveItem().config.tier;

        if(tier == 2){
            this.getPatientListNav().deselectAll();
            panel.setActiveItem(0);
        }else if(tier == 3){
            panel.setActiveItem(1);
        }else if(tier == 4){
            this.getMedicalRecordMenu().deselectAll();
            panel.setActiveItem(2);
        }
    },

    goToPanel:function(xtype){
        var mainPanel = this.getMainPanel(),
            newPanel = mainPanel.query(xtype)[0];
        mainPanel.setActiveItem(newPanel);
    },

    /**
     * Nav Lists Panels selects
     */
    onPatientListSelect: function(view, model){
        App.pid = model.data.pid;
        this.getMainPanel().setActiveItem(1);
        this.getMainPanelTitleBar().setTitle(model.data.name);
        this.getApplication().getController('App.controller.PatientSummary').loadPatient();
    },
    onMedicalRecordMenuSelect:function(view, model){
        this.goToPanel(model.data.action);
    },

    /**
     * Nav Collapse functions!!!
     */
    onMainPanelTitleBarInitialize:function(comp){
        comp.element.on('swipe', function(event){
            this.setNavCollapse(event.direction == 'left')
        }, this);
    },
    setNavCollapse: function(collapse) {
        var nav = this.getNavPanel(),
            title = this.getNavPanelTitleBar();
        this.getNavPanelTitleBar().setTitle(collapse ? '' : 'Patients');
        nav.collapsed = collapse;
        collapse ? nav.addCls('collapsed') : nav.removeCls('collapsed');
        nav.setWidth(collapse ? 85 : 350);
        nav.fireEvent('collapsechange', collapse, nav, title);
    },
    onNavPanelCollapseBtnTap:function(){
        var collapsed = this.getNavPanel().collapsed;
        this.setNavCollapse(!collapsed);
    },
    onNavPanelCollapseChange:function(collapse){
        this.getNavPanelCollapseBtn().setWidth(85);
    },
    setNavMenu:function(menu){
        var navPanel = this.getNavPanel(),
          newMenu = navPanel.query(menu)[0];
        navPanel.setActiveItem(newMenu);
        this.setNavCollapse(menu != 'patientlistnav');
    }

});