/*!
 * Extensible 1.5.1
 * Copyright(c) 2010-2011 Extensible, LLC
 * licensing@ext.ensible.com
 * http://ext.ensible.com
 */
/**
 * @class Extensible.calendar.data.EventMappings
 * @extends Object
 * <p>A simple object that provides the field definitions for 
 * {@link Extensible.calendar.EventRecord EventRecord}s so that they can be easily overridden.</p>
 * 
 * <p>There are several ways of overriding the default Event record mappings to customize how 
 * Ext records are mapped to your back-end data model. If you only need to change a handful 
 * of field properties you can directly modify the EventMappings object as needed and then 
 * reconfigure it. The simplest approach is to only override specific field attributes:</p>
 * <pre><code>
var M = Extensible.calendar.data.EventMappings;
M.Title.mapping = 'evt_title';
M.Title.name = 'EventTitle';
Extensible.calendar.EventRecord.reconfigure();
</code></pre>
 * 
 * <p>You can alternately override an entire field definition using object-literal syntax, or 
 * provide your own custom field definitions (as in the following example). Note that if you do 
 * this, you <b>MUST</b> include a complete field definition, including the <tt>type</tt> attribute
 * if the field is not the default type of <tt>string</tt>.</p>
 * <pre><code>
// Add a new field that does not exist in the default EventMappings:
Extensible.calendar.data.EventMappings.Timestamp = {
    name: 'Timestamp',
    mapping: 'timestamp',
    type: 'date'
};
Extensible.calendar.EventRecord.reconfigure();
</code></pre>
 * 
 * <p>If you are overriding a significant number of field definitions it may be more convenient 
 * to simply redefine the entire EventMappings object from scratch. The following example
 * redefines the same fields that exist in the standard EventRecord object but the names and 
 * mappings have all been customized. Note that the name of each field definition object 
 * (e.g., 'EventId') should <b>NOT</b> be changed for the default EventMappings fields as it 
 * is the key used to access the field data programmatically.</p>
 * <pre><code>
Extensible.calendar.data.EventMappings = {
    EventId:     {name: 'ID', mapping:'evt_id', type:'int'},
    CalendarId:  {name: 'CalID', mapping: 'cal_id', type: 'int'},
    Title:       {name: 'EvtTitle', mapping: 'evt_title'},
    StartDate:   {name: 'StartDt', mapping: 'start_dt', type: 'date', dateFormat: 'c'},
    EndDate:     {name: 'EndDt', mapping: 'end_dt', type: 'date', dateFormat: 'c'},
    RRule:       {name: 'RecurRule', mapping: 'recur_rule'},
    Location:    {name: 'Location', mapping: 'location'},
    Notes:       {name: 'Desc', mapping: 'full_desc'},
    Url:         {name: 'LinkUrl', mapping: 'link_url'},
    IsAllDay:    {name: 'AllDay', mapping: 'all_day', type: 'boolean'},
    Reminder:    {name: 'Reminder', mapping: 'reminder'},
    
    // We can also add some new fields that do not exist in the standard EventRecord:
    CreatedBy:   {name: 'CreatedBy', mapping: 'created_by'},
    IsPrivate:   {name: 'Private', mapping:'private', type:'boolean'}
};
// Don't forget to reconfigure!
Extensible.calendar.EventRecord.reconfigure();
</code></pre>
 * 
 * <p><b>NOTE:</b> Any record reconfiguration you want to perform must be done <b>PRIOR to</b> 
 * initializing your data store, otherwise the changes will not be reflected in the store's records.</p>
 * 
 * <p>Another important note is that if you alter the default mapping for <tt>EventId</tt>, make sure to add
 * that mapping as the <tt>idProperty</tt> of your data reader, otherwise it won't recognize how to
 * access the data correctly and will treat existing records as phantoms. Here's an easy way to make sure
 * your mapping is always valid:</p>
 * <pre><code>
var reader = new Ext.data.JsonReader({
    totalProperty: 'total',
    successProperty: 'success',
    root: 'data',
    messageProperty: 'message',
    
    // read the id property generically, regardless of the mapping:
    idProperty: Extensible.calendar.data.EventMappings.EventId.mapping  || 'id',
    
    // this is also a handy way to configure your reader's fields generically:
    fields: Extensible.calendar.EventRecord.prototype.fields.getRange()
});
</code></pre>
 */
Ext.ns('Extensible.calendar.data');

Extensible.calendar.data.EventMappings = {
    EventId: {
        name:    'EventId',
        mapping: 'id',
        type:    'int'
    },
    CalendarId: {
        name:    'CalendarId',
        mapping: 'cid',
        type:    'int'
    },
    Title: {
        name:    'Title',
        mapping: 'title',
        type:    'string'
    },
    StartDate: {
        name:       'StartDate',
        mapping:    'start',
        type:       'date',
        dateFormat: 'c'
    },
    EndDate: {
        name:       'EndDate',
        mapping:    'end',
        type:       'date',
        dateFormat: 'c'
    },
    RRule: { // not currently used
        name:    'RecurRule', 
        mapping: 'rrule', 
        type:    'string' 
    },
    Location: {
        name:    'Location',
        mapping: 'loc',
        type:    'string'
    },
    Notes: {
        name:    'Notes',
        mapping: 'notes',
        type:    'string'
    },
    Url: {
        name:    'Url',
        mapping: 'url',
        type:    'string'
    },
    IsAllDay: {
        name:    'IsAllDay',
        mapping: 'ad',
        type:    'boolean'
    },
    Reminder: {
        name:    'Reminder',
        mapping: 'rem',
        type:    'string'
    }
};/**
 * @class Extensible.calendar.data.CalendarMappings
 * @extends Object
 * A simple object that provides the field definitions for 
 * {@link Extensible.calendar.data.CalendarModel CalendarRecord}s so that they can be easily overridden.
 * 
 * <p>There are several ways of overriding the default Calendar record mappings to customize how 
 * Ext records are mapped to your back-end data model. If you only need to change a handful 
 * of field properties you can directly modify the CalendarMappings object as needed and then 
 * reconfigure it. The simplest approach is to only override specific field attributes:</p>
 * <pre><code>
var M = Extensible.calendar.data.CalendarMappings;
M.Title.mapping = 'cal_title';
M.Title.name = 'CalTitle';
Extensible.calendar.data.CalendarModel.reconfigure();
</code></pre>
 * 
 * <p>You can alternately override an entire field definition using object-literal syntax, or 
 * provide your own custom field definitions (as in the following example). Note that if you do 
 * this, you <b>MUST</b> include a complete field definition, including the <tt>type</tt> attribute
 * if the field is not the default type of <tt>string</tt>.</p>
 * <pre><code>
// Add a new field that does not exist in the default CalendarMappings:
Extensible.calendar.data.CalendarMappings.Owner = {
    name: 'Owner',
    mapping: 'owner',
    type: 'string'
};
Extensible.calendar.data.CalendarModel.reconfigure();
</code></pre>
 * 
 * <p>If you are overriding a significant number of field definitions it may be more convenient 
 * to simply redefine the entire CalendarMappings object from scratch. The following example
 * redefines the same fields that exist in the standard CalendarRecord object but the names and 
 * mappings have all been customized. Note that the name of each field definition object 
 * (e.g., 'CalendarId') should <b>NOT</b> be changed for the default CalendarMappings fields as it 
 * is the key used to access the field data programmatically.</p>
 * <pre><code>
Extensible.calendar.data.CalendarMappings = {
    CalendarId:   {name:'ID', mapping: 'id', type: 'int'},
    Title:        {name:'CalTitle', mapping: 'title', type: 'string'},
    Description:  {name:'Desc', mapping: 'desc', type: 'string'},
    ColorId:      {name:'Color', mapping: 'color', type: 'int'},
    IsHidden:     {name:'Hidden', mapping: 'hidden', type: 'boolean'},
    
    // We can also add some new fields that do not exist in the standard CalendarRecord:
    Owner:        {name: 'Owner', mapping: 'owner'}
};
// Don't forget to reconfigure!
Extensible.calendar.data.CalendarModel.reconfigure();
</code></pre>
 * 
 * <p><b>NOTE:</b> Any record reconfiguration you want to perform must be done <b>PRIOR to</b> 
 * initializing your data store, otherwise the changes will not be reflected in the store's records.</p>
 * 
 * <p>Another important note is that if you alter the default mapping for <tt>CalendarId</tt>, make sure to add
 * that mapping as the <tt>idProperty</tt> of your data reader, otherwise it won't recognize how to
 * access the data correctly and will treat existing records as phantoms. Here's an easy way to make sure
 * your mapping is always valid:</p>
 * <pre><code>
var reader = new Ext.data.JsonReader({
    totalProperty: 'total',
    successProperty: 'success',
    root: 'data',
    messageProperty: 'message',
    
    // read the id property generically, regardless of the mapping:
    idProperty: Extensible.calendar.data.CalendarMappings.CalendarId.mapping  || 'id',
    
    // this is also a handy way to configure your reader's fields generically:
    fields: Extensible.calendar.data.CalendarModel.prototype.fields.getRange()
});
</code></pre>
 */
Ext.ns('Extensible.calendar.data');

Extensible.calendar.data.CalendarMappings = {
    CalendarId: {
        name:    'CalendarId',
        mapping: 'id',
        type:    'int'
    },
    Title: {
        name:    'Title',
        mapping: 'title',
        type:    'string'
    },
    Description: {
        name:    'Description', 
        mapping: 'desc',   
        type:    'string' 
    },
    ColorId: {
        name:    'ColorId',
        mapping: 'color',
        type:    'int'
    },
    IsHidden: {
        name:    'IsHidden',
        mapping: 'hidden',
        type:    'boolean'
    }
};/**
 * @class Extensible.calendar.template.BoxLayout
 * @extends Ext.XTemplate
 * <p>This is the template used to render calendar views based on small day boxes within a non-scrolling container (currently
 * the {@link Extensible.calendar.view.Month MonthView} and the all-day headers for {@link Extensible.calendar.view.Day DayView} and 
 * {@link Extensible.calendar.view.Week WeekView}. This template is automatically bound to the underlying event store by the 
 * calendar components and expects records of type {@link Extensible.calendar.data.EventModel}.</p>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Extensible.calendar.template.BoxLayout', {
    extend: 'Ext.XTemplate',
    
    requires: ['Ext.Date'],
    
    /**
     * @cfg {String} firstWeekDateFormat
     * The date format used for the day boxes in the first week of the view only (subsequent weeks
     * use the {@link #otherWeeksDateFormat} config). Defaults to 'D j'. Note that if the day names header is displayed
     * above the first row (e.g., {@link Extensible.calendar.view.Month#showHeader MonthView.showHeader} = true)
     * then this value is ignored and {@link #otherWeeksDateFormat} will be used instead.
     */
    firstWeekDateFormat: 'D j',
    /**
     * @cfg {String} otherWeeksDateFormat
     * The date format used for the date in day boxes (other than the first week, which is controlled by
     * {@link #firstWeekDateFormat}). Defaults to 'j'.
     */
    otherWeeksDateFormat: 'j',
    /**
     * @cfg {String} singleDayDateFormat
     * The date format used for the date in the header when in single-day view (defaults to 'l, F j, Y').
     */
    singleDayDateFormat: 'l, F j, Y',
    /**
     * @cfg {String} multiDayFirstDayFormat
     * The date format used for the date in the header when more than one day are visible (defaults to 'M j, Y').
     */
    multiDayFirstDayFormat: 'M j, Y',
    /**
     * @cfg {String} multiDayMonthStartFormat
     * The date format to use for the first day in a month when more than one day are visible (defaults to 'M j').
     * Note that if this day falls on the first day within the view, {@link #multiDayFirstDayFormat} takes precedence.
     */
    multiDayMonthStartFormat: 'M j',
    
    // private
    constructor: function(config){
        
        Ext.apply(this, config);
    
        var weekLinkTpl = this.showWeekLinks ? '<div id="{weekLinkId}" class="ext-cal-week-link">{weekNum}</div>' : '';
        
        Extensible.calendar.template.BoxLayout.superclass.constructor.call(this,
            '<tpl for="weeks">',
                '<div id="{[this.id]}-wk-{[xindex-1]}" class="ext-cal-wk-ct" style="top:{[this.getRowTop(xindex, xcount)]}%; height:{[this.getRowHeight(xcount)]}%;">',
                    weekLinkTpl,
                    '<table class="ext-cal-bg-tbl" cellpadding="0" cellspacing="0">',
                        '<tbody>',
                            '<tr>',
                                '<tpl for=".">',
                                     '<td id="{[this.id]}-day-{date:date("Ymd")}" class="{cellCls}">&#160;</td>',
                                '</tpl>',
                            '</tr>',
                        '</tbody>',
                    '</table>',
                    '<table class="ext-cal-evt-tbl" cellpadding="0" cellspacing="0">',
                        '<tbody>',
                            '<tr>',
                                '<tpl for=".">',
                                    '<td id="{[this.id]}-ev-day-{date:date("Ymd")}" class="{titleCls}"><div>{title}</div></td>',
                                '</tpl>',
                            '</tr>',
                        '</tbody>',
                    '</table>',
                '</div>',
            '</tpl>', {
                getRowTop: function(i, ln){
                    return ((i-1)*(100/ln));
                },
                getRowHeight: function(ln){
                    return 100/ln;
                }
            }
        );
    },
    
    // private
    applyTemplate : function(o){
        
        Ext.apply(this, o);
        
        var w = 0, title = '', first = true, isToday = false, showMonth = false, 
            prevMonth = false, nextMonth = false, isWeekend = false,
            weekendCls = o.weekendCls,
            prevMonthCls = o.prevMonthCls,
            nextMonthCls = o.nextMonthCls,
            todayCls = o.todayCls,
            weeks = [[]],
            today = Extensible.Date.today(),
            dt = Ext.Date.clone(this.viewStart),
            thisMonth = this.startDate.getMonth();
        
        for(; w < this.weekCount || this.weekCount == -1; w++){
            if(dt > this.viewEnd){
                break;
            }
            weeks[w] = [];
            
            for(var d = 0; d < this.dayCount; d++){
                isToday = dt.getTime() === today.getTime();
                showMonth = first || (dt.getDate() == 1);
                prevMonth = (dt.getMonth() < thisMonth) && this.weekCount == -1;
                nextMonth = (dt.getMonth() > thisMonth) && this.weekCount == -1;
                isWeekend = dt.getDay() % 6 === 0;
                
                if(dt.getDay() == 1){
                    // The ISO week format 'W' is relative to a Monday week start. If we
                    // make this check on Sunday the week number will be off.
                    weeks[w].weekNum = this.showWeekNumbers ? Ext.Date.format(dt, 'W') : '&#160;';
                    weeks[w].weekLinkId = 'ext-cal-week-'+Ext.Date.format(dt, 'Ymd');
                }
                
                if(showMonth){
                    if(isToday){
                        title = this.getTodayText();
                    }
                    else{
                        title = Ext.Date.format(dt, this.dayCount == 1 ? this.singleDayDateFormat : 
                                (first ? this.multiDayFirstDayFormat : this.multiDayMonthStartFormat));
                    }
                }
                else{
                    var dayFmt = (w == 0 && this.showHeader !== true) ? this.firstWeekDateFormat : this.otherWeeksDateFormat;
                    title = isToday ? this.getTodayText() : Ext.Date.format(dt, dayFmt);
                }
                
                weeks[w].push({
                    title: title,
                    date: Ext.Date.clone(dt),
                    titleCls: 'ext-cal-dtitle ' + (isToday ? ' ext-cal-dtitle-today' : '') + 
                        (w==0 ? ' ext-cal-dtitle-first' : '') +
                        (prevMonth ? ' ext-cal-dtitle-prev' : '') + 
                        (nextMonth ? ' ext-cal-dtitle-next' : ''),
                    cellCls: 'ext-cal-day ' + (isToday ? ' '+todayCls : '') + 
                        (d==0 ? ' ext-cal-day-first' : '') +
                        (prevMonth ? ' '+prevMonthCls : '') +
                        (nextMonth ? ' '+nextMonthCls : '') +
                        (isWeekend && weekendCls ? ' '+weekendCls : '')
                });
                dt = Extensible.Date.add(dt, {days: 1});
                first = false;
            }
        }
        
        if (Ext.getVersion().isLessThan('4.1')) {
            return Extensible.calendar.template.BoxLayout.superclass.applyTemplate.call(this, {
                weeks: weeks
            });
        }
        else {
            return this.applyOut({
                weeks: weeks
            }, []).join('');
        }
    },
    
    // private
    getTodayText : function(){
        var timeFmt = Extensible.Date.use24HourTime ? 'G:i ' : 'g:ia ',
            todayText = this.showTodayText !== false ? this.todayText : '',
            timeText = this.showTime !== false ? ' <span id="'+this.id+'-clock" class="ext-cal-dtitle-time" aria-live="off">' + 
                    Ext.Date.format(new Date(), timeFmt) + '</span>' : '',
            separator = todayText.length > 0 || timeText.length > 0 ? ' &#8212; ' : ''; // &#8212; == &mdash;
        
        if(this.dayCount == 1){
            return Ext.Date.format(new Date(), this.singleDayDateFormat) + separator + todayText + timeText;
        }
        fmt = this.weekCount == 1 ? this.firstWeekDateFormat : this.otherWeeksDateFormat;
        return todayText.length > 0 ? todayText + timeText : Ext.Date.format(new Date(), fmt) + timeText;
    }
}, 
function() {
    this.createAlias('apply', 'applyTemplate');
});/**
 * @class Extensible.calendar.template.DayHeader
 * @extends Ext.XTemplate
 * <p>This is the template used to render the all-day event container used in {@link Extensible.calendar.view.Day DayView} and 
 * {@link Extensible.calendar.view.Week WeekView}. Internally the majority of the layout logic is deferred to an instance of
 * {@link Extensible.calendar.template.BoxLayout}.</p> 
 * <p>This template is automatically bound to the underlying event store by the 
 * calendar components and expects records of type {@link Extensible.calendar.data.EventModel}.</p>
 * <p>Note that this template would not normally be used directly. Instead you would use the {@link Extensible.calendar.view.DayTemplate}
 * that internally creates an instance of this template along with a {@link Extensible.calendar.template.DayBody}.</p>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Extensible.calendar.template.DayHeader', {
    extend: 'Ext.XTemplate',
    
    requires: ['Extensible.calendar.template.BoxLayout'],
    
    // private
    constructor: function(config){
        
        Ext.apply(this, config);
    
        this.allDayTpl = Ext.create('Extensible.calendar.template.BoxLayout', config);
        this.allDayTpl.compile();
        
        Extensible.calendar.template.DayHeader.superclass.constructor.call(this,
            '<div class="ext-cal-hd-ct">',
                '<table class="ext-cal-hd-days-tbl" cellspacing="0" cellpadding="0">',
                    '<tbody>',
                        '<tr>',
                            '<td class="ext-cal-gutter"></td>',
                            '<td class="ext-cal-hd-days-td"><div class="ext-cal-hd-ad-inner">{allDayTpl}</div></td>',
                            '<td class="ext-cal-gutter-rt"></td>',
                        '</tr>',
                    '</tbody>',
                '</table>',
            '</div>'
        );
    },
    
    // private
    applyTemplate : function(o){
        var templateConfig = {
            allDayTpl: this.allDayTpl.apply(o)
        };
         
        if (Ext.getVersion().isLessThan('4.1')) {
            return Extensible.calendar.template.DayHeader.superclass.applyTemplate.call(this, templateConfig);
        }
        else {
            return this.applyOut(templateConfig, []).join('');
        }
    }
}, 
function() {
    this.createAlias('apply', 'applyTemplate');
});/**
 * @class Extensible.calendar.template.DayBody
 * @extends Ext.XTemplate
 * <p>This is the template used to render the scrolling body container used in {@link Extensible.calendar.view.Day DayView} and 
 * {@link Extensible.calendar.view.Week WeekView}. This template is automatically bound to the underlying event store by the 
 * calendar components and expects records of type {@link Extensible.calendar.data.EventModel}.</p>
 * <p>Note that this template would not normally be used directly. Instead you would use the {@link Extensible.calendar.view.DayTemplate}
 * that internally creates an instance of this template along with a {@link Extensible.calendar.DayHeaderTemplate}.</p>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Extensible.calendar.template.DayBody', {
    extend: 'Ext.XTemplate',
    
    // private
    constructor: function(config){
        
        Ext.apply(this, config);
    
        Extensible.calendar.template.DayBody.superclass.constructor.call(this,
            '<table class="ext-cal-bg-tbl" cellspacing="0" cellpadding="0" style="height:{dayHeight}px;">',
                '<tbody>',
                    '<tr height="1">',
                        '<td class="ext-cal-gutter"></td>',
                        '<td colspan="{dayCount}">',
                            '<div class="ext-cal-bg-rows">',
                                '<div class="ext-cal-bg-rows-inner">',
                                    '<tpl for="times">',
                                        '<div class="ext-cal-bg-row ext-row-{[xindex]}" style="height:{parent.hourHeight}px;">',
                                            '<div class="ext-cal-bg-row-div {parent.hourSeparatorCls}" style="height:{parent.hourSeparatorHeight}px;"></div>',
                                        '</div>',
                                    '</tpl>',
                                '</div>',
                            '</div>',
                        '</td>',
                    '</tr>',
                    '<tr>',
                        '<td class="ext-cal-day-times">',
                            '<tpl for="times">',
                                '<div class="ext-cal-bg-row" style="height:{parent.hourHeight}px;">',
                                    '<div class="ext-cal-day-time-inner"  style="height:{parent.hourHeight-1}px;">{.}</div>',
                                '</div>',
                            '</tpl>',
                        '</td>',
                        '<tpl for="days">',
                            '<td class="ext-cal-day-col">',
                                '<div class="ext-cal-day-col-inner">',
                                    '<div id="{[this.id]}-day-col-{.:date("Ymd")}" class="ext-cal-day-col-gutter" style="height:{parent.dayHeight}px;"></div>',
                                '</div>',
                            '</td>',
                        '</tpl>',
                    '</tr>',
                '</tbody>',
            '</table>'
        );
    },

    // private
    applyTemplate : function(o){
        this.today = Extensible.Date.today();
        this.dayCount = this.dayCount || 1;
        
        var i = 0, days = [],
            dt = Ext.Date.clone(o.viewStart);
            
        for(; i<this.dayCount; i++){
            days[i] = Extensible.Date.add(dt, {days: i});
        }

        var times = [],
            start = this.viewStartHour,
            end = this.viewEndHour,
            mins = this.hourIncrement,
            dayHeight = this.hourHeight * (end - start),
            fmt = Extensible.Date.use24HourTime ? 'G:i' : 'ga',
            templateConfig;
        
        // use a fixed DST-safe date so times don't get skipped on DST boundaries
        dt = Extensible.Date.add(new Date('5/26/1972'), {hours: start});
        
        for(i=start; i<end; i++){
            times.push(Ext.Date.format(dt, fmt));
            dt = Extensible.Date.add(dt, {minutes: mins});
        }

        templateConfig = {
            days: days,
            dayCount: days.length,
            times: times,
            hourHeight: this.hourHeight,
            hourSeparatorCls: this.showHourSeparator ? '' : 'no-sep', // the class suppresses the default separator
            dayHeight: dayHeight,
            hourSeparatorHeight: (this.hourHeight / 2)
        };
         
        if (Ext.getVersion().isLessThan('4.1')) {
            return Extensible.calendar.template.DayBody.superclass.applyTemplate.call(this, templateConfig);
        }
        else {
            return this.applyOut(templateConfig, []).join('');
        }
    }
}, 
function() {
    this.createAlias('apply', 'applyTemplate');
});/**
 * @class Extensible.calendar.template.Month
 * @extends Ext.XTemplate
 * <p>This is the template used to render the {@link Extensible.calendar.view.Month MonthView}. Internally this class defers to an
 * instance of {@link Ext.calerndar.BoxLayoutTemplate} to handle the inner layout rendering and adds containing elements around
 * that to form the month view.</p> 
 * <p>This template is automatically bound to the underlying event store by the 
 * calendar components and expects records of type {@link Extensible.calendar.data.EventModel}.</p>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Extensible.calendar.template.Month', {
    extend: 'Ext.XTemplate',
    
    requires: ['Extensible.calendar.template.BoxLayout'],
    
    /**
     * @cfg {String} dayHeaderFormat
     * The date format to use for day headers, if used (defaults to 'D', e.g. 'Mon' for Monday)
     */
    dayHeaderFormat: 'D',
    /**
     * @cfg {String} dayHeaderTitleFormat
     * The date format to use for the day header's HTML title attribute displayed on mouseover 
     * (defaults to 'l, F j, Y', e.g. 'Monday, December 27, 2010')
     */
    dayHeaderTitleFormat: 'l, F j, Y',
    
    // private
    constructor: function(config){
        
        Ext.apply(this, config);
    
        this.weekTpl = Ext.create('Extensible.calendar.template.BoxLayout', config);
        this.weekTpl.compile();
        
        var weekLinkTpl = this.showWeekLinks ? '<div class="ext-cal-week-link-hd">&#160;</div>' : '';
        
        Extensible.calendar.template.Month.superclass.constructor.call(this,
    	    '<div class="ext-cal-inner-ct {extraClasses}">',
                '<div class="ext-cal-hd-ct ext-cal-month-hd">',
                    weekLinkTpl,
    		        '<table class="ext-cal-hd-days-tbl" cellpadding="0" cellspacing="0">',
    		            '<tbody>',
                            '<tr>',
                                '<tpl for="days">',
    		                        '<th class="ext-cal-hd-day{[xindex==1 ? " ext-cal-day-first" : ""]}" title="{title}">{name}</th>',
    		                    '</tpl>',
                            '</tr>',
    		            '</tbody>',
    		        '</table>',
                '</div>',
    	        '<div class="ext-cal-body-ct">{weeks}</div>',
            '</div>'
        );
    },
    
    // private
    applyTemplate : function(o){
        var days = [],
            weeks = this.weekTpl.apply(o),
            dt = o.viewStart,
            D = Extensible.Date,
            templateConfig;
        
        for(var i = 0; i < 7; i++){
            var d = D.add(dt, {days: i});
            days.push({
                name: Ext.Date.format(d, this.dayHeaderFormat),
                title: Ext.Date.format(d, this.dayHeaderTitleFormat)
            });
        }
        
        var extraClasses = this.showHeader === true ? '' : 'ext-cal-noheader';
        if(this.showWeekLinks){
            extraClasses += ' ext-cal-week-links';
        }
        
        templateConfig = {
            days: days,
            weeks: weeks,
            extraClasses: extraClasses
        };
         
        if (Ext.getVersion().isLessThan('4.1')) {
            return Extensible.calendar.template.Month.superclass.applyTemplate.call(this, templateConfig);
        }
        else {
            return this.applyOut(templateConfig, []).join('');
        }
    }
}, 
function() {
    this.createAlias('apply', 'applyTemplate');
});/*
 * @class Ext.dd.ScrollManager
 * <p>Provides automatic scrolling of overflow regions in the page during drag operations.</p>
 * <p>The ScrollManager configs will be used as the defaults for any scroll container registered with it,
 * but you can also override most of the configs per scroll container by adding a
 * <tt>ddScrollConfig</tt> object to the target element that contains these properties: {@link #hthresh},
 * {@link #vthresh}, {@link #increment} and {@link #frequency}.  Example usage:
 * <pre><code>
var el = Ext.get('scroll-ct');
el.ddScrollConfig = {
    vthresh: 50,
    hthresh: -1,
    frequency: 100,
    increment: 200
};
Ext.dd.ScrollManager.register(el);
</code></pre>
 * <b>Note: This class uses "Point Mode" and is untested in "Intersect Mode".</b>
 * @singleton
 */
Ext.define('Ext.dd.ScrollManager', {
    singleton: true,
    requires: [
        'Ext.dd.DragDropManager'
    ],

    constructor: function() {
        var ddm = Ext.dd.DragDropManager;
        ddm.fireEvents = Ext.Function.createSequence(ddm.fireEvents, this.onFire, this);
        ddm.stopDrag = Ext.Function.createSequence(ddm.stopDrag, this.onStop, this);
        this.doScroll = Ext.Function.bind(this.doScroll, this);
        this.ddmInstance = ddm;
        this.els = {};
        this.dragEl = null;
        this.proc = {};
    },

    onStop: function(e){
//        var sm = Ext.dd.ScrollManager;
//        sm.dragEl = null;
//        sm.clearProc();
        this.dragEl = null;
        this.clearProc();
    },

    triggerRefresh: function() {
        if (this.ddmInstance.dragCurrent) {
            this.ddmInstance.refreshCache(this.ddmInstance.dragCurrent.groups);
        }
    },

    doScroll: function() {
        if (this.ddmInstance.dragCurrent) {
            var proc   = this.proc,
                procEl = proc.el,
                ddScrollConfig = proc.el.ddScrollConfig,
                inc = ddScrollConfig ? ddScrollConfig.increment : this.increment;

            if (!this.animate) {
                if (procEl.scroll(proc.dir, inc)) {
                    this.triggerRefresh();
                }
            } else {
                procEl.scroll(proc.dir, inc, true, this.animDuration, this.triggerRefresh);
            }
        }
    },

    clearProc: function() {
        var proc = this.proc;
        if (proc.id) {
            clearInterval(proc.id);
        }
        proc.id = 0;
        proc.el = null;
        proc.dir = "";
    },

    startProc: function(el, dir) {
        this.clearProc();
        this.proc.el = el;
        this.proc.dir = dir;
        var group = el.ddScrollConfig ? el.ddScrollConfig.ddGroup : undefined,
            freq  = (el.ddScrollConfig && el.ddScrollConfig.frequency)
                  ? el.ddScrollConfig.frequency
                  : this.frequency;

        if (group === undefined || this.ddmInstance.dragCurrent.ddGroup == group) {
            this.proc.id = setInterval(this.doScroll, freq);
        }
    },

    onFire: function(e, isDrop) {
        if (isDrop || !this.ddmInstance.dragCurrent) {
            return;
        }
        if (!this.dragEl || this.dragEl != this.ddmInstance.dragCurrent) {
            this.dragEl = this.ddmInstance.dragCurrent;
            // refresh regions on drag start
            this.refreshCache();
        }

        var xy = e.getXY(),
            pt = e.getPoint(),
            proc = this.proc,
            els = this.els;

        for (var id in els) {
            var el = els[id], r = el._region;
            var c = el.ddScrollConfig ? el.ddScrollConfig : this;
            if (r && r.contains(pt) && el.isScrollable()) {
                if (r.bottom - pt.y <= c.vthresh) {
                    if(proc.el != el){
                        this.startProc(el, "down");
                    }
                    return;
                }else if (r.right - pt.x <= c.hthresh) {
                    if (proc.el != el) {
                        this.startProc(el, "left");
                    }
                    return;
                } else if(pt.y - r.top <= c.vthresh) {
                    if (proc.el != el) {
                        this.startProc(el, "up");
                    }
                    return;
                } else if(pt.x - r.left <= c.hthresh) {
                    if (proc.el != el) {
                        this.startProc(el, "right");
                    }
                    return;
                }
            }
        }
        this.clearProc();
    },

    /**
     * Registers new overflow element(s) to auto scroll
     * @param {Mixed/Array} el The id of or the element to be scrolled or an array of either
     */
    register : function(el){
        if (Ext.isArray(el)) {
            for(var i = 0, len = el.length; i < len; i++) {
                    this.register(el[i]);
            }
        } else {
            el = Ext.get(el);
            this.els[el.id] = el;
        }
    },

    /**
     * Unregisters overflow element(s) so they are no longer scrolled
     * @param {Mixed/Array} el The id of or the element to be removed or an array of either
     */
    unregister : function(el){
        if(Ext.isArray(el)){
            for (var i = 0, len = el.length; i < len; i++) {
                this.unregister(el[i]);
            }
        }else{
            el = Ext.get(el);
            delete this.els[el.id];
        }
    },

    /**
     * The number of pixels from the top or bottom edge of a container the pointer needs to be to
     * trigger scrolling (defaults to 25)
     * @type Number
     */
    vthresh : 25,
    /**
     * The number of pixels from the right or left edge of a container the pointer needs to be to
     * trigger scrolling (defaults to 25)
     * @type Number
     */
    hthresh : 25,

    /**
     * The number of pixels to scroll in each scroll increment (defaults to 100)
     * @type Number
     */
    increment : 100,

    /**
     * The frequency of scrolls in milliseconds (defaults to 500)
     * @type Number
     */
    frequency : 500,

    /**
     * True to animate the scroll (defaults to true)
     * @type Boolean
     */
    animate: true,

    /**
     * The animation duration in seconds -
     * MUST BE less than Ext.dd.ScrollManager.frequency! (defaults to .4)
     * @type Number
     */
    animDuration: 0.4,

    /**
     * The named drag drop {@link Ext.dd.DragSource#ddGroup group} to which this container belongs (defaults to undefined).
     * If a ddGroup is specified, then container scrolling will only occur when a dragged object is in the same ddGroup.
     * @type String
     */
    ddGroup: undefined,

    /**
     * Manually trigger a cache refresh.
     */
    refreshCache : function(){
        var els = this.els,
            id;
        for (id in els) {
            if(typeof els[id] == 'object'){ // for people extending the object prototype
                els[id]._region = els[id].getRegion();
            }
        }
    }
});
/**
 * @class Extensible.calendar.dd.StatusProxy
 * A specialized drag proxy that supports a drop status icon, {@link Ext.Layer} styles and auto-repair. It also
 * contains a calendar-specific drag status message containing details about the dragged event's target drop date range.  
 * This is the default drag proxy used by all calendar views.
 * @constructor
 * @param {Object} config
 */
Ext.define('Extensible.calendar.dd.StatusProxy', {
    extend: 'Ext.dd.StatusProxy',
    
    /**
     * @cfg {String} moveEventCls
     * The CSS class to apply to the status element when an event is being dragged (defaults to 'ext-cal-dd-move').
     */
    moveEventCls : 'ext-cal-dd-move',
    /**
     * @cfg {String} addEventCls
     * The CSS class to apply to the status element when drop is not allowed (defaults to 'ext-cal-dd-add').
     */
    addEventCls : 'ext-cal-dd-add',

    // Overridden to add a separate message element inside the ghost area.
    // Applies only to Ext 4.1 and above, see notes in constructor
    renderTpl: [
        '<div class="' + Ext.baseCSSPrefix + 'dd-drop-icon"></div>',
        '<div class="ext-dd-ghost-ct">',
            '<div id="{id}-ghost" class="' + Ext.baseCSSPrefix + 'dd-drag-ghost"></div>',
            '<div id="{id}-message" class="ext-dd-msg"></div>',
        '</div>'
    ],
    
    // private -- applies only to Ext 4.1 and above, see notes in constructor
    childEls: [
        'ghost',
        'message'
    ],
    
    // private
    constructor: function(config) {
        // In Ext 4.0.x StatusProxy was a plain class that did not inherit from Component,
        // and all of its els were rendered inside the constructor. Unfortunately, because
        // of this none of the standard Component lifecycle methods apply and so we are left
        // with manually overriding the entire constructor function to inject our custom
        // markup and set up our references.
        //
        // In 4.1 StatusProxy was switched to inherit from Component, so the renderTpl and 
        // renderSelectors configs will kick in and generate the proper elements and refs
        // automagically, and will be ignored by 4.0.x.
        if (Ext.getVersion().isLessThan('4.1')) {
            this.preComponentConstructor(config);
        }
        else {
            this.callParent(arguments);
        }
    },
    
    // private -- applies only to Ext <4.1, see notes in constructor
    preComponentConstructor: function(config) {
        var me = this;
        
        Ext.apply(me, config);
        
        me.id = me.id || Ext.id();
        me.proxy = Ext.createWidget('component', {
            floating: true,
            id: me.id || Ext.id(),
            html: me.renderTpl.join(''),
            cls: Ext.baseCSSPrefix + 'dd-drag-proxy ' + me.dropNotAllowed,
            shadow: !config || config.shadow !== false,
            renderTo: document.body
        });
 
        me.el = me.proxy.el;
        me.el.show();
        me.el.setVisibilityMode(Ext.core.Element.VISIBILITY);
        me.el.hide();
 
        me.ghost = Ext.get(me.el.dom.childNodes[1].childNodes[0]);
        me.message = Ext.get(me.el.dom.childNodes[1].childNodes[1]);
        me.dropStatus = me.dropNotAllowed;
    },
    
    // inherit docs
    update : function(html){
        this.callParent(arguments);
        
        // If available, set the ghosted event el to autoHeight for visual consistency
        var el = this.ghost.dom.firstChild;
        if(el){
            Ext.fly(el).setHeight('auto');
        }
    },
    
    /* @private
     * Update the calendar-specific drag status message without altering the ghost element.
     * @param {String} msg The new status message
     */
    updateMsg : function(msg){
        this.message.update(msg);
    }
});/* @private
 * Internal drag zone implementation for the calendar components. This provides base functionality
 * and is primarily for the month view -- DayViewDD adds day/week view-specific functionality.
 */
Ext.define('Extensible.calendar.dd.DragZone', {
    extend: 'Ext.dd.DragZone',
    
    requires: [
        'Extensible.calendar.dd.StatusProxy',
        'Extensible.calendar.data.EventMappings'
    ],
    
    ddGroup : 'CalendarDD',
    eventSelector : '.ext-cal-evt',
    
    constructor : function(el, config){
        if(!Extensible.calendar._statusProxyInstance){
            Extensible.calendar._statusProxyInstance = Ext.create('Extensible.calendar.dd.StatusProxy');
        }
        this.proxy = Extensible.calendar._statusProxyInstance;
        this.callParent(arguments);
    },
    
    getDragData : function(e){
        // Check whether we are dragging on an event first
        var t = e.getTarget(this.eventSelector, 3);
        if(t){
            var rec = this.view.getEventRecordFromEl(t);
            if(!rec){
                // if rec is null here it usually means there was a timing issue between drag 
                // start and the browser reporting it properly. Simply ignore and it will 
                // resolve correctly once the browser catches up.
                return;
            }
            return {
                type: 'eventdrag',
                ddel: t,
                eventStart: rec.data[Extensible.calendar.data.EventMappings.StartDate.name],
                eventEnd: rec.data[Extensible.calendar.data.EventMappings.EndDate.name],
                proxy: this.proxy
            };
        }
        
        // If not dragging an event then we are dragging on 
        // the calendar to add a new event
        t = this.view.getDayAt(e.getX(), e.getY());
        if(t.el){
            return {
                type: 'caldrag',
                start: t.date,
                proxy: this.proxy
            };
        }
        return null;
    },
    
    onInitDrag : function(x, y){
        if(this.dragData.ddel){
            var ghost = this.dragData.ddel.cloneNode(true),
                child = Ext.fly(ghost).down('dl');
            
            Ext.fly(ghost).setWidth('auto');
            
            if(child){
                // for IE/Opera
                child.setHeight('auto');
            }
            this.proxy.update(ghost);
            this.onStartDrag(x, y);
        }
        else if(this.dragData.start){
            this.onStartDrag(x, y);
        }
        this.view.onInitDrag();
        return true;
    },
    
    afterRepair : function(){
        if(Ext.enableFx && this.dragData.ddel){
            Ext.Element.fly(this.dragData.ddel).highlight(this.hlColor || 'c3daf9');
        }
        this.dragging = false;
    },
    
    getRepairXY : function(e){
        if(this.dragData.ddel){
            return Ext.Element.fly(this.dragData.ddel).getXY();
        }
    },
    
    afterInvalidDrop : function(e, id){
        Ext.select('.ext-dd-shim').hide();
    },
    
    destroy : function(){
        this.callParent(arguments);
        delete Extensible.calendar._statusProxyInstance;
    }    
});/* @private
 * Internal drop zone implementation for the calendar components. This provides base functionality
 * and is primarily for the month view -- DayViewDD adds day/week view-specific functionality.
 */
Ext.define('Extensible.calendar.dd.DropZone', {
    extend: 'Ext.dd.DropZone',
    
    requires: [
        'Ext.Layer',
        'Extensible.calendar.data.EventMappings'
    ],
    
    ddGroup : 'CalendarDD',
    eventSelector : '.ext-cal-evt',
    dateRangeFormat : '{0}-{1}',
    dateFormat : 'n/j',
    
    // private
    shims : [],
    
    getTargetFromEvent : function(e){
        var dragOffset = this.dragOffset || 0,
            y = e.getPageY() - dragOffset,
            d = this.view.getDayAt(e.getPageX(), y);
        
        return d.el ? d : null;
    },
    
    onNodeOver : function(n, dd, e, data){
        var D = Extensible.Date,
            start = data.type == 'eventdrag' ? n.date : D.min(data.start, n.date),
            end = data.type == 'eventdrag' ? D.add(n.date, {days: D.diffDays(data.eventStart, data.eventEnd)}) :
                D.max(data.start, n.date);
        
        if(!this.dragStartDate || !this.dragEndDate || (D.diffDays(start, this.dragStartDate) != 0) || (D.diffDays(end, this.dragEndDate) != 0)){
            this.dragStartDate = start;
            this.dragEndDate = D.add(end, {days: 1, millis: -1, clearTime: true});
            this.shim(start, end);
            
            var range = Ext.Date.format(start, this.dateFormat);
                
            if(D.diffDays(start, end) > 0){
                end = Ext.Date.format(end, this.dateFormat);
                range = Ext.String.format(this.dateRangeFormat, range, end);
            }
            var msg = Ext.String.format(data.type == 'eventdrag' ? this.moveText : this.createText, range);
            data.proxy.updateMsg(msg);
        }
        return this.dropAllowed;
    },
    
    shim : function(start, end){
        this.currWeek = -1;
        var dt = Ext.Date.clone(start),
            i = 0, shim, box,
            D = Extensible.Date,
            cnt = D.diffDays(dt, end) + 1;
        
        Ext.each(this.shims, function(shim){
            if(shim){
                shim.isActive = false;
            }
        });
        
        while(i++ < cnt){
            var dayEl = this.view.getDayEl(dt);
            
            // if the date is not in the current view ignore it (this
            // can happen when an event is dragged to the end of the
            // month so that it ends outside the view)
            if(dayEl){
                var wk = this.view.getWeekIndex(dt),
                    shim = this.shims[wk];
            
                if(!shim){
                    shim = this.createShim();
                    this.shims[wk] = shim;
                }
                if(wk != this.currWeek){
                    shim.boxInfo = dayEl.getBox();
                    this.currWeek = wk;
                }
                else{
                    box = dayEl.getBox();
                    shim.boxInfo.right = box.right;
                    shim.boxInfo.width = box.right - shim.boxInfo.x;
                }
                shim.isActive = true;
            }
            dt = D.add(dt, {days: 1});
        }
        
        Ext.each(this.shims, function(shim){
            if(shim){
                if(shim.isActive){
                    shim.show();
                    shim.setBox(shim.boxInfo);
                }
                else if(shim.isVisible()){
                    shim.hide();
                }
            }
        });
    },
    
    createShim : function(){
        var owner = this.view.ownerCalendarPanel ? this.view.ownerCalendarPanel : this.view;
        if(!this.shimCt){
            this.shimCt = Ext.get('ext-dd-shim-ct-'+owner.id);
            if(!this.shimCt){
                this.shimCt = document.createElement('div');
                this.shimCt.id = 'ext-dd-shim-ct-'+owner.id;
                owner.getEl().parent().appendChild(this.shimCt);
            }
        }
        var el = document.createElement('div');
        el.className = 'ext-dd-shim';
        this.shimCt.appendChild(el);
        
        return Ext.create('Ext.Layer', {
            shadow: false, 
            useDisplay: true, 
            constrain: false
        }, el);
    },
    
    clearShims : function(){
        Ext.each(this.shims, function(shim){
            if(shim){
                shim.hide();
            }
        });
    },
    
    onContainerOver : function(dd, e, data){
        return this.dropAllowed;
    },
    
    onCalendarDragComplete : function(){
        delete this.dragStartDate;
        delete this.dragEndDate;
        this.clearShims();
    },
    
    onNodeDrop : function(n, dd, e, data){
        if(n && data){
            if(data.type == 'eventdrag'){
                var rec = this.view.getEventRecordFromEl(data.ddel),
                    dt = Extensible.Date.copyTime(rec.data[Extensible.calendar.data.EventMappings.StartDate.name], n.date);
                    
                this.view.onEventDrop(rec, dt);
                this.onCalendarDragComplete();
                return true;
            }
            if(data.type == 'caldrag'){
                this.view.onCalendarEndDrag(this.dragStartDate, this.dragEndDate, 
                    Ext.bind(this.onCalendarDragComplete, this));
                //shims are NOT cleared here -- they stay visible until the handling
                //code calls the onCalendarDragComplete callback which hides them.
                return true;
            }
        }
        this.onCalendarDragComplete();
        return false;
    },
    
    onContainerDrop : function(dd, e, data){
        this.onCalendarDragComplete();
        return false;
    },
    
    destroy: function() {
        Ext.each(this.shims, function(shim){
            if(shim){
                Ext.destroy(shim);
            }
        });
        
        Ext.removeNode(this.shimCt);
        delete this.shimCt;
        this.shims.length = 0;
    }
});

/* @private
 * Internal drag zone implementation for the calendar day and week views.
 */
Ext.define('Extensible.calendar.dd.DayDragZone', {
    extend: 'Extensible.calendar.dd.DragZone',
    
    ddGroup : 'DayViewDD',
    resizeSelector : '.ext-evt-rsz',
    
    getDragData : function(e){
        var t = e.getTarget(this.resizeSelector, 2, true);
        if(t){
            var p = t.parent(this.eventSelector), 
                rec = this.view.getEventRecordFromEl(p);
            
            if(!rec){
                // if rec is null here it usually means there was a timing issue between drag 
                // start and the browser reporting it properly. Simply ignore and it will 
                // resolve correctly once the browser catches up.
                return;
            }
            return {
                type: 'eventresize',
                xy: e.getXY(),
                ddel: p.dom,
                eventStart: rec.data[Extensible.calendar.data.EventMappings.StartDate.name],
                eventEnd: rec.data[Extensible.calendar.data.EventMappings.EndDate.name],
                proxy: this.proxy
            };
        }
        var t = e.getTarget(this.eventSelector, 3);
        if(t){
            var rec = this.view.getEventRecordFromEl(t);
            if(!rec){
                // if rec is null here it usually means there was a timing issue between drag 
                // start and the browser reporting it properly. Simply ignore and it will 
                // resolve correctly once the browser catches up.
                return;
            }
            return {
                type: 'eventdrag',
                xy: e.getXY(),
                ddel: t,
                eventStart: rec.data[Extensible.calendar.data.EventMappings.StartDate.name],
                eventEnd: rec.data[Extensible.calendar.data.EventMappings.EndDate.name],
                proxy: this.proxy
            };
        }
        
        // If not dragging/resizing an event then we are dragging on 
        // the calendar to add a new event
        t = this.view.getDayAt(e.getX(), e.getY());
        if(t.el){
            return {
                type: 'caldrag',
                dayInfo: t,
                proxy: this.proxy
            };
        }
        return null;
    }
});/* @private
 * Internal drop zone implementation for the calendar day and week views.
 */
Ext.define('Extensible.calendar.dd.DayDropZone', {
    extend: 'Extensible.calendar.dd.DropZone',

    ddGroup : 'DayViewDD',
    dateRangeFormat : '{0}-{1}',
    dateFormat : 'n/j',
    
    onNodeOver : function(n, dd, e, data){
        var dt, text = this.createText,
            timeFormat = Extensible.Date.use24HourTime ? 'G:i' : 'g:ia';
            
        if(data.type == 'caldrag'){
            if(!this.dragStartMarker){
                // Since the container can scroll, this gets a little tricky.
                // There is no el in the DOM that we can measure by default since
                // the box is simply calculated from the original drag start (as opposed
                // to dragging or resizing the event where the orig event box is present).
                // To work around this we add a placeholder el into the DOM and give it
                // the original starting time's box so that we can grab its updated
                // box measurements as the underlying container scrolls up or down.
                // This placeholder is removed in onNodeDrop.
                this.dragStartMarker = n.el.parent().createChild({
                    style: 'position:absolute;'
                });
                // use the original dayInfo values from the drag start
                this.dragStartMarker.setBox(data.dayInfo.timeBox);
                this.dragCreateDt = data.dayInfo.date;
            }
            var endDt, box = this.dragStartMarker.getBox();
            box.height = Math.ceil(Math.abs(e.getY() - box.y) / n.timeBox.height) * n.timeBox.height;
            
            if(e.getY() < box.y){
                box.height += n.timeBox.height;
                box.y = box.y - box.height + n.timeBox.height;
                endDt = Extensible.Date.add(this.dragCreateDt, {minutes: this.ddIncrement});
            }
            else{
                n.date = Extensible.Date.add(n.date, {minutes: this.ddIncrement});
            }
            this.shim(this.dragCreateDt, box);
            
            var diff = Extensible.Date.diff(this.dragCreateDt, n.date),
                curr = Extensible.Date.add(this.dragCreateDt, {millis: diff});
                
            this.dragStartDate = Extensible.Date.min(this.dragCreateDt, curr);
            this.dragEndDate = endDt || Extensible.Date.max(this.dragCreateDt, curr);
                
            dt = Ext.String.format(this.dateRangeFormat,
                Ext.Date.format(this.dragStartDate, timeFormat),
                Ext.Date.format(this.dragEndDate, timeFormat));
        }
        else{
            var evtEl = Ext.get(data.ddel),
                dayCol = evtEl.parent().parent(),
                box = evtEl.getBox();
            
            box.width = dayCol.getWidth();
            
            if(data.type == 'eventdrag'){
                if(this.dragOffset === undefined){
                    // on fast drags there is a lag between the original drag start xy position and
                    // that first detected within the drop zone's getTargetFromEvent method (which is 
                    // where n.timeBox comes from). to avoid a bad offset we calculate the
                    // timeBox based on the initial drag xy, not the current target xy.
                    var initialTimeBox = this.view.getDayAt(data.xy[0], data.xy[1]).timeBox;
                    this.dragOffset = initialTimeBox.y - box.y;
                }
                else{
                    box.y = n.timeBox.y;
                }
                dt = Ext.Date.format(n.date, (this.dateFormat + ' ' + timeFormat));
                box.x = n.el.getLeft();
                
                this.shim(n.date, box);
                text = this.moveText;
            }
            if(data.type == 'eventresize'){
                if(!this.resizeDt){
                    this.resizeDt = n.date;
                }
                box.x = dayCol.getLeft();
                box.height = Math.ceil(Math.abs(e.getY() - box.y) / n.timeBox.height) * n.timeBox.height;
                if(e.getY() < box.y){
                    box.y -= box.height;
                }
                else{
                    n.date = Extensible.Date.add(n.date, {minutes: this.ddIncrement});
                }
                this.shim(this.resizeDt, box);
                
                var diff = Extensible.Date.diff(this.resizeDt, n.date),
                    curr = Extensible.Date.add(this.resizeDt, {millis: diff}),
                    start = Extensible.Date.min(data.eventStart, curr),
                    end = Extensible.Date.max(data.eventStart, curr);
                    
                data.resizeDates = {
                    StartDate: start,
                    EndDate: end
                }
                
                dt = Ext.String.format(this.dateRangeFormat, 
                    Ext.Date.format(start, timeFormat), 
                    Ext.Date.format(end, timeFormat));
                    
                text = this.resizeText;
            }
        }
        
        data.proxy.updateMsg(Ext.String.format(text, dt));
        return this.dropAllowed;
    },
    
    shim : function(dt, box){
        Ext.each(this.shims, function(shim){
            if(shim){
                shim.isActive = false;
                shim.hide();
            }
        });
        
        var shim = this.shims[0];
        if(!shim){
            shim = this.createShim();
            this.shims[0] = shim;
        }
        
        shim.isActive = true;
        shim.show();
        shim.setBox(box);
    },
    
    onNodeDrop : function(n, dd, e, data){
        if(n && data){
            if(data.type == 'eventdrag'){
                var rec = this.view.getEventRecordFromEl(data.ddel);
                this.view.onEventDrop(rec, n.date);
                this.onCalendarDragComplete();
                delete this.dragOffset;
                return true;
            }
            if(data.type == 'eventresize'){
                var rec = this.view.getEventRecordFromEl(data.ddel);
                this.view.onEventResize(rec, data.resizeDates);
                this.onCalendarDragComplete();
                delete this.resizeDt;
                return true;
            }
            if(data.type == 'caldrag'){
                Ext.destroy(this.dragStartMarker);
                delete this.dragStartMarker;
                delete this.dragCreateDt;
                this.view.onCalendarEndDrag(this.dragStartDate, this.dragEndDate, 
                    Ext.bind(this.onCalendarDragComplete, this));
                //shims are NOT cleared here -- they stay visible until the handling
                //code calls the onCalendarDragComplete callback which hides them.
                return true;
            }
        }
        this.onCalendarDragComplete();
        return false;
    }
});
/**
 * @class Extensible.calendar.data.EventModel
 * @extends Ext.data.Record
 * <p>This is the {@link Ext.data.Record Record} specification for calendar event data used by the
 * {@link Extensible.calendar.CalendarPanel CalendarPanel}'s underlying store. It can be overridden as 
 * necessary to customize the fields supported by events, although the existing field definition names 
 * should not be altered. If your model fields are named differently you should update the <b>mapping</b>
 * configs accordingly.</p>
 * <p>The only required fields when creating a new event record instance are <tt>StartDate</tt> and
 * <tt>EndDate</tt>.  All other fields are either optional or will be defaulted if blank.</p>
 * <p>Here is a basic example for how to create a new record of this type:<pre><code>
rec = new Extensible.calendar.data.EventModel({
    StartDate: '2101-01-12 12:00:00',
    EndDate: '2101-01-12 13:30:00',
    Title: 'My cool event',
    Notes: 'Some notes'
});
</code></pre>
 * If you have overridden any of the record's data mappings via the {@link Extensible.calendar.data.EventMappings EventMappings} object
 * you may need to set the values using this alternate syntax to ensure that the field names match up correctly:<pre><code>
var M = Extensible.calendar.data.EventMappings,
    rec = new Extensible.calendar.data.EventModel();

rec.data[M.StartDate.name] = '2101-01-12 12:00:00';
rec.data[M.EndDate.name] = '2101-01-12 13:30:00';
rec.data[M.Title.name] = 'My cool event';
rec.data[M.Notes.name] = 'Some notes';
</code></pre>
 * @constructor
 * @param {Object} data (Optional) An object, the properties of which provide values for the new Record's
 * fields. If not specified the {@link Ext.data.Field#defaultValue defaultValue}
 * for each field will be assigned.
 * @param {Object} id (Optional) The id of the Record. The id is used by the
 * {@link Ext.data.Store} object which owns the Record to index its collection
 * of Records (therefore this id should be unique within each store). If an
 * id is not specified a {@link #phantom}
 * Record will be created with an {@link #Record.id automatically generated id}.
 */
Ext.define('Extensible.calendar.data.EventModel', {
    extend: 'Ext.data.Model',
    
    requires: [
        'Ext.util.MixedCollection',
        'Extensible.calendar.data.EventMappings'
    ],
    
    statics: {
        /**
         * Reconfigures the default record definition based on the current {@link Extensible.calendar.data.EventMappings EventMappings}
         * object. See the header documentation for {@link Extensible.calendar.data.EventMappings} for complete details and 
         * examples of reconfiguring an EventRecord.
         * @method create
         * @static
         * @return {Function} The updated EventRecord constructor function
         */
        reconfigure: function() {
            var Data = Extensible.calendar.data,
                Mappings = Data.EventMappings,
                proto = Data.EventModel.prototype,
                fields = [];
            
            // It is critical that the id property mapping is updated in case it changed, since it
            // is used elsewhere in the data package to match records on CRUD actions:
            proto.idProperty = Mappings.EventId.name || 'id';
            
            for(prop in Mappings){
                if(Mappings.hasOwnProperty(prop)){
                    fields.push(Mappings[prop]);
                }
            }
            proto.fields.clear();
            for(var i = 0, len = fields.length; i < len; i++){
                proto.fields.add(Ext.create('Ext.data.Field', fields[i]));
            }
            return Data.EventModel;
        }
    }
},
function(){
    Extensible.calendar.data.EventModel.reconfigure();
});
Ext.define('Extensible.calendar.data.EventStore', {
    extend: 'Ext.data.Store',
    model: 'Extensible.calendar.data.EventModel',
    
    constructor: function(config) {
        config = config || {};
        
        // By default autoLoad will cause the store to load itself during the
        // constructor, before the owning calendar view has a chance to set up
        // the initial date params to use during loading.  We replace autoLoad
        // with a deferLoad property that the view can check for and use to set
        // up default params as needed, then call the load itself. 
        this.deferLoad = config.autoLoad;
        config.autoLoad = false;
        
        //this._dateCache = [];
        
        this.callParent(arguments);
    },
    
    load : function(o) {
        Extensible.log('store load');
        o = o || {};
        
        // if params are passed delete the one-time defaults
        if(o.params){
            delete this.initialParams;
        }
        // this.initialParams will only be set if the store is being loaded manually
        // for the first time (autoLoad = false) so the owning calendar view set
        // the initial start and end date params to use. Every load after that will
        // have these params set automatically during normal UI navigation.
        if(this.initialParams){
            o.params = o.params || {};
            Ext.apply(o.params, this.initialParams);
            delete this.initialParams;
        }
        
        this.callParent(arguments);
    }
    
//    execute : function(action, rs, options, /* private */ batch) {
//        if(action=='read'){
//            var i = 0, 
//                dc = this._dateCache, 
//                len = dc.length,
//                range,
//                p = options.params,
//                start = p.start,
//                end = p.end;
//                
//            //options.add = true;
//            for(i; i<len; i++){
//                range = dc[i];
//                
//            }
//        }
//        this.callParent(arguments);
//    }
});/**
 * @class Extensible.calendar.data.CalendarModel
 * @extends Ext.data.Record
 * <p>This is the {@link Ext.data.Record Record} specification for calendar items used by the
 * {@link Extensible.calendar.CalendarPanel CalendarPanel}'s calendar store. If your model fields 
 * are named differently you should update the <b>mapping</b> configs accordingly.</p>
 * <p>The only required fields when creating a new calendar record instance are CalendarId and
 * Title.  All other fields are either optional or will be defaulted if blank.</p>
 * <p>Here is a basic example for how to create a new record of this type:<pre><code>
rec = new Extensible.calendar.data.CalendarModel({
    CalendarId: 5,
    Title: 'My Holidays',
    Description: 'My personal holiday schedule',
    ColorId: 3
});
</code></pre>
 * If you have overridden any of the record's data mappings via the {@link Extensible.calendar.data.CalendarMappings CalendarMappings} object
 * you may need to set the values using this alternate syntax to ensure that the fields match up correctly:<pre><code>
var M = Extensible.calendar.data.CalendarMappings;

rec = new Extensible.calendar.data.CalendarModel();
rec.data[M.CalendarId.name] = 5;
rec.data[M.Title.name] = 'My Holidays';
rec.data[M.Description.name] = 'My personal holiday schedule';
rec.data[M.ColorId.name] = 3;
</code></pre>
 * @constructor
 * @param {Object} data (Optional) An object, the properties of which provide values for the new Record's
 * fields. If not specified the {@link Ext.data.Field#defaultValue defaultValue}
 * for each field will be assigned.
 * @param {Object} id (Optional) The id of the Record. The id is used by the
 * {@link Ext.data.Store} object which owns the Record to index its collection
 * of Records (therefore this id should be unique within each store). If an
 * id is not specified a {@link #phantom}
 * Record will be created with an {@link #Record.id automatically generated id}.
 */
Ext.define('Extensible.calendar.data.CalendarModel', {
    extend: 'Ext.data.Model',
    
    requires: [
        'Ext.util.MixedCollection',
        'Extensible.calendar.data.CalendarMappings'
    ],
    
    statics: {
        /**
         * Reconfigures the default record definition based on the current {@link Extensible.calendar.data.CalendarMappings CalendarMappings}
         * object. See the header documentation for {@link Extensible.calendar.data.CalendarMappings} for complete details and 
         * examples of reconfiguring a CalendarRecord.
         * @method create
         * @static
         * @return {Function} The updated CalendarRecord constructor function
         */
        reconfigure: function(){
            var Data = Extensible.calendar.data,
                Mappings = Data.CalendarMappings,
                proto = Data.CalendarModel.prototype,
                fields = [];
            
            // It is critical that the id property mapping is updated in case it changed, since it
            // is used elsewhere in the data package to match records on CRUD actions:
            proto.idProperty = Mappings.CalendarId.name || 'id';
            
            for(prop in Mappings){
                if(Mappings.hasOwnProperty(prop)){
                    fields.push(Mappings[prop]);
                }
            }
            proto.fields.clear();
            for(var i = 0, len = fields.length; i < len; i++){
                proto.fields.add(Ext.create('Ext.data.Field', fields[i]));
            }
            return Data.CalendarModel;
        }
    }
},
function() {
    Extensible.calendar.data.CalendarModel.reconfigure();
});/*
 * A simple reusable store that loads static calendar field definitions into memory
 * and can be bound to the CalendarCombo widget and used for calendar color selection.
 */
Ext.define('Extensible.calendar.data.MemoryCalendarStore', {
    extend: 'Ext.data.Store',
    model: 'Extensible.calendar.data.CalendarModel',
    
    requires: [
        'Ext.data.proxy.Memory',
        'Ext.data.reader.Json',
        'Ext.data.writer.Json',
        'Extensible.calendar.data.CalendarModel',
        'Extensible.calendar.data.CalendarMappings'
    ],
    
    proxy: {
        type: 'memory',
        reader: {
            type: 'json',
            root: 'calendars'
        },
        writer: {
            type: 'json'
        }
    },

    autoLoad: true,
    
    initComponent: function() {
        this.sorters = this.sorters || [{
            property: Extensible.calendar.data.CalendarMappings.Title.name,
            direction: 'ASC'
        }];
        
        this.idProperty = this.idProperty || Extensible.calendar.data.CalendarMappings.CalendarId.name || 'id';
        
        this.fields = Extensible.calendar.data.CalendarModel.prototype.fields.getRange();
        
        this.callParent(arguments);
    }
});/*
 * This is a simple in-memory store implementation that is ONLY intended for use with
 * calendar samples running locally in the browser with no external data source. Under
 * normal circumstances, stores that use a MemoryProxy are read-only and intended only
 * for displaying data read from memory. In the case of the calendar, it's still quite
 * useful to be able to deal with in-memory data for sample purposes (as many people 
 * may not have PHP set up to run locally), but by default, updates will not work since the
 * calendar fully expects all CRUD operations to be supported by the store (and in fact
 * will break, for example, if phantom records are not removed properly). This simple
 * class gives us a convenient way of loading and updating calendar event data in memory,
 * but should NOT be used outside of the local samples. 
 * 
 * For a real-world store implementation see the remote sample (remote.js).
 */
Ext.define('Extensible.calendar.data.MemoryEventStore', {
    extend: 'Ext.data.Store',
    model: 'Extensible.calendar.data.EventModel',
    
    requires: [
        'Ext.data.proxy.Memory',
        'Ext.data.reader.Json',
        'Ext.data.writer.Json',
        'Extensible.calendar.data.EventModel',
        'Extensible.calendar.data.EventMappings'
    ],
    
    proxy: {
        type: 'memory',
        reader: {
            type: 'json',
            root: 'evts'
        },
        writer: {
            type: 'json'
        }
    },
    
    // private
    constructor: function(config) {
        config = config || {};
        
        this.callParent(arguments);
        
        this.sorters = this.sorters || [{
            property: Extensible.calendar.data.EventMappings.StartDate.name,
            direction: 'ASC'
        }];
        
        this.idProperty = this.idProperty || Extensible.calendar.data.EventMappings.EventId.mapping || 'id';
        
        this.fields = Extensible.calendar.data.EventModel.prototype.fields.getRange();
        
        // By default this shared example store will monitor its own CRUD events and 
        // automatically show a page-level message for each event. This is simply a shortcut
        // so that each example doesn't have to provide its own messaging code, but this pattern
        // of handling messages at the store level could easily be implemented in an application
        // (see the source of test-app.js for an example of this). The autoMsg config is provided
        // to turn off this automatic messaging in any case where this store is used but the 
        // default messaging is not desired.
        if (config.autoMsg !== false) {
            // Note that while the store provides individual add, update and remove events, those only 
            // signify that records were added to the store, NOT that your changes were actually 
            // persisted correctly in the back end (in remote scenarios). While this isn't an issue
            // with the MemoryProxy since everything is local, it's still harder to work with the 
            // individual CRUD events since they have different APIs and quirks (notably the add and 
            // update events both fire during record creation and it's difficult to differentiate a true
            // update from an update caused by saving the PK into a newly-added record). Because of all
            // this, in general the 'write' event is the best option for generically messaging after 
            // CRUD persistance has actually succeeded.
            this.on('write', this.onWrite, this);
        }
        
        this.autoMsg = config.autoMsg;
        this.onCreateRecords = Ext.Function.createInterceptor(this.onCreateRecords, this.interceptCreateRecords);
        this.initRecs();
    },
    
    // private - override to make sure that any records added in-memory
    // still get a unique PK assigned at the data level
    interceptCreateRecords: function(records, operation, success) {
        if (success) {
            var i = 0,
                rec,
                len = records.length;
            
            for (; i < len; i++) {
                records[i].data[Extensible.calendar.data.EventMappings.EventId.name] = records[i].id;
            }
        }
    },
    
    // If the store started with preloaded inline data, we have to make sure the records are set up
    // properly as valid "saved" records otherwise they may get "added" on initial edit.
    initRecs: function() {
        this.each(function(rec){
            rec.store = this;
            rec.phantom = false;
        }, this);
    },
    
    // private
    onWrite: function(store, operation) {
        var me = this;
        
        if (Extensible.example && Extensible.example.msg) {
            var success = operation.wasSuccessful(),
                rec = operation.getRecords()[0],
                title = rec.data[Extensible.calendar.data.EventMappings.Title.name];
    
            switch (operation.action) {
                case 'create':
                    Extensible.example.msg('Add', 'Added "' + Ext.value(title, '(No title)') + '"');
                    break;
                case 'update':
                    Extensible.example.msg('Update', 'Updated "' + Ext.value(title, '(No title)') + '"');
                    break;
                case 'destroy':
                    Extensible.example.msg('Delete', 'Deleted "' + Ext.value(title, '(No title)') + '"');
                    break;
            }
        }
    },
    
    // private - override the default logic for memory storage
    onProxyLoad: function(operation) {
        var me = this,
            records;
        
        if (me.data && me.data.length > 0) {
            // this store has already been initially loaded, so do not reload
            // and lose updates to the store, just use store's latest data
            me.totalCount = me.data.length;
            records = me.data.items;
        }
        else {
            // this is the initial load, so defer to the proxy's result
            var resultSet = operation.getResultSet(),
                successful = operation.wasSuccessful();

            records = operation.getRecords();

            if (resultSet) {
                me.totalCount = resultSet.total;
            }
            if (successful) {
                me.loadRecords(records, operation);
            }
        }

        me.loading = false;
        me.fireEvent('load', me, records, successful);
    }
});/* @private
 * This is an internal helper class for the calendar views and should not be overridden.
 * It is responsible for the base event rendering logic underlying all views based on a 
 * box-oriented layout that supports day spanning (MonthView, MultiWeekView, DayHeaderView).
 */
Ext.define('Extensible.calendar.util.WeekEventRenderer', {
    
    requires: ['Ext.core.DomHelper'],
    
    statics: {
        /**
         * Retrieve the event layout table row for the specified week and row index. If
         * the row does not already exist it will get created and appended to the DOM.
         * This method does not check against the max allowed events -- it is the responsibility
         * of calling code to ensure that an event row at the specified index is really needed.
         */
        getEventRow: function(viewId, weekIndex, rowIndex) {
            var indexOffset = 1, //skip the first row with date #'s
                weekRow = Ext.get(viewId + '-wk-' + weekIndex),
                eventRow,
                weekTable;
            
            if (weekRow) {
                weekTable = weekRow.child('.ext-cal-evt-tbl', true);
                eventRow = weekTable.tBodies[0].childNodes[rowIndex + indexOffset];
                
                if (!eventRow) {
                    eventRow = Ext.core.DomHelper.append(weekTable.tBodies[0], '<tr></tr>');
                }
            }
            return Ext.get(eventRow);
        },
        
        /**
         * Render an individual event
         * @private
         */
        renderEvent: function(event, weekIndex, dayIndex, eventIndex, dayCount, currentDate, renderConfig) {
            var eventMappings = Extensible.calendar.data.EventMappings,
                eventData = event.data || event.event.data,
                startOfWeek = Ext.Date.clone(currentDate),
                endOfWeek = Extensible.Date.add(startOfWeek, {days: dayCount - dayIndex, millis: -1}),
                eventRow = this.getEventRow(renderConfig.viewId, weekIndex, eventIndex),
                daysToEventEnd = Extensible.Date.diffDays(currentDate, eventData[eventMappings.EndDate.name]) + 1,
                // Restrict the max span to the current week only since this is for the cuurent week's markup
                colspan = Math.min(daysToEventEnd, dayCount - dayIndex);
            
            // The view passes a template function to use when rendering the events.
            // These are special data values that get passed back to the template.
            eventData._weekIndex = weekIndex;
            eventData._renderAsAllDay = eventData[eventMappings.IsAllDay.name] || event.isSpanStart;
            eventData.spanLeft = eventData[eventMappings.StartDate.name].getTime() < startOfWeek.getTime();
            eventData.spanRight = eventData[eventMappings.EndDate.name].getTime() > endOfWeek.getTime();
            eventData.spanCls = (eventData.spanLeft ? (eventData.spanRight ?
                'ext-cal-ev-spanboth' : 'ext-cal-ev-spanleft') : (eventData.spanRight ? 'ext-cal-ev-spanright' : ''));
            
            var cellConfig = {
                tag: 'td',
                cls: 'ext-cal-ev',
                // This is where the passed template gets processed and the markup returned
                cn: renderConfig.tpl.apply(renderConfig.templateDataFn(eventData))
            };
            
            if (colspan > 1) {
                cellConfig.colspan = colspan;
            }
            Ext.core.DomHelper.append(eventRow, cellConfig);
        },
        
        /**
         * Events are collected into a big multi-dimensional array in the view, then passed here
         * for rendering. The event grid consists of an array of weeks (1-n), each of which contains an
         * array of days (1-7), each of which contains an array of events and span placeholders (0-n). 
         * @param {Object} o An object containing all of the supported config options (see
         * Extensible.calendar.view.Month.renderItems() to see what gets passed).
         * @private
         */
        render: function(config) {
            // var-apalooza ;) since we're looping a lot, minimize initial declarations
            var me = this,
                spaceChar = '&#160;',
                weekIndex = 0,
                eventGrid = config.eventGrid,
                currentDate = Ext.Date.clone(config.viewStart),
                currentDateString = '',
                eventTpl = config.tpl,
                maxEventsPerDay = config.maxEventsPerDay != undefined ? config.maxEventsPerDay : 999,
                weekCount = config.weekCount < 1 ? 6 : config.weekCount,
                dayCount = config.weekCount == 1 ? config.dayCount : 7,
                eventRow,
                dayIndex,
                weekGrid,
                eventIndex,
                skippedEventCount,
                dayGrid,
                eventCount,
                currentEvent,
                cellConfig,
                eventData;
            
            // Loop through each week in the overall event grid
            for (; weekIndex < weekCount; weekIndex++) {
                dayIndex = 0;
                weekGrid = eventGrid[weekIndex];
                
                // Loop through each day in the current week grid
                for (; dayIndex < dayCount; dayIndex++) {
                    // Make sure there is actually a day to process events for first
                    if (weekGrid && weekGrid[dayIndex]) {
                        eventIndex = 0;
                        skippedEventCount = 0;
                        dayGrid = weekGrid[dayIndex];
                        eventCount = dayGrid.length;
                        currentDateString = Ext.Date.format(currentDate, 'Ymd');
                        
                        // Loop through each event in the current day grid. Note that this grid can
                        // also contain placeholders representing segments of spanning events, though
                        // for simplicity's sake these will all be referred to as "events" in comments.
                        for(; eventIndex < eventCount; eventIndex++){
                            if (!dayGrid[eventIndex]) {
                                // There is no event at the current index
                                if (eventIndex >= maxEventsPerDay) {
                                    // We've already hit the max count of displayable event rows, so
                                    // skip adding any additional empty row markup. In this case, since
                                    // there is no event we don't track it as a skipped event as below.
                                    continue;
                                }
                                // Insert an empty TD since there is no event at this index
                                eventRow = me.getEventRow(config.viewId, weekIndex, eventIndex);
                                Ext.core.DomHelper.append(eventRow, {
                                    tag: 'td',
                                    cls: 'ext-cal-ev',
                                    html: spaceChar,
                                    //style: 'outline: 1px solid red;', // helpful for debugging
                                    id: config.viewId + '-empty-' + eventCount + '-day-' + currentDateString
                                });
                            }
                            else {
                                if (eventIndex >= maxEventsPerDay) {
                                    // We've hit the max count of displayable event rows, but since there
                                    // is an event at the current index we have to track the count of events
                                    // that aren't being rendered so that we can provide the proper count
                                    // when displaying the "more events" link below.
                                    skippedEventCount++;
                                    continue;
                                }
                                currentEvent = dayGrid[eventIndex];
                                
                                // We only want to insert the markup for an event that does not span days, or
                                // if it does span, only for the initial instance (not any of its placeholders
                                // in the event grid, which are there only to reserve the space in the layout).
                                if (!currentEvent.isSpan || currentEvent.isSpanStart) {
                                    me.renderEvent(currentEvent, weekIndex, dayIndex, eventIndex,
                                        dayCount, currentDate, config);
                                }
                            }
                        }
                        
                        // We're done processing all of the events for the current day. Time to insert the
                        // "more events" link or the last empty TD for the day, if needed.
                        
                        if (skippedEventCount > 0) {
                            // We hit one or more events in the grid that could not be displayed since the max
                            // events per day count was exceeded, so add the "more events" link.
                            eventRow = me.getEventRow(config.viewId, weekIndex, maxEventsPerDay);
                            Ext.core.DomHelper.append(eventRow, {
                                tag: 'td',
                                cls: 'ext-cal-ev-more',
                                //style: 'outline: 1px solid blue;', // helpful for debugging
                                id: 'ext-cal-ev-more-' + Ext.Date.format(currentDate, 'Ymd'),
                                cn: {
                                    tag: 'a',
                                    html: Ext.String.format(config.getMoreText(skippedEventCount), skippedEventCount)
                                }
                            });
                        }
                        else if (eventCount < config.evtMaxCount[weekIndex]) {
                            // We did NOT hit the max event count, meaning that we are now left with a gap in
                            // the layout table which we need to fill with one last empty TD.
                            eventRow = me.getEventRow(config.viewId, weekIndex, eventCount);
                            if (eventRow) {
                                cellConfig = {
                                    tag: 'td',
                                    cls: 'ext-cal-ev',
                                    html: spaceChar,
                                    //style: 'outline: 1px solid green;', // helpful for debugging
                                    id: config.viewId + '-empty-' + (eventCount + 1) + '-day-' + currentDateString
                                };
                                
                                // It's easy to determine at this point how many extra rows are needed, so
                                // just add a rowspan rather than multiple dummy TDs if needed.
                                var rowspan = config.evtMaxCount[weekIndex] - eventCount;
                                if (rowspan > 1) {
                                    cellConfig.rowspan = rowspan;
                                }
                                Ext.core.DomHelper.append(eventRow, cellConfig);
                            }
                        }
                        // Else the event count for the current day equals the max event count, so the current
                        // day is completely filled up with no additional placeholder markup needed.
                    }
                    else {
                        // There are no events for the current day, so no need to go through all the logic
                        // above -- simply append an empty TD spanning the total row count for the week.
                        eventRow = me.getEventRow(config.viewId, weekIndex, 0);
                        if (eventRow) {
                            cellConfig = {
                                tag: 'td',
                                cls: 'ext-cal-ev',
                                html: spaceChar,
                                //style: 'outline: 1px solid purple;', // helpful for debugging
                                id: config.viewId + '-empty-day-' + currentDateString
                            };
                            
                            if (config.evtMaxCount[weekIndex] > 1) {
                                cellConfig.rowspan = config.evtMaxCount[weekIndex];
                            }
                            Ext.core.DomHelper.append(eventRow, cellConfig);
                        }
                    }
                    
                    // Move to the next date and restart the loop
                    currentDate = Extensible.Date.add(currentDate, {days: 1});
                }
            }
        }
    }
});/**
 * @class Extensible.calendar.form.field.CalendarCombo
 * @extends Ext.form.ComboBox
 * <p>A custom combo used for choosing from the list of available calendars to assign an event to. You must
 * pass a populated calendar store as the store config or the combo will not work.</p>
 * <p>This is pretty much a standard combo that is simply pre-configured for the options needed by the
 * calendar components. The default configs are as follows:<pre><code>
fieldLabel: 'Calendar',
triggerAction: 'all',
queryMode: 'local',
forceSelection: true,
width: 200
</code></pre>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Extensible.calendar.form.field.CalendarCombo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.extensible.calendarcombo',
    
    requires: ['Extensible.calendar.data.CalendarMappings'],
    
    fieldLabel: 'Calendar',
    triggerAction: 'all',
    queryMode: 'local',
    forceSelection: true,
    selectOnFocus: true,
    
    // private
    defaultCls: 'x-cal-default',
    hiddenCalendarCls: 'ext-cal-hidden',
    
    // private
    initComponent: function(){
        this.valueField = Extensible.calendar.data.CalendarMappings.CalendarId.name;
        this.displayField = Extensible.calendar.data.CalendarMappings.Title.name;
    
        this.listConfig = Ext.apply(this.listConfig || {}, {
            getInnerTpl: this.getListItemTpl
        });
        
        this.store.on('update', this.refreshColorCls, this);
        
        this.callParent(arguments);
    },
    
    getListItemTpl: function(displayField) {
        return '<div class="x-combo-list-item x-cal-{' + Extensible.calendar.data.CalendarMappings.ColorId.name +
                '}"><div class="ext-cal-picker-icon">&#160;</div>{' + displayField + '}</div>';
    },
    
    // private
    afterRender: function(){
        this.callParent(arguments);
        
        this.wrap = this.el.down('.x-form-item-body');
        this.wrap.addCls('ext-calendar-picker');
        
        this.icon = Ext.core.DomHelper.append(this.wrap, {
            tag: 'div', cls: 'ext-cal-picker-icon ext-cal-picker-mainicon'
        });
    },
    
    /* @private
     * Refresh the color CSS class based on the current field value
     */
    refreshColorCls: function() {
        var me = this,
            calendarMappings = Extensible.calendar.data.CalendarMappings,
            colorCls = '',
            value = me.getValue();
        
        if (!me.wrap) {
            return me;
        }
        if (me.currentStyleClss !== undefined) {
            me.wrap.removeCls(me.currentStyleClss);
        }
        
        if (!Ext.isEmpty(value)) {
            if (Ext.isArray(value)) {
                value = value[0];
            }
            if (!value.data) {
                // this is a calendar id, need to get the record first then use its color
                value = this.store.findRecord(calendarMappings.CalendarId.name, value);
            }
            colorCls = 'x-cal-' + (value.data ? value.data[calendarMappings.ColorId.name] : value);
        }
        
        me.currentStyleClss = colorCls;
        
//        if (value && value.data && value.data[calendarMappings.IsHidden.name] === true) {
//            colorCls += ' ' + me.hiddenCalendarCls;
//        }
        me.wrap.addCls(colorCls);
        
        return me;
    },
    
    // inherited docs
    setValue: function(value) {
        if (!value && this.store.getCount() > 0) {
            // ensure that a valid value is always set if possible
            value = this.store.getAt(0).data[Extensible.calendar.data.CalendarMappings.CalendarId.name];
        }
        
        this.callParent(arguments);
        
        this.refreshColorCls();
    }
});/* @private
 * Currently not used
 */
Ext.define('Extensible.form.recurrence.Combo', {
    extend: 'Ext.form.ComboBox',
    alias: 'widget.extensible.recurrencecombo',
    
    requires: ['Ext.data.ArrayStore'],
    
    width: 160,
    fieldLabel: 'Repeats',
    mode: 'local',
    triggerAction: 'all',
    forceSelection: true,
    displayField: 'pattern',
    valueField: 'id',
    
    recurrenceText: {
        none: 'Does not repeat',
        daily: 'Daily',
        weekly: 'Weekly',
        monthly: 'Monthly',
        yearly: 'Yearly'
    },
    
    initComponent: function(){
        this.callParent(arguments);
        
        this.addEvents('recurrencechange');
        
        this.store = this.store || Ext.create('Ext.data.ArrayStore', {
            fields: ['id', 'pattern'],
            idIndex: 0,
            data: [
                ['NONE', this.recurrenceText.none],
                ['DAILY', this.recurrenceText.daily],
                ['WEEKLY', this.recurrenceText.weekly],
                ['MONTHLY', this.recurrenceText.monthly],
                ['YEARLY', this.recurrenceText.yearly]
            ]
        });
    },
    
    initValue : function(){
        this.callParent(arguments);
        
        if(this.value != undefined){
            this.fireEvent('recurrencechange', this.value);
        }
    },
    
    setValue : function(v){
        var old = this.value;
        
        this.callParent(arguments);
        
        if(old != v){
            this.fireEvent('recurrencechange', v);
        }
        return this;
    }
});/* @private
 * Currently not used
 * Rrule info: http://www.kanzaki.com/docs/ical/rrule.html
 */
Ext.define('Extensible.form.recurrence.Fieldset', {
    extend: 'Ext.form.Field',
    alias: 'widget.extensible.recurrencefield',
    
    requires: ['Extensible.form.recurrence.Combo'],
    
    fieldLabel: 'Repeats',
    startDate: Ext.Date.clearTime(new Date()),
    enableFx: true,
    
    initComponent : function(){
        this.callParent(arguments);
        
        if(!this.height){
            this.autoHeight = true;
        }
    },
    
    onRender: function(ct, position){
        if(!this.el){
            this.frequencyCombo = Ext.create('Extensible.form.recurrence.Combo', {
                id: this.id+'-frequency',
                listeners: {
                    'recurrencechange': {
                        fn: this.showOptions,
                        scope: this
                    }
                }
            });
            if(this.fieldLabel){
                this.frequencyCombo.fieldLabel = this.fieldLabel;
            }
            
            this.innerCt = Ext.create('Ext.Container', {
                cls: 'extensible-recur-inner-ct',
                items: []
            });
            this.fieldCt = Ext.create('Ext.Container', {
                autoEl: {id:this.id}, //make sure the container el has the field's id
                cls: 'extensible-recur-ct',
                renderTo: ct,
                items: [this.frequencyCombo, this.innerCt]
            });
            
            this.fieldCt.ownerCt = this;
            this.innerCt.ownerCt = this.fieldCt;
            this.el = this.fieldCt.getEl();
            this.items = Ext.create('Ext.util.MixedCollection');
            this.items.addAll(this.initSubComponents());
        }
        this.callParent(arguments);
    },
    
//    afterRender : function(){
//        this.callParent(arguments);
//        this.setStartDate(this.startDate);
//    },
    
    // private
    initValue : function(){
        this.setStartDate(this.startDate);
        
        if(this.value !== undefined){
            this.setValue(this.value);
        }
        else if(this.frequency !== undefined){
            this.setValue('FREQ='+this.frequency);
        }
        else{
            this.setValue('NONE');
        }
        this.originalValue = this.getValue();
    },
    
    showOptions : function(o){
        var layoutChanged = false, unit = 'day';
        
        if(o != 'NONE'){
            this.hideSubPanels();
        }
        this.frequency = o;
        
        switch(o){
            case 'DAILY':
                layoutChanged = this.showSubPanel(this.repeatEvery);
                layoutChanged |= this.showSubPanel(this.until);
                break;
                
            case 'WEEKLY':
                layoutChanged = this.showSubPanel(this.repeatEvery);
                layoutChanged |= this.showSubPanel(this.weekly);
                layoutChanged |= this.showSubPanel(this.until);
                unit = 'week';
                break;
                
            case 'MONTHLY':
                layoutChanged = this.showSubPanel(this.repeatEvery);
                layoutChanged |= this.showSubPanel(this.monthly);
                layoutChanged |= this.showSubPanel(this.until);
                unit = 'month';
                break;
                
            case 'YEARLY':
                layoutChanged = this.showSubPanel(this.repeatEvery);
                layoutChanged |= this.showSubPanel(this.yearly);
                layoutChanged |= this.showSubPanel(this.until);
                unit = 'year';
                break;
            
            default:
                // case NONE
                this.hideInnerCt();
                return; 
        }
        
        if(layoutChanged){
            this.innerCt.doLayout();
        }
        
        this.showInnerCt();
        this.repeatEvery.updateLabel(unit);
    },
    
    showSubPanel : function(p){
        if (p.rendered) {
            p.show();
            return false;
        }
        else{
            if(this.repeatEvery.rendered){
                // make sure weekly/monthly options show in the middle
                p = this.innerCt.insert(1, p);
            }
            else{
                p = this.innerCt.add(p);
            }
            p.show();
            return true;
        }
    },
    
    showInnerCt: function(){
        if(!this.innerCt.isVisible()){
            if(this.enableFx && Ext.enableFx){
                this.innerCt.getPositionEl().slideIn('t', {
                    duration: .3
                });
            }
            else{
                this.innerCt.show();
            }
        }
    },
    
    hideInnerCt: function(){
        if(this.innerCt.isVisible()){
            if(this.enableFx && Ext.enableFx){
                this.innerCt.getPositionEl().slideOut('t', {
                    duration: .3,
                    easing: 'easeIn',
                    callback: this.hideSubPanels,
                    scope: this
                });
            }
            else{
                this.innerCt.hide();
                this.hideSubPanels();
            }
        }
    },
    
    setStartDate : function(dt){
        this.items.each(function(p){
            p.setStartDate(dt);
        });
    },
    
    getValue : function(){
        if(!this.rendered) {
            return this.value;
        }
        if(this.frequency=='NONE'){
            return '';
        }
        var value = 'FREQ='+this.frequency;
        this.items.each(function(p){
            if(p.isVisible()){
                value += p.getValue();
            }
        });
        return value;
    },
    
    setValue : function(v){
        this.value = v;
        
        if(v == null || v == '' || v == 'NONE'){
            this.frequencyCombo.setValue('NONE');
            this.showOptions('NONE');
            return this;
        }
        var parts = v.split(';');
        this.items.each(function(p){
            p.setValue(parts);
        });
        Ext.each(parts, function(p){
            if(p.indexOf('FREQ') > -1){
                var freq = p.split('=')[1];
                this.frequencyCombo.setValue(freq);
                this.showOptions(freq);
                return;
            }
        }, this);
        
        return this;
    },
    
    hideSubPanels : function(){
        this.items.each(function(p){
            p.hide();
        });
    },
    
    initSubComponents : function(){
        Extensible.calendar.recurrenceBase = Ext.extend(Ext.Container, {
            fieldLabel: ' ',
            labelSeparator: '',
            hideLabel: true,
            layout: 'table',
            anchor: '100%',
            startDate: this.startDate,

            //TODO: This is not I18N-able:
            getSuffix : function(n){
                if(!Ext.isNumber(n)){
                    return '';
                }
                switch (n) {
                    case 1:
                    case 21:
                    case 31:
                        return "st";
                    case 2:
                    case 22:
                        return "nd";
                    case 3:
                    case 23:
                        return "rd";
                    default:
                        return "th";
                }
            },
            
            //shared by monthly and yearly components:
            initNthCombo: function(cbo){
                var cbo = Ext.getCmp(this.id+'-combo'),
                    dt = this.startDate,
                    store = cbo.getStore(),
                    last = dt.getLastDateOfMonth().getDate(),
                    dayNum = dt.getDate(),
                    nthDate = Ext.Date.format(dt, 'jS') + ' day',
                    isYearly = this.id.indexOf('-yearly') > -1,
                    yearlyText = ' in ' + Ext.Date.format(dt, 'F'),
                    nthDayNum, nthDay, lastDay, lastDate, idx, data, s;
                    
                nthDayNum = Math.ceil(dayNum / 7);
                nthDay = nthDayNum + this.getSuffix(nthDayNum) + Ext.Date.format(dt, ' l');
                if(isYearly){
                    nthDate += yearlyText;
                    nthDay += yearlyText;
                }
                data = [[nthDate],[nthDay]];
                
                s = isYearly ? yearlyText : '';
                if(last-dayNum < 7){
                    data.push(['last '+Ext.Date.format(dt, 'l')+s]);
                }
                if(last == dayNum){
                    data.push(['last day'+s]);
                }
                
                idx = store.find('field1', cbo.getValue());
                store.removeAll();
                cbo.clearValue();
                store.loadData(data);
                
                if(idx > data.length-1){
                    idx = data.length-1;
                }
                cbo.setValue(store.getAt(idx > -1 ? idx : 0).data.field1);
                return this;
            },
            setValue:Ext.emptyFn
        });
        
        this.repeatEvery = new Extensible.calendar.recurrenceBase({
            id: this.id+'-every',
            layoutConfig: {
                columns: 3
            },
            items: [{
                xtype: 'label',
                text: 'Repeat every'
            },{
                xtype: 'numberfield',
                id: this.id+'-every-num',
                value: 1,
                width: 35,
                minValue: 1,
                maxValue: 99,
                allowBlank: false,
                enableKeyEvents: true,
                listeners: {
                    'keyup': {
                        fn: function(){
                            this.repeatEvery.updateLabel();
                        },
                        scope: this
                    }
                }
            },{
                xtype: 'label',
                id: this.id+'-every-label'
            }],
            setStartDate: function(dt){
                this.startDate = dt;
                this.updateLabel();
                return this;
            },
            getValue: function(){
                var v = Ext.getCmp(this.id+'-num').getValue();
                return v > 1 ? ';INTERVAL='+v : '';
            },
            setValue : function(v){
                var set = false, 
                    parts = Ext.isArray(v) ? v : v.split(';');
                
                Ext.each(parts, function(p){
                    if(p.indexOf('INTERVAL') > -1){
                        var interval = p.split('=')[1];
                        Ext.getCmp(this.id+'-num').setValue(interval);
                    }
                }, this);
                return this;
            },
            updateLabel: function(type){
                if(this.rendered){
                    var s = Ext.getCmp(this.id+'-num').getValue() == 1 ? '' : 's';
                    this.type = type ? type.toLowerCase() : this.type || 'day';
                    var lbl = Ext.getCmp(this.id+'-label');
                    if(lbl.rendered){
                        lbl.update(this.type + s + ' beginning ' + Ext.Date.format(this.startDate, 'l, F j'));
                    }
                }
                return this;
            },
            afterRender: function(){
                this.callParent(arguments);
                this.updateLabel();
            }
        });
            
        this.weekly = new Extensible.calendar.recurrenceBase({
            id: this.id+'-weekly',
            layoutConfig: {
                columns: 2
            },
            items: [{
                xtype: 'label',
                text: 'on:'
            },{
                xtype: 'checkboxgroup',
                id: this.id+'-weekly-days',
                items: [
                    {boxLabel: 'Sun', name: 'SU', id: this.id+'-weekly-SU'},
                    {boxLabel: 'Mon', name: 'MO', id: this.id+'-weekly-MO'},
                    {boxLabel: 'Tue', name: 'TU', id: this.id+'-weekly-TU'},
                    {boxLabel: 'Wed', name: 'WE', id: this.id+'-weekly-WE'},
                    {boxLabel: 'Thu', name: 'TH', id: this.id+'-weekly-TH'},
                    {boxLabel: 'Fri', name: 'FR', id: this.id+'-weekly-FR'},
                    {boxLabel: 'Sat', name: 'SA', id: this.id+'-weekly-SA'}
                ]
            }],
            setStartDate: function(dt){
                this.startDate = dt;
                this.selectToday();
                return this;
            },
            selectToday: function(){
                this.clearValue();
                var day = Ext.Date.format(this.startDate, 'D').substring(0,2).toUpperCase();
                Ext.getCmp(this.id + '-days').setValue(day, true);
            },
            clearValue: function(){
                Ext.getCmp(this.id + '-days').setValue([false, false, false, false, false, false, false]);
            },
            getValue: function(){
                var v = '', sel = Ext.getCmp(this.id+'-days').getValue();
                Ext.each(sel, function(chk){
                    if(v.length > 0){
                        v += ',';
                    }
                    v += chk.name;
                });
                var day = Ext.Date.format(this.startDate, 'D').substring(0,2).toUpperCase();
                return v.length > 0 && v != day ? ';BYDAY='+v : '';
            },
            setValue : function(v){
                var set = false, 
                    parts = Ext.isArray(v) ? v : v.split(';');
                
                this.clearValue();
                
                Ext.each(parts, function(p){
                    if(p.indexOf('BYDAY') > -1){
                        var days = p.split('=')[1].split(','),
                            vals = {};
                            
                        Ext.each(days, function(d){
                            vals[d] = true;
                        }, this);
                        
                        Ext.getCmp(this.id+'-days').setValue(vals);
                        return set = true;
                    }
                }, this);
                
                if(!set){
                    this.selectToday();
                }
                return this;
            }
        });
            
        this.monthly = new Extensible.calendar.recurrenceBase({
            id: this.id+'-monthly',
            layoutConfig: {
                columns: 3
            },
            items: [{
                xtype: 'label',
                text: 'on the'
            },{
                xtype: 'combo',
                id: this.id+'-monthly-combo',
                mode: 'local',
                width: 150,
                triggerAction: 'all',
                forceSelection: true,
                store: []
            },{
                xtype: 'label',
                text: 'of each month'
            }],
            setStartDate: function(dt){
                this.startDate = dt;
                this.initNthCombo();
                return this;
            },
            getValue: function(){
                var cbo = Ext.getCmp(this.id+'-combo'),
                    store = cbo.getStore(),
                    idx = store.find('field1', cbo.getValue()),
                    dt = this.startDate,
                    day = Ext.Date.format(dt, 'D').substring(0,2).toUpperCase();
                
                if (idx > -1) {
                    switch(idx){
                        case 0:  return ';BYMONTHDAY='+Ext.Date.format(dt, 'j');
                        case 1:  return ';BYDAY='+cbo.getValue()[0].substring(0,1)+day;
                        case 2:  return ';BYDAY=-1'+day;
                        default: return ';BYMONTHDAY=-1';
                    }
                }
                return '';
            }
        });
            
        this.yearly = new Extensible.calendar.recurrenceBase({
            id: this.id+'-yearly',
            layoutConfig: {
                columns: 3
            },
            items: [{
                xtype: 'label',
                text: 'on the'
            },{
                xtype: 'combo',
                id: this.id+'-yearly-combo',
                mode: 'local',
                width: 170,
                triggerAction: 'all',
                forceSelection: true,
                store: []
            },{
                xtype: 'label',
                text: 'each year'
            }],
            setStartDate: function(dt){
                this.startDate = dt;
                this.initNthCombo();
                return this;
            },
            getValue: function(){
                var cbo = Ext.getCmp(this.id+'-combo'),
                    store = cbo.getStore(),
                    idx = store.find('field1', cbo.getValue()),
                    dt = this.startDate,
                    day = Ext.Date.format(dt, 'D').substring(0,2).toUpperCase(),
                    byMonth = ';BYMONTH='+dt.format('n');
                
                if(idx > -1){
                    switch(idx){
                        case 0:  return byMonth;
                        case 1:  return byMonth+';BYDAY='+cbo.getValue()[0].substring(0,1)+day;
                        case 2:  return byMonth+';BYDAY=-1'+day;
                        default: return byMonth+';BYMONTHDAY=-1';
                    }
                }
                return '';
            }
        });
            
        this.until = new Extensible.calendar.recurrenceBase({
            id: this.id+'-until',
            untilDateFormat: 'Ymd\\T000000\\Z',
            layoutConfig: {
                columns: 5
            },
            items: [{
                xtype: 'label',
                text: 'and continuing'
            },{
                xtype: 'combo',
                id: this.id+'-until-combo',
                mode: 'local',
                width: 85,
                triggerAction: 'all',
                forceSelection: true,
                value: 'forever',
                store: ['forever', 'for', 'until'],
                listeners: {
                    'select': {
                        fn: function(cbo, rec){
                            var dt = Ext.getCmp(this.id+'-until-date');
                            if(rec.data.field1 == 'until'){
                                dt.show();
                                if (dt.getValue() == '') {
                                    dt.setValue(this.startDate.add(Date.DAY, 5));
                                    dt.setMinValue(this.startDate.clone().add(Date.DAY, 1));
                                }
                            }
                            else{
                                dt.hide();
                            }
                            if(rec.data.field1 == 'for'){
                                Ext.getCmp(this.id+'-until-num').show();
                                Ext.getCmp(this.id+'-until-endlabel').show();
                            }
                            else{
                                Ext.getCmp(this.id+'-until-num').hide();
                                Ext.getCmp(this.id+'-until-endlabel').hide();
                            }
                        },
                        scope: this
                    }
                }
            },{
                xtype: 'datefield',
                id: this.id+'-until-date',
                showToday: false,
                hidden: true
            },{
                xtype: 'numberfield',
                id: this.id+'-until-num',
                value: 5,
                width: 35,
                minValue: 1,
                maxValue: 99,
                allowBlank: false,
                hidden: true
            },{
                xtype: 'label',
                id: this.id+'-until-endlabel',
                text: 'occurrences',
                hidden: true
            }],
            setStartDate: function(dt){
                this.startDate = dt;
                return this;
            },
            getValue: function(){
                var dt = Ext.getCmp(this.id+'-date');
                if(dt.isVisible()){
                    return ';UNTIL='+Ext.String.format(dt.getValue(), this.untilDateFormat);
                }
                var ct = Ext.getCmp(this.id+'-num');
                if(ct.isVisible()){
                    return ';COUNT='+ct.getValue();
                }
                return '';
            },
            setValue : function(v){
                var set = false, 
                    parts = Ext.isArray(v) ? v : v.split(';');
                
                Ext.each(parts, function(p){
                    if(p.indexOf('COUNT') > -1){
                        var count = p.split('=')[1];
                        Ext.getCmp(this.id+'-combo').setValue('for');
                        Ext.getCmp(this.id+'-num').setValue(count).show();
                        Ext.getCmp(this.id+'-endlabel').show();
                    }
                    else if(p.indexOf('UNTIL') > -1){
                        var dt = p.split('=')[1];
                        Ext.getCmp(this.id+'-combo').setValue('until');
                        Ext.getCmp(this.id+'-date').setValue(Date.parseDate(dt, this.untilDateFormat)).show();
                        Ext.getCmp(this.id+'-endlabel').hide();
                    }
                }, this);
                return this;
            }
        });
        
        return [this.repeatEvery, this.weekly, this.monthly, this.yearly, this.until];
    }
});// Not currently used
/*
 * @class Extensible.form.field.DateRangeLayout
 * @extends Ext.layout.container.Container
 * @markdown
 * @private
 */
Ext.define('Extensible.form.field.DateRangeLayout', {
    extend: 'Ext.layout.container.Container',
    alias: ['layout.extensible.daterange'],
    
    onLayout: function() {
        var me = this,
            shadowCt = me.getShadowCt(),
            owner = me.owner,
            singleLine = owner.isSingleLine();
        
        me.owner.suspendLayout = true;
        
        if (singleLine) {
            shadowCt.getComponent('row1').add(owner.startDate, owner.startTime, owner.toLabel, 
                owner.endTime, owner.endDate, owner.allDay);
        }
        else {
            shadowCt.getComponent('row1').add(owner.startDate, owner.startTime, owner.toLabel);
            shadowCt.getComponent('row2').add(owner.endDate, owner.endTime, owner.allDay);
        }
        
        if (!shadowCt.rendered) {
            shadowCt.render(me.getRenderTarget());
        }

        shadowCt.doComponentLayout();
        owner.setHeight(shadowCt.getHeight()-5);
        
        delete me.owner.suspendLayout;
    },

    /**
     * @private
     * Creates and returns the shadow vbox container that will be used to arrange the owner's items
     */
    getShadowCt: function() {
        var me = this,
            items = [];

        if (!me.shadowCt) {
            me.shadowCt = Ext.createWidget('container', {
                layout: 'auto',
                anchor: '100%',
                ownerCt: me.owner,
                items: [{
                    xtype: 'container',
                    itemId: 'row1',
                    layout: 'hbox',
                    defaults:{
                        margins:'0 5 0 0'
                    }
                },{
                    xtype: 'container',
                    itemId: 'row2',
                    layout: 'hbox',
                    defaults:{
                        margins:'0 5 0 0'
                    }
                }]
            });
        }
        
        return me.shadowCt;
    },
    
    // We don't want to render any items to the owner directly, that gets handled by each column's own layout
    renderItems: Ext.emptyFn
});/**
 * @class Extensible.form.field.DateRange
 * @extends Ext.form.Field
 * <p>A combination field that includes start and end dates and times, as well as an optional all-day checkbox.</p>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Extensible.form.field.DateRange', {
    extend: 'Ext.form.FieldContainer',
    alias: 'widget.extensible.daterangefield',
    
    requires: [
        'Ext.form.field.Date',
        'Ext.form.field.Time',
        'Ext.form.Label',
        'Ext.form.field.Checkbox'
    ],
    
    /**
     * @cfg {String} toText
     * The text to display in between the date/time fields (defaults to 'to')
     */
    toText: 'to',
    /**
     * @cfg {String} allDayText
     * The text to display as the label for the all day checkbox (defaults to 'All day')
     */
    allDayText: 'All day',
    /**
     * @cfg {String/Boolean} singleLine
     * <code>true</code> to render the fields all on one line, <code>false</code> to break the start
     * date/time and end date/time into two stacked rows of fields to preserve horizontal space
     * (defaults to <code>true</code>).
     */
    singleLine: true,
    /*
     * @cfg {Number} singleLineMinWidth -- not currently used
     * If {@link singleLine} is set to 'auto' it will use this value to determine whether to render the field on one
     * line or two. This value is the approximate minimum width required to render the field on a single line, so if
     * the field's container is narrower than this value it will automatically be rendered on two lines.
     */
    //singleLineMinWidth: 490,
    /**
     * @cfg {String} dateFormat
     * The date display format used by the date fields (defaults to 'n/j/Y') 
     */
    dateFormat: 'n/j/Y',
    
    // private
    fieldLayout: {
        type: 'hbox',
        defaultMargins: { top: 0, right: 5, bottom: 0, left: 0 }
    },
    
    // private
    initComponent: function() {
        var me = this;
        /**
         * @cfg {String} timeFormat
         * The time display format used by the time fields. By default the DateRange uses the
         * {@link Extensible.Date.use24HourTime} setting and sets the format to 'g:i A' for 12-hour time (e.g., 1:30 PM) 
         * or 'G:i' for 24-hour time (e.g., 13:30). This can also be overridden by a static format string if desired.
         */
        me.timeFormat = me.timeFormat || (Extensible.Date.use24HourTime ? 'G:i' : 'g:i A');
        
        me.addCls('ext-dt-range');
        
        if (me.singleLine) {
            me.layout = me.fieldLayout;
            me.items = me.getFieldConfigs();
        }
        else {
            me.items = [{
                xtype: 'container',
                layout: me.fieldLayout,
                items: [
                    me.getStartDateConfig(),
                    me.getStartTimeConfig(),
                    me.getDateSeparatorConfig()
                ]
            },{
                xtype: 'container',
                layout: me.fieldLayout,
                items: [
                    me.getEndDateConfig(),
                    me.getEndTimeConfig(),
                    me.getAllDayConfig()
                ]
            }];
        }
        
        me.callParent(arguments);
        me.initRefs();
    },
    
    initRefs: function() {
        var me = this;
        me.startDate = me.down('#' + me.id + '-start-date');
        me.startTime = me.down('#' + me.id + '-start-time');
        me.endTime = me.down('#' + me.id + '-end-time');
        me.endDate = me.down('#' + me.id + '-end-date');
        me.allDay = me.down('#' + me.id + '-allday');
        me.toLabel = me.down('#' + me.id + '-to-label');
    },
    
    getFieldConfigs: function() {
        var me = this;
        return [
            me.getStartDateConfig(),
            me.getStartTimeConfig(),
            me.getDateSeparatorConfig(),
            me.getEndTimeConfig(),
            me.getEndDateConfig(),
            me.getAllDayConfig()
        ];
    },
    
    getLayoutItems: function(singleLine) {
        var me = this;
        return singleLine ? me.items.items : [[
            me.startDate, me.startTime, me.toLabel
        ],[
            me.endDate, me.endTime, me.allDay
        ]];
    },
    
    getStartDateConfig: function() {
        return {
            xtype: 'datefield',
            id: this.id + '-start-date',
            format: this.dateFormat,
            width: 100,
            listeners: {
                'change': {
                    fn: function(){
                        this.onFieldChange('date', 'start');
                    },
                    scope: this
                }
            }
        };
    },
    
    getStartTimeConfig: function() {
        return {
            xtype: 'timefield',
            id: this.id + '-start-time',
            hidden: this.showTimes === false,
            labelWidth: 0,
            hideLabel: true,
            width: 90,
            format: this.timeFormat,
            listeners: {
                'select': {
                    fn: function(){
                        this.onFieldChange('time', 'start');
                    },
                    scope: this
                }
            }
        };
    },
    
    getEndDateConfig: function() {
        return {
            xtype: 'datefield',
            id: this.id + '-end-date',
            format: this.dateFormat,
            hideLabel: true,
            width: 100,
            listeners: {
                'change': {
                    fn: function(){
                        this.onFieldChange('date', 'end');
                    },
                    scope: this
                }
            }
        };
    },
    
    getEndTimeConfig: function() {
        return {
            xtype: 'timefield',
            id: this.id + '-end-time',
            hidden: this.showTimes === false,
            labelWidth: 0,
            hideLabel: true,
            width: 90,
            format: this.timeFormat,
            listeners: {
                'select': {
                    fn: function(){
                        this.onFieldChange('time', 'end');
                    },
                    scope: this
                }
            }
        };
    },
    
    getAllDayConfig: function() {
        return {
            xtype: 'checkbox',
            id: this.id + '-allday',
            hidden: this.showTimes === false || this.showAllDay === false,
            boxLabel: this.allDayText,
            margins: { top: 2, right: 5, bottom: 0, left: 0 },
            handler: this.onAllDayChange,
            scope: this
        };
    },
    
    onAllDayChange: function(chk, checked) {
        this.startTime.setVisible(!checked);
        this.endTime.setVisible(!checked);
    },
    
    getDateSeparatorConfig: function() {
        return {
            xtype: 'label',
            id: this.id + '-to-label',
            text: this.toText,
            margins: { top: 4, right: 5, bottom: 0, left: 0 }
        };
    },
    
    isSingleLine: function() {
        var me = this;
        
        if (me.calculatedSingleLine === undefined) {
            if(me.singleLine == 'auto'){
                var ownerCtEl = me.ownerCt.getEl(),
                    w = me.ownerCt.getWidth() - ownerCtEl.getPadding('lr'),
                    el = ownerCtEl.down('.x-panel-body');
                    
                if(el){
                    w -= el.getPadding('lr');
                }
                
                el = ownerCtEl.down('.x-form-item-label')
                if(el){
                    w -= el.getWidth() - el.getPadding('lr');
                }
                singleLine = w <= me.singleLineMinWidth ? false : true;
            }
            else {
                me.calculatedSingleLine = me.singleLine !== undefined ? me.singleLine : true;
            }
        }
        return me.calculatedSingleLine;
    },
    
    // private
//    onRender: function(ct, position){
//        if(!this.el){
//            this.startDate = Ext.create('Ext.form.DateField', {
//                id: this.id+'-start-date',
//                format: this.dateFormat,
//                width:100,
//                listeners: {
//                    'change': {
//                        fn: function(){
//                            this.onFieldChange('date', 'start');
//                        },
//                        scope: this
//                    }
//                }
//            });
//            this.startTime = Ext.create('Ext.form.TimeField', {
//                id: this.id+'-start-time',
//                hidden: this.showTimes === false,
//                labelWidth: 0,
//                hideLabel:true,
//                width:90,
//                listeners: {
//                    'select': {
//                        fn: function(){
//                            this.onFieldChange('time', 'start');
//                        },
//                        scope: this
//                    }
//                }
//            });
//            this.endTime = Ext.create('Ext.form.TimeField', {
//                id: this.id+'-end-time',
//                hidden: this.showTimes === false,
//                labelWidth: 0,
//                hideLabel:true,
//                width:90,
//                listeners: {
//                    'select': {
//                        fn: function(){
//                            this.onFieldChange('time', 'end');
//                        },
//                        scope: this
//                    }
//                }
//            })
//            this.endDate = Ext.create('Ext.form.DateField', {
//                id: this.id+'-end-date',
//                format: this.dateFormat,
//                hideLabel:true,
//                width:100,
//                listeners: {
//                    'change': {
//                        fn: function(){
//                            this.onFieldChange('date', 'end');
//                        },
//                        scope: this
//                    }
//                }
//            });
//            this.allDay = Ext.create('Ext.form.Checkbox', {
//                id: this.id+'-allday',
//                hidden: this.showTimes === false || this.showAllDay === false,
//                boxLabel: this.allDayText,
//                handler: function(chk, checked){
//                    this.startTime.setVisible(!checked);
//                    this.endTime.setVisible(!checked);
//                },
//                scope: this
//            });
//            this.toLabel = Ext.create('Ext.form.Label', {
//                xtype: 'label',
//                id: this.id+'-to-label',
//                text: this.toText
//            });
            
//            var singleLine = this.singleLine;
//            if(singleLine == 'auto'){
//                var el, w = this.ownerCt.getWidth() - this.ownerCt.getEl().getPadding('lr');
//                if(el = this.ownerCt.getEl().child('.x-panel-body')){
//                    w -= el.getPadding('lr');
//                }
//                if(el = this.ownerCt.getEl().child('.x-form-item-label')){
//                    w -= el.getWidth() - el.getPadding('lr');
//                }
//                singleLine = w <= this.singleLineMinWidth ? false : true;
//            }
//            
//            this.fieldCt = Ext.create('Ext.Container', {
//                autoEl: {id:this.id}, //make sure the container el has the field's id
//                cls: 'ext-dt-range',
//                renderTo: ct,
//                layout: 'table',
//                layoutConfig: {
//                    columns: singleLine ? 6 : 3
//                },
//                defaults: {
//                    hideParent: true
//                },
//                items:[
//                    this.startDate,
//                    this.startTime,
//                    this.toLabel,
//                    singleLine ? this.endTime : this.endDate,
//                    singleLine ? this.endDate : this.endTime,
//                    this.allDay
//                ]
//            });
//            
//            this.fieldCt.ownerCt = this;
//            this.el = this.fieldCt.getEl();
//            this.items = Ext.create('Ext.util.MixedCollection');
//            this.items.addAll([this.startDate, this.endDate, this.toLabel, this.startTime, this.endTime, this.allDay]);
//        }
//        
//        this.callParent(arguments);
//        
//        if(!singleLine){
//            this.el.child('tr').addCls('ext-dt-range-row1');
//        }
//    },

    // private
    onFieldChange: function(type, startend){
        this.checkDates(type, startend);
        this.fireEvent('change', this, this.getValue());
    },
        
    // private
    checkDates: function(type, startend){
        var me = this,
            typeCap = type === 'date' ? 'Date' : 'Time',
            startField = this['start' + typeCap],
            endField = this['end' + typeCap],
            startValue = me.getDT('start'),
            endValue = me.getDT('end');

        if(startValue > endValue){
            if(startend=='start'){
                endField.setValue(startValue);
            }else{
                startField.setValue(endValue);
                me.checkDates(type, 'start');
            }
        }
        if(type=='date'){
            me.checkDates('time', startend);
        }
    },
    
    /**
     * Returns an array containing the following values in order:<div class="mdetail-params"><ul>
     * <li><b><code>DateTime</code></b> : <div class="sub-desc">The start date/time</div></li>
     * <li><b><code>DateTime</code></b> : <div class="sub-desc">The end date/time</div></li>
     * <li><b><code>Boolean</code></b> : <div class="sub-desc">True if the dates are all-day, false 
     * if the time values should be used</div></li><ul></div>
     * @return {Array} The array of return values
     */
    getValue: function(){
        return [
            this.getDT('start'), 
            this.getDT('end'),
            this.allDay.getValue()
        ];
    },
    
    // private getValue helper
    getDT: function(startend){
        var time = this[startend+'Time'].getValue(),
            dt = this[startend+'Date'].getValue();
            
        if(Ext.isDate(dt)){
            dt = Ext.Date.format(dt, this[startend + 'Date'].format);
        }
        else{
            return null;
        };
        if(time && time != ''){
            time = Ext.Date.format(time, this[startend+'Time'].format);
            var val = Ext.Date.parseDate(dt + ' ' + time, this[startend+'Date'].format + ' ' + this[startend+'Time'].format);
            return val;
            //return Ext.Date.parseDate(dt+' '+time, this[startend+'Date'].format+' '+this[startend+'Time'].format);
        }
        return Ext.Date.parseDate(dt, this[startend+'Date'].format);
        
    },
    
    /**
     * Sets the values to use in the date range.
     * @param {Array/Date/Object} v The value(s) to set into the field. Valid types are as follows:<div class="mdetail-params"><ul>
     * <li><b><code>Array</code></b> : <div class="sub-desc">An array containing, in order, a start date, end date and all-day flag.
     * This array should exactly match the return type as specified by {@link #getValue}.</div></li>
     * <li><b><code>DateTime</code></b> : <div class="sub-desc">A single Date object, which will be used for both the start and
     * end dates in the range.  The all-day flag will be defaulted to false.</div></li>
     * <li><b><code>Object</code></b> : <div class="sub-desc">An object containing properties for StartDate, EndDate and IsAllDay
     * as defined in {@link Extensible.calendar.data.EventMappings}.</div></li><ul></div>
     */
    setValue: function(v){
        if(!v) {
            return;
        }
        var me = this,
            eventMappings = Extensible.calendar.data.EventMappings,
            startDateName = eventMappings.StartDate.name;
            
        if(Ext.isArray(v)){
            me.setDT(v[0], 'start');
            me.setDT(v[1], 'end');
            me.allDay.setValue(!!v[2]);
        }
        else if(Ext.isDate(v)){
            me.setDT(v, 'start');
            me.setDT(v, 'end');
            me.allDay.setValue(false);
        }
        else if(v[startDateName]){ //object
            me.setDT(v[startDateName], 'start');
            if(!me.setDT(v[eventMappings.EndDate.name], 'end')){
                me.setDT(v[startDateName], 'end');
            }
            me.allDay.setValue(!!v[eventMappings.IsAllDay.name]);
        }
    },
    
    // private setValue helper
    setDT: function(dt, startend){
        if(dt && Ext.isDate(dt)){
            this[startend + 'Date'].setValue(dt);
            this[startend + 'Time'].setValue(Ext.Date.format(dt, this[startend + 'Time'].format));
            return true;
        }
    },
    
    // inherited docs
    isDirty: function(){
        var dirty = false;
        if(this.rendered && !this.disabled) {
            this.items.each(function(item){
                if (item.isDirty()) {
                    dirty = true;
                    return false;
                }
            });
        }
        return dirty;
    },
    
    // inherited docs
    reset : function(){
        this.delegateFn('reset');
    },
    
    // private
    delegateFn : function(fn){
        this.items.each(function(item){
            if (item[fn]) {
                item[fn]();
            }
        });
    },
    
    // private
    beforeDestroy: function(){
        Ext.destroy(this.fieldCt);
        this.callParent(arguments);
    },
    
    /**
     * @method getRawValue
     * @hide
     */
    getRawValue : Ext.emptyFn,
    /**
     * @method setRawValue
     * @hide
     */
    setRawValue : Ext.emptyFn
});/**
 * @class Extensible.calendar.form.field.ReminderCombo
 * @extends Ext.form.ComboBox
 * <p>A custom combo used for choosing a reminder setting for an event.</p>
 * <p>This is pretty much a standard combo that is simply pre-configured for the options needed by the
 * calendar components. The default configs are as follows:<pre><code>
width: 200,
fieldLabel: 'Reminder',
queryMode: 'local',
triggerAction: 'all',
forceSelection: true,
displayField: 'desc',
valueField: 'value',
noneText: 'None',
atStartTimeText: 'At start time',
reminderValueFormat: '{0} {1} before start'
</code></pre>
 * <p>To customize the descriptions in the dropdown list override the following methods: 
 * {@link #getMinutesText}, {@link #getHoursText}, {@link #getDaysText} and {@link #getWeeksText}.</p>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Extensible.calendar.form.field.ReminderCombo', {
    extend: 'Ext.form.ComboBox',
    alias: 'widget.extensible.remindercombo',
    
    requires: ['Ext.data.ArrayStore'],
    
    fieldLabel: 'Reminder',
    queryMode: 'local',
    triggerAction: 'all',
    forceSelection: true,
    displayField: 'desc',
    valueField: 'value',
    noneText: 'None',
    atStartTimeText: 'At start time',
    reminderValueFormat: '{0} {1} before start',
    
    // the following are all deprecated in favor of the corresponding get* template methods.
    // they are still here only for backwards-compatibility and will be removed in a future release.
    minutesText: 'minutes',
    hourText: 'hour',
    hoursText: 'hours',
    dayText: 'day',
    daysText: 'days',
    weekText: 'week',
    weeksText: 'weeks',
    
    // private
    initComponent: function() {
        this.store = this.store || Ext.create('Ext.data.ArrayStore', {
            fields: ['value', 'desc'],
            idIndex: 0,
            data: this.getValueList()
        });
        
        this.callParent(arguments);
    },
    
    /**
     * Returns the list of reminder values used as the contents of the combo list. This method is provided so that
     * the value list can be easily overridden as needed.
     * @return {Array} A 2-dimensional array of type [{String}, {String}] which contains the value and description
     * respectively of each item in the combo list. By default the value is the number of minutes for the selected 
     * time value (e.g., value 120 == '2 hours') with empty string for no value, but these can be set to anything.
     */
    getValueList: function(){
        var me = this,
            fmt = me.reminderValueFormat,
            stringFormat = Ext.String.format;
            
        return [
            ['', me.noneText],
            ['0', me.atStartTimeText],
            ['5', stringFormat(fmt, '5', me.getMinutesText(5))],
            ['15', stringFormat(fmt, '15', me.getMinutesText(15))],
            ['30', stringFormat(fmt, '30', me.getMinutesText(30))],
            ['60', stringFormat(fmt, '1', me.getHoursText(1))],
            ['90', stringFormat(fmt, '1.5', me.getHoursText(1.5))],
            ['120', stringFormat(fmt, '2', me.getHoursText(2))],
            ['180', stringFormat(fmt, '3', me.getHoursText(3))],
            ['360', stringFormat(fmt, '6', me.getHoursText(6))],
            ['720', stringFormat(fmt, '12', me.getHoursText(12))],
            ['1440', stringFormat(fmt, '1', me.getDaysText(1))],
            ['2880', stringFormat(fmt, '2', me.getDaysText(2))],
            ['4320', stringFormat(fmt, '3', me.getDaysText(3))],
            ['5760', stringFormat(fmt, '4', me.getDaysText(4))],
            ['7200', stringFormat(fmt, '5', me.getDaysText(5))],
            ['10080', stringFormat(fmt, '1', me.getWeeksText(1))],
            ['20160', stringFormat(fmt, '2', me.getWeeksText(2))]
        ]
    },
    
    /**
     * Returns the unit text to use for a reminder that has a specified number of minutes
     * prior to the due time (defaults to 'minute' when the passed value === 1, else 'minutes').
     * @param {Number} numMinutes The number of minutes prior to the due time
     * @return {String} The unit text
     */
    getMinutesText: function(numMinutes){
        return numMinutes === 1 ? this.minuteText : this.minutesText;
    },
    /**
     * Returns the unit text to use for a reminder that has a specified number of hours
     * prior to the due time (defaults to 'hour' when the passed value === 1, else 'hours').
     * @param {Number} numHours The number of hours prior to the due time
     * @return {String} The unit text
     */
    getHoursText: function(numHours){
        return numHours === 1 ? this.hourText : this.hoursText;
    },
    /**
     * Returns the unit text to use for a reminder that has a specified number of days
     * prior to the due time (defaults to 'day' when the passed value === 1, else 'days').
     * @param {Number} numDays The number of days prior to the due time
     * @return {String} The unit text
     */
    getDaysText: function(numDays){
        return numDays === 1 ? this.dayText : this.daysText;
    },
    /**
     * Returns the unit text to use for a reminder that has a specified number of weeks
     * prior to the due time (defaults to 'week' when the passed value === 1, else 'weeks').
     * @param {Number} numWeeks The number of weeks prior to the due time
     * @return {String} The unit text
     */
    getWeeksText: function(numWeeks){
        return numWeeks === 1 ? this.weekText : this.weeksText;
    },
    
    // inherited docs
    initValue : function(){
        if(this.value !== undefined){
            this.setValue(this.value);
        }
        else{
            this.setValue('');
        }
        this.originalValue = this.getValue();
    }
});/**
 * @class Extensible.calendar.util.ColorPicker
 * @extends Ext.picker.Color
 * Simple color picker class for choosing colors specifically for calendars. This is a lightly modified version
 * of the default Ext color picker that is based on calendar ids rather than hex color codes so that the colors
 * can be easily modified via CSS and automatically applied to calendars. The specific colors used by default are
 * also chosen to provide good color contrast when displayed in calendars.
</code></pre>
 * @constructor
 * Create a new color picker
 * @param {Object} config The config object
 * @xtype extensible.calendarcolorpicker
 */
Ext.define('Extensible.calendar.util.ColorPicker', {
    extend: 'Ext.picker.Color',
    alias: 'widget.extensible.calendarcolorpicker',
    
    requires: ['Ext.XTemplate'],
    
    // private
    colorCount: 32,
    
    /**
     * @cfg {Function} handler
     * Optional. A function that will handle the select event of this color picker.
     * The handler is passed the following parameters:<div class="mdetail-params"><ul>
     * <li><code>picker</code> : ColorPicker<div class="sub-desc">The picker instance.</div></li>
     * <li><code>colorId</code> : String<div class="sub-desc">The id that identifies the selected color and relates it to a calendar.</div></li>
     * </ul></div>
     */
    
    constructor: function() {
        this.renderTpl = Ext.create('Ext.XTemplate', 
            '<tpl for="colors"><a href="#" class="x-cal-{.}" hidefocus="on">' +
            '<em><span unselectable="on">&#160;</span></em></a></tpl>');
        
        this.callParent(arguments);
    },
    
    // private
    initComponent: function(){
        this.callParent(arguments);
        
        this.addCls('x-calendar-palette');
            
        if(this.handler){
            this.on('select', this.handler, this.scope || this, {
                delegate: 'a'
            });
        }
        
        this.colors = [];
        for(var i=1; i<=this.colorCount; i++){
            this.colors.push(i);
        }
    },
    
    // private
    handleClick : function(e, t){
        e.preventDefault();
        
        var colorId = t.className.split('x-cal-')[1];
        this.select(colorId);
    },
    
    /**
     * Selects the specified color in the palette (fires the {@link #select} event)
     * @param {Number} colorId The id that identifies the selected color and relates it to a calendar
     * @param {Boolean} suppressEvent (optional) True to stop the select event from firing. Defaults to <tt>false</tt>.
     */
    select : function(colorId, suppressEvent){
        var me = this,
            selectedCls = me.selectedCls,
            value = me.value;
            
        if (!me.rendered) {
            me.value = colorId;
            return;
        }
        
        if (colorId != value || me.allowReselect) {
            var el = me.el;

            if (me.value) {
                el.down('.x-cal-' + value).removeCls(selectedCls);
            }
            el.down('.x-cal-' + colorId).addCls(selectedCls);
            me.value = colorId;
            
            if (suppressEvent !== true) {
                me.fireEvent('select', me, colorId);
            }
        }
    }
});/**
 * @private
 * @class Extensible.calendar.gadget.CalendarListMenu
 * @extends Ext.menu.Menu
 * <p>A menu containing a {@link Extensible.calendar.util.ColorPicker color picker} for choosing calendar colors, 
 * as well as other calendar-specific options.</p>
 * @xtype extensible.calendarlistmenu
 */
Ext.define('Extensible.calendar.gadget.CalendarListMenu', {
    extend: 'Ext.menu.Menu',
    alias: 'widget.extensible.calendarlistmenu',
    
    requires: ['Extensible.calendar.util.ColorPicker'],
    
    /**
     * @cfg {Boolean} hideOnClick
     * False to continue showing the menu after a color is selected, defaults to true.
     */
    hideOnClick : true,
    /**
     * @cfg {Boolean} ignoreParentClicks
     * True to ignore clicks on any item in this menu that is a parent item (displays a submenu) 
     * so that the submenu is not dismissed when clicking the parent item (defaults to true).
     */
    ignoreParentClicks: true,
    /**
     * @cfg {String} displayOnlyThisCalendarText
     * The text to display for the 'Display only this calendar' option in the menu.
     */
    displayOnlyThisCalendarText: 'Display only this calendar',
    /**
     * @cfg {Number} calendarId
     * The id of the calendar to be associated with this menu. This calendarId will be passed
     * back with any events from this menu to identify the calendar to be acted upon. The calendar
     * id can also be changed at any time after creation by calling {@link setCalendar}.
     */
    
    /** 
     * @cfg {Boolean} enableScrolling
     * @hide 
     */
    enableScrolling : false,
    /** 
     * @cfg {Number} maxHeight
     * @hide 
     */
    /** 
     * @cfg {Number} scrollIncrement
     * @hide 
     */
    /**
     * @event click
     * @hide
     */
    /**
     * @event itemclick
     * @hide
     */
    
    /**
     * @property palette
     * @type ColorPicker
     * The {@link Extensible.calendar.util.ColorPicker ColorPicker} instance for this CalendarListMenu
     */
    
    // private
    initComponent : function(){
        this.addEvents(
            'showcalendar',
            'hidecalendar',
            'radiocalendar',
            'colorchange'
        );
        
        Ext.apply(this, {
            items: [{
                text: this.displayOnlyThisCalendarText,
                iconCls: 'extensible-cal-icon-cal-show',
                handler: Ext.bind(this.handleRadioCalendarClick, this)
            }, '-', {
                xtype: 'extensible.calendarcolorpicker',
                id: this.id + '-calendar-color-picker',
                handler: Ext.bind(this.handleColorSelect, this)
            }]
        });
        
        this.addClass('x-calendar-list-menu');
        this.callParent(arguments);
    },
    
    // private
    afterRender: function(){
        this.callParent(arguments);
        
        this.palette = this.down('#' + this.id + '-calendar-color-picker');
        
        if(this.colorId){
            this.palette.select(this.colorId, true);
        }
    },
    
    // private
    handleRadioCalendarClick: function(e, t){
        this.fireEvent('radiocalendar', this, this.calendarId);
    },
    
    // private
    handleColorSelect: function(cp, selColorId){
        this.fireEvent('colorchange', this, this.calendarId, selColorId, this.colorId);
        this.colorId = selColorId;
        this.menuHide();
    },
    
    /**
     * Sets the calendar id and color id to be associated with this menu. This should be called each time the
     * menu is shown relative to a new calendar.
     * @param {Number} calendarId The id of the calendar to be associated
     * @param {Number} colorId The id of the color to be pre-selected in the color palette
     * @return {Extensible.calendar.gadget.CalendarListMenu} this
     */
    setCalendar: function(id, cid){
        this.calendarId = id;
        this.colorId = cid;
        
        if(this.rendered){
            this.palette.select(cid, true);
        }
        return this;
    },

    // private
    menuHide : function(){
        if(this.hideOnClick){
            this.hide();
        }
    }
});/**
 * @class Extensible.calendar.gadget.CalendarListPanel
 * @extends Ext.Panel
 * <p>This is a {@link Ext.Panel panel} subclass that renders a list of available calendars
 * @constructor
 * @param {Object} config The config object
 * @xtype extensible.calendarlist
 */
Ext.define('Extensible.calendar.gadget.CalendarListPanel', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.extensible.calendarlist',
    
    requires: [
        'Ext.XTemplate',
        'Extensible.calendar.gadget.CalendarListMenu'
    ],
    
    title: 'Calendars',
    collapsible: true,
    autoHeight: true,
    layout: 'fit',
    menuSelector: 'em',
    width: 100, // this should be overridden by this container's layout
    
    /**
     * @cfg {Ext.data.Store} store
     * A {@link Ext.data.Store store} containing records of type {@link Extensible.calendar.data.CalendarModel CalendarRecord}.
     * This is a required config and is used to populate the calendar list.  The CalendarList widget will also listen for events from
     * the store and automatically refresh iteself in the event that the underlying calendar records in the store change.
     */
    
    // private
    initComponent: function(){
        this.addCls('x-calendar-list');
        this.callParent(arguments);
    },
    
    // private
    afterRender : function(ct, position){
        this.callParent(arguments);
        
        if(this.store){
            this.setStore(this.store, true);
        }
        this.refresh();
        
        this.body.on('click', this.onClick, this);
        this.body.on('mouseover', this.onMouseOver, this, {delegate: 'li'});
        this.body.on('mouseout', this.onMouseOut, this, {delegate: 'li'});
    },
    
    // private
    getListTemplate : function(){
        if(!this.tpl){
            this.tpl = !(Ext.isIE || Ext.isOpera) ? 
                Ext.create('Ext.XTemplate', 
                    '<ul class="x-unselectable"><tpl for=".">',
                        '<li id="{cmpId}" class="ext-cal-evr {colorCls} {hiddenCls}">{title}<em>&#160;</em></li>',
                    '</tpl></ul>'
                )
                : Ext.create('Ext.XTemplate',
                    '<ul class="x-unselectable"><tpl for=".">',
                        '<li id="{cmpId}" class="ext-cal-evo {colorCls} {hiddenCls}">',
                            '<div class="ext-cal-evm">',
                                '<div class="ext-cal-evi">{title}<em>&#160;</em></div>',
                            '</div>',
                        '</li>',
                    '</tpl></ul>'
                );
            this.tpl.compile();
        }
        return this.tpl;
    },
    
    /**
     * Sets the store used to display the available calendars. It should contain 
     * records of type {@link Extensible.calendar.data.CalendarModel CalendarRecord}.
     * @param {Ext.data.Store} store
     */
    setStore : function(store, initial){
        if(!initial && this.store){
            this.store.un("load", this.refresh, this);
            this.store.un("add", this.refresh, this);
            this.store.un("remove", this.refresh, this);
            this.store.un("update", this.onUpdate, this);
            this.store.un("clear", this.refresh, this);
        }
        if(store){
            store.on("load", this.refresh, this);
            store.on("add", this.refresh, this);
            store.on("remove", this.refresh, this);
            store.on("update", this.onUpdate, this);
            store.on("clear", this.refresh, this);
        }
        this.store = store;
    },
    
    // private
    onUpdate : function(ds, rec, operation){
        // ignore EDIT notifications, only refresh after a commit
        if(operation == Ext.data.Record.COMMIT){
            this.refresh();
        }
    },
    
    /**
     * Refreshes the calendar list so that it displays based on the most current state of
     * the underlying calendar store. Usually this method does not need to be called directly
     * as the control is automatically bound to the store's events, but it is available in the
     * event that a manual refresh is ever needed.
     */
    refresh: function(){
        if(this.skipRefresh){
            return;
        }
        var data = [], i = 0, o = null,
            CM = Extensible.calendar.data.CalendarMappings,
            recs = this.store.getRange(),
            len = recs.length;
            
        for(; i < len; i++){
            o = {
                cmpId: this.id + '__' + recs[i].data[CM.CalendarId.name],
                title: recs[i].data[CM.Title.name],
                colorCls: this.getColorCls(recs[i].data[CM.ColorId.name])
            };
            if(recs[i].data[CM.IsHidden.name] === true){
                o.hiddenCls = 'ext-cal-hidden';
            }
            data[data.length] = o;
        }
        this.getListTemplate().overwrite(this.body, data);
    },
    
    // private
    getColorCls: function(colorId){
        return 'x-cal-'+colorId+'-ad';
    },
    
    // private
    toggleCalendar: function(id, commit){
        var rec = this.store.findRecord(Extensible.calendar.data.CalendarMappings.CalendarId.name, id);
            CM = Extensible.calendar.data.CalendarMappings,
            isHidden = rec.data[CM.IsHidden.name]; 
        
        rec.set([CM.IsHidden.name], !isHidden);
        
        if(commit !== false){
            rec.commit();
        }
    },
    
    // private
    showCalendar: function(id, commit){
        var rec = this.store.findRecord(Extensible.calendar.data.CalendarMappings.CalendarId.name, id);
        if(rec.data[Extensible.calendar.data.CalendarMappings.IsHidden.name] === true){
            this.toggleCalendar(id, commit);
        }
    },
    
    // private
    hideCalendar: function(id, commit){
        var rec = this.store.findRecord(Extensible.calendar.data.CalendarMappings.CalendarId.name, id);
        if(rec.data[Extensible.calendar.data.CalendarMappings.IsHidden.name] !== true){
            this.toggleCalendar(id, commit);
        }
    },
    
    // private
    radioCalendar: function(id){
        var i = 0, recId,
            calendarId = Extensible.calendar.data.CalendarMappings.CalendarId.name,
            recs = this.store.getRange(),
            len = recs.length;
            
        for(; i < len; i++){
            recId = recs[i].data[calendarId];
            // make a truthy check so that either numeric or string ids can match
            if(recId == id){
                this.showCalendar(recId, false);
            }
            else{
                this.hideCalendar(recId, false);
            }
        }
        
        // store.commitChanges() just loops over each modified record and calls rec.commit(),
        // which in turns fires an update event that would cause a full refresh for each record.
        // To avoid this we simply set a flag and make sure we only refresh once per commit set.
        this.skipRefresh = true;
        this.store.sync();
        delete this.skipRefresh;
        this.refresh();
    },
    
    // private
    onMouseOver: function(e, t){
        Ext.fly(t).addCls('hover');
    },
    
    // private
    onMouseOut: function(e, t){
        Ext.fly(t).removeCls('hover');
    },
    
    // private
    getCalendarId: function(el){
        return el.id.split('__')[1];
    },
    
    // private
    getCalendarItemEl: function(calendarId){
        return Ext.get(this.id+'__'+calendarId);
    },
    
    // private
    onClick : function(e, t){
        var el;
        if(el = e.getTarget(this.menuSelector, 3, true)){
            this.showEventMenu(el, e.getXY());
        }
        else if(el = e.getTarget('li', 3, true)){
            this.toggleCalendar(this.getCalendarId(el));
        } 
    },
    
    // private
    handleColorChange: function(menu, id, colorId, origColorId){
        var rec = this.store.findRecord(Extensible.calendar.data.CalendarMappings.CalendarId.name, id);
        rec.data[Extensible.calendar.data.CalendarMappings.ColorId.name] = colorId;
        rec.commit();
    },
    
    // private
    handleRadioCalendar: function(menu, id){
        this.radioCalendar(id);
    },
    
    // private
    showEventMenu : function(el, xy){
        var id = this.getCalendarId(el.parent('li')),
            rec = this.store.findRecord(Extensible.calendar.data.CalendarMappings.CalendarId.name, id);
            colorId = rec.data[Extensible.calendar.data.CalendarMappings.ColorId.name];
            
        if(!this.menu){
            this.menu = Ext.create('Extensible.calendar.gadget.CalendarListMenu');
            this.menu.on('colorchange', this.handleColorChange, this);
            this.menu.on('radiocalendar', this.handleRadioCalendar, this);
        }
        this.menu.setCalendar(id, colorId);
        this.menu.showAt(xy);
    }
});/**
 * @class Extensible.calendar.menu.Event
 * @extends Ext.menu.Menu
 * The context menu displayed for calendar events in any {@link Extensible.calendar.view.AbstractCalendar CalendarView} subclass. 
 * @xtype extensible.eventcontextmenu
 */
Ext.define('Extensible.calendar.menu.Event', {
    extend: 'Ext.menu.Menu',
    alias: 'widget.extensible.eventcontextmenu',
    
    requires: ['Ext.menu.DatePicker'],
    
    /** 
     * @cfg {Boolean} hideOnClick
     * False to continue showing the menu after a color is selected, defaults to true.
     */
    hideOnClick : true,
    /**
     * @cfg {Boolean} ignoreParentClicks
     * True to ignore clicks on any item in this menu that is a parent item (displays a submenu) 
     * so that the submenu is not dismissed when clicking the parent item (defaults to true).
     */
    ignoreParentClicks: true,
    /**
     * @cfg {String} editDetailsText
     * The text to display for the 'Edit Details' option in the menu.
     */
    editDetailsText: 'Edit Details',
    /**
     * @cfg {String} deleteText
     * The text to display for the 'Delete' option in the menu.
     */
    deleteText: 'Delete',
    /**
     * @cfg {String} moveToText
     * The text to display for the 'Move to...' option in the menu.
     */
    moveToText: 'Move to...',
    
    /** 
     * @cfg {Boolean} enableScrolling
     * @hide 
     */
    enableScrolling : false,
    /** 
     * @cfg {Number} maxHeight
     * @hide 
     */
    /** 
     * @cfg {Number} scrollIncrement
     * @hide 
     */
    /**
     * @event click
     * @hide
     */
    /**
     * @event itemclick
     * @hide
     */
    
    // private
    initComponent : function(){
        this.addEvents(
            /**
             * @event editdetails
             * Fires when the user selects the option to edit the event details
             * (by default, in an instance of {@link Extensible.calendar.form.EventDetails}. Handling code should 
             * transfer the current event record to the appropriate instance of the detailed form by showing
             * the form and calling {@link Extensible.calendar.form.EventDetails#loadRecord loadRecord}.
             * @param {Extensible.calendar.menu.Event} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} that is currently being edited
             * @param {Ext.Element} el The element associated with this context menu
             */
            'editdetails',
            /**
             * @event eventdelete
             * Fires after the user selectes the option to delete an event. Note that this menu does not actually
             * delete the event from the data store. This is simply a notification that the menu option was selected --
             * it is the responsibility of handling code to perform the deletion and any clean up required.
             * @param {Extensible.calendar.menu.Event} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} for the event to be deleted
             * @param {Ext.Element} el The element associated with this context menu
             */
            'eventdelete',
            /**
             * @event eventmove
             * Fires after the user selects a date in the calendar picker under the "move event" menu option. Note that this menu does not actually
             * update the event in the data store. This is simply a notification that the menu option was selected --
             * it is the responsibility of handling code to perform the move action and any clean up required.
             * @param {Extensible.calendar.menu.Event} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} for the event to be moved
             * @param {Date} dt The new start date for the event (the existing event start time will be preserved)
             */
            'eventmove'
        );
        this.buildMenu();
        this.callParent(arguments);
    },
    
    /**
     * Overrideable method intended for customizing the menu items. This should only to be used for overriding 
     * or called from a subclass and should not be called directly from application code.
     */
    buildMenu: function(){
        if(this.rendered){
            return;
        }
        this.dateMenu = Ext.create('Ext.menu.DatePicker', {
            scope: this,
            handler: function(dp, dt){
                dt = Extensible.Date.copyTime(this.rec.data[Extensible.calendar.data.EventMappings.StartDate.name], dt);
                this.fireEvent('eventmove', this, this.rec, dt);
            }
        });
        
        Ext.apply(this, {
            items: [{
                text: this.editDetailsText,
                iconCls: 'extensible-cal-icon-evt-edit',
                scope: this,
                handler: function(){
                    this.fireEvent('editdetails', this, this.rec, this.ctxEl);
                }
            },{
                text: this.deleteText,
                iconCls: 'extensible-cal-icon-evt-del',
                scope: this,
                handler: function(){
                    this.fireEvent('eventdelete', this, this.rec, this.ctxEl);
                }
            },'-',{
                text: this.moveToText,
                iconCls: 'extensible-cal-icon-evt-move',
                menu: this.dateMenu
            }]
        });
    },
    
    /**
     * Shows the specified event at the given XY position. 
     * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} for the event
     * @param {Ext.Element} el The element associated with this context menu
     * @param {Array} xy The X & Y [x, y] values for the position at which to show the menu (coordinates are page-based) 
     */
    showForEvent: function(rec, el, xy){
        this.rec = rec;
        this.ctxEl = el;
        this.dateMenu.picker.setValue(rec.data[Extensible.calendar.data.EventMappings.StartDate.name]);
        this.showAt(xy);
    },
    
    // private
    onHide : function(){
        this.callParent(arguments);
        delete this.ctxEl;
    }
});/**
 * @class Extensible.calendar.form.EventDetails
 * @extends Ext.form.FormPanel
 * <p>A custom form used for detailed editing of events.</p>
 * <p>This is pretty much a standard form that is simply pre-configured for the options needed by the
 * calendar components. It is also configured to automatically bind records of type {@link Extensible.calendar.data.EventModel}
 * to and from the form.</p>
 * <p>This form also provides custom events specific to the calendar so that other calendar components can be easily
 * notified when an event has been edited via this component.</p>
 * <p>The default configs are as follows:</p><pre><code>
labelWidth: 65,
labelWidthRightCol: 65,
colWidthLeft: .6,
colWidthRight: .4,
title: 'Event Form',
titleTextAdd: 'Add Event',
titleTextEdit: 'Edit Event',
titleLabelText: 'Title',
datesLabelText: 'When',
reminderLabelText: 'Reminder',
notesLabelText: 'Notes',
locationLabelText: 'Location',
webLinkLabelText: 'Web Link',
calendarLabelText: 'Calendar',
repeatsLabelText: 'Repeats',
saveButtonText: 'Save',
deleteButtonText: 'Delete',
cancelButtonText: 'Cancel',
bodyStyle: 'padding:20px 20px 10px;',
border: false,
buttonAlign: 'center',
autoHeight: true // to allow for the notes field to autogrow
</code></pre>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Extensible.calendar.form.EventDetails', {
    extend: 'Ext.form.Panel',
    alias: 'widget.extensible.eventeditform',
    
    requires: [
        'Extensible.form.field.DateRange',
        'Extensible.calendar.form.field.ReminderCombo',
        'Extensible.calendar.data.EventMappings',
        'Extensible.calendar.form.field.CalendarCombo',
        'Extensible.form.recurrence.Combo',
        'Ext.layout.container.Column'
    ],
    
    labelWidth: 65,
    labelWidthRightCol: 65,
    colWidthLeft: .6,
    colWidthRight: .4,
    title: 'Event Form',
    titleTextAdd: 'Add Event',
    titleTextEdit: 'Edit Event',
    titleLabelText: 'Title',
    datesLabelText: 'When',
    reminderLabelText: 'Reminder',
    notesLabelText: 'Notes',
    locationLabelText: 'Location',
    webLinkLabelText: 'Web Link',
    calendarLabelText: 'Calendar',
    repeatsLabelText: 'Repeats',
    saveButtonText: 'Save',
    deleteButtonText: 'Delete',
    cancelButtonText: 'Cancel',
    bodyStyle: 'padding:20px 20px 10px;',
    border: false,
    buttonAlign: 'center',
    autoHeight: true, // to allow for the notes field to autogrow
    
    /* // not currently supported
     * @cfg {Boolean} enableRecurrence
     * True to show the recurrence field, false to hide it (default). Note that recurrence requires
     * something on the server-side that can parse the iCal RRULE format in order to generate the
     * instances of recurring events to display on the calendar, so this field should only be enabled
     * if the server supports it.
     */
    enableRecurrence: false,
    
    // private properties:
    layout: 'column',
    
    // private
    initComponent: function(){
        
        this.addEvents({
            /**
             * @event eventadd
             * Fires after a new event is added
             * @param {Extensible.calendar.form.EventDetails} this
             * @param {Extensible.calendar.data.EventModel} rec The new {@link Extensible.calendar.data.EventModel record} that was added
             */
            eventadd: true,
            /**
             * @event eventupdate
             * Fires after an existing event is updated
             * @param {Extensible.calendar.form.EventDetails} this
             * @param {Extensible.calendar.data.EventModel} rec The new {@link Extensible.calendar.data.EventModel record} that was updated
             */
            eventupdate: true,
            /**
             * @event eventdelete
             * Fires after an event is deleted
             * @param {Extensible.calendar.form.EventDetails} this
             * @param {Extensible.calendar.data.EventModel} rec The new {@link Extensible.calendar.data.EventModel record} that was deleted
             */
            eventdelete: true,
            /**
             * @event eventcancel
             * Fires after an event add/edit operation is canceled by the user and no store update took place
             * @param {Extensible.calendar.form.EventDetails} this
             * @param {Extensible.calendar.data.EventModel} rec The new {@link Extensible.calendar.data.EventModel record} that was canceled
             */
            eventcancel: true
        });
                
        this.titleField = Ext.create('Ext.form.TextField', {
            fieldLabel: this.titleLabelText,
            name: Extensible.calendar.data.EventMappings.Title.name,
            anchor: '90%'
        });
        this.dateRangeField = Ext.create('Extensible.form.field.DateRange', {
            fieldLabel: this.datesLabelText,
            singleLine: false,
            anchor: '90%',
            listeners: {
                'change': Ext.bind(this.onDateChange, this)
            }
        });
        this.reminderField = Ext.create('Extensible.calendar.form.field.ReminderCombo', {
            name: Extensible.calendar.data.EventMappings.Reminder.name,
            fieldLabel: this.reminderLabelText,
            anchor: '70%'
        });
        this.notesField = Ext.create('Ext.form.TextArea', {
            fieldLabel: this.notesLabelText,
            name: Extensible.calendar.data.EventMappings.Notes.name,
            grow: true,
            growMax: 150,
            anchor: '100%'
        });
        this.locationField = Ext.create('Ext.form.TextField', {
            fieldLabel: this.locationLabelText,
            name: Extensible.calendar.data.EventMappings.Location.name,
            anchor: '100%'
        });
        this.urlField = Ext.create('Ext.form.TextField', {
            fieldLabel: this.webLinkLabelText,
            name: Extensible.calendar.data.EventMappings.Url.name,
            anchor: '100%'
        });
        
        var leftFields = [this.titleField, this.dateRangeField, this.reminderField], 
            rightFields = [this.notesField, this.locationField, this.urlField];
            
        if(this.enableRecurrence){
            this.recurrenceField = Ext.create('Extensible.form.recurrence.Fieldset', {
                name: Extensible.calendar.data.EventMappings.RRule.name,
                fieldLabel: this.repeatsLabelText,
                anchor: '90%'
            });
            leftFields.splice(2, 0, this.recurrenceField);
        }
        
        if(this.calendarStore){
            this.calendarField = Ext.create('Extensible.calendar.form.field.CalendarCombo', {
                store: this.calendarStore,
                fieldLabel: this.calendarLabelText,
                name: Extensible.calendar.data.EventMappings.CalendarId.name,
                anchor: '70%'
            });
            leftFields.splice(2, 0, this.calendarField);
        };
        
        this.items = [{
            id: this.id+'-left-col',
            columnWidth: this.colWidthLeft,
            layout: 'anchor',
            fieldDefaults: {
                labelWidth: this.labelWidth
            },
            border: false,
            items: leftFields
        },{
            id: this.id+'-right-col',
            columnWidth: this.colWidthRight,
            layout: 'anchor',
            fieldDefaults: {
                labelWidth: this.labelWidthRightCol || this.labelWidth
            },
            border: false,
            items: rightFields
        }];
        
        this.fbar = [{
            text:this.saveButtonText, scope: this, handler: this.onSave
        },{
            itemId:this.id+'-del-btn', text:this.deleteButtonText, scope:this, handler:this.onDelete
        },{
            text:this.cancelButtonText, scope: this, handler: this.onCancel
        }];
        
        this.addCls('ext-evt-edit-form');
        
        this.callParent(arguments);
    },
    
    // private
    onDateChange: function(dateRangeField, val){
        if(this.recurrenceField){
            this.recurrenceField.setStartDate(val[0]);
        }
    },
    
    // inherited docs
    loadRecord: function(rec){
        this.form.reset().loadRecord.apply(this.form, arguments);
        this.activeRecord = rec;
        this.dateRangeField.setValue(rec.data);
        
        if(this.recurrenceField){
            this.recurrenceField.setStartDate(rec.data[Extensible.calendar.data.EventMappings.StartDate.name]);
        }
        if(this.calendarStore){
            this.form.setValues({'calendar': rec.data[Extensible.calendar.data.EventMappings.CalendarId.name]});
        }
        
        //this.isAdd = !!rec.data[Extensible.calendar.data.EventMappings.IsNew.name];
        if(rec.phantom){
            this.setTitle(this.titleTextAdd);
            this.down('#' + this.id + '-del-btn').hide();
        }
        else {
            this.setTitle(this.titleTextEdit);
            this.down('#' + this.id + '-del-btn').show();
        }
        this.titleField.focus();
    },
    
    // inherited docs
    updateRecord: function(){
        var dates = this.dateRangeField.getValue(),
            M = Extensible.calendar.data.EventMappings,
            rec = this.activeRecord,
            fs = rec.fields,
            dirty = false;
            
        rec.beginEdit();
        
        //TODO: This block is copied directly from BasicForm.updateRecord.
        // Unfortunately since that method internally calls begin/endEdit all
        // updates happen and the record dirty status is reset internally to
        // that call. We need the dirty status, plus currently the DateRangeField
        // does not map directly to the record values, so for now we'll duplicate
        // the setter logic here (we need to be able to pick up any custom-added 
        // fields generically). Need to revisit this later and come up with a better solution.
        fs.each(function(f){
            var field = this.form.findField(f.name);
            if(field){
                var value = field.getValue();
                if (value.getGroupValue) {
                    value = value.getGroupValue();
                } 
                else if (field.eachItem) {
                    value = [];
                    field.eachItem(function(item){
                        value.push(item.getValue());
                    });
                }
                rec.set(f.name, value);
            }
        }, this);
        
        rec.set(M.StartDate.name, dates[0]);
        rec.set(M.EndDate.name, dates[1]);
        rec.set(M.IsAllDay.name, dates[2]);
        
        dirty = rec.dirty;
        //delete rec.store; // make sure the record does not try to autosave
        rec.endEdit();
        
        return dirty;
    },
    
    // private
    onCancel: function(){
        this.cleanup(true);
        this.fireEvent('eventcancel', this, this.activeRecord);
    },
    
    // private
    cleanup: function(hide){
        if (this.activeRecord) {
            this.activeRecord.reject();
        }
        delete this.activeRecord;
        
        if (this.form.isDirty()) {
            this.form.reset();
        }
    },
    
    // private
    onSave: function(){
        if(!this.form.isValid()){
            return;
        }
        if(!this.updateRecord()){
            this.onCancel();
            return;
        }
        this.fireEvent(this.activeRecord.phantom ? 'eventadd' : 'eventupdate', this, this.activeRecord);
    },

    // private
    onDelete: function(){
        this.fireEvent('eventdelete', this, this.activeRecord);
    }
});/**
 * @class Extensible.calendar.form.EventWindow
 * @extends Ext.Window
 * <p>A custom window containing a basic edit form used for quick editing of events.</p>
 * <p>This window also provides custom events specific to the calendar so that other calendar components can be easily
 * notified when an event has been edited via this component.</p>
 * <p>The default configs are as follows:</p><pre><code>
    // Locale configs
    titleTextAdd: 'Add Event',
    titleTextEdit: 'Edit Event',
    width: 600,
    labelWidth: 65,
    detailsLinkText: 'Edit Details...',
    savingMessage: 'Saving changes...',
    deletingMessage: 'Deleting event...',
    saveButtonText: 'Save',
    deleteButtonText: 'Delete',
    cancelButtonText: 'Cancel',
    titleLabelText: 'Title',
    datesLabelText: 'When',
    calendarLabelText: 'Calendar',
    
    // General configs
    closeAction: 'hide',
    modal: false,
    resizable: false,
    constrain: true,
    buttonAlign: 'left',
    editDetailsLinkClass: 'edit-dtl-link',
    enableEditDetails: true,
    bodyStyle: 'padding: 8px 10px 5px;',
    layout: 'fit'
</code></pre>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Extensible.calendar.form.EventWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.extensible.eventeditwindow',
    
    requires: [
        'Ext.form.Panel',
        'Extensible.calendar.data.EventModel',
        'Extensible.calendar.data.EventMappings'
    ],
    
    // Locale configs
    titleTextAdd: 'Add Event',
    titleTextEdit: 'Edit Event',
    width: 600,
    labelWidth: 65,
    detailsLinkText: 'Edit Details...',
    savingMessage: 'Saving changes...',
    deletingMessage: 'Deleting event...',
    saveButtonText: 'Save',
    deleteButtonText: 'Delete',
    cancelButtonText: 'Cancel',
    titleLabelText: 'Title',
    datesLabelText: 'When',
    calendarLabelText: 'Calendar',
    
    // General configs
    closeAction: 'hide',
    modal: false,
    resizable: false,
    constrain: true,
    buttonAlign: 'left',
    editDetailsLinkClass: 'edit-dtl-link',
    enableEditDetails: true,
    layout: 'fit',
    
    formPanelConfig: {
        border: false
    },
    
    // private
    initComponent: function(){
        this.addEvents({
            /**
             * @event eventadd
             * Fires after a new event is added
             * @param {Extensible.calendar.form.EventWindow} this
             * @param {Extensible.calendar.data.EventModel} rec The new {@link Extensible.calendar.data.EventModel record} that was added
             * @param {Ext.Element} el The target element
             */
            eventadd: true,
            /**
             * @event eventupdate
             * Fires after an existing event is updated
             * @param {Extensible.calendar.form.EventWindow} this
             * @param {Extensible.calendar.data.EventModel} rec The new {@link Extensible.calendar.data.EventModel record} that was updated
             * @param {Ext.Element} el The target element
             */
            eventupdate: true,
            /**
             * @event eventdelete
             * Fires after an event is deleted
             * @param {Extensible.calendar.form.EventWindow} this
             * @param {Extensible.calendar.data.EventModel} rec The new {@link Extensible.calendar.data.EventModel record} that was deleted
             * @param {Ext.Element} el The target element
             */
            eventdelete: true,
            /**
             * @event eventcancel
             * Fires after an event add/edit operation is canceled by the user and no store update took place
             * @param {Extensible.calendar.form.EventWindow} this
             * @param {Extensible.calendar.data.EventModel} rec The new {@link Extensible.calendar.data.EventModel record} that was canceled
             * @param {Ext.Element} el The target element
             */
            eventcancel: true,
            /**
             * @event editdetails
             * Fires when the user selects the option in this window to continue editing in the detailed edit form
             * (by default, an instance of {@link Extensible.calendar.form.EventDetails}. Handling code should hide this window
             * and transfer the current event record to the appropriate instance of the detailed form by showing it
             * and calling {@link Extensible.calendar.form.EventDetails#loadRecord loadRecord}.
             * @param {Extensible.calendar.form.EventWindow} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} that is currently being edited
             * @param {Ext.Element} el The target element
             */
            editdetails: true
        });
        
        this.fbar = this.getFooterBarConfig();
        
        this.callParent(arguments);
    },
    
    getFooterBarConfig: function() {
        var cfg = ['->', {
                text: this.saveButtonText,
                itemId: this.id + '-save-btn',
                disabled: false,
                handler: this.onSave, 
                scope: this
            },{
                text: this.deleteButtonText, 
                itemId: this.id + '-delete-btn',
                disabled: false,
                handler: this.onDelete,
                scope: this,
                hideMode: 'offsets' // IE requires this
            },{
                text: this.cancelButtonText,
                itemId: this.id + '-cancel-btn',
                disabled: false,
                handler: this.onCancel,
                scope: this
            }];
        
        if(this.enableEditDetails !== false){
            cfg.unshift({
                xtype: 'tbtext',
                itemId: this.id + '-details-btn',
                text: '<a href="#" class="' + this.editDetailsLinkClass + '">' + this.detailsLinkText + '</a>'
            });
        }
        return cfg;
    },
    
    // private
    onRender : function(ct, position){        
        this.formPanel = Ext.create('Ext.FormPanel', Ext.applyIf({
            fieldDefaults: {
                labelWidth: this.labelWidth
            },
            items: this.getFormItemConfigs()
        }, this.formPanelConfig));
        
        this.add(this.formPanel);
        
        this.callParent(arguments);
    },
    
    getFormItemConfigs: function() {
        var items = [{
            xtype: 'textfield',
            itemId: this.id + '-title',
            name: Extensible.calendar.data.EventMappings.Title.name,
            fieldLabel: this.titleLabelText,
            anchor: '100%'
        },{
            xtype: 'extensible.daterangefield',
            itemId: this.id + '-dates',
            name: 'dates',
            anchor: '95%',
            singleLine: true,
            fieldLabel: this.datesLabelText
        }];
        
        if(this.calendarStore){
            items.push({
                xtype: 'extensible.calendarcombo',
                itemId: this.id + '-calendar',
                name: Extensible.calendar.data.EventMappings.CalendarId.name,
                anchor: '100%',
                fieldLabel: this.calendarLabelText,
                store: this.calendarStore
            });
        }
        
        return items;
    },

    // private
    afterRender: function(){
        this.callParent(arguments);
		
		this.el.addCls('ext-cal-event-win');
        
        this.initRefs();
        
        // This junk spacer item gets added to the fbar by Ext (fixed in 4.0.2)
        var junkSpacer = this.getDockedItems('toolbar')[0].items.items[0];
        if (junkSpacer.el.hasCls('x-component-default')) {
            Ext.destroy(junkSpacer);
        }
    },
    
    initRefs: function() {
        // toolbar button refs
        this.saveButton = this.down('#' + this.id + '-save-btn');
        this.deleteButton = this.down('#' + this.id + '-delete-btn');
        this.cancelButton = this.down('#' + this.id + '-cancel-btn');
        this.detailsButton = this.down('#' + this.id + '-details-btn');
        
        if (this.detailsButton) {
            this.detailsButton.getEl().on('click', this.onEditDetailsClick, this);
        }
        
        // form item refs
        this.titleField = this.down('#' + this.id + '-title');
        this.dateRangeField = this.down('#' + this.id + '-dates');
        this.calendarField = this.down('#' + this.id + '-calendar');
    },
    
    // private
    onEditDetailsClick: function(e){
        e.stopEvent();
        this.updateRecord(this.activeRecord, true);
        this.fireEvent('editdetails', this, this.activeRecord, this.animateTarget);
    },
	
	/**
     * Shows the window, rendering it first if necessary, or activates it and brings it to front if hidden.
	 * @param {Ext.data.Record/Object} o Either a {@link Ext.data.Record} if showing the form
	 * for an existing event in edit mode, or a plain object containing a StartDate property (and 
	 * optionally an EndDate property) for showing the form in add mode. 
     * @param {String/Element} animateTarget (optional) The target element or id from which the window should
     * animate while opening (defaults to null with no animation)
     * @return {Ext.Window} this
     */
    show: function(o, animateTarget){
		// Work around the CSS day cell height hack needed for initial render in IE8/strict:
		this.animateTarget = (Ext.isIE8 && Ext.isStrict) ? null : animateTarget,
            M = Extensible.calendar.data.EventMappings;

        this.callParent([this.animateTarget, function(){
            this.titleField.focus(false, 100);
        }, this]);
        
        this.deleteButton[o.data && o.data[M.EventId.name] ? 'show' : 'hide']();
        
        var rec, f = this.formPanel.form;

        if(o.data){
            rec = o;
			//this.isAdd = !!rec.data[Extensible.calendar.data.EventMappings.IsNew.name];
			if(rec.phantom){
				// Enable adding the default record that was passed in
				// if it's new even if the user makes no changes 
				//rec.markDirty();
				this.setTitle(this.titleTextAdd);
			}
			else{
				this.setTitle(this.titleTextEdit);
			}
            
            f.loadRecord(rec);
        }
        else{
			//this.isAdd = true;
            this.setTitle(this.titleTextAdd);

            var start = o[M.StartDate.name],
                end = o[M.EndDate.name] || Extensible.Date.add(start, {hours: 1});
                
            rec = Ext.create('Extensible.calendar.data.EventModel');
            //rec.data[M.EventId.name] = this.newId++;
            rec.data[M.StartDate.name] = start;
            rec.data[M.EndDate.name] = end;
            rec.data[M.IsAllDay.name] = !!o[M.IsAllDay.name] || start.getDate() != Extensible.Date.add(end, {millis: 1}).getDate();
            
            f.reset();
            f.loadRecord(rec);
        }
        
        if(this.calendarStore){
            this.calendarField.setValue(rec.data[M.CalendarId.name]);
        }
        this.dateRangeField.setValue(rec.data);
        this.activeRecord = rec;
        //this.el.setStyle('z-index', 12000);
        
		return this;
    },
    
    // private
    roundTime: function(dt, incr){
        incr = incr || 15;
        var m = parseInt(dt.getMinutes());
        return dt.add('mi', incr - (m % incr));
    },
    
    // private
    onCancel: function(){
    	this.cleanup(true);
		this.fireEvent('eventcancel', this, this.activeRecord, this.animateTarget);
    },

    // private
    cleanup: function(hide){
        if (this.activeRecord) {
            this.activeRecord.reject();
        }
        delete this.activeRecord;
		
        if (hide===true) {
			// Work around the CSS day cell height hack needed for initial render in IE8/strict:
			//var anim = afterDelete || (Ext.isIE8 && Ext.isStrict) ? null : this.animateTarget;
            this.hide();
        }
    },
    
    // private
//    updateRecord: function(keepEditing){
//        var dates = this.dateRangeField.getValue(),
//            M = Extensible.calendar.data.EventMappings,
//            rec = this.activeRecord,
//            form = this.formPanel.form,
//            fs = rec.fields,
//            dirty = false;
//            
//        rec.beginEdit();
//
//        //TODO: This block is copied directly from BasicForm.updateRecord.
//        // Unfortunately since that method internally calls begin/endEdit all
//        // updates happen and the record dirty status is reset internally to
//        // that call. We need the dirty status, plus currently the DateRangeField
//        // does not map directly to the record values, so for now we'll duplicate
//        // the setter logic here (we need to be able to pick up any custom-added 
//        // fields generically). Need to revisit this later and come up with a better solution.
//        fs.each(function(f){
//            var field = form.findField(f.name);
//            if(field){
//                var value = field.getValue();
//                if (value.getGroupValue) {
//                    value = value.getGroupValue();
//                } 
//                else if (field.eachItem) {
//                    value = [];
//                    field.eachItem(function(item){
//                        value.push(item.getValue());
//                    });
//                }
//                rec.set(f.name, value);
//            }
//        }, this);
//        
//        rec.set(M.StartDate.name, dates[0]);
//        rec.set(M.EndDate.name, dates[1]);
//        rec.set(M.IsAllDay.name, dates[2]);
//        
//        dirty = rec.dirty;
//        
//        if(!keepEditing){
//            rec.endEdit();
//        }
//        
//        return dirty;
//    },
    
    updateRecord: function(record, keepEditing) {
        var fields = record.fields,
            values = this.formPanel.getForm().getValues(),
            name,
            M = Extensible.calendar.data.EventMappings,
            obj = {};

        fields.each(function(f) {
            name = f.name;
            if (name in values) {
                obj[name] = values[name];
            }
        });
        
        var dates = this.dateRangeField.getValue();
        obj[M.StartDate.name] = dates[0];
        obj[M.EndDate.name] = dates[1];
        obj[M.IsAllDay.name] = dates[2];

        record.beginEdit();
        record.set(obj);
        
        if (!keepEditing) {
            record.endEdit();
        }

        return this;
    },
    
    // private
    onSave: function(){
        if(!this.formPanel.form.isValid()){
            return;
        }
		if(!this.updateRecord(this.activeRecord)){
			this.onCancel();
			return;
		}
		this.fireEvent(this.activeRecord.phantom ? 'eventadd' : 'eventupdate', this, this.activeRecord, this.animateTarget);
    },
    
    // private
    onDelete: function(){
		this.fireEvent('eventdelete', this, this.activeRecord, this.animateTarget);
    }
});/**
 * @class Extensible.calendar.view.AbstractCalendar
 * @extends Ext.BoxComponent
 * <p>This is an abstract class that serves as the base for other calendar views. This class is not
 * intended to be directly instantiated.</p>
 * <p>When extending this class to create a custom calendar view, you must provide an implementation
 * for the <code>renderItems</code> method, as there is no default implementation for rendering events
 * The rendering logic is totally dependent on how the UI structures its data, which
 * is determined by the underlying UI template (this base class does not have a template).</p>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Extensible.calendar.view.AbstractCalendar', {
    extend: 'Ext.Component',
    
    requires: [
        'Ext.CompositeElement'
    ],
    
    requires: [
        'Extensible.calendar.form.EventDetails',
        'Extensible.calendar.form.EventWindow',
        'Extensible.calendar.menu.Event',
        'Extensible.calendar.dd.DragZone',
        'Extensible.calendar.dd.DropZone'
    ],
    
    /**
     * @cfg {Ext.data.Store} eventStore
     * The {@link Ext.data.Store store} which is bound to this calendar and contains {@link Extensible.calendar.data.EventModel EventRecords}.
     * Note that this is an alias to the default {@link #store} config (to differentiate that from the optional {@link #calendarStore}
     * config), and either can be used interchangeably.
     */
    /**
     * @cfg {Ext.data.Store} calendarStore
     * The {@link Ext.data.Store store} which is bound to this calendar and contains {@link Extensible.calendar.data.CalendarModel CalendarRecords}.
     * This is an optional store that provides multi-calendar (and multi-color) support. If available an additional field for selecting the
     * calendar in which to save an event will be shown in the edit forms. If this store is not available then all events will simply use
     * the default calendar (and color).
     */
    /*
     * @cfg {Boolean} enableRecurrence
     * True to show the recurrence field, false to hide it (default). Note that recurrence requires
     * something on the server-side that can parse the iCal RRULE format in order to generate the
     * instances of recurring events to display on the calendar, so this field should only be enabled
     * if the server supports it.
     */
    //enableRecurrence: false,
    /**
     * @cfg {Boolean} readOnly
     * True to prevent clicks on events or the view from providing CRUD capabilities, false to enable CRUD (the default).
     */
    /**
     * @cfg {Number} startDay
     * The 0-based index for the day on which the calendar week begins (0=Sunday, which is the default)
     */
    startDay : 0,
    /**
     * @cfg {Boolean} spansHavePriority
     * Allows switching between two different modes of rendering events that span multiple days. When true,
     * span events are always sorted first, possibly at the expense of start dates being out of order (e.g., 
     * a span event that starts at 11am one day and spans into the next day would display before a non-spanning 
     * event that starts at 10am, even though they would not be in date order). This can lead to more compact
     * layouts when there are many overlapping events. If false (the default), events will always sort by start date
     * first which can result in a less compact, but chronologically consistent layout.
     */
    spansHavePriority: false,
    /**
     * @cfg {Boolean} trackMouseOver
     * Whether or not the view tracks and responds to the browser mouseover event on contained elements (defaults to
     * true). If you don't need mouseover event highlighting you can disable this.
     */
	trackMouseOver: true,
    /**
     * @cfg {Boolean} enableFx
     * Determines whether or not visual effects for CRUD actions are enabled (defaults to true). If this is false
     * it will override any values for {@link #enableAddFx}, {@link #enableUpdateFx} or {@link enableRemoveFx} and
     * all animations will be disabled.
     */
	enableFx: true,
    /**
     * @cfg {Boolean} enableAddFx
     * True to enable a visual effect on adding a new event (the default), false to disable it. Note that if 
     * {@link #enableFx} is false it will override this value. The specific effect that runs is defined in the
     * {@link #doAddFx} method.
     */
	enableAddFx: true,
    /**
     * @cfg {Boolean} enableUpdateFx
     * True to enable a visual effect on updating an event, false to disable it (the default). Note that if 
     * {@link #enableFx} is false it will override this value. The specific effect that runs is defined in the
     * {@link #doUpdateFx} method.
     */
	enableUpdateFx: false,
    /**
     * @cfg {Boolean} enableRemoveFx
     * True to enable a visual effect on removing an event (the default), false to disable it. Note that if 
     * {@link #enableFx} is false it will override this value. The specific effect that runs is defined in the
     * {@link #doRemoveFx} method.
     */
	enableRemoveFx: true,
    /**
     * @cfg {Boolean} enableDD
     * True to enable drag and drop in the calendar view (the default), false to disable it
     */
    enableDD: true,
    /**
     * @cfg {Boolean} enableContextMenus
     * True to enable automatic right-click context menu handling in the calendar views (the default), false to disable
     * them. Different context menus are provided when clicking on events vs. the view background.
     */
    enableContextMenus: true,
    /**
     * @cfg {Boolean} suppressBrowserContextMenu
     * When {@link #enableContextMenus} is true, the browser context menu will automatically be suppressed whenever a
     * custom context menu is displayed. When this option is true, right-clicks on elements that do not have a custom
     * context menu will also suppress the default browser context menu (no menu will be shown at all). When false,
     * the browser context menu will still show if the right-clicked element has no custom menu (this is the default).
     */
    suppressBrowserContextMenu: false,
    /**
     * @cfg {Boolean} monitorResize
     * True to monitor the browser's resize event (the default), false to ignore it. If the calendar view is rendered
     * into a fixed-size container this can be set to false. However, if the view can change dimensions (e.g., it's in 
     * fit layout in a viewport or some other resizable container) it is very important that this config is true so that
     * any resize event propagates properly to all subcomponents and layouts get recalculated properly.
     */
    monitorResize: true,
    /**
     * @cfg {String} todayText
     * The text to display in the current day's box in the calendar when {@link #showTodayText} is true (defaults to 'Today')
     */
    todayText: 'Today',
    /**
     * @cfg {String} ddCreateEventText
     * The text to display inside the drag proxy while dragging over the calendar to create a new event (defaults to 
     * 'Create event for {0}' where {0} is a date range supplied by the view)
     */
	ddCreateEventText: 'Create event for {0}',
    /**
     * @cfg {String} ddMoveEventText
     * The text to display inside the drag proxy while dragging an event to reposition it (defaults to 
     * 'Move event to {0}' where {0} is the updated event start date/time supplied by the view)
     */
	ddMoveEventText: 'Move event to {0}',
    /**
     * @cfg {String} ddResizeEventText
     * The string displayed to the user in the drag proxy while dragging the resize handle of an event (defaults to 
     * 'Update event to {0}' where {0} is the updated event start-end range supplied by the view). Note that 
     * this text is only used in views
     * that allow resizing of events.
     */
    ddResizeEventText: 'Update event to {0}',
    /**
     * @cfg {String} defaultEventTitleText
     * The default text to display as the title of an event that has a null or empty string title value (defaults to '(No title)')
     */
    defaultEventTitleText: '(No title)',
    /**
     * @cfg {String} dateParamStart
     * The param name representing the start date of the current view range that's passed in requests to retrieve events
     * when loading the view (defaults to 'startDate').
     */
    dateParamStart: 'startDate',
    /**
     * @cfg {String} dateParamEnd
     * The param name representing the end date of the current view range that's passed in requests to retrieve events
     * when loading the view (defaults to 'endDate').
     */
    dateParamEnd: 'endDate',
    /**
     * @cfg {String} dateParamFormat
     * The format to use for date parameters sent with requests to retrieve events for the calendar (defaults to 'Y-m-d', e.g. '2010-10-31')
     */
    dateParamFormat: 'Y-m-d',
    /**
     * @cfg {Boolean} editModal
     * True to show the default event editor window modally over the entire page, false to allow user interaction with the page
     * while showing the window (the default). Note that if you replace the default editor window with some alternate component this
     * config will no longer apply. 
     */
    editModal: false,
    /**
     * @cfg {Boolean} enableEditDetails
     * True to show a link on the event edit window to allow switching to the detailed edit form (the default), false to remove the
     * link and disable detailed event editing. 
     */
    enableEditDetails: true,
    /**
     * @cfg {String} weekendCls
     * A CSS class to apply to weekend days in the current view (defaults to 'ext-cal-day-we' which highlights weekend days in light blue). 
     * To disable this styling set the value to null or ''. 
     */
    weekendCls: 'ext-cal-day-we',
    /**
     * @cfg {String} prevMonthCls
     * A CSS class to apply to any days that fall in the month previous to the current view's month (defaults to 'ext-cal-day-prev' which 
     * highlights previous month days in light gray). To disable this styling set the value to null or ''. 
     */
    prevMonthCls: 'ext-cal-day-prev',
    /**
     * @cfg {String} nextMonthCls
     * A CSS class to apply to any days that fall in the month after the current view's month (defaults to 'ext-cal-day-next' which 
     * highlights next month days in light gray). To disable this styling set the value to null or ''. 
     */
    nextMonthCls: 'ext-cal-day-next',
    /**
     * @cfg {String} todayCls
     * A CSS class to apply to the current date when it is visible in the current view (defaults to 'ext-cal-day-today' which 
     * highlights today in yellow). To disable this styling set the value to null or ''.
     */
    todayCls: 'ext-cal-day-today',
    /**
     * @cfg {String} hideMode
     * <p>How this component should be hidden. Supported values are <tt>'visibility'</tt>
     * (css visibility), <tt>'offsets'</tt> (negative offset position) and <tt>'display'</tt>
     * (css display).</p>
     * <br><p><b>Note</b>: For calendar views the default is 'offsets' rather than the Ext JS default of
     * 'display' in order to preserve scroll position after hiding/showing a scrollable view like Day or Week.</p>
     */
    hideMode: 'offsets',
    
    /**
     * @property ownerCalendarPanel
     * @type Extensible.calendar.CalendarPanel
     * If this view is hosted inside a {@link Extensible.calendar.CalendarPanel CalendarPanel} this property will reference
     * it. If the view was created directly outside of a CalendarPanel this property will be null. Read-only.
     */
    
    //private properties -- do not override:
    weekCount: 1,
    dayCount: 1,
    eventSelector : '.ext-cal-evt',
    eventOverClass: 'ext-evt-over',
	eventElIdDelimiter: '-evt-',
    dayElIdDelimiter: '-day-',
    
    /**
     * Returns a string of HTML template markup to be used as the body portion of the event template created
     * by {@link #getEventTemplate}. This provides the flexibility to customize what's in the body without
     * having to override the entire XTemplate. This string can include any valid {@link Ext.Template} code, and
     * any data tokens accessible to the containing event template can be referenced in this string.
     * @return {String} The body template string
     */
    getEventBodyMarkup : Ext.emptyFn, // must be implemented by a subclass
    
    /**
     * <p>Returns the XTemplate that is bound to the calendar's event store (it expects records of type
     * {@link Extensible.calendar.data.EventModel}) to populate the calendar views with events. Internally this method
     * by default generates different markup for browsers that support CSS border radius and those that don't.
     * This method can be overridden as needed to customize the markup generated.</p>
     * <p>Note that this method calls {@link #getEventBodyMarkup} to retrieve the body markup for events separately
     * from the surrounding container markup.  This provides the flexibility to customize what's in the body without
     * having to override the entire XTemplate. If you do override this method, you should make sure that your 
     * overridden version also does the same.</p>
     * @return {Ext.XTemplate} The event XTemplate
     */
    getEventTemplate : Ext.emptyFn, // must be implemented by a subclass
    
    /**
     * This is undefined by default, but can be implemented to allow custom CSS classes and template data to be
     * conditionally applied to events during rendering. This function will be called with the parameter list shown
     * below and is expected to return the CSS class name (or empty string '' for none) that will be added to the 
     * event element's wrapping div. To apply multiple class names, simply return them space-delimited within the 
     * string (e.g., 'my-class another-class'). Example usage, applied in a CalendarPanel config:
     * <pre><code>
// This example assumes a custom field of 'IsHoliday' has been added to EventRecord
viewConfig: {
    getEventClass: function(rec, allday, templateData, store){
        if(rec.data.IsHoliday){
            templateData.iconCls = 'holiday';
            return 'evt-holiday';
        }
        templateData.iconCls = 'plain';
        return '';
    },
    getEventBodyMarkup : function(){
        // This is simplified, but shows the symtax for how you could add a
        // custom placeholder that maps back to the templateData property created
        // in getEventClass. Note that this is standard Ext template syntax.
        if(!this.eventBodyMarkup){
            this.eventBodyMarkup = '&lt;span class="{iconCls}">&lt;/span> {Title}';
        }
        return this.eventBodyMarkup;
    }
}
</code></pre>
     * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} being rendered
     * @param {Boolean} isAllDay A flag indicating whether the event will be <em>rendered</em> as an all-day event. Note that this
     * will not necessarily correspond with the value of the <tt>EventRecord.IsAllDay</tt> field &mdash; events that span multiple
     * days will be rendered using the all-day event template regardless of the field value. If your logic for this function
     * needs to know whether or not the event will be rendered as an all-day event, this value should be used. 
     * @param {Object} templateData A plain JavaScript object that is empty by default. You can add custom properties
     * to this object that will then be passed into the event template for the specific event being rendered. If you have 
     * overridden the default event template and added custom data placeholders, you can use this object to pass the data
     * into the template that will replace those placeholders.
     * @param {Ext.data.Store} store The Event data store in use by the view
     * @method getEventClass
     * @return {String} A space-delimited CSS class string (or '')
     */
    
    // private
    initComponent : function(){
        this.setStartDate(this.startDate || new Date());
        
        this.callParent(arguments);
        
        if(this.readOnly === true){
            this.addCls('ext-cal-readonly');
        }
		
        this.addEvents({
            /**
             * @event eventsrendered
             * Fires after events are finished rendering in the view
             * @param {Extensible.calendar.view.AbstractCalendar} this 
             */
            eventsrendered: true,
            /**
             * @event eventclick
             * Fires after the user clicks on an event element. This is a cancelable event, so returning false from a 
             * handler will cancel the click without displaying the event editor view. This could be useful for 
             * validating the rules by which events should be editable by the user.
             * @param {Extensible.calendar.view.AbstractCalendar} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} for the event that was clicked on
             * @param {HTMLNode} el The DOM node that was clicked on
             */
            eventclick: true,
            /**
             * @event eventover
             * Fires anytime the mouse is over an event element
             * @param {Extensible.calendar.view.AbstractCalendar} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} for the event that the cursor is over
             * @param {HTMLNode} el The DOM node that is being moused over
             */
            eventover: true,
            /**
             * @event eventout
             * Fires anytime the mouse exits an event element
             * @param {Extensible.calendar.view.AbstractCalendar} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} for the event that the cursor exited
             * @param {HTMLNode} el The DOM node that was exited
             */
            eventout: true,
            /**
             * @event beforedatechange
             * Fires before the start date of the view changes, giving you an opportunity to save state or anything else you may need
             * to do prior to the UI view changing. This is a cancelable event, so returning false from a handler will cancel both the
             * view change and the setting of the start date.
             * @param {Extensible.calendar.view.AbstractCalendar} this
             * @param {Date} startDate The current start date of the view (as explained in {@link #getStartDate}
             * @param {Date} newStartDate The new start date that will be set when the view changes
             * @param {Date} viewStart The first displayed date in the current view
             * @param {Date} viewEnd The last displayed date in the current view
             */
            beforedatechange: true,
            /**
             * @event datechange
             * Fires after the start date of the view has changed. If you need to cancel the date change you should handle the 
             * {@link #beforedatechange} event and return false from your handler function.
             * @param {Extensible.calendar.view.AbstractCalendar} this
             * @param {Date} startDate The start date of the view (as explained in {@link #getStartDate}
             * @param {Date} viewStart The first displayed date in the view
             * @param {Date} viewEnd The last displayed date in the view
             */
            datechange: true,
            /**
             * @event rangeselect
             * Fires after the user drags on the calendar to select a range of dates/times in which to create an event. This is a 
             * cancelable event, so returning false from a handler will cancel the drag operation and clean up any drag shim elements
             * without displaying the event editor view. This could be useful for validating that a user can only create events within
             * a certain range.
             * @param {Extensible.calendar.view.AbstractCalendar} this
             * @param {Object} dates An object containing the start (StartDate property) and end (EndDate property) dates selected
             * @param {Function} callback A callback function that MUST be called after the event handling is complete so that
             * the view is properly cleaned up (shim elements are persisted in the view while the user is prompted to handle the
             * range selection). The callback is already created in the proper scope, so it simply needs to be executed as a standard
             * function call (e.g., callback()).
             */
			rangeselect: true,
            /**
             * @event beforeeventmove
             * Fires before an event element is dragged by the user and dropped in a new position. This is a cancelable event, so 
             * returning false from a handler will cancel the move operation. This could be useful for validating that a user can 
             * only move events within a certain date range.
             * @param {Extensible.calendar.view.AbstractCalendar} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} for the event that will be moved
             * @param {Date} dt The new start date to be set (the end date will be automaticaly adjusted to match the event duration)
             */
            beforeeventmove: true,
            /**
             * @event eventmove
             * Fires after an event element has been dragged by the user and dropped in a new position. If you need to cancel the 
             * move operation you should handle the {@link #beforeeventmove} event and return false from your handler function.
             * @param {Extensible.calendar.view.AbstractCalendar} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} for the event that was moved with
             * updated start and end dates
             */
			eventmove: true,
            /**
             * @event initdrag
             * Fires when a drag operation is initiated in the view
             * @param {Extensible.calendar.view.AbstractCalendar} this
             */
            initdrag: true,
            /**
             * @event dayover
             * Fires while the mouse is over a day element 
             * @param {Extensible.calendar.view.AbstractCalendar} this
             * @param {Date} dt The date that is being moused over
             * @param {Ext.Element} el The day Element that is being moused over
             */
            dayover: true,
            /**
             * @event dayout
             * Fires when the mouse exits a day element 
             * @param {Extensible.calendar.view.AbstractCalendar} this
             * @param {Date} dt The date that is exited
             * @param {Ext.Element} el The day Element that is exited
             */
            dayout: true,
            /**
             * @event editdetails
             * Fires when the user selects the option in this window to continue editing in the detailed edit form
             * (by default, an instance of {@link Extensible.calendar.form.EventDetails}. Handling code should hide this window
             * and transfer the current event record to the appropriate instance of the detailed form by showing it
             * and calling {@link Extensible.calendar.form.EventDetails#loadRecord loadRecord}.
             * @param {Extensible.calendar.view.AbstractCalendar} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} that is currently being edited
             * @param {Ext.Element} el The target element
             */
            editdetails: true,
            /**
             * @event eventadd
             * Fires after a new event has been added to the underlying store
             * @param {Extensible.calendar.view.AbstractCalendar} this
             * @param {Extensible.calendar.data.EventModel} rec The new {@link Extensible.calendar.data.EventModel record} that was added
             */
            eventadd: true,
            /**
             * @event eventupdate
             * Fires after an existing event has been updated
             * @param {Extensible.calendar.view.AbstractCalendar} this
             * @param {Extensible.calendar.data.EventModel} rec The new {@link Extensible.calendar.data.EventModel record} that was updated
             */
            eventupdate: true,
            /**
             * @event eventcancel
             * Fires after an event add/edit operation has been canceled by the user and no store update took place
             * @param {Extensible.calendar.view.AbstractCalendar} this
             * @param {Extensible.calendar.data.EventModel} rec The new {@link Extensible.calendar.data.EventModel record} that was canceled
             */
            eventcancel: true,
            /**
             * @event beforeeventdelete
             * Fires before an event is deleted by the user. This is a cancelable event, so returning false from a handler 
             * will cancel the delete operation.
             * @param {Extensible.calendar.view.AbstractCalendar} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} for the event that was deleted
             * @param {Ext.Element} el The target element
             */
            beforeeventdelete: true,
            /**
             * @event eventdelete
             * Fires after an event has been deleted by the user. If you need to cancel the delete operation you should handle the 
             * {@link #beforeeventdelete} event and return false from your handler function.
             * @param {Extensible.calendar.view.AbstractCalendar} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} for the event that was deleted
             * @param {Ext.Element} el The target element
             */
            eventdelete: true
        });
    },

    // private
    afterRender : function(){
        this.callParent(arguments);

        this.renderTemplate();
        
        if(this.store){
            this.setStore(this.store, true);
            if(this.store.deferLoad){
                this.reloadStore(this.store.deferLoad);
                delete this.store.deferLoad;
            }
            else {
                this.store.initialParams = this.getStoreParams();
            }
        }
        if(this.calendarStore){
            this.setCalendarStore(this.calendarStore, true);
        }
        
        this.on('resize', this.onResize, this);

        this.el.on({
            'mouseover': this.onMouseOver,
            'mouseout': this.onMouseOut,
            'click': this.onClick,
			//'resize': this.onResize,
            scope: this
        });
        
        // currently the context menu only contains CRUD actions so do not show it if read-only
        if(this.enableContextMenus && this.readOnly !== true){
            this.el.on('contextmenu', this.onContextMenu, this);
        }
		
		this.el.unselectable();
        
        if(this.enableDD && this.readOnly !== true && this.initDD){
			this.initDD();
        }
        
        this.on('eventsrendered', this.onEventsRendered);
        
        Ext.defer(this.forceSize, 100, this);
    },
    
    /**
     * Returns an object containing the start and end dates to be passed as params in all calls
     * to load the event store. The param names are customizable using {@link #dateParamStart}
     * and {@link #dateParamEnd} and the date format used in requests is defined by {@link #dateParamFormat}.
     * If you need to add additional parameters to be sent when loading the store see {@link #getStoreParams}.
     * @return {Object} An object containing the start and end dates
     */
    getStoreDateParams : function(){
        var o = {};
        o[this.dateParamStart] = Ext.Date.format(this.viewStart, this.dateParamFormat);
        o[this.dateParamEnd] = Ext.Date.format(this.viewEnd, this.dateParamFormat);
        return o;
    },
    
    /**
     * Returns an object containing all key/value params to be passed when loading the event store.
     * By default the returned object will simply be the same object returned by {@link #getStoreDateParams},
     * but this method is intended to be overridden if you need to pass anything in addition to start and end dates.
     * See the inline code comments when overriding for details.
     * @return {Object} An object containing all params to be sent when loading the event store
     */
    getStoreParams : function(){
        // This is needed if you require the default start and end dates to be included
        var params = this.getStoreDateParams();
        
        // Here is where you can add additional custom params, e.g.:
        // params.now = Ext.Date.format(new Date(), this.dateParamFormat);
        // params.foo = 'bar';
        // params.number = 123;
        
        return params;
    },
    
    /**
     * Reloads the view's underlying event store using the params returned from {@link #getStoreParams}.
     * Reloading the store is typically managed automatically by the view itself, but the method is
     * available in case a manual reload is ever needed.
     * @param {Object} options (optional) An object matching the format used by Store's {@link Ext.data.Store#load load} method
     */
    reloadStore : function(o){
        Extensible.log('reloadStore');
        o = Ext.isObject(o) ? o : {};
        o.params = o.params || {};
        
        Ext.apply(o.params, this.getStoreParams());
        this.store.load(o);
    },
    
    // private
    onEventsRendered: function() {
        this.forceSize();
    },
    
    // private
    forceSize: function(){
        if(this.el && this.el.down){
            var hd = this.el.down('.ext-cal-hd-ct'),
                bd = this.el.down('.ext-cal-body-ct');
                
            if(bd==null || hd==null) return;
                
            var headerHeight = hd.getHeight(),
                sz = this.el.parent().getSize();
                   
            bd.setHeight(sz.height-headerHeight);
        }
    },

    /**
     * Refresh the current view, optionally reloading the event store also. While this is normally
     * managed internally on any navigation and/or CRUD action, there are times when you might want
     * to refresh the view manually (e.g., if you'd like to reload using different {@link #getStoreParams params}).
     * @param {Boolean} reloadData True to reload the store data first, false to simply redraw the view using current 
     * data (defaults to false)
     */
    refresh : function(reloadData){
        Extensible.log('refresh (base), reload = '+reloadData);
        if(reloadData === true){
            this.reloadStore();
        }
        this.prepareData();
        this.renderTemplate();
        this.renderItems();
    },
    
    // private
    getWeekCount : function(){
        var days = Extensible.Date.diffDays(this.viewStart, this.viewEnd);
        return Math.ceil(days / this.dayCount);
    },
    
    // private
    prepareData : function(){
        var lastInMonth = Ext.Date.getLastDateOfMonth(this.startDate),
            w = 0, row = 0,
            dt = Ext.Date.clone(this.viewStart),
            weeks = this.weekCount < 1 ? 6 : this.weekCount;
        
        this.eventGrid = [[]];
        this.allDayGrid = [[]];
        this.evtMaxCount = [];
        
        var evtsInView = this.store.queryBy(function(rec){
            return this.isEventVisible(rec.data);
        }, this);
        
        for(; w < weeks; w++){
            this.evtMaxCount[w] = 0;
            if(this.weekCount == -1 && dt > lastInMonth){
                //current week is fully in next month so skip
                break;
            }
            this.eventGrid[w] = this.eventGrid[w] || [];
            this.allDayGrid[w] = this.allDayGrid[w] || [];
            
            for(d = 0; d < this.dayCount; d++){
                if(evtsInView.getCount() > 0){
                    var evts = evtsInView.filterBy(function(rec){
                        var startDt = Ext.Date.clearTime(rec.data[Extensible.calendar.data.EventMappings.StartDate.name], true),
                            startsOnDate = dt.getTime() == startDt.getTime(),
                            spansFromPrevView = (w == 0 && d == 0 && (dt > rec.data[Extensible.calendar.data.EventMappings.StartDate.name]));

                        return startsOnDate || spansFromPrevView;
                    }, this);
                    
                    this.sortEventRecordsForDay(evts);
                    this.prepareEventGrid(evts, w, d);
                }
                dt = Extensible.Date.add(dt, {days: 1});
            }
        }
        this.currentWeekCount = w;
    },
    
    // private
    prepareEventGrid : function(evts, w, d) {
        var me = this,
            row = 0,
            dt = Ext.Date.clone(me.viewStart),
            max = me.maxEventsPerDay || 999,
            maxEventsForDay;
        
        evts.each(function(evt) {
            var M = Extensible.calendar.data.EventMappings;
            
            if (Extensible.Date.diffDays(evt.data[M.StartDate.name], evt.data[M.EndDate.name]) > 0) {
                var daysInView = Extensible.Date.diffDays(
                    Extensible.Date.max(me.viewStart, evt.data[M.StartDate.name]),
                    Extensible.Date.min(me.viewEnd, evt.data[M.EndDate.name])) + 1;
                    
                me.prepareEventGridSpans(evt, me.eventGrid, w, d, daysInView);
                me.prepareEventGridSpans(evt, me.allDayGrid, w, d, daysInView, true);
            }
            else {
                row = me.findEmptyRowIndex(w,d);
                me.eventGrid[w][d] = me.eventGrid[w][d] || [];
                me.eventGrid[w][d][row] = evt;
                
                if(evt.data[M.IsAllDay.name]){
                    row = me.findEmptyRowIndex(w,d, true);
                    me.allDayGrid[w][d] = me.allDayGrid[w][d] || [];
                    me.allDayGrid[w][d][row] = evt;
                }
            }
            
            // If calculating the max event count for the day/week view header, use the allDayGrid
            // so that only all-day events displayed in that area get counted, otherwise count all events.            
            maxEventsForDay = me[me.isHeaderView ? 'allDayGrid' : 'eventGrid'][w][d] || [];
            
            if (maxEventsForDay.length && me.evtMaxCount[w] < maxEventsForDay.length) {
                me.evtMaxCount[w] = Math.min(max + 1, maxEventsForDay.length);
            }
            return true;
        }, me);
    },
    
    // private
    prepareEventGridSpans : function(evt, grid, w, d, days, allday){
        // this event spans multiple days/weeks, so we have to preprocess
        // the events and store special span events as placeholders so that
        // the render routine can build the necessary TD spans correctly.
        var w1 = w, d1 = d, 
            row = this.findEmptyRowIndex(w,d,allday),
            dt = Ext.Date.clone(this.viewStart);
        
        var start = {
            event: evt,
            isSpan: true,
            isSpanStart: true,
            spanLeft: false,
            spanRight: (d == 6)
        };
        grid[w][d] = grid[w][d] || [];
        grid[w][d][row] = start;
        
        while(--days){
            dt = Extensible.Date.add(dt, {days: 1});
            if(dt > this.viewEnd){
                break;
            }
            if(++d1>6){
                // reset counters to the next week
                d1 = 0; w1++;
                row = this.findEmptyRowIndex(w1,0);
            }
            grid[w1] = grid[w1] || [];
            grid[w1][d1] = grid[w1][d1] || [];
            
            grid[w1][d1][row] = {
                event: evt,
                isSpan: true,
                isSpanStart: (d1 == 0),
                spanLeft: (w1 > w) && (d1 % 7 == 0),
                spanRight: (d1 == 6) && (days > 1)
            };
        }
    },
    
    // private
    findEmptyRowIndex : function(w, d, allday){
        var grid = allday ? this.allDayGrid : this.eventGrid,
            day = grid[w] ? grid[w][d] || [] : [],
            i = 0, ln = day.length;
            
        for(; i < ln; i++){
            if(day[i] == null){
                return i;
            }
        }
        return ln;
    },
    
    // private
    renderTemplate : function(){
        if(this.tpl){
            this.tpl.overwrite(this.el, this.getTemplateParams());
            this.lastRenderStart = Ext.Date.clone(this.viewStart);
            this.lastRenderEnd = Ext.Date.clone(this.viewEnd);
        }
    },
    
    // private
    getTemplateParams : function(){
        return {
            viewStart: this.viewStart,
            viewEnd: this.viewEnd,
            startDate: this.startDate,
            dayCount: this.dayCount,
            weekCount: this.weekCount,
            weekendCls: this.weekendCls,
            prevMonthCls: this.prevMonthCls,
            nextMonthCls: this.nextMonthCls,
            todayCls: this.todayCls
        };
    },
    
    /**
     * Disable store event monitoring within this view. Note that if you do this the view will no longer
     * refresh itself automatically when CRUD actions occur. To enable store events see {@link #enableStoreEvents}.
     * @return {CalendarView} this
     */
	disableStoreEvents : function(){
		this.monitorStoreEvents = false;
        return this;
	},
	
    /**
     * Enable store event monitoring within this view if disabled by {@link #disbleStoreEvents}.
     * @return {CalendarView} this
     */
	enableStoreEvents : function(refresh){
		this.monitorStoreEvents = true;
		if(refresh === true){
			this.refresh();
		}
        return this;
	},
	
    // private
	onResize : function(){
		this.refresh(false);
	},
	
    // private
	onInitDrag : function(){
        this.fireEvent('initdrag', this);
    },
	
    // private
	onEventDrop : function(rec, dt){
        this.moveEvent(rec, dt);
	},
    
    // private
	onCalendarEndDrag : function(start, end, onComplete){
        // set this flag for other event handlers that might conflict while we're waiting
        this.dragPending = true;
        
        var dates = {},
            onComplete = Ext.bind(this.onCalendarEndDragComplete, this, [onComplete]);
        
        dates[Extensible.calendar.data.EventMappings.StartDate.name] = start;
        dates[Extensible.calendar.data.EventMappings.EndDate.name] = end;
        
        if(this.fireEvent('rangeselect', this, dates, onComplete) !== false){
            this.showEventEditor(dates, null);
            if (this.editWin) {
                this.editWin.on('hide', onComplete, this, {single:true});
            }
            else {
                onComplete();
            }
        }
        else{
            // client code canceled the selection so clean up immediately
            this.onCalendarEndDragComplete(onComplete);
        }
	},
    
    // private
    onCalendarEndDragComplete : function(onComplete){
        // callback for the drop zone to clean up
        onComplete();
        // clear flag for other events to resume normally
        this.dragPending = false;
    },
	
    // private
    onUpdate : function(ds, rec, operation){
        if(this.hidden === true || this.monitorStoreEvents === false){
            return;
        }
        if(operation == Ext.data.Record.COMMIT){
            Extensible.log('onUpdate');
            this.dismissEventEditor();
            
            var rrule = rec.data[Extensible.calendar.data.EventMappings.RRule.name];
            // if the event has a recurrence rule we have to reload the store in case
            // any event instances were updated on the server
            this.refresh(rrule !== undefined && rrule !== '');
            
			if(this.enableFx && this.enableUpdateFx){
				this.doUpdateFx(this.getEventEls(rec.data[Extensible.calendar.data.EventMappings.EventId.name]), {
                    scope: this
                });
			}
        }
    },
    
    /**
     * Provides the element effect(s) to run after an event is updated. The method is passed a {@link Ext.CompositeElement}
     * that contains one or more elements in the DOM representing the event that was updated. The default 
     * effect is {@link Ext.Element#highlight highlight}. Note that this method will only be called when 
     * {@link #enableUpdateFx} is true (it is false by default).
     * @param {Ext.CompositeElement} el The {@link Ext.CompositeElement} representing the updated event
     * @param {Object} options An options object to be passed through to any Element.Fx methods. By default this
     * object only contains the current scope (<tt>{scope:this}</tt>) but you can also add any additional fx-specific 
     * options that might be needed for a particular effect to this object.
     */
	doUpdateFx : function(els, o){
		this.highlightEvent(els, null, o);
	},
	
    // private
    onAdd : function(ds, recs, index){
        var rec = Ext.isArray(recs) ? recs[0] : recs; 
        if(this.hidden === true || this.monitorStoreEvents === false){
            return;
        }
        if(rec._deleting){
            delete rec._deleting;
            return;
        }
        
        Extensible.log('onAdd');
        
		var rrule = rec.data[Extensible.calendar.data.EventMappings.RRule.name];
        
        this.dismissEventEditor();    
		this.tempEventId = rec.id;
        // if the new event has a recurrence rule we have to reload the store in case
        // new event instances were generated on the server
		this.refresh(rrule !== undefined && rrule !== '');
		
		if(this.enableFx && this.enableAddFx){
			this.doAddFx(this.getEventEls(rec.data[Extensible.calendar.data.EventMappings.EventId.name]), {
                scope: this
            });
		};
    },
	
    /**
     * Provides the element effect(s) to run after an event is added. The method is passed a {@link Ext.CompositeElement}
     * that contains one or more elements in the DOM representing the event that was added. The default 
     * effect is {@link Ext.Element#fadeIn fadeIn}. Note that this method will only be called when 
     * {@link #enableAddFx} is true (it is true by default).
     * @param {Ext.CompositeElement} el The {@link Ext.CompositeElement} representing the added event
     * @param {Object} options An options object to be passed through to any Element.Fx methods. By default this
     * object only contains the current scope (<tt>{scope:this}</tt>) but you can also add any additional fx-specific 
     * options that might be needed for a particular effect to this object.
     */
	doAddFx : function(els, o){
		els.fadeIn(Ext.apply(o, { duration: 2000 }));
	},
	
    // private
    onRemove : function(ds, rec){
        if(this.hidden === true || this.monitorStoreEvents === false){
            return;
        }
        
        Extensible.log('onRemove');
        this.dismissEventEditor();
        
        var rrule = rec.data[Extensible.calendar.data.EventMappings.RRule.name],
            // if the new event has a recurrence rule we have to reload the store in case
            // new event instances were generated on the server
            isRecurring = rrule !== undefined && rrule !== '';
        
		if(this.enableFx && this.enableRemoveFx){
			this.doRemoveFx(this.getEventEls(rec.data[Extensible.calendar.data.EventMappings.EventId.name]), {
	            remove: true,
	            scope: this,
				callback: Ext.bind(this.refresh, this, [isRecurring])
			});
		}
		else{
			this.getEventEls(rec.data[Extensible.calendar.data.EventMappings.EventId.name]).remove();
            this.refresh(isRecurring);
		}
    },
	
    /**
     * Provides the element effect(s) to run after an event is removed. The method is passed a {@link Ext.CompositeElement}
     * that contains one or more elements in the DOM representing the event that was removed. The default 
     * effect is {@link Ext.Element#fadeOut fadeOut}. Note that this method will only be called when 
     * {@link #enableRemoveFx} is true (it is true by default).
     * @param {Ext.CompositeElement} el The {@link Ext.CompositeElement} representing the removed event
     * @param {Object} options An options object to be passed through to any Element.Fx methods. By default this
     * object contains the following properties:
     * <pre><code>
{
   remove: true, // required by fadeOut to actually remove the element(s)
   scope: this,  // required for the callback
   callback: fn  // required to refresh the view after the fx finish
} 
     * </code></pre>
     * While you can modify this options object as needed if you change the effect used, please note that the
     * callback method (and scope) MUST still be passed in order for the view to refresh correctly after the removal.
     * Please see the inline code comments before overriding this method. 
     */
	doRemoveFx : function(els, o){
        // Please make sure you keep this entire code block or removing events might not work correctly!
        // Removing is a little different because we have to wait for the fx to finish, then we have to actually
        // refresh the view AFTER the fx are run (this is different than add and update).
        if(els.getCount() == 0 && Ext.isFunction(o.callback)){
            // if there are no matching elements in the view make sure the callback still runs.
            // this can happen when an event accessed from the "more" popup is deleted.
            o.callback.call(o.scope || this);
        }
        else{
            // If you'd like to customize the remove fx do so here. Just make sure you
            // DO NOT override the default callback property on the options object, and that
            // you still pass that object in whatever fx method you choose.
            els.fadeOut(o);
        }
	},
	
	/**
	 * Visually highlights an event using {@link Ext.Fx#highlight} config options.
	 * @param {Ext.CompositeElement} els The element(s) to highlight
	 * @param {Object} color (optional) The highlight color. Should be a 6 char hex 
	 * color without the leading # (defaults to yellow: 'ffff9c')
	 * @param {Object} o (optional) Object literal with any of the {@link Ext.Fx} config 
	 * options. See {@link Ext.Fx#highlight} for usage examples.
	 */
	highlightEvent : function(els, color, o) {
		if(this.enableFx){
			var c;
			!(Ext.isIE || Ext.isOpera) ? 
				els.highlight(color, o) :
				// Fun IE/Opera handling:
				els.each(function(el){
					el.highlight(color, Ext.applyIf({attr:'color'}, o));
					if(c = el.down('.ext-cal-evm')) {
						c.highlight(color, o);
					}
				}, this);
		}
	},
	
	/**
	 * Retrieve an Event object's id from its corresponding node in the DOM.
	 * @param {String/Element/HTMLElement} el An {@link Ext.Element}, DOM node or id
	 */
//	getEventIdFromEl : function(el){
//		el = Ext.get(el);
//		var id = el.id.split(this.eventElIdDelimiter)[1];
//        if(id.indexOf('-w_') > -1){
//            //This id has the index of the week it is rendered in as part of the suffix.
//            //This allows events that span across weeks to still have reproducibly-unique DOM ids.
//            id = id.split('-w_')[0];
//        }
//        return id;
//	},
    getEventIdFromEl : function(el){
        el = Ext.get(el);
        var parts, id = '', cls, classes = el.dom.className.split(' ');
        
        Ext.each(classes, function(cls){
            parts = cls.split(this.eventElIdDelimiter);
            if(parts.length > 1){
                id = parts[1];
                return false;
            }
        }, this);
        
        return id;
    },
	
	// private
	getEventId : function(eventId){
		if(eventId === undefined && this.tempEventId){
            // temp record id assigned during an add, will be overwritten later
			eventId = this.tempEventId;
		}
		return eventId;
	},
	
	/**
	 * 
	 * @param {String} eventId
	 * @param {Boolean} forSelect
	 * @return {String} The selector class
	 */
	getEventSelectorCls : function(eventId, forSelect){
		var prefix = forSelect ? '.' : '';
		return prefix + this.id + this.eventElIdDelimiter + this.getEventId(eventId);
	},

	/**
	 * 
	 * @param {String} eventId
	 * @return {Ext.CompositeElement} The matching CompositeElement of nodes
	 * that comprise the rendered event.  Any event that spans across a view 
	 * boundary will contain more than one internal Element.
	 */
	getEventEls : function(eventId){
		var els = this.el.select(this.getEventSelectorCls(this.getEventId(eventId), true), false);
		return Ext.create('Ext.CompositeElement', els);
	},
    
    /**
     * Returns true if the view is currently displaying today's date, else false.
     * @return {Boolean} True or false
     */
    isToday : function(){
        var today = Ext.Date.clearTime(new Date()).getTime();
        return this.viewStart.getTime() <= today && this.viewEnd.getTime() >= today;
    },

    // private
    onDataChanged : function(store){
        Extensible.log('onDataChanged');
        this.refresh(false);
    },
    
    // private
    isEventVisible : function(evt){
        var M = Extensible.calendar.data.EventMappings,
            data = evt.data || evt,
            calRec = this.calendarStore ? 
                this.calendarStore.findRecord(M.CalendarId.name, evt[M.CalendarId.name]) : null;
            
        if(calRec && calRec.data[Extensible.calendar.data.CalendarMappings.IsHidden.name] === true){
            // if the event is on a hidden calendar then no need to test the date boundaries
            return false;
        }
            
        var start = this.viewStart.getTime(),
            end = this.viewEnd.getTime(),
            evStart = data[M.StartDate.name].getTime(),
            evEnd = data[M.EndDate.name].getTime();
            
        return Extensible.Date.rangesOverlap(start, end, evStart, evEnd);
    },
    
    // private
    isOverlapping : function(evt1, evt2){
        var ev1 = evt1.data ? evt1.data : evt1,
            ev2 = evt2.data ? evt2.data : evt2,
            M = Extensible.calendar.data.EventMappings,
            start1 = ev1[M.StartDate.name].getTime(),
            end1 = Extensible.Date.add(ev1[M.EndDate.name], {seconds: -1}).getTime(),
            start2 = ev2[M.StartDate.name].getTime(),
            end2 = Extensible.Date.add(ev2[M.EndDate.name], {seconds: -1}).getTime(),
            startDiff = Extensible.Date.diff(ev1[M.StartDate.name], ev2[M.StartDate.name], 'm');
            
            if(end1<start1){
                end1 = start1;
            }
            if(end2<start2){
                end2 = start2;
            }
            
//            var ev1startsInEv2 = (start1 >= start2 && start1 <= end2),
//            ev1EndsInEv2 = (end1 >= start2 && end1 <= end2),
//            ev1SpansEv2 = (start1 < start2 && end1 > end2),
            var evtsOverlap = Extensible.Date.rangesOverlap(start1, end1, start2, end2),
                minimumMinutes = this.minEventDisplayMinutes || 0, // applies in day/week body view only for vertical overlap
                ev1MinHeightOverlapsEv2 = minimumMinutes > 0 && (startDiff > -minimumMinutes && startDiff < minimumMinutes);
        
        //return (ev1startsInEv2 || ev1EndsInEv2 || ev1SpansEv2 || ev1MinHeightOverlapsEv2);
        return (evtsOverlap || ev1MinHeightOverlapsEv2);
    },
    
    // private
    isEventSpanning : function(evt) {
        var M = Extensible.calendar.data.EventMappings,
            data = evt.data || evt,
            diff;
            
        diff = Extensible.Date.diffDays(data[M.StartDate.name], data[M.EndDate.name]);
        
        //TODO: Prevent 00:00 end time from causing a span. This logic is OK, but
        //      other changes are still needed for it to work fully. Deferring for now.
//        if (diff <= 1 && Extensible.Date.isMidnight(data[M.EndDate.name])) {
//            return false;
//        }
        return diff > 0;
    },
    
    // private
    getDayEl : function(dt){
        return Ext.get(this.getDayId(dt));
    },
    
    // private
    getDayId : function(dt){
        if(Ext.isDate(dt)){
            dt = Ext.Date.format(dt, 'Ymd');
        }
        return this.id + this.dayElIdDelimiter + dt;
    },
    
    /**
     * Returns the start date of the view, as set by {@link #setStartDate}. Note that this may not 
     * be the first date displayed in the rendered calendar -- to get the start and end dates displayed
     * to the user use {@link #getViewBounds}.
     * @return {Date} The start date
     */
    getStartDate : function(){
        return this.startDate;
    },

    /**
     * Sets the start date used to calculate the view boundaries to display. The displayed view will be the 
     * earliest and latest dates that match the view requirements and contain the date passed to this function.
     * @param {Date} dt The date used to calculate the new view boundaries
     */
    setStartDate : function(start, /*private*/reload){
        var me = this;
        
        Extensible.log('setStartDate (base) '+Ext.Date.format(start, 'Y-m-d'));
        
        var cloneDt = Ext.Date.clone,
            cloneStartDate = me.startDate ? cloneDt(me.startDate) : null,
            cloneStart = cloneDt(start),
            cloneViewStart = me.viewStart ? cloneDt(me.viewStart) : null,
            cloneViewEnd = me.viewEnd ? cloneDt(me.viewEnd) : null;
        
        if (me.fireEvent('beforedatechange', me, cloneStartDate, cloneStart, cloneViewStart, cloneViewEnd) !== false) {
            me.startDate = Ext.Date.clearTime(start);
            me.setViewBounds(start);
            
            if (me.ownerCalendarPanel && me.ownerCalendarPanel.startDate !== me.startDate) {
                // Sync the owning CalendarPanel's start date directly, not via CalendarPanel.setStartDate(),
                // since that would in turn call this method again.
                me.ownerCalendarPanel.startDate = me.startDate;
            }
            
            if (me.rendered) {
                me.refresh(reload);
            }
            me.fireEvent('datechange', me, cloneDt(me.startDate), cloneDt(me.viewStart), cloneDt(me.viewEnd));
        }
    },
    
    // private
    setViewBounds : function(startDate){
        var me = this,
            start = startDate || me.startDate,
            offset = start.getDay() - me.startDay,
            Dt = Extensible.Date;

        if(offset < 0){
            // if the offset is negative then some days will be in the previous week so add a week to the offset
            offset += 7;
        }
        switch(this.weekCount){
            case 0:
            case 1:
                me.viewStart = me.dayCount < 7 && !me.startDayIsStatic ?
                    start: Dt.add(start, {days: -offset, clearTime: true});
                me.viewEnd = Dt.add(me.viewStart, {days: me.dayCount || 7, seconds: -1});
                return;
            
            case -1: 
                // auto by month
                start = Ext.Date.getFirstDateOfMonth(start);
                offset = start.getDay() - me.startDay;
                if(offset < 0){
                    // if the offset is negative then some days will be in the previous week so add a week to the offset
                    offset += 7;
                }
                me.viewStart = Dt.add(start, {days: -offset, clearTime: true});
                
                // start from current month start, not view start:
                var end = Dt.add(start, {months: 1, seconds: -1});
                
                // fill out to the end of the week:
                offset = me.startDay;
                if(offset > end.getDay()){
                    // if the offset is larger than the end day index then the last row will be empty so skip it
                    offset -= 7;
                }
                
                me.viewEnd = Dt.add(end, {days: 6 - end.getDay() + offset});
                return;
            
            default:
                me.viewStart = Dt.add(start, {days: -offset, clearTime: true});
                me.viewEnd = Dt.add(me.viewStart, {days: me.weekCount * 7, seconds: -1});
        }
    },
    
    /**
     * Returns the start and end boundary dates currently displayed in the view. The method
     * returns an object literal that contains the following properties:<ul>
     * <li><b>start</b> Date : <div class="sub-desc">The start date of the view</div></li>
     * <li><b>end</b> Date : <div class="sub-desc">The end date of the view</div></li></ul>
     * For example:<pre><code>
var bounds = view.getViewBounds();
alert('Start: '+bounds.start);
alert('End: '+bounds.end);
</code></pre>
     * @return {Object} An object literal containing the start and end values
     */
    getViewBounds : function(){
        return {
            start: this.viewStart,
            end: this.viewEnd
        }
    },
	
	/* private
	 * Sort events for a single day for display in the calendar.  This sorts allday
	 * events first, then non-allday events are sorted either based on event start
	 * priority or span priority based on the value of {@link #spansHavePriority} 
	 * (defaults to event start priority).
	 * @param {MixedCollection} evts A {@link Ext.util.MixedCollection MixedCollection}  
	 * of {@link #Extensible.calendar.data.EventModel EventRecord} objects
	 */
	sortEventRecordsForDay: function(evts){
        if(evts.length < 2){
            return;
        }
		evts.sortBy(Ext.bind(function(evtA, evtB) {
			var a = evtA.data, 
                b = evtB.data,
                M = Extensible.calendar.data.EventMappings;
			
			// Always sort all day events before anything else
			if (a[M.IsAllDay.name]) {
				return -1;
			}
			else if (b[M.IsAllDay.name]) {
				return 1;
			}
			if (this.spansHavePriority) {
				// This logic always weights span events higher than non-span events 
				// (at the possible expense of start time order). This seems to 
				// be the approach used by Google calendar and can lead to a more
				// visually appealing layout in complex cases, but event order is
				// not guaranteed to be consistent.
				var diff = Extensible.Date.diffDays;
				if (diff(a[M.StartDate.name], a[M.EndDate.name]) > 0) {
					if (diff(b[M.StartDate.name], b[M.EndDate.name]) > 0) {
						// Both events are multi-day
						if (a[M.StartDate.name].getTime() == b[M.StartDate.name].getTime()) {
							// If both events start at the same time, sort the one
							// that ends later (potentially longer span bar) first
							return b[M.EndDate.name].getTime() - a[M.EndDate.name].getTime();
						}
						return a[M.StartDate.name].getTime() - b[M.StartDate.name].getTime();
					}
					return -1;
				}
				else if (diff(b[M.StartDate.name], b[M.EndDate.name]) > 0) {
					return 1;
				}
				return a[M.StartDate.name].getTime() - b[M.StartDate.name].getTime();
			}
			else {
				// Doing this allows span and non-span events to intermingle but
				// remain sorted sequentially by start time. This seems more proper
				// but can make for a less visually-compact layout when there are
				// many such events mixed together closely on the calendar.
				return a[M.StartDate.name].getTime() - b[M.StartDate.name].getTime();
			}
		}, this));
	},
    
    /**
     * Updates the view to contain the passed date
     * @param {Date} dt The date to display
     */
    moveTo : function(dt, /*private*/reload){
        if(Ext.isDate(dt)){
            this.setStartDate(dt, reload);
            return this.startDate;
        }
        return dt;
    },

    /**
     * Updates the view to the next consecutive date(s)
     * @return {Date} The new view start date
     */
    moveNext : function(/*private*/reload){
        return this.moveTo(Extensible.Date.add(this.viewEnd, {days: 1}), reload);
    },

    /**
     * Updates the view to the previous consecutive date(s)
     * @return {Date} The new view start date
     */
    movePrev : function(/*private*/reload){
        var days = Extensible.Date.diffDays(this.viewStart, this.viewEnd)+1;
        return this.moveDays(-days, reload);
    },
    
    /**
     * Shifts the view by the passed number of months relative to the currently set date
     * @param {Number} value The number of months (positive or negative) by which to shift the view
     * @return {Date} The new view start date
     */
    moveMonths : function(value, /*private*/reload){
        return this.moveTo(Extensible.Date.add(this.startDate, {months: value}), reload);
    },
    
    /**
     * Shifts the view by the passed number of weeks relative to the currently set date
     * @param {Number} value The number of weeks (positive or negative) by which to shift the view
     * @return {Date} The new view start date
     */
    moveWeeks : function(value, /*private*/reload){
        return this.moveTo(Extensible.Date.add(this.startDate, {days: value * 7}), reload);
    },
    
    /**
     * Shifts the view by the passed number of days relative to the currently set date
     * @param {Number} value The number of days (positive or negative) by which to shift the view
     * @return {Date} The new view start date
     */
    moveDays : function(value, /*private*/reload){
        return this.moveTo(Extensible.Date.add(this.startDate, {days: value}), reload);
    },
    
    /**
     * Updates the view to show today
     * @return {Date} Today's date
     */
    moveToday : function(/*private*/reload){
        return this.moveTo(new Date(), reload);
    },
    
    /**
     * Sets the event store used by the calendar to display {@link Extensible.calendar.data.EventModel events}.
     * @param {Ext.data.Store} store
     */
    setStore : function(store, initial){
        var currStore = this.store;
        
        if(!initial && currStore){
            currStore.un("datachanged", this.onDataChanged, this);
            currStore.un("clear", this.refresh, this);
            currStore.un("write", this.onWrite, this);
            currStore.un("exception", this.onException, this);
        }
        if(store){
            store.on("datachanged", this.onDataChanged, this);
            store.on("clear", this.refresh, this);
            store.on("write", this.onWrite, this);
            store.on("exception", this.onException, this);
        }
        this.store = store;
    },
    
    // private
    onException : function(proxy, type, action, o, res, arg){
        // form edits are explicitly canceled, but we may not know if a drag/drop operation
        // succeeded until after a server round trip. if the update failed we have to explicitly
        // reject the changes so that the record doesn't stick around in the store's modified list 
        if(arg.reject){
            arg.reject();
        }
    },
    
    /**
     * Sets the calendar store used by the calendar (contains records of type {@link Extensible.calendar.data.CalendarModel CalendarRecord}).
     * @param {Ext.data.Store} store
     */
    setCalendarStore : function(store, initial){
        if(!initial && this.calendarStore){
            this.calendarStore.un("datachanged", this.refresh, this);
            this.calendarStore.un("add", this.refresh, this);
            this.calendarStore.un("remove", this.refresh, this);
            this.calendarStore.un("update", this.refresh, this);
        }
        if(store){
            store.on("datachanged", this.refresh, this);
            store.on("add", this.refresh, this);
            store.on("remove", this.refresh, this);
            store.on("update", this.refresh, this);
        }
        this.calendarStore = store;
    },
	
    // private
    getEventRecord : function(id){
        var idx = this.store.find(Extensible.calendar.data.EventMappings.EventId.name, id, 
            0,     // start index
            false, // match any part of string 
            true,  // case sensitive
            true   // force exact match
        );
        return this.store.getAt(idx);
    },
	
    // private
	getEventRecordFromEl : function(el){
		return this.getEventRecord(this.getEventIdFromEl(el));
	},
    
    // private
    getEventEditor : function(){
        // only create one instance of the edit window, even if there are multiple CalendarPanels
        this.editWin = this.editWin || Ext.WindowMgr.get('ext-cal-editwin');
         
        if(!this.editWin){
            this.editWin = Ext.create('Extensible.calendar.form.EventWindow', {
                id: 'ext-cal-editwin',
                calendarStore: this.calendarStore,
                modal: this.editModal,
                enableEditDetails: this.enableEditDetails,
                listeners: {
                    'eventadd': {
                        fn: function(win, rec, animTarget) {
                            //win.hide(animTarget);
                            win.currentView.onEventAdd(null, rec);
                        },
                        scope: this
                    },
                    'eventupdate': {
                        fn: function(win, rec, animTarget) {
                            //win.hide(animTarget);
                            win.currentView.onEventUpdate(null, rec);
                        },
                        scope: this
                    },
                    'eventdelete': {
                        fn: function(win, rec, animTarget) {
                            //win.hide(animTarget);
                            win.currentView.onEventDelete(null, rec);
                        },
                        scope: this
                    },
                    'editdetails': {
                        fn: function(win, rec, animTarget, view) {
                            // explicitly do not animate the hide when switching to detail
                            // view as it looks weird visually
                            win.animateTarget = null;
                            win.hide();
                            win.currentView.fireEvent('editdetails', win.currentView, rec);
                        },
                        scope: this
                    },
                    'eventcancel': {
                        fn: function(win, rec, animTarget){
                            this.dismissEventEditor(null, animTarget);
                            win.currentView.onEventCancel();
                        },
                        scope: this
                    }
                }
            });
        }
        
        // allows the window to reference the current scope in its callbacks
        this.editWin.currentView = this;
        return this.editWin;
    },
    
    /**
     * Show the currently configured event editor view (by default the shared instance of 
     * {@link Extensible.calendar.form.EventWindow EventEditWindow}).
     * @param {Extensible.calendar.data.EventModel} rec The event record
     * @param {Ext.Element/HTMLNode} animateTarget The reference element that is being edited. By default this is
     * used as the target for animating the editor window opening and closing. If this method is being overridden to
     * supply a custom editor this parameter can be ignored if it does not apply.
     * @return {Extensible.calendar.view.AbstractCalendar} this
     */
    showEventEditor : function(rec, animateTarget){
        this.getEventEditor().show(rec, animateTarget, this);
        return this;
    },
    
    /**
     * Dismiss the currently configured event editor view (by default the shared instance of 
     * {@link Extensible.calendar.form.EventWindow EventEditWindow}, which will be hidden).
     * @param {String} dismissMethod (optional) The method name to call on the editor that will dismiss it 
     * (defaults to 'hide' which will be called on the default editor window)
     * @return {Extensible.calendar.view.AbstractCalendar} this
     */
    dismissEventEditor : function(dismissMethod, /*private*/ animTarget){
        if(this.newRecord && this.newRecord.phantom){
            this.store.remove(this.newRecord);
        }
        delete this.newRecord;
        
        // grab the manager's ref so that we dismiss it properly even if the active view has changed
        var editWin = Ext.WindowMgr.get('ext-cal-editwin');
        if(editWin){
            editWin[dismissMethod ? dismissMethod : 'hide'](animTarget);
        }
        return this;
    },
    
    // private
    save: function(){
        // If the store is configured as autoSync:true the record's endEdit
        // method will have already internally caused a save to execute on
        // the store. We only need to save manually when autoSync is false,
        // otherwise we'll create duplicate transactions.
        if(!this.store.autoSync){
            this.store.sync();
        }
    },
    
    // private
    onWrite: function(store, operation){
        if (operation.wasSuccessful()) {
            var rec = operation.records[0];
            
            switch(operation.action){
                case 'create': 
                    this.onAdd(store, rec);
                    break;
                case 'update':
                    this.onUpdate(store, rec, Ext.data.Record.COMMIT);
                    break;
                case 'destroy':
                    this.onRemove(store, rec);
                    break;
            }
        }
    },
    
    // private
    onEventAdd: function(form, rec){
        this.newRecord = rec;
        if(!rec.store){
            this.store.add(rec);
            this.save();
        }
        this.fireEvent('eventadd', this, rec);
    },
    
    // private
    onEventUpdate: function(form, rec){
        this.save();
        this.fireEvent('eventupdate', this, rec);
    },
    
    // private
    onEventDelete: function(form, rec){
        if(rec.store){
            this.store.remove(rec);
        }
        this.save();
        this.fireEvent('eventdelete', this, rec);
    },
    
    // private
    onEventCancel: function(form, rec){
        this.fireEvent('eventcancel', this, rec);
    },
    
    // private -- called from subclasses
    onDayClick: function(dt, ad, el){
        if(this.readOnly === true){
            return;
        }
        if(this.fireEvent('dayclick', this, Ext.Date.clone(dt), ad, el) !== false){
            var M = Extensible.calendar.data.EventMappings,
                data = {};
                
            data[M.StartDate.name] = dt;
            data[M.IsAllDay.name] = ad;
                
            this.showEventEditor(data, el);
        }
    },
    
    // private
    showEventMenu : function(el, xy){
        if(!this.eventMenu){
            this.eventMenu = Ext.create('Extensible.calendar.menu.Event', {
                listeners: {
                    'editdetails': Ext.bind(this.onEditDetails, this),
                    'eventdelete': Ext.bind(this.onDeleteEvent, this),
                    'eventmove'  : Ext.bind(this.onMoveEvent, this)
                }
            });
        }
        this.eventMenu.showForEvent(this.getEventRecordFromEl(el), el, xy);
        this.menuActive = true;
    },
    
    // private
    onEditDetails : function(menu, rec, el){
        this.fireEvent('editdetails', this, rec, el);
        this.menuActive = false;
    },
    
    // private
    onMoveEvent : function(menu, rec, dt){
        this.moveEvent(rec, dt);
        this.menuActive = false;
    },
    
    /**
     * Move the event to a new start date, preserving the original event duration.
     * @param {Object} rec The event {@link Extensible.calendar.data.EventModel record}
     * @param {Object} dt The new start date
     */
    moveEvent : function(rec, dt){
        if(Extensible.Date.compare(rec.data[Extensible.calendar.data.EventMappings.StartDate.name], dt) === 0){
            // no changes
            return;
        }
        if(this.fireEvent('beforeeventmove', this, rec, Ext.Date.clone(dt)) !== false){
            var diff = dt.getTime() - rec.data[Extensible.calendar.data.EventMappings.StartDate.name].getTime();
            rec.beginEdit();
            rec.set(Extensible.calendar.data.EventMappings.StartDate.name, dt);
            rec.set(Extensible.calendar.data.EventMappings.EndDate.name, Extensible.Date.add(rec.data[Extensible.calendar.data.EventMappings.EndDate.name], {millis: diff}));
            rec.endEdit();
            this.save();
            
            this.fireEvent('eventmove', this, rec);
        }
    },
    
    // private
    onDeleteEvent: function(menu, rec, el){
        rec._deleting = true;
        this.deleteEvent(rec, el);
        this.menuActive = false;
    },
    
    /**
     * Delete the specified event.
     * @param {Object} rec The event {@link Extensible.calendar.data.EventModel record}
     */
    deleteEvent: function(rec, /* private */el){
        if(this.fireEvent('beforeeventdelete', this, rec, el) !== false){
            this.store.remove(rec);
            this.save();
            this.fireEvent('eventdelete', this, rec, el);
        }
    },
    
    // private
    onContextMenu : function(e, t){
        var el, match = false;
        
        if(el = e.getTarget(this.eventSelector, 5, true)){
            this.dismissEventEditor().showEventMenu(el, e.getXY());
            match = true;
        }
        
        if(match || this.suppressBrowserContextMenu === true){
            e.preventDefault();
        }
    },
    
    /*
     * Shared click handling.  Each specific view also provides view-specific
     * click handling that calls this first.  This method returns true if it
     * can handle the click (and so the subclass should ignore it) else false.
     */
    onClick : function(e, t) {
        var me = this,
            el = e.getTarget(me.eventSelector, 5);
        
        if (me.dropZone) {
            me.dropZone.clearShims();
        }
        if (me.menuActive === true) {
            // ignore the first click if a context menu is active (let it close)
            me.menuActive = false;
            return true;
        }
        if (el) {
            var id = me.getEventIdFromEl(el),
                rec = me.getEventRecord(id);
            
            if (me.fireEvent('eventclick', me, rec, el) !== false) {
                if (me.readOnly !== true) {
                    me.showEventEditor(rec, el);
                }
            }
            return true;
        }
    },
    
    // private
    onMouseOver : function(e, t){
        if(this.trackMouseOver !== false && (this.dragZone == undefined || !this.dragZone.dragging)){
            if(!this.handleEventMouseEvent(e, t, 'over')){
                this.handleDayMouseEvent(e, t, 'over');
            }
        }
    },
    
    // private
    onMouseOut : function(e, t){
        if(this.trackMouseOver !== false && (this.dragZone == undefined || !this.dragZone.dragging)){
            if(!this.handleEventMouseEvent(e, t, 'out')){
                this.handleDayMouseEvent(e, t, 'out');
            }
        }
    },
    
    // private
    handleEventMouseEvent : function(e, t, type){
        var el;
        if(el = e.getTarget(this.eventSelector, 5, true)){
            var rel = Ext.get(e.getRelatedTarget());
            if(el == rel || el.contains(rel)){
                return true;
            }
            
            var evtId = this.getEventIdFromEl(el);
            
            if(this.eventOverClass != ''){
                var els = this.getEventEls(evtId);
                els[type == 'over' ? 'addCls' : 'removeCls'](this.eventOverClass);
            }
            this.fireEvent('event'+type, this, this.getEventRecord(evtId), el);
            return true;
        }
        return false;
    },
    
    // private
    getDateFromId : function(id, delim){
        var parts = id.split(delim);
        return parts[parts.length-1];
    },
    
    // private
    handleDayMouseEvent : function(e, t, type){
        if(t = e.getTarget('td', 3)){
            if(t.id && t.id.indexOf(this.dayElIdDelimiter) > -1){
                var dt = this.getDateFromId(t.id, this.dayElIdDelimiter),
                    rel = Ext.get(e.getRelatedTarget()),
                    relTD, relDate;
                
                if(rel){
                    relTD = rel.is('td') ? rel : rel.up('td', 3);
                    relDate = relTD && relTD.id ? this.getDateFromId(relTD.id, this.dayElIdDelimiter) : '';
                }
                if(!rel || dt != relDate){
                    var el = this.getDayEl(dt);
                    if(el && this.dayOverClass != ''){
                        el[type == 'over' ? 'addCls' : 'removeCls'](this.dayOverClass);
                    }
                    this.fireEvent('day'+type, this, Ext.Date.parseDate(dt, "Ymd"), el);
                }
            }
        }
    },
    
    // private, MUST be implemented by subclasses
    renderItems : function(){
        throw 'This method must be implemented by a subclass';
    },
    
    // private
    destroy: function(){
        this.callParent(arguments);
        
        if(this.el){
            this.el.un('contextmenu', this.onContextMenu, this);
        }
        Ext.destroy(
            this.editWin, 
            this.eventMenu,
            this.dragZone,
            this.dropZone
        );
    }
});/*
 * This is the view used internally by the panel that displays overflow events in the
 * month view. Anytime a day cell cannot display all of its events, it automatically displays
 * a link at the bottom to view all events for that day. When clicked, a panel pops up that
 * uses this view to display the events for that day.
 */
Ext.define('Extensible.calendar.view.MonthDayDetail', {
    extend: 'Ext.Component',
    alias: 'widget.extensible.monthdaydetailview',
    
    requires: [
        'Ext.XTemplate',
        'Extensible.calendar.view.AbstractCalendar'
    ],
    
    initComponent : function(){
        this.callParent(arguments);
		
        this.addEvents({
            eventsrendered: true
		});
    },
	
    afterRender : function(){
        this.tpl = this.getTemplate();
		
        this.callParent(arguments);
		
        this.el.on({
            'click': this.view.onClick,
			'mouseover': this.view.onMouseOver,
			'mouseout': this.view.onMouseOut,
            scope: this.view
        });
    },
	
    getTemplate : function(){
        if(!this.tpl){
	        this.tpl = Ext.create('Ext.XTemplate',
                '<div class="ext-cal-mdv x-unselectable">',
	                '<table class="ext-cal-mvd-tbl" cellpadding="0" cellspacing="0">',
						'<tbody>',
							'<tpl for=".">',
		                        '<tr><td class="ext-cal-ev">{markup}</td></tr>',
							'</tpl>',
	                    '</tbody>',
	                '</table>',
                '</div>'
	        );
        }
        this.tpl.compile();
        return this.tpl;
    },
	
	update : function(dt){
		this.date = dt;
		this.refresh();
	},
	
    refresh : function(){
		if(!this.rendered){
			return;
		}
        var eventTpl = this.view.getEventTemplate(),
		
			templateData = [];
			
			evts = this.store.queryBy(function(rec){
                var thisDt = Ext.Date.clearTime(this.date, true).getTime(),
                    M = Extensible.calendar.data.EventMappings,
                    recStart = Ext.Date.clearTime(rec.data[M.StartDate.name], true).getTime(),
	            	startsOnDate = (thisDt == recStart),
					spansDate = false,
                    calId = rec.data[M.CalendarId.name],
                    calRec = this.calendarStore ? this.calendarStore.getById(calId) : null;
                    
                if(calRec && calRec.data[Extensible.calendar.data.CalendarMappings.IsHidden.name] === true){
                    // if the event is on a hidden calendar then no need to test the date boundaries
                    return false;
                }
				
				if(!startsOnDate){
                    var recEnd = Ext.Date.clearTime(rec.data[M.EndDate.name], true).getTime();
	            	spansDate = recStart < thisDt && recEnd >= thisDt;
				}
	            return startsOnDate || spansDate;
	        }, this);
		
        Extensible.calendar.view.AbstractCalendar.prototype.sortEventRecordsForDay.call(this, evts);
        
		evts.each(function(evt){
            var item = evt.data,
                M = Extensible.calendar.data.EventMappings;
                
			item._renderAsAllDay = item[M.IsAllDay.name] || Extensible.Date.diffDays(item[M.StartDate.name], item[M.EndDate.name]) > 0;
            item.spanLeft = Extensible.Date.diffDays(item[M.StartDate.name], this.date) > 0;
            item.spanRight = Extensible.Date.diffDays(this.date, item[M.EndDate.name]) > 0;
            item.spanCls = (item.spanLeft ? (item.spanRight ? 'ext-cal-ev-spanboth' : 
                'ext-cal-ev-spanleft') : (item.spanRight ? 'ext-cal-ev-spanright' : ''));

			templateData.push({markup: eventTpl.apply(this.getTemplateEventData(item))});
		}, this);
		
		this.tpl.overwrite(this.el, templateData);
		this.fireEvent('eventsrendered', this, this.date, evts.getCount());
    },
	
	getTemplateEventData : function(evt){
		var data = this.view.getTemplateEventData(evt);
		data._elId = 'dtl-'+data._elId;
		return data;
	}
});/**
 * @class Extensible.calendar.view.Month
 * @extends Extensible.calendar.view.AbstractCalendar
 * <p>Displays a calendar view by month. This class does not usually need ot be used directly as you can
 * use a {@link Extensible.calendar.CalendarPanel CalendarPanel} to manage multiple calendar views at once including
 * the month view.</p>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Extensible.calendar.view.Month', {
    extend: 'Extensible.calendar.view.AbstractCalendar',
    alias: 'widget.extensible.monthview',
    
    requires: [
        'Ext.XTemplate',
        'Extensible.calendar.template.Month',
        'Extensible.calendar.util.WeekEventRenderer',
        'Extensible.calendar.view.MonthDayDetail'
    ],
    
    /**
     * @cfg {String} moreText
     * <p><b>Deprecated.</b> Please override {@link #getMoreText} instead.</p>
     * <p>The text to display in a day box when there are more events than can be displayed and a link is provided to
     * show a popup window with all events for that day (defaults to '+{0} more...', where {0} will be 
     * replaced by the number of additional events that are not currently displayed for the day).</p>
     * @deprecated
     */
    moreText: '+{0} more...',
    /**
     * @cfg {String} detailsTitleDateFormat
     * The date format for the title of the details panel that shows when there are hidden events and the "more" link 
     * is clicked (defaults to 'F j').
     */
    detailsTitleDateFormat: 'F j',
    /**
     * @cfg {Boolean} showTime
     * True to display the current time in today's box in the calendar, false to not display it (defaults to true)
     */
    showTime: true,
    /**
     * @cfg {Boolean} showTodayText
     * True to display the {@link #todayText} string in today's box in the calendar, false to not display it (defaults to true)
     */
    showTodayText: true,
    /**
     * @cfg {Boolean} showHeader
     * True to display a header beneath the navigation bar containing the week names above each week's column, false not to 
     * show it and instead display the week names in the first row of days in the calendar (defaults to false).
     */
    showHeader: false,
    /**
     * @cfg {Boolean} showWeekLinks
     * True to display an extra column before the first day in the calendar that links to the {@link Extensible.calendar.view.Week view}
     * for each individual week, false to not show it (defaults to false). If true, the week links can also contain the week 
     * number depending on the value of {@link #showWeekNumbers}.
     */
    showWeekLinks: false,
    /**
     * @cfg {Boolean} showWeekNumbers
     * True to show the week number for each week in the calendar in the week link column, false to show nothing (defaults to false).
     * Note that if {@link #showWeekLinks} is false this config will have no affect even if true.
     */
    showWeekNumbers: false,
    /**
     * @cfg {String} weekLinkOverClass
     * The CSS class name applied when the mouse moves over a week link element (only applies when {@link #showWeekLinks} is true,
     * defaults to 'ext-week-link-over').
     */
    weekLinkOverClass: 'ext-week-link-over',
    
    //private properties -- do not override:
    daySelector: '.ext-cal-day',
    moreSelector : '.ext-cal-ev-more',
    weekLinkSelector : '.ext-cal-week-link',
    weekCount: -1, // defaults to auto by month
    dayCount: 7,
	moreElIdDelimiter: '-more-',
    weekLinkIdDelimiter: 'ext-cal-week-',
    
    // private
    initComponent : function(){
        this.callParent(arguments);
        
        this.addEvents({
            /**
             * @event dayclick
             * Fires after the user clicks within the view container and not on an event element. This is a cancelable event, so 
             * returning false from a handler will cancel the click without displaying the event editor view. This could be useful 
             * for validating that a user can only create events on certain days.
             * @param {Extensible.calendar.view.Month} this
             * @param {Date} dt The date/time that was clicked on
             * @param {Boolean} allday True if the day clicked on represents an all-day box, else false. Clicks within the 
             * MonthView always return true for this param.
             * @param {Ext.Element} el The Element that was clicked on
             */
            dayclick: true,
            /**
             * @event weekclick
             * Fires after the user clicks within a week link (when {@link #showWeekLinks is true)
             * @param {Extensible.calendar.view.Month} this
             * @param {Date} dt The start date of the week that was clicked on
             */
            weekclick: true,
            // inherited docs
            dayover: true,
            // inherited docs
            dayout: true
        });
    },
	
    // private
	initDD : function(){
		var cfg = {
			view: this,
			createText: this.ddCreateEventText,
			moveText: this.ddMoveEventText,
            ddGroup : this.ddGroup || this.id+'-MonthViewDD'
		};
        
        this.dragZone = Ext.create('Extensible.calendar.dd.DragZone', this.el, cfg);
        this.dropZone = Ext.create('Extensible.calendar.dd.DropZone', this.el, cfg);
	},
    
    // private
    onDestroy : function(){
        Ext.destroy(this.ddSelector);
		Ext.destroy(this.dragZone);
		Ext.destroy(this.dropZone);
        
        this.callParent(arguments);
    },
    
    // private
    afterRender : function(){
        if(!this.tpl){
            this.tpl = Ext.create('Extensible.calendar.template.Month', {
                id: this.id,
                showTodayText: this.showTodayText,
                todayText: this.todayText,
                showTime: this.showTime,
                showHeader: this.showHeader,
                showWeekLinks: this.showWeekLinks,
                showWeekNumbers: this.showWeekNumbers
            });
            this.tpl.compile();
        }
        
        this.addCls('ext-cal-monthview ext-cal-ct');
        
        this.callParent(arguments);
    },
	
    // private
	onResize : function(){
		if (this.monitorResize) {
            this.maxEventsPerDay = this.getMaxEventsPerDay();
			this.refresh(false);
        }
	},
    
    // private
    forceSize: function(){
        // Compensate for the week link gutter width if visible
        if(this.showWeekLinks && this.el){
            var hd = this.el.down('.ext-cal-hd-days-tbl'),
                bgTbl = this.el.select('.ext-cal-bg-tbl'),
                evTbl = this.el.select('.ext-cal-evt-tbl'),
                wkLinkW = this.el.down('.ext-cal-week-link').getWidth(),
                w = this.el.getWidth()-wkLinkW;
            
            hd.setWidth(w);
            bgTbl.setWidth(w);
            evTbl.setWidth(w);
        }
        this.callParent(arguments);
    },
    
    //private
    initClock : function(){
        if(Ext.fly(this.id+'-clock') !== null){
            this.prevClockDay = new Date().getDay();
            if(this.clockTask){
                Ext.TaskManager.stop(this.clockTask);
            }
            this.clockTask = Ext.TaskManager.start({
                run: function(){ 
                    var el = Ext.fly(this.id+'-clock'),
                        t = new Date();
                        
                    if(t.getDay() == this.prevClockDay){
                        if(el){
                            el.update(Ext.Date.format(t, Extensible.Date.use24HourTime ? 'G:i' : 'g:ia'));
                        }
                    }
                    else{
                        this.prevClockDay = t.getDay();
                        this.moveTo(t);
                    }
                },
                scope: this,
                interval: 1000
            });
        }
    },
    
    /**
     * <p>Returns the text to display in a day box when there are more events than can be displayed and a link is 
     * provided to show a popup window with all events for that day (defaults to '+{0} more...', where {0} will be 
     * replaced by the number of additional events that are not currently displayed for the day).</p>
     * @param {Integer} numEvents The number of events currently hidden from view
     * @return {String} The text to display for the "more" link 
     */
    getMoreText: function(numEvents){
        return this.moreText;
    },

    // inherited docs
    getEventBodyMarkup : function(){
        if(!this.eventBodyMarkup){
            this.eventBodyMarkup = ['{Title}',
	            '<tpl if="_isReminder">',
	                '<i class="ext-cal-ic ext-cal-ic-rem">&#160;</i>',
	            '</tpl>',
	            '<tpl if="_isRecurring">',
	                '<i class="ext-cal-ic ext-cal-ic-rcr">&#160;</i>',
	            '</tpl>',
	            '<tpl if="spanLeft">',
	                '<i class="ext-cal-spl">&#160;</i>',
	            '</tpl>',
	            '<tpl if="spanRight">',
	                '<i class="ext-cal-spr">&#160;</i>',
	            '</tpl>'
	        ].join('');
        }
        return this.eventBodyMarkup;
    },
    
    // inherited docs
    getEventTemplate : function(){
        if(!this.eventTpl){
	        var tpl, body = this.getEventBodyMarkup();
            
	        tpl = !(Ext.isIE || Ext.isOpera) ? 
				Ext.create('Ext.XTemplate',
                    '<div class="{_extraCls} {spanCls} ext-cal-evt ext-cal-evr">',
		                body,
		            '</div>'
		        ) 
				: Ext.create('Ext.XTemplate',
		            '<tpl if="_renderAsAllDay">',
                        '<div class="{_extraCls} {spanCls} ext-cal-evt ext-cal-evo">',
		                    '<div class="ext-cal-evm">',
		                        '<div class="ext-cal-evi">',
		            '</tpl>',
		            '<tpl if="!_renderAsAllDay">',
                        '<div class="{_extraCls} ext-cal-evt ext-cal-evr">',
		            '</tpl>',
		            body,
		            '<tpl if="_renderAsAllDay">',
		                        '</div>',
		                    '</div>',
		            '</tpl>',
		                '</div>'
	        	);
            tpl.compile();
            this.eventTpl = tpl;
        }
        return this.eventTpl;
    },
    
    // private
    getTemplateEventData : function(evt){
		var M = Extensible.calendar.data.EventMappings,
            extraClasses = [this.getEventSelectorCls(evt[M.EventId.name])],
            data = {},
            recurring = evt[M.RRule.name] != '',
            colorCls = 'x-cal-default',
		    title = evt[M.Title.name],
            fmt = Extensible.Date.use24HourTime ? 'G:i ' : 'g:ia ';
        
        if(this.calendarStore && evt[M.CalendarId.name]){
            var rec = this.calendarStore.findRecord(Extensible.calendar.data.CalendarMappings.CalendarId.name, evt[M.CalendarId.name]);
            if(rec){
                colorCls = 'x-cal-' + rec.data[Extensible.calendar.data.CalendarMappings.ColorId.name];
            }
        }
        colorCls += (evt._renderAsAllDay ? '-ad' : '');
        extraClasses.push(colorCls);
        
        if(this.getEventClass){
            var rec = this.getEventRecord(evt[M.EventId.name]),
                cls = this.getEventClass(rec, !!evt._renderAsAllDay, data, this.store);
            extraClasses.push(cls);
        }
        
		data._extraCls = extraClasses.join(' ');
        data._isRecurring = evt.Recurrence && evt.Recurrence != '';
        data._isReminder = evt[M.Reminder.name] && evt[M.Reminder.name] != '';
        data.Title = (evt[M.IsAllDay.name] ? '' : Ext.Date.format(evt[M.StartDate.name], fmt)) + 
                (!title || title.length == 0 ? this.defaultEventTitleText : title);
        
        return Ext.applyIf(data, evt);
    },
    
    // private
	refresh : function(reloadData){
        Extensible.log('refresh (MonthView)');
		if(this.detailPanel){
			this.detailPanel.hide();
		}
		this.callParent(arguments);
        
        if(this.showTime !== false){
            this.initClock();
        }
	},
    
    // private
    renderItems : function(){
        Extensible.calendar.util.WeekEventRenderer.render({
            eventGrid: this.allDayOnly ? this.allDayGrid : this.eventGrid,
            viewStart: this.viewStart,
            tpl: this.getEventTemplate(),
            maxEventsPerDay: this.maxEventsPerDay,
            viewId: this.id,
            templateDataFn: Ext.bind(this.getTemplateEventData, this),
            evtMaxCount: this.evtMaxCount,
            weekCount: this.weekCount,
            dayCount: this.dayCount,
            getMoreText: Ext.bind(this.getMoreText, this)
        });
        this.fireEvent('eventsrendered', this);
    },
	
    // private
	getDayEl : function(dt){
		return Ext.get(this.getDayId(dt));
	},
	
    // private
	getDayId : function(dt){
		if(Ext.isDate(dt)){
            dt = Ext.Date.format(dt, 'Ymd');
		}
		return this.id + this.dayElIdDelimiter + dt;
	},
	
    // private
	getWeekIndex : function(dt){
		var el = this.getDayEl(dt).up('.ext-cal-wk-ct');
		return parseInt(el.id.split('-wk-')[1]);
	},
	
    // private
	getDaySize : function(contentOnly){
        var box = this.el.getBox(),
            padding = this.getViewPadding(),
            w = (box.width - padding.width) / this.dayCount,
            h = (box.height - padding.height) / this.getWeekCount();
            
		if(contentOnly){
            // measure last row instead of first in case text wraps in first row
			var hd = this.el.select('.ext-cal-dtitle').last().parent('tr');
			h = hd ? h-hd.getHeight(true) : h;
		}
		return {height: h, width: w};
	},
    
    // private
    getEventHeight : function() {
        if (!this.eventHeight) {
            var evt = this.el.select('.ext-cal-evt').first();
            if(evt){
                this.eventHeight = evt.parent('td').getHeight();
            }
            else {
                return 16; // no events rendered, so try setting this.eventHeight again later
            }
        }
        return this.eventHeight;
    },
	
    // private
	getMaxEventsPerDay : function(){
		var dayHeight = this.getDaySize(true).height,
			eventHeight = this.getEventHeight(),
            max = Math.max(Math.floor((dayHeight - eventHeight) / eventHeight), 0);
		
		return max;
	},
	
    // private
    getViewPadding: function(sides) {
        var sides = sides || 'tlbr',
            top = sides.indexOf('t') > -1,
            left = sides.indexOf('l') > -1,
            right = sides.indexOf('r') > -1,
            height = this.showHeader && top ? this.el.select('.ext-cal-hd-days-tbl').first().getHeight() : 0,
            width = 0;
        
        if (this.isHeaderView) {
            if (left) {
                width = this.el.select('.ext-cal-gutter').first().getWidth();
            }
            if (right) {
                width += this.el.select('.ext-cal-gutter-rt').first().getWidth();
            }
        }
        else if (this.showWeekLinks && left) {
            width = this.el.select('.ext-cal-week-link').first().getWidth();
        }
        
        return {
            height: height,
            width: width
        }
    },
    
    // private
	getDayAt : function(x, y){
		var box = this.el.getBox(),
            padding = this.getViewPadding('tl'), // top/left only since we only want the xy offsets
			daySize = this.getDaySize(),
            dayL = Math.floor(((x - box.x - padding.width) / daySize.width)),
            dayT = Math.floor(((y - box.y - padding.height) / daySize.height)),
			days = (dayT * 7) + dayL,
            dt = Extensible.Date.add(this.viewStart, {days: days});
        
		return {
			date: dt,
			el: this.getDayEl(dt)
		}
	},
    
    // inherited docs
    moveNext : function(){
        return this.moveMonths(1, true);
    },
    
    // inherited docs
    movePrev : function(){
        return this.moveMonths(-1, true);
    },
    
    // private
	onInitDrag : function(){
        this.callParent(arguments);
        
		Ext.select(this.daySelector).removeCls(this.dayOverClass);
		if(this.detailPanel){
			this.detailPanel.hide();
		}
	},
	
    // private
	onMoreClick : function(dt){
		if(!this.detailPanel){
	        this.detailPanel = Ext.create('Ext.Panel', {
				id: this.id+'-details-panel',
				title: Ext.Date.format(dt, this.detailsTitleDateFormat),
				layout: 'fit',
				floating: true,
				renderTo: Ext.getBody(),
				tools: [{
					type: 'close',
					handler: function(e, t, p){
						p.ownerCt.hide();
					}
				}],
				items: {
					xtype: 'extensible.monthdaydetailview',
					id: this.id+'-details-view',
					date: dt,
					view: this,
					store: this.store,
                    calendarStore: this.calendarStore,
					listeners: {
                        'eventsrendered': Ext.bind(this.onDetailViewUpdated, this)
					}
				}
			});
            
            if(this.enableContextMenus && this.readOnly !== true){
                this.detailPanel.body.on('contextmenu', this.onContextMenu, this);
            }
		}
		else{
			this.detailPanel.setTitle(Ext.Date.format(dt, this.detailsTitleDateFormat));
		}
		this.detailPanel.getComponent(this.id+'-details-view').update(dt);
	},
	
    // private
	onDetailViewUpdated : function(view, dt, numEvents){
		var p = this.detailPanel,
			dayEl = this.getDayEl(dt),
			box = dayEl.getBox();
		
		p.setWidth(Math.max(box.width, 220));
		p.show();
		p.getPositionEl().alignTo(dayEl, 't-t?');
	},
    
    // private
    onHide : function(){
        this.callParent(arguments);
        
        if(this.detailPanel){
            this.detailPanel.hide();
        }
    },
	
    // private
    onClick : function(e, t){
        if(this.detailPanel){
            this.detailPanel.hide();
        }
        if(el = e.getTarget(this.moreSelector, 3)){
            var dt = el.id.split(this.moreElIdDelimiter)[1];
            this.onMoreClick(Ext.Date.parseDate(dt, 'Ymd'));
            return;
        }
        if(el = e.getTarget(this.weekLinkSelector, 3)){
            var dt = el.id.split(this.weekLinkIdDelimiter)[1];
            this.fireEvent('weekclick', this, Ext.Date.parseDate(dt, 'Ymd'));
            return;
        }
        if(Extensible.calendar.view.Month.superclass.onClick.apply(this, arguments)){
            // The superclass handled the click already so exit
            return;
        }
        if(el = e.getTarget('td', 3)){
            if(el.id && el.id.indexOf(this.dayElIdDelimiter) > -1){
                var parts = el.id.split(this.dayElIdDelimiter),
                    dt = parts[parts.length-1];
                    
                //this.fireEvent('dayclick', this, Ext.Date.parseDate(dt, 'Ymd'), false, Ext.get(this.getDayId(dt)));
                this.onDayClick(Ext.Date.parseDate(dt, 'Ymd'), false, Ext.get(this.getDayId(dt)));
                return;
            }
        }
    },
    
    // private
    handleDayMouseEvent : function(e, t, type){
        var el = e.getTarget(this.weekLinkSelector, 3, true);
        if(el){
            el[type == 'over' ? 'addCls' : 'removeCls'](this.weekLinkOverClass);
            return;
        }
        this.callParent(arguments);
    },
    
    // private
    destroy: function(){
        this.callParent(arguments);
        
        if(this.detailsPanel){
            this.detailPanel.body.un('contextmenu', this.onContextMenu, this);
        }
    }
});/**
 * @class Extensible.calendar.view.DayHeader
 * @extends Extensible.calendar.view.Month
 * <p>This is the header area container within the day and week views where all-day events are displayed.
 * Normally you should not need to use this class directly -- instead you should use {@link Extensible.calendar.view.Day DayView}
 * which aggregates this class and the {@link Extensible.calendar.view.DayBody DayBodyView} into the single unified view
 * presented by {@link Extensible.calendar.CalendarPanel CalendarPanel}.</p>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Extensible.calendar.view.DayHeader', {
    extend: 'Extensible.calendar.view.Month',
    alias: 'widget.extensible.dayheaderview',
    
    requires: [
        'Extensible.calendar.template.DayHeader'
    ],
    
    // private configs
    weekCount: 1,
    dayCount: 1,
    allDayOnly: true,
    monitorResize: false,
    isHeaderView: true,
    
    // The event is declared in MonthView but we're just overriding the docs:
    /**
     * @event dayclick
     * Fires after the user clicks within the view container and not on an event element. This is a cancelable event, so 
     * returning false from a handler will cancel the click without displaying the event editor view. This could be useful 
     * for validating that a user can only create events on certain days.
     * @param {Extensible.calendar.view.DayHeader} this
     * @param {Date} dt The date/time that was clicked on
     * @param {Boolean} allday True if the day clicked on represents an all-day box, else false. Clicks within the 
     * DayHeaderView always return true for this param.
     * @param {Ext.Element} el The Element that was clicked on
     */
    
    // private
    afterRender : function(){
        if(!this.tpl){
            this.tpl = Ext.create('Extensible.calendar.template.DayHeader', {
                id: this.id,
                showTodayText: this.showTodayText,
                todayText: this.todayText,
                showTime: this.showTime
            });
        }
        this.tpl.compile();
        this.addCls('ext-cal-day-header');
        
        this.callParent(arguments);
    },
    
    // private
    forceSize: Ext.emptyFn,
    
    // private
    refresh : function(reloadData){
        Extensible.log('refresh (DayHeaderView)');
        this.callParent(arguments);
        this.recalcHeaderBox();
    },
    
    // private
    recalcHeaderBox : function(){
        var tbl = this.el.down('.ext-cal-evt-tbl'),
            h = tbl.getHeight();
        
        this.el.setHeight(h+7);
        
        // These should be auto-height, but since that does not work reliably
        // across browser / doc type, we have to size them manually
        this.el.down('.ext-cal-hd-ad-inner').setHeight(h+5);
        this.el.down('.ext-cal-bg-tbl').setHeight(h+5);
    },
    
    // private
    moveNext : function(){
        return this.moveDays(this.dayCount);
    },

    // private
    movePrev : function(){
        return this.moveDays(-this.dayCount);
    },
    
    // private
    onClick : function(e, t){
        if(el = e.getTarget('td', 3)){
            if(el.id && el.id.indexOf(this.dayElIdDelimiter) > -1){
                var parts = el.id.split(this.dayElIdDelimiter),
                    dt = parts[parts.length-1];
                    
                this.onDayClick(Ext.Date.parseDate(dt, 'Ymd'), true, Ext.get(this.getDayId(dt, true)));
                return;
            }
        }
        this.callParent(arguments);
    }
});/**
 * @class Extensible.calendar.view.DayBody
 * @extends Extensible.calendar.view.AbstractCalendar
 * <p>This is the scrolling container within the day and week views where non-all-day events are displayed.
 * Normally you should not need to use this class directly -- instead you should use {@link 
 * Extensible.calendar.view.Day DayView} which aggregates this class and the {@link 
 * Extensible.calendar.view.DayHeader DayHeaderView} into the single unified view
 * presented by {@link Extensible.calendar.CalendarPanel CalendarPanel}.</p>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Extensible.calendar.view.DayBody', {
    extend: 'Extensible.calendar.view.AbstractCalendar',
    alias: 'widget.extensible.daybodyview',
    
    requires: [
        'Ext.XTemplate',
        'Extensible.calendar.template.DayBody',
        'Extensible.calendar.data.EventMappings',
        'Extensible.calendar.dd.DayDragZone',
        'Extensible.calendar.dd.DayDropZone'
    ],
    
    //private
    dayColumnElIdDelimiter: '-day-col-',
    hourIncrement: 60,
    
    //private
    initComponent : function(){
        this.callParent(arguments);
        
        if(this.readOnly === true){
            this.enableEventResize = false;
        }
        this.incrementsPerHour = this.hourIncrement / this.ddIncrement;
        this.minEventHeight = this.minEventDisplayMinutes / (this.hourIncrement / this.hourHeight);
        
        this.addEvents({
            /**
             * @event beforeeventresize
             * Fires after the user drags the resize handle of an event to resize it, but before the resize
             * operation is carried out. This is a cancelable event, so returning false from a handler will
             * cancel the resize operation.
             * @param {Extensible.calendar.view.DayBody} this
             * @param {Extensible.calendar.data.EventModel} rec The original {@link
             * Extensible.calendar.data.EventModel record} for the event that was resized
             * @param {Object} data An object containing the new start and end dates that will be set into the
             * event record if the event is not canceled. Format of the object is: {StartDate: [date], EndDate: [date]}
             */
            beforeeventresize: true,
            /**
             * @event eventresize
             * Fires after the user has drag-dropped the resize handle of an event and the resize operation is
             * complete. If you need to cancel the resize operation you should handle the {@link #beforeeventresize}
             * event and return false from your handler function.
             * @param {Extensible.calendar.view.DayBody} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel
             * record} for the event that was resized containing the updated start and end dates
             */
            eventresize: true,
            /**
             * @event dayclick
             * Fires after the user clicks within the view container and not on an event element. This is a
             * cancelable event, so returning false from a handler will cancel the click without displaying the event
             * editor view. This could be useful for validating that a user can only create events on certain days.
             * @param {Extensible.calendar.view.DayBody} this
             * @param {Date} dt The date/time that was clicked on
             * @param {Boolean} allday True if the day clicked on represents an all-day box, else false. Clicks
             * within the DayBodyView always return false for this param.
             * @param {Ext.Element} el The Element that was clicked on
             */
            dayclick: true
        });
    },
    
    //private
    initDD : function(){
        var cfg = {
            view: this,
            createText: this.ddCreateEventText,
            moveText: this.ddMoveEventText,
            resizeText: this.ddResizeEventText,
            ddIncrement: this.ddIncrement,
            ddGroup: this.ddGroup || this.id+'-DayViewDD'
        };

        this.el.ddScrollConfig = {
            // scrolling is buggy in IE/Opera for some reason.  A larger vthresh
            // makes it at least functional if not perfect
            vthresh: Ext.isIE || Ext.isOpera ? 100 : 40,
            hthresh: -1,
            frequency: 50,
            increment: 100,
            ddGroup: this.ddGroup || this.id+'-DayViewDD'
        };
        
        this.dragZone = Ext.create('Extensible.calendar.dd.DayDragZone', this.el, Ext.apply({
            // disabled for now because of bugs in Ext 4 ScrollManager:
            //containerScroll: true
        }, cfg));
        
        this.dropZone = Ext.create('Extensible.calendar.dd.DayDropZone', this.el, cfg);
    },
    
    //private
    refresh : function(reloadData){
        Extensible.log('refresh (DayBodyView)');
        var top = this.el.getScroll().top;
        
        this.callParent(arguments);
        
        // skip this if the initial render scroll position has not yet been set.
        // necessary since IE/Opera must be deferred, so the first refresh will
        // override the initial position by default and always set it to 0.
        if(this.scrollReady){
            this.scrollTo(top);
        }
    },

    /**
     * Scrolls the container to the specified vertical position. If the view is large enough that
     * there is no scroll overflow then this method will have no affect.
     * @param {Number} y The new vertical scroll position in pixels 
     * @param {Boolean} defer (optional) <p>True to slightly defer the call, false to execute immediately.</p>
     * 
     * <p>This method will automatically defer itself for IE and Opera (even if you pass false) otherwise
     * the scroll position will not update in those browsers. You can optionally pass true, however, to
     * force the defer in all browsers, or use your own custom conditions to determine whether this is needed.</p>
     * 
     * <p>Note that this method should not generally need to be called directly as scroll position is
     * managed internally.</p>
     */
    scrollTo : function(y, defer){
        defer = defer || (Ext.isIE || Ext.isOpera);
        if(defer){
            Ext.defer(function(){
                this.el.scrollTo('top', y);
                this.scrollReady = true;
            }, 10, this);
        }
        else{
            this.el.scrollTo('top', y);
            this.scrollReady = true;
        }
    },

    // private
    afterRender : function(){
        if(!this.tpl){
            this.tpl = Ext.create('Extensible.calendar.template.DayBody', {
                id: this.id,
                dayCount: this.dayCount,
                showTodayText: this.showTodayText,
                todayText: this.todayText,
                showTime: this.showTime,
                showHourSeparator: this.showHourSeparator,
                viewStartHour: this.viewStartHour,
                viewEndHour: this.viewEndHour,
                hourIncrement: this.hourIncrement,
                hourHeight: this.hourHeight
            });
        }
        this.tpl.compile();
        
        this.addCls('ext-cal-body-ct');
        
        this.callParent(arguments);
        
        // default scroll position to scrollStartHour (7am by default) or min view hour if later
        var startHour = Math.max(this.scrollStartHour, this.viewStartHour),
            scrollStart = Math.max(0, startHour - this.viewStartHour);
            
        if(scrollStart > 0){
            this.scrollTo(scrollStart * this.hourHeight);
        }
    },
    
    // private
    forceSize: Ext.emptyFn,
    
    // private -- called from DayViewDropZone
    onEventResize : function(rec, data){
        if(this.fireEvent('beforeeventresize', this, rec, data) !== false){
            var D = Extensible.Date,
                start = Extensible.calendar.data.EventMappings.StartDate.name,
                end = Extensible.calendar.data.EventMappings.EndDate.name;
                
            if(D.compare(rec.data[start], data.StartDate) === 0 &&
                D.compare(rec.data[end], data.EndDate) === 0){
                // no changes
                return;
            } 
            rec.set(start, data.StartDate);
            rec.set(end, data.EndDate);
            this.onEventUpdate(null, rec);
            
            this.fireEvent('eventresize', this, rec);
        }
    },

    // inherited docs
    getEventBodyMarkup : function(){
        if(!this.eventBodyMarkup){
            this.eventBodyMarkup = ['{Title}',
                '<tpl if="_isReminder">',
                    '<i class="ext-cal-ic ext-cal-ic-rem">&#160;</i>',
                '</tpl>',
                '<tpl if="_isRecurring">',
                    '<i class="ext-cal-ic ext-cal-ic-rcr">&#160;</i>',
                '</tpl>'
//                '<tpl if="spanLeft">',
//                    '<i class="ext-cal-spl">&#160;</i>',
//                '</tpl>',
//                '<tpl if="spanRight">',
//                    '<i class="ext-cal-spr">&#160;</i>',
//                '</tpl>'
            ].join('');
        }
        return this.eventBodyMarkup;
    },
    
    // inherited docs
    getEventTemplate : function(){
        if(!this.eventTpl){
            this.eventTpl = !(Ext.isIE || Ext.isOpera) ? 
                Ext.create('Ext.XTemplate',
                    '<div id="{_elId}" class="{_extraCls} ext-cal-evt ext-cal-evr" ',
                            'style="left: {_left}%; width: {_width}%; top: {_top}px; height: {_height}px;">',
                        '<div class="ext-evt-bd">', this.getEventBodyMarkup(), '</div>',
                        this.enableEventResize ?
                            '<div class="ext-evt-rsz"><div class="ext-evt-rsz-h">&#160;</div></div>' : '',
                    '</div>'
                )
                : Ext.create('Ext.XTemplate',
                    '<div id="{_elId}" class="ext-cal-evt {_extraCls}" ',
                            'style="left: {_left}%; width: {_width}%; top: {_top}px;">',
                        '<div class="ext-cal-evb">&#160;</div>',
                        '<dl style="height: {_height}px;" class="ext-cal-evdm">',
                            '<dd class="ext-evt-bd">',
                                this.getEventBodyMarkup(),
                            '</dd>',
                            this.enableEventResize ?
                                '<div class="ext-evt-rsz"><div class="ext-evt-rsz-h">&#160;</div></div>' : '',
                        '</dl>',
                        '<div class="ext-cal-evb">&#160;</div>',
                    '</div>'
                );
            this.eventTpl.compile();
        }
        return this.eventTpl;
    },
    
    /**
     * <p>Returns the XTemplate that is bound to the calendar's event store (it expects records of type
     * {@link Extensible.calendar.data.EventModel}) to populate the calendar views with <strong>all-day</strong> events. 
     * Internally this method by default generates different markup for browsers that support CSS border radius 
     * and those that don't. This method can be overridden as needed to customize the markup generated.</p>
     * <p>Note that this method calls {@link #getEventBodyMarkup} to retrieve the body markup for events separately
     * from the surrounding container markup.  This provdes the flexibility to customize what's in the body without
     * having to override the entire XTemplate. If you do override this method, you should make sure that your 
     * overridden version also does the same.</p>
     * @return {Ext.XTemplate} The event XTemplate
     */
    getEventAllDayTemplate : function(){
        if(!this.eventAllDayTpl){
            var tpl, body = this.getEventBodyMarkup();
            
            tpl = !(Ext.isIE || Ext.isOpera) ? 
                Ext.create('Ext.XTemplate',
                    '<div class="{_extraCls} {spanCls} ext-cal-evt ext-cal-evr" ',
                            'style="left: {_left}%; width: {_width}%; top: {_top}px; height: {_height}px;">',
                        body,
                    '</div>'
                ) 
                : Ext.create('Ext.XTemplate',
                    '<div class="ext-cal-evt" ',
                            'style="left: {_left}%; width: {_width}%; top: {_top}px; height: {_height}px;">',
                        '<div class="{_extraCls} {spanCls} ext-cal-evo">',
                            '<div class="ext-cal-evm">',
                                '<div class="ext-cal-evi">',
                                    body,
                                '</div>',
                            '</div>',
                        '</div>',
                    '</div>'
                );
            tpl.compile();
            this.eventAllDayTpl = tpl;
        }
        return this.eventAllDayTpl;
    },
    
    // private
    getTemplateEventData : function(evt){
        var M = Extensible.calendar.data.EventMappings,
            extraClasses = [this.getEventSelectorCls(evt[M.EventId.name])],
            data = {},
            colorCls = 'x-cal-default',
            title = evt[M.Title.name],
            fmt = Extensible.Date.use24HourTime ? 'G:i ' : 'g:ia ',
            recurring = evt[M.RRule.name] != '';
        
        this.getTemplateEventBox(evt);
        
        if(this.calendarStore && evt[M.CalendarId.name]){
            var rec = this.calendarStore.findRecord(Extensible.calendar.data.CalendarMappings.CalendarId.name, 
                    evt[M.CalendarId.name]);
                
            if(rec){
                colorCls = 'x-cal-' + rec.data[Extensible.calendar.data.CalendarMappings.ColorId.name];
            }
        }
        colorCls += (evt._renderAsAllDay ? '-ad' : '') + (Ext.isIE || Ext.isOpera ? '-x' : '');
        extraClasses.push(colorCls);
        
        if(this.getEventClass){
            var rec = this.getEventRecord(evt[M.EventId.name]),
                cls = this.getEventClass(rec, !!evt._renderAsAllDay, data, this.store);
            extraClasses.push(cls);
        }
        
        data._extraCls = extraClasses.join(' ');
        data._isRecurring = evt.Recurrence && evt.Recurrence != '';
        data._isReminder = evt[M.Reminder.name] && evt[M.Reminder.name] != '';
        data.Title = (evt[M.IsAllDay.name] ? '' : Ext.Date.format(evt[M.StartDate.name], fmt)) + 
                (!title || title.length == 0 ? this.defaultEventTitleText : title);
        
        return Ext.applyIf(data, evt);
    },
    
    // private
    getEventPositionOffsets: function(){
        return {
            top: 0,
            height: -1
        }
    },
    
    // private
    getTemplateEventBox : function(evt){
        var heightFactor = this.hourHeight / this.hourIncrement,
            start = evt[Extensible.calendar.data.EventMappings.StartDate.name],
            end = evt[Extensible.calendar.data.EventMappings.EndDate.name],
            startOffset = Math.max(start.getHours() - this.viewStartHour, 0),
            endOffset = Math.min(end.getHours() - this.viewStartHour, this.viewEndHour - this.viewStartHour),
            startMins = startOffset * this.hourIncrement,
            endMins = endOffset * this.hourIncrement,
            viewEndDt = Extensible.Date.add(Ext.Date.clone(end), {hours: this.viewEndHour, clearTime: true}),
            evtOffsets = this.getEventPositionOffsets();
            
        if(start.getHours() >= this.viewStartHour){
            // only add the minutes if the start is visible, otherwise it offsets the event incorrectly
            startMins += start.getMinutes();
        }
        if(end <= viewEndDt){
            // only add the minutes if the end is visible, otherwise it offsets the event incorrectly
            endMins += end.getMinutes();
        }

        evt._left = 0;
        evt._width = 100;
        evt._top = startMins * heightFactor + evtOffsets.top;
        evt._height = Math.max(((endMins - startMins) * heightFactor), this.minEventHeight) + evtOffsets.height;
    },

    // private
    renderItems: function(){
        var day = 0, evts = [];
        for(; day < this.dayCount; day++){
            var ev = emptyCells = skipped = 0, 
                d = this.eventGrid[0][day],
                ct = d ? d.length : 0, 
                evt;
            
            for(; ev < ct; ev++){
                evt = d[ev];
                if(!evt){
                    continue;
                }
                var item = evt.data || evt.event.data,
                    M = Extensible.calendar.data.EventMappings,
                    ad = item[M.IsAllDay.name] === true,
                    span = this.isEventSpanning(evt.event || evt),
                    renderAsAllDay = ad || span;
                         
                if(renderAsAllDay){
                    // this event is already rendered in the header view
                    continue;
                }
                Ext.apply(item, {
                    cls: 'ext-cal-ev',
                    _positioned: true
                });
                evts.push({
                    data: this.getTemplateEventData(item),
                    date: Extensible.Date.add(this.viewStart, {days: day})
                });
            }
        }
        
        // overlapping event pre-processing loop
        var i = j = 0, overlapCols = [], l = evts.length, prevDt;
        for(; i<l; i++){
            var evt = evts[i].data, 
                evt2 = null, 
                dt = evt[Extensible.calendar.data.EventMappings.StartDate.name].getDate();
            
            for(j=0; j<l; j++){
                if(i==j)continue;
                evt2 = evts[j].data;
                if(this.isOverlapping(evt, evt2)){
                    evt._overlap = evt._overlap == undefined ? 1 : evt._overlap+1;
                    if(i<j){
                        if(evt._overcol===undefined){
                            evt._overcol = 0;
                        }
                        evt2._overcol = evt._overcol+1;
                        overlapCols[dt] = overlapCols[dt] ? Math.max(overlapCols[dt], evt2._overcol) : evt2._overcol;
                    }
                }
            }
        }
        
        // rendering loop
        for(i=0; i<l; i++){
            var evt = evts[i].data,
                dt = evt[Extensible.calendar.data.EventMappings.StartDate.name].getDate();
                
            if(evt._overlap !== undefined){
                var colWidth = 100 / (overlapCols[dt]+1),
                    evtWidth = 100 - (colWidth * evt._overlap);
                    
                evt._width = colWidth;
                evt._left = colWidth * evt._overcol;
            }
            var markup = this.getEventTemplate().apply(evt),
                target = this.id + '-day-col-' + Ext.Date.format(evts[i].date, 'Ymd');
                
            Ext.core.DomHelper.append(target, markup);
        }
        
        this.fireEvent('eventsrendered', this);
    },
    
    // private
    getDayEl : function(dt){
        return Ext.get(this.getDayId(dt));
    },
    
    // private
    getDayId : function(dt){
        if(Ext.isDate(dt)){
            dt = Ext.Date.format(dt, 'Ymd');
        }
        return this.id + this.dayColumnElIdDelimiter + dt;
    },
    
    // private
    getDaySize : function(){
        var box = this.el.down('.ext-cal-day-col-inner').getBox();
        return {height: box.height, width: box.width};
    },
    
    // private
    getDayAt : function(x, y){
        var sel = '.ext-cal-body-ct',
            xoffset = this.el.down('.ext-cal-day-times').getWidth(),
            viewBox = this.el.getBox(),
            daySize = this.getDaySize(false),
            relX = x - viewBox.x - xoffset,
            dayIndex = Math.floor(relX / daySize.width), // clicked col index
            scroll = this.el.getScroll(),
            row = this.el.down('.ext-cal-bg-row'), // first avail row, just to calc size
            rowH = row.getHeight() / this.incrementsPerHour,
            relY = y - viewBox.y - rowH + scroll.top,
            rowIndex = Math.max(0, Math.ceil(relY / rowH)),
            mins = rowIndex * (this.hourIncrement / this.incrementsPerHour),
            dt = Extensible.Date.add(this.viewStart, {days: dayIndex, minutes: mins, hours: this.viewStartHour}),
            el = this.getDayEl(dt),
            timeX = x;
        
        if(el){
            timeX = el.getLeft();
        }
        
        return {
            date: dt,
            el: el,
            // this is the box for the specific time block in the day that was clicked on:
            timeBox: {
                x: timeX,
                y: (rowIndex * this.hourHeight / this.incrementsPerHour) + viewBox.y - scroll.top,
                width: daySize.width,
                height: rowH
            } 
        }
    },

    // private
    onClick : function(e, t){
        if(this.dragPending || Extensible.calendar.view.DayBody.superclass.onClick.apply(this, arguments)){
            // The superclass handled the click already so exit
            return;
        }
        if(e.getTarget('.ext-cal-day-times', 3) !== null){
            // ignore clicks on the times-of-day gutter
            return;
        }
        var el = e.getTarget('td', 3);
        if(el){
            if(el.id && el.id.indexOf(this.dayElIdDelimiter) > -1){
                var dt = this.getDateFromId(el.id, this.dayElIdDelimiter);
                this.onDayClick(Ext.Date.parseDate(dt, 'Ymd'), true, Ext.get(this.getDayId(dt)));
                return;
            }
        }
        var day = this.getDayAt(e.getX(), e.getY());
        if(day && day.date){
            this.onDayClick(day.date, false, null);
        }
    }
});/**
 * @class Extensible.calendar.view.Day
 * @extends Ext.Container
 * <p>Unlike other calendar views, is not actually a subclass of {@link Extensible.calendar.view.AbstractCalendar CalendarView}.
 * Instead it is a {@link Ext.Container Container} subclass that internally creates and manages the layouts of
 * a {@link Extensible.calendar.view.DayHeader DayHeaderView} and a {@link Extensible.calendar.view.DayBody DayBodyView}. As such
 * DayView accepts any config values that are valid for DayHeaderView and DayBodyView and passes those through
 * to the contained views. It also supports the interface required of any calendar view and in turn calls methods
 * on the contained views as necessary.</p>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Extensible.calendar.view.Day', {
    extend: 'Ext.Container',
    alias: 'widget.extensible.dayview',
    
    requires: [
        'Extensible.calendar.view.AbstractCalendar',
        'Extensible.calendar.view.DayHeader',
        'Extensible.calendar.view.DayBody'
    ],
    
    /**
     * @cfg {String} todayText
     * The text to display in the current day's box in the calendar when {@link #showTodayText} is true (defaults to 'Today')
     */
    /**
     * @cfg {Boolean} readOnly
     * True to prevent clicks on events or the view from providing CRUD capabilities, false to enable CRUD (the default).
     */

    /**
     * @cfg {Boolean} showTime
     * True to display the current time in today's box in the calendar, false to not display it (defaults to true)
     */
    showTime: true,
    /**
     * @cfg {Boolean} showTodayText
     * True to display the {@link #todayText} string in today's box in the calendar, false to not display it (defaults to true)
     */
    showTodayText: true,
    /**
     * @cfg {Number} dayCount
     * The number of days to display in the view (defaults to 1). Only values from 1 to 7 are allowed.
     */
    dayCount: 1,
    /**
     * @cfg {Boolean} enableEventResize
     * True to allow events in the view's scrolling body area to be updated by a resize handle at the 
     * bottom of the event, false to disallow it (defaults to true). If {@link #readOnly} is true event 
     * resizing will be disabled automatically.
     */
    enableEventResize: true,
    /**
     * @cfg {Integer} ddIncrement
     * <p>The number of minutes between each step during various drag/drop operations in the view (defaults to 30).
     * This controls the number of times the dragged object will "snap" to the view during a drag operation, and does
     * not have to match with the time boundaries displayed in the view. E.g., the view could be displayed in 30 minute
     * increments (the default) but you could configure ddIncrement to 10, which would snap a dragged object to the
     * view at 10 minute increments.</p>
     * <p>This config currently applies while dragging to move an event, resizing an event by its handle or dragging 
     * on the view to create a new event.</p>
     */
    ddIncrement: 30,
    /**
     * @cfg {Integer} minEventDisplayMinutes
     * This is the minimum <b>display</b> height, in minutes, for events shown in the view (defaults to 30). This setting
     * ensures that events with short duration are still readable (e.g., by default any event where the start and end
     * times were the same would have 0 height). It also applies when calculating whether multiple events should be
     * displayed as overlapping. In datetime terms, an event that starts and ends at 9:00 and another event that starts
     * and ends at 9:05 do not overlap, but visually the second event would obscure the first in the view. This setting
     * provides a way to ensure that such events will still be calculated as overlapping and displayed correctly.
     */
    minEventDisplayMinutes: 30,
    /**
     * @cfg {Boolean} showHourSeparator
     * True to display a dotted line that separates each hour block in the scrolling body area at the half-hour mark 
     * (the default), false to hide it.
     */
    showHourSeparator: true,
    /**
     * @cfg {Integer} viewStartHour
     * The hour of the day at which to begin the scrolling body area's times (defaults to 0, which equals early 12am / 00:00).
     * Valid values are integers from 0 to 24, but should be less than the value of {@link viewEndHour}.
     */
    viewStartHour: 0,
    /**
     * @cfg {Integer} viewEndHour
     * The hour of the day at which to end the scrolling body area's times (defaults to 24, which equals late 12am / 00:00).
     * Valid values are integers from 0 to 24, but should be greater than the value of {@link viewStartHour}. 
     */
    viewEndHour: 24,
    /**
     * @cfg {Integer} scrollStartHour
     * The default hour of the day at which to set the body scroll position on view load (defaults to 7, which equals 7am / 07:00).
     * Note that if the body is not sufficiently overflowed to allow this positioning this setting will have no effect.
     * This setting should be equal to or greater than {@link viewStartHour}.
     */
    scrollStartHour: 7,
    /**
     * @cfg {Integer} hourHeight
     * <p>The height, in pixels, of each hour block displayed in the scrolling body area of the view (defaults to 42).</p> 
     * <br><p><b>Important note:</b> While this config can be set to any reasonable integer value, note that it is also used to 
     * calculate the ratio used when assigning event heights. By default, an hour is 60 minutes and 42 pixels high, so the
     * pixel-to-minute ratio is 42 / 60, or 0.7. This same ratio is then used when rendering events. When rendering a 
     * 30 minute event, the rendered height would be 30 minutes * 0.7 = 21 pixels (as expected).</p>
     * <p>This is important to understand when changing this value because some browsers may handle pixel rounding in
     * different ways which could lead to inconsistent visual results in some cases. If you have any problems with pixel
     * precision in how events are laid out, you might try to stick with hourHeight values that will generate discreet ratios.
     * This is easily done by simply multiplying 60 minutes by different discreet ratios (.6, .8, 1.1, etc.) to get the 
     * corresponding hourHeight pixel values (36, 48, 66, etc.) that will map back to those ratios. By contrast, if you 
     * chose an hourHeight of 50 for example, the resulting height ratio would be 50 / 60 = .833333... This will work just
     * fine, just be aware that browsers may sometimes round the resulting height values inconsistently.
     */
    hourHeight: 42,
    /**
     * @cfg {String} hideMode
     * <p>How this component should be hidden. Supported values are <tt>'visibility'</tt>
     * (css visibility), <tt>'offsets'</tt> (negative offset position) and <tt>'display'</tt>
     * (css display).</p>
     * <br><p><b>Note</b>: For calendar views the default is 'offsets' rather than the Ext JS default of
     * 'display' in order to preserve scroll position after hiding/showing a scrollable view like Day or Week.</p>
     */
    hideMode: 'offsets',
    
    // private
    initComponent : function(){
        /**
         * @cfg {String} ddCreateEventText
         * The text to display inside the drag proxy while dragging over the calendar to create a new event (defaults to 
         * 'Create event for {0}' where {0} is a date range supplied by the view)
         */
        this.ddCreateEventText = this.ddCreateEventText || Extensible.calendar.view.AbstractCalendar.prototype.ddCreateEventText;
        /**
         * @cfg {String} ddMoveEventText
         * The text to display inside the drag proxy while dragging an event to reposition it (defaults to 
         * 'Move event to {0}' where {0} is the updated event start date/time supplied by the view)
         */
        this.ddMoveEventText = this.ddMoveEventText || Extensible.calendar.view.AbstractCalendar.prototype.ddMoveEventText;
        
        // day count is only supported between 1 and 7 days
        this.dayCount = this.dayCount > 7 ? 7 : (this.dayCount < 1 ? 1 : this.dayCount);
        
        var cfg = Ext.apply({}, this.initialConfig);
        cfg.showTime = this.showTime;
        cfg.showTodayText = this.showTodayText;
        cfg.todayText = this.todayText;
        cfg.dayCount = this.dayCount;
        cfg.weekCount = 1;
        cfg.readOnly = this.readOnly;
        cfg.ddIncrement = this.ddIncrement;
        cfg.minEventDisplayMinutes = this.minEventDisplayMinutes;
        
        var header = Ext.applyIf({
            xtype: 'extensible.dayheaderview',
            id: this.id+'-hd',
            ownerCalendarPanel: this.ownerCalendarPanel
        }, cfg);
        
        var body = Ext.applyIf({
            xtype: 'extensible.daybodyview',
            enableEventResize: this.enableEventResize,
            showHourSeparator: this.showHourSeparator,
            viewStartHour: this.viewStartHour,
            viewEndHour: this.viewEndHour,
            scrollStartHour: this.scrollStartHour,
            hourHeight: this.hourHeight,
            id: this.id+'-bd',
            ownerCalendarPanel: this.ownerCalendarPanel
        }, cfg);
        
        this.items = [header, body];
        this.addCls('ext-cal-dayview ext-cal-ct');
        
        this.callParent(arguments);
    },
    
    // private
    afterRender : function(){
        this.callParent(arguments);
        
        this.header = Ext.getCmp(this.id+'-hd');
        this.body = Ext.getCmp(this.id+'-bd');
        
        this.body.on('eventsrendered', this.forceSize, this);
        this.on('resize', this.onResize, this);
    },
    
    // private
    refresh : function(){
        Extensible.log('refresh (DayView)');
        this.header.refresh();
        this.body.refresh();
    },
    
    // private
    forceSize: function(){
        // The defer call is mainly for good ol' IE, but it doesn't hurt in
        // general to make sure that the window resize is good and done first
        // so that we can properly calculate sizes.
        Ext.defer(function(){
            var ct = this.el.up('.x-panel-body'),
                hd = this.el.down('.ext-cal-day-header'),
                h = ct.getHeight() - hd.getHeight();
            
            this.el.down('.ext-cal-body-ct').setHeight(h-1);
        }, 1, this);
    },
    
    // private
    onResize : function(){
        this.forceSize();
        Ext.defer(this.refresh, Ext.isIE ? 1 : 0, this); //IE needs the defer
    },
    
    /*
     * We have to "relay" this Component method so that the hidden
     * state will be properly reflected when the views' active state changes
     */
    doHide: function(){
        this.header.doHide.apply(this, arguments);
        this.body.doHide.apply(this, arguments);
    },
    
    // private
    getViewBounds : function(){
        return this.header.getViewBounds();
    },
    
    /**
     * Returns the start date of the view, as set by {@link #setStartDate}. Note that this may not 
     * be the first date displayed in the rendered calendar -- to get the start and end dates displayed
     * to the user use {@link #getViewBounds}.
     * @return {Date} The start date
     */
    getStartDate : function(){
        return this.header.getStartDate();
    },

    /**
     * Sets the start date used to calculate the view boundaries to display. The displayed view will be the 
     * earliest and latest dates that match the view requirements and contain the date passed to this function.
     * @param {Date} dt The date used to calculate the new view boundaries
     */
    setStartDate: function(dt){
        this.header.setStartDate(dt, true);
        this.body.setStartDate(dt);
    },

    // private
    renderItems: function(){
        this.header.renderItems();
        this.body.renderItems();
    },
    
    /**
     * Returns true if the view is currently displaying today's date, else false.
     * @return {Boolean} True or false
     */
    isToday : function(){
        return this.header.isToday();
    },
    
    /**
     * Updates the view to contain the passed date
     * @param {Date} dt The date to display
     * @return {Date} The new view start date
     */
    moveTo : function(dt){
        this.header.moveTo(dt);
        return this.body.moveTo(dt, true);
    },
    
    /**
     * Updates the view to the next consecutive date(s)
     * @return {Date} The new view start date
     */
    moveNext : function(){
        this.header.moveNext();
        return this.body.moveNext(true);
    },
    
    /**
     * Updates the view to the previous consecutive date(s)
     * @return {Date} The new view start date
     */
    movePrev : function(noRefresh){
        this.header.movePrev();
        return this.body.movePrev(true);
    },

    /**
     * Shifts the view by the passed number of days relative to the currently set date
     * @param {Number} value The number of days (positive or negative) by which to shift the view
     * @return {Date} The new view start date
     */
    moveDays : function(value){
        this.header.moveDays(value);
        return this.body.moveDays(value, true);
    },
    
    /**
     * Updates the view to show today
     * @return {Date} Today's date
     */
    moveToday : function(){
        this.header.moveToday();
        return this.body.moveToday(true);
    },
    
    /**
     * Show the currently configured event editor view (by default the shared instance of 
     * {@link Extensible.calendar.form.EventWindow EventEditWindow}).
     * @param {Extensible.calendar.data.EventModel} rec The event record
     * @param {Ext.Element/HTMLNode} animateTarget The reference element that is being edited. By default this is
     * used as the target for animating the editor window opening and closing. If this method is being overridden to
     * supply a custom editor this parameter can be ignored if it does not apply.
     * @return {Extensible.calendar.view.Day} this
     */
    showEventEditor : function(rec, animateTarget){
        return Extensible.calendar.view.AbstractCalendar.prototype.showEventEditor.apply(this, arguments);
    },
    
    /**
     * Dismiss the currently configured event editor view (by default the shared instance of 
     * {@link Extensible.calendar.form.EventWindow EventEditWindow}, which will be hidden).
     * @param {String} dismissMethod (optional) The method name to call on the editor that will dismiss it 
     * (defaults to 'hide' which will be called on the default editor window)
     * @return {Extensible.calendar.view.Day} this
     */
    dismissEventEditor : function(dismissMethod){
        return Extensible.calendar.view.AbstractCalendar.prototype.dismissEventEditor.apply(this, arguments);
    }
});/**
 * @class Extensible.calendar.view.MultiDay
 * @extends Extensible.calendar.view.Day
 * <p>Displays a calendar view by day, more than one day at a time. This class does not usually need to be used directly as you can
 * use a {@link Extensible.calendar.CalendarPanel CalendarPanel} to manage multiple calendar views at once.</p>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Extensible.calendar.view.MultiDay', {
    extend: 'Extensible.calendar.view.Day',
    alias: 'widget.extensible.multidayview',
    
    /**
     * @cfg {Number} dayCount
     * The number of days to display in the view (defaults to 3).  Only values from 1 to 7 are allowed.
     */
    dayCount: 3,
    
    /**
     * @cfg {Boolean} startDayIsStatic
     * <p>By default, any configuration of a multi-day view that contains fewer than 7 days will have a rolling
     * start day. If you view the next or previous views, the dates will be adjusted as needed so that each
     * view is contiguous (e.g., if the last day in the current view is Wednesday and you go to the next view
     * it will always begin with Thursday, regardless of the value of {@link #startDay}.</p>
     * <p>If you set <tt>startDayIsStatic</tt> to <tt>true</tt>, then the view will <em>always</em> begin on
     * {@link #startDay}. For any {@link #dayCount} less than 7, days outside the startDay + dayCount range
     * will not be viewable. If a date that is not in the viewable range is set into the view it will 
     * automatically advance to the first viewable date for the current range.  This could be useful for 
     * creating custom views like a weekday-only or weekend-only view.</p>
     * <p>Some example {@link Extensible.calendar.CalendarPanel CalendarPanel} configs:</p>
     * <pre><code>
    // Weekdays only:
    showMultiDayView: true,
    multiDayViewCfg: {
        dayCount: 5,
        startDay: 1,
        startDayIsStatic: true
    }
    
    // Weekends only:
    showMultiDayView: true,
    multiDayViewCfg: {
        dayCount: 2,
        startDay: 6,
        startDayIsStatic: true
    }
     * </code></pre>
     */
    startDayIsStatic: false,
    
    // inherited docs
    moveNext : function(/*private*/reload){
        return this.moveDays(this.startDayIsStatic ? 7 : this.dayCount, reload);
    },

    // inherited docs
    movePrev : function(/*private*/reload){
        return this.moveDays(this.startDayIsStatic ? -7 : -this.dayCount, reload);
    }
});/**
 * @class Extensible.calendar.view.Week
 * @extends Extensible.calendar.view.MultiDay
 * <p>Displays a calendar view by week. This class does not usually need to be used directly as you can
 * use a {@link Extensible.calendar.CalendarPanel CalendarPanel} to manage multiple calendar views at once including
 * the week view.</p>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Extensible.calendar.view.Week', {
    extend: 'Extensible.calendar.view.MultiDay',
    alias: 'widget.extensible.weekview',
    
    /**
     * @cfg {Number} dayCount
     * The number of days to display in the view (defaults to 7)
     */
    dayCount: 7
});/**
 * @class Extensible.calendar.view.MultiWeek
 * @extends Extensible.calendar.view.Month
 * <p>Displays a calendar view by week, more than one week at a time. This class does not usually need to be used directly as you can
 * use a {@link Extensible.calendar.CalendarPanel CalendarPanel} to manage multiple calendar views at once.</p>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Extensible.calendar.view.MultiWeek', {
    extend: 'Extensible.calendar.view.Month',
    alias: 'widget.extensible.multiweekview',
    
    /**
     * @cfg {Number} weekCount
     * The number of weeks to display in the view (defaults to 2)
     */
    weekCount: 2,
    
    // inherited docs
    moveNext : function(){
        return this.moveWeeks(this.weekCount, true);
    },
    
    // inherited docs
    movePrev : function(){
        return this.moveWeeks(-this.weekCount, true);
    }
});/**
 * @class Extensible.calendar.CalendarPanel
 * @extends Ext.Panel
 * <p>This is the default container for calendar views. It supports day, week, multi-week and month views as well
 * as a built-in event edit form. The only requirement for displaying a calendar is passing in a valid
 * {@link #Ext.data.Store store} config containing records of type {@link Extensible.calendar.data.EventModel EventRecord}.</p>
 * @constructor
 * @param {Object} config The config object
 * @xtype extensible.calendarpanel
 */
Ext.define('Extensible.calendar.CalendarPanel', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.extensible.calendarpanel',
    
    requires: [
        'Ext.layout.container.Card',
        'Extensible.calendar.view.Day',
        'Extensible.calendar.view.Week',
        'Extensible.calendar.view.Month',
        'Extensible.calendar.view.MultiDay',
        'Extensible.calendar.view.MultiWeek'
    ],
    
    /**
     * @cfg {Number} activeItem
     * The 0-based index within the available views to set as the default active view (defaults to undefined). If not 
     * specified the default view will be set as the last one added to the panel. You can retrieve a reference to the
     * active {@link Extensible.calendar.view.AbstractCalendar view} at any time using the {@link #activeView} property.
     */
    /*
     * @cfg {Boolean} enableRecurrence
     * True to show the recurrence field, false to hide it (default). Note that recurrence requires
     * something on the server-side that can parse the iCal RRULE format in order to generate the
     * instances of recurring events to display on the calendar, so this field should only be enabled
     * if the server supports it.
     */
    enableRecurrence: false, // not currently implemented
    /**
     * @cfg {Boolean} showDayView
     * True to include the day view (and toolbar button), false to hide them (defaults to true).
     */
    showDayView: true,
    /**
     * @cfg {Boolean} showMultiDayView
     * True to include the multi-day view (and toolbar button), false to hide them (defaults to false).
     */
    showMultiDayView: false,
    /**
     * @cfg {Boolean} showWeekView
     * True to include the week view (and toolbar button), false to hide them (defaults to true).
     */
    showWeekView: true,
    /**
     * @cfg {Boolean} showMultiWeekView
     * True to include the multi-week view (and toolbar button), false to hide them (defaults to true).
     */
    showMultiWeekView: true,
    /**
     * @cfg {Boolean} showMonthView
     * True to include the month view (and toolbar button), false to hide them (defaults to true).
     * If all other views are hidden, the month view will show by default even if this config is false.
     */
    showMonthView: true,
    /**
     * @cfg {Boolean} showNavBar
     * True to display the calendar navigation toolbar, false to hide it (defaults to true). Note that
     * if you hide the default navigation toolbar you'll have to provide an alternate means of navigating the calendar.
     */
    showNavBar: true,
    /**
     * @cfg {String} todayText
     * Text to use for the 'Today' nav bar button.
     */
    todayText: 'Today',
    /**
     * @cfg {Boolean} showTodayText
     * True to show the value of {@link #todayText} instead of today's date in the calendar's current day box,
     * false to display the day number(defaults to true).
     */
    showTodayText: true,
    /**
     * @cfg {Boolean} showTime
     * True to display the current time next to the date in the calendar's current day box, false to not show it 
     * (defaults to true).
     */
    showTime: true,
    /**
     * @cfg {Boolean} readOnly
     * True to prevent clicks on events or calendar views from providing CRUD capabilities, false to enable CRUD 
     * (the default). This option is passed into all views managed by this CalendarPanel.
     */
    readOnly: false,
    /**
     * @cfg {Boolean} showNavToday
     * True to display the "Today" button in the calendar panel's navigation header, false to not
     * show it (defaults to true).
     */
    showNavToday: true,
    /**
     * @cfg {Boolean} showNavJump
     * True to display the "Jump to:" label in the calendar panel's navigation header, false to not
     * show it (defaults to true).
     */
    showNavJump: true,
    /**
     * @cfg {Boolean} showNavNextPrev
     * True to display the left/right arrow buttons in the calendar panel's navigation header, false to not
     * show it (defaults to true).
     */
    showNavNextPrev: true,
    /**
     * @cfg {String} jumpToText
     * Text to use for the 'Jump to:' navigation label.
     */
    jumpToText: 'Jump to:',
    /**
     * @cfg {String} goText
     * Text to use for the 'Go' navigation button.
     */
    goText: 'Go',
    /**
     * @cfg {String} dayText
     * Text to use for the 'Day' nav bar button.
     */
    dayText: 'Day',
    /**
     * @cfg {String} multiDayText
     * <p><b>Deprecated.</b> Please override {@link #getMultiDayText} instead.</p>
     * <p>Text to use for the 'X Days' nav bar button (defaults to "{0} Days" where {0} is automatically replaced by the
     * value of the {@link #multDayViewCfg}'s dayCount value if available, otherwise it uses the view default of 3).</p>
     * @deprecated
     */
    multiDayText: '{0} Days',
    /**
     * @cfg {String} weekText
     * Text to use for the 'Week' nav bar button.
     */
    weekText: 'Week',
    /**
     * @cfg {String} multiWeekText
     * <p><b>Deprecated.</b> Please override {@link #getMultiWeekText} instead.</p>
     * <p>Text to use for the 'X Weeks' nav bar button (defaults to "{0} Weeks" where {0} is automatically replaced by the
     * value of the {@link #multiWeekViewCfg}'s weekCount value if available, otherwise it uses the view default of 2).</p>
     * @deprecated
     */
    multiWeekText: '{0} Weeks',
    /**
     * @cfg {String} monthText
     * Text to use for the 'Month' nav bar button.
     */
    monthText: 'Month',
    /**
     * @cfg {Boolean} editModal
     * True to show the default event editor window modally over the entire page, false to allow user interaction with the page
     * while showing the window (the default). Note that if you replace the default editor window with some alternate component this
     * config will no longer apply. 
     */
    editModal: false,
    /**
     * @cfg {Boolean} enableEditDetails
     * True to show a link on the event edit window to allow switching to the detailed edit form (the default), false to remove the
     * link and disable detailed event editing. 
     */
    enableEditDetails: true,
    
    /**
     * @cfg {Ext.data.Store} eventStore
     * The {@link Ext.data.Store store} which is bound to this calendar and contains {@link Extensible.calendar.data.EventModel EventModels}.
     * Note that this is an alias to the default {@link #store} config (to differentiate that from the optional {@link #calendarStore}
     * config), and either can be used interchangeably.
     */
    /**
     * @cfg {Ext.data.Store} calendarStore
     * The {@link Ext.data.Store store} which is bound to this calendar and contains {@link Extensible.calendar.data.CalendarModel CalendarModelss}.
     * This is an optional store that provides multi-calendar (and multi-color) support. If available an additional field for selecting the
     * calendar in which to save an event will be shown in the edit forms. If this store is not available then all events will simply use
     * the default calendar (and color).
     */
    /**
     * @cfg {Object} viewConfig
     * A config object that will be applied to all {@link Extensible.calendar.view.AbstractCalendar views} managed by this CalendarPanel. Any
     * options on this object that do not apply to any particular view will simply be ignored.
     */
    /**
     * @cfg {Object} dayViewCfg
     * A config object that will be applied only to the {@link Extensible.calendar.view.Day DayView} managed by this CalendarPanel.
     */
    /**
     * @cfg {Object} multiDayViewCfg
     * A config object that will be applied only to the {@link Extensible.calendar.view.MultiDay MultiDayView} managed by this CalendarPanel.
     */
    /**
     * @cfg {Object} weekViewCfg
     * A config object that will be applied only to the {@link Extensible.calendar.view.Week WeekView} managed by this CalendarPanel.
     */
    /**
     * @cfg {Object} multiWeekViewCfg
     * A config object that will be applied only to the {@link Extensible.calendar.view.MultiWeek MultiWeekView} managed by this CalendarPanel.
     */
    /**
     * @cfg {Object} monthViewCfg
     * A config object that will be applied only to the {@link Extensible.calendar.view.Month MonthView} managed by this CalendarPanel.
     */
    /**
     * @cfg {Object} editViewCfg
     * A config object that will be applied only to the {@link Extensible.calendar.form.EventDetails EventEditForm} managed by this CalendarPanel.
     */
    
    /**
     * A reference to the {@link Extensible.calendar.view.AbstractCalendar view} that is currently active.
     * @type {Extensible.calendar.view.AbstractCalendar}
     * @property activeView
     */
    
    // private
    layout: {
        type: 'card',
        deferredRender: true
    },
    
    // private property
    startDate: new Date(),
    
    // private
    initComponent : function(){
        this.tbar = {
            cls: 'ext-cal-toolbar',
            border: true,
            items: []
        };
        
        this.viewCount = 0;
        
        var multiDayViewCount = (this.multiDayViewCfg && this.multiDayViewCfg.dayCount) || 3,
            multiWeekViewCount = (this.multiWeekViewCfg && this.multiWeekViewCfg.weekCount) || 2;
        
        //
        // TODO: Pull the configs for the toolbar/buttons out to the prototype for overrideability
        //
        if(this.showNavToday){
            this.tbar.items.push({
                id: this.id+'-tb-today', text: this.todayText, handler: this.onTodayClick, scope: this
            });
        }
        if(this.showNavNextPrev){
            this.tbar.items.push({id: this.id+'-tb-prev', handler: this.onPrevClick, scope: this, iconCls: 'x-tbar-page-prev'});
            this.tbar.items.push({id: this.id+'-tb-next', handler: this.onNextClick, scope: this, iconCls: 'x-tbar-page-next'});
        }
        if(this.showNavJump){
            this.tbar.items.push(this.jumpToText);
            this.tbar.items.push({id: this.id+'-tb-jump-dt', xtype: 'datefield', width: 120, showToday: false});
            this.tbar.items.push({id: this.id+'-tb-jump', text: this.goText, handler: this.onJumpClick, scope: this});
        }
        
        this.tbar.items.push('->');
        
        if(this.showDayView){
            this.tbar.items.push({
                id: this.id+'-tb-day', text: this.dayText, handler: this.onDayNavClick, scope: this, toggleGroup: this.id+'-tb-views'
            });
            this.viewCount++;
        }
        if(this.showMultiDayView){
            var text = Ext.String.format(this.getMultiDayText(multiDayViewCount), multiDayViewCount);
            this.tbar.items.push({
                id: this.id+'-tb-multiday', text: text, handler: this.onMultiDayNavClick, scope: this, toggleGroup: this.id+'-tb-views'
            });
            this.viewCount++;
        }
        if(this.showWeekView){
            this.tbar.items.push({
                id: this.id+'-tb-week', text: this.weekText, handler: this.onWeekNavClick, scope: this, toggleGroup: this.id+'-tb-views'
            });
            this.viewCount++;
        }
        if(this.showMultiWeekView){
            var text = Ext.String.format(this.getMultiWeekText(multiWeekViewCount), multiWeekViewCount);
            this.tbar.items.push({
                id: this.id+'-tb-multiweek', text: text, handler: this.onMultiWeekNavClick, scope: this, toggleGroup: this.id+'-tb-views'
            });
            this.viewCount++;
        }
        if(this.showMonthView || this.viewCount == 0){
            this.tbar.items.push({
                id: this.id+'-tb-month', text: this.monthText, handler: this.onMonthNavClick, scope: this, toggleGroup: this.id+'-tb-views'
            });
            this.viewCount++;
            this.showMonthView = true;
        }
        
        var idx = this.viewCount-1;
        this.activeItem = this.activeItem === undefined ? idx : (this.activeItem > idx ? idx : this.activeItem);
        
        if(this.showNavBar === false){
            delete this.tbar;
            this.addCls('x-calendar-nonav');
        }
        
        this.callParent(arguments);
        
        this.addEvents({
            /**
             * @event eventadd
             * Fires after a new event is added to the underlying store
             * @param {Extensible.calendar.CalendarPanel} this
             * @param {Extensible.calendar.data.EventModel} rec The new {@link Extensible.calendar.data.EventModel record} that was added
             */
            eventadd: true,
            /**
             * @event eventupdate
             * Fires after an existing event is updated
             * @param {Extensible.calendar.CalendarPanel} this
             * @param {Extensible.calendar.data.EventModel} rec The new {@link Extensible.calendar.data.EventModel record} that was updated
             */
            eventupdate: true,
            /**
             * @event beforeeventdelete
             * Fires before an event is deleted by the user. This is a cancelable event, so returning false from a handler 
             * will cancel the delete operation.
             * @param {Extensible.calendar.CalendarPanel} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} for the event that was deleted
             * @param {Ext.Element} el The target element
             */
            beforeeventdelete: true,
            /**
             * @event eventdelete
             * Fires after an event is deleted by the user.
             * @param {Extensible.calendar.CalendarPanel} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} for the event that was deleted
             * @param {Ext.Element} el The target element
             */
            eventdelete: true,
            /**
             * @event eventcancel
             * Fires after an event add/edit operation is canceled by the user and no store update took place
             * @param {Extensible.calendar.CalendarPanel} this
             * @param {Extensible.calendar.data.EventModel} rec The new {@link Extensible.calendar.data.EventModel record} that was canceled
             */
            eventcancel: true,
            /**
             * @event viewchange
             * Fires after a different calendar view is activated (but not when the event edit form is activated)
             * @param {Extensible.calendar.CalendarPanel} this
             * @param {Extensible.CalendarView} view The view being activated (any valid {@link Extensible.calendar.view.AbstractCalendar CalendarView} subclass)
             * @param {Object} info Extra information about the newly activated view. This is a plain object 
             * with following properties:<div class="mdetail-params"><ul>
             * <li><b><code>activeDate</code></b> : <div class="sub-desc">The currently-selected date</div></li>
             * <li><b><code>viewStart</code></b> : <div class="sub-desc">The first date in the new view range</div></li>
             * <li><b><code>viewEnd</code></b> : <div class="sub-desc">The last date in the new view range</div></li>
             * </ul></div>
             */
            viewchange: true,
            /**
             * @event editdetails
             * Fires when the user selects the option to edit the selected event in the detailed edit form
             * (by default, an instance of {@link Extensible.calendar.form.EventDetails}). Handling code should hide the active
             * event editor and transfer the current event record to the appropriate instance of the detailed form by showing it
             * and calling {@link Extensible.calendar.form.EventDetails#loadRecord loadRecord}.
             * @param {Extensible.calendar.CalendarPanel} this The CalendarPanel
             * @param {Extensible.calendar.view.AbstractCalendar} view The currently active {@link Extensible.calendar.view.AbstractCalendar CalendarView} subclass
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} that is currently being edited
             * @param {Ext.Element} el The target element
             */
            editdetails: true
            
            
            //
            // NOTE: CalendarPanel also relays the following events from contained views as if they originated from this:
            //
            
            /**
             * @event eventsrendered
             * Fires after events are finished rendering in the view
             * @param {Extensible.calendar.CalendarPanel} this 
             */
            /**
             * @event eventclick
             * <p>Fires after the user clicks on an event element.</p>
             * <p><strong>NOTE:</strong> This version of <code>eventclick</code> differs from the same event fired directly by
             * {@link Extensible.calendar.view.AbstractCalendar CalendarView} subclasses in that it provides a default implementation (showing
             * the default edit window) and is also cancelable (if a handler returns <code>false</code> the edit window will not be shown).
             * This event when fired from a view class is simply a notification that an event was clicked and has no default behavior.
             * @param {Extensible.calendar.CalendarPanel} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} for the event that was clicked on
             * @param {HTMLNode} el The DOM node that was clicked on
             */
            /**
             * @event rangeselect
             * Fires after the user drags on the calendar to select a range of dates/times in which to create an event
             * @param {Extensible.calendar.CalendarPanel} this
             * @param {Object} dates An object containing the start (StartDate property) and end (EndDate property) dates selected
             * @param {Function} callback A callback function that MUST be called after the event handling is complete so that
             * the view is properly cleaned up (shim elements are persisted in the view while the user is prompted to handle the
             * range selection). The callback is already created in the proper scope, so it simply needs to be executed as a standard
             * function call (e.g., callback()).
             */
            /**
             * @event eventover
             * Fires anytime the mouse is over an event element
             * @param {Extensible.calendar.CalendarPanel} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} for the event that the cursor is over
             * @param {HTMLNode} el The DOM node that is being moused over
             */
            /**
             * @event eventout
             * Fires anytime the mouse exits an event element
             * @param {Extensible.calendar.CalendarPanel} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} for the event that the cursor exited
             * @param {HTMLNode} el The DOM node that was exited
             */
            /**
             * @event beforedatechange
             * Fires before the start date of the view changes, giving you an opportunity to save state or anything else you may need
             * to do prior to the UI view changing. This is a cancelable event, so returning false from a handler will cancel both the
             * view change and the setting of the start date.
             * @param {Extensible.calendar.CalendarPanel} this
             * @param {Date} startDate The current start date of the view (as explained in {@link #getStartDate}
             * @param {Date} newStartDate The new start date that will be set when the view changes
             * @param {Date} viewStart The first displayed date in the current view
             * @param {Date} viewEnd The last displayed date in the current view
             */
            /**
             * @event dayclick
             * Fires after the user clicks within a day/week view container and not on an event element
             * @param {Extensible.calendar.CalendarPanel} this
             * @param {Date} dt The date/time that was clicked on
             * @param {Boolean} allday True if the day clicked on represents an all-day box, else false.
             * @param {Ext.Element} el The Element that was clicked on
             */
            /**
             * @event datechange
             * Fires after the start date of the view changes
             * @param {Extensible.calendar.CalendarPanel} this
             * @param {Date} startDate The start date of the view (as explained in {@link #getStartDate}
             * @param {Date} viewStart The first displayed date in the view
             * @param {Date} viewEnd The last displayed date in the view
             */
            /**
             * @event beforeeventmove
             * Fires before an event element is dragged by the user and dropped in a new position. This is a cancelable event, so 
             * returning false from a handler will cancel the move operation.
             * @param {Extensible.calendar.CalendarPanel} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} for the event that will be moved
             */
            /**
             * @event eventmove
             * Fires after an event element is dragged by the user and dropped in a new position
             * @param {Extensible.calendar.CalendarPanel} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} for the event that was moved with
             * updated start and end dates
             */
            /**
             * @event initdrag
             * Fires when a drag operation is initiated in the view
             * @param {Extensible.calendar.CalendarPanel} this
             */
            /**
             * @event dayover
             * Fires while the mouse is over a day element 
             * @param {Extensible.calendar.CalendarPanel} this
             * @param {Date} dt The date that is being moused over
             * @param {Ext.Element} el The day Element that is being moused over
             */
            /**
             * @event dayout
             * Fires when the mouse exits a day element 
             * @param {Extensible.calendar.CalendarPanel} this
             * @param {Date} dt The date that is exited
             * @param {Ext.Element} el The day Element that is exited
             */
            /**
             * @event beforeeventresize
             * Fires after the user drags the resize handle of an event to resize it, but before the resize operation is carried out.
             * This is a cancelable event, so returning false from a handler will cancel the resize operation. <strong>NOTE:</strong>
             * This event is only fired from views that support event resizing.
             * @param {Extensible.calendar.CalendarPanel} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} for the event that was resized
             * containing the updated start and end dates
             */
            /**
             * @event eventresize
             * Fires after the user drags the resize handle of an event and the resize operation is complete. <strong>NOTE:</strong>
             * This event is only fired from views that support event resizing.
             * @param {Extensible.calendar.CalendarPanel} this
             * @param {Extensible.calendar.data.EventModel} rec The {@link Extensible.calendar.data.EventModel record} for the event that was resized
             * containing the updated start and end dates
             */
        });
        
        this.addCls('x-cal-panel');
        
        if(this.eventStore){
            this.store = this.eventStore;
            delete this.eventStore;
        }
        this.setStore(this.store);
        
        var sharedViewCfg = {
            showToday: this.showToday,
            todayText: this.todayText,
            showTodayText: this.showTodayText,
            showTime: this.showTime,
            readOnly: this.readOnly,
            enableRecurrence: this.enableRecurrence,
            store: this.store,
            calendarStore: this.calendarStore,
            editModal: this.editModal,
            enableEditDetails: this.enableEditDetails,
            ownerCalendarPanel: this
        };
        
        if(this.showDayView){
            var day = Ext.apply({
                xtype: 'extensible.dayview',
                title: this.dayText
            }, sharedViewCfg);
            
            day = Ext.apply(Ext.apply(day, this.viewConfig), this.dayViewCfg);
            day.id = this.id+'-day';
            this.initEventRelay(day);
            this.add(day);
        }
        if(this.showMultiDayView){
            var mday = Ext.apply({
                xtype: 'extensible.multidayview',
                title: this.getMultiDayText(multiDayViewCount)
            }, sharedViewCfg);
            
            mday = Ext.apply(Ext.apply(mday, this.viewConfig), this.multiDayViewCfg);
            mday.id = this.id+'-multiday';
            this.initEventRelay(mday);
            this.add(mday);
        }
        if(this.showWeekView){
            var wk = Ext.applyIf({
                xtype: 'extensible.weekview',
                title: this.weekText
            }, sharedViewCfg);
            
            wk = Ext.apply(Ext.apply(wk, this.viewConfig), this.weekViewCfg);
            wk.id = this.id+'-week';
            this.initEventRelay(wk);
            this.add(wk);
        }
        if(this.showMultiWeekView){
            var mwk = Ext.applyIf({
                xtype: 'extensible.multiweekview',
                title: this.getMultiWeekText(multiWeekViewCount)
            }, sharedViewCfg);
            
            mwk = Ext.apply(Ext.apply(mwk, this.viewConfig), this.multiWeekViewCfg);
            mwk.id = this.id+'-multiweek';
            this.initEventRelay(mwk);
            this.add(mwk);
        }
        if(this.showMonthView){
            var month = Ext.applyIf({
                xtype: 'extensible.monthview',
                title: this.monthText,
                listeners: {
                    'weekclick': {
                        fn: function(vw, dt){
                            this.showWeek(dt);
                        },
                        scope: this
                    }
                }
            }, sharedViewCfg);
            
            month = Ext.apply(Ext.apply(month, this.viewConfig), this.monthViewCfg);
            month.id = this.id+'-month';
            this.initEventRelay(month);
            this.add(month);
        }

        this.add(Ext.applyIf({
            xtype: 'extensible.eventeditform',
            id: this.id+'-edit',
            calendarStore: this.calendarStore,
            enableRecurrence: this.enableRecurrence,
            listeners: {
                'eventadd':    { scope: this, fn: this.onEventAdd },
                'eventupdate': { scope: this, fn: this.onEventUpdate },
                'eventdelete': { scope: this, fn: this.onEventDelete },
                'eventcancel': { scope: this, fn: this.onEventCancel }
            }
        }, this.editViewCfg));
    },
    
    // private
    initEventRelay: function(cfg){
        cfg.listeners = cfg.listeners || {};
        cfg.listeners.afterrender = {
            fn: function(c){
                // relay view events so that app code only has to handle them in one place.
                // these events require no special handling by the calendar panel 
                this.relayEvents(c, ['eventsrendered','eventclick','dayclick','eventover','eventout','beforedatechange',
                    'datechange','rangeselect','beforeeventmove','eventmove','initdrag','dayover','dayout','beforeeventresize',
                    'eventresize','eventadd','eventupdate','beforeeventdelete','eventdelete','eventcancel']);
                
                c.on('editdetails', this.onEditDetails, this);
            },
            scope: this,
            single: true
        }
    },
    
    // private
    afterRender: function(){
        this.callParent(arguments);
        
        this.body.addCls('x-cal-body');
        this.updateNavState();
        this.setActiveView();
    },
    
    /**
     * Returns the text to use for the 'X Days' nav bar button (defaults to "{0} Days" where {0} is automatically replaced by the
     * value of the {@link #multDayViewCfg}'s dayCount value if available, otherwise it uses the view default of 3).
     */
    getMultiDayText: function(numDays){
        return this.multiDayText;
    },
    
    /**
     * Returns the text to use for the 'X Weeks' nav bar button (defaults to "{0} Weeks" where {0} is automatically replaced by the
     * value of the {@link #multiWeekViewCfg}'s weekCount value if available, otherwise it uses the view default of 2).
     */
    getMultiWeekText: function(numWeeks){
        return this.multiWeekText;
    },
    
    /**
     * Sets the event store used by the calendar to display {@link Extensible.calendar.data.EventModel events}.
     * @param {Ext.data.Store} store
     */
    setStore : function(store, initial){
        var currStore = this.store;
        
        if(!initial && currStore){
            currStore.un("write", this.onWrite, this);
        }
        if(store){
            store.on("write", this.onWrite, this);
        }
        this.store = store;
    },
    
    // private
    onStoreAdd : function(ds, recs, index){
        this.hideEditForm();
    },
    
    // private
    onStoreUpdate : function(ds, rec, operation){
        if(operation == Ext.data.Record.COMMIT){
            this.hideEditForm();
        }
    },

    // private
    onStoreRemove : function(ds, rec){
        this.hideEditForm();
    },
    
    // private
    onWrite: function(store, operation){
        var rec = operation.records[0];
        
        switch(operation.action){
            case 'create': 
                this.onStoreAdd(store, rec);
                break;
            case 'update':
                this.onStoreUpdate(store, rec, Ext.data.Record.COMMIT);
                break;
            case 'destroy':
                this.onStoreRemove(store, rec);
                break;
        }
    },
    
    // private
    onEditDetails: function(vw, rec, el){
        if(this.fireEvent('editdetails', this, vw, rec, el) !== false){
            this.showEditForm(rec);
        }
    },
    
    // private
    save: function(){
        // If the store is configured as autoSync:true the record's endEdit
        // method will have already internally caused a save to execute on
        // the store. We only need to save manually when autoSync is false,
        // otherwise we'll create duplicate transactions.
        if(!this.store.autoSync){
            this.store.sync();
        }
    },
        
    // private
    onEventAdd: function(form, rec){
        if(!rec.store){
            this.store.add(rec);
            this.save();
        }
        this.fireEvent('eventadd', this, rec);
    },
    
    // private
    onEventUpdate: function(form, rec){
        this.save();
        this.fireEvent('eventupdate', this, rec);
    },
    
    // private
    onEventDelete: function(form, rec){
        this.store.remove(rec);
        this.save();
        this.fireEvent('eventdelete', this, rec);
    },
    
    // private
    onEventCancel: function(form, rec){
        this.hideEditForm();
        this.fireEvent('eventcancel', this, rec);
    },
    
    /**
     * Shows the built-in event edit form for the passed in event record.  This method automatically
     * hides the calendar views and navigation toolbar.  To return to the calendar, call {@link #hideEditForm}.
     * @param {Extensible.calendar.data.EventModel} record The event record to edit
     * @return {Extensible.calendar.CalendarPanel} this
     */
    showEditForm: function(rec){
        this.preEditView = this.layout.getActiveItem().id;
        this.setActiveView(this.id+'-edit');
        this.layout.getActiveItem().loadRecord(rec);
        return this;
    },
    
    /**
     * Hides the built-in event edit form and returns to the previous calendar view. If the edit form is
     * not currently visible this method has no effect.
     * @return {Extensible.calendar.CalendarPanel} this
     */
    hideEditForm: function(){
        if(this.preEditView){
            this.setActiveView(this.preEditView);
            delete this.preEditView;
        }
        return this;
    },
    
    /**
     * Set the active view, optionally specifying a new start date.
     * @param {String} id The id of the view to activate
     * @param {Date} startDate (optional) The new view start date (defaults to the current start date)
     */
    setActiveView: function(id, startDate){
        var me = this,
            layout = me.layout,
            editViewId = me.id + '-edit',
            toolbar;
        
        if (startDate) {
            me.startDate = startDate;
        }
        
        // Make sure we're actually changing views
        if (id !== layout.getActiveItem().id) {
            // Show/hide the toolbar first so that the layout will calculate the correct item size
            toolbar = me.getDockedItems('toolbar')[0];
            if (toolbar) {
                toolbar[id === editViewId ? 'hide' : 'show']();
            }
            
            // Activate the new view and refresh the layout
            layout.setActiveItem(id || me.activeItem);
            me.doComponentLayout();
            me.activeView = layout.getActiveItem();
            
            if (id !== editViewId) {
                if (id && id !== me.preEditView) {
                    // We're changing to a different view, so the view dates are likely different.
                    // Re-set the start date so that the view range will be updated if needed.
                    // If id is undefined, it means this is the initial pass after render so we can
                    // skip this (as we don't want to cause a duplicate forced reload).
                    layout.activeItem.setStartDate(me.startDate, true);
                }
                // Switching to a view that's not the edit view (i.e., the nav bar will be visible)
                // so update the nav bar's selected view button
                me.updateNavState();
            }
            // Notify any listeners that the view changed
            me.fireViewChange();
        }
    },
    
    // private
    fireViewChange: function() {
        if (this.layout && this.layout.getActiveItem) {
            var view = this.layout.getActiveItem(),
                cloneDt = Ext.Date.clone;
                
            if (view) {
                if (view.getViewBounds) {
                    var vb = view.getViewBounds(),
                        info = {
                            activeDate: cloneDt(view.getStartDate()),
                            viewStart: cloneDt(vb.start),
                            viewEnd: cloneDt(vb.end)
                        };
                };
                if (view.dismissEventEditor){
                    view.dismissEventEditor();
                }
                this.fireEvent('viewchange', this, view, info);
            }
        }
    },
    
    // private
    updateNavState: function(){
        var me = this,
            activeItem = me.layout.activeItem;
        
        if (activeItem && me.showNavBar !== false) {
            var suffix = activeItem.id.split(me.id + '-')[1],
                btn = Ext.getCmp(me.id + '-tb-' + suffix);
            
            if (me.showNavToday) {
                Ext.getCmp(me.id + '-tb-today').setDisabled(activeItem.isToday());
            }
            btn.toggle(true);
        }
    },

    /**
     * Sets the start date for the currently-active calendar view.
     * @param {Date} dt The new start date
     * @return {Extensible.calendar.CalendarPanel} this
     */
    setStartDate: function(dt){
        Extensible.log('setStartDate (CalendarPanel');
        this.startDate = dt;
        this.layout.activeItem.setStartDate(dt, true);
        this.updateNavState();
        this.fireViewChange();
        return this;
    },
        
    // private
    showWeek: function(dt){
        this.setActiveView(this.id+'-week', dt);
    },
    
    // private
    onTodayClick: function(){
        this.startDate = this.layout.activeItem.moveToday(true);
        this.updateNavState();
        this.fireViewChange();
    },
    
    // private
    onJumpClick: function(){
        var dt = Ext.getCmp(this.id+'-tb-jump-dt').getValue();
        if(dt !== ''){
            this.startDate = this.layout.activeItem.moveTo(dt, true);
            this.updateNavState();
            // TODO: check that view actually changed:
            this.fireViewChange();
        }
    },
    
    // private
    onPrevClick: function(){
        this.startDate = this.layout.activeItem.movePrev(true);
        this.updateNavState();
        this.fireViewChange();
    },
    
    // private
    onNextClick: function(){
        this.startDate = this.layout.activeItem.moveNext(true);
        this.updateNavState();
        this.fireViewChange();
    },
    
    // private
    onDayNavClick: function(){
        this.setActiveView(this.id+'-day');
    },
    
    // private
    onMultiDayNavClick: function(){
        this.setActiveView(this.id+'-multiday');
    },
    
    // private
    onWeekNavClick: function(){
        this.setActiveView(this.id+'-week');
    },
    
    // private
    onMultiWeekNavClick: function(){
        this.setActiveView(this.id+'-multiweek');
    },
    
    // private
    onMonthNavClick: function(){
        this.setActiveView(this.id+'-month');
    },
    
    /**
     * Return the calendar view that is currently active, which will be a subclass of
     * {@link Extensible.calendar.view.AbstractCalendar CalendarView}.
     * @return {Extensible.calendar.view.AbstractCalendar} The active view
     */
    getActiveView: function(){
        return this.layout.activeItem;
    }
});