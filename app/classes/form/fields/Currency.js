/**
 * Created by JetBrains PhpStorm.
 * User: GaiaEHR
 * Date: 3/18/12
 * Time: 10:02 PM
 * To change this template use File | Settings | File Templates.
 */
/*
 * GNU General Public License Usage
 * This file may be used under the terms of the GNU General Public License version 3.0 as published by the Free Software Foundation and appearing in the file LICENSE included in the packaging of this file.  Please review the following information to ensure the GNU General Public License version 3.0 requirements will be met: http://www.gnu.org/copyleft/gpl.html.
 *
 * http://www.gnu.org/licenses/lgpl.html
 *
 * @description: This class provide aditional format to numbers by extending Ext.form.field.Number
 *
 * @author: Greivin Britton
 * @email: brittongr@gmail.com
 * @version: 2 compatible with ExtJS 4
 */
Ext.define('App.classes.form.fields.Currency',
    {
        extend:'Ext.form.field.Number', //Extending the NumberField
        alias:'widget.mitos.currency', //Defining the xtype,
        currencySymbol:'$',
        useThousandSeparator:true,
        thousandSeparator:',',
        alwaysDisplayDecimals:true,
        fieldStyle:'text-align: right;',
        initComponent:function () {
            if (this.useThousandSeparator && this.decimalSeparator == ',' && this.thousandSeparator == ',')
                this.thousandSeparator = '.';
            else
            if (this.allowDecimals && this.thousandSeparator == '.' && this.decimalSeparator == '.')
                this.decimalSeparator = ',';

            this.callParent(arguments);
        },
        setValue:function (value) {
            App.classes.form.fields.Currency.superclass.setValue.call(this, value != null ? value.toString().replace('.', this.decimalSeparator) : value);

            this.setRawValue(this.getFormattedValue(this.getValue()));
        },
        getFormattedValue:function (value) {
            if (Ext.isEmpty(value) || !this.hasFormat())
                return value;
            else {
                var neg = null;

                value = (neg = value < 0) ? value * -1 : value;
                value = this.allowDecimals && this.alwaysDisplayDecimals ? value.toFixed(this.decimalPrecision) : value;

                if (this.useThousandSeparator) {
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

                return Ext.String.format('{0}{1}{2}', (neg ? '-' : ''), (Ext.isEmpty(this.currencySymbol) ? '' : this.currencySymbol + ' '), value);
            }
        },
        /**
         * overrides parseValue to remove the format applied by this class
         */
        parseValue:function (value) {
            //Replace the currency symbol and thousand separator
            return App.classes.form.fields.Currency.superclass.parseValue.call(this, this.removeFormat(value));
        },
        /**
         * Remove only the format added by this class to let the superclass validate with it's rules.
         * @param {Object} value
         */
        removeFormat:function (value) {
            if (Ext.isEmpty(value) || !this.hasFormat())
                return value;
            else {
                value = value.toString().replace(this.currencySymbol + ' ', '');

                value = this.useThousandSeparator ? value.replace(new RegExp('[' + this.thousandSeparator + ']', 'g'), '') : value;

                return value;
            }
        },
        /**
         * Remove the format before validating the the value.
         * @param {Number} value
         */
        getErrors:function (value) {
            return App.classes.form.fields.Currency.superclass.getErrors.call(this, this.removeFormat(value));
        },
        hasFormat:function () {
            return this.decimalSeparator != '.' || (this.useThousandSeparator == true && this.getRawValue() != null) || !Ext.isEmpty(this.currencySymbol) || this.alwaysDisplayDecimals;
        },
        /**
         * Display the numeric value with the fixed decimal precision and without the format using the setRawValue, don't need to do a setValue because we don't want a double
         * formatting and process of the value because beforeBlur perform a getRawValue and then a setValue.
         */
        onFocus:function () {
            this.setRawValue(this.removeFormat(this.getRawValue()));

            this.callParent(arguments);
        }
    });