/*!
 * Ext.ux.RatingField
 *
 * Copyright 2011, Dan Harabagiu
 * Licenced under the Apache License Version 2.0
 * See LICENSE
 *
 *
 * Version : 0.1 - Initial coding
 * Version : 0.2
 *  - Added Field reset button
 *  - Added CSS class for reset button
 *  - Added reset function for the field
 *  - Minimum number of stars is 2
 *  - On creation default value is now 0, was null
 *  - Option to choose left / right for the reset button position
 */
/*global Ext : false, */

Ext.define('App.ux.RatingField', {
	extend: 'Ext.form.Field',
	alias: 'widget.ratingField',
	requires: [
		'Ext.form.field.VTypes',
		'Ext.layout.component.field.Text'
	],
	//Configurable parameters
	numberOfStars: 5,
	ratingClassOn: "starOn",
	ratingClassOff: "starOff",
	ratingClassReset: "starReset",
	ratingClassSelected: "starClicked",
	resetButtonPosition: "right",
	margin:'3 5',
	/**
	 * Initialisez the elements and renders them
	 * @param ct {Ext.Component} The component itself
	 * @param position {Object} The options object
	 * @return nothing
	 * Private Function
	 */
	onRender: function(ct, position){
		this.callParent(arguments);

		//We default to 2 stars
//		if(this.numberOfStars < 2 || this.numberOfStars > 10){
//			this.numberOfStars = 2;
//		}

		//We default to right
//		if(this.resetButtonPosition !== "right" && this.resetButtonPosition !== "left"){
//			this.resetButtonPosition = "right";
//		}

		this.bodyEl.update('');

//		if(this.resetButtonPosition === "left"){
//			this.createCancelButton();
//		}

		this.stars = [];
		for(var i = 1; i <= this.numberOfStars; i++){
			var starElement = document.createElement('div');
			starElement.setAttributeNode(this.createHtmlAttribute("key", i));
			var star = new Ext.Element(starElement);
			star.addCls(this.ratingClassOff);
			this.bodyEl.appendChild(star);
			this.stars[i - 1] = star;
		}

//		if(this.resetButtonPosition === "right"){
//			this.createCancelButton();
//		}

		var inputElement = document.createElement('input');
		inputElement.setAttributeNode(this.createHtmlAttribute("type", "hidden"));
		inputElement.setAttributeNode(this.createHtmlAttribute("name", this.getName()));
		this.hiddenField = new Ext.Element(inputElement);
		this.hiddenField.addCls('starHiddenClearMode');
		this.bodyEl.appendChild(this.hiddenField);
		this.reset();
	},
	/**
	 * Create and append the reset button for the field
	 * @return nothing
	 * Private function
	 */
//	createCancelButton: function(){
//		var cancelButtonElement = document.createElement('div');
//		this.cancelButton = new Ext.Element(cancelButtonElement);
//		this.cancelButton.addCls(this.ratingClassReset);
//		this.bodyEl.appendChild(this.cancelButton);
//	},
	/**
	 * Initialise event listeners
	 * @return nothing
	 * Private function
	 */
	initEvents: function(){
		this.callParent();

		for(var i = 0; i < this.stars.length; i++){
			this.stars[i].on('mouseenter', this.showStars, this);
			this.stars[i].on('mouseleave', this.hideStars, this);
			this.stars[i].on('click', this.selectStars, this);
		}

//		this.cancelButton.on('click', this.reset, this);
	},
	/**
	 * Reset the stars and content of the field to 0
	 * @return nothing
	 */
	reset: function(){
		for(var i = 0; i < this.stars.length; i++){
			if(this.stars[i].hasCls(this.ratingClassOn) === true && this.stars[i].hasCls(this.ratingClassSelected) === true){
				this.stars[i].replaceCls(this.ratingClassOn, this.ratingClassOff);
				this.stars[i].removeCls(this.ratingClassSelected);
			}
		}
		this.setValue(0);
		this.hiddenField.set({ 'value': 0 }, true);
	},
	/**
	 * Based on click event, mark the amount of stars selected
	 * @param {Ext.EventObject} e
	 * @param {HTMLElement} t
	 * @return nothing
	 */
	selectStars: function(e, t){
		var i;
		var limitStar = t.getAttribute('key');
		this.reset();
		this.setValue(limitStar);
		this.hiddenField.set({ 'value': limitStar }, true);
		for(i = 0; i < this.stars.length; i++){
			this.stars[i].removeCls(this.ratingClassSelected);
		}

		for(i = 0; i < limitStar; i++){
			if(this.stars[i].hasCls(this.ratingClassOn) === false){
				this.stars[i].replaceCls(this.ratingClassOff, this.ratingClassOn);
			}
			this.stars[i].addCls(this.ratingClassSelected);
		}
	},
	/**
	 * Based on hover, show the amount of stars that will be selected
	 * @param {Ext.EventObject} e
	 * @param {HTMLElement} t
	 * @return nothing
	 */
	showStars: function(e, t){
		var limitStar = t.getAttribute('key');
		for(var i = 0; i < limitStar; i++){
			if(this.stars[i].hasCls(this.ratingClassOn) === false && this.stars[i].hasCls(this.ratingClassSelected) === false){
				this.stars[i].replaceCls(this.ratingClassOff, this.ratingClassOn);
			}
		}
	},
	/**
	 * Based on hover out, hide the amount of stars showed
	 * @return nothing
	 */
	hideStars: function(){
		for(var i = 0; i < this.stars.length; i++){
			if(this.stars[i].hasCls(this.ratingClassOff) === false && this.stars[i].hasCls(this.ratingClassSelected) === false){
				this.stars[i].replaceCls(this.ratingClassOn, this.ratingClassOff);
			}
		}
	},
	/**
	 * Private function, that ads a html attribute to a dom element
	 * @param {string} name The name of the attribute
	 * @param {string} value The value of the attribute
	 * @return {*}
	 */
	createHtmlAttribute: function(name, value){
		var attribute = document.createAttribute(name);
		attribute.nodeValue = value;
		return attribute;
	}
});