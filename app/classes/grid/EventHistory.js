/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 3/14/12
 * Time: 9:07 PM
 */
Ext.define('App.classes.grid.EventHistory',{
    extend: 'Ext.grid.Panel',
    alias : 'widget.mitos.eventhistorygrid',
    initComponent:function(){
        Ext.apply(this,{
            columns: [
                { header: 'Date',  dataIndex: 'date', width: 140, renderer: Ext.util.Format.dateRenderer('Y-m-d g:i:s a') },
                { header: 'User',  dataIndex: 'user', width: 150 },
                { header: 'Event', dataIndex: 'event', flex: 1 }
            ]
        },null);

        this.callParent(arguments);
    }
});