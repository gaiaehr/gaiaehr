(function(window, document, Darkroom){
	'use strict';

	Darkroom.plugins['save'] = Darkroom.Plugin.extend({
		defaults: {
			callback: function(){
				this.darkroom.selfDestroy();

				if(typeof(this.darkroom.options.save) == 'object' && this.darkroom.options.save.callback){
					this.darkroom.options.save.callback.bind(this);
				}
			}
		},

		initialize: function InitDarkroomSavePlugin(){

			if(this.darkroom.options.save === false) return;

			var buttonGroup = this.darkroom.toolbar.createButtonGroup();

			this.destroyButton = buttonGroup.createButton({
				image: 'save'
			});

			this.destroyButton.addEventListener('click', this.options.callback.bind(this));
		}
	});
})(window, document, Darkroom);
