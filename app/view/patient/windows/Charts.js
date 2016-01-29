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

Ext.define('App.view.patient.windows.Charts', {
    extend       : 'Ext.window.Window',
    requires     : [
        'App.store.patient.Vitals'
    ],
    title        : _('vector_charts'),
    layout       : 'card',
    closeAction  : 'hide',
    modal        : true,
    width        : window.innerWidth - 200,
    height       : window.innerHeight - 200,
    maximizable  : true,
    //maximized  : true,
    initComponent: function() {
        var me = this;

        me.vitalsStore = Ext.create('App.store.patient.Vitals');
        me.graphStore = Ext.create('App.store.patient.VectorGraph');

        me.WeightForAgeInfStore = Ext.create('App.store.patient.charts.WeightForAgeInf');
        me.LengthForAgeInfStore = Ext.create('App.store.patient.charts.LengthForAgeInf');
        me.WeightForRecumbentInfStore = Ext.create('App.store.patient.charts.WeightForRecumbentInf');
        me.HeadCircumferenceInfStore = Ext.create('App.store.patient.charts.HeadCircumferenceInf');
        me.WeightForStatureStore = Ext.create('App.store.patient.charts.WeightForStature');
        me.WeightForAgeStore = Ext.create('App.store.patient.charts.WeightForAge');
        me.StatureForAgeStore = Ext.create('App.store.patient.charts.StatureForAge');
        me.BMIForAgeStore = Ext.create('App.store.patient.charts.BMIForAge');

        me.tbar = ['->', {
            text        : _('bp_pulse_temp'),
            action      : 'bpPulseTemp',
            pressed     : true,
            enableToggle: true,
            toggleGroup : 'charts',
            scope       : me,
            handler     : me.onChartSwitch
        },'-',{
            text        : _('weight_for_age'),
            action      : 'WeightForAgeInf',
            enableToggle: true,
            toggleGroup : 'charts',
            scope       : me,
            handler     : me.onChartSwitch
        },'-',{
            text        : _('length_for_age'),
            action      : 'LengthForAgeInf',
            enableToggle: true,
            toggleGroup : 'charts',
            scope       : me,
            handler     : me.onChartSwitch
        },'-',{
            text        : _('weight_for_recumbent'),
            action      : 'WeightForRecumbentInf',
            enableToggle: true,
            toggleGroup : 'charts',
            scope       : me,
            handler     : me.onChartSwitch
        },'-',{
            text        : _('head_circumference'),
            action      : 'HeadCircumferenceInf',
            enableToggle: true,
            toggleGroup : 'charts',
            scope       : me,
            handler     : me.onChartSwitch
        },'-',{
            text        : _('weight_for_stature'),
            action      : 'WeightForStature',
            enableToggle: true,
            toggleGroup : 'charts',
            scope       : me,
            handler     : me.onChartSwitch
        },'-',{
            text        : _('weight_for_age'),
            action      : 'WeightForAge',
            enableToggle: true,
            toggleGroup : 'charts',
            scope       : me,
            handler     : me.onChartSwitch
        },'-',{
            text        : _('stature_for_age'),
            action      : 'StatureForAge',
            enableToggle: true,
            toggleGroup : 'charts',
            scope       : me,
            handler     : me.onChartSwitch
        },'-',{
            text        : _('bmi_for_age'),
            action      : 'BMIForAge',
            enableToggle: true,
            toggleGroup : 'charts',
            scope       : me,
            handler     : me.onChartSwitch
        },'-'];

        me.tools = [
            {
                type   : 'print',
                tooltip: _('print_chart'),
                handler: function() {
                    console.log(this.up('window').down('chart'));
                }
            }
        ];

        me.items = [
            Ext.create('App.view.patient.charts.BPPulseTemp', {
                store: me.vitalsStore
            }),

            me.WeightForAgeInf = Ext.create('App.view.patient.charts.HeadCircumference', {
                title   : _('weight_for_age_0_3_mos'),
                xTitle  : _('weight_kg'),
                yTitle  : _('age_months'),
                xMinimum: 1,
                xMaximum: 19,
                yMinimum: 0,
                yMaximum: 36,
                store   : me.WeightForAgeInfStore
            }),

            me.LengthForAgeInf = Ext.create('App.view.patient.charts.HeadCircumference', {
                title   : _('length_for_age_0_3_mos'),
                xTitle  : _('length_cm'),
                yTitle  : _('age_months'),
                xMinimum: 40,
                xMaximum: 110,
                yMinimum: 0,
                yMaximum: 36,
                store   : me.LengthForAgeInfStore
            }),

            me.WeightForRecumbentInf = Ext.create('App.view.patient.charts.HeadCircumference', {
                title   : _('weight_for_recumbent_0_3_mos'),
                xTitle  : _('weight_kg'),
                yTitle  : _('length_cm'),
                xMinimum: 1,
                xMaximum: 20,
                yMinimum: 45,
                yMaximum: 103.5,
                store   : me.WeightForRecumbentInfStore
            }),

            me.HeadCircumferenceInf = Ext.create('App.view.patient.charts.HeadCircumference', {
                title   : _('head_circumference_0_3_mos'),
                xTitle  : _('circumference_cm'),
                yTitle  : _('age_months'),
                xMinimum: 30,
                xMaximum: 55,
                yMinimum: 0,
                yMaximum: 36,
                store   : me.HeadCircumferenceInfStore
            }),

            me.WeightForStature = Ext.create('App.view.patient.charts.HeightForStature', {
////	            title   : _('weight_for_age_2_20_years'),
////	            xTitle  : _('weight_kg'),
////	            yTitle  : _('age_years'),
//	            xMinimum: 7,
//	            xMaximum: 30,
//	            yMinimum: 76,
//	            yMaximum: 122,
                store: me.WeightForStatureStore
            }),

            me.WeightForAge = Ext.create('App.view.patient.charts.HeadCircumference', {
                title   : _('weight_for_age_2_20_years'),
                xTitle  : _('weight_kg'),
                yTitle  : _('age_years'),
                xMinimum: 10,
                xMaximum: 110,
                yMinimum: 2,
                yMaximum: 20,
                store   : me.WeightForAgeStore
            }),

            me.StatureForAge = Ext.create('App.view.patient.charts.HeadCircumference', {
                title   : _('stature_for_age_2_20_years'),
                xTitle  : _('stature_cm'),
                yTitle  : _('age_years'),
                xMinimum: 60,
                xMaximum: 200,
                yMinimum: 2,
                yMaximum: 20,
                store   : me.StatureForAgeStore
            }),

            me.BMIForAge = Ext.create('App.view.patient.charts.HeadCircumference', {
                title   : _('bmi_for_age_2_20_years'),
                xTitle  : _('bmi'),
                yTitle  : _('age_years'),
                xMinimum: 10,
                xMaximum: 35,
                yMinimum: 2,
                yMaximum: 20,
                store   : me.BMIForAgeStore
            })
        ];

        me.listeners = {
            scope: me,
            show : me.onWinShow
        };

        me.callParent(arguments);
    },

    onWinShow: function() {
        var me = this,
	        layout = me.getLayout(),
	        btns = me.down('toolbar').items.items,
	        btn;
        layout.setActiveItem(0);

	    me.vitalsStore.load({params: {pid: app.patient.pid}});

        for(var i = 0; i < btns.length; i++) {
            btn = btns[i];
            if(btn.type == 'button' && (
                btn.action == 'WeightForAgeInf' || btn.action == 'LengthForAgeInf' || btn.action == 'WeightForRecumbentInf' || btn.action == 'HeadCircumferenceInf')) {
                btn.setVisible(app.patient.age.DMY.years < 2);
	            btns[i + 1].setVisible(app.patient.age.DMY.years < 2);
            } else if(btn.type == 'button') {
                btn.setVisible(app.patient.age.DMY.years >= 2);
	            btns[i + 1].setVisible(app.patient.age.DMY.years >= 2);
            }
        }
    },

    onChartSwitch: function(btn) {
        var me = this, layout = me.getLayout(), card, chart, x, y;
        if(btn.action == 'bpPulseTemp') {
            layout.setActiveItem(0);
        } else if(btn.action == 'WeightForAgeInf') {
            layout.setActiveItem(1);
            me.WeightForAgeInfStore.load({params: {pid: app.patient.pid}});
        } else if(btn.action == 'LengthForAgeInf') {
            layout.setActiveItem(2);
            me.LengthForAgeInfStore.load({params: {pid: app.patient.pid}});
        } else if(btn.action == 'WeightForRecumbentInf') {
            layout.setActiveItem(3);
            me.WeightForRecumbentInfStore.load({params: {pid: app.patient.pid}});
        } else if(btn.action == 'HeadCircumferenceInf') {
            layout.setActiveItem(4);
            me.HeadCircumferenceInfStore.load({params: {pid: app.patient.pid}});
        } else if(btn.action == 'WeightForStature') {
            layout.setActiveItem(5);
            me.WeightForStatureStore.load({params: {pid: app.patient.pid}});
        } else if(btn.action == 'WeightForAge') {
            layout.setActiveItem(6);
            me.WeightForAgeStore.load({params: {pid: app.patient.pid}});
        } else if(btn.action == 'StatureForAge') {
            layout.setActiveItem(7);
            me.StatureForAgeStore.load({params: {pid: app.patient.pid}});
        } else if(btn.action == 'BMIForAge') {
            layout.setActiveItem(8);
            me.BMIForAgeStore.load({params: {pid: app.patient.pid}});
        }
    }
});
