Ext.define('App.ux.form.fields.plugin.BadgeText', {
	extend: 'Ext.AbstractPlugin',
	alias: 'plugin.badgetext',

	disableBg: 'gray',
	enableBg: 'red',
	textSize: 10,
	textColor: 'white',
	defaultText: ' ',
	disableOpacity: 0,
	align: 'left',
	text: ' ',
	disable: true,
	button: null,
	/**
	 *
	 * @param button
	 */
	init: function(button){

		var me = this;

		me.button = button;
		me.text = me.defaultText;

		button.on('render', me.addBadgeEl, me);

		Ext.apply(button,{

			setBadgeText:function(text){

				me.disable = typeof text == 'undefined' || text === me.defaultText;
				me.text = !me.disable ? text : me.defaultText;
				if (button.rendered) {
					button.badgeEl.update(text.toString ? text.toString() : text);
					if (Ext.isStrict && Ext.isIE8) {
						button.el.repaint();
					}
					me.setDisabled(me.disable);
				}
				return button;
			},

			getBadgeText:function(){
				return me.text;
			}


		});

	},

	/**
	 *
	 * @param button
	 */
	addBadgeEl: function(button){
		var me = this,
			styles = {
				'position': 'absolute',
				'background-color': me.disableBg,
				'font-size': me.textSize + 'px',
				'color': me.textColor,
				'padding': '1px 2px',
				'index': 50,
				'top': '-5px',
				'border-radius': '3px',
				'font-weight': 'bold',
				'text-shadow': 'rgba(0, 0, 0, 0.5) 0 -0.08em 0',
				'box-shadow': 'rgba(0, 0, 0, 0.3) 0 0.1em 0.1em',
				'cursor':'pointer'
			};

		if(me.align == 'left'){
			styles.left = '2px';
		}else{
			styles.right = '2px';
		}

		button.badgeEl = Ext.DomHelper.append(button.el, { tag:'div', cls:'badgeText x-unselectable'}, true);
		button.badgeEl.setOpacity(me.disableOpacity);
		button.badgeEl.setStyle(styles);
		button.badgeEl.update(me.text.toString ? me.text.toString() : me.text);

	},

	/**
	 *
	 */
	onBadgeClick:function(){
		var me = this;
		me.button.fireEvent('badgeclick', me.button, me.text)
	},

	/**
	 *
	 * @param disable
	 */
	setDisabled:function(disable){
		var me = this;

		me.button.badgeEl.setStyle({
			'background-color': (disable ? me.disableBg : me.enableBg),
			//'color': (disable ? 'black' : 'white'),
			'opacity': (disable ? me.disableOpacity : 1)
		});

		me.button.badgeEl.clearListeners();
		if(!disable) me.button.badgeEl.on('click', me.onBadgeClick, me, { preventDefault: true, stopEvent:true });

	}
});