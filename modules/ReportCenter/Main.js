Ext.define('Modules.ReportCenter.Main', {
    extend:'Modules.Module',
    constructor:function(){
        var me = this;

        me.addPanel(Ext.create('Modules.ReportCenter.view.reportCenter'));

        me.callParent();
    }
});