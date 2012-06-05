<?php
if(!defined('_MitosEXEC')) die('No direct access allowed.');
// *****************************************************************************************
// Main Screen Application
// Description: Installation screen procedure
// version 0.0.1
// revision: N/A
// author: Ernesto J Rodriguez - GaiaEHR
// *****************************************************************************************
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>GaiaEHR :: Installation</title>
<script type="text/javascript" src="lib/<?php echo $_SESSION['dir']['ext']; ?>/bootstrap.js"></script>
<link rel="stylesheet" type="text/css" href="lib/<?php echo $_SESSION['dir']['ext']; ?>/resources/css/ext-all.css">
<link rel="stylesheet" type="text/css" href="ui_app/style_newui.css" >
<link rel="stylesheet" type="text/css" href="ui_app/GaiaEHR_app.css" >
<script type="text/javascript">
Ext.require(['*']);
Ext.onReady(function() {
    var obj;
    var conn;
    var field;
	// *************************************************************************************
	// Structure, data for storeReq
	// AJAX -> requirements.ejs.php
	// *************************************************************************************
	Ext.define("Requirements", {extend: "Ext.data.Model",
		fields: [
			{name: 'msg',     type: 'string'},
	        {name: 'status',  type: 'string'}
		]
	});
	function status(val) {
	    if (val == 'Ok') {
	        return '<span style="color:green;">' + val + '</span>';
	    } else {
	        return '<span style="color:red;">' + val + '</span>';
	    }
	    return val;
	}
	var storeSites = new Ext.data.Store({
		model	: 'Requirements',
		proxy	: {
			type	: 'ajax',
			url		: 'install/requirements.ejs.php',
			reader	: {
				type: 'json'
			}
		},
		autoLoad: true
	});

	// *************************************************************************************
	// grid to show all the requirements status
	// *************************************************************************************
	var reqGrid = new Ext.grid.GridPanel({
		id 			: 'reqGrid',
	    store		: storeSites,
	    frame		: false,
	    border		: false,
	    viewConfig	: {stripeRows: true},
	    columns: [{
	        text     	: 'Requirements',
	        flex     	: 1,
	        sortable 	: false, 
	        dataIndex	: 'msg'
	    },{
	        text     	: 'Status', 
	        width    	: 150, 
	        sortable 	: true,
	        renderer 	: status,
	        dataIndex	: 'status'
	    }]
	});
	
	// *************************************************************************************
	// The Copyright Notice Window
	// *************************************************************************************
	var winCopyright = Ext.create('widget.window', {
		id				: 'winCopyright',
		width			: 800,
		height			: 500,
        y               : 130,
		closeAction		: 'hide',
		bodyStyle		: 'background-color: #ffffff; padding: 5px;',
		modal			: false,
		resizable		: true,
		title			: 'GaiaEHR Copyright Notice',
		draggable		: true,
		closable		: false,
		autoLoad		: 'gpl-licence-en.html',
		autoScroll		: true,
		dockedItems: [{
			dock	: 'bottom',
			frame	: false,
			border	: false,
			buttons	: [{
		        text	: 'I Agree',
		        id		: 'btn_agree',
		        margin	: '0 5',
				name	: 'btn_reset',
				handler	: function() {
		            winCopyright.hide();
		            winSiteSetup.show();
		        }
			}, '-',{
				text	: 'Not Agree',
		        id		: 'btn_notAgree',
		        margin	: '0 10 0 5',
				name	: 'btn_reset',
				handler	: function() {
		            formLogin.getForm().reset();
		        }
			}]
		}]
	});
	winCopyright.show();
	
	// *************************************************************************************
	// Install proccess form
	// *************************************************************************************

	var formInstall = Ext.create('Ext.form.Panel', {
		id				: 'formInstall',
        bodyStyle		: 'padding:5px',
        border			: false,
        url				: 'install/logic.ejs.php',
        layout			: 'fit',
        fieldDefaults	: {
            msgTarget	: 'side',
            labelWidth 	: 130
        },
        defaults		: {
            anchor		: '100%'
        },
        items: [{
            xtype		: 'tabpanel',
            id			: 'tabsInstall',
            plain		: true,
            border		: false,
            activeTab	: 0,
            defaults	: {bodyStyle:'padding:10px'},
            items:[{
                title		: 'Instructions',
                layout		: 'fit',
                autoLoad	: 'install/instructions.html',
                autoScroll	: true,
		        buttons: [{
		            text	: 'Next',
		            handler	: function() {
		            	Ext.getCmp('clinicInfo').enable();
						Ext.getCmp('tabsInstall').setActiveTab(1);
		        	}
		        }]
            },{
                title		: 'Site Information',
                defaults	: {width: 530},
                id			: 'clinicInfo',
                defaultType	: 'textfield',
                disabled	: true,
                items: [{
					xtype		: 'textfield',
			        name		: 'siteName',
			        id			: 'siteNameField',
			        labelAlign	: 'top',
			        fieldLabel	: 'Site Name (Your Main Clinic\'s Name)',
			        allowBlank	: false ,
			        listeners: {
				   	  	validitychange: function(){
				   	  	field = Ext.getCmp('siteNameField');
			   	  		if(field.isValid()){
				   	  			Ext.getCmp('clinicInfoNext').enable();
				   	  		}else{
				   	  			Ext.getCmp('clinicInfoNext').disable();
				   	  		}
				   		}
				  	}
			    },{
			    	xtype: 'displayfield',
		            value: 'Tips...'
                },{
			    	xtype: 'displayfield',
		            value: '<span style="color:red;">* A Site will have their own database and will no be able to communicate with other sites.</span>'
                },{
			    	xtype: 'displayfield',
		            value: '<span style="color:green;">* If not sure what name to choose for your site, just type "default".</span>'
                },{
			    	xtype: 'displayfield',
		            value: '<span style="color:green;">* A Site can have multiple clinics.</span>'
                },{
			    	xtype: 'displayfield',
		            value: '<span style="color:green;">* Why "Site Name" and no "Clinic\' Name"?</span> Basically because you can have more than one installation using the same webserver. ei. Two physician that share the same office but no their patients.'
		        },{
			    	xtype: 'displayfield',
		            value: '<span style="color:green;">* more tips to come...</span>'
                }],
                    buttons: [{
                        text	: 'Back',
                        handler	: function() {
                            Ext.getCmp('tabsInstall').setActiveTab(0);
                        }
                    },{
                        text	: 'Next',
                        id		:'clinicInfoNext',
                        disabled: true,
                        handler	: function() {
                            Ext.getCmp('databaseInfo').enable();
                            Ext.getCmp('tabsInstall').setActiveTab(2);
                        }
                    }]
            },{
                title		: 'Database Information',
                defaults	: {width: 530},
                id			: 'databaseInfo',
                defaultType	: 'textfield',
                disabled	: true,
                items: [{
			    	xtype	: 'displayfield',
			    	padding	: '10px',
		            value	: 'Choose if you want to <a href="javascript:void(0);" onClick="Ext.getCmp(\'rootFieldset\').enable();">create a new database</a> or use an <a href="javascript:void(0);" onClick="Ext.getCmp(\'dbuserFieldset\').enable();">existing database</a><br>'
                },{
					xtype			: 'fieldset',
					id				: 'rootFieldset',
		            checkboxToggle	: true,
		            title			: 'Create a New Database (Root Access Needed)',
		            defaultType		: 'textfield',
		            collapsed		: true,
		            disabled		: true,
		            layout			: 'anchor',
		            defaults		: {anchor: '100%'},
		            items :[{
		                fieldLabel	: 'Root User',
		                name		: 'rootUser',
		                allowBlank	: false
		            },{
		                fieldLabel	: 'Root Password',
		                name		: 'rootPass',
		                id			: 'rootPass',
		                inputType	: 'password', 
		                allowBlank	: true
		            },{
		                fieldLabel	: 'SQL Server Host',
		                name		: 'dbHost',
		                allowBlank	: false
		            },{
		                fieldLabel	: 'SQL Server Port',
		                name		: 'dbPort',
		                allowBlank	: false
		            },{
		                fieldLabel	: 'Database Name',
		                name		: 'dbName',
						allowBlank	: false
		            },{
		            	fieldLabel	: 'New Database User',
		                name		: 'dbUser',
						allowBlank 	: false
					},{
		            	fieldLabel	: 'New Database Pass',
		                name		: 'dbPass',
		                inputType	: 'password',
						allowBlank	: false
		            }],
			        listeners: {
				   	  	enable: function(){
				   	  		conn = 'root';
							Ext.getCmp('dbuserFieldset').collapse();
				   			Ext.getCmp('dbuserFieldset').disable();
							Ext.getCmp('rootFieldset').expand();
							
				   		}
				  	}
		        },{
		            xtype			: 'fieldset',
		            id				: 'dbuserFieldset',
		            checkboxToggle	: true,
		            title			: 'Install on a existing database',
		            defaultType		: 'textfield',
		            collapsed		: true,
		            disabled		: true,
		            layout			: 'anchor',
		            defaults		: {anchor: '100%'},
		            items :[{
		                fieldLabel	: 'Database Name',
		                name		: 'dbName',
						allowBlank	: false
		            },{
		                fieldLabel	: 'Database User',
		                name		: 'dbUser',
		                allowBlank	: false
		            },{
		                fieldLabel	: 'Database Pass',
		                name		: 'dbPass',
		                id			: 'dbPass',
		                inputType	: 'password',
		                allowBlank	: false
		            },{
		                fieldLabel	: 'Database Host',
		                name		: 'dbHost',
		                allowBlank	: false
		            },{
		                fieldLabel	: 'Database Port',
		                name		: 'dbPort',
		                allowBlank 	: false
		            }],
		            listeners: {
				   	  	enable: function(){
				   	  		conn = 'user';
							Ext.getCmp('rootFieldset').collapse();
							Ext.getCmp('rootFieldset').disable();
							Ext.getCmp('dbuserFieldset').expand();
							
				   	  	}
				  	}
                }],
		        buttons: [{
		            text	: 'Back',
		            handler	: function() {
						Ext.getCmp('tabsInstall').setActiveTab(1);
		        	}
		        },{
		            text	: 'Test Database Credentials',
		            id		: 'dataTester',
		            handler	: function() {
			            var form = this.up('form').getForm();
			            if (form.isValid()) {
			                form.submit({
			                	method:'POST',
			                	params: {
				                    task: 'connType',
				                    conn: conn
				                },
			                    success: function(form, action) {
			                    var obj = Ext.JSON.decode(action.response.responseText);
			                       Ext.Msg.alert('Sweet!', obj.msg);
			                       Ext.getCmp('dataInfoNext').enable();
			                    },
			                    failure: function(form, action) {
			                    var obj = Ext.JSON.decode(action.response.responseText);
			                        Ext.Msg.alert('Oops!', obj.msg);
			                        Ext.getCmp('dataInfoNext').disable();
			                    }
			                });
			            }
			        }
		        },{
		            text	: 'Next',
		            id		:'dataInfoNext',
		            disabled: true,
		            handler: function() {
		            	Ext.getCmp('adminInfo').enable(3);
						Ext.getCmp('tabsInstall').setActiveTab(3);
		        	}
		        }]
            },{
                title		: 'Administrator Information',
                defaults	: {width: 530},
                id			: 'adminInfo',
                defaultType	: 'textfield',
                disabled	: true,
                items: [{
			    	xtype		: 'displayfield',
		            value		: 'Choose Administrator Username and Password'
                },{
			    	xtype		: 'displayfield',
			    	padding		: '0 0 10px 0',
		            value		: '(This account will be the Super User/Global Admin with access to all areas)'
                },{
	                fieldLabel	: 'Administrator Username',
	                name		: 'adminUser',
	                padding		: '0 0 10px 0'
	            },{
	                fieldLabel	: 'Administrator Password',
	                type		: 'password', 
	                name		: 'adminPass',
	                inputType	: 'password'
	            }],
		        buttons: [{
		        	text	: 'Back',
		            handler	: function() {
						Ext.getCmp('tabsInstall').setActiveTab(2);
		        	}
		        },{
		            text	: 'Finish',
		            handler	: function() {
			            var form = this.up('form').getForm();
			            if (form.isValid()) {
			                form.submit({
			                	method:'POST', 
			                	params: {
				                    task: 'install'
				                },
			                    success: function(form, action) {
			                    obj = Ext.JSON.decode(action.response.responseText);
			                       Ext.Msg.alert('Sweet!', obj.msg, function(btn, text) {
				                       if (btn == 'ok'){
									        window.location = "index.php"
									    }
			                       });
			                       
			                    },
			                    failure: function(form, action) {
			                    obj = Ext.JSON.decode(action.response.responseText);
			                        Ext.Msg.alert('Oops!', obj.msg);
			                        Ext.getCmp('dataInfoNext').disable();
			                    }
			                });
			            }
			        }
		        }]
            }]
        }]
    });

	// *************************************************************************************
	// The New Instalation Window 
	// *************************************************************************************
	var winSiteSetup = Ext.create('widget.window', {
	    title		: 'GaiaEHR Requirements',
	    id			: 'winSiteSetup',
	    closable    : false,
        y           : 130,
	    width		: 600,
		bodyPadding	: 2,
		closeAction	: 'hide',
	    plain		: true,
		modal		: false,
		resizable	: false,
		draggable	: false,
	    bodyStyle	: 'background-color: #ffffff; padding: 5px;',
	    items		: [ reqGrid ],
	    dockedItems	: [{
			dock	: 'bottom',
			frame	: false,
			border	: false,
			buttons	: [{
		        text	: 'Next',
		        id		: 'btn_agree',
		        padding	: '0 10',
				name	: 'btn_reset',
				handler	: function() {
			        winSiteSetup.hide();
			        winInstall.show();
		        }
			}]
		}]
	});
	// *************************************************************************************
	// The New Instalation Window 
	// *************************************************************************************
	var winInstall = Ext.create('widget.window', {
	    title		: 'GaiaEHR Installation',
	    id			: 'winInstall',
	    closable	: false,
        y           : 130,
	    width		: 600,
		bodyPadding	: 2,
		closeAction	: 'hide',
	    plain		: true,
		modal		: false,
		resizable	: false,
		draggable	: false,
	    bodyStyle	: 'background-color: #ffffff; padding: 5px;',
	    items		: [ formInstall ]
	});
}); // End of Ext.onReady function
</script>
</head>
<body id="login">
<div id="bg_logo"></div>	
<div id="copyright">GaiaEHR | <a href="javascript:void(0)" onClick="Ext.getCmp('winCopyright').show();" >Copyright Notice</a></div>
</body>
</html>
