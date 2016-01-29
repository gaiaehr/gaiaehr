/**
 * Created with JetBrains PhpStorm.
 * User: ernesto
 * Date: 11/19/12
 * Time: 10:31 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.PatientChartsPanel', {
    extend: 'Ext.Panel',
    xtype: 'patientchartspanel',
    requires: [
        'Ext.chart.*'
    ],
    config: {
        scrollable:true,
        nav: 'medicalrecordnav',
        tier: 4,
        layout: {
            type: 'vbox',
            align: 'stretch'
        },
        defaults: { flex: 1 },
        items: [
            {
                xtype: 'chart',
                animate: true,
                store: {
                    fields: ['date', 'CBP', 'DBP'],
                    data: [
                        {'date': new Date('Jan 1 2010').getTime(), 'CBP': 120, 'high': 120, 'low': 80, 'DBP': 80},
                        {'date': new Date('Jan 2 2010').getTime(), 'CBP': 125, 'high': 125, 'low': 85, 'DBP': 85},
                        {'date': new Date('Jan 3 2010').getTime(), 'CBP': 119, 'high': 119, 'low': 82, 'DBP': 82}
                    ]
                },
                axes: [
                    {
                        type: 'numeric',
                        position: 'left',
                        fields: ['CBP', 'DBP'],
                        title: {
                            text: 'CBP / DBP',
                            fontSize: 15
                        },
                        grid: true
                    },
                    {
                        type: 'time',
                        position: 'bottom',
                        fields: ['date'],
                        fromDate: new Date('Dec 31 2009'),
                        toDate: new Date('Jan 6 2010'),
                        title: {
                            text: 'Date',
                            fontSize: 15
                        },
                        style: {
                            axisLine: false
                        }
                    }
                ],
                series: [
                    {
                        type: 'candlestick',
                        xField: 'date',
                        openField: 'CBP',
                        highField: 'CBP',
                        lowField: 'DBP',
                        closeField: 'DBP',
                        style: {
                            dropStyle: {
                                fill: 'rgb(237, 123, 43)',
                                stroke: 'rgb(237, 123, 43)'
                            },
                            raiseStyle: {
                                fill: 'rgb(55, 153, 19)',
                                stroke: 'rgb(55, 153, 19)'
                            }
                        },
                        aggregator: {
                            strategy: 'time'
                        }
                    }
                ]
            },
            {
                xtype: 'chart',
                animate: true,
                store: {
                    fields: ['date', 'CBP', 'DBP'],
                    data: [
                        {'date': new Date('Jan 1 2010').getTime(), 'CBP': 120, 'high': 120, 'low': 80, 'DBP': 80},
                        {'date': new Date('Jan 2 2010').getTime(), 'CBP': 125, 'high': 125, 'low': 85, 'DBP': 85},
                        {'date': new Date('Jan 3 2010').getTime(), 'CBP': 119, 'high': 119, 'low': 82, 'DBP': 82}
                    ]
                },
                axes: [
                    {
                        type: 'numeric',
                        position: 'left',
                        fields: ['CBP', 'DBP'],
                        title: {
                            text: 'CBP / DBP',
                            fontSize: 15
                        },
                        grid: true
                    },
                    {
                        type: 'time',
                        position: 'bottom',
                        fields: ['date'],
                        fromDate: new Date('Dec 31 2009'),
                        toDate: new Date('Jan 6 2010'),
                        title: {
                            text: 'Date',
                            fontSize: 15
                        },
                        style: {
                            axisLine: false
                        }
                    }
                ],
                series: [
                    {
                        type: 'candlestick',
                        xField: 'date',
                        openField: 'CBP',
                        highField: 'CBP',
                        lowField: 'DBP',
                        closeField: 'DBP',
                        style: {
                            dropStyle: {
                                fill: 'rgb(237, 123, 43)',
                                stroke: 'rgb(237, 123, 43)'
                            },
                            raiseStyle: {
                                fill: 'rgb(55, 153, 19)',
                                stroke: 'rgb(55, 153, 19)'
                            }
                        },
                        aggregator: {
                            strategy: 'time'
                        }
                    }
                ]
            },
            {
                xtype: 'chart',
                animate: true,
                store: {
                    fields: ['date', 'CBP', 'DBP'],
                    data: [
                        {'date': new Date('Jan 1 2010').getTime(), 'CBP': 120, 'high': 120, 'low': 80, 'DBP': 80},
                        {'date': new Date('Jan 2 2010').getTime(), 'CBP': 125, 'high': 125, 'low': 85, 'DBP': 85},
                        {'date': new Date('Jan 3 2010').getTime(), 'CBP': 119, 'high': 119, 'low': 82, 'DBP': 82}
                    ]
                },
                axes: [
                    {
                        type: 'numeric',
                        position: 'left',
                        fields: ['CBP', 'DBP'],
                        title: {
                            text: 'CBP / DBP',
                            fontSize: 15
                        },
                        grid: true
                    },
                    {
                        type: 'time',
                        position: 'bottom',
                        fields: ['date'],
                        fromDate: new Date('Dec 31 2009'),
                        toDate: new Date('Jan 6 2010'),
                        title: {
                            text: 'Date',
                            fontSize: 15
                        },
                        style: {
                            axisLine: false
                        }
                    }
                ],
                series: [
                    {
                        type: 'candlestick',
                        xField: 'date',
                        openField: 'CBP',
                        highField: 'CBP',
                        lowField: 'DBP',
                        closeField: 'DBP',
                        style: {
                            dropStyle: {
                                fill: 'rgb(237, 123, 43)',
                                stroke: 'rgb(237, 123, 43)'
                            },
                            raiseStyle: {
                                fill: 'rgb(55, 153, 19)',
                                stroke: 'rgb(55, 153, 19)'
                            }
                        },
                        aggregator: {
                            strategy: 'time'
                        }
                    }
                ]
            }
        ]
    }
});