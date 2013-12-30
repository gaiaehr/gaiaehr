Ext.define('App.controller.DualScreen', {
    extend: 'Ext.app.Controller',
	requires:[

	],
	refs: [
        {
            ref:'viewport',
            selector:'viewport'
        }
	],

	init: function() {
		var me = this;

		me._enable = true;
		me._screen = null;

		me.control({

		});

	},

	startDual:function(){
		this.enable();
		if(this._screen == null || this._screen.closed){
			this._screen = window.open('./?dual=true','_blank','fullscreen=yes,menubar=no',true);
			this._screen.app = app;
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
	}
});