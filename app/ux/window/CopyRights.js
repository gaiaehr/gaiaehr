Ext.define('App.ux.window.CopyRights', {
    extend:'Ext.window.Window',
    id         : 'winCopyright',
    title      : 'GaiaEHR ' + i18n('copyright_notice'),
    bodyStyle  : 'background-color: #ffffff; padding: 5px;',
    autoLoad   : 'gpl-licence-en.html',
    closeAction: 'hide',
    width      : 900,
    height     : 500,
    y          : 90,
    modal      : false,
    draggable  : true,
    resizable  : true,
    autoScroll : true,
    dockedItems: [
        {
            dock   : 'bottom',
            frame  : false,
            border : false,
            buttons: [
                {
                    text   : 'Close',
                    margin : '0 10 0 5',
                    name   : 'btn_reset',
                    handler: function(btn) {
                        btn.up('window').close();
                    }
                }
            ]
        }
    ]
});