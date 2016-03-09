/*
 * Copyright 2007-2011, Active Group, Inc.  All rights reserved.
 * ******************************************************************************
 * This file is distributed on an AS IS BASIS WITHOUT ANY WARRANTY; without even
 * the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * ***********************************************************************************
 * @version 4.0 alpha-2
 * [For Ext 4.0 or higher only]
 *
 * License: App.ux.ManagedIframe.Component, App.ux.ManagedIframe.Element, and multidom.js
 * are licensed under the terms of the Open Source GPL 3.0 license:
 * http://www.gnu.org/licenses/gpl.html
 *
 * Commercial use is prohibited without a Commercial Developement License. See
 * http://licensing.theactivegroup.com.
 *
 */

(function(){

	var Element = Ext.core.Element,
		DomHelper = Ext.core.DomHelper,
		slice = Array.prototype.slice;

	/**
	 * @class App.ux.ManagedIframe
	 * @extends Ext.Component
	 */
	Ext.define('App.ux.ManagedIframe', {

		/* Begin Definitions */
		extend: 'Ext.Component',
		alias: 'widget.miframe',

		/* End Definitions */

		hideMode: Ext.isIE ? 'display' : 'nosize',

		/*
		 * @cfg {Boolean} autoScroll True to set overflow:auto on the nested iframe.
		 * If False, overflow is forced to hidden.
		 * Note: set to undefined to control overflow-x/y via the frameStyle config option
		 */
		autoScroll: true,

		/*
		 * @cfg {String/Object} frameStyle (optional) Style string or object configuration representing
		 * the desired style attributes to apply to the embedded IFRAME.
		 * @default 'height:100%; width:100%;'
		 */
		frameStyle: null,

		frameConfig: undefined,

		frameCls: 'ux-miframe',

		shimCls: 'ux-miframe-shim',

		shimUrl: Ext.BLANK_IMAGE_URL,

		/*
		 * @cfg {Boolean} eventsFollowFrameLinks True to raise the 'dataavailable' event anytime
		 * the frame document is reloaded (including when the user follows a link to another page)
		 * Note: the load event is always fired
		 * @default true
		 */
		eventsFollowFrameLinks: true,

		/*
		 * @cfg {String} src (optional) Uri to load into the frame
		 */
		src: 'about:blank',

		/*
		 * @cfg {Boolean} autoMask True to display a loadMask during page content changes
		 */
		autoMask: false,

		/*
		 * @cfg {String} maskMessage default message text rendered during masking operations
		 */
		maskMessage: 'Loading...',

		/*
		 * @cfg {String} resetUrl (optional) Uri to load into the frame during initialization only
		 * @default undefined
		 */
		resetUrl: undefined,

		ariaRole: 'presentation',

		unsupportedText: 'Frames are disabled',

		/*
		 * Bubble frame events to upstream containers
		 */
		//bubbleEvents: ['dataavailable', 'load', 'unload', 'scroll', 'reset'],

		initComponent: function(){

			var me = this;
			me.frameStyle = Ext.isString(me.frameStyle)
				? Element.parseStyles(me.frameStyle)
				: me.frameStyle || {};

			me.frameName = me.frameName || me.getId();

			//			delete me.autoEl;

			me.autoEl = me.autoEl || {              //generate the necessary markup for shimming and noframes support
				cn: [
					Ext.applyIf(me.frameConfig || {},
						{
							tag: 'iframe',
							cls: me.frameCls,
							style: Ext.apply(
								{
									"height": "100%",
									"width": "100%"
								},
								me.frameStyle
							),
							frameBorder: 'no',
							role: me.ariaRole,
							name: me.frameName
						}
					),
					{
						tag: 'noframes',
						html: me.unsupportedText
					},
					{
						tag: 'img',
						cls: me.shimCls,
						galleryimg: "no",
						style: "position:absolute;top:0;left:0;display:none;z-index:20;height:100%;width:100%;",
						src: me.shimUrl
					}
				]
			};

			this.callParent();

		},

		renderSelectors: {
			frameElement: 'iframe.ux-miframe',
			frameShim: 'img.ux-miframe-shim'
		},

		iframeMessageListener: function(event){

			if(event.origin !== window.location.origin) return;
			if(!event.data.match(/^documentedit/)) return;

			var data = event.data.replace(/^documentedit/, '');
			app.fireEvent('documentedit', eval('(' + data + ')'));
		},

		afterRender: function(container){
			var me = this, frame;
			me.callParent();

			if(me.iframeMessageListener){
				if (window.addEventListener){
					window.addEventListener("message", me.iframeMessageListener, false);
				} else {
					window.attachEvent("onmessage", me.iframeMessageListener);
				}
			}

			if(me.frameShim){
				me.frameShim.autoBoxAdjust = false;
				me.frameShim.setVisibilityMode(Element.DISPLAY);
			}

			if(frame = me.frameElement){
				frame = me.frameElement = new App.ux.ManagedIframe.Element(frame.dom);

				//Suppress dataavailable event chatter during initialization
				frame.eventsFollowFrameLinks = false;

				frame.on({
					dataavailable: me.onFrameDataAvailable,
					scope: me
				});

				if(this.autoLoad){
					frame.isReset = Ext.isIE;
					frame.eventsFollowFrameLinks = !!me.eventsFollowFrameLinks;
				}else{

					Ext.Function.defer(
						frame.reset,
						100,        //permit layout to quiesce
						frame,
						[
							me.resetUrl,
							function(){
								var me = this;
								frame.eventsFollowFrameLinks = !!me.eventsFollowFrameLinks;
								if(me.src || me.defaultSrc){
									me.setSrc();
								}else if(me.data || me.html){
									me.update(me.data || me.html);
								}
							},
							me
						]
					);
				}
			}
		},

		// private
		getContentTarget: function(){
			return this.frameElement;
		},

		getActionEl: function(){
			return this.frameElement || this.el;
		},

		/*
		 * @private
		 */
		onFrameDataAvailable: function(e){
			if(this.autoMask){
				this.setLoading(false);
			}
		},

		/*
		 * Setter - Changes the current src attribute of the IFRAME, applying a loadMask
		 * over the frame (if autoMask is true)
		 * Note: call without the uri argument to simply refresh the frame with the current src value
		 * @param {Function} callback (Optional) A callback function invoked when the
		 *            frame document has been fully loaded.
		 * @param {Object} scope (Optional) scope by which the callback function is
		 *            invoked.
		 */
		setSrc: function(uri, callback, scope){
			var me = this;
			uri = uri || me.src || me.defaultSrc;
			if(uri && me.rendered && me.frameElement){

				if(me.autoMask && me.isVisible(true)){
					me.setLoading(me.maskMessage || '', true);
				}

				me.frameElement.setSrc(uri, false, callback, scope);
			}
			me.src = uri;
			return me;
		},

		setLoading: function(load){
			var me = this;

			if(load !== false){
				me.el.mask(me.maskMessage || '');
			}else{
				me.el.unmask();
			}
		},

		/**
		 * contentEl is NOT supported, but tpl/data, and html ARE.
		 * @private
		 */
		initContent: function(){
			var me = this,
				content = me.data || me.html;

			if(me.contentEl && Ext.isDefined(Ext.global.console)){
				Ext.global.console.warn('App.ux.ManagedIframe: \'contentEl\' is not supported by this class.');
			}

			// Make sure this.tpl is an instantiated XTemplate
			if(me.tpl){
				me.setTpl(me.tpl);
			}

			delete me.contentEl;
		},

		setTpl: function(tpl){
			this.tpl = (tpl && !tpl.isTemplate) ? Ext.create('Ext.XTemplate', tpl) : tpl;
			return this;
		},

		/**
		 * Update(replacing) the document content of the IFRAME.
		 * @param {Mixed} htmlOrData
		 * If this component has been configured with a template via the tpl config
		 * then it will use this argument as data to populate the frame.
		 * If this component was not configured with a template, the components
		 * content area (iframe) will be updated via App.ux.ManagedIframe.Element update
		 * @param {Boolean} loadScripts (optional) Defaults to false
		 * @param {Function} callback (optional) Callback to execute when scripts have finished loading
		 * @param {Object} scope (optional) execution context of the the callback
		 */
		update: function(htmlOrData, loadScripts, callback, scope){
			var me = this,
				content = htmlOrData;

			if(me.tpl && Ext.isArray(content) || Ext.isObject(content)){
				me.data = content;
				content = me.tpl.apply(content || {});
			}

			if(me.rendered){
				me.autoMask &&
				me.isVisible(true) &&
				me.setLoading(me.maskMessage || '');

				var frame = me.getContentTarget();
				Ext.defer(
					function(){
						frame.update(content, loadScripts, callback, scope);
						//Ext.defer(this.setLoading, 100, this, [false]);
					},
					me.autoMask ? 100 : 10,
					me,
					[ ]
				);

			}
			return me;
		},

		/**
		 * Sets the overflow on the IFRAME element of the component.
		 * @param {Boolean} scroll True to allow the IFRAME to auto scroll.
		 * @return {App.ux.ManagedIframe.Component} this
		 */
		setAutoScroll: function(scroll){
			var me = this,
				targetEl;
			if(Ext.isDefined(scroll)){  //permits frameStyle overrides
				scroll = !!scroll;
				if(me.rendered && (targetEl = me.getContentTarget())){
					targetEl.setStyle('overflow', scroll ? 'auto' : 'hidden');
				}
				me.autoScroll = scroll;
			}
			return me;
		},

		/*
		 *   Toggle the transparent shim on/off
		 */
		toggleShim: function(enabled){
			var me = this;
			if(me.frameShim){
				me.frameShim[enabled ? 'show' : 'hide']();
			}
			return me.frameShim;
		},

		onDestroy: function(){
			var me = this, frame;
			if(frame = me.frameElement){
				frame.remove();
			}
			me.deleteMembers('frameElement', 'frameShim');

			if(me.iframeMessageListener){
				if (window.addEventListener){
					removeEventListener("message", me.iframeMessageListener, false);
				} else {
					detachEvent("onmessage", me.iframeMessageListener);
				}
			}


			me.callParent();
		}

	});

	var MIF = App.ux.ManagedIframe,
		EC = Ext.cache,
		DOC = window.document,

	// @private add/remove Listeners
		addListener = function(){
			var handler;
			if(window.addEventListener){
				handler = function F(el, eventName, fn, capture){
					el.addEventListener(eventName, fn, !!capture);
				};
			}else if(window.attachEvent){
				handler = function F(el, eventName, fn, capture){
					el.attachEvent("on" + eventName, fn);
				};
			}else{
				handler = function F(){
				};
			}
			var F = null; //Gbg collect
			return handler;
		}(),
		removeListener = function(){
			var handler;
			if(window.removeEventListener){
				handler = function F(el, eventName, fn, capture){
					el.removeEventListener(eventName, fn, (capture));
				};
			}else if(window.detachEvent){
				handler = function F(el, eventName, fn){
					el.detachEvent("on" + eventName, fn);
				};
			}else{
				handler = function F(){
				};
			}
			var F = null; //Gbg collect
			return handler;
		}();

	Ext.define('App.ux.ManagedIframe.Element', {

		/* Begin Definitions */
		extend: 'Ext.core.Element',
		alias: 'widget.miframeelement',
		/* End Definitions */

		visibilityMode: Element.ASCLASS,   //nosize for hiding

		eventsFollowFrameLinks: true,

		focusOnLoad: Ext.isIE,

		constructor: function(element){
			var id,
				dom = (typeof element == "string")
					? DOC.getElementById(element)
					: (element || {}).dom || element;

			if(!dom){
				return null;
			}

			id = dom.id;

			/**
			 * The DOM element
			 * @type HTMLElement
			 */
			this.dom = dom;

			/**
			 * The DOM element ID
			 * @type String
			 */
			this.id = id || Ext.id(dom);

			this.dom.name = this.dom.name || this.id;
			window.frames[this.dom.name] = this.dom;

			this.dom.manager = this;
			this._flyweights = {};

			if(EC[this.id] && EC[this.id].el){
				EC[this.id].el = this;
			}else{
				Element.addToCache(this);
			}

			/*
			 * Sets up the events required to maintain the state machine
			 */
			// Hook the Iframes loaded/state handlers
			Ext.isGecko || Ext.isWebkit || this.on(
				(Ext.isOpera) ? 'DOMFrameContentLoaded' : 'readystatechange',
				this.loadHandler,
				this,
				/**
				 * Opera still fires LOAD events for images within the FRAME as well,
				 * so we'll buffer hopefully catching one of the later events
				 */
				Ext.isOpera ? {buffer: this.operaLoadBuffer || 2000} : null
			);

			this.on({
				'dataavailable': function(e, target){
					//set current frameAction for downstream listeners
					e && Ext.apply(e, {
						frameAction: this._frameAction,
						frameResetting: this.isReset
					});
				},
				load: this.loadHandler,
				scope: this

			});

		},

		/**
		 * If sufficient privilege exists, returns the frame's current document
		 * as an HTMLElement.
		 * @param {Boolean} assertAccess (optional) False to return the document regardless of
		 what domain served the page.
		 * @return {HTMLElement} The frame document or false if access to document object was denied.
		 */
		getFrameDocument: function(assertAccess){
			var win = this.getWindow(), doc = null;
			try{
				doc = win.contentDocument || this.dom.contentDocument || window.frames[this.dom.name].document || win.document;
			}catch(gdEx){
				doc = false; // signifies probable access restriction
			}
			return  doc || false;
		},

		/**
		 * Returns the frame's current HTML document object as an
		 * {@link Ext.Element}.
		 * @return {Ext.Element} The document
		 */
		getDoc: function(){
			return this.fly(this.getFrameDocument());
		},

		/**
		 * If sufficient privilege exists, returns the frame's current document
		 * body as an HTMLElement.
		 *
		 * @return {Ext.Element} The frame document body or Null if access to
		 *         document object was denied.
		 */
		getBody: function(){
			var d;
			return (d = this.getFrameDocument()) ? this.get(d.body) : null;
		},

		/*
		 * Convert an HTMLElement (by id or reference) to a Flyweight Element
		 */
		get: function(el){
			return this.fly(el);
		},

		fly: function(el, named){
			var me = this,
				ret = null,
				doc = me.getFrameDocument(),
				id;

			if(!doc || !el){
				return ret;
			}
			named = named || '_global';
			el = Ext.getDom(el, false, doc);
			if(el){

				/* Note: this does two things:
				 * 1) properly asserts the window/document id's
				 * 2) initializes event caches for foreign Flyweights
				 */
				id = Ext.EventManager.getId(el);

				/*
				 * maintain a Frame-localized cache of Flyweights
				 */
				(me._flyweights[id] = me._flyweights[id] || new MIF.Element.Flyweight()).dom = el;
				ret = me._flyweights[id];
			}
			return ret;
		},

		/**
		 * Creates a {@link Ext.CompositeElement} for child nodes based on the
		 * passed CSS selector (the selector should not contain an id).
		 *
		 * @param {String} selector The CSS selector
		 * @return {Ext.CompositeElement/Ext.CompositeElementLite} The composite element
		 */
		select: function(selector){
			var d;
			return (d = this.getFrameDocument()) ? Element.select(selector, false, d) : d = null;
		},

		/**
		 * Selects frame document child nodes based on the passed CSS selector
		 * (the selector should not contain an id).
		 *
		 * @param {String} selector The CSS selector
		 * @return {Array} An array of the matched nodes
		 */
		query: function(selector){
			var d;
			return (d = this.getFrameDocument()) ? Ext.DomQuery.select(selector, d) : d = null;
		},

		/**
		 * Attempt to retrieve the frames current URI via frame's document object
		 * @return {string} The frame document's current URI or the last know URI if permission was denied.
		 */
		getDocumentURI: function(){

			var URI, d;
			try{
				URI = this.src && (d = this.getFrameDocument()) ? d.location.href : null;
			}catch(ex){
			} // will fail on NON-same-origin domains
			return URI || (Ext.isFunction(this.src) ? this.src() : this.src);
		},

		/**
		 * Attempt to retrieve the frames current URI via frame's Window object
		 * @return {string} The frame document's current URI or the last know URI if permission was denied.
		 */
		getWindowURI: function(){
			var URI, w, me = this;
			try{
				URI = (w = me.getWindow()) ? w.location.href : null;
			}catch(ex){
			} // will fail on NON-same-origin domains
			return URI || (Ext.isFunction(me.src) ? me.src() : me.src);

		},

		/**
		 * Returns the frame's current window object.
		 * @return {Window} The frame Window object.
		 */
		getWindow: function(){
			var dom = this.dom, win = null;
			try{
				win = dom.contentWindow || window.frames[dom.name] || null;
			}catch(gwEx){
			}
			return win;
		},

		/**

		 * Scrolls a frame document's child element into view within the passed container.
		 * Note:
		 * @param {String} child The id of the element to scroll into view.
		 * @param {Mixed} container (optional) The container element to scroll (defaults to the frame's document.body).  Should be a
		 * string (id), dom node, or Ext.Element reference with an overflow style setting.
		 * @param {Boolean} hscroll (optional) False to disable horizontal scroll (defaults to true)
		 * @return {App.ux.ManagedIframe.Element} this
		 */
		scrollChildIntoView: function(child, container, hscroll){
			var me = this,
				doc = me.getFrameDocument(),
				f;
			if(doc){
				container = (container ? Ext.getDom(container, true, doc) : null) || (!Ext.isWebKit && Ext.isDocumentStrict(doc) ? doc.documentElement : doc.body);
				if(f = me.fly(child)){
					f.scrollIntoView(container, hscroll);
				}
			}
			return me;
		},

		/**
		 * @private
		 * Evaluate the Iframes readyState/load event to determine its
		 * 'load' state, and raise the 'dataavailable' and other events when
		 * applicable.
		 */
		loadHandler: function(e, target, options){
			e = e || {};
			var me = this,
				rstatus = (e.type == 'readystatechange') ? (me.dom || {}).readyState : e.type;

			//console.info('LH ' ,  rstatus , ' follows:',me.eventsFollowFrameLinks, ' isReset:', me.isReset, ' action:',me._frameAction );
			if(me.eventsFollowFrameLinks || me._frameAction || me.isReset){

				me.isReset && e.stopEvent && e.stopEvent();
				switch(rstatus){

					case 'domready' : // MIF
					case 'DOMFrameContentLoaded' :

						me._onDocReady(rstatus, e);
						me.fireDOMEvent('domready', null, {message: e.message});
						break;

					case 'interactive':   // IE/ Legacy Opera
						me.domReady = me.loaded = false;
						me.isReset || me.assertOnReady();  //for IE, begin polling here as IE7 holds the DOM a bit longer
						break;
					case 'complete' :
						me.loaded = true;
						me.fireDOMEvent('complete', null, {message: e.message});
						break;
					case 'load' : // Gecko, Opera, IE
						me.loaded = true;
						Ext.apply(e, {
							frameAction: me._frameAction,
							frameResetting: me.isReset
						});
						me._onDocLoaded(rstatus, e);
						me.dispatchCallbacks(e, target, options);
						break;

					case 'error':
						me.fireDOMEvent('error', null, {message: e.message});
						me._frameAction = false;
						break;
					default :
				}

				me.frameState = rstatus;
			}

		},

		/*
		 *  @private DOM Ready handler
		 */
		_onDocReady: function(status, e){
			var me = this;

			me.domReady = true;

			try{
				if(!me.isReset && me.focusOnLoad){
					me.focus();
				}
			}catch(ex){
			}

			//raise internal private event regardless of state.
			me.fireDOMEvent('datasetchanged');

			if(!me.isReset && !me.domReadyFired && me._renderHooks()){

				// Only raise if sandBox injection succeeded (same origin)
				if(!me.isReset){  //but never during a reset
					me.domReadyFired = true;
					me.fireDOMEvent('dataavailable');
				}
			}
		},

		_onDocLoaded: function(status, e){
			var me = this;
			/*
			 * this is necessary to permit other browsers a chance to raise dataavailable during
			 * page transitions (eventsFollowFrameLinks)
			 */
			!me.isReset && (!me._frameAction || me.eventsFollowFrameLinks) && !me.domReady && me._onDocReady();
			me._frameAction = me.domReadyFired = me.isReset = me.domReady = false;
			me._targetURI = null;
		},

		/*
		 * @private DOM Event Factory
		 */
		createEvent: document.createEvent ?
			function(eventName, eventClass, fromE, options){   //DOM2 Event interfaces
				options = options || { bubbles: true, cancelable: false };
				eventClass = eventClass || 'Event';
				var evt = document.createEvent(eventClass);
				evt.initEvent(eventName, !!Ext.value(options.bubbles, true), !!Ext.value(options.cancelable, false));
				return evt;
			} :
			function(eventName, eventClass, fromE, options){   //IE-style Events
				options = options || { type: 'on' + eventName, bubbles: true, cancelable: false };
				var evt = document.createEventObject(fromE);
				return Ext.apply(evt, options);
			},

		/*
		 * @private DOM Event Dispatch
		 */
		dispatchEvent: document.createEvent ?
			function(e, eventName, options){
				return this.dom ? !this.dom.dispatchEvent(e) : null;
			} :
			function(e, eventName, options){
				return this.dom ? this.dom.fireEvent('on' + eventName, e) : null;
			},

		/*
		 *  Dispatch a new (or copied) Generic event with the current Element as the event target
		 */
		fireDOMEvent: function(eventName, e, args){
			return this.dispatchEvent(
				this.createEvent(eventName, null, e, args),
				eventName, args
			);
		},

		/**
		 * @private execScript sandbox and messaging interface
		 */
		_renderHooks: function(){

			var me = this;
			me._windowContext = null;
			Ext.destroy(me.CSS);
			delete me.CSS;
			me._hooked = false;
			try{
				if(me.writeScript(
						'(function(){(window.hostMIF = parent.document.getElementById("' + me.id +
						'").manager)._windowContext='
						+ (Ext.isIE
						? 'window'
						: '{eval:function(s){return new Function("return ("+s+")")();}}')
						+ ';})()')){

					var w,
						al = addListener,
						p = me._frameProxy || (me._frameProxy = Ext.bind(MIF.Element.eventProxy, me)),
						doc = me.getFrameDocument();

					/*
					 * Route all desired events through the proxy for normalization
					 */
					if(doc && (w = me.getWindow())){
						al(Ext.isIE ? doc.body || doc.documentElement : w, 'focus', p);
						al(Ext.isIE ? doc.body || doc.documentElement : w, 'blur', p);
						al(w, 'resize', p);
						al(w, 'beforeunload', p);
						//al(Ext.supports.Event('scroll', doc) ? doc : w, 'scroll', p);
					}

					// doc && (this.CSS = new Ext.ux.ManagedIFrame.CSS(doc));
				}

			}catch(ex){
			}
			return (me._hooked = me.domWritable());
		},

		/**
		 * Returns the general 'DOM modification capability' (same-origin status) of the frame.
		 * @return {Boolean} accessible If True, the frame's inner DOM can be manipulated, queried, and
		 * Event Listeners set.
		 */
		domWritable: function(){
			return !!this._windowContext && !!this.getFrameDocument(); //test access
		},

		/** @private : clear all event listeners and Sandbox hooks
		 * This returns the Element to an un-managed state.
		 */
		_unHook: function(){
			var me = this;
			if(me._hooked){
				try{
					me._windowContext && (me._windowContext.hostMIF = null);
				}catch(uhex){
				}
				me._windowContext = null;
				var w,
					rl = removeListener,
					p = me._frameProxy,
					doc = me.getFrameDocument();

				if(p && doc && (w = me.getWindow())){
					rl(Ext.isIE ? doc.body || doc.documentElement : w, 'focus', p);
					rl(Ext.isIE ? doc.body || doc.documentElement : w, 'blur', p);
					rl(w, 'resize', p);
					rl(w, 'beforeunload', p);
					//rl(Ext.supports.Event('scroll', doc) ? doc : w, 'scroll', p);
				}

			}
			me._flyweights = {};
			//doc && Element.clearDocumentCache(doc.id);

			Ext.destroy(me.CSS);
			delete me.CSS;
			me._frameAction = me.domReady = me.domFired = me._hooked = false;
		},

		/**
		 * Loads the frame Element with the response from a form submit to the
		 * specified URL with the ManagedIframe.Element as it's submit target.
		 *
		 * @param {Object} submitCfg A config object containing any of the following options:
		 * <pre><code>
		 *      myIframe.submitAsTarget({
         *         form : formPanel.form,  //optional Ext.FormPanel, Ext form element, or HTMLFormElement
         *         url: &quot;your-url.php&quot;,
         *         action : (see url) ,
         *         params: {param1: &quot;foo&quot;, param2: &quot;bar&quot;}, // or URL encoded string or function that returns either
         *         callback: yourFunction,  //optional, called with the signature (event, target, evOptions)
         *         scope: yourObject, // optional scope for the callback
         *         method: 'POST', //optional form.method
         *         encoding : "multipart/form-data" //optional, default = HTMLForm default
         *      });
		 *
		 * </code></pre>
		 * @return {Ext.ux.ManagedIFrame.Element} this
		 *
		 */
		submitAsTarget: function(submitCfg){

			var opt = submitCfg || {},
				doc = this.getParentDocument(),
				form = Ext.getDom(
					opt.form ? opt.form.form || opt.form : null, false, doc) ||
					Ext.DomHelper.append(doc.body, {
						tag: 'form',
						cls: 'x-hide-offsets x-mif-form',
						encoding: 'multipart/form-data'
					}),
				formFly = Ext.fly(form, '_dynaForm'),
				formState = {
					target: form.target || '',
					method: form.method || '',
					encoding: form.encoding || '',
					enctype: form.enctype || '',
					action: form.action || ''
				},
				encoding = opt.encoding || form.encoding,
				method = opt.method || form.method || 'POST';

			formFly.set({
				target: this.dom.name,
				method: method,
				encoding: encoding,
				action: opt.url || opt.action || form.action
			});

			if(method == 'POST' || !!opt.enctype){
				formFly.set({enctype: opt.enctype || form.enctype || encoding});
			}

			var hiddens, hd, ps;
			// add any additional dynamic params
			if(opt.params && (ps = Ext.isFunction(opt.params) ? opt.params() : opt.params)){
				hiddens = [];

				Ext.iterate(ps = typeof ps == 'string' ? Ext.urlDecode(ps, false) : ps,
					function(n, v){

						Ext.fly(hd = D.createElement('input')).set({
							type: 'hidden',
							name: n,
							value: v
						});
						form.appendChild(hd);
						hiddens.push(hd);
					});
			}

			Ext.isFunction(opt.callback) &&  //use the internal event to dispatch the callback
			this.on('datasetchanged', opt.callback, opt.scope || this, {single: true, submitOptions: opt});

			this._frameAction = true;
			this._targetURI = location.href;

			form.submit();

			// remove dynamic inputs
			hiddens && Ext.each(hiddens, Ext.removeNode, Ext);

			//Remove if dynamically generated, restore state otherwise
			if(formFly.hasClass('x-mif-form')){
				formFly.remove();
			}else{
				formFly.set(formState);
			}

			formFly = null;
			return this;
		},

		/**
		 * @cfg {String} resetUrl Frame document reset string for use with the {@link #Ext.ux.ManagedIFrame.Element-reset} method.
		 * Defaults:<p> For IE on SSL domains - the current value of Ext.SSL_SECURE_URL<p> "about:blank" for all others.
		 */
		resetUrl: (function(){
			return Ext.isIE && Ext.isSecure ? Ext.SSL_SECURE_URL : 'about:blank';
		})(),

		/**
		 * Sets the embedded Iframe src property. Note: invoke the function with
		 * no arguments to refresh the iframe based on the current src value.
		 *
		 * @param {String/Function} url (Optional) A string or reference to a Function that
		 *            returns a URI string when called
		 * @param {Boolean} discardUrl (Optional) If not passed as <tt>false</tt>
		 *            the URL of this action becomes the default SRC attribute
		 *            for this iframe, and will be subsequently used in future
		 *            setSrc calls (emulates autoRefresh by calling setSrc
		 *            without params).
		 * @param {Function} callback (Optional) A callback function invoked when the
		 *            frame document has been fully loaded.
		 * @param {Object} scope (Optional) scope by which the callback function is
		 *            invoked.
		 */
		setSrc: function(url, discardUrl, callback, scope){

			var src = url || this.src || this.resetUrl;
			Ext.isFunction(callback) && this.queueCallback(callback, scope || this);
			(discardUrl !== true) && (this.src = src);
			var s = this._targetURI = (Ext.isFunction(src) ? src() || '' : src);
			try{
				this._frameAction = true; // signal listening now
				this.dom.src = s;
				Ext.isIE || this.assertOnReady();
			}catch(ex){

			}
			return this;
		},

		/**
		 * Sets the embedded Iframe location using its replace method (precluding a history update).
		 * Note: invoke the function with no arguments to refresh the iframe based on the current src value.
		 *
		 * @param {String/Function} url (Optional) A string or reference to a Function that
		 *            returns a URI string when called
		 * @param {Boolean} discardUrl (Optional) If not passed as <tt>false</tt>
		 *            the URL of this action becomes the default SRC attribute
		 *            for this iframe, and will be subsequently used in future
		 *            setSrc calls (emulates autoRefresh by calling setSrc
		 *            without params).
		 * @param {Function} callback (Optional) A callback function invoked when the
		 *            frame document has been fully loaded.
		 * @param {Object} scope (Optional) scope by which the callback function is
		 *            invoked.
		 *
		 */
		setLocation: function(url, discardUrl, callback, scope){

			var me = this,
				src = url || me.src || me.resetUrl;
			me._unHook();
			Ext.isFunction(callback) && me.queueCallback(callback, scope);
			var s = me._targetURI = (Ext.isFunction(src) ? src() || '' : src);
			if(discardUrl !== true){
				me.src = src;
			}

			try{
				me._frameAction = true; // signal listening now
				me.getWindow().location.replace(s);
				Ext.isIE || me.assertOnReady();
			}catch(ex){
			}
			return me;
		},

		/**
		 * Resets the frame to a neutral (blank document) state
		 *
		 * @param {String}
		 *            src (Optional) A specific reset string (eg. 'about:blank')
		 *            to use for resetting the frame.
		 * @param {Function}
		 *            callback (Optional) A callback function invoked when the
		 *            frame reset is complete.
		 * @param {Object}
		 *            scope (Optional) scope by which the callback function is
		 *            invoked.
		 */
		reset: function(src, callback, scope){

			var me = this,
				s = src,
				win;

			me._unHook();
			if(win = me.getWindow()){
				me.isReset = me._frameAction = true;
				Ext.isFunction(callback) && me.queueCallback(callback, scope);

				Ext.isFunction(src) && ( s = src());
				s = me._targetURI = Ext.isEmpty(s, true) ? me.resetUrl : s;
				win.location.href = s;
			}
			return me;
		},

		queueCallback: function(fn, scope){
			var me = this;
			me.callbacks = me.callbacks || [];
			me.callbacks.push(scope ? Ext.bind(fn, scope || me) : fn);
			return me;
		},

		dispatchCallbacks: function(e, target, options){
			var me = this;
			if(me.callbacks && me.callbacks.length){
				while(me.callbacks.length){
					me.callbacks.shift()(e, target, options);
				}
			}
		},

		/**
		 * @private
		 * Regular Expression filter pattern for script tag removal.
		 * @cfg {regexp} scriptRE script removal RegeXp
		 * Default: "/(?:<script.*?>)((\n|\r|.)*?)(?:<\/script>)/gi"
		 */
		scriptRE: /(?:<script.*?>)((\n|\r|.)*?)(?:<\/script>)/gi,

		/**
		 * Write(replacing) string content into the IFrames document structure
		 * @param {String} content The new content
		 * @param {Boolean} loadScripts
		 * (optional) true to also render and process embedded scripts
		 * @param {Function} callback (Optional) A callback function invoked when the
		 * frame document has been written and fully loaded. @param {Object}
		 * scope (Optional) scope by which the callback function is invoked.
		 */
		update: function(content, loadScripts, callback, scope){

			var me = this;
			content = DomHelper.markup(content || '');

			content = (loadScripts !== false) ? content : content.replace(me.scriptRE, "");
			var doc;
			if((doc = me.getFrameDocument()) && !!content.length){
				me._unHook();
				me.src = null;
				Ext.isFunction(callback) && me.queueCallback(callback, scope || this);

				me._targetURI = null;
				me._frameAction = true;
				doc.open();
				doc.write(content);
				me.assertOnReady();
				doc.close();

			}else{
				Ext.callback(callback, scope || me);
			}

			return me;
		},

		/**
		 * Executes a Midas command on the current document, current selection, or the given range.
		 * @param {String} command The command string to execute in the frame's document context.
		 * @param {Booloean} userInterface (optional) True to enable user interface (if supported by the command)
		 * @param {Mixed} value (optional)
		 * @param {Boolean} validate If true, the command is validated to ensure it's invocation is permitted.
		 * @return {Boolean} indication whether command execution succeeded
		 */
		execCommand: function(command, userInterface, value, validate){

			var doc, assert;
			if((doc = this.getFrameDocument()) && !!command){
				try{
					Ext.isIE && this.focus();
					assert = validate && Ext.isFunction(doc.queryCommandEnabled)
						? doc.queryCommandEnabled(command)
						: true;

					return assert && doc.execCommand(command, !!userInterface, value);
				}catch(eex){
					return false;
				}
			}
			return false;
		},

		/**
		 * Sets the current DesignMode attribute of the Frame's document
		 * @param {Boolean/String} active True (or "on"), to enable designMode
		 *
		 */
		setDesignMode: function(active){
			var doc;
			if(doc = this.getFrameDocument()){
				doc.designMode = (/on|true/i).test(String(active)) ? 'On' : 'Off';
			}
			return this;
		},

		/**
		 * Print the contents of the Iframes (if we own the document)
		 * @return {Ext.ux.ManagedIFrame.Element} this
		 */
		print: function(){
			try{
				var win;
				if(win = this.getWindow()){
					Ext.isIE && win.focus();
					win.print();
				}
			}catch(ex){
				//<debug>
				var Err = this.statics().Error;
				Err.raise(
					{
                        msg: Err.message.printexception || ex.description || ex.message,
						error: ex,
						win: win
					}
				);
				//</debug>
			}
			return this;
		},

		/**
		 * Write a script block into the iframe's document
		 * @param {String} block A valid (executable) script source block.
		 * @param {object} attributes Additional Script tag attributes to apply to the script
		 * Element (for other language specs [vbscript, Javascript] etc.) <p>
		 * Note: writeScript will only work after a successful iframe.(Updater)
		 * update or after same-domain document has been hooked, otherwise an
		 * exception is raised.
		 */
		writeScript: function(block, attributes){

			attributes = Ext.apply({}, attributes || {}, {
				type: "text/javascript",
				text: block
			});

			try{
				var head, script, doc = this.getFrameDocument();
				if(doc && typeof doc.getElementsByTagName != 'undefined'){
					if(!(head = doc.getElementsByTagName("head")[0])){
						// some browsers (Webkit, Safari) do not auto-create
						// head elements during document.write
						head = doc.createElement("head");
						doc.getElementsByTagName("html")[0].appendChild(head);
					}
					if(head && (script = doc.createElement("script"))){
						for(var attrib in attributes){
							if(attributes.hasOwnProperty(attrib) && attrib in script){
								script[attrib] = attributes[attrib];
							}
						}
						return !!head.appendChild(script);
					}
				}

			}catch(ex){
			}finally{
				script = head = null;
			}
			return false;
		},

		/**
		 * eval a javascript code block(string) within the context of the
		 * Iframes' window object.
		 * @param {String} block A valid ('eval'able) script source block.
		 * @param {Boolean} useDOM  if true, inserts the function
		 * into a dynamic script tag, false does a simple eval on the function
		 * definition. (useful for debugging) <p> Note: will only work after a
		 * successful iframe.(Updater) update or after same-domain document has
		 * been hooked, otherwise an exception is raised.
		 * @return {Mixed}
		 */
		execScript: function(block, useDOM){
			var me = this;
			try{
				if(me.domWritable()){
					if(useDOM){
						me.writeScript(block);
					}else{
						return me._windowContext.eval(block);
					}
				}else{
					var Err = this.statics().Error;
					throw new Err(
						{ msg: Err.message['execscript-secure-context'],
							script: block
						}
					);
				}
			}catch(ex){
				return false;
			}
			return true;
		},

		/**
		 * Eval a function definition into the iframe window context.
		 * @param {String/Object} fn Name of the function or function map
		 * object: {name:'encodeHTML',fn:Ext.util.Format.htmlEncode}
		 * @param {Boolean} useDOM  if true, inserts the fn into a dynamic script tag,
		 * false does a simple eval on the function definition
		 * @param {Boolean} invokeIt if true, the function specified is also executed in the
		 * Window context of the frame. Function arguments are not supported.
		 * @example <pre><code> var trim = function(s){ return s.replace(/^\s+|\s+$/g,''); };
		 * iframe.loadFunction('trim');
		 * iframe.loadFunction({name:'myTrim',fn:String.prototype.trim || trim});</code></pre>
		 */
		loadFunction: function(fn, useDOM, invokeIt){
			var name = fn.name || fn,
				fnSrc = fn.fn || window[fn];

			name && fnSrc && this.execScript(name + '=' + fnSrc, useDOM); // fn.toString coercion
			invokeIt && this.execScript(name + '()'); // no args only
		},

		/**
		 * @private
		 * Poll the Iframes document structure to determine DOM ready
		 * state, and raise the 'domready' event when applicable.
		 */
		assertOnReady: function(){

			if(Ext.isGecko || this.isReset){
				return;
			}
			// initialise the counter
			var n = 0, frame = this, domReady = false,
				body, l, doc,
				max = this.domReadyRetries || 5000, //default max 5 seconds
				atTarget = false,
				startLocation = (this.getFrameDocument() || {location: {}}).location.href,
				fileSize, href,
				notDefined = /undefined|unknown/i,

				assertion = function(targetURI){ // DOM polling for IE and others
					if(this.domReady){
						return;
					}
					if(doc = this.getFrameDocument()){

						// wait for location.href transition
						// null href is a 'same-origin' document access violation,
						// this assumes the DOM is built when the browser updates it
						href = doc.location.href || '';
						atTarget = !targetURI || (href && (href != startLocation || href.indexOf(targetURI) > -1));

						/*
						 * On IE, when !(Transfer-Encoding: chunked), document.fileSize is populated when
						 * the DOM is ready
						 */
						fileSize = 0;
						try{  //IE/Webkit/Opera? will report the fileSize of the document when the DOM is ready
							fileSize = notDefined.test(typeof doc.fileSize) ? 0 : parseFloat(doc.fileSize);
						}catch(errFilesize){
						}

						domReady = (!!fileSize) || (atTarget && (body = doc.body) && !!(body.innerHTML || '').length );

						if(domReady){
							return frame.loadHandler.call(frame, { type: 'domready'});
						}
					}
					frame.loaded || (++n > max) || Ext.defer(assertion, 2, frame, slice.call(arguments, 0)); // try again
				};
			//console.log('seeking ', frame._targetURI, startLocation);
			assertion.call(frame, frame._targetURI);
		},

		/**
		 * Tries to focus the element. Any exceptions are caught and ignored.
		 * @param {Number} defer (optional) Milliseconds to defer the focus
		 * @return {App.ux.ManagedIframe.Element} this
		 */
		focus: function(defer){
			var me = this,
				w = me.getWindow();
			if(w){
				try{
					if(Number(defer)){
						Ext.defer(me.focus, defer, me, [null]);
					}else{
						w.focus();
					}
				}catch(e){
				}
			}
			w = null;
			return me;
		},

		/**
		 * Tries to blur the element. Any exceptions are caught and ignored.
		 * @return {App.ux.ManagedIframe.Element} this
		 */
		blur: function(){
			var me = this,
				w = me.getWindow();
			if(w){
				try{
					w.blur();
				}catch(e){
				}
			}
			w = null;
			return me;
		},

		/**
		 * <p>Removes this element's dom reference.  Note that event and cache removal is handled at {@link Ext#removeNode Ext.removeNode}</p>
		 */
		remove: function(){
			var me = this,
				dom = me.dom;
			if(dom){
				me.reset();
				Ext.removeNode(dom);
				delete me.dom;
			}
		},

		statics: {

			addMethods: function(o){
				Ext.apply(MIF.Element.prototype, o);
			},

			/** @private
			 * @static
			 * DOMFrameReadyHandler -- Dispatches the captured event to the target MIF.Element
			 */
			DOMFrameReadyHandler: function(e, target){
				var frame;
				try{
					frame = e.target ? e.target.manager : null;
				}catch(rhEx){        //nested (foreign) iframes will throw when accessing target
				}
				if(frame){
					frame.loadHandler.call(frame, e);
				}
			},

			/** @private
			 * @static
			 * Frame document event proxy
			 */
			eventProxy: function(e){
				if(!e) return;

				var evr,
					eventClass;

				if(!e['eventPhase'] || (e['eventPhase'] == (e['AT_TARGET'] || 2))){

					switch(e.type){
						case 'blur':
						case 'focus':
							eventClass = 'UIEvents';
						case 'resize':
							if(Ext.isIE)break;   //IE handles (blur, focus, resize) on the IFRAME itself, so
						// let's not fire them twice.
						case 'scroll':

							//relay subscribed events to the Element instance
							evr = this.dispatchEvent(
								this.createEvent(e.type, eventClass || 'HTMLEvents'),
								e.type
							);
							break;

						case 'unload':
						case 'beforeunload':
							this._unHook();  // same-domain unloads should unhook for next document rendering

					}

				}
				return evr;
			},

			Flyweight: Element.Flyweight,

			/*
			 * Returns the document context of the passed HTMLElement, window, or document object
			 * @return {HTMLDocument}
			 */
			getParentDocument: Element.getParentDocument,

			isDocumentStrict: function(doc){
				return (doc && doc.compatMode && doc.compatMode != "BackCompat");
			},

			/**
			 * Retrieves the document height
			 * @static
			 * @return {Number} documentHeight
			 */
			getDocumentHeight: function(win){
				win = win || window;
				var doc = this.getParentDocument(win);
				return Math.max(!this.isDocumentStrict(doc) ? doc.body.scrollHeight : doc.documentElement.scrollHeight, this.getViewportHeight(win));
			},

			/**
			 * Retrieves the document width
			 * @static
			 * @return {Number} documentWidth
			 */
			getDocumentWidth: function(win){
				win = win || window;
				var doc = this.getParentDocument(win);
				return Math.max(!this.isDocumentStrict(doc) ? doc.body.scrollWidth : doc.documentElement.scrollWidth, this.getViewportWidth(win));
			},

			/**
			 * Retrieves the viewport height of the window.
			 * @static
			 * @return {Number} viewportHeight
			 */
			getViewportHeight: function(win){
				return (win || window).innerHeight;
			},

			/**
			 * Retrieves the viewport width of the window.
			 * @static
			 * @return {Number} viewportWidth
			 */
			getViewportWidth: function(win){
				return (win || window).innerWidth;
			},

			/**
			 * Retrieves the viewport size of the window.
			 * @static
			 * @return {Object} object containing width and height properties
			 */
			getViewSize: function(win){
				win = win || window;
				return {
					width: this.getViewportWidth(win),
					height: this.getViewportHeight(win)
				};
			},

			/**
			 * Returns the top Element that is located at the passed coordinates
			 * @static
			 * @param {Number} x The x coordinate
			 * @param {Number} x The y coordinate
			 * @param {HTMLElement} doc The targeted document context
			 * @return {FlyWeight} The found Element
			 */
			fromPoint: function(x, y, doc){
				doc = this.getParentDocument(doc || document);
				return doc ? Ext.fly(doc.elementFromPoint(x, y), '_fromPoint') : null;
			}

		}
	});

	Ext.define('App.ux.ManagedIframe.Error', {
		extend: 'Ext.Error',
		name: 'App.ux.ManagedIframe.Error',
		statics: {
			raise: Ext.Error.raise,
			ignore: false,
			handle: function(){
				return this.ignore;
			},
			message: {
				'execscript-secure-context': 'An attempt was made at script execution within a document context with restricted access.',
				'printexception': 'An Error was encountered attempting the print the frame contents (document access is likely restricted).'
			}
		}
	});

	//Give MIFElement a static reference to the Error class
	MIF.Element.addStatics(
		{Error: App.ux.ManagedIframe.Error}
	);

	// for Gecko and any who might support it later
	if(window.addEventListener){

		window.addEventListener("DOMFrameContentLoaded", MIF.Element.DOMFrameReadyHandler, false);

		Ext.EventManager.on(window, 'beforeunload', function(){
			window.removeEventListener("DOMFrameContentLoaded", MIF.Element.DOMFrameReadyHandler, false);
		});
	}

}());
