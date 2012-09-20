Ext.define('Modules.Module', {
    extend:'Ext.Component',
    constructor:function(){
        var me = this;

        say('Hi From Modules.Module');
        me.callParent();
    }
});