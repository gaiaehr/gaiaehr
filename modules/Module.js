Ext.define('Modules.Module', {
    extend:'Ext.Component',
    constructor:function(){
        var me = this;

        me.callParent();
    },

    addPanel:function(panel){
        app.MainPanel.add(panel);
    },

    addHeaderItem:function(item){
        app.Header.add(item);
    }

});