/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
Ext.define('App.ux.combo.ComboResettable', {
	extend: 'Ext.form.ComboBox',
	triggerTip: _('click_to_clear_selection'),
	spObj: '',
	spForm: '',
	spExtraParam: '',
	qtip: _('clearable_combo_box'),

	trigger1Class: 'x-form-select-trigger',
	trigger2Class: 'x-form-clear-trigger',

	onRender: function(ct, position){
		this.callParent(arguments);
		var id = this.getId();
		this.triggerConfig = {
			tag: 'div',
			cls: 'x-form-twin-triggers',
			style: 'display:block;',
			cn: [
				{
					tag: "img",
					style: Ext.isIE ? 'margin-left:0;height:21px' : '',
					src: Ext.BLANK_IMAGE_URL,
					id: "trigger2" + id,
					name: "trigger2" + id,
					cls: "x-form-trigger " + this.trigger2Class
				}
			]
		};
		this.triggerEl.replaceWith(this.triggerConfig);

		this.triggerEl.on('mouseup', function(e){
			if(e.target.name == "trigger2" + id){
				this.reset();
				this.oldValue = null;
				if(this.spObj !== '' && this.spExtraParam !== ''){
					Ext.getCmp(this.spObj).store.setExtraParam(this.spExtraParam, '');
					Ext.getCmp(this.spObj).store.load()
				}
				if(this.spForm !== ''){
					Ext.getCmp(this.spForm).getForm().reset();
				}
				this.fireEvent('fieldreset', this);
			}

		}, this);

		var trigger2 = Ext.get("trigger2" + id);
		trigger2.addClsOnOver('x-form-trigger-over');
	}
}); 