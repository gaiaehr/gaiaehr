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