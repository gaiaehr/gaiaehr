/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * @class Ext.ux.form.field.DateTime
 * @extends Ext.form.FieldContainer
 * @author atian25 (http://www.sencha.com/forum/member.php?51682-atian25)
 * @author ontho (http://www.sencha.com/forum/member.php?285806-ontho)
 * @author jakob.ketterl (http://www.sencha.com/forum/member.php?25102-jakob.ketterl)
 *
 */
Ext.define('App.ux.form.fields.DateTime', {
	extend: 'Ext.form.FieldContainer',
	mixins: {
		field: 'Ext.form.field.Field'
	},
	alias : 'widget.mitos.datetime',

	combineErrors: true,
	msgTarget    : 'under',
	layout       : 'hbox',
	readOnly     : false,
	allowBlank   : true,

	/**
	 * @cfg {String} dateFormat
	 * The default is 'Y-m-d'
	 */
	dateFormat    : 'Y-m-d',
	/**
	 * @cfg {String} timeFormat
	 * The default is 'H:i:s'
	 */
	timeFormat    : 'g:i a',
	/**
	 * @cfg {String} dateTimeFormat
	 * The format used when submitting the combined value.
	 * Defaults to 'Y-m-d H:i:s'
	 */
	dateTimeFormat: 'Y-m-d H:i:s',
	/**
	 * @cfg {Object} dateConfig
	 * Additional config options for the date field.
	 */
	dateConfig    : {},
	/**
	 * @cfg {Object} timeConfig
	 * Additional config options for the time field.
	 */
	timeConfig    : {},


	// properties

	dateValue: null, // Holds the actual date
	/**
	 * @property dateField
	 * @type Ext.form.field.Date
	 */
	dateField: null,
	/**
	 * @property timeField
	 * @type Ext.form.field.Time
	 */
	timeField: null,

	initComponent: function() {
		var me = this;
		me.items = me.items || [];

		me.dateConfig.allowBlank = me.allowBlank;

		me.dateField = Ext.create('Ext.form.field.Date', Ext.apply({
			format     : me.dateFormat,
			flex       : 1,
            emptyText  : _('date'),
            margin     : 0,
			submitValue: false
		}, me.dateConfig));
		me.items.push(me.dateField);

		me.timeField = Ext.create('Ext.form.field.Time', Ext.apply({
			format     : me.timeFormat,
			flex       : 1,
            emptyText  : _('time'),
            margin     : 0,
			submitValue: false
		}, me.timeConfig));
		me.items.push(me.timeField);

		for(var i = 0; i < me.items.length; i++) {
			me.items[i].on('focus', Ext.bind(me.onItemFocus, me));
			me.items[i].on('blur', Ext.bind(me.onItemBlur, me));
			me.items[i].on('specialkey', function(field, event) {
				var key = event.getKey(),
					tab = key == event.TAB;

				if(tab && me.focussedItem == me.dateField) {
					event.stopEvent();
					me.timeField.focus();
					return;
				}

				me.fireEvent('specialkey', field, event);
			});
		}

		if(me.layout == 'vbox') me.height = 44;

		me.callParent();

		// this dummy is necessary because Ext.Editor will not check whether an inputEl is present or not
//		this.inputEl = {
//			dom         : {},
//			swallowEvent: function() {
//			}
//		};

		me.initField();
	},

	focus: function() {
		this.callParent();
		this.dateField.focus();
	},

	onItemFocus: function(item) {
		if(this.blurTask) this.blurTask.cancel();
		this.focussedItem = item;
	},

	onItemBlur: function(item) {
		var me = this;
		if(item != me.focussedItem) return;
		// 100ms to focus a new item that belongs to us, otherwise we will assume the user left the field
		me.blurTask = new Ext.util.DelayedTask(function() {
			me.fireEvent('blur', me);
		});
		me.blurTask.delay(100);
	},

	getValue: function() {
		var value = null,
			date = this.dateField.getSubmitValue(),
			time = this.timeField.getSubmitValue();

		if(date) {
			if(time) {
				var format = this.getFormat();
				value = Ext.Date.parse(date + ' ' + time, format);
			}
			else {
				value = this.dateField.getValue();
			}
		}
		return value;
	},

	getSubmitValue: function() {
		var value = this.getValue();
		return value ? Ext.Date.format(value, this.dateTimeFormat) : null;
	},

	setValue: function(value) {
		if(Ext.isString(value)) {
			value = Ext.Date.parse(value, this.dateTimeFormat);
		}
		this.dateField.setValue(value);
		this.timeField.setValue(value);
	},

	getFormat    : function() {
		return (this.dateField.submitFormat || this.dateField.format) + " " + (this.timeField.submitFormat || this.timeField.format);
	},

	// Bug? A field-mixin submits the data from getValue, not getSubmitValue
	getSubmitData: function() {
		var me = this,
			data = null;
		if(!me.disabled && me.submitValue && !me.isFileUpload()) {
			data = {};
			data[me.getName()] = '' + me.getSubmitValue();
		}
		return data;
	},

    setReadOnly:function(value){
        this.dateField.setReadOnly(value);
        this.timeField.setReadOnly(value);
    },

	setMaxValue:function(date){
		this.dateField.setMaxValue(date);
	}
});

//eo file