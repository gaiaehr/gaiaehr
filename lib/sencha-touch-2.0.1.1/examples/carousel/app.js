//<debug>
Ext.Loader.setPath({
    'Ext': '../../src'
});
//</debug>

/**
 * This example demonstrates the Carousel component in Sencha Touch 2.
 *
 * The carousel can run both horizontally and vertically, and in this example in combine both
 * of them together.
 *
 * The final result will be 4 horizontal carousels inside 1 vertical carousel.
 * Each of the horizontal carousels will have a category of images within it.
 */
Ext.application({
    //first we define the tablet + phone startup screens and the applicaiton icon url
    phoneStartupScreen: 'resources/loading/Homescreen.jpg',
    tabletStartupScreen: 'resources/loading/Homescreen~ipad.jpg',

    glossOnIcon: false,
    icon: {
        57: 'resources/icons/icon.png',
        72: 'resources/icons/icon@72.png',
        114: 'resources/icons/icon@2x.png',
        144: 'resources/icons/icon@114.png'
    },

    //here we require any components we are using in our application
    requires: [
        'Ext.carousel.Carousel',
        'Ext.Img'
    ],

    /**
     * The launch method is called when the browser is ready and the application is ready to
     * launch.
     */
    launch: function() {
        //first we define each of the categories we have for each one of the horixontal carousels
        //these images can be found inside resources/photos/{category_name}/*
        var categories = ['Food', 'Animals', 'Cars', 'Architecture'],
            itemsCountPerCategory = 10,
            horizontalCarousels = [],
            items, i, j, ln, category;

        //now we loop through each of the categories
        for (i = 0,ln = categories.length; i < ln; i++) {
            items = [];
            category = categories[i];

            for (j = 1; j <= itemsCountPerCategory; j++) {
                //and push each of the image as an item into the items array
                //you can see we are using the img xtype which is an image component,
                //and we just give is a custom cls to style it, and the src
                //of the image
                items.push({
                    xtype: 'image',
                    cls: 'my-carousel-item-img',
                    src: 'resources/photos/' + category + '/' + j + '.jpg'
                });
            }

            //now we add the new horizontal carousel for this category
            horizontalCarousels.push({
                xtype: 'carousel',

                //the direction is horizontal
                direction: 'horizontal',

                //we turn on direction lock so you cannot scroll diagonally
                directionLock: true,

                //and give it the items array
                items: items
            });
        }

        //and finally we create the vertical carousel which contains each of the horizontal
        //category carousels above
        Ext.Viewport.add({
            xtype: 'carousel',

            //this time direction vertical
            direction: 'vertical',

            //and the horizontalCarousels array
            items: horizontalCarousels
        });
    }
});

