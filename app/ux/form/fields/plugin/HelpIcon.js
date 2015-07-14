Ext.define('App.ux.form.fields.plugin.HelpIcon', {
	extend: 'Ext.AbstractPlugin',
	alias: 'plugin.helpicon',
	iconSrc: 'resources/images/icons/icohelp.png',
	iconHeight: 16,
	iconWidth: 16,
	iconMargin: '0 5',
	init: function(field){
		field.on('render', this.addHelpIcon, this);
	},
	addHelpIcon: function(field){
		var me = this,
			tpl = '<td><img src="' + me.iconSrc + '" height="' + me.iconHeight + '" width="' + me.iconWidth + '" style="margin:' + me.iconMargin + '"></td>',
			tplDom;

		tplDom = Ext.DomHelper.append(field.inputRow, tpl, true);

		Ext.create('Ext.tip.ToolTip', {
			target: tplDom,
			dismissDelay: 0,
			html: me.helpMsg || field.helpMsg || 'Help Message...'
		});
	}
});