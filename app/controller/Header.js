Ext.define('App.controller.Header', {
    extend: 'Ext.app.Controller',
	requires:[
	],
	refs: [
        {
            ref:'AppHeaderRight',
            selector:'#AppHeaderRight'
        },
        {
            ref:'AppHeaderLeft',
            selector:'#AppHeaderLeft'
        }
	],

	init: function() {
		var me = this;



	},

	/**
	 *
	 * @param {object}  btn         btn config
	 * @param {string}  area        left or right
	 * @param {int}     position    position index
	 */
	addHeaderBtn: function(btn, area, position){

		btn = btn || {};
		area = area || 'left';
		position = position || 0;

		var comp = area == 'left' ? this.getAppHeaderLeft() : this.getAppHeaderRight(),
			btnConf = Ext.apply(btn, {
			xtype: 'button',
			scale: 'large',
			margin: '0 3 0 0',
			cls: 'headerLargeBtn',
			padding: 0
		});

		comp.insert(position, btnConf);

	}

});