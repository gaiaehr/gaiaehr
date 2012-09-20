Ext.define('Modules.ReportCenter.Main', {
    extend:'Modules.Module',
    constructor:function(){
        var me = this;

        me.addPanel(Ext.create('Modules.ReportCenter.view.reportCenter'));

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