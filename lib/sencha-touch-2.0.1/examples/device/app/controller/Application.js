Ext.define('Device.controller.Application', {
    extend: 'Ext.app.Controller',

    config: {
        refs: {
            main: 'main'
        },
        control: {
            tabbar: {
                activetabchange: 'onActiveTabChange'
            }
        }
    },

    onActiveTabChange: function(tabBar, newTab, oldTab) {
        var index = tabBar.indexOf(newTab);
        this.getMain().setActiveItem(index + 1);
    }
});
