Ext.define('Modules.ReportCenter.Main', {
    extend     : 'Modules.Module',
    constructor: function() {
        var me = this;
        //
        //        /**
        //         * @param panel     (Ext.component)     Component to add to MainPanel
        //         */
        //        me.addPanel(Ext.create('Modules.ReportCenter.view.reportCenter'));
        //
        //        /**
        //         * funtion to add navigation links
        //         * @param parentId  (string)            navigation node parent ID,
        //         * @param node      (object || array)   navigation node configuration properties
        //         */
        //        me.addNavigationNodes('navigationReportCenter',
        //        {
        //            text	:i18n['client_list_report'],
        //            leaf	:true,
        //            cls		:'file',
        //            iconCls	:'icoReport',
        //            id		: 'panelClientListReport'
        //        });

        /**
         * add Patient Category
         * @type {*}
         */
        me.patientCat = app.ReportCenter.addCategory(i18n['patient_reports']);
        /**
         * Patient Category Links...
         * @type {*}
         */
        me.link1 = app.ReportCenter.addReportByCategory(me.patientCat, i18n['prescriptions_and_dispensations'], function(btn) {
            say(btn);
            say(app.ReportCenter);
            say(app.ReportPanel);
            app.ReportCenter.goToReportPanel('panelReportPanel');
        });
        me.link2 = app.ReportCenter.addReportByCategory(me.patientCat, 'clinical', function(btn) {
            app.ReportCenter.goToReportPanel('panelReportPanel');
        });
        me.link3 = app.ReportCenter.addReportByCategory(me.patientCat, 'referrals', function(btn) {
            app.ReportCenter.goToReportPanel('panelReportPanel');
        });
        me.link4 = app.ReportCenter.addReportByCategory(me.patientCat, 'immunization_registry', function(btn) {
            app.ReportCenter.goToReportPanel('panelReportPanel');
        });



        /**
         * add clicnic category
         * @type {*}
         */
        me.clinicCat = app.ReportCenter.addCategory('clinic_reports');
        /**
         * Clicnic Category Links...
         * @type {*}
         */
        me.link5 = app.ReportCenter.addReportByCategory(me.clinicCat, 'standard_measures', function(btn) {
            app.ReportCenter.goToReportPanel('panelReportPanel') ;
        });
        me.link6 = app.ReportCenter.addReportByCategory(me.clinicCat, 'clinical_quality_measures_cqm', function(btn) {
            app.ReportCenter.goToReportPanel('panelReportPanel');
        });
        me.link7 = app.ReportCenter.addReportByCategory(me.clinicCat, 'automated_measure_calculations_amc', function(btn) {
            app.ReportCenter.goToReportPanel('panelReportPanel');
        });
        me.link8 = app.ReportCenter.addReportByCategory(me.clinicCat, 'automated_measure_calculations_amc_tracking', function(btn) {
            app.ReportCenter.goToReportPanel('panelReportPanel');
        });
        me.callParent();
    }

});