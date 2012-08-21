/*!
 * Extensible 1.6.0-b1
 * Copyright(c) 2010-2012 Extensible, LLC
 * licensing@ext.ensible.com
 * http://ext.ensible.com
 */
// Ext.Loader.setConfig({
    // enabled: true,
    // disableCaching: true,
    // paths: {
        // "Extensible": "../../../src",
        // "Extensible.example": "../.."
    // }
// });

Ext.require([
    'Gnt.panel.Gantt',
    'Gnt.column.StartDate',
    'Gnt.column.EndDate',
    'Gnt.column.Duration',
    'Sch.plugin.TreeCellEditing',
    'Gnt.widget.Calendar'
]);

Ext.define('App.Gantt', {

    // Initialize application
    init: function (serverCfg) {
        Ext.QuickTips.init();

        var calendar        = new Gnt.data.Calendar({
            autoLoad : true,
            proxy : {
                type : 'ajax',
                //api : { read : 'holidaydata.js' },
                url: 'holidaydata.js',
                reader : { type : 'json' }
            },
            listeners : {
                //beforesync : function() { return false; }
            }
        });      
        
        var taskStore = Ext.create("Gnt.data.TaskStore", {
            autoLoad        : true,
            
            calendar        : calendar,
            
            proxy : {
                type    : 'ajax',
                method  : 'GET',
                url     : 'tasks.xml',
                reader  : {
                    type        : 'xml',
                    // records will have a 'Task' tag
                    record      : "Task",
                    root        : "Tasks",
                    idProperty  : "Id"
                }
            },
            sorters: [{
                property        : 'leaf',
                direction       : 'ASC'
            }]
        });
        

        var dependencyStore = Ext.create("Ext.data.Store", {
            autoLoad    : true,
            model       : 'Gnt.model.Dependency',
            proxy       : {
                type    : 'ajax',
                url     : 'dependencies.xml',
                method  : 'GET',
                reader  : {
                    type        : 'xml',
                    root        : 'Links',
                    record      : 'Link' // records will have a 'Link' tag
                }
            }
        });


        var colSlider = Ext.create("Ext.slider.Single", {
            width       : 120,
            value       : Sch.preset.Manager.getPreset('weekAndDayLetter').timeColumnWidth,
            minValue    : 100,
            maxValue    : 240,
            increment   : 10
        });
     
        var startDate   = new Date(2010, 0, 11);
        var endDate     = Sch.util.Date.add(new Date(2010, 0, 11), Sch.util.Date.WEEK, 10);

        var gantt = Ext.create('Gnt.panel.Gantt', {
            height      : 450,
            width       : 1000,
            
            renderTo    : Ext.getBody(),
            
            leftLabelField  : 'Name',

//            resizeHandles               : 'none',
//            showTodayLine               : true,
//            snapToIncrement             : true,
            loadMask                    : true,
            enableProgressBarResize     : true,
            enableDependencyDragDrop    : false,
            highlightWeekends           : true,
//            weekendsAreWorkdays                : false,  // uncomment to disable the skipping weekends/holidays functionality completely (empty calendar)
                                                    // (for compatibility with 1.x)
            
//            skipWeekendsDuringDragDrop  : false,  // uncomment to disable the skipping weekends/holidays functionality during d&d operations

            viewPreset      : 'weekAndDayLetter',
            
            startDate       : startDate,
            endDate         : endDate,
            
            tooltipTpl: new Ext.XTemplate(
                '<ul class="taskTip">',
                    '<li><strong>Task:</strong>{Name}</li>',
                    '<li><strong>Start:</strong>{[Ext.Date.format(values.StartDate, "y-m-d")]}</li>',
                    '<li><strong>Duration:</strong> {[parseFloat(Ext.Number.toFixed(values.Duration, 1))]} {DurationUnit}</li>',
                    '<li><strong>Progress:</strong>{PercentDone}%</li>',
                '</ul>'
            ).compile(),


            // Setup your static columns
            columns         : [
                {
                    xtype       : 'treecolumn',
                    header      : 'Tasks',
                    sortable    : true,
                    dataIndex   : 'Name',
                    width       : 180
                },
                {
                    xtype       : 'startdatecolumn',
                    width       : 80
                },
                {
                    xtype       : 'enddatecolumn',
                    width       : 80
                },
                {
                    xtype       : 'durationcolumn',
                    width       : 70
                },
                {
                    header      : '% Done',
                    sortable    : true,
                    dataIndex   : 'PercentDone',
                    width       : 50,
                    align       : 'center'
                }
            ],

            taskStore           : taskStore,
            dependencyStore     : dependencyStore,
            
            plugins             : [
                Ext.create('Sch.plugin.TreeCellEditing', {
                    clicksToEdit: 1
                })            
            ],
            tbar                : [
                {
                    text            : 'See calendar',
                    iconCls         : 'gnt-date',
                    
                    menu            : [
                        {
                            xtype           : 'ganttcalendar',
                            
                            calendar        : calendar,
                            startDate       : startDate,
                            endDate         : endDate,
                            
                            showToday       : false
                        }
                    ]
                },
                '->',
                {
                    xtype           : 'label',
                    text            : 'Column Width'
                },
                ' ',
                colSlider
            ]
        });

        colSlider.on({
            
            change: function (slider, value) {
                gantt.setTimeColumnWidth(value, true);
            },
            
            changecomplete: function (slider, value) {
                gantt.setTimeColumnWidth(value);
            }
        });

        this.initCalendar(calendar, startDate);
    },

    // This initializes the Ext Calendar component (www.ext-calendar.com)
    initCalendar : function(eventStore, date) {
        Extensible.calendar.data.EventModel.override({
            idProperty : 'Id',

            set : function(field, val) {
                if (arguments.length === 2 && field === 'Date' && val) {
                    this.data.Id = (+new Date);
                }
                this.callOverridden(arguments);
            }
        });

        var M = Extensible.calendar.data.EventMappings;

        M.EventId =   {name:'Id' };
        M.StartDate = {name:'Date', mapping:'Date', type: 'date'};
        M.EndDate   = {name:'Date', mapping:'Date', type: 'date'};
        M.Title     = {name:'Name', mapping:'Name', type: 'string'};
        
        Extensible.calendar.data.EventModel.reconfigure();

        var cp = new Extensible.calendar.CalendarPanel({
            renderTo: document.body,
            width: 1000,
            height: 500,
            cls : 'extcal',
            title: 'Holiday Calendar',
            store: eventStore
        });

        cp.setStartDate(date);
    }
});

Ext.onReady(function() {
    Ext.create('App.Gantt').init();
});
