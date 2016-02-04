Ext.define('App.controller.DualScreen', {
    extend: 'Ext.app.Controller',
	requires:[

	],
	refs: [
        {
            ref:'DualViewport',
            selector:'#dualViewport'
        },
        {
            ref:'Header',
            selector:'#RenderPanel-header'
        },
        {
            ref:'TabPanel',
            selector:'#dualViewport tabpanel'
        }
	],

	isDual: false,
	appMask: null,
	init: function() {
		var me = this;

		me._loggedout = false;
		me._enable = true;
		me._screen = null;

		me.control({
			'#dualViewport':{
				render:me.onDualViewportRender,
				beforerender:me.onDualViewportBeforeRender
			}
		});

	},

	startDual:function(){
		var me = this;
		me.enable();
		if(me._screen == null || me._screen.closed){
			me._screen = window.open('./?dual=true','_target','fullscreen=yes,menubar=no',true);
		}
	},

	stopDual:function(){
		this.disable();
		this._screen.close();
		this._screen = null;
	},

	enable:function(){
		this._enable = true;
	},

	disable:function(){
		this._enable = false;
	},

	isEnabled:function(){
		return this._enable;
	},

	onDualViewportBeforeRender:function(){
		this.isDual = true;
		window.app = window.opener.app;
		app.on('patientset', this.onPatientSet, this);
		app.on('patientunset', this.onPatientUnset, this);
	},

	onDualViewportRender:function(){
		Ext.get('mainapp-loading').remove();
		Ext.get('mainapp-loading-mask').fadeOut({
			remove: true
		});
		this.onPatientUnset(false);
		this.initHandShakeTask();
	},

	onPatientSet:function(){
        var title,
            store;

		if(!this.isDual || this._loggedout) return;
		title = app.patient.name + ' - #' + app.patient.pid + ' - ' + app.patient.age.str,
			store = this.getActiveStore();

		this.unmask();
		this.getHeader().update(title);
		store.clearFilter(true);
		store.filter([
			{
				property: 'pid',
				value: app.patient.pid
			}
		]);
	},

	onPatientUnset:function(filter){
        var store;

		if(!this.isDual || this._loggedout) return;
		store = this.getActiveStore();

		this.mask(_('no_patient_selected'));
		this.getHeader().update('');

		if(filter === false) return;
		store.clearFilter(true);
		store.filter([
			{
				property: 'pid',
				value: app.patient.pid
			}
		]);
	},

	getActiveStore:function(){
		var panel = this.getTabPanel().getActiveTab();

		if(panel.getStore){
			return panel.getStore();
		}if(panel.xtype == 'patientdocumentspanel' ||
			panel.xtype == 'patientimmunizationspanel' ||
			panel.xtype == 'patientmedicationspanel' ||
			panel.xtype == 'patientimmunizationspanel'){
			return panel.down('grid').getStore();
		}
	},

	mask:function(msg){
		var me = this;
		if(me.appMask == null){
			me.appMask = new Ext.LoadMask(me.getDualViewport(), {
				msg : '<img height="86" width="254" src="resources/images/gaiaehr-med-dark.png"><p>' + msg + '</p>',
				maskCls: 'dualAppMask',
				cls: 'dualAppMaskMsg',
				autoShow: true
			});
		}else{
			me.appMask.show();
			me.appMask.msgEl.query('p')[0].innerHTML = msg;
		}
	},

	unmask:function(){
		if(this.appMask) this.appMask.hide();
	},

	initHandShakeTask:function(){
		var me = this,
			task = {
			run: function(){
				if(window.opener == null) window.close();
				if(!window.opener.app.logged && !me._loggedout){
					me.mask(_('logged_out'));
					me._loggedout = true;
				}
			},
			interval: 1000,
			scope: me
		};
		Ext.TaskManager.start(task);
	}

});
