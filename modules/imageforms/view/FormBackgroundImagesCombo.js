/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, inc.

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

Ext.define('Modules.imageforms.view.FormBackgroundImagesCombo', {
	extend: 'Ext.form.ComboBox',
	initComponent: function() {
		var me = this;

		Ext.define('FormImagesBackgroundImagesModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'name', type: 'string' },
				{name: 'value', type: 'string' }
			]
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'FormImagesBackgroundImagesModel',
            data: [
                {
                    name:"Body Full",
                    value:"modules/imageforms/resources/images/body_outline.png"
                },
                {
                    name:"Breast Front",
                    value:"modules/imageforms/resources/images/breast_front.png"
                },
                {
                    name:"Face",
                    value:"modules/imageforms/resources/images/face.png"
                },
                {
                    name:"Feet bottom",
                    value:"modules/imageforms/resources/images/feet_bottom.png"
                },
                {
                    name:"Hand",
                    value:"modules/imageforms/resources/images/hand.png"
                },
                {
                    name:"Hand Full",
                    value:"modules/imageforms/resources/images/hands_full.png"
                },
                {
                    name:"Head",
                    value:"modules/imageforms/resources/images/head.png"
                }
            ]
		});

		Ext.apply(this, {
			editable    : false,
			queryMode   : 'local',
			displayField: 'name',
			valueField  : 'value',
			emptyText   : i18n('select'),
			store       : me.store
		}, null);
		me.callParent();
	}
}); 