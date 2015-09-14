Ext.define('App.ux.form.fields.plugin.ReadOnlyLabel', {
	extend: 'Ext.AbstractPlugin',
	alias: 'plugin.readonlylabel',

	align: 'right',

	text: _('read_only'),
	textBg: 'red',
	textSize: '14',
	textColor: 'white',
	textOpacity: .7,

	/**
	 *
	 * @param field
	 */
	init: function(field){
		var  me = this;

		field.on('render', me.onRender, me);
		field.on('writeablechange', me.setReadOnly, me);

	},

	setReadOnly: function(field, readOnly){
		field.readOnlyEl.setVisible(readOnly);
	},

	onRender: function(field){
		this.addReadOnlyEl(field);
		this.setReadOnly(field, field.readOnly);
	},

	/**
	 *
	 * @param field
	 */
	addReadOnlyEl: function(field){
		var me = this,
			styles = {
				'position': 'absolute',
				'background-color': me.textBg,
				'font-size': me.textSize + 'px',
				'color': me.textColor,
				'padding': '5px 10px',
				'index': 50,
				'top': '10px',
				'border-radius': '5px',
				'visibility': 'hidden'
			};

		if(me.align == 'left'){
			styles.left = '10px';
		}else{
			styles.right = '10px';
		}

		field.readOnlyEl = Ext.DomHelper.append(field.el, { tag:'div', cls:'badgeText x-unselectable'}, true);
		field.readOnlyEl.setOpacity(me.textOpacity);
		field.readOnlyEl.setStyle(styles);
		field.readOnlyEl.update(me.text.toString ? me.text.toString() : me.text);

	}

});