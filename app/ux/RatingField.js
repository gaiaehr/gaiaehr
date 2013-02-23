/*!
 * Ext.ux.RatingField
 *
 * Copyright 2011, Dan Harabagiu
 * Licenced under the Apache License Version 2.0
 * See LICENSE
 *
 *
 * Version : 0.1 - Initial coding
 * Version : 0.2
 *  - Added Field reset button
 *  - Added CSS class for reset button
 *  - Added reset function for the field
 *  - Minimum number of stars is 2
 *  - On creation default value is now 0, was null
 *  - Option to choose left / right for the reset button position
 */
/*global Ext : false, */
Ext.define('App.ux.RatingField', {
	extend: 'Ext.form.field.Number',
	alias: 'widget.ratingField',
	componentCls: 'x-form-rating',
	ratingClassReset: "ux-rating-reset",
	starCls: 'ux-rating-star',
	margin: '4 10',
	/**
	 * @cfg {Number} numberOfStars
	 *
	 */
	numberOfStars: 5,

	/**
	 * @cfg {Number} minValue
	 * css Number of stars to display
	 */
	minValue: 0,

	/**
	 * @cfg {Number} maxValue
	 *
	 */
	maxValue: 5,

	/**
	 * @cfg {Number} split
	 * number of split per star (to show a gradual selection)
	 */
	split: 1,

	/**
	 * @cfg {Number} canReset
	 * true to show a reset button
	 */
	canReset: true,

	/**
	 * @cfg {Number} resetPosition
	 * position of reset button when canReset is true. Whatever is not equal to 'right' is considered as 'left'
	 */
	resetPosition: "right",

	/**
	 * @cfg {Number} emptyText
	 *
	 */
	emptyText: '',

	/**
	 * @cfg {Number} keyNavEnabled
	 * when set to true, special keys (arrows, space and del) are  enabled to set value on focus.
	 */
	keyNavEnabled: true,

	/**
	 * @cfg {Object} titles
	 * Object used when setting titles to stars. In example below, star values from 0 to 1.4 would carry 'poor' as a title, from 1.4 to 3, 'medium', etc
	 */
	/*titles: {
	 '0': 'poor',
	 '1.4': 'medium',
	 '3': 'excellent',
	 '5': 'perfect'
	 },*/
	initComponent: function () {
		var me = this;
		Ext.applyIf(me, {
			maxValue: 5,
			minValue: 0,
			split: 1,
			numberOfStars: 5,
			titles: {}
		});
		var tempVal = [],
			hasTitle,
			name,
			split = me.split,
			width = 16 / split,
			nb = me.numberOfStars,
			len = nb * split - 1,
			minValue = me.minValue,
			maxValue = me.maxValue,
			val, title, i;

		for (name in me.titles) {
			tempVal.push({
				val: name * 1,
				title: me.titles[name]
			});
		}
		hasTitle = !!tempVal.length;
		Ext.Array.sort(tempVal, function (a, b) {
			return a.val > b.val;
		});

		//me.step = (maxValue - minValue) / len;
		me.stars = [];
		me.values = [];
		me.titles = [];
		me.starWidth = width;
		if (!me.value) {
			me.value = minValue;
		}
		for (i = 0; i <= len; i++) {
			val = i + 1;
			if (hasTitle) {
				if (tempVal.length && (tempVal[1].val <= val)) {
					tempVal = tempVal.slice(1);
				}
				title = tempVal[0].title;
			} else {
				title = val;
			}
			me.stars.push({
				key: val,
				title: title,
				margin: (i % split) * width
			});
			me.values.push(val);
			me.titles.push(title);
		}
		me.msgTarget = 'side';
		me.starClsOn = me.starCls + '-on';
		me.starClsHover = me.starCls + '-hover';
		me.starClsFocus = me.starCls + '-focus';
		me.callParent();

	},
	setValue: function (val) {
		var me = this;
		me.callParent(arguments);
		me.afterSetValue(eval(val));
	},
	afterSetValue: function (val) {
		var me = this;
		if (me.rendered) {
			var index = me.getValueIndex(val);
			if (index === -1) {
				if (me.canReset) {
					me.resetEl.setOpacity(0.5);
				}
				me.fillNone();
				me.selected = -1;
			} else if (index !== me.selected) {
				if (me.canReset) {
					me.resetEl.setOpacity(0.99);
				}
				me.selected = index;
				me.fillTo(index, false);

			}
		}
	},
	getValueIndex: function (val) {
		var me = this;
		if (val == me.emptyText || !Ext.isDefined(val) || (val > me.maxValue) || (val < me.minValue)) {
			return -1
		}
		var ret = me.values.indexOf(val);
		if (ret > -1) {
			return ret
		}
		return Ext.each(me.values, function (v, i) {
			if (v >= val) {
				return false
			}
		});
	},

	addFocusListener: function () {
		var me = this;
		if (!me.focusListenerAdded) {
			me.focusEl.on({
				focus: me.onFocus,
				blur: me.onBlur,
				scope: me
			});
			me.focusListenerAdded = true;
		}
	},
	onFocus: function () {
		var me = this;
		if (me.isReactive()) {
			me.bodyEl.addCls(me.starClsFocus)
		}
	},
	onBlur: function () {
		var me = this
		me.bodyEl.removeCls(me.starClsFocus)
	},
	afterRender: function (ct, position) {
		var me = this,
			rightPos = me.resetPosition == 'right',
			cancel = '<div class="reset-el ' + me.ratingClassReset + '" title="reset" style="margin-' + (rightPos ? 'left' : 'right') + ':5px;"></div>';

		me.callParent();
		var tpl = new Ext.XTemplate(
			'<div class="ux-rating-container ux-rating-clearfix" tabIndex="'+ me.tabIndex +'">',
			rightPos ? '' : cancel,
			'<tpl for=".">',
			'<div key="{key}" class="', me.starCls, '" style="width: ', me.starWidth, 'px;">',
			'<a title="{title}" style="margin-left: -{margin}px;">{key}</a>',
			'</div>',
			'</tpl>',
			'<input type="hidden" name="', me.getName(), '" class="input-el" tabIndex="'+ me.tabIndex +'"></hidden>',
			rightPos ? cancel : '',
			'<a class="focus-el" tabIndex="0"></a>',
			'</div>',

			{
				compiled: true
			});

		me.stars = tpl.overwrite(me.bodyEl, me.stars, true).query('.' + me.starCls);
		me.focusEl = Ext.get(Ext.query('a.focus-el', me.bodyEl.dom[0]));
		me.inputEl = Ext.get(Ext.query('input.input-el', me.bodyEl.dom)[0]);
		me.resetEl = Ext.get(Ext.query('div.reset-el', me.bodyEl.dom)[0]);
	},
	initEvents: function () {
		var me = this;
		me.callParent(arguments);
		me.bodyEl.on({
			click: me.onStarClick,
			mouseover: me.onStarOver,
			mouseout: me.onStarOut,
			scope: me,
			delegate: 'div.ux-rating-star'
		});
		if (me.canReset) {
			me.resetEl.visibilityMode = Ext.Element.DISPLAY;
			me.resetEl.hover(
                function () {
                    if (me.isReactive() && me.getValue() > 0) {
                        Ext.fly(this).addCls('ux-rating-reset-hover');
                    }
			    },
                function () {
                    if (me.isReactive()) {
                        Ext.fly(this).removeCls('ux-rating-reset-hover');
                    }
			    }
            );

			me.resetEl.on('click', me.reset, me);
		}
		// Init arrow keys amd space/del for reseting
		if (me.keyNavEnabled) {
			me.spinnerKeyNav = new Ext.util.KeyNav(me.focusEl, {
				scope: me,
				up: me.spinUp,
				down: me.spinDown,
				right: me.spinUp,
				left: me.spinDown,
				space: me.reset,
				del: me.reset
			});
		}
		me.setValue(me.value)
	},
	reset: function (ev, t) {
		if (this.isReactive() && this.getValue() > 0) {
			this.setValue(0);
			this.fireEvent('click', this, 0);
		}
	},

	isReactive: function () {
		return !this.readOnly && !this.disabled
	},
	onStarClick: function (e, t) {
		var me = this, val;
		if (me.isReactive()){
			val = me.values[me.stars.indexOf(t)];
			me.setValue(val);
			me.fireEvent('click', me, val);
		}
	},
	onStarOver: function (e, t) {
		if (this.isReactive()) {
			this.fillTo(this.stars.indexOf(t), true);
		}
	},
	onStarOut: function (e, t) {
		if (this.isReactive()) {
			this.fillTo(this.selected);
		}
	},
	fillTo: function (index, hover) {
		var me = this;
		if (index != -1) {
			var addCls = hover ? me.starClsHover : me.starClsOn;
			var removeCls = hover ? me.starClsOn : me.starClsHover;

			// We add a css class to each star up until the selected one
			Ext.each(me.stars.slice(0, index + 1), function () {
				Ext.fly(this).removeCls(removeCls).addCls(addCls);
			});

			// And then remove the same class from all the stars after this one
			Ext.each(me.stars.slice(index + 1), function () {
				Ext.fly(this).removeCls([removeCls, addCls]);
			});
		} else {
			me.fillNone();
		}
	},
	fillNone: function () {
		this.bodyEl.select('.ux-rating-star').removeCls(['ux-rating-star-hover', 'ux-rating-star-on']);
	}
});