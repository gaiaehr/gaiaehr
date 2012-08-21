/*!
 * Extensible 1.6.0-b1
 * Copyright(c) 2010-2012 Extensible, LLC
 * licensing@ext.ensible.com
 * http://ext.ensible.com
 */

// TODO: Create Extensible.form.recurrence.Parser and factor all
//       rrule value getting/setting out of these option classes
//       and into the parser.

Ext.define('Extensible.form.recurrence.AbstractOption', {
    extend: 'Ext.form.FieldContainer',
    
    mixins: {
        field: 'Ext.form.field.Field'
    },
    
    layout: 'hbox',
    
    defaults: {
        margins: '0 5 0 0'
    },
    
    key: undefined,
    
    /**
     * @cfg {String} dateValueFormat
     * The date string format to return in the RRULE. This is the standard ISO-style iCal
     * date format, e.g. January 31, 2012, 14:00 would be formatted as: "20120131T140000Z".
     */
    dateValueFormat: 'Ymd\\THis\\Z',
    
    optionDelimiter: ';',
    
    initComponent: function() {
        var me = this;
        
        me.addEvents(
            /**
             * @event change
             * Fires when a user-initiated change is detected in the value of the field.
             * @param {Extensible.form.recurrence.AbstractOption} this
             * @param {Mixed} newValue The new value
             * @param {Mixed} oldValue The old value
             */
            'change'
        );
        me.startDate = me.startDate || new Date();
        me.items = me.getItemConfigs();
        
        me.callParent(arguments);
        
        me.initRefs();
        me.initField();
    },
    
    formatDate: function(date) {
        return Ext.Date.format(date, this.dateValueFormat);
    },
    
    parseDate: function(dateString, options) {
        options = options || {};
        
        try {
            var date = Ext.Date.parse(dateString, options.format || this.dateValueFormat, options.strict);
            if (date) {
                return date;
            }
        }
        catch(ex) {}
        
        return options.defaultValue || new Date();
    },
    
    afterRender: function(){
        this.callParent(arguments);
        this.updateLabel();
    },
    
    initRefs: Ext.emptyFn,
    
    setFrequency: function(freq) {
        this.frequency = freq;
    },
    
    setStartDate: function(dt) {
        this.startDate = dt;
        return this;
    },
    
    getStartDate: function() {
        return this.startDate || Extensible.Date.today();
    },
    
    getDefaultValue: function() {
        return '';
    },
    
    preSetValue: function(v, readyField) {
        var me = this;
        
        if (!v) {
            v = me.getDefaultValue();
        }
        if (!readyField) {
            me.on('afterrender', function() {
                me.setValue(v);
            }, me, {single: true});
            return false;
        }
        
        me.value = v;
        
        return true;
    }
});Ext.define('Extensible.form.recurrence.option.Duration', {
    extend: 'Extensible.form.recurrence.AbstractOption',
    alias: 'widget.extensible.recurrence-duration',
    
    requires: [
        'Ext.form.Label',
        'Ext.form.field.ComboBox',
        'Ext.form.field.Number',
        'Ext.form.field.Date'
    ],
    
    minOccurrences: 1,
    
    maxOccurrences: 999,
    
    /**
     * @cfg {Number} defaultEndDateOffset
     * The unit of time after the start date to set the end date field when no end date is specified in the
     * recurrence rule (defaults to 5). The specific date value depends on the recurrence frequency
     * (selected in the {@link Extensible.form.recurrence.FrequencyCombo FrequencyCombo}) which is the
     * unit by which this setting is multiplied to calculate the default date. For example, if recurrence
     * frequency is daily, then the resulting date would be 5 days after the start date. However, if
     * frequency is monthly, then the date would be 5 months after the start date.
     */
    defaultEndDateOffset: 5,
    
    /**
     * @cfg {Number} minDateOffset
     * The number of days after the start date to set as the minimum allowable end date
     * (defaults to 1).
     */
    minDateOffset: 1,
    
    maxEndDate: new Date('12/31/9999'),
    
    endDateWidth: 120,
    
    cls: 'extensible-recur-duration',
    
    //endDateFormat: null, // inherit by default
    
    getItemConfigs: function() {
        var me = this,
            startDate = me.getStartDate();
        
        return [{
            xtype: 'label',
            text: 'and continuing'
        },{
            xtype: 'combo',
            itemId: me.id + '-duration-combo',
            mode: 'local',
            width: 85,
            triggerAction: 'all',
            forceSelection: true,
            value: 'forever',
            store: ['forever', 'for', 'until'],
            listeners: {
                'change': Ext.bind(me.onComboChange, me)
            }
        },{
            xtype: 'datefield',
            itemId: me.id + '-duration-date',
            showToday: false,
            width: me.endDateWidth,
            format: me.endDateFormat || Ext.form.field.Date.prototype.format,
            maxValue: me.maxEndDate,
            allowBlank: false,
            hidden: true,
            minValue: Ext.Date.add(startDate, Ext.Date.DAY, me.minDateOffset),
            value: me.getDefaultEndDate(startDate),
            listeners: {
                'change': Ext.bind(me.onEndDateChange, me)
            }
        },{
            xtype: 'numberfield',
            itemId: me.id + '-duration-num',
            value: 5,
            width: 55,
            minValue: me.minOccurrences,
            maxValue: me.maxOccurrences,
            allowBlank: false,
            hidden: true,
            listeners: {
                'change': Ext.bind(me.onOccurrenceCountChange, me)
            }
        },{
            xtype: 'label',
            itemId: me.id + '-duration-num-label',
            text: 'occurrences',
            hidden: true
        }];
    },
    
    initRefs: function() {
        var me = this;
        me.untilCombo = me.down('#' + me.id + '-duration-combo');
        me.untilDateField = me.down('#' + me.id + '-duration-date');
        me.untilNumberField = me.down('#' + me.id + '-duration-num');
        me.untilNumberLabel = me.down('#' + me.id + '-duration-num-label');
    },
    
    onComboChange: function(combo, value) {
        this.toggleFields(value);
        this.checkChange();
    },
    
    toggleFields: function(toShow) {
        var me = this;
        
        me.untilCombo.setValue(toShow);
        
        if (toShow === 'until') {
            if (!me.untilDateField.getValue()) {
                me.initUntilDate();
            }
            me.untilDateField.show();
        }
        else {
            me.untilDateField.hide();
            me.untilDateIsSet = false;
        }
        
        if (toShow === 'for') {
            me.untilNumberField.show();
            me.untilNumberLabel.show();
        }
        else {
            // recur forever
            me.untilNumberField.hide();
            me.untilNumberLabel.hide();
        }
    },
    
    onOccurrenceCountChange: function(field, value, oldValue) {
        this.checkChange();
    },
    
    onEndDateChange: function(field, value, oldValue) {
        this.checkChange();
    },
    
    setStartDate: function(dt) {
        var me = this,
            value = me.getValue();
        
        if (dt.getTime() !== me.startDate.getTime()) {
            me.callParent(arguments);
            me.untilDateField.setMinValue(dt);
            
            if (!value || me.untilDateField.getValue() < dt) {
                me.initUntilDate(dt);
            }
        }
        return me;
    },
    
    setFrequency: function() {
        this.callParent(arguments);
        this.initUntilDate();
        
        return this;
    },
    
    initUntilDate: function(startDate) {
        if (!this.untilDateIsSet) {
            this.untilDateIsSet = true;
            var endDate = this.getDefaultEndDate(startDate || this.getStartDate());
            this.untilDateField.setValue(endDate);
        }
        return this;
    },
    
    getDefaultEndDate: function(startDate) {
        var options = {},
            unit;
        
        switch (this.frequency) {
            case 'WEEKLY':
            case 'WEEKDAYS':
                unit = 'weeks';
                break;
            
            case 'MONTHLY':
                unit = 'months';
                break;
            
            case 'YEARLY':
                unit = 'years';
                break;
            
            default:
                unit = 'days';
        }
        
        options[unit] = this.defaultEndDateOffset;
        
        return Extensible.Date.add(startDate, options);
    },
    
    getValue: function() {
        var me = this;
        
        // sanity check that child fields are available first
        if (me.untilCombo) {
            if (me.untilNumberField.isVisible()) {
                return 'COUNT=' + me.untilNumberField.getValue();
            }
            else if (me.untilDateField.isVisible()) {
                return 'UNTIL=' + me.formatDate(this.adjustUntilDateValue(me.untilDateField.getValue()));
            }
        }
        return '';
    },
    
    /**
     * If a recurrence UNTIL date is specified, it must be inclusive of all times on that date. By default
     * the returned date value is incremented by one day minus one second to ensure that.
     * @param {Object} untilDate The raw UNTIL date value returned from the untilDateField
     * @return {Date} The adjusted Date object
     */
    adjustUntilDateValue: function(untilDate) {
        return Extensible.Date.add(untilDate, {days: 1, seconds: -1});
    },
    
    setValue: function(v) {
        var me = this;
        
        if (!me.preSetValue(v, me.untilCombo)) {
            return me;
        }
        if (!v) {
            me.toggleFields('forever');
            return me;
        }
        var options = Ext.isArray(v) ? v : v.split(me.optionDelimiter),
            didSetValue = false,
            parts;

        Ext.each(options, function(option) {
            parts = option.split('=');
            
            if (parts[0] === 'COUNT') {
                me.untilNumberField.setValue(parts[1]);
                me.toggleFields('for');
                didSetValue = true;
                return;
            }
            else if (parts[0] === 'UNTIL') {
                me.untilDateField.setValue(me.parseDate(parts[1]));
                // If the min date is updated before this new value gets set it can sometimes
                // lead to a false validation error showing even though the value is valid. This
                // is a simple hack to essentially refresh the min value validation now:
                me.untilDateField.validate();
                me.toggleFields('until');
                didSetValue = true;
                return;
            }
        }, me);
        
        if (!didSetValue) {
            me.toggleFields('forever');
        }
        
        return me;
    }
});
Ext.define('Extensible.form.recurrence.option.Interval', {
    extend: 'Extensible.form.recurrence.AbstractOption',
    alias: 'widget.extensible.recurrence-interval',
    
    dateLabelFormat: 'l, F j',
    
    unit: 'day',
    
    minValue: 1,
    
    maxValue: 999,
    
    cls: 'extensible-recur-interval',
    
    getItemConfigs: function() {
        var me = this;
        
        return [{
            xtype: 'label',
            text: 'Repeat every'
        },{
            xtype: 'numberfield',
            itemId: me.id + '-interval',
            value: 1,
            width: 55,
            minValue: me.minValue,
            maxValue: me.maxValue,
            allowBlank: false,
            enableKeyEvents: true,
            listeners: {
                'change': Ext.bind(me.onIntervalChange, me)
            }
        },{
            xtype: 'label',
            itemId: me.id + '-date-label'
        }];
    },
    
    initRefs: function() {
        var me = this;
        me.intervalField = me.down('#' + me.id + '-interval');
        me.dateLabel = me.down('#' + me.id + '-date-label');
    },
    
    onIntervalChange: function(field, value, oldValue) {
        this.checkChange();
        this.updateLabel();
    },
    
    getValue: function() {
        if (this.intervalField) {
            return 'INTERVAL=' + this.intervalField.getValue();
        }
        return '';
    },
    
    setValue: function(v) {
        var me = this;
        
        if (!me.preSetValue(v, me.intervalField)) {
            return me;
        }
        if (!v) {
            me.intervalField.setValue(me.minValue);
            return me;
        }
        var options = Ext.isArray(v) ? v : v.split(me.optionDelimiter),
            parts;

        Ext.each(options, function(option) {
            parts = option.split('=');
            
            if (parts[0] === 'INTERVAL') {
                me.intervalField.setValue(parts[1]);
                me.updateLabel();
                return;
            }
        }, me);
        
        return me;
    },
    
    setStartDate: function(dt) {
        this.startDate = dt;
        this.updateLabel();
        return this;
    },
    
    setUnit: function(unit) {
        this.unit = unit;
        this.updateLabel();
        return this;
    },
    
    updateLabel: function(unit){
        var me = this;
        
        if (me.intervalField) {
            //TODO: Refactor for localization
            var s = me.intervalField.getValue() === 1 ? '' : 's';
            me.unit = unit ? unit.toLowerCase() : me.unit || 'day';
            
            if (me.dateLabel) {
                me.dateLabel.update(me.unit + s + ' beginning ' +
                    Ext.Date.format(me.getStartDate(), me.dateLabelFormat));
            }
        }
        return me;
    }
});
Ext.define('Extensible.form.recurrence.option.Monthly', {
    extend: 'Extensible.form.recurrence.AbstractOption',
    alias: 'widget.extensible.recurrence-monthly',
    
    requires: [
        'Ext.form.field.ComboBox',
        'Extensible.lang.Number'
    ],
    
    cls: 'extensible-recur-monthly',
    
    nthComboWidth: 150,
    
    unit: 'month',
    
    afterRender: function() {
        this.callParent(arguments);
        this.isYearly = (this.unit === 'year');
        this.initNthCombo();
    },
    
    getItemConfigs: function() {
        return [{
            xtype: 'label',
            text: 'on the'
        },{
            xtype: 'combobox',
            itemId: this.id + '-nth-combo',
            queryMode: 'local',
            width: this.nthComboWidth,
            triggerAction: 'all',
            forceSelection: true,
            displayField: 'text',
            valueField: 'value',
            store: Ext.create('Ext.data.ArrayStore', {
                fields: ['text', 'value'],
                idIndex: 0,
                data: []
            }),
            listeners: {
                'change': Ext.bind(this.onComboChange, this)
            }
        },{
            xtype: 'label',
            text: 'of each ' + this.unit
        }];
    },
    
    initRefs: function() {
        this.nthCombo = this.down('#' + this.id + '-nth-combo');
    },
    
    onComboChange: function(combo, value) {
        this.checkChange();
    },
    
    setStartDate: function(dt) {
        if (dt.getTime() !== this.startDate.getTime()) {
            this.callParent(arguments);
            this.initNthCombo();
        }
        return this;
    },
    
    initNthCombo: function(){
        if (!this.rendered) {
            return;
        }
        var me = this,
            combo = me.nthCombo,
            store = combo.store,
            dt = me.getStartDate(),
            
            // e.g. 30 (for June):
            lastDayOfMonth = Ext.Date.getLastDateOfMonth(dt).getDate(),
            // e.g. "28th day":
            monthDayText = Ext.Date.format(dt, 'jS') + ' day',
            // e.g. 28:
            dayNum = dt.getDate(),
            // index in the month, e.g. 4 for the 4th Tuesday
            dayIndex = Math.ceil(dayNum / 7),
            // e.g. "TU":
            dayNameAbbreviated = Ext.Date.format(dt, 'D').substring(0,2).toUpperCase(),
            // e.g. "4th Tuesday":
            dayOfWeekText = dayIndex + Extensible.Number.getOrdinalSuffix(dayIndex) + Ext.Date.format(dt, ' l'),
            
            // year-specific additions to the resulting value string, used if we are currently
            // executing from within the Yearly option subclass.
            // e.g. "in 2012":
            yearlyText = me.isYearly ? ' in ' + Ext.Date.format(dt, 'F') : '',
            // e.g. "BYMONTH=2;":
            byMonthValue = me.isYearly ? 'BYMONTH=' + Ext.Date.format(dt, 'n') : '',
            // only use this if yearly:
            delimiter = me.isYearly ? me.optionDelimiter : '',
            
            // the first two combo items, which are always included:
            data = [
                [monthDayText + yearlyText, me.isYearly ? byMonthValue : 'BYMONTHDAY=' + dayNum],
                [dayOfWeekText + yearlyText, byMonthValue + delimiter +
                    'BYDAY=' + dayIndex + dayNameAbbreviated]
            ],
            
            // the currently selected index, which we will try to restore after refreshing the combo:
            idx = store.find('value', combo.getValue());
        
        if (lastDayOfMonth - dayNum < 7) {
            // the start date is the last of a particular day (e.g. last Tuesday) for the month
            data.push(['last ' + Ext.Date.format(dt, 'l') + yearlyText,
                byMonthValue + delimiter + 'BYDAY=-1' + dayNameAbbreviated]);
        }
        if (lastDayOfMonth === dayNum) {
            // the start date is the last day of the month
            data.push(['last day' + yearlyText, byMonthValue + delimiter + 'BYMONTHDAY=-1']);
        }
        
        store.removeAll();
        combo.clearValue();
        store.loadData(data);
        
        if (idx > data.length - 1) {
            // if the previously-selected index is now greater than the number of items in the
            // combo default to the last item in the new list
            idx = data.length - 1;
        }
        
        combo.setValue(store.getAt(idx > -1 ? idx : 0).data.value);
        
        return me;
    },
    
    getValue: function() {
        var me = this;
        
        if (me.nthCombo) {
            return me.nthCombo.getValue();
        }
        return '';
    },
    
    setValue: function(v) {
        var me = this;
        
        if (!me.preSetValue(v, me.nthCombo)) {
            return me;
        }
        if (!v) {
            var defaultItem = me.nthCombo.store.getAt(0);
            if (defaultItem) {
                me.nthCombo.setValue(defaultItem.data.value);
            }
            return me;
        }
        var options = Ext.isArray(v) ? v : v.split(me.optionDelimiter),
            parts,
            values = [];

        Ext.each(options, function(option) {
            parts = option.split('=');
            if (parts[0] === 'BYMONTH') {
                // if BYMONTH is present make sure it goes to the beginning of the value
                // string since that's the order the combo sets it in and they must match
                values.unshift(option);
            }
            if (parts[0] === 'BYMONTHDAY' || parts[0] === 'BYDAY') {
                // these go to the back of the value string
                values.push(option);
            }
        }, me);
        
        if (values.length) {
            me.nthCombo.setValue(values.join(me.optionDelimiter));
        }
        
        return me;
    }
});
Ext.define('Extensible.form.recurrence.option.Weekly', {
    extend: 'Extensible.form.recurrence.AbstractOption',
    alias: 'widget.extensible.recurrence-weekly',
    
    requires: [
        'Ext.form.field.Checkbox', // should be required by CheckboxGroup but isn't
        'Ext.form.CheckboxGroup'
    ],
    
    dayValueDelimiter: ',',
    
    cls: 'extensible-recur-weekly',
    
    getItemConfigs: function() {
        var id = this.id;
        
        return [{
            xtype: 'label',
            text: 'on:'
        },{
            xtype: 'checkboxgroup',
            itemId: id + '-days',
            flex: 1,
            items: [
                //**************************************************
                // TODO: Support week start day !== Sunday
                //**************************************************
                { boxLabel: 'Sun', name: 'SU', id: id + '-SU' },
                { boxLabel: 'Mon', name: 'MO', id: id + '-MO' },
                { boxLabel: 'Tue', name: 'TU', id: id + '-TU' },
                { boxLabel: 'Wed', name: 'WE', id: id + '-WE' },
                { boxLabel: 'Thu', name: 'TH', id: id + '-TH' },
                { boxLabel: 'Fri', name: 'FR', id: id + '-FR' },
                { boxLabel: 'Sat', name: 'SA', id: id + '-SA' }
            ],
            listeners: {
                'change': Ext.bind(this.onSelectionChange, this)
            }
        }];
    },
    
    initValue: function() {
        this.callParent(arguments);
        
        if (!this.value) {
            this.selectByDate();
        }
    },
    
    initRefs: function() {
        this.daysCheckboxGroup = this.down('#' + this.id + '-days');
    },
    
    onSelectionChange: function(field, value, oldValue) {
        this.checkChange();
        this.updateLabel();
    },
    
    selectByDate: function(dt) {
        var day = Ext.Date.format(dt || this.getStartDate(), 'D').substring(0,2).toUpperCase();
        this.setValue('BYDAY=' + day);
    },
    
    clearValue: function() {
        this.value = undefined;
        
        if (this.daysCheckboxGroup) {
            this.daysCheckboxGroup.setValue({
                SU:0, MO:0, TU:0, WE:0, TH:0, FR:0, SA:0
            });
        }
    },
    
    getValue: function() {
        var me = this;
        
        if (me.daysCheckboxGroup) {
            // Checkbox group value will look like {MON:"on", TUE:"on", FRI:"on"}
            var fieldValue = me.daysCheckboxGroup.getValue(),
                days = [],
                property;
            
            for (property in fieldValue) {
                if (fieldValue.hasOwnProperty(property)) {
                    // Push the name ('MON') not the value ('on')
                    days.push(property);
                }
            }
            return days.length > 0 ? 'BYDAY=' + days.join(me.dayValueDelimiter) : '';
        }
        return '';
    },
    
    setValue: function(v) {
        var me = this;
        
        if (!me.preSetValue(v, me.daysCheckboxGroup)) {
            return me;
        }
        if (!v) {
            me.daysCheckboxGroup.setValue(null);
            return me;
        }
        var options = Ext.isArray(v) ? v : v.split(me.optionDelimiter),
            compositeValue = {},
            parts, days;

        Ext.each(options, function(option) {
            parts = option.split('=');
            
            if (parts[0] === 'BYDAY') {
                days = parts[1].split(me.dayValueDelimiter);
                    
                Ext.each(days, function(day) {
                    compositeValue[day] = true;
                }, me);
                
                me.daysCheckboxGroup.setValue(compositeValue);
                return;
            }
        }, me);
        
        return me;
    }
});Ext.define('Extensible.form.recurrence.option.Yearly', {
    extend: 'Extensible.form.recurrence.option.Monthly',
    alias: 'widget.extensible.recurrence-yearly',
    
    cls: 'extensible-recur-yearly',
    
    nthComboWidth: 200,
    
    unit: 'year'
    
});/* @private
 * Currently not used
 */
Ext.define('Extensible.form.recurrence.FrequencyCombo', {
    extend: 'Ext.form.ComboBox',
    alias: 'widget.extensible.recurrence-frequency',
    
    requires: ['Ext.data.ArrayStore'],
    
    fieldLabel: 'Repeats',
    queryMode: 'local',
    triggerAction: 'all',
    forceSelection: true,
    displayField: 'pattern',
    valueField: 'id',
    cls: 'extensible-recur-frequency',
    
    frequencyText: {
        none     : 'Does not repeat',
        daily    : 'Daily',
        weekdays : 'Every weekday (Mon-Fri)',
        weekly   : 'Weekly',
        monthly  : 'Monthly',
        yearly   : 'Yearly'
    },
    
    initComponent: function() {
        var me = this;
        
        /**
         * @event frequencychange
         * Fires when a frequency list item is selected.
         * @param {Extensible.form.recurrence.Combo} combo This combo box
         * @param {String} value The selected frequency value (one of the names
         * from {@link #frequencyOptions}, e.g. 'DAILY')
         */
        me.addEvents('frequencychange');
        
        /**
         * @cfg {Array} frequencyOptions
         * An array of arrays, each containing the name/value pair that defines a recurring
         * frequency option supported by the frequency combo. This array is bound to the underlying
         * {@link Ext.data.ArrayStore store} to provide the combo list items. Defaults to:
         *
         *    [
         *        ['NONE', this.frequencyText.none],
         *        ['DAILY', this.frequencyText.daily],
         *        ['WEEKDAYS', this.frequencyText.weekdays],
         *        ['WEEKLY', this.frequencyText.weekly],
         *        ['MONTHLY', this.frequencyText.monthly],
         *        ['YEARLY', this.frequencyText.yearly]
         *    ]
         */
        me.frequencyOptions = me.frequencyOptions || [
            ['NONE', me.frequencyText.none],
            ['DAILY', me.frequencyText.daily],
            ['WEEKDAYS', me.frequencyText.weekdays],
            ['WEEKLY', me.frequencyText.weekly],
            ['MONTHLY', me.frequencyText.monthly],
            ['YEARLY', me.frequencyText.yearly]
        ];
        
        me.store = me.store || Ext.create('Ext.data.ArrayStore', {
            fields: ['id', 'pattern'],
            idIndex: 0,
            data: me.frequencyOptions
        });
        
        me.on('select', me.onSelect, me);
        
        me.callParent(arguments);
    },
    
    onSelect: function(combo, records) {
        this.fireEvent('frequencychange', records[0].data.id);
    }
});/* @private
 * Currently not used
 * Rrule info: http://www.kanzaki.com/docs/ical/rrule.html
 */
Ext.define('Extensible.form.recurrence.Fieldset', {
    extend: 'Ext.form.FieldContainer',
    alias: 'widget.extensible.recurrencefield',
    
    mixins: {
        field: 'Ext.form.field.Field'
    },
    
    requires: [
        'Ext.form.Label',
        'Extensible.form.recurrence.FrequencyCombo',
        'Extensible.form.recurrence.option.Interval',
        'Extensible.form.recurrence.option.Weekly',
        'Extensible.form.recurrence.option.Monthly',
        'Extensible.form.recurrence.option.Yearly',
        'Extensible.form.recurrence.option.Duration'
    ],
    
    //TODO: implement code to use this config.
    // Maybe use xtypes instead for dynamic loading of custom options?
    // Include secondly/minutely/hourly, plugins for M-W-F, T-Th, weekends
    options: [
        'daily', 'weekly', 'weekdays', 'monthly', 'yearly'
    ],
    
    //TODO: implement
    displayStyle: 'field', // or 'dialog'
    
    fieldLabel: 'Repeats',
    fieldContainerWidth: 400,
    startDate: Ext.Date.clearTime(new Date()),
    
    //enableFx: true,
    monitorChanges: true,
    cls: 'extensible-recur-field',
    
    frequencyWidth: null, // defaults to the anchor value
    
    layout: 'anchor',
    defaults: {
        anchor: '100%'
    },
    
    initComponent : function() {
        var me = this;
        
        if (!me.height || me.displayStyle === 'field') {
            delete me.height;
            me.autoHeight = true;
        }
        
        me.items = [{
            xtype: 'extensible.recurrence-frequency',
            hideLabel: true,
            width: this.frequencyWidth,
            itemId: this.id + '-frequency',
            
            listeners: {
                'frequencychange': {
                    fn: this.onFrequencyChange,
                    scope: this
                }
            }
        },{
            xtype: 'container',
            itemId: this.id + '-inner-ct',
            cls: 'extensible-recur-inner-ct',
            autoHeight: true,
            layout: 'anchor',
            hideMode: 'offsets',
            hidden: true,
            width: this.fieldContainerWidth,
            defaults: {
                hidden: true
            },
            items: [{
                xtype: 'extensible.recurrence-interval',
                itemId: this.id + '-interval'
            },{
                xtype: 'extensible.recurrence-weekly',
                itemId: this.id + '-weekly'
            },{
                xtype: 'extensible.recurrence-monthly',
                itemId: this.id + '-monthly'
            },{
                xtype: 'extensible.recurrence-yearly',
                itemId: this.id + '-yearly'
            },{
                xtype: 'extensible.recurrence-duration',
                itemId: this.id + '-duration'
            }]
        }];
        
        me.callParent(arguments);
        
        me.initField();
    },
    
    afterRender: function() {
        this.callParent(arguments);
        this.initRefs();
    },
    
    initRefs: function() {
        var me = this,
            id = me.id;
        
        me.innerContainer = me.down('#' + id + '-inner-ct');
        me.frequencyCombo = me.down('#' + id + '-frequency');
        me.intervalField = me.down('#' + id + '-interval');
        me.weeklyField = me.down('#' + id + '-weekly');
        me.monthlyField = me.down('#' + id + '-monthly');
        me.yearlyField = me.down('#' + id + '-yearly');
        me.durationField = me.down('#' + id + '-duration');
        
        me.initChangeEvents();
    },
    
    initChangeEvents: function() {
        var me = this;
        
        me.intervalField.on('change', me.onChange, me);
        me.weeklyField.on('change', me.onChange, me);
        me.monthlyField.on('change', me.onChange, me);
        me.yearlyField.on('change', me.onChange, me);
        me.durationField.on('change', me.onChange, me);
    },
    
    onChange: function() {
        this.fireEvent('change', this, this.getValue());
    },
    
    onFrequencyChange: function(freq) {
        this.setFrequency(freq);
        this.onChange();
    },
    
    // private
    initValue: function(){
        var me = this;

        me.originalValue = me.lastValue = me.value;

        // Set the initial value - prevent validation on initial set
        me.suspendCheckChange++;
        
        me.setStartDate(me.startDate);
        
        if (me.value !== undefined) {
            me.setValue(me.value);
        }
        else if (me.frequency !== undefined) {
            me.setValue('FREQ=' + me.frequency);
        }
        else{
            me.setValue('');
        }
        me.suspendCheckChange--;
        
        Ext.defer(me.doLayout, 1, me);
        me.onChange();
    },
    
    /**
     * Sets the start date of the recurrence pattern
     * @param {Date} The new start date
     * @return {Extensible.form.recurrence.Fieldset} this
     */
    setStartDate: function(dt) {
        var me = this;
        
        me.startDate = dt;
        
        if (me.innerContainer) {
            me.innerContainer.items.each(function(item) {
                if (item.setStartDate) {
                    item.setStartDate(dt);
                }
            });
        }
        else {
            me.on('afterrender', function() {
                me.setStartDate(dt);
            }, me, {single: true});
        }
        return me;
    },
    
    /**
     * Returns the start date of the recurrence pattern (defaults to the current date
     * if not explicitly set via {@link #setStartDate} or the constructor).
     * @return {Date} The recurrence start date
     */
    getStartDate: function() {
        return this.startDate;
    },
    
    /**
     * Return true if the fieldset currently has a recurrence value set, otherwise returns false.
     */
    isRecurring: function() {
        return this.getValue() !== '';
    },
    
    getValue: function() {
        if (!this.innerContainer) {
            return this.value;
        }
        if (this.frequency === 'NONE') {
            return '';
        }
        
        var values,
            itemValue;
        
        if (this.frequency === 'WEEKDAYS') {
            values = ['FREQ=WEEKLY','BYDAY=MO,TU,WE,TH,FR'];
        }
        else {
            values = ['FREQ=' + this.frequency];
        }
        
        this.innerContainer.items.each(function(item) {
            if(item.isVisible() && item.getValue){
                itemValue = item.getValue();
                if (this.includeItemValue(itemValue)) {
                    values.push(itemValue);
                }
            }
        }, this);
        
        return values.length > 1 ? values.join(';') : values[0];
    },
    
    includeItemValue: function(value) {
        if (value) {
            if (value === 'INTERVAL=1') {
                // Interval is assumed to be 1 in the spec by default, no need to include it
                return false;
            }
            var day = Ext.Date.format(this.startDate, 'D').substring(0,2).toUpperCase();
            if (value === ('BYDAY=' + day)) {
                // BYDAY is only required if different from the pattern start date
                return false;
            }
            return true;
        }
        return false;
    },
    
    getDescription: function() {
        var value = this.getValue(),
            text = '';
        
        // switch(value) {
            // default:
                // text = 'No recurrence';
        // }
        return 'Friendly text : ' + text;
    },
    
    setValue: function(value){
        var me = this;
        
        me.value = (!value || value === 'NONE' ? '' : value);
        
        if (!me.frequencyCombo || !me.innerContainer) {
            me.on('afterrender', function() {
                me.setValue(value);
            }, me, {
                single: true
            });
            return;
        }

        var parts = me.value.split(';');
        
        if (me.value === '') {
            me.setFrequency('NONE');
        }
        else {
            Ext.each(parts, function(part) {
                if (part.indexOf('FREQ') > -1) {
                    var freq = part.split('=')[1];
                    me.setFrequency(freq);
                    me.checkChange();
                    return;
                }
            }, me);
        }
        
        me.innerContainer.items.each(function(item) {
            if (item.setValue) {
                item.setValue(me.value);
            }
        });
        
        me.checkChange();
        
        return me;
    },
    
    setFrequency: function(freq) {
        var me = this;
        
        me.frequency = freq;
        
        if (me.frequencyCombo) {
            me.frequencyCombo.setValue(freq);
            me.showOptions(freq);
            
            this.innerContainer.items.each(function(item) {
                item.setFrequency(freq);
            });
        }
        else {
            me.on('afterrender', function() {
                me.frequencyCombo.setValue(freq);
                me.showOptions(freq);
            }, me, {single: true});
        }
        return me;
    },
    
    showOptions: function(freq) {
        var me = this,
            unit = 'day';
        
        if (freq === 'NONE') {
            // me.innerContainer.items.each(function(item) {
                // item.hide();
            // });
            me.innerContainer.hide();
        }
        else {
            me.intervalField.show();
            me.durationField.show();
            me.innerContainer.show();
        }
        
        switch(freq){
            case 'DAILY':
            case 'WEEKDAYS':
                me.weeklyField.hide();
                me.monthlyField.hide();
                me.yearlyField.hide();
                
                if (freq === 'WEEKDAYS') {
                    unit = 'week';
                }
                break;
            
            case 'WEEKLY':
                me.weeklyField.show();
                me.monthlyField.hide();
                me.yearlyField.hide();
                unit = 'week';
                break;
            
            case 'MONTHLY':
                me.monthlyField.show();
                me.weeklyField.hide();
                me.yearlyField.hide();
                unit = 'month';
                break;
            
            case 'YEARLY':
                me.yearlyField.show();
                me.weeklyField.hide();
                me.monthlyField.hide();
                unit = 'year';
                break;
        }

        me.intervalField.updateLabel(unit);
    }
});Ext.define('Extensible.form.recurrence.RangeEditPanel', {
    extend: 'Ext.form.Panel',
    alias: 'widget.extensible.recurrence-rangeeditpanel',
    
    cls: 'extensible-recur-edit-options',
    
    headerText: 'There are multiple events in this series. How would you like your changes applied?',
    optionSingleButtonText: 'Single',
    optionSingleDescription: 'Apply to this event only. No other events in the series will be affected.',
    optionFutureButtonText: 'Future',
    optionFutureDescription: 'Apply to this and all following events only. Past events will be unaffected.',
    optionAllButtonText: 'All Events',
    optionAllDescription: 'Apply to every event in this series.',
    
    editModes: {
        SINGLE: 'single',
        FUTURE: 'future',
        ALL: 'all'
    },
    
    border: false,
    
    layout: {
        type: 'vbox',
        align: 'stretch'
    },
    
    // private
    initComponent: function(){
        var me = this;
        
        me.editMode = me.editMode || me.editModes.ALL;
        
        me.items = [
            me.getHeaderConfig(),
            me.getOptionPanelConfig(),
            me.getSummaryConfig()
        ];
        me.callParent(arguments);
    },
    
    getHeaderConfig: function() {
        return {
            xtype: 'component',
            html: this.headerText,
            height: 55,
            padding: 15
        };
    },
    
    getSummaryConfig: function() {
        return {
            xtype: 'component',
            itemId: this.id + '-summary',
            html: this.optionAllDescription,
            flex: 1,
            padding: 15
        };
    },
    
    getOptionPanelConfig: function() {
        return {
            xtype: 'panel',
            border: false,
            layout: {
                type: 'hbox',
                pack: 'center'
            },
            items: this.getOptionButtonConfigs()
        };
    },
    
    getOptionButtonConfigs: function() {
        var me = this,
            defaultConfig = {
                xtype: 'button',
                iconAlign: 'top',
                enableToggle: true,
                scale: 'large',
                width: 80,
                toggleGroup: 'recur-toggle',
                toggleHandler: me.onToggle,
                scope: me
        },
        items = [Ext.apply({
            itemId: me.id + '-single',
            text: me.optionSingleButtonText,
            iconCls: 'recur-edit-single',
            pressed: me.editMode === me.editModes.SINGLE
        }, defaultConfig),
        Ext.apply({
            itemId: me.id + '-future',
            text: me.optionFutureButtonText,
            iconCls: 'recur-edit-future',
            pressed: me.editMode === me.editModes.FUTURE
        }, defaultConfig),
        Ext.apply({
            itemId: me.id + '-all',
            text: me.optionAllButtonText,
            iconCls: 'recur-edit-all',
            pressed: me.editMode === me.editModes.ALL
        }, defaultConfig)];
        
        return items;
    },
    
    getEditMode: function() {
        return this.editMode;
    },
    
    showEditModes: function(modes) {
        modes = modes || [];
        
        var me = this,
            i = 0,
            btn,
            len = modes.length;
        
        // If modes were passed in hide all by default so we can only show the
        // passed ones, otherwise if nothing was passed in show all
        me.down('#' + me.id + '-single')[len ? 'hide' : 'show']();
        me.down('#' + me.id + '-future')[len ? 'hide' : 'show']();
        me.down('#' + me.id + '-all')[len ? 'hide' : 'show']();
        
        for (; i < len; i++) {
            btn = me.down('#' + me.id + '-' + modes[i]);
            if (btn) {
                btn.show();
            }
        }
    },
    
    onToggle: function(btn) {
        var me = this,
            summaryEl = me.getComponent(me.id + '-summary').getEl();
        
        if (btn.itemId === me.id + '-single') {
            summaryEl.update(me.optionSingleDescription);
            me.editMode = me.editModes.SINGLE;
        }
        else if (btn.itemId === me.id + '-future') {
            summaryEl.update(me.optionFutureDescription);
            me.editMode = me.editModes.FUTURE;
        }
        else {
            summaryEl.update(me.optionAllDescription);
            me.editMode = me.editModes.ALL;
        }
    }
});Ext.define('Extensible.form.recurrence.RangeEditWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.extensible.recurrence-rangeeditwindow',
    singleton: true,
    
    requires: [
        'Extensible.form.recurrence.RangeEditPanel'
    ],
    
    // Locale configs
    title: 'Recurring Event Options',
    width: 350,
    height: 240,
    saveButtonText: 'Save',
    cancelButtonText: 'Cancel',
    
    // General configs
    closeAction: 'hide',
    modal: true,
    resizable: false,
    constrain: true,
    buttonAlign: 'right',
    layout: 'fit',
    
    formPanelConfig: {
        border: false
    },
    
    initComponent: function() {
        this.items = [{
            xtype: 'extensible.recurrence-rangeeditpanel',
            itemId: this.id + '-recur-panel'
        }];
        this.fbar = this.getFooterBarConfig();
        
        this.callParent(arguments);
    },
    
    getRangeEditPanel: function() {
        return this.down('#' + this.id + '-recur-panel');
    },
    
    /**
     * Configure the window and show it
     * @param {Object} options Valid properties: editModes[], callback, scope 
     */
    prompt: function(o) {
        this.callbackFunction = Ext.bind(o.callback, o.scope || this);
        this.getRangeEditPanel().showEditModes(o.editModes);
        this.show();
    },
    
    getFooterBarConfig: function() {
        var cfg = ['->', {
                text: this.saveButtonText,
                itemId: this.id + '-save-btn',
                disabled: false,
                handler: this.onSaveAction,
                scope: this
            },{
                text: this.cancelButtonText,
                itemId: this.id + '-cancel-btn',
                disabled: false,
                handler: this.onCancelAction,
                scope: this
            }];
        
        return cfg;
    },
    
    onSaveAction: function() {
        var mode = this.getComponent(this.id + '-recur-panel').getEditMode();
        this.callbackFunction(mode);
        this.close();
    },
    
    onCancelAction: function() {
        this.callbackFunction(false);
        this.close();
    }
});