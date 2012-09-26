Ext.define('App.view.reports.Module', {
    extend:'Ext.Component',
    constructor:function(){
        var me = this;

        me.callParent();
    },

    /**
     * @param panel
     */
    addPanel:function(panel)
    {
        app.MainPanel.add(panel);
    },

    /**
     * @param item
     */
    addHeaderItem:function(item)
    {
        app.Header.add(item);
    },

    /**
     * @param parentId
     * @param node
     * 
     * Desc: Method to add items to the navigtion tree.
     * 
     */
    addNavigationNodes:function(parentId, node)
    {
        var parent;
        if(parentId == 'root' || parentId == null)
        {
            parent = app.storeTree.tree.getRootNode();
        }
        else
        {
            parent = app.storeTree.tree.getNodeById(parentId);
        }
        if(Ext.isArray(node)) 
        {
            for(var i=0; i < node.length; i++) parent.appendChild(node[i]);
        } 
        else 
        {
            parent.appendChild(node);
        }

    },

    addLanguages:function(languages){

    }

});