/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 3/14/12
 * Time: 9:07 PM
 */
Ext.define('App.ux.grid.EventHistory',{
    extend: 'Ext.grid.Panel',
    alias : 'widget.mitos.eventhistorygrid',
    initComponent:function(){
        Ext.apply(this,{
            columns: [
                { header: i18n('date'),  dataIndex: 'date', width: 140, renderer: Ext.util.Format.dateRenderer('Y-m-d g:i:s a') },
                { header: i18n('user'),  dataIndex: 'user', width: 150 },
                { header: i18n('event'), dataIndex: 'event', flex: 1 }
            ]
        },null);

        this.callParent(arguments);
    }
});