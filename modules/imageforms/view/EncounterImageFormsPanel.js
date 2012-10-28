Ext.define('Modules.imageforms.view.EncounterImageFormsPanel', {
    extend   : 'Ext.panel.Panel',
    title: i18n('image_forms'),
    layout:'auto',
    autoScroll:true,
    initComponent: function() {
        var me = this;

        me.tbar = [
            {
                text:'New Form',
                iconCls:'icoAdd',
                scope:me,
                handler:me.addNewForm
            }
        ];

        me.callParent(arguments);
    },

    addNewForm:function(btn){
        var me = this;
        me.add(Ext.create('Modules.imageforms.view.ImageForm'));
    }

});