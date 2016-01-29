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
Ext.define('App.ux.LiveUserSearch', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.userlivetsearch',
	hideLabel: true,
	displayField: 'fullname',
	valueField: 'id',
	emptyText: _('search_for_a_user') + '...',
	maxLength: 40,
	typeAhead: false,
    queryMode: 'remote',
    allowBlank: true,
    minChars: 1,
	queryDelay: 200,
	acl: null,

    triggerTip: _('click_to_clear_selection'),
    spObj: '',
    spForm: '',
    spExtraParam: '',
    qtip: _('clearable_combo_box'),
    trigger1Class: 'x-form-select-trigger',
    trigger2Class: 'x-form-clear-trigger',

    listConfig: {
        loadingText: _('searching') + '...',
        getInnerTpl: function(){
            return '<div class="search-item">{fullname} <b>({role})</b></div>'
        }
    },

	initComponent: function(){
		var me = this;

		Ext.define('userLiveSearchModel', {
			extend: 'Ext.data.Model',
			fields: [
				{
					name: 'id',
					type: 'int'
				},
				{
					name: 'title',
					type: 'string'
				},
				{
					name: 'role',
					type: 'string'
				},
				{
					name: 'fname',
					type: 'string'
				},
				{
					name: 'mname',
					type: 'string'
				},
				{
					name: 'lname',
					type: 'string'
				},
				{
					name: 'fullname',
					type: 'string',
					convert: function(v, record){
						return record.data.fname + ' ' + record.data.mname + ' ' + record.data.lname
					}
				}
			]
		});

		me.store = Ext.create('Ext.data.Store', {
			model: 'userLiveSearchModel',
			pageSize: 10,
			autoLoad: false,
			proxy: {
				type: 'direct',
				api: {
					read: 'User.userLiveSearch'
				},
				extraParams: {
					acl: me.acl
				},
				reader: {
					root: 'data'
				},
                writer: {
                    writeAllFields: true
                }
			}
		});

		Ext.apply(me, {
			store: me.store,
			pageSize: 10
		});

		me.callParent();
	},

    onRender: function(ct, position){
        var id = this.getId();
        var trigger2;
        this.callParent(arguments);
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
                this.fireEvent('reset', this);
            }
        }, this);
        trigger2 = Ext.get("trigger2" + id);
        trigger2.addClsOnOver('x-form-trigger-over');
    }

});
