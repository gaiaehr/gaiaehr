Ext.define('Modules.imageforms.view.ImageForm', {
    extend   : 'Ext.form.Panel',
    width:462,
    height:487,
    style:'float:left',
    margin:'0 10 10 0',
    initComponent: function() {
        var me = this;



        me.tbar = [
            {
                text:'Add Note',
                iconCls:'icoAdd'
            },
            '->',
            Ext.create('Modules.imageforms.view.FormBackgroundImagesCombo',{
                listeners:{
                    scope:me,
                    change:me.onFormSelected
                }
            }),
            '-',
            {
                text:'Upload Image'
            },
            '-',
            {
                xtype:'tool',
                type:'close',
                scope:me,
                handler:me.removeForm
            }
        ];

        me.callParent(arguments);
    },

    onFormSelected:function(btn, newValue){
        var me = this;
        if(me.img){
            me.img.setSrc(newValue);
        }else{
            me.img = me.add(Ext.create('Ext.Img',{src: newValue}));
        }


    },

    removeForm:function(){
        this.destroy();
    }
});