(function(window, document, Darkroom){
	'use strict';

	if(this.darkroom.options.save === false) return;

	Darkroom.plugins['save'] = Darkroom.Plugin.extend({
		defaults: {
			callback: function(){
				this.darkroom.selfDestroy();
			}
		},

		initialize: function InitDarkroomSavePlugin(){
			var buttonGroup = this.darkroom.toolbar.createButtonGroup();

			this.destroyButton = buttonGroup.createButton({
				image: 'save'
			});

			this.destroyButton.addEventListener('click', this.options.callback.bind(this));
		}
	});
})(window, document, Darkroom);
