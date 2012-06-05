<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>ExtJs Test Area</title>
    <link rel="stylesheet" type="text/css" href="ui_app/dashboard.css">
    <link rel="stylesheet" type="text/css" href="lib/extjs-4.1.0-rc1/resources/css/ext-all-gray.css">
    <script type="text/javascript" src="lib/extjs-4.1.0-rc1/ext-all-debug.js"></script>
</head>
<body>

<script src="data/api.php"></script>
<script type="text/javascript">
    Ext.Loader.setConfig({
        enabled:true,
        disableCaching:false,
        paths:{
            'Ext.ux':'app/classes/ux',
            'App':'app'
        }
    });
    Ext.direct.Manager.addProvider(App.data.REMOTING_API);
</script>

<script type="text/javascript">

Ext.define('Company', {
    extend:'Ext.data.Model',
    fields:[
        {name:'id'},
        {name:'company', type:'string'},
        {name:'price', type:'float'},
        {name:'change', type:'float'},
        {name:'pctChange', type:'float'},
        {name:'lastChange', type:'date', dateFormat:'n/j h:ia'}
    ],
    proxy:{
        type:'direct',
        api:{
            read:Test.getRec
        }
    }
});

Ext.define('Company2', {
    extend:'Ext.data.Model',
    fields:[
        {name:'id'},
        {name:'company', type:'string'},
        {name:'price', type:'float'},
        {name:'change', type:'float'},
        {name:'pctChange', type:'float'},
        {name:'lastChange', type:'date', dateFormat:'n/j h:ia'}
    ],
    proxy:{
        type:'direct',
        api:{
            read:Test.getRec2,
            create:Test.addRec
        }
    }
});


Ext.onReady(function () {
    Ext.QuickTips.init();

    function change(val) {
        if (val > 0) {
            return '<span style="color:green;">' + val + '</span>';
        } else if (val < 0) {
            return '<span style="color:red;">' + val + '</span>';
        }
        return val;
    }

    /**
     * Custom function used for column renderer
     * @param {Object} val
     */
    function pctChange(val) {
        if (val > 0) {
            return '<span style="color:green;">' + val + '%</span>';
        } else if (val < 0) {
            return '<span style="color:red;">' + val + '%</span>';
        }
        return val;
    }

    // create the data store
    var store = Ext.create('Ext.data.Store', {
        model:'Company',
        autoLoad:true
    });

    var store2 = Ext.create('Ext.data.Store', {
            model:'Company2',
            autoLoad:true,
            autoSync:true
        });

    // create the Grid
    Ext.create('Ext.grid.Panel', {
        store:store,
        collapsible:true,
        multiSelect:true,
        stateId:'stateGrid',
        columns:[
            {
                text:'Company',
                flex:1,
                sortable:false,
                dataIndex:'company'
            },
            {
                text:'Price',
                width:75,
                sortable:true,
                renderer:'usMoney',
                dataIndex:'price'
            },
            {
                text:'Change',
                width:75,
                sortable:true,
                renderer:change,
                dataIndex:'change'
            },
            {
                text:'% Change',
                width:75,
                sortable:true,
                renderer:pctChange,
                dataIndex:'pctChange'
            },
            {
                text:'Last Updated',
                width:85,
                sortable:true,
                renderer:Ext.util.Format.dateRenderer('m/d/Y'),
                dataIndex:'lastChange'
            },
            {
                menuDisabled:true,
                sortable:false,
                xtype:'actioncolumn',
                width:50,
                items:[
                    {
                        tooltip:'Sell stock',
                        handler:function (grid, rowIndex, colIndex) {
                            var rec = store.getAt(rowIndex);
                            alert("Sell " + rec.get('company'));
                        }
                    },
                    {
                        getClass:function (v, meta, rec) {          // Or return a class from a function
                            if (rec.get('change') < 0) {
                                this.items[1].tooltip = 'Hold stock';
                                return 'alert-col';
                            } else {
                                this.items[1].tooltip = 'Buy stock';
                                return 'buy-col';
                            }
                        },
                        handler:function (grid, rowIndex, colIndex) {
                            var rec = store.getAt(rowIndex);
                            alert((rec.get('change') < 0 ? "Hold " : "Buy ") + rec.get('company'));
                        }
                    }
                ]
            }
        ],
        height:350,
        width:600,
        title:'Array Grid',
        renderTo:Ext.getBody(),
        viewConfig:{
            stripeRows:true,
            enableTextSelection:true,
            copy:true,
            plugins:[
                {
                    ptype:'gridviewdragdrop',
                    dragGroup:'CPTGridDDGroup'
                }
            ]

        },
        listeners:{
            itemclick:function (view, record) {
                console.log(record.data.id);
            }
        },
        bbar:[
            {
                text:'loadRecord',
                handler:function () {
                    store.add({
                        company:'Boeing Co.',
                        price:75.43,
                        change:0.53,
                        pctChange:0.71,
                        lastChange:'9/1 12:00am'
                    });

                }
            }
        ]
    });

    Ext.create('Ext.grid.Panel', {
        store:store2,
        collapsible:true,
        multiSelect:true,
        stateId:'stateGrid',
        columns:[
            {
                text:'Company',
                flex:1,
                sortable:false,
                dataIndex:'company'
            },
            {
                text:'Price',
                width:75,
                sortable:true,
                renderer:'usMoney',
                dataIndex:'price'
            },
            {
                text:'Change',
                width:75,
                sortable:true,
                renderer:change,
                dataIndex:'change'
            },
            {
                text:'% Change',
                width:75,
                sortable:true,
                renderer:pctChange,
                dataIndex:'pctChange'
            },
            {
                text:'Last Updated',
                width:85,
                sortable:true,
                renderer:Ext.util.Format.dateRenderer('m/d/Y'),
                dataIndex:'lastChange'
            },
            {
                menuDisabled:true,
                sortable:false,
                xtype:'actioncolumn',
                width:50,
                items:[
                    {
                        tooltip:'Sell stock',
                        handler:function (grid, rowIndex, colIndex) {
                            var rec = store.getAt(rowIndex);
                            alert("Sell " + rec.get('company'));
                        }
                    },
                    {
                        getClass:function (v, meta, rec) {          // Or return a class from a function
                            if (rec.get('change') < 0) {
                                this.items[1].tooltip = 'Hold stock';
                                return 'alert-col';
                            } else {
                                this.items[1].tooltip = 'Buy stock';
                                return 'buy-col';
                            }
                        },
                        handler:function (grid, rowIndex, colIndex) {
                            var rec = store.getAt(rowIndex);
                            alert((rec.get('change') < 0 ? "Hold " : "Buy ") + rec.get('company'));
                        }
                    }
                ]
            }
        ],
        height:350,
        width:600,
        title:'Array Grid',
        renderTo:Ext.getBody(),
        viewConfig:{
            stripeRows:true,
            enableTextSelection:true,
            plugins:[
                {
                    ptype:'gridviewdragdrop',
                    dropGroup:'CPTGridDDGroup'
                }

            ]
        },
        plugins: [
            Ext.create('Ext.grid.plugin.RowEditing', {
                clicksToEdit: 1
            })
        ],
        listeners:{
            itemclick:function (view, record) {
                console.log(record.data.id);
            }
        },
        bbar:[
            {
                text:'loadRecord',
                handler:function () {
                    store.add({
                        company:'Boeing Co.',
                        price:75.43,
                        change:0.53,
                        pctChange:0.71,
                        lastChange:'9/1 12:00am'
                    });

                }
            }
        ]
    });

});
</script>
</body>
</html>