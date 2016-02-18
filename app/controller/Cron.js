Ext.define('App.controller.Cron', {
    extend: 'Ext.app.Controller',

    // in seconds - interval to run me.cronTask (check PHP session, refresh Patient Pool Areas, and PHP Cron Job)
	cronTaskInterval: 10,

	fns:[
		'app.getPatientsInPoolArea()',
		'me.checkSession()',
		//'CronJob.run()'
	],

	init: function() {
		var me = this,
            i;

		/**
		 * TaskScheduler
		 */
		me.cronTask = {
			scope: me,
			run: function(){
				/**
				 * loop for functions
				 */
				for(i=0; i < me.fns.length; i++){
					eval(me.fns[i]);
				}
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


	/**
	 *This will add the function to the functions array
	 *
	 * @param {string} fn example 'app.getPatientsInPoolArea()'
	 */
	addCronFn:function(fn){
		this.fns.push(fn);
	},

	/**
	 * This will remove the function from the functions array
	 *
	 * @param {string} fn example 'app.getPatientsInPoolArea()'
	 */
	removeCronFn:function(fn){
		Ext.Array.remove(this.fns, fn);
	},

	checkSession: function(){
		authProcedures.ckAuth(function(provider, response){
			if(!response.result.authorized){
				window.location.reload();
			}
		});
	}

});
