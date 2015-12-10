Ext.define('App.controller.KeyCommands', {
    extend: 'Ext.app.Controller',
	refs: [
        {
            ref:'viewport',
            selector:'viewport'
        }
	],

	enabled: false,

	init: function() {
		this.enableKeyCommands();
	},

	enableKeyCommands: function(){
		if(this.enabled) return;
		Ext.getBody().on('keyup', this.onKeyUp, this);
		this.enabled = true;
	},

	disableKeyCommands: function(){
		Ext.getBody().un('keyup', this.onKeyUp, this);
		this.enabled = false;
	},


	onKeyUp: function(e, t, eOpts){

		if(e.getKey() == e.ALT || e.getKey() == e.CTRL || e.getKey() == e.SHIFT){
			return;
		}

		if(e.altKey || e.ctrlKey || e.shiftKey){
			var event = 'KEY-';
			if(e.altKey) event += 'ALT-';
			if(e.ctrlKey) event += 'CTRL-';
			if(e.shiftKey) event += 'SHIFT-';
			event += String.fromCharCode(e.getCharCode());
			app.fireEvent(event, e, t);
		}

		if(e.altKey && e.ctrlKey && e.shiftKey){
			var action = '';

			if(e.getKey() == e.A){
				action = 'allergies';
			}else if(e.getKey() == e.I){
				action = 'immunization';
			}else if(e.getKey() == e.M){
				action = 'medications';
			}else if(e.getKey() == e.P){
				action = 'activeproblems';
			}else if(e.getKey() == e.R){
				action = 'laboratories';
			}else if(e.getKey() == e.C){
				action = 'social';

			// close window
			}else if(e.getKey() == e.W){
				var cmp = Ext.getCmp(e.getTarget(null, null, true).id);

				if(cmp.xtype == 'window'){
					cmp.close();
				}else{
					var win = cmp.up('window');
					if(win) win.close();
				}
				return;
			}

			if(action != ''){
				app.onMedicalWin(action);
			}

		}
	}



});