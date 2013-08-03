/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, inc.
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

Ext.define('App.view.patient.windows.Medical', {
    extend:'App.ux.window.Window',
    title:i18n('medical_window'),
    id:'MedicalWindow',
    layout:'card',
    closeAction:'hide',
    height:750,
    width:1200,
    bodyStyle:'background-color:#fff',
    modal:true,
    defaults:{
        margin:5
    },
    requires:['App.view.patient.LaboratoryResults'],
    pid:null,
    initComponent:function(){
        var me = this;
        /*****************************************************************************
         * STORES
         *****************************************************************************/
        me.patientImmuListStore = Ext.create('App.store.patient.PatientImmunization', {
            groupField:'immunization_name',
            sorters:['immunization_name', 'administered_date'],
            listeners:{
                scope:me,
                beforesync:me.setDefaults
            },
            autoSync:false
        });
        me.patientAllergiesListStore = Ext.create('App.store.patient.Allergies', {
            listeners:{
                scope:me,
                beforesync:me.setDefaults
            },
            autoSync:false
        });
        me.patientMedicalIssuesStore = Ext.create('App.store.patient.MedicalIssues', {
            listeners:{
                scope:me,
                beforesync:me.setDefaults
            },
            autoSync:false
        });
//        me.patientSurgeryStore = Ext.create('App.store.patient.Surgery', {
//            listeners:{
//                scope:me,
//                beforesync:me.setDefaults
//            },
//            autoSync:false
//        });
//        me.patientDentalStore = Ext.create('App.store.patient.Dental', {
//            listeners:{
//                scope:me,
//                beforesync:me.setDefaults
//            },
//            autoSync:false
//        });
        me.patientMedicationsStore = Ext.create('App.store.patient.Medications', {
            listeners:{
                scope:me,
                beforesync:me.setDefaults
            },
            autoSync:false
        });
        me.labPanelsStore = Ext.create('App.store.patient.LaboratoryTypes', {
            autoSync:true
        });

        me.items = [
            /**
             * Immunization Panel
             */
            {
                xtype:'grid',
                action:'patientImmuListGrid',
                itemId:'patientImmuListGrid',
                store:me.patientImmuListStore,
                features:Ext.create('Ext.grid.feature.Grouping', {
                    groupHeaderTpl:i18n('immunization') + ': {name} ({rows.length} Item{[values.rows.length > 1 ? "s" : ""]})',
                    hideGroupedHeader:true
                }),
                columns:[
                    {
                        header:i18n('immunization_name'),
                        width:100,
                        dataIndex:'immunization_name'
                    },
                    {
                        xtype:'datecolumn',
                        header:i18n('date'),
                        format:'Y-m-d',
                        width:100,
                        dataIndex:'administered_date'
                    },
                    {
                        header:i18n('lot_number'),
                        width:100,
                        dataIndex:'lot_number'
                    },
                    {
                        header:i18n('notes'),
                        flex:1,
                        dataIndex:'note'
                    },
                    {
                        header:i18n('administered_by'),
                        width:150,
                        dataIndex:'administered_by'
                    }
                ],
                plugins:Ext.create('App.ux.grid.RowFormEditing', {
                    autoCancel:false,
                    errorSummary:false,
                    clicksToEdit:1,
                    formItems:[
                        {

                            title:'general',
                            xtype:'container',
                            layout:'vbox',
                            items:[
                                {
                                    /**
                                     * Line one
                                     */
                                    xtype:'fieldcontainer',
                                    layout:'hbox',
                                    itemId:'line1',
                                    defaults:{
                                        margin:'0 10 0 0',
                                        xtype:'textfield'
                                    },
                                    items:[
                                        {
                                            xtype:'immunizationlivesearch',
                                            fieldLabel:i18n('name'),
                                            hideLabel:false,
                                            allowBlank:false,
                                            itemId:'immunization_id',
                                            name:'immunization_id',
                                            enableKeyEvents:true,
                                            action:'immunization_id',
                                            width:570,
                                            listeners:{
                                                scope:me,
                                                select:me.onLiveSearchSelect
                                            }
                                        },
                                        {
                                            xtype:'textfield',
                                            fieldLabel:i18n('name'),
                                            hidden:true,
                                            editable:false,
                                            width:570,
                                            name:'immunization_name',
                                            itemId:'immunization_name',
                                            action:'immunization_name'
                                        },
                                        {
                                            fieldLabel:i18n('administrator'),
                                            name:'administered_by',
                                            width:295,
                                            labelWidth:160

                                        }
                                    ]

                                },
                                {
                                    /**
                                     * Line two
                                     */
                                    xtype:'fieldcontainer',
                                    layout:'hbox',
                                    defaults:{
                                        margin:'0 10 0 0',
                                        xtype:'textfield'
                                    },
                                    items:[
                                        {
                                            fieldLabel:i18n('lot_number'),
                                            xtype:'textfield',
                                            width:300,
                                            name:'lot_number'

                                        },
                                        {

                                            xtype:'numberfield',
                                            fieldLabel:i18n('dosis_number'),
                                            width:260,
                                            name:'dosis'
                                        },
                                        {
                                            fieldLabel:i18n('info_statement_given'),
                                            width:295,
                                            labelWidth:160,
                                            xtype:'datefield',
                                            format:'Y-m-d',
                                            name:'education_date'
                                        }
                                    ]

                                },
                                {
                                    /**
                                     * Line three
                                     */
                                    xtype:'fieldcontainer',
                                    layout:'hbox',
                                    defaults:{
                                        margin:'0 10 0 0',
                                        xtype:'textfield'
                                    },
                                    items:[
                                        {
                                            fieldLabel:i18n('notes'),
                                            xtype:'textfield',
                                            width:300,
                                            name:'note'

                                        },
                                        me.CvxMvxCombo = Ext.create('App.ux.combo.CVXManufacturersForCvx',{
                                            fieldLabel:i18n('manufacturer'),
                                            width:260,
                                            name:'manufacturer'
                                        }),
                                        {
                                            fieldLabel:i18n('date_administered'),
                                            width:295,
                                            labelWidth:160,
                                            xtype:'datefield',
                                            format:'Y-m-d',
                                            name:'administered_date'
                                        }
                                    ]

                                }
                            ]

                        }
                    ],
                    listeners:{
                        scope:me,
                        beforeedit:me.beforeImmunizationEdit
                    }
                }),
                bbar:['->', {
                    text:i18n('reviewed'),
                    action:'review',
                    itemId:'review_immunizations',
                    scope:me,
                    handler:me.onReviewed
                }]
            },
            /**
             * Allergies Card panel
             */
            {
                xtype:'grid',
                action:'patientAllergiesListGrid',
                store:me.patientAllergiesListStore,
                columns:[
                    {
                        header:i18n('type'),
                        width:100,
                        dataIndex:'allergy_type'
                    },
                    {
                        header:i18n('name'),
                        width:375,
                        dataIndex:'allergy'
                    },
                    {
                        header:i18n('location'),
                        width:100,
                        dataIndex:'location'
                    },
                    {
                        header:i18n('severity'),
                        flex:1,
                        dataIndex:'severity'
                    },
                    {
                        text:i18n('active'),
                        width:55,
                        dataIndex:'active',
                        renderer:me.boolRenderer
                    }
                ],
                plugins:me.rowEditingAllergies = Ext.create('App.ux.grid.RowFormEditing', {
                    autoCancel:false,
                    errorSummary:false,
                    clicksToEdit:1,
	                listeners:{
		                scope:me,
		                beforeedit:me.beforeAllergyEdit
	                },
                    formItems:[
                        {
                            title:i18n('general'),
                            xtype:'container',
                            padding:'0 10',
                            layout:'vbox',
                            items:[
                                {
                                    /**
                                     * Line one
                                     */
                                    xtype:'fieldcontainer',
                                    layout:'hbox',
                                    defaults:{
                                        margin:'0 10 0 0'
                                    },
                                    items:[
                                        {
                                            xtype:'mitos.allergiestypescombo',
                                            fieldLabel:i18n('type'),
                                            name:'allergy_type',
                                            action:'allergy_type',
                                            allowBlank:false,
                                            width:225,
                                            labelWidth:70,
                                            enableKeyEvents:true,
                                            listeners:{
                                                scope:me,
                                                change:me.onAllergyTypeCahnge
                                            }
                                        },
	                                    me.allergieType = Ext.create('App.ux.combo.Allergies', {
		                                    fieldLabel:i18n('allergy'),
		                                    action:'allergie_name',
		                                    name:'allergy',
		                                    enableKeyEvents:true,
		                                    disabled:true,
		                                    width:550,
		                                    labelWidth:70
	                                    }),
	                                    me.allergieMedication = Ext.widget('rxnormallergylivetsearch', {
		                                    fieldLabel:i18n('allergy'),
		                                    hideLabel:false,
		                                    action:'allergy',
		                                    name:'allergy',
		                                    hidden:true,
		                                    disabled:true,
		                                    enableKeyEvents:true,
		                                    width:550,
		                                    labelWidth:70,
		                                    listeners:{
			                                    scope:me,
			                                    select:me.onLiveSearchSelect
		                                    }
	                                    }),
	                                    {
		                                    fieldLabel:i18n('begin_date'),
		                                    xtype:'datefield',
		                                    format:'Y-m-d',
		                                    name:'begin_date'

	                                    }
                                    ]

                                },
                                {
                                    /**
                                     * Line two
                                     */
                                    xtype:'fieldcontainer',
                                    layout:'hbox',
                                    defaults:{
                                        margin:'0 10 0 0'
                                    },
                                    items:[
	                                    {
		                                    xtype:'mitos.allergieslocationcombo',
		                                    fieldLabel:i18n('location'),
		                                    name:'location',
		                                    action:'location',
		                                    width:225,
		                                    labelWidth:70,
		                                    listeners:{
			                                    scope:me,
			                                    select:me.onLocationSelect
		                                    }

	                                    },
	                                    me.allergiesReaction = Ext.create('App.ux.combo.AllergiesAbdominal', {
		                                    xtype:'mitos.allergiesabdominalcombo',
		                                    fieldLabel:i18n('reaction'),
		                                    name:'reaction',
		                                    width:315,
		                                    labelWidth:70
	                                    }),
	                                    {
		                                    xtype:'mitos.allergiesseveritycombo',
		                                    fieldLabel:i18n('severity'),
		                                    name:'severity',
		                                    width:225,
		                                    labelWidth:70
	                                    },
	                                    {
		                                    fieldLabel:i18n('end_date'),
		                                    xtype:'datefield',
		                                    format:'Y-m-d',
		                                    name:'end_date'
	                                    }
                                    ]
                                }
                            ]
                        }
                    ]
                }),
                bbar:[
	                {
		                text:i18n('only_active'),
		                enableToggle:true,
		                scope:me,
		                toggleHandler:me.onOnlyActiveToggle
	                },
	                '->',
	                {
	                    text:i18n('reviewed'),
	                    action:'review',
	                    itemId:'review_allergies',
	                    scope:me,
	                    handler:me.onReviewed
	                }
                ]
            },
            /**
             * Active Problem Card panel
             */
            {
                xtype:'grid',
                action:'patientMedicalListGrid',
                store:me.patientMedicalIssuesStore,
                columns:[
                    {
                        header:i18n('problem'),
                        flex:1,
                        dataIndex:'code_text'
                    },
                    {
                        xtype:'datecolumn',
                        header:i18n('date_diagnosed'),
                        width:100,
                        format:'Y-m-d',
                        dataIndex:'begin_date'
                    },
                    {
                        xtype:'datecolumn',
                        header:i18n('end_date'),
                        width:100,
                        format:'Y-m-d',
                        dataIndex:'end_date'
                    },
                    {
                        header:i18n('active?'),
                        width:60,
                        dataIndex:'active',
	                    renderer:me.boolRenderer
                    }
                ],
                plugins:Ext.create('App.ux.grid.RowFormEditing', {
                    autoCancel:false,
                    errorSummary:false,
                    clicksToEdit:1,
                    formItems:[
                        {
                            xtype:'container',
                            padding:10,
                            layout:'vbox',
                            items:[
	                            {
		                            xtype:'liveicdxsearch',
		                            fieldLabel:i18n('search'),
		                            name:'code',
		                            hideLabel:false,
		                            itemId:'actiiveproblems',
		                            action:'actiiveproblems',
		                            enableKeyEvents:true,
		                            displayField:'code',
		                            valueField:'code',
		                            width:720,
		                            labelWidth:70,
		                            listeners:{
			                            scope:me,
			                            select:me.onLiveSearchSelect
		                            }
	                            },
                                {
                                    /**
                                     * Line one
                                     */
                                    xtype:'fieldcontainer',
                                    layout:'hbox',
                                    defaults:{
                                        margin:'0 10 0 0'
                                    },
                                    items:[
                                        {
	                                        xtype:'textfield',
	                                        fieldLabel:i18n('problem'),
	                                        width:510,
	                                        labelWidth:70,
	                                        allowBlank:false,
                                            name:'code_text',
                                            action:'code_text'
                                        },
                                        {
                                            fieldLabel:i18n('code_type'),
                                            xtype:'textfield',
                                            width:200,
                                            labelWidth:100,
                                            name:'code_type'

                                        }
                                    ]

                                },
                                {
                                    /**
                                     * Line two
                                     */
                                    xtype:'fieldcontainer',
                                    layout:'hbox',
                                    defaults:{
                                        margin:'0 10 0 0'
                                    },
                                    items:[
                                        {
                                            fieldLabel:i18n('occurrence'),
                                            width:250,
                                            labelWidth:70,
                                            xtype:'mitos.occurrencecombo',
                                            name:'occurrence'

                                        },
                                        {
                                            fieldLabel:i18n('outcome'),
                                            xtype:'mitos.outcome2combo',
                                            width:250,
                                            labelWidth:70,
                                            name:'outcome'

                                        },

	                                    {
		                                    fieldLabel:i18n('date_diagnosed'),
		                                    xtype:'datefield',
		                                    width:200,
		                                    labelWidth:100,
		                                    format:'Y-m-d',
		                                    name:'begin_date'

	                                    }
                                    ]

                                },
                                {
                                    /**
                                     * Line three
                                     */
                                    xtype:'fieldcontainer',
                                    layout:'hbox',
                                    defaults:{
                                        margin:'0 10 0 0'
                                    },
                                    items:[
                                        {
                                            xtype:'textfield',
                                            width:510,
                                            labelWidth:70,
                                            fieldLabel:i18n('referred_by'),
                                            name:'referred_by'
                                        },
	                                    {
		                                    fieldLabel:i18n('end_date'),
		                                    xtype:'datefield',
		                                    width:200,
		                                    labelWidth:100,
		                                    format:'Y-m-d',
		                                    name:'end_date'

	                                    }
                                    ]
                                }
                            ]
                        }
                    ]
                }),
                bbar:['->', {
                    text:i18n('reviewed'),
                    action:'review',
                    itemId:'review_active_problems',
                    scope:me,
                    handler:me.onReviewed
                }]
            },
            /**
             * Surgery Card panel
             */
//            {
//
//
//                xtype:'grid',
//                action:'patientSurgeryListGrid',
//                store:me.patientSurgeryStore,
//                columns:[
//                    {
//                        header:i18n('surgery'),
//                        width:100,
//                        flex:1,
//                        dataIndex:'surgery'
//                    },
//                    {
//                        xtype:'datecolumn',
//                        header:i18n('date'),
//                        width:100,
//                        format:'Y-m-d',
//                        dataIndex:'date'
//                    }
//                ],
//                plugins: Ext.create('App.ux.grid.RowFormEditing', {
//                    autoCancel:false,
//                    errorSummary:false,
//                    clicksToEdit:1,
//                    formItems:[
//                        {
//                            title:i18n('general'),
//                            xtype:'container',
//                            padding:10,
//                            layout:'vbox',
//                            items:[
//                                {
//                                    /**
//                                     * Line one
//                                     */
//                                    xtype:'fieldcontainer',
//                                    layout:'hbox',
//                                    defaults:{
//                                        margin:'0 10 0 0'
//                                    },
//                                    items:[
//                                        {
//                                            fieldLabel:i18n('surgery'),
//                                            name:'surgery_id',
//                                            hideLabel:false,
//                                            allowBlank:false,
//                                            width:510,
//                                            labelWidth:70,
//                                            xtype:'surgerieslivetsearch',
//                                            itemId:'surgery',
//                                            action:'surgery',
//                                            enableKeyEvents:true,
//                                            listeners:{
//                                                scope:me,
//                                                'select':me.onLiveSearchSelect
//                                            }
//                                        },
//                                        {
//                                            xtype:'textfield',
//                                            hidden:true,
//                                            name:'surgery',
//                                            action:'idField'
//                                        },
//                                        {
//                                            fieldLabel:i18n('date'),
//                                            xtype:'datefield',
//                                            width:200,
//                                            labelWidth:80,
//                                            format:'Y-m-d',
//                                            name:'date'
//
//                                        }
//                                    ]
//
//                                },
//                                {
//                                    /**
//                                     * Line two
//                                     */
//                                    xtype:'fieldcontainer',
//                                    layout:'hbox',
//                                    defaults:{
//                                        margin:'0 10 0 0'
//                                    },
//                                    items:[
//                                        {
//                                            fieldLabel:i18n('notes'),
//                                            xtype:'textfield',
//                                            width:510,
//                                            labelWidth:70,
//                                            name:'notes'
//
//                                        },
//                                        {
//                                            fieldLabel:i18n('outcome'),
//                                            xtype:'mitos.outcome2combo',
//                                            width:200,
//                                            labelWidth:80,
//                                            name:'outcome'
//
//                                        }
//                                    ]
//
//                                },
//                                {
//                                    /**
//                                     * Line three
//                                     */
//                                    xtype:'fieldcontainer',
//                                    layout:'hbox',
//                                    defaults:{
//                                        margin:'0 10 0 0'
//                                    },
//                                    items:[
//                                        {
//                                            xtype:'textfield',
//                                            width:510,
//                                            labelWidth:70,
//                                            fieldLabel:i18n('referred_by'),
//                                            name:'referred_by'
//                                        }
//                                    ]
//                                }
//                            ]
//                        }
//                    ]
//                }),
//                bbar:['->', {
//                    text:i18n('reviewed'),
//                    action:'review',
//                    itemId:'review_surgery',
//                    scope:me,
//                    handler:me.onReviewed
//                }]
//            },
            /**
             * Dental Card panel
             */
//            {
//
//
//                xtype:'grid',
//                action:'patientDentalListGrid',
//                store:me.patientDentalStore,
//                columns:[
//                    {
//                        header:i18n('dental'),
//                        width:990,
//                        dataIndex:'description'
//                    },
//                    {
//                        xtype:'datecolumn',
//                        header:i18n('begin_date'),
//                        width:100,
//                        format:'Y-m-d',
//                        dataIndex:'begin_date'
//                    },
//                    {
//                        xtype:'datecolumn',
//                        header:i18n('end_date'),
//                        flex:1,
//                        format:'Y-m-d',
//                        dataIndex:'end_date'
//                    }
//                ],
//                plugins:Ext.create('App.ux.grid.RowFormEditing', {
//                    autoCancel:false,
//                    errorSummary:false,
//                    clicksToEdit:1,
//                    formItems:[
//	                    {
//		                    title:i18n('general'),
//		                    xtype:'container',
//		                    padding:10,
//		                    layout:'vbox',
//		                    items:[
//			                    {
//				                    /**
//				                     * Line one
//				                     */
//				                    xtype:'fieldcontainer',
//				                    layout:'hbox',
//				                    defaults:{
//					                    margin:'0 10 0 0'
//				                    },
//				                    items:[
//					                    {
//						                    fieldLabel:i18n('dental'),
//						                    name:'surgery_id',
//						                    hideLabel:false,
//						                    allowBlank:false,
//						                    width:510,
//						                    labelWidth:70,
//						                    xtype:'cdtlivetsearch',
//						                    itemId:'cdt',
//						                    action:'cdt',
//						                    enableKeyEvents:true,
//						                    listeners:{
//							                    scope:me,
//							                    'select':me.onLiveSearchSelect
//						                    }
//					                    },
//					                    {
//						                    xtype:'textfield',
//						                    hidden:true,
//						                    name:'surgery',
//						                    action:'idField'
//					                    },
//					                    {
//						                    fieldLabel:i18n('date'),
//						                    xtype:'datefield',
//						                    width:200,
//						                    labelWidth:80,
//						                    format:'Y-m-d',
//						                    name:'date'
//
//					                    }
//				                    ]
//
//			                    },
//			                    {
//				                    /**
//				                     * Line two
//				                     */
//				                    xtype:'fieldcontainer',
//				                    layout:'hbox',
//				                    defaults:{
//					                    margin:'0 10 0 0'
//				                    },
//				                    items:[
//					                    {
//						                    fieldLabel:i18n('notes'),
//						                    xtype:'textfield',
//						                    width:510,
//						                    labelWidth:70,
//						                    name:'notes'
//
//					                    },
//					                    {
//						                    fieldLabel:i18n('outcome'),
//						                    xtype:'mitos.outcome2combo',
//						                    width:200,
//						                    labelWidth:80,
//						                    name:'outcome'
//
//					                    }
//				                    ]
//
//			                    },
//			                    {
//				                    /**
//				                     * Line three
//				                     */
//				                    xtype:'fieldcontainer',
//				                    layout:'hbox',
//				                    defaults:{
//					                    margin:'0 10 0 0'
//				                    },
//				                    items:[
//					                    {
//						                    xtype:'textfield',
//						                    width:510,
//						                    labelWidth:70,
//						                    fieldLabel:i18n('referred_by'),
//						                    name:'referred_by'
//					                    }
//				                    ]
//			                    }
//		                    ]
//	                    }
//                    ]
//                }),
//                bbar:['->', {
//                    text:i18n('reviewed'),
//                    action:'review',
//                    itemId:'review_dental',
//                    scope:me,
//                    handler:me.onReviewed
//                }]
//            },
            /**
             * Medications panel
             */
            {


                xtype:'grid',
                action:'patientMedicationsListGrid',
                store:me.patientMedicationsStore,
                columns:[
	                {
		                header:i18n('medication'),
		                flex:1,
		                dataIndex:'STR',
		                editor:{
			                xtype:'rxnormlivetsearch',
			                displayField : 'STR',
			                valueField : 'STR',
			                action: 'medication',
			                listeners:{
				                scope:me,
				                select:me.onLiveSearchSelect
			                }
		                }
	                },
	                {
		                header:i18n('dose'),
		                width:125,
		                dataIndex:'dose',
		                sortable:false,
		                hideable: false,
		                editor:{
			                xtype:'textfield'
		                }
	                },
	                {
		                header:i18n('route'),
		                width:100,
		                dataIndex:'route',
		                sortable:false,
		                hideable: false,
		                editor:{
			                xtype:'mitos.prescriptionhowto'
		                }
	                },
	                {
		                header:i18n('form'),
		                width:125,
		                dataIndex:'form',
		                sortable:false,
		                hideable: false,
		                editor:{
			                xtype:'mitos.prescriptiontypes'
		                }
	                },
	                {
		                header:i18n('instructions'),
		                width:200,
		                dataIndex:'prescription_when',
		                sortable:false,
		                hideable: false,
		                editor:Ext.widget('livesigssearch')
	                },
	                {
		                xtype:'datecolumn',
		                format:globals['date_display_format'],
		                header:i18n('begin_date'),
		                width:100,
		                dataIndex:'begin_date',
		                sortable:false,
		                hideable: false
	                },
	                {
		                header:i18n('end_date'),
		                width:100,
		                dataIndex:'end_date',
		                sortable:false,
		                hideable: false,
		                editor:{
			                xtype:'datefield',
			                format:globals['date_display_format']
		                }
	                },
	                {
		                header:i18n('active?'),
		                width:60,
		                dataIndex:'active',
		                renderer:me.boolRenderer
	                }
                ],
                plugins:Ext.create('Ext.grid.plugin.RowEditing', {
                    autoCancel:false,
                    errorSummary:false,
                    clicksToEdit:2
                }),
                bbar:['->', {
                    text:i18n('reviewed'),
                    action:'review',
                    itemId:'review_medications',
                    scope:me,
                    handler:me.onReviewed
                }]
            },
            /**
             * Lab panel
             */
            {

                xtype:'container',
                action:'patientLabs',
                layout:'border',
                items:[
                    {
                        xtype:'panel',
                        region:'north',
                        layout:'border',
                        bodyBorder:false,
                        border:false,
                        height:350,
                        split:true,
                        items:[
                            {
                                xtype:'grid',
                                region:'west',
                                width:290,
                                split:true,
                                store:me.labPanelsStore,
                                columns:[
                                    {
                                        header:i18n('laboratories'),
                                        dataIndex:'label',
                                        flex:1
                                    }
                                ],
                                listeners:{
                                    scope:me,
                                    itemclick:me.onLabPanelSelected,
                                    selectionchange:me.onLabPanelSelectionChange
                                }
                            },
                            {
                                xtype:'panel',
                                action:'labPreviewPanel',
                                title:i18n('laboratory_preview'),
                                region:'center',
                                items:[
	                                me.uploadWin = Ext.create('Ext.window.Window', {
                                        draggable:false,
                                        closable:false,
                                        closeAction:'hide',
                                        items:[
                                            {
                                                xtype:'form',
                                                bodyPadding:10,
                                                width:400,
                                                items:[
                                                    {
                                                        xtype:'filefield',
                                                        name:'filePath',
                                                        buttonText:i18n('select_a_file') + '...',
                                                        anchor:'100%'
                                                    }
                                                ],
                                                api:{
                                                    submit:DocumentHandler.uploadDocument
                                                }
                                            }
                                        ],
                                        buttons:[
                                            {
                                                text:i18n('cancel'),
                                                handler:function(){
                                                    me.uploadWin.close();
                                                }
                                            },
                                            {
                                                text:i18n('upload'),
                                                scope:me,
                                                handler:me.onLabUpload
                                            }
                                        ]
                                    })
                                ],
	                            listeners:{
		                            scope:me,
		                            render:me.onLaboratoryPreviewRender
	                            }
                            }
                        ]
                    },
                    {
                        xtype:'container',
                        region:'center',
                        layout:'border',
                        split:true,
                        items:[
                            {
                                xtype:'form',
                                title:i18n('laboratory_entry_form'),
                                region:'west',
                                width:290,
                                split:true,
                                bodyPadding:5,
                                autoScroll:true,
                                bbar:['->', {
                                    text:i18n('reset'),
                                    scope:me,
                                    handler:me.onLabResultsReset
                                }, '-', {
                                    text:i18n('sign'),
                                    scope:me,
                                    handler:me.onLabResultsSign
                                }, '-', {
                                    text:i18n('save'),
                                    scope:me,
                                    handler:me.onLabResultsSave
                                }]
                            },
                            {
                                xtype:'panel',
                                region:'center',
                                height:300,
                                split:true,
                                items:[
                                    {
                                        xtype:'lalboratoryresultsdataview',
                                        action:'lalboratoryresultsdataview',
                                        store:Ext.create('App.store.patient.PatientLabsResults'),
                                        listeners:{
                                            scope:me,
                                            itemclick:me.onLabResultClick
                                        }
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }
        ];
        /**
         * Docked Items
         * @type {Array}
         */
        me.dockedItems = [
            {
                xtype:'toolbar',
                items:[
                    {

                        text:i18n('immunization'),
                        enableToggle:true,
                        toggleGroup:'medicalWin',
                        pressed:true,
                        itemId:'immunization',
                        action:'immunization',
                        scope:me,
                        handler:me.cardSwitch
                    },
                    '-',
                    {
                        text:i18n('allergies'),
                        enableToggle:true,
                        toggleGroup:'medicalWin',
                        itemId:'allergies',
                        action:'allergies',
                        scope:me,
                        handler:me.cardSwitch
                    },
                    '-',
                    {
                        text:i18n('active_problems'),
                        enableToggle:true,
                        toggleGroup:'medicalWin',
                        itemId:'issues',
                        action:'issues',
                        scope:me,
                        handler:me.cardSwitch
                    },
                    '-',
//                    {
//                        text:i18n('surgeries'),
//                        enableToggle:true,
//                        toggleGroup:'medicalWin',
//                        itemId:'surgery',
//                        action:'surgery',
//                        scope:me,
//                        handler:me.cardSwitch
//                    },
//                    '-',
//                    {
//                        text:i18n('dental'),
//                        enableToggle:true,
//                        toggleGroup:'medicalWin',
//                        itemId:'dental',
//                        action:'dental',
//                        scope:me,
//                        handler:me.cardSwitch
//                    },
//                    '-',
                    {
                        text:i18n('medications'),
                        enableToggle:true,
                        toggleGroup:'medicalWin',
                        itemId:'medications',
                        action:'medications',
                        scope:me,
                        handler:me.cardSwitch
                    },
                    '-',
                    {
                        text:i18n('laboratories'),
                        enableToggle:true,
                        toggleGroup:'medicalWin',
                        itemId:'laboratories',
                        action:'laboratories',
                        scope:me,
                        handler:me.cardSwitch
                    },
                    '->',
                    {
                        text:i18n('add_new'),
                        action:'AddRecord',
	                    iconCls:'icoAdd',
                        scope:me,
                        handler:me.onAddItem
                    }
                ]
            }
        ];

	    me.buttons = [
		    {
			    text:i18n('close'),
			    scope:me,
			    handler:function(){
				    me.close();
			    }
		    }
	    ];

        /**
         * Listeners
         * @type {{scope: *, show: Function, close: Function}}
         */
        me.listeners = {
            scope:me,
            show:me.onMedicalWinShow,
            close:me.onMedicalWinClose
        };
        me.callParent(arguments);
    },
    //*******************************************************

	onOnlyActiveToggle:function(btn, pressed){
		var me = this,
			store = btn.up('grid').getStore();

		if(pressed){
			store.load({
				filters:[
					{
						property:'pid',
						value:me.pid
					},
					{
						property:'end_date',
						value:null
					}
				]
			})
		}else{
			store.load({
				filters:[
					{
						property:'pid',
						value:me.pid
					}
				]
			})
		}
	},

    onLabPanelSelected:function(grid, model){
        var me = this, formPanel = me.query('[action="patientLabs"]')[0].down('form'), dataView = me.query('[action="lalboratoryresultsdataview"]')[0], store = dataView.store, fields = model.data.fields;
        me.currLabPanelId = model.data.id;
        me.removeLabDocument();
        formPanel.removeAll();
        formPanel.add({
                xtype:'textfield',
                name:'id',
                hidden:true
            });
        for(var i = 0; i < fields.length; i++){
            formPanel.add({
                xtype:'fieldcontainer',
                layout:'hbox',
                margin:0,
                anchor:'100%',
                fieldLabel:fields[i].code_text_short || fields[i].loinc_name,
                labelWidth:130,
                items:[
                    {
                        xtype:'textfield',
                        name:fields[i].loinc_number,
                        flex:1,
                        allowBlank:fields[i].required_in_panel != 'R'
                    },
                    {
                        xtype:'mitos.unitscombo',
                        value:fields[i].default_unit,
                        name:fields[i].loinc_number + '_unit',
                        width:90
                    }
                ]
            });
        }
        store.load({params:{parent_id:model.data.id}});
    },

	onLaboratoryPreviewRender:function(panel){
		var me = this;
		panel.dockedItems.items[0].add({
			xtype:'button',
			text:i18n('upload'),
			disabled:true,
			action:'uploadBtn',
			scope:me,
			handler:me.onLabUploadWind
		});
	},

    onLabPanelSelectionChange:function(model, record){
        this.query('[action="uploadBtn"]')[0].setDisabled(record.length == 0);
    },

    onLabUploadWind:function(){
        var me = this, previewPanel = me.query('[action="labPreviewPanel"]')[0];
        me.uploadWin.show();
        me.uploadWin.alignTo(previewPanel.el.dom, 'tr-tr', [-5, 35])
    },

    onLabUpload:function(btn){
        var me = this, formPanel = me.uploadWin.down('form'), form = formPanel.getForm(), win = btn.up('window');
        if(form.isValid()){
            formPanel.el.mask(i18n('uploading_laboratory') + '...');
            form.submit({
                //waitMsg: i18n('uploading_laboratory') + '...',
                params:{
                    pid:app.patient.pid,
                    docType:'laboratory',
                    eid:app.patient.eid
                },
                success:function(fp, o){
                    formPanel.el.unmask();
                    say(o.result);
                    win.close();
                    me.getLabDocument(o.result.doc.url);
                    me.addNewLabResults(o.result.doc.id);
                },
                failure:function(fp, o){
                    formPanel.el.unmask();
                    say(o.result);
                    win.close();
                }
            });
        }
    },

    onLabResultClick:function(view, model){
        var me = this, form = me.query('[action="patientLabs"]')[0].down('form').getForm();
        if(me.currDocUrl != model.data.document_url){
            form.reset();
            model.data.data.id = model.data.id;
            form.setValues(model.data.data);
            me.getLabDocument(model.data.document_url);
            me.currDocUrl = model.data.document_url;
        }
    },

    onLabResultsSign:function(){
        var me = this, form = me.query('[action="patientLabs"]')[0].down('form').getForm(), dataView = me.query('[action="lalboratoryresultsdataview"]')[0], store = dataView.store, values = form.getValues(), record = dataView.getSelectionModel().getLastSelected();
        if(form.isValid()){
            if(values.id){
                me.passwordVerificationWin(function(btn, password){
                    if(btn == 'ok'){
                        User.verifyUserPass(password, function(provider, response){
                            if(response.result){
                                say(record);
                                Medical.signPatientLabsResultById(record.data.id, function(provider, response){
                                    store.load({
                                            params:{
                                                parent_id:me.currLabPanelId
                                            }
                                        });
                                });
                            }else{
                                Ext.Msg.show({
                                        title:'Oops!',
                                        msg:i18n('incorrect_password'),
                                        //buttons:Ext.Msg.OKCANCEL,
                                        buttons:Ext.Msg.OK,
                                        icon:Ext.Msg.ERROR,
                                        fn:function(btn){
                                            if(btn == 'ok'){
                                                //me.onLabResultsSign();
                                            }
                                        }
                                    });
                            }
                        });
                    }
                });
            }else{
                Ext.Msg.show({
                    title:'Oops!',
                    msg:i18n('nothing_to_sign'),
                    //buttons:Ext.Msg.OKCANCEL,
                    buttons:Ext.Msg.OK,
                    icon:Ext.Msg.ERROR,
                    fn:function(btn){
                        if(btn == 'ok'){
                            //me.onLabResultsSign();
                        }
                    }
                });
            }
        }
    },

    onLabResultsSave:function(btn){
        var me = this, form = btn.up('form').getForm(), dataView = me.query('[action="lalboratoryresultsdataview"]')[0], store = dataView.store, values = form.getValues(), record = dataView.getSelectionModel().getLastSelected();
        if(form.isValid()){
            Medical.updatePatientLabsResult(values, function(){
                store.load({params:{parent_id:record.data.parent_id}});
                form.reset();
            });
        }
    },

    addNewLabResults:function(docId){
        var me = this, dataView = me.query('[action="lalboratoryresultsdataview"]')[0], store = dataView.store, params = {
            parent_id:me.currLabPanelId,
            document_id:docId
        };
        Medical.addPatientLabsResult(params, function(provider, response){
            store.load({
                params:{
                    parent_id:me.currLabPanelId
                }
            });
        });
    },

    onReviewed:function(btn){
        var me = this, BtnId = btn.itemId, params = {
            eid:app.patient.eid,
            area:BtnId
        };
        Medical.reviewMedicalWindowEncounter(params, function(provider, response){
            me.msg('Sweet!', i18n('succefully_reviewed'));
        });
    },

    onLabResultsReset:function(btn){
        var form = btn.up('form').getForm();
        form.reset();
    },

    getLabDocument:function(src){
        var panel = this.query('[action="labPreviewPanel"]')[0];
        panel.remove(this.doc);
        panel.add(this.doc = Ext.create('App.ux.ManagedIframe', {
                src:src
            }));
    },

    removeLabDocument:function(src){
        var panel = this.query('[action="labPreviewPanel"]')[0];
        panel.remove(this.doc);
    },

    beforeImmunizationEdit:function(editor, e){
        var form = editor.editor.getForm(),
            search = form.findField('immunization_id'),
            name = form.findField('immunization_name'),
            newRecord = e.record.data.immunization_name == '';
        search.setVisible(newRecord);
        name.setVisible(!newRecord);
        if(newRecord){
            var dt = new Date();
            e.record.data.immunization_id = '';
            e.record.data.education_date = dt;
            e.record.data.administered_date = dt;
        }
    },


	beforeAllergyEdit:function(editor, e){
		this.allergieMedication.setValue(e.record.data.allergy);
	},

    //*********************************************************
    onLiveSearchSelect:function(combo, record){
        var me = this,
	        xform = combo.up('form').getForm(),
	        field,
	        name;
	    
        if(combo.action == 'immunization_id'){
            name = record[0].data.name;
            field = combo.up('fieldcontainer').getComponent('immunization_name');
            field.setValue(name);
            me.CvxMvxCombo.store.load({params:{cvx_code:record[0].data.cvx_code}})

        }else if(combo.action == 'allergy'){
	        xform.getRecord().set({allergy_code:record[0].data.RXCUI});
        }else if(combo.action == 'actiiveproblems'){
	        xform.findField('code_text').setValue(record[0].data.code_text);
	        xform.findField('code_type').setValue(record[0].data.code_type);
//        }else if(combo.action == 'surgery'){
//            name = record[0].data.surgery;
//            field = combo.up('fieldcontainer').query('[action="idField"]')[0];
//            field.setValue(name);

        }else if(combo.action == 'medication'){
	        Rxnorm.getMedicationAttributesByCODE(record[0].data.CODE, function(provider, response){
		        xform.setValues({
			        RXCUI:record[0].data.RXCUI,
			        CODE:record[0].data.CODE,
			        STR:record[0].data.STR.split(',')[0],
			        route:response.result.DRT,
			        dose:response.result.DST,
			        form:response.result.DDF
		        });
	        });
        }else if(combo.action == 'cdt'){
            name = record[0].data.text;
            field = combo.up('fieldcontainer').query('[action="description"]')[0];
            field.setValue(name);
        }

    },

    onAddItem:function(){
        var me = this,
	        grid = this.getLayout().getActiveItem(),
	        store = grid.store,
	        params;

	    grid.editingPlugin.cancelEdit();

	    store.insert(0, {
            created_uid:app.user.id,

            uid:app.user.id,
            pid:app.patient.pid,
	        eid:app.patient.eid,

	        create_date:new Date(),
            begin_date:new Date()

        });
	    grid.editingPlugin.startEdit(0, 0);

        if(app.patient.eid != null){
            if(grid.action == 'patientImmuListGrid'){
                params = {
                    eid:app.patient.eid,
                    area:'review_immunizations'
                };
            }else if(grid.action == 'patientAllergiesListGrid'){
                params = {
                    eid:app.patient.eid,
                    area:'review_allergies'
                };
            }else if(grid.action == 'patientMedicalListGrid'){
                params = {
                    eid:app.patient.eid,
                    area:'review_active_problems'
                };
            }else if(grid.action == 'patientSurgeryListGrid'){
                params = {
                    eid:app.patient.eid,
                    area:'review_surgery'
                };
            }else if(grid.action == 'patientDentalListGrid'){
                params = {
                    eid:app.patient.eid,
                    area:'review_dental'
                };
            }else if(grid.action == 'patientMedicationsListGrid'){
                params = {
                    eid:app.patient.eid,
                    area:'review_medications'
                };
            }
            Medical.reviewMedicalWindowEncounter(params);
        }
    },

    onLocationSelect:function(combo, record){
        var me = this,
	        list,
            value = combo.getValue();

        if(value == 'Skin'){
			list = 80;
	        me.allergiesReaction.getStore().load();
        }else if(value == 'Local'){
	        list = 81;
        }else if(value == 'Abdominal'){
	        list = 82;
        }else if(value == 'Systemic / Anaphylactic'){
	        list = 83;
        }

	    me.allergiesReaction.getStore().load({params:{list_id:list}});
    },

	onAllergyTypeCahnge:function(combo){
        var me = this,
	        type = combo.getValue(),
	        isDrug = type == 'Drug';

		me.allergieMedication.setVisible(isDrug);
		me.allergieMedication.setDisabled(!isDrug);
		me.allergieType.setVisible(!isDrug);
		me.allergieType.setDisabled(isDrug);

		if(!isDrug) me.allergieType.store.load({params:{allergy_type:type}})

    },

    setDefaults:function(options){
        var data;
        if(options.update){
            data = options.update[0].data;
            data.updated_uid = app.user.id;
        }else if(options.create){

        }
    },

	cardSwitch:function(btn){
        var me = this,
	        layout = me.getLayout(),
	        addBtn = me.down('toolbar').query('[action="AddRecord"]')[0],
	        p = app.patient,
	        title;

		me.pid = p.pid;
        addBtn.show();

		if(btn.action == 'immunization'){
            layout.setActiveItem(0);
            title = 'Immunizations';
        }else if(btn.action == 'allergies'){
            layout.setActiveItem(1);
            title = 'Allergies';
        }else if(btn.action == 'issues'){
            layout.setActiveItem(2);
            title = 'Active Problems';
//        }else if(btn.action == 'surgery'){
//            layout.setActiveItem(3);
//            title = 'Surgeries';
//        }else if(btn.action == 'dental'){
//            layout.setActiveItem(4);
//            title = 'Dentals';
        }else if(btn.action == 'medications'){
            layout.setActiveItem(3);
            title = 'Medications';
        }else if(btn.action == 'laboratories'){
            layout.setActiveItem(4);
            title = 'Laboratories';
            addBtn.hide();
        }
        me.setTitle(p.name + ' (' + title + ') ' + (p.readOnly ? '-  <span style="color:red">[Read Mode]</span>' : ''));
    },

	onMedicalWinShow:function(){
        var me = this, reviewBts = me.query('button[action="review"]'), p = app.patient;
        me.pid = p.pid;
        me.eid = p.eid;
        me.setTitle(p.name + (p.readOnly ? ' <span style="color:red">[' + i18n('read_mode') + ']</span>' : ''));
        me.setReadOnly(app.patient.readOnly);
        for(var i = 0; i < reviewBts.length; i++){
            reviewBts[i].setVisible((app.patient.eid != null));
        }
        me.labPanelsStore.load();
        me.patientImmuListStore.load({
            params:{
                pid:app.patient.pid
            }
        });


        me.patientAllergiesListStore.load({
            filters:[
	            {
		            property:'pid',
		            value:app.patient.pid
	            }
            ]
        });


        me.patientMedicalIssuesStore.load({
            params:{
                pid:app.patient.pid
            }
        });
//        me.patientSurgeryStore.load({
//                params:{
//                    pid:app.patient.pid
//                }
//            });
//        me.patientDentalStore.load({
//                params:{
//                    pid:app.patient.pid
//                }
//            });
        me.patientMedicationsStore.load({
            params:{
                pid:app.patient.pid
            }
        });
    },

	onMedicalWinClose:function(){
	    if(app.getActivePanel().$className == 'App.view.patient.Summary'){
            app.getActivePanel().loadStores();
        }
    }
});