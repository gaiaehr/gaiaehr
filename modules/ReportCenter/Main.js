Ext.define('Modules.ReportCenter.Main', {
    extend:'Modules.Module',
    constructor:function(){
        var me = this;

        /**
         * @param panel     (Ext.component)     Component to add to MainPanel
         */
        me.addPanel(Ext.create('Modules.ReportCenter.view.reportCenter'));

        /**
         * funtion to add navigation links
         * @param parentId  (string)            navigation node parent ID,
         * @param node      (object || array)   navigation node configuration properties
         */
        me.addNavigationNodes('navigationReportCenter',{
            text:i18n['client_list_report'],
            leaf:true,
            cls:'file',
            iconCls:'icoReport',
            id: 'panelClientListReport'
        });

        me.callParent();
    }
});