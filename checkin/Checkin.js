/**
 * Logon page
 *
 *
 * @namespace authProcedures.getSites
 * @namespace authProcedures.login
 */
Ext.define('App.panel.checkin.Checkin',{
    extend:'Ext.Viewport',
    initComponent:function(){
        var me = this;

        me.winLogon = Ext.create('widget.window', {
            title			: 'GaiaEHR Chech In',
            closeAction		: 'hide',
            width           : 325,
            height          : 274,
            plain			: true,
            modal			: false,
            resizable		: false,
            draggable		: false,
            closable		: false,
            bodyStyle		: 'background: #ffffff;',
            items			: [
                {
                    html:'<div class="container">' +
                        '<object id="iembedflash" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"' +
                        'codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="320" height="240"> ' +
                        '<param name="movie" value="camcanvas.swf"/>' +
                        '<param name="quality" value="high"/>' +
                        '<param name="allowScriptAccess" value="always"/>' +
                        '<embed src="checkin/camcanvas.swf" width="320" height="240" quality="high" allowScriptAccess="always" id="embedflash" ' +
                        'type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"' +
                        'mayscript="true"/>' +
                        '</object>' +
                        '</div>' +
                        '<canvas id="qr-canvas" style="display:none;" width="640" height="480"></canvas>'
                }
            ],
            listeners:{
                scope:me,
                afterrender:function(){
                    load();
                }
            }
        }).show();

        me.logo = Ext.create('Ext.container.Container', {
            html: '<img src="resources/images/gaiaehr_small_white.png" />',
            floating:true,
            shadow:false,
            renderTo: Ext.getBody()
        });

        me.listeners = {
            resize:me.onResized,
            afterrender:me.onAfterrender
        };

        me.callParent(arguments);
    },


    patientFound:function(p){

        var p = eval('('+'{"name":"Ernesto J Rodriguez","pid": 1,"ehr": "GaiaEHR"}'+')');

        Ext.Msg.alert('', '<span style="font-size: 40px; color: #ffffff">Welcome '+p.name+'<br></span>', function(){

            window.location = './';

        });
        this.winLogon.hide();
    },


    /**
     * After form is render load store
     */
    onAfterrender:function(){
        this.onResized();
    },

    onResized:function(){
        var win = this.winLogon,
            logo = this.logo,
            wh =  win.getHeight() - (win.getHeight() / 2),
            ww = win.getWidth() - (win.getWidth() / 2),
            lw = logo.getWidth() - (logo.getWidth() / 2);

        console.log(logo);
        win.alignTo(this, 'c', [-ww, -wh]);
        logo.alignTo(this, 't', [-lw, 10]);
    }
});