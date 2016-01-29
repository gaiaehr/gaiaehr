(function(window, document, Darkroom, fabric){
	'use strict';

	Darkroom.plugins['zoom'] = Darkroom.Plugin.extend({
		initialize: function InitDarkroomZoomPlugin(){
			var buttonGroup = this.darkroom.toolbar.createButtonGroup();

			this.zoomOutButton = buttonGroup.createButton({
				image: 'zoom-out'
			});

			this.zoomInButton = buttonGroup.createButton({
				image: 'zoom-in'
			});

			this.zoomOutButton.addEventListener('click', this.zoomOutLeft.bind(this));
			this.zoomInButton.addEventListener('click', this.zoomInRight.bind(this));
		},

		zoomOutLeft: function zoomOutLeft(){
			this.zoom(false);
		},

		zoomInRight: function zoomRightRight(){
			this.zoom(true);
		},

		zoom: function zoom(zoom_in){
			var _this = this;

			var darkroom = this.darkroom;
			var canvas = darkroom.canvas;
			var image = darkroom.image;


			if(zoom_in){
				darkroom.scroller.zoomBy(0.8, true);
			}else{
				darkroom.scroller.zoomBy(1.2, true);
			}

			canvas.centerObject(image);
			image.setCoords();
			canvas.renderAll();
			darkroom.dispatchEvent('image:change');
		}
	});
})(window, document, Darkroom, fabric);
