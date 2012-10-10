Ext.define('Modules.imageforms.view.ImageForm', {
    extend   : 'Ext.form.Panel',
    width:460,
    height:460,
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
            {
                xtype:'combobox',
                emptyText:'Choose Image'
            },
            '-',
            {
                text:'Upload Image'
            }
        ];

        me.callParent(arguments);
    }
});