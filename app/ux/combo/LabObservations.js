/**
 * Created by JetBrains PhpStorm.
 * User: ernesto
 * Date: 6/27/11
 * Time: 8:43 AM
 * To change this template use File | Settings | File Templates.
 *
 *
 * @namespace Patient.patientLiveSearch
 */
Ext.define('App.ux.combo.LabObservations', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.labobservationscombo',
	initComponent: function() {
		var me = this;

		Ext.define('labObservationsComboModel', {
			extend: 'Ext.data.Model',
			fields: [
              		{name: 'label' },
              		{name: 'name' },
              		{name: 'unit' },
              		{name: 'range_start' },
              		{name: 'range_end' },
              		{name: 'threshold' },
              		{name: 'notes' }
			],
			proxy : {
				type  : 'direct',
				api   : {
					read: 'Services.getAllLabObservations'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'labObservationsComboModel',
			autoLoad: false
		});

		Ext.apply(this, {
			store       : me.store,
			displayField: 'label',
			valueField  : 'id',
			emptyText   : _('select_existing_observation'),
            editable    : false,
            width: 810,
			listConfig  : {
				getInnerTpl: function() {
					return '<div>' +
                        '<span style="width:200px;display:inline-block;"><span style="font-weight:bold;">' + _('Label') + ':</span> {label},</span>' +
                        '<span style="width:90px;display:inline-block;"><span style="font-weight:bold;">' + _('unit') + ':</span> {unit},</span>' +
                        '<span style="width:150px;display:inline-block;"><span style="font-weight:bold;">' + _('range_start') + ':</span> {range_start},</span>' +
                        '<span style="width:130px;display:inline-block;"><span style="font-weight:bold;">' + _('range_end') + ':</span> {range_end},</span>' +
                        '<span style="width:100px;display:inline-block;"><span style="font-weight:bold;">' + _('threshold') + ':</span> {threshold}</span>' +
                        '</div>';
				}
			}
		}, null);

		me.callParent();
	}

});