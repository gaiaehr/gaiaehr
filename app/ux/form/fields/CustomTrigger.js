Ext.define('App.ux.form.fields.CustomTrigger', {
	extend: 'Ext.form.field.Trigger',
	alias: 'widget.customtrigger',
	hideLabel    : true,
	triggerTip: i18n('click_to_clear_selection'),
	qtip: i18n('clearable_combo_box'),
	trigger1Class:'x-form-select-trigger',
	trigger2Class:'x-form-clear-trigger',

	onRender:function (ct, position) {
		this.callParent(arguments);
		var id = this.getId();

		this.triggerConfig = {
			tag:'div', cls:'x-form-twin-triggers', style:'display:block;', cn:[
				{tag:"img", style:Ext.isIE ? 'margin-left:0;height:21px' : '', src:Ext.BLANK_IMAGE_URL, id:"trigger2" + id, name:"trigger2" + id, cls:"x-form-trigger " + this.trigger2Class}
			]};
		this.triggerEl.replaceWith(this.triggerConfig);
		this.triggerEl.on('mouseup', function() {
			this.destroy();
		}, this);
		var trigger2 = Ext.get("trigger2" + id);
		trigger2.addClsOnOver('x-form-trigger-over');
	}
});