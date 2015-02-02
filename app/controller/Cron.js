Ext.define('App.controller.Cron', {
    extend: 'Ext.app.Controller',

	cronTaskInterval: 10, // in seconds - interval to run me.cronTask (check PHP session, refresh Patient Pool Areas, and PHP Cron Job)

	init: function() {
		var me = this;

		/**
		 * TaskScheduler
		 * This will run all the procedures inside the checkSession
		 */
		me.cronTask = {
			scope: me,
			run: function(){
				me.checkSession();
				app.getPatientsInPoolArea();
				CronJob.run();
			},
			interval: me.cronTaskInterval * 1000
		};

	},

	start:function(){
		Ext.TaskManager.start(this.cronTask);
	},

	stop:function(){
		Ext.TaskManager.stop(this.cronTask);
	},

	checkSession: function(){
		authProcedures.ckAuth(function(provider, response){
			if(!response.result.authorized){
				window.location = './';
			}
		});
	}



});