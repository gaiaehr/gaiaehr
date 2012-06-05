/**
 * Created by JetBrains PhpStorm.
 * User: erodriguez
 * Date: 2/15/12
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.
 */
Ext.setup({
    icon: 'icon.png',
    tabletStartupScreen: 'tablet_startup.png',
    phoneStartupScreen: 'phone_startup.png',
    glossOnIcon: false,
    onReady: function() {
        var form;

        Ext.define('User', {
            extend: 'Ext.data.Model',
            fields: [
                { type: 'int', name: 'site_id', mapping: 'value'},
                { type: 'string', name: 'site', mapping: 'text'}
            ],
            idProperty: 'site_id'
        });
        var storeSites = Ext.create('Ext.data.Store',{
            model: 'User',
            proxy:{
                type: 'ajax',
                url: 'app/login/component_data.ejs.php?task=sites',
                reader: {
                    type: 'json',
                    idProperty: 'site_id',
                    totalProperty: 'results',
                    root: 'row'
                }
            },
            autoLoad: true
        }); // End storeLang

        ////////////////////////////////////////////////////////////////////////////////////////
        // This will be set in the global settings page... for now lets set the first site    //
        storeSites.on('load',function(ds,records,o){                                          //
            Ext.getCmp('choiseSite').setValue(records[0].data.site);                          //
        });                                                                                   //
        ////////////////////////////////////////////////////////////////////////////////////////

        var formBase  = {
            //scroll: 'vertical',
            url             : 'lib/authProcedures/auth.inc.php',
            baseParams		: { auth:'true' },
            standardSubmit  : false,
            items: [{
                xtype: 'fieldset',
                title: 'GaiaEHR Login Form',
                instructions: 'Please enter your login info.',
                defaults: {
                    required    : true,
                    labelAlign  : 'left',
                    labelWidth  : '40%'
                },
                items: [{
                    xtype           : 'textfield',
                    name            : 'authUser',
                    label           : 'Username',
                    clearIcon       : true,
                    autoCapitalize  : false
                },{
                    xtype           : 'passwordfield',
                    name            : 'authPass',
                    label           : 'Password',
                    clearIcon       : false
                },{
                    xtype: 'selectfield',
                    name: 'choiseSite',
                    label: 'Site',
                    typeAhead: true,
                    emptyText:'Select Site',
                    selectOnFocus:true,
                    id:'choiseSite',
                    //displayField:'site',
                    //valueField:'site_id',
                    //options: storeSites
                    options: [{text: 'default',  value: 'default'}]
                },
                    Ext.create('Ext.Button', {
                        text:'Login',
                        margin: '20 0',
                        ui  : 'confirm',
                        handler: function() {
                            if(formBase){
                                form.updateRecord(formBase, true);
                            }
                            form.submit({
                                waitMsg : {message:'Submitting', cls : 'demos-loading'}
                            });
                        }
                    }),
                    Ext.create('Ext.Button', {
                        text:'Reset',
                        margin: '20 0',
                        ui  : 'decline',
                        handler: function() {
                            form.reset();
                        }
                    })
                ]
            }],
            listeners : {
                submit : function(form, result){
                    if (result.errors){
                        Ext.Msg.alert('Opps!',result.errors.reason , Ext.emptyFn);
                    } else {
                        window.location = 'index.php'
                    }
                    console.log('success', Ext.toArray(arguments));
                }
            }
        };
        if (Ext.os.deviceType == 'Phone') {
            Ext.apply(formBase, {
                xtype: 'formpanel',
                autoRender: true
            },null);

            Ext.Viewport.add(formBase);
        } else {
            Ext.apply(formBase, {
                autoRender   : true,
                modal        : true,
                hideOnMaskTap: false,
                height       : 505,
                width        : 480,
                centered     : true,
                fullscreen   : true
            },null);

            form = Ext.create('Ext.form.Panel', formBase);
            form.show();
        }
    }
});