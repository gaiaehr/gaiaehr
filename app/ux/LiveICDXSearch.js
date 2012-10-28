/*
 GaiaEHR (Electronic Health Records)
 LiveICDXSearch.js
 UX
 Copyright (C) 2012 Ernesto Rodriguez

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
Ext.define('App.ux.LiveICDXSearch',
{
	extend : 'Ext.form.field.ComboBox',
	alias : 'widget.liveicdxsearch',
	hideLabel : true,
	triggerTip : i18n('click_to_clear_selection'),
	spObj : '',
	spForm : '',
	spExtraParam : '',
	qtip : i18n('clearable_combo_box'),
	trigger1Class : 'x-form-select-trigger',
	trigger2Class : 'x-form-clear-trigger',
	initComponent : function()
	{
		var me = this;

		Ext.define('liveICDXSearchModel',
		{
			extend : 'Ext.data.Model',
			fields : [
			{
				name : 'id',
				type : 'int'
			},
			{
				name : 'code',
				type : 'string'
			},
			{
				name : 'xcode',
				type : 'string'
			},
			{
				name : 'code_text',
				type : 'string'
			},
			{
				name : 'code_type',
				type : 'string'
			}],
			proxy :
			{
				type : 'direct',
				api :
				{
					read : DiagnosisCodes.ICDCodeSearch
				},
				reader :
				{
					totalProperty : 'totals',
					root : 'rows'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store',
		{
			model : 'liveICDXSearchModel',
			pageSize : 25,
			autoLoad : false
		});

		Ext.apply(this,
		{
			store : me.store,
			displayField : 'code_text',
			valueField : 'code',
			emptyText : me.emptyText,
			typeAhead : true,
			minChars : 1,
			anchor : '100%',
			listConfig :
			{
				loadingText : i18n('searching') + '...',
				//emptyText	: 'No matching posts found.',
				//---------------------------------------------------------------------
				// Custom rendering template for each item
				//---------------------------------------------------------------------
				getInnerTpl : function()
				{
					return '<div class="search-item">{code_type} <span style="font-weight: bold;">{code}</span>: {code_text}</div>';
				}
			},
			pageSize : 10
			//            listeners:{
			//                scope:me,
			//                select:me.codeSelected
			//            }
		}, null);

		me.callParent();
	},

	//    codeSelected:function(combo, record){
	//        var value = record[0].data.code;
	//        if(this.oldValue){
	//            combo.setRawValue(this.oldValue + value + ', ');
	//        }else{
	//            combo.setRawValue(value + ', ');
	//        }
	//        this.oldValue = combo.getRawValue();
	//    },

	onRender : function(ct, position)
	{
		this.callParent(arguments);
		var id = this.getId();
		this.triggerConfig =
		{
			tag : 'div',
			cls : 'x-form-twin-triggers',
			style : 'display:block;',
			cn : [
			{
				tag : "img",
				style : Ext.isIE ? 'margin-left:0;height:21px' : '',
				src : Ext.BLANK_IMAGE_URL,
				id : "trigger2" + id,
				name : "trigger2" + id,
				cls : "x-form-trigger " + this.trigger2Class
			}]
		};
		this.triggerEl.replaceWith(this.triggerConfig);
		this.triggerEl.on('mouseup', function(e)
		{
			if (e.target.name == "trigger2" + id)
			{
				this.reset();
				this.oldValue = null;
				if (this.spObj !== '' && this.spExtraParam !== '')
				{
					Ext.getCmp(this.spObj).store.setExtraParam(this.spExtraParam, '');
					Ext.getCmp(this.spObj).store.load()
				}
				if (this.spForm !== '')
				{
					Ext.getCmp(this.spForm).getForm().reset();
				}
			}
		}, this);
		var trigger2 = Ext.get("trigger2" + id);
		trigger2.addClsOnOver('x-form-trigger-over');
	}
}); 