/*!
 * Extensible 1.6.0-b1
 * Copyright(c) 2010-2012 Extensible, LLC
 * licensing@ext.ensible.com
 * http://ext.ensible.com
 */
Ext.Loader.setConfig({
    enabled: true,
    disableCaching: false,
    paths: {
        "Extensible": "../../src",
        "Extensible.example": ".."
    }
});

Ext.onReady(function() {

    
    initComponent: function() 
   {    
      evtStore = Ext.create('Extensible.calendar.data.EventStore', 
       {
         autoLoad: false,
         storeId: 'evtStore',
         proxy: {
            type: 'memory',
         }
       });      
      
      // build initial dummy data and add to the store
      var rec = new Extensible.calendar.data.EventModel(
      {
         EventId: 11,
         StartDate: '2012-02-23 12:00:00',
         EndDate: '2012-02-23 12:30:00',
         Title: 'dummy event'
      });
      evtStore.add(rec);

      // create calendar
        var cp = Ext.create('Extensible.calendar.CalendarPanel', 
      {
         id: 'cal',
         eventStore:    evtStore,
         padding:       '10',
         showNavBar:      false,
              showDayView:   true,
              showWeekView:    true,
              showMonthView:   true        
      });
      
      //   add the calendar to the parent component
      Ext.getCmp('calview-content').add(cp);
      
      this.callParent(arguments);
   }
});