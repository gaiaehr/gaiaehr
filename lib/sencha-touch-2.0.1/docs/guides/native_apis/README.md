# Accessing Native APIs with Ext.device

One of the biggest benefits of native packaging is access to native APIs. Sencha Touch 2 provides a access to native APIs through its Ext.device API. The Ext.device API does not itself provide the native API capabilities - it's just a wrapper that can be used to access native APIs powered by the Sencha Packager or third party solutions like Phonegap and the iOS Simulator. At the time of writing, Ext.device provides 4 APIs:

* **Connection** - tells you whether or not the device has a current connection to the internet
* **Notification** - allows you to use native notification windows (the native version of Ext.Msg)
* **Orientation** - gives you as much information as the device provides about its current orientation
* **Camera** - allows your app to take pictures or select from the camera library (with your user's permission)

## Getting Started

Because we want to keep our apps small, and because native APIs only apply in native packaged apps, the Ext.device API is not included in the standard build of Sencha Touch 2. When you want to use any of the Device API's , you must require them using `Ext.require`:

    Ext.require('Ext.device.Connection');

    Ext.application({
        name: 'MyApp',
        //the rest of your app follows
    });

## Connection

The Connection API is used to determine whether or not the device is currently online. It exposes two main methods - isOnline and getType:

    if (Ext.device.Connection.isOnline()) {
        Ext.Msg.alert('You are currently connected via ' + Ext.device.Connection.getType());
    } else {
        Ext.Msg.alert('You are not currently connected');
    }

This is probably the simplest of the APIs offered by Ext.device. If you load this up on a device or inside the simulator you'll see a Sencha Touch message box appearing either telling you you're offline, or if you're online, which connection type you have (wifi, 2g, 3g etc). See the Ext.device.Connection docs for a full list of possible connection types.

## Notification

Notifications are used to show native dialog boxes. Notifications work very much like Ext.Msg and can be configured in many different ways. For example, here's how we can display a native notification box with a title and a message:

    Ext.device.Notification.show({
        title: 'One Button',
        message: 'This is a simple notification with one button.'
    });

This causes a native notification window to appear like this:

{@img notifications-simple.png}

This is just going to pop up a native message box with our configured title and message. It adds an OK button too so the user can dismiss the message. When the user taps on the button, our callback is called - in this case we'll respond to their button tap by opening a second notification:

    Ext.device.Notification.show({
        title: 'One Button',
        message: 'This is a simple notification with one button.',

        callback: function(button) {
            //When the user taps a button, show another notification
            Ext.device.Notification.show({
                message: 'You pressed: "' + button + '"'
            });
        }
    });

We didn't provide a title for the second message, which is fine - it just shows the message and an OK button. We can customize the buttons that are presented to the user easily:

    Ext.device.Notification.show({
        title: 'Multiple Buttons',
        message: 'This is a notification with multiple buttons.',
        buttons: ["Cancel", "Login", "Another"],

        callback: function(button) {
            //When the user taps a button, show another notification
            Ext.device.Notification.show({
                message: 'You pressed: "' + button + '"'
            });
        }
    });

This time we provided three button text strings in the *buttons* array - when we run this code now we see the buttons stacked on top of one another:

{@img notifications-custom.png}

### Vibration

The final capability of the notification API is to vibrate the device. Making this happen is straightforward:

    Ext.device.notification.vibrate();

This will cause the device to vibrate for a short time, and can be called as often as desired.

## Camera

The Camera API allows you to take pictures using the phone's camera, or alternatively select an image from the existing photo library available on the phone. Here's how we can ask the device to prompt the user to take a new picture:

    Ext.device.Camera.capture({
        source: 'camera',
        destination: 'file',

        success: function(url) {
            //show the newly captured image in a full screen Ext.Img component:
            Ext.create('Ext.Img', {
                src: url,
                fullscreen: true
            });
        }
    });

Note that we specified two options here (apart from our *success* callback) - source and destination. We can set *source* to either 'camera' or 'library' - the first option opens the camera to take a new picture, the second opens a native photo browser window to select an existing image. Our example above is going to open the camera app then save the new image to a file, finally calling our *success* function with the local url to that file.

There are several other options that can be passed into the Ext.device.Camera.capture call:

* **success** - the function to call if an image was successfully acquired
* **failure** - the function to call if an image could not be acquired
* **scope** - the scope to run the two functions above in (sets the value of *this*)
* **quality** - gives the camera a hint about the compression quality we'd like our image to have (0-100)
* **width** - the desired width of the new image in px
* **height** - the desired height of the new image in px
* **source** - can be 'camera' or 'library' - 'camera' takes a new picture, 'library' chooses an existing one
* **destination** - can be 'file' or 'data' - file returns a local url to the new image, 'data' returns a Base64 encoded string of the image
* **encoding** - can be 'jpeg', 'jpg' or 'png' - the first two options encode the image as a JPEG, the third as a PNG

Any number of these options can be passed into your Ext.device.Camera.capture call.

## Orientation

The orientation API yields information about the current orientation of the device. This time we register a handler function that is called every time a change in orientation is detected:

    Ext.device.Orientation.on('orientation', function(e) {
        var alpha = Math.round(e.alpha),
            beta = Math.round(e.beta),
            gamma = Math.round(e.gamma);

        console.log(alpha, beta, gamma);
    });

This is going to provide a stream of console messages with the alpha, beta and gamma orientation of the device. These values correspond to the three dimensions the device can be pivoted around, are are expressed in degrees ranging from 0 to 360.

## Packaging

Now that you have a grasp of how to use the Ext.device API to access native device functionality, proceed to the guides to packaging your app for <a href="#!/guide/native_packaging">iOS</a> and <a href="#!/guide/native_android">Android</a> to start testing on a real device.
