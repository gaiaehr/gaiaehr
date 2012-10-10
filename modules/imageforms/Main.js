Ext.define('Modules.imageforms.Main',{
    extend     : 'Modules.Module',
    constructor: function(){
        var me = this;


        me.encPanel = Ext.getCmp('panelEncounter');
        me.imgFormPanel = me.encPanel.encounterTabPanel.add(Ext.create('Modules.imageforms.view.EncounterImageFormsPanel'));



        me.callParent();
    }

});