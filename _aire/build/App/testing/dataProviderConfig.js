(function(){
    //Ext.direct.Manager = Ext.create('Ext.direct.Manager');

    Ext.direct.Manager.addProvider({
        id:'ServerProvider',
        url: 'http://',
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
                    "len": 0
                }
            ]
        }
    })
})();
