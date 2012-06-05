/**
 * facilities.ejs.php
 * Description: Patient File ScreenS
 * v0.0.3
 *
 * Author: Ernesto J Rodriguez
 * Modified: n/a
 *
 * GaiaEHR (Electronic Health Records) 2011
 *
 * @namespace Calendar.getCalendars
 * @namespace Calendar.getEvents
 * @namespace Calendar.addEvent
 * @namespace Calendar.updateEvent
 * @namespace Calendar.deleteEvent
 *
 */
Ext.define('App.view.calendar.Calendar', {
	extend     : 'App.classes.RenderPanel',
	id         : 'panelCalendar',
	pageTitle  : 'Calendar Events',
	constructor: function() {

		this.callParent(arguments);

		this.calendarStore = Ext.create('Extensible.calendar.data.MemoryCalendarStore', {
			autoLoad: true,
			proxy   : {
				type   : 'direct',
				api    : {
					read: Calendar.getCalendars
				},
				noCache: false,

				reader: {
					type: 'json',
					root: 'calendars'
				}
			}
		});

		this.eventStore = Ext.create('Extensible.calendar.data.EventStore', {
			autoLoad : true,
			proxy    : {
				type   : 'direct',
				api    : {
					read   : Calendar.getEvents,
					create : Calendar.addEvent,
					update : Calendar.updateEvent,
					destroy: Calendar.deleteEvent
				},
				noCache: false,

				reader: {
					type: 'json',
					root: 'data'
				},

				writer: {
					type        : 'json',
					nameProperty: 'mapping'
				},

				listeners: {
					exception: function(proxy, response) {
						var msg = response.message ? response.message : Ext.decode(response.responseText).message;
						// ideally an app would provide a less intrusive message display
						Ext.Msg.alert('Server Error', msg);
					}
				}
			},

			// It's easy to provide generic CRUD messaging without having to handle events on every individual view.
			// Note that while the store provides individual add, update and remove events, those fire BEFORE the
			// remote transaction returns from the server -- they only signify that records were added to the store,
			// NOT that your changes were actually persisted correctly in the back end. The 'write' event is the best
			// option for generically messaging after CRUD persistence has succeeded.
			listeners: {
				scope  : this,
				'write': function(store, operation) {
					var title = Ext.value(operation.records[0].data[Extensible.calendar.data.EventMappings.Title.name], '(No title)');
					if(operation.action == 'create') {
						this.msg('Add', 'Added "' + title + '"');
					} else if(operation.action == 'update') {
						this.msg('Update', 'Updated "' + title + '"');
					} else if(operation.action == 'destroy') {
						this.msg('Delete', 'Deleted "' + title + '"');
					}
				}
			}
		});

		this.pageBody = [
			{
				xtype : 'panel',
				layout: 'border',
				border: true,
				items : [
					{
						id    : 'app-west',
						region: 'west',
						width : 179,
						border: false,
						items : [
							{
								xtype    : 'datepicker',
								id       : 'app-nav-picker',
								cls      : 'ext-cal-nav-picker',
								listeners: {
									'select': {
										fn   : function(dp, dt) {
                                            say(dt);
											Ext.getCmp('app-calendar').setStartDate(dt);
										},
										scope: this
									}
								}
							},
							{
								xtype      : 'extensible.calendarlist',
								id         : 'app-calendarlist',
								store      : this.calendarStore,
								collapsible: true,
								border     : false,
								width      : 178
							}
						]
					},
					{
						xtype        : 'extensible.calendarpanel',
						eventStore   : this.eventStore,
						calendarStore: this.calendarStore,
						border       : false,
						id           : 'app-calendar',
						region       : 'center',
						activeItem   : 3, // month view

						// Any generic view options that should be applied to all sub views:
						viewConfig   : {
							enableFx: false,
							//ddIncrement           : 10, //only applies to DayView and subclasses, but convenient to put it here
							viewStartHour         : 8,
							viewEndHour           : 16,
							minEventDisplayMinutes: 15,
							showTime: false
						},

						// View options specific to a certain view (if the same options exist in viewConfig
						// they will be overridden by the view-specific config):
						monthViewCfg : {
							showHeader     : true,
							showWeekLinks  : true,
							showWeekNumbers: true
						},

						multiWeekViewCfg : {
							//weekCount: 3
						},

						// Some optional CalendarPanel configs to experiment with:
						//readOnly          : true,
						//showDayView       : false,
						//showMultiDayView  : true,
						//showWeekView      : false,
						//showMultiWeekView : false,
						//showMonthView     : false,
						//showNavBar        : false,
						//showTodayText     : false,
						//showTime          : false,
						//editModal         : true,
						enableEditDetails: false,
						//title             : 'My Calendar',

						listeners: {
							'eventclick' : {
								fn   : function() {
									this.clearMsg();
								},
								scope: this
							},
							'eventover'  : function() {
								//console.log('Entered evt rec='+rec.data[Extensible.calendar.data.EventMappings.Title.name]', view='+ vw.id +', el='+el.id);
							},
							'eventout'   : function() {
								//console.log('Leaving evt rec='+rec.data[Extensible.calendar.data.EventMappings.Title.name]+', view='+ vw.id +', el='+el.id);
							},
							'eventadd'   : {
								fn   : function(cp, rec) {
									this.showMsg('Event ' + rec.data[Extensible.calendar.data.EventMappings.Title.name] + ' was added');
								},
								scope: this
							},
							'eventupdate': {
								fn   : function(cp, rec) {
									this.showMsg('Event ' + rec.data[Extensible.calendar.data.EventMappings.Title.name] + ' was updated');
								},
								scope: this
							},
							'eventcancel': {
								fn   : function() {
									// edit canceled
								},
								scope: this
							},
							'viewchange' : {
								fn   : function(p, vw, dateInfo) {
									if(dateInfo) {
										//this.updateTitle(dateInfo.viewStart, dateInfo.viewEnd);
									}
								},
								scope: this
							},
							'dayclick'   : {
								fn   : function() {
									this.clearMsg();
								},
								scope: this
							},
							'rangeselect': {
								fn   : function() {
									this.clearMsg();
								},
								scope: this
							},
							'eventmove'  : {
								fn   : function(vw, rec) {
									var mappings = Extensible.calendar.data.EventMappings,
										time = rec.data[mappings.IsAllDay.name] ? '' : ' \\a\\t g:i a';

									rec.commit();

									this.showMsg('Event ' + rec.data[mappings.Title.name] + ' was moved to ' +
										Ext.Date.format(rec.data[mappings.StartDate.name], ('F jS' + time)));
								},
								scope: this
							},
							'eventresize': {
								fn   : function(vw, rec) {
									rec.commit();
									this.showMsg('Event ' + rec.data[Extensible.calendar.data.EventMappings.Title.name] + ' was updated');
								},
								scope: this
							},
							'eventdelete': {
								fn   : function(win, rec) {
									this.eventStore.remove(rec);
									this.showMsg('Event ' + rec.data[Extensible.calendar.data.EventMappings.Title.name] + ' was deleted');
								},
								scope: this
							},
							'initdrag'   : {
								fn   : function() {
									// do something when drag starts
								},
								scope: this
							}
						}
					}
				]
			}
		];

		this.callParent(arguments);

	},
	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */

	onActive   : function(callback) {
		this.calendarStore.load();
		//Ext.getCmp('app-calendar').onActiveCard();
		Ext.Function.defer(function() {
			Ext.getCmp('app-calendarlist').doLayout();
		}, 500, this);
		callback(true);
	},

	showMsg: function(msg) {
		Ext.fly('app-msg').update(msg).removeCls('x-hidden');
	},

	clearMsg: function() {
		Ext.fly('app-msg').update('').addCls('x-hidden');
	}
});