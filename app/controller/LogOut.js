/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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
		me.activityMonitorInterval = 10;
		/**
		 * in minutes - Maximum time application can
		 * be inactive (no mouse or keyboard input)
		 */
		me.activityMonitorMaxInactive = eval(g('timeout'));

		me.cron = me.getController('Cron');

		me.control({
			'treepanel[action=mainNav]':{
				beforerender: me.onNavigationBeforeRender
			},
			'menuitem[action=logout]':{
				click: me.appLogout
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
				verbose: false,
				controller: me,
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
		app.el.unmask();
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
		var me = this,
			nav = me.getController('Navigation');

		if(auto === true){
			me.ActivityMonitor(false);
			if(app.patient.pid) Patient.unsetPatient(app.patient.pid);
			authProcedures.unAuth(function(){
				nav.navigateTo('App.view.login.Login', null, true);
				window.location.reload();

			});
		}else{
			Ext.Msg.show({
				title: _('please_confirm') + '...',
				msg: _('are_you_sure_to_quit') + ' GaiaEHR?',
				icon: Ext.MessageBox.QUESTION,
				buttons: Ext.Msg.YESNO,
				fn: function(btn){
					if(btn == 'yes'){
						if(app.patient.pid) Patient.unsetPatient(app.patient.pid);
						authProcedures.unAuth(function(){
							me.ActivityMonitor(false);
							nav.navigateTo('App.view.login.Login', null, true);
							window.location.reload();
						});
					}
				}
			});
		}
	}
});