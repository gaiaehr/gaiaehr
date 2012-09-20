Ext.define('Modules.examplemodule2.Main', {
    extend:'Modules.Module',
    constructor:function(){
        var me = this;

        say('Hi From Modules.examplemodule2.Main');
        me.callParent();
    }
});