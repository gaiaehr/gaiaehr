/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 11/1/11
 * Time: 12:37 PM
 */
Ext.define('App.ux.form.fields.Help', {
    extend       : 'Ext.Img',
    alias        : 'widget.helpbutton',
    src          : 'resources/images/icons/icohelp.png',
    height       : 16,
    width        : 16,
    margin       : '3 10',
    helpMsg      : i18n('help_message'),
    initComponent: function() {
        var me = this;
        me.listeners = {
            render: function(c) {
                me.setToolTip(c.getEl());
            }
        };
        me.callParent();
    },

    setToolTip: function(el) {
        Ext.create('Ext.tip.ToolTip', {
            target      : el,
            dismissDelay: 0,
            html        : this.helpMsg
        });
    }
});