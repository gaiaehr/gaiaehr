Ext.define('Modules.Module',
    {
        extend: 'Ext.Component',
        constructor: function () {
            var me = this;
            me.callParent();
        },

        /**
         * @param panel
         */
        addAppPanel: function (panel) {
            app.MainPanel.add(panel);
        },

        /**
         * @param item
         */
        addHeaderItem: function (item) {
            app.Header.add(item);
        },

        /**
         * @param parentId
         * @param node
         *
         * Desc: Method to add items to the navigation tree.
         *
         */
        addNavigationNodes: function (parentId, node) {
            var parent;
            if (parentId == 'root' || parentId == null) {
                parent = app.storeTree.tree.getRootNode();
            }
            else {
                parent = app.storeTree.tree.getNodeById(parentId);
            }

            var firstChildNode = parent.findChildBy(function (node) {
                return node.hasChildNodes();
            });

            if (Ext.isArray(node)) {
                for (var i = 0; i < node.length; i++)
                    parent.insertBefore(node[i], firstChildNode);
            }
            else {
                parent.insertBefore(node, firstChildNode);
            }

        },


        getModuleData:function(name){
            var me = this;
            Modules.getModuleByName(name, function(provider, response){
                me.fireEvent('moduledata', response.result)
            });
        },

        updateModuleData:function(data){
            var me = this;
            Modules.updateModule(data, function(provider, response){
                me.fireEvent('moduledataupdate', response.result)
            });
        },

        addLanguages: function (languages) {

        }
    });