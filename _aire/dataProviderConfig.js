(function(){
    var Ext = window.Ext;

    function getServerData(){
        return window.App.server || {};
    }

    Ext.override(Ext.direct.RemotingProvider, {
        getCallData: function(transaction) {
            return {
                action: transaction.getAction(),
                method: transaction.getMethod(),
                data: transaction.getData(),
                type: 'rpc',
                tid: transaction.getId(),
                server: getServerData()
            };
        }
    });

    window.ExtDirectManagerProvider = Ext.direct.Manager.addProvider({
        id:'ServerProvider',
        url: 'http://www.gaiaehr.org/demo/data/appRouter.php',
        type: 'remoting',
        namespace : 'DataProvider',
        actions: {
            authProcedures: [
                {
                    "name": "login",
                    "len": 1
                }
            ],
            PoolArea: [
                {
                    "name": "getPatientsByPoolAreaAccess",
                    "len": 1
                }
            ]
       }
    });
    Ext.Direct.on('exception', function(event) {
        Ext.Viewport.unmask();
        say('Type: Exception, Message: '+event.config.message+', Where: '+event.config.where);
    });
})();