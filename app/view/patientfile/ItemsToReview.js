/**
 * Created with JetBrains PhpStorm.
 * User: Plushy
 * Date: 7/6/12
 * Time: 5:54 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.patientfile.ItemsToReview',{
    extend:'Ext.panel.Panel',
    layout:'column',
	eid: null,
    initComponent:function () {
        var me = this;

        me.column1 = Ext.create('Ext.container.Container',{
            columnWidth: 0.3333,
            defaults:{
                xtype:'grid',
                margin:'0 5 5 0'
            },
            items:[
                {
                    title:'Col 1 Item 1',
                    frame:true,
                    columns:[
                        {
                            header:'Date'
                        },
                        {
                            header:'Item',
                            flex:1
                        }
                    ]
                },
                {
                    title:'Col 1 Item 2',
                    frame:true,
                    columns:[
                        {
                            header:'Date'
                        },
                        {
                            header:'Item',
                            flex:1
                        }
                    ]
                }
            ]
        });

        me.column2 = Ext.create('Ext.container.Container',{
            columnWidth: 0.3333,
            defaults:{
                xtype:'grid',
                margin:'0 5 5 0'
            },
            items:[
                {
                    title:'Col 2 Item 1',
                    frame:true,
                    columns:[
                        {
                            header:'Date'
                        },
                        {
                            header:'Item',
                            flex:1
                        }
                    ]
                },
                {
                    title:'Col 2 Item 2',
                    frame:true,
                    columns:[
                        {
                            header:'Date'
                        },
                        {
                            header:'Item',
                            flex:1
                        }
                    ]
                }
            ]
        });

        me.column3 = Ext.create('Ext.container.Container',{
            columnWidth: 0.3333,
            defaults:{
                xtype:'grid',
                margin:'0 0 5 0'
            },
            items:[
                {
                    title:'Col 3Item 1',
                    frame:true,
                    columns:[
                        {
                            header:'Date'
                        },
                        {
                            header:'Item',
                            flex:1
                        }
                    ]
                },
                {
                    title:'Col 3Item 2',
                    frame:true,
                    columns:[
                        {
                            header:'Date'
                        },
                        {
                            header:'Item',
                            flex:1
                        }
                    ]
                }
            ]
        });

        me.items = [ me.column1, me.column2, me.column3 ];

        me.callParent(arguments);

    }
});