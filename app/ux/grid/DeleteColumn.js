
Ext.define('App.ux.grid.DeleteColumn', {
	extend: 'Ext.grid.column.Action',
	xtype: 'griddeletecolumn',
	icon: 'resources/images/icons/delete.png',  // Use a URL in the icon config
	tooltip: _('delete'),
	acl: '*',
	width: 30,
	handler: function(grid, rowIndex, colIndex, item, e, record) {
		if(this.acl === false || eval(a(this.acl)) === true){
			Ext.Msg.show({
				title:_('wait'),
				msg: _('delete_record_confirmation'),
				buttons: Ext.Msg.YESNO,
				icon: Ext.Msg.QUESTION,
				fn: function(btn){
					if(btn == 'yes'){
						record.store.remove(record);
					}
				}
			});
		}else{
			app.msg(_('oops'), _('permission_denied'), true);
		}
	}
});
