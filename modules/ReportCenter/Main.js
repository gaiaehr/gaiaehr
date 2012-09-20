Ext.define('Modules.ReportCenter.Main', {
    extend:'Modules.Module',
    constructor:function(){
        var me = this;

        say(app.MainPanel.add(Ext.create('Modules.ReportCenter.view.reportCenter')));

        me.callParent();
    }
});