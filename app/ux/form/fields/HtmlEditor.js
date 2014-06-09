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