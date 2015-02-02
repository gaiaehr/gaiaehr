/**
 * Created with JetBrains PhpStorm.
 * User: ernesto
 * Date: 11/10/12
 * Time: 11:37 AM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.controller.Login', {
    extend: 'Ext.app.Controller',
    config: {
        control: {
            settingsForm: {
                initialize: 'setServerData'
            },
            loginButton: {
                tap: 'doLogin'
            },
            logoutButton: {
                tap: 'doLogout'
            }
        },
        refs: {
            mainPhoneView: 'mainphoneview',
            mainTabletView: 'maintabletview',
            settingsForm: 'formpanel[action=settings]',
            loginForm: 'formpanel[action=login]',
            loginWindow: 'loginWindow',
            loginButton: 'button[action=login]',
            logoutButton: 'button[action=logout]',
            pvtKeyField: 'textfield[action=pvtKey]'
        },
        db:null
    },
    doLogin: function(){
        var me = this,
            values = me.getLoginForm().getValues(),
            server = me.getSettingsForm().getValues();
        values.site = server.site;
        App.server = server;
        Ext.Viewport.mask({xtype: 'loadmask', message: 'Be Right Back!'});

        window.ExtDirectManagerProvider.setUrl(server.url+'data/appRouter.php');
        if(App.isNative) me.saveServerData(App.server);
        DataProvider.authProcedures.login(values, function(response){
            Ext.Viewport.unmask();
            if(response.success){
                App.user = response.user;
                App.server.token = response.token;
                me.getLoginWindow().destroy();
                if(App.isPhone){
                    Ext.Viewport.add(Ext.create('App.view.MainPhone'));
                }else{
                    Ext.Viewport.add(Ext.create('App.view.MainTabletView'));
                }
            }else{

                App.MsgOk('Oops!', Ext.String.capitalize(response.type) + ': ' + response.message, Ext.emptyFn);
            }
        });
    },

    doLogout:function(){
        var me = this;
        App.MsgOkCancel('Logout...', 'Are you sure?', function(btn){
            if(btn == 'yes'){
                if(App.isPhone){
                    Ext.Viewport.remove(me.getMainPhoneView());
                }else{
                    Ext.Viewport.remove(me.getMainTabletView());
                }
                Ext.Viewport.add(Ext.create('App.view.Login',{
                    border: !App.isPhone ? 5 : 0,
                    style: !App.isPhone ? 'border-color: black; border-style: solid; border-radius: 5px' : '',
                    modal: !App.isPhone,
                    centered: !App.isPhone,
                    width: App.isPhone ? '100%' : 520,
                    height: App.isPhone ? '100%' : 440
                }));
            }
        });
    },

    setSettingsValues: function(tx, results){
    	var me = this;
    	say('***** Total rows: '+results.rows.length);
    	if(results.rows.length == 0){
    		tx.executeSql('INSERT INTO GaiaEHRAirServerData (id, url, site, pvtKey) VALUES (1, "", "", "")');
			me.dbError('****** No data found ******');
			App.server.site = 'default';
		    App.server.url = 'http://www.gaiaehr.org/demo/';
		    App.server.pvtKey = '8BAR-NYRB-8R9E-RFYW-EGOV';
		}else{
			App.server.site = results.rows.item(0).site || ' ';
		    App.server.url = results.rows.item(0).url || ' ';
		    App.server.pvtKey = results.rows.item(0).pvtKey || ' ';
		}

    	me.getSettingsForm().setValues(App.server);
    },

    setServerData:function(){
        var me = this;
        if(App.db){
        	App.server = {};
            me.createDataBaseTable();   // create the database table if doesn't exist
            me.getServerData(); 		// get server data
        }else{
            App.server = Ext.Object.fromQueryString(window.location.search);
            App.server.url = window.location.href.replace('_aire/'+window.location.search, '');
            App.server.router = App.server.url+'data/restRouter.php';
            App.server.pvtKey = '8BAR-NYRB-8R9E-RFYW-EGOV';
            me.getSettingsForm().setValues(App.server);
        }
    },

    createDataBaseTable:function(){
        var me = this;
        App.db.transaction(
            function(tx){
                tx.executeSql('CREATE TABLE IF NOT EXISTS GaiaEHRAirServerData (id unique, url, site, pvtKey)');
            },
            function(tx, err){  // failure
                me.dbError(err);
                return false;
            },
            function(){         // success
                return true;
            }
        );
    },

    saveServerData:function(data){
    	var me = this;
    	data.url = (data.url == null || data.url == '') ? ' ' : data.url;
    	data.site = (data.site == null || data.site == '') ? ' ' : data.site;
    	data.pvtKey = (data.pvtKey == null || data.pvtKey == '') ? ' ' : data.pvtKey;
        App.db.transaction(
            function(tx){
                tx.executeSql('UPDATE GaiaEHRAirServerData SET url = "'+data.url+'", site = "'+data.site+'", pvtKey = "'+data.pvtKey+'" WHERE id = 1');
            },
            function(tx, err){  // failure
                me.dbError(err);
                return false;
            },
            function(){         // success
                say('****** Success Data Saved ******');
                return true;
            }
        );
    },

    getServerData:function(){
        var me = this;
        App.db.transaction(
            function(tx){
                tx.executeSql('SELECT * FROM GaiaEHRAirServerData', [],
                	function(tx, results){
                		me.setSettingsValues(tx, results);
                	},
                    function(err){          // failure
                        me.dbError(err);
                        return false;
                    }
                );
            },
            function(tx, err){              // failure
                me.dbError(err);
                return false;
            }
        );
    },

    dbError:function(err){
        say('***** Error processing SQL: ' + err + ' ******');
    }
});