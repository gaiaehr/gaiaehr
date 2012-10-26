/*
 GaiaEHR (Electronic Health Records)
 Currency.js
 UX
 Copyright (C) 2012 Ernesto Rodriguez

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
Ext.define('App.ux.form.fields.Currency',
{
	extend : 'Ext.form.field.Number', //Extending the NumberField
	alias : 'widget.mitos.currency', //Defining the xtype,
	currencySymbol : globals['gbl_currency_symbol'],
	useThousandSeparator : true,
	thousandSeparator : ',',
	alwaysDisplayDecimals : true,
	fieldStyle : 'text-align: right;',
	initComponent : function()
	{
		if (this.useThousandSeparator && this.decimalSeparator == ',' && this.thousandSeparator == ',')
			this.thousandSeparator = '.';
		else
		if (this.allowDecimals && this.thousandSeparator == '.' && this.decimalSeparator == '.')
			this.decimalSeparator = ',';

		this.callParent(arguments);
	},
	setValue : function(value)
	{
		App.ux.form.fields.Currency.superclass.setValue.call(this, value != null ? value.toString().replace('.', this.decimalSeparator) : value);

		this.setRawValue(this.getFormattedValue(this.getValue()));
	},
	getFormattedValue : function(value)
	{
		if (Ext.isEmpty(value) || !this.hasFormat())
			return value;
		else
		{
			var neg = null;

			value = ( neg = value < 0) ? value * -1 : value;
			value = this.allowDecimals && this.alwaysDisplayDecimals ? value.toFixed(this.decimalPrecision) : value;

			if (this.useThousandSeparator)
			{
				if (this.useThousandSeparator && Ext.isEmpty(this.thousandSeparator))
					throw ('NumberFormatException: invalid thousandSeparator, property must has a valid character.');

				if (this.thousandSeparator == this.decimalSeparator)
					throw ('NumberFormatException: invalid thousandSeparator, thousand separator must be different from decimalSeparator.');

				value = value.toString();

				var ps = value.split('.');
				ps[1] = ps[1] ? ps[1] : null;

				var whole = ps[0];

				var r = /(\d+)(\d{3})/;

				var ts = this.thousandSeparator;

				while (r.test(whole))
				whole = whole.replace(r, '$1' + ts + '$2');

				value = whole + (ps[1] ? this.decimalSeparator + ps[1] : '');
			}

			return Ext.String.format('{0}{1}{2}', ( neg ? '-' : ''), (Ext.isEmpty(this.currencySymbol) ? '' : this.currencySymbol + ' '), value);
		}
	},
	/**
	 * overrides parseValue to remove the format applied by this class
	 */
	parseValue : function(value)
	{
		//Replace the currency symbol and thousand separator
		return App.ux.form.fields.Currency.superclass.parseValue.call(this, this.removeFormat(value));
	},
	/**
	 * Remove only the format added by this class to let the superclass validate with it's rules.
	 * @param {Object} value
	 */
	removeFormat : function(value)
	{
		if (Ext.isEmpty(value) || !this.hasFormat())
			return value;
		else
		{
			value = value.toString().replace(this.currencySymbol + ' ', '');

			value = this.useThousandSeparator ? value.replace(new RegExp('[' + this.thousandSeparator + ']', 'g'), '') : value;

			return value;
		}
	},
	/**
	 * Remove the format before validating the the value.
	 * @param {Number} value
	 */
	getErrors : function(value)
	{
		return App.ux.form.fields.Currency.superclass.getErrors.call(this, this.removeFormat(value));
	},
	hasFormat : function()
	{
		return this.decimalSeparator != '.' || (this.useThousandSeparator == true && this.getRawValue() != null) || !Ext.isEmpty(this.currencySymbol) || this.alwaysDisplayDecimals;
	},
	/**
	 * Display the numeric value with the fixed decimal precision and without the format using the setRawValue, don't need to do a setValue because we don't want a double
	 * formatting and process of the value because beforeBlur perform a getRawValue and then a setValue.
	 */
	onFocus : function()
	{
		this.setRawValue(this.removeFormat(this.getRawValue()));

		this.callParent(arguments);
	}
}); 