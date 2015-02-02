/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 11/1/11
 * Time: 12:37 PM
 */
Ext.define('App.ux.form.fields.HtmlEditor', {
	extend: 'Ext.form.field.HtmlEditor',
	alias: 'widget.customhtmleditor',


	componentTpl: [
		'{beforeTextAreaTpl}',
		'<textarea id="{id}-textareaEl" name="{name}" tabIndex="-1" {inputAttrTpl}',
		' class="{textareaCls}" autocomplete="off" data-nusa-custom-container-id="{id}-iframeEl" data-nusa-custom-control-type="Control_Type">',
		'{[Ext.util.Format.htmlEncode(values.value)]}',
		'</textarea>',
		'{afterTextAreaTpl}',
		'{beforeIFrameTpl}',
		'<iframe id="{id}-iframeEl" name="{iframeName}" frameBorder="0" data-nusa-custom-container-type="Frame_Type" {iframeAttrTpl}',
		' src="{iframeSrc}" class="{iframeCls}"></iframe>',
		'{afterIFrameTpl}',
		{
			disableFormats: true
		}
	],

	getDocMarkup: function() {
		var me = this,
			h = me.iframeEl.getHeight() - me.iframePad * 2,
			oldIE = Ext.isIE8m;

		// - IE9+ require a strict doctype otherwise text outside visible area can't be selected.
		// - Opera inserts <P> tags on Return key, so P margins must be removed to avoid double line-height.
		// - On browsers other than IE, the font is not inherited by the IFRAME so it must be specified.
		return Ext.String.format(
				(oldIE ? '' : '<!DOCTYPE html>')
				+ '<html><head><style type="text/css">'
				+ (Ext.isOpera ? 'p{margin:0}' : '')
				+ 'body{border:0;margin:0;padding:{0}px;direction:' + (me.rtl ? 'rtl;' : 'ltr;')
				+ (oldIE ? Ext.emptyString : 'min-')
				+ 'height:{1}px;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:text;background-color:white;'
				+ (Ext.isIE ? '' : 'font-size:12px;font-family:{2}')
				+ '}</style></head><body contentEditable="true" ></body></html>'
			, me.iframePad, h, me.defaultFont);
	},

	/**
	 * @overriden method
	 * Initialize the events
	 */
	initEvents: function(){
		var me = this;

		me.callParent(arguments);

		me.on({
			scope: me,
			initialize: me.onInitializeHtmlEditor
		});
	},

	/**
	 * Attach the custom events
	 */
	onInitializeHtmlEditor: function(){
		var me = this,
			frameWin = me.getWin(),
			fnBlur = Ext.bind(me.onHtmlEditorBlur, me),
			fnFocus = Ext.bind(me.onHtmlEditorFocus, me);

		say('onInitializeHtmlEditor');
		say(frameWin);

		if(frameWin.attachEvent){
			frameWin.addEventListener('blur', fnBlur);
			frameWin.addEventListener('focus', fnFocus);
		}
		else{
			frameWin.addEventListener('blur', fnBlur, false);
			frameWin.addEventListener('focus', fnFocus, false);
		}
	},

	/**
	 * Method which will fire the event "blur"
	 */
	onHtmlEditorBlur: function(event){
		this.fireEvent('blur', this);
	},

	/**
	 * Method which will fire the event "blur"
	 */
	onHtmlEditorFocus: function(event){
		this.fireEvent('focus', this);
	}
});