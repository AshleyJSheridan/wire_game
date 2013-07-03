TMW Competition App for the Wire Game
=========================================

The latest version of the Zend implementation for a Competition App with a Form submition contest or registration (with image upload and likegate optional) includes a simple CMS with a form builder to manage the app and export its data. There is a big chance that you wont need to alter the php code and just use the CMS to create the app.



Start a new project
----------------------------------------------

Please copy the files and setup the database found under the database folder to start a new project.



Connecting the database
-------------------------------------------

You can find the database connection details under the application.ini file that exists in the folder application/configs. 



Routing
---------------------------------------------
In the same folder application/configs you can see the routes.ini but there propably be no need to alter the routes.



Controller
---------------------------------------------

The functionality of the app is held in the CompetitionController.php that exists inside the application/controllers folder. In the init function you can find all the variables (most are CMS managed) and the naming is self declaring so you can eazily understand the nature and purpose of each variable.
If there is need for a likegate please uncomment the code:

if($this->_facebookPage != 'like' && $this->_facebookPage != 'submit' && !$pageStatus->liked && !$this->_ajaxParam) {
    $this->_redirect('/facebook/' . $this->_facebookCampaign . '/like/');
}



Views
----------------------------------------------

You can find the views for the app in the application/views/scripts/campaign/your campaign name/competition that hold the contents of each page for the like, index, register, submit actions(submit is the success page after submition has taken place). Those views fill ine the contect of the competition.phtml layout that can be found under application/layouts/scripts folder. Again all variables here are CMS managable.



Create a new Facebook App
-------------------------------------------------
Login with the the developers account facebook user and go to url https://developers.facebook.com/apps. Create a new app and make sure that https is properly configure for the given domain.



Administration Section
-------------------------------------------------

You can login to the admin section from the url yourdomain.com/admin/ with credentials admin/admin that have to be changed when the app goes live. There you can manage the form fields, the app settings, the pages copy, google analytics etc. Please remember if you change the Campaign Name to resave all content(application form fields and texts) so it can be matched to the name campaign name. Fill in all required variables (use the values from you newlly created fb app for Id and secret).



Styling
------------------------------------------------

You can see the app under the new domain working now. http://subdomain.domain.com/competition/your campaign name/(action name: like or register or index or submit) and propwerly style the as per your designs. Please use the folder convention for placing your css, js and image files to keep the app source organized.


Exporting Data
------------------------------------------------------

Form within the CMS you can select the competition name that you wish to export the data for and then a csv file is created for download.
Eazymode.


VHOST Setup
--------------------------------------------------------

<VirtualHost *:80>
    ServerName wire-game.dev
    DocumentRoot "C:\wamp\www\wire_game\public_html"

    <Directory "C:\wamp\www\wire_game\public_html">
    	Options FollowSymLinks
	AllowOverride All

        Order allow,deny
        Allow from all
    </Directory>

    ErrorLog "logs/wire_game-error.log"
    CustomLog "logs/wire_game-access.log" common
    DirectoryIndex index.html index.php
</VirtualHost>