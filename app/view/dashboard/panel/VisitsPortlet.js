/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, LLC.

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

Ext.define('App.view.dashboard.panel.VisitsPortlet', {
    extend: 'Ext.panel.Panel',
    initComponent: function() {
        Ext.apply(this, {
            layout: 'fit',
            width : 300,
            height: 250,
            items : {
                xtype  : 'chart',
                animate: false,
                shadow : false,
                store  : Ext.create('App.store.patient.Encounters'),
                axes   : [
                    {
                        type    : 'Numeric',
                        position: 'left',
                        fields  : ['djia'],
                        title   : 'Visits',
                        label   : {
                            font: '11px Arial'
                        }
                    },
                    {
                        type    : 'Numeric',
                        position: 'bottom',
                        grid    : false,
                        fields  : ['sp500'],
                        title   : 'Day',
                        label   : {
                            font: '11px Arial'
                        }
                    }
                ],
                series : [
                    {
                        type: 'column',
                        axis: 'left',
                        highlight: true,
                        tips: {
                          trackMouse: true,
                          width: 140,
                          height: 28,
                          renderer: function(storeItem, item) {
                            this.setTitle(storeItem.get('name') + ': ' + storeItem.get('data1') + ' $');
                          }
                        },
                        label: {
                          display: 'insideEnd',
                          'text-anchor': 'middle',
                            field: 'data1',
                            renderer: Ext.util.Format.numberRenderer('0'),
                            orientation: 'vertical',
                            color: '#333'
                        },
                        xField: 'name',
                        yField: 'data1'
                    }
                ]
            }
        }, null);

        this.callParent(arguments);
    }
});
