# Sencha Touch 2 Native Packaging for iOS

## Requirements

### Software

  - Mac OS X 10.6+ and Windows Vista+
  - Xcode (Optional and Mac only, required for for iOS Simulator)

## Steps to package your application for iOS on Mac

  1. Complete iOS provisioning on [Apple iOS provisioning portal][1] including certificates and devices setup through
     the provisioning portal and Xcode.
  2. Install the packager, part of Sencha SDK Tools 2.0
  3. Create a packaging configuration file to be use with the native packager.
  4. Run the packager to create a packaged <application>.app.

### Step 1: Complete iOS provisioning on Apple iOS provisioning portal for the application

Use the [Apple iOS provisioning portal][1] to set up the appropriate development and distribution certifications and
profiles. Create an App ID and finish provisioning your application. Please refer to the How-To section in the
[Apple iOS provisioning portal][1] for help.

*Note:* You will need to know your App ID and App Name to complete the packaging process.

Please also refer to the [Creating an Apple iOS development certificate in P12 format][6] guide for instructions on
generating the required P12 certificate used in the packaging process.

### Step 2: Install the packager

  - Run the [Sencha SDK Tools][5] installation: SenchaSDKTools (SenchaSDKTools-2.0.0-Beta)
  - The `sencha` command that includes the package option will be installed to the specified location during
    installation (default: Applications/SenchaSDKTools-2.0.0-Beta/command).**

### Step 3: Create a packaging configuration file to be use with the native packager.

The configuration file has the following format:

    {
        "applicationName": "<AppName>",
        "applicationId": "<AppID>",
        "inputPath": "<PathToWebApp>",
        "iconName": "<AppIconName>",
        "outputPath": "<AppPackageOutputPath>",
        "versionString": "<AppVersion>",
        "configuration": "<Release | Debug>",
        "platform": "<iOSSimulator | iOS>",
        "deviceType": "<iPhone | iPad | Universal>",
		"certificatePath":"<P12 Certificate>",
		"certificatePassword": "<(Optional)CertificatePassword>"
		"certificateAlias": "<(Optional)CertificateAlias>"
		"provisionProfile": "<(Optional)ProvisionProfile>"
		"rawConfig":"<Optional><key>UIPrerenderedIcon</key><false/>",
        "orientations": [
            "portrait",
            "landscapeLeft",
            "landscapeRight",
            "portraitUpsideDown"
        ]
    }

Note: A configuration file template can be created by running the following command (in Terminal on Mac OS or Command
Prompt on Windows):

    sencha package generate <configTemplate.json>

`<configTemplate.json>` is the name of the configuration file.

*Note:* The `<configTemplate.json>` path or filename cannot contain any space.

The following parameters are applicable to iOS packaging:

    "applicationName":"<AppName>"

Both `AppName` and `AppID` can be found on the [iOS provisioning portal][1] on the App IDs section. Here's an example App ID

{@img idScreen.png App ID}

This example uses the following:

  - AppName: Sencha Touch 2 Packaging
  - AppID: com.Sencha.Touch2Packaging

*Note:* the App ID is the same as the one you put in the Identifier field in Xcode.

You'll also need to do the following:

 - "inputPath" specifies the path of the web application to be packaged:

    "inputPath":"<PathToWebApp>""

 - "outputPath" specifies the output location of the packaged application, `<application.app>`:

    "outputPath":"<AppPackageOutputPath>"

 - "iconName" specifies the icon file to be used for your application:

    "iconName":"<AppIconName>"

*Note:* Retina icon should be specified with ``@2x`` at the end of the icon name. A regular icon name looks like
`icon.png`, while a retina icon looks like `(regular) and `icon@2x.png``. If a retina icon with the `<Icon Name>@2x.png`
exists, the packager will include the retina icon.

*Note:* Refer to the [iOS icon guideline][3] for further information on icon file specifications.


 - "versionString" specifies the version of the application, like this:

    "versionString":"<AppVersion>"


 - "configuration" specifies the build type, either Release or Debug:

    "configuration":"<Release | Debug>"

 - "platform" specifies if the build is for the iOS simulator (iOSSimulator) or for the device
(iOS):

    "platform":"<Simulator | iOS>"

*Note:* the iOS simulator cannot run a signed build. A signed build can only be run on the device.

 - "deviceType" specifies the device type the application is targeted for:

  "deviceType":"<iPhone | iPad | Universal>"

- The available options are: `iPhone` for iPhone applications, `iPad` for iPad applications, or `Universal` for both iPhone and iPad applications

 - "certificatePath" specifies the P12 certificate used for signing the application
	"certificatePath":"<P12 Certificate>",

- Please refer to the [Creating an Apple iOS development certificate in P12 format][6] guide on how to generate the P12 certificate

 - Optional: Specify the Certificate password for the P12 certificate, if you used a password:

    "certificatePassword":"<(Optional)certificatePassword>"

 - Optional: Specify a specific Certificate Alias to use for signing your application:

    "certificateAlias":"<(Optional)CertificateAlias>"

 - Optional: Specify a specific Provision Profile to be used:

	    "provisionProfile":"<(Optional)ProvisionProfile>"

 - Optional: Specify whether to use an icon with shine or not. True is an icon without shine.

		"rawConfig":"<Optional><key>UIPrerenderedIcon</key><false/>"

 - Optional: specify the orientations of the application from `portrait`, `landscapeLeft`, `landscapeRight`, and `portraitUpsideDown`, like this:

    "orientations": [
        "portrait",
        "landscapeLeft",
        "landscapeRight",
        "portraitUpsideDown"
    ]

*Note:* If this omitted, the default orientations are all four orientations.


**Sample debug configuration file**

    {
        "applicationName":"Sencha Touch 2 Packaging",
        "applicationId":"com.sencha.touch2packaing",
        "iconName":"icon.png",
        "versionString":"1.0",
        "outputPath":"~/Desktop/STBuild-iOS",
        "inputPath":"~/Desktop/www/",
        "configuration":"Debug",
        "platform":"iOSSimulator",
        "deviceType":"iPhone",
		"certificatePath":"./Cert/Certificates.p12",
		"certificateAlias":"iPhone Developer: First Last (4E6D79BASH)",
		"rawConfig":"<key>UIPrerenderedIcon</key><false/>",
        "orientations": [
            "portrait",
            "landscapeLeft",
            "landscapeRight",
            "portraitUpsideDown"
        ]
    }

**Sample release configuration file**

    {
        "applicationName":"Sencha Touch 2 Packaging",
        "applicationId":"com.sencha.touch2packaing",
        "iconName":"icon.png",
        "versionString":"1.0",
        "outputPath":"~/Desktop/STBuild-iOS",
        "inputPath":"~/Desktop/www/",
        "configuration":"Release",
        "platform":"iOS",
        "deviceType":"iPhone",
		"certificatePath":"./Cert/Certificates.p12",
		"certificateAlias":"iPhone Developer: First Last (4E6D79BASH)",
		"rawConfig":"<key>UIPrerenderedIcon</key><false/>",
        "orientations": [
            "portrait",
            "landscapeLeft",
            "landscapeRight",
            "portraitUpsideDown"
        ]
    }

## Step 4: Run the packager to create the packaged application

**Package a debug application and run it on the iOS simulator (MAC OS only)**

*Prerequisite:* The Platform and Configuration setting needs to be set in the config file, for example:

    platform: iOSSimulator
    configuration: Debug

If Platform and Configuration are not set, iOS will not run the application correctly.

To package a debug/unsigned application to run on the iOS simulator, issue the following command in Terminal:

    sencha package run <configFile.json>

The iOS simulator with the application running will launch after successful execution of this command. The `deviceType`
identifier  -- `iPhone` or `iPad` -- triggers the appropriate simulator.
.

**Packaging the application to deploy on the iOS device**

To package a signed application to run on the device, issue the following command in the terminal:

    sencha package <configFile.json>

This will create a `<AppName.app>` file in the specified output location. This is the application that you can use to deploy
to the iOS device. To do this, you drag the file onto the iTunes icon:

{@img itunes1.png}

Which will add your application into the Apps section of iTunes:

{@img itunes2.png}

Now you can select that application when syncing your device with iTunes:

{@img itunes3.png}

**See Also**

  1. [Apple iOS provisioning portal][1]
  2. [iOS Icon guideline][4]

[1]: https://developer.apple.com/ios/manage/overview/index.action
[3]: http://developer.apple.com/library/ios/%23documentation/iPhone/Conceptual/iPhoneOSProgrammingGuide/BuildTimeConfiguration/BuildTimeConfiguration.html%23//apple_ref/doc/uid/TP40007072-CH7-SW1
[4]: http://developer.apple.com/library/ios/%23documentation/userexperience/conceptual/mobilehig/IconsImages/IconsImages.html
[5]: http://www.sencha.com/products/sdk-tools/
[6]: #!/guide/native_provisioning
