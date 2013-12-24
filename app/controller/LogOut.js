Ext.define('App.controller.LogOut', {
    extend: 'Ext.app.Controller',
	requires:[
		'App.ux.ActivityMonitor'
	],
	init: function() {
		var me = this;

		/**
		 * in seconds - interval to check for
		 * mouse and keyboard activity
		 */
		me.activityMonitorInterval = 60;
		/**
		 * in minutes - Maximum time application can
		 * be inactive (no mouse or keyboard input)
		 */
		me.activityMonitorMaxInactive = 20;

		me.cron = me.getController('Cron');

		me.control({
			'treepanel[action=mainNav]':{
				beforerender: me.onNavigationBeforeRender
			}
		});

	},

	onNavigationBeforeRender:function(treepanel){
		treepanel.getStore().on('load', function(){
			this.ActivityMonitor(true);
		}, this);
	},

	ActivityMonitor:function(start){
		var me = this;

		if(start){
			App.ux.ActivityMonitor.init({
				interval: me.activityMonitorInterval * 1000,
				maxInactive: (1000 * 60 * me.activityMonitorMaxInactive),
				verbose: true,
				isInactive: function(){
					me.startAutoLogout();
				}
			});
			me.cron.start();
			App.ux.ActivityMonitor.start();
		}else{
			me.cron.stop();
			App.ux.ActivityMonitor.stop();
		}
	},

	cancelAutoLogout: function(){
		var me = this;
		me.el.unmask();
		me.LogoutTask.stop(me.LogoutTaskTimer);
		me.logoutWarinigWindow.destroy();
		delete me.logoutWarinigWindow;
		App.ux.ActivityMonitor.start();
	},

	startAutoLogout: function(){
		var me = this;

		me.logoutWarinigWindow = Ext.create('Ext.Container', {
			floating: true,
			cls: 'logout-warning-window',
			html: 'Logging Out in...',
			seconds: 10
		}).show();

		app.el.mask();

		if(!me.LogoutTask)
			me.LogoutTask = new Ext.util.TaskRunner();
		if(!me.LogoutTaskTimer){
			me.LogoutTaskTimer = me.LogoutTask.start({
				scope: me,
				run: me.logoutCounter,
				interval: 1000
			});
		}else{
			me.LogoutTask.start(me.LogoutTaskTimer);
		}
	},

	logoutCounter: function(){
		var me = this, sec = me.logoutWarinigWindow.seconds - 1;
		if(sec <= 0){
			me.logoutWarinigWindow.update('Logging Out... Bye! Bye!');
			me.appLogout(true);
		}else{
			me.logoutWarinigWindow.update('Logging Out in ' + sec + 'sec');
			me.logoutWarinigWindow.seconds = sec;
			say('Logging Out in ' + sec + 'sec');
		}
	},

	appLogout: function(auto){
		var me = this;

		if(auto === true){
			me.ActivityMonitor(false);
			authProcedures.unAuth(function(){
				window.location = './'
			});
		}else{
			Ext.Msg.show({
				title: i18n('please_confirm') + '...',
				msg: i18n('are_you_sure_to_quit') + ' GaiaEHR?',
				icon: Ext.MessageBox.QUESTION,
				buttons: Ext.Msg.YESNO,
				fn: function(btn){
					if(btn == 'yes'){
						authProcedures.unAuth(function(){
							me.ActivityMonitor(false);
							window.location = './'
						});
					}
				}
			});
		}
	}
});