/*
* Copyright 2007-2011, Active Group, Inc.  All rights reserved.
* ******************************************************************************
* This file is distributed on an AS IS BASIS WITHOUT ANY WARRANTY; without even
* the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* ***********************************************************************************
* @version 4.0 alpha-1
* [For Ext 4.0 or higher only]
*
* License: Ext.ux.ManagedIframe.Component and Ext.ux.ManagedIframe.Element
* are licensed under the terms of the Open Source GPL 3.0 license:
* http://www.gnu.org/licenses/gpl.html
*
* Commercial use is prohibited without a Commercial Developement License. See
* http://licensing.theactivegroup.com.
*
*/

/**
 * @class Ext.ux.ManagedIframe.Component
 * @extends Ext.Component
 */

Ext.define('App.ux.ManagedIframe',
{

	/* Begin Definitions */
	extend : 'Ext.Component',
	alias : 'widget.miframe',
	/* End Definitions */

	hideMode : Ext.isIE ? 'display' : 'nosize',

	/*
	 * @cfg {Boolean} autoScroll True to set overflow:auto on the nested iframe.
	 * If False, overflow is forced to hidden.
	 * Note: set to undefined to control overflow-x/y via the frameStyle config option
	 */
	autoScroll : true,

	/*
	 * @cfg {String/Object} frameStyle (optional) Style string or object configuration representing
	 * the desired style attributes to apply to the embedded IFRAME.
	 * Defaults to 'height:100%; width:100%;'
	 */
	frameStyle : null,

	frameCls : 'ux-miframe',

	shimCls : 'ux-miframe-shim',

	shimUrl : Ext.BLANK_IMAGE_URL,

	/*
	 * @cfg {String} src (optional) Uri to load into the frame
	 */
	src : null,

	/*
	 * @cfg {Boolean} autoMask True to display a loadMask during page content changes
	 */
	autoMask : true,

	/*
	 * @cfg {String} maskMessage default message text rendered during masking operations
	 */
	maskMessage : 'Loading...',

	resetUrl : 'javascript:void(0);',

	ariaRole : 'presentation',

	unsupportedText : i18n('frames_are_disabled'),

	/*
	 * Bubble frame events to upstream containers
	 */
	bubbleEvents : ['documentloaded', 'load'],

	initComponent : function()
	{

		var me = this, frameStyle = Ext.isString(me.frameStyle) ? Ext.core.Element.parseStyles(me.frameStyle) : me.frameStyle ||
		{
		};

		me.autoEl =
		{
			cn : [Ext.applyIf(me.frameConfig ||
			{
			},
			{
				tag : 'iframe',
				cls : me.frameCls,
				style : Ext.apply(
				{
					"height" : "100%",
					"width" : "100%"
				}, frameStyle),
				frameBorder : 'no',
				role : me.ariaRole,
				name : me.getId(),
				src : me.resetUrl || ''
			}),
			{
				tag : 'noframes',
				html : me.unsupportedText
			},
			{
				tag : 'img',
				cls : me.shimCls,
				galleryimg : "no",
				style : "position:absolute;top:0;left:0;display:none;z-index:20;height:100%;width:100%;",
				src : me.shimUrl
			}]
		};
		this.callParent();
	},

	renderSelectors :
	{
		frameElement : 'iframe.ux-miframe',
		frameShim : 'img.ux-miframe-shim'
	},

	afterRender : function()
	{
		var me = this;
		me.callParent();

		if (me.frameElement)
		{
			me.frameElement.relayEvent('load', me);
			//propagate DOM events to the component and bubbled consumers
			me.on(
			{
				load : me.onFrameLoad,
				scope : me
			});
		}
		if (me.frameShim)
		{
			me.frameShim.autoBoxAdjust = false;
			me.frameShim.setVisibilityMode(Ext.core.Element.DISPLAY);
		}
		//permit layout to quiesce
		Ext.defer(me.setSrc, 50, me, []);
	},

	// private
	getContentTarget : function()
	{
		return this.frameElement;
	},

	getActionEl : function()
	{
		return this.frameElement || this.el;
	},

	/*
	 * @private
	 */
	onFrameLoad : function(e)
	{
		var me = this;
		me.fireEvent('documentloaded', me, me.frameElement);
		if (me.autoMask)
		{
			me.setLoading(false);
		}
	},

	/*
	 * Setter - Changes the current src attribute of the IFRAME, applying a loadMask
	 * over the frame (if autoMask is true)
	 * Note: call without the uri argument to simply refresh the frame with the current src value
	 */
	setSrc : function(uri)
	{
		var me = this;
		uri = uri || me.src || me.defaultSrc;
		if (uri && me.rendered && me.frameElement)
		{
			me.autoMask && me.isVisible(true) && me.setLoading(me.maskMessage || '');

			me.frameElement.dom.src = uri;
		}
		me.src = uri;
		return me;
	},

	/**
	 * contentEl is NOT supported, but tpl/data, and html ARE.
	 * @private
	 */
	initContent : function()
	{
		var me = this, content = me.data || me.html;

		if (me.contentEl && Ext.isDefined(Ext.global.console))
		{
			Ext.global.console.warn('Ext.ux.ManagedIframe.Component: \'contentEl\' is not supported by this class.');
		}

		// Make sure this.tpl is an instantiated XTemplate
		if (me.tpl && !me.tpl.isTemplate)
		{
			me.tpl = Ext.create('Ext.XTemplate', me.tpl);
		}

		if (content)
		{
			me.update(content);
			//no-op until alpha2 release
		}
		delete me.contentEl;
	},

	/**
	 * Update(replacing) the document content of the IFRAME.
	 * @param {Mixed} htmlOrData
	 * If this component has been configured with a template via the tpl config
	 * then it will use this argument as data to populate the frame.
	 * If this component was not configured with a template, the components
	 * content area (iframe) will be updated via Ext.ux.ManagedIframe.Element update
	 * @param {Boolean} loadScripts (optional) Defaults to false
	 * @param {Function} callback (optional) Callback to execute when scripts have finished loading
	 */
	updateAlpha2 : function(htmlOrData, loadScripts, callback)
	{
		var me = this;

		if (me.tpl && !Ext.isString(htmlOrData))
		{
			me.data = htmlOrData;
			me.html = me.tpl.apply(htmlOrData ||
			{
			});
		}
		else
		{
			me.html = Ext.core.DomHelper.markup(htmlOrData);
		}

		if (me.rendered)
		{
			me.getContentTarget().update(me.html, loadScripts, callback);
		}
		return me;
	},

	//Frame writing scheduled for Aplha2, so no-op for now
	update : function()
	{
	},

	/**
	 * Sets the overflow on the IFRAME element of the component.
	 * @param {Boolean} scroll True to allow the IFRAME to auto scroll.
	 * @return {Ext.ux.ManagedIframe.Component} this
	 */
	setAutoScroll : function(scroll)
	{
		var me = this, targetEl;
		if (Ext.isDefined(scroll))
		{
			//permits frameStyle overrides
			scroll = !!scroll;
			if (me.rendered && ( targetEl = me.getContentTarget()))
			{
				targetEl.setStyle('overflow', scroll ? 'auto' : 'hidden');
			}
			me.autoScroll = scroll;
		}
		return me;
	},

	/*
	 *   Toggle the transparent shim on/off
	 */
	toggleShim : function(enabled)
	{
		var me = this;
		if (me.frameShim)
		{
			me.frameShim[enabled ? 'show' : 'hide']();
		}
		return me.frameShim;
	},

	onDestroy : function()
	{
		var me = this, frame;
		if ( frame = me.frameElement)
		{
			frame.clearListeners();
			frame.remove();
		}
		me.deleteMembers('frameElement', 'frameShim');
		me.callParent();
	}
});
