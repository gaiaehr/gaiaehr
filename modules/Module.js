Ext.define('Modules.Module', {
    extend:'Ext.Component',
    constructor:function(){
        var me = this;

        me.callParent();
    },

    addPanel:function(panel){
        app.MainPanel.add(panel);
    },

    addHeaderItem:function(item){
        app.Header.add(item);
    },

    addNavigationNodes:function(parentId, node){
        var parent;
        if(parentId == 'root' || parentId == null){
            parent = app.storeTree.tree.getRootNode();
        }else{
            parent = app.storeTree.tree.getNodeById(parentId);
        }
        if(Ext.isArray(node)) {
            for(var i=0; i < node.length; i++) parent.appendChild(node[i]);
        } else {
            parent.appendChild(node);
        }

    }

});