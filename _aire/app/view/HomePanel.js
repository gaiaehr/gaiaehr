/**
 * Created with JetBrains PhpStorm.
 * User: ernesto
 * Date: 11/19/12
 * Time: 10:31 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.HomePanel',{
    extend:'Ext.Panel',
    xtype:'homepanel',
    nav:'patientlistnav',
    config:{
        action:'home',
        scrollable:true,
        nav:'patientlistnav',
        tier:1,
        html: '<div class="features">' +
            '   <h2>Welcome to GaiaEHR Aire <span class="version">Beta 0.0.1</span></h2>' +
            '   <div class="feature main">' +
            '       <h3>Unleash GaiaEHR</h3>' +
            '       <p>This is the Kitchen Sink &#8212; a collection of features and examples in an easy-to-browse format. Each example also has a &#8220;view source&#8221; button which shows how it was created.</p>' +
            '   </div>' +
            '   <div class="feature">' +
            '       <h3>Unleash GaiaEHR</h3>' +
            '       <p>Faster layouts and animations, smoother scrolling, and overall more responsive.</p>' +
            '   </div>' +
            '   <div class="feature">' +
            '       <h3>Mobile Architecture</h3>' +
            '       <p>Our new class system is simpler to write and easier to extend. All new MVC and state-management support.</p>' +
            '   </div>' +
            '   <div class="feature">' +
            '       <h3>Simple</h3>' +
            '       <p>Sencha SDK Tools now allow you to build your app for App Store distribution, on Windows and Mac.</p>' +
            '   </div>' +
            '   <div class="feature">' +
            '       <h3>Easy to Learn</h3>' +
            '       <p>With over 30 new guides, 6 new full-fledged demo apps, and improved documentation, Sencha Touch 2 is easier to learn than ever.</p>' +
            '   </div>' +
            '   <div class="footer">Learn more at <a href="http://www.gaiaehr.org/" target="blank">www.gaiaehr.org</a></div>' +
            '</div>'
    }
});