Ext.define('App.ux.form.fields.plugin.BadgeText', {
	extend: 'Ext.AbstractPlugin',
	alias: 'plugin.badgetext',

	disableBg: 'gray',
	enableBg: 'red',
	textSize: 10,
	textColor: 'black',
	defaultText: '&#160;',
	disableOpacity: 0,
	text: '&#160;',
	disable: true,

	/**
	 *
	 * @param field
	 */
	init: function(field){

		var me = this;

		me.field = field;
		me.text = me.defaultText;

		field.on('render', me.addBadgeEl, me);

		Ext.apply(field,{

			setBadgeText:function(text){

				me.disable = typeof text == 'undefined' || text === me.defaultText;
				me.text = !me.disable ? text : me.defaultText;
				if (field.rendered) {
					field.badgeEl.update(text.toString ? text.toString() : text);
					if (Ext.isStrict && Ext.isIE8) {
						field.el.repaint();
					}
					me.setDisabled(me.disable);
				}
				return field;
			},

			getBadgeText:function(){
				return me.text;
			}


		});

	},

	/**
	 *
	 * @param field
	 */
	addBadgeEl: function(field){
		var me = this;

		field.badgeEl = Ext.DomHelper.append(field.el, { tag:'div', cls:'badgeText x-unselectable'}, true);
		field.badgeEl.setOpacity(me.disableOpacity);
		field.badgeEl.setStyle({
			'position': 'absolute',
			'background-color': me.disableBg,
			'font-size': me.textSize,
			'color': me.textColor,
			'padding': '2 4',
			'border': 'solid 1px gray',
			'index': 50,
			'left': 0,
			'top': 0,
			'cursor':'pointer'
		});
		field.badgeEl.update(me.text.toString ? me.text.toString() : me.text);

	},

	/**
	 *
	 */
	onBadgeClick:function(){
		var me = this;
		me.field.fireEvent('badgeclick', me.field, me.text)
	},

	/**
	 *
	 * @param disable
	 */
	setDisabled:function(disable){
		var me = this;

		me.field.badgeEl.setStyle({
			'background-color': (disable ? me.disableBg : me.enableBg),
			'color': (disable ? 'black' : 'white'),
			'opacity': (disable ? me.disableOpacity : 1)
		});

		if(!disable){
			me.field.badgeEl.on('click', me.onBadgeClick, me, { preventDefault: true, stopEvent:true });
		}else{
			me.field.badgeEl.un('click', me.onBadgeClick, me);
		}
	}
});