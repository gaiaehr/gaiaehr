Ext.define('Modules.examplemodule.Main', {
    extend:'Modules.Module',
    constructor:function(){
        var me = this;

        say('Hi From Modules.examplemodule.Main');
        me.callParent();
    }
});