Installation steps:

–  Unzip the zip file to the your plugins directory

–  Go to your WP admin panel and activate the plugin

- You're done unless you want to style the layout or change the radio buttons to a dropdown box

- You can see the privacy settings in your profile page under each profile option. 

* To change from dropdown box to radio buttons, open the plugin php file and near the top of the file find this line: 

	$profile_privacy_use_radio_buttons = 1;

	change it to this: 

	$profile_privacy_use_radio_buttons = 0;

	That's it

* To modify the CSS edit the css/xprofile/privacy.css file located in the plugin directory. 
