# NEVER PUSH TO MASTER BEFORE PUSHING INTO DEV REPO.
--------------------------------------------



## This is readme file of schneps project.
--------------------------------------------

### 1. Master branch. Always stable.

### 2. Dev branch. Always change. In this branch developer can make changes. And deployed them into github and dev server and test, After hi can merge the dev branch with master branch if QA allow doing this.

> Module list:

> 0. (Done) Meta Box (http://www.deluxeblogtips.com/meta-box/)

> 1. (Done) Google Maps plugin (Need research what plugin will confirm the aim)

> 2. (Done) Facebook/Twitter comment left (need research plugin)

> 3. (Done) NextGen Gallery (http://wordpress.org/plugins/nextgen-gallery/)

> 4. (Done) Event Manager (http://demo.wp-events-plugin.com/)

> 5. (Done) BuddyPress (https://buddypress.org/)

> 6. (Done) BuddyPress  social networks (http://wordpress.org/plugins/buddypress-social/)

> 7. (Done) BuddyPress WP-FB-Autoconnect (https://wordpress.org/plugins/wp-fb-autoconnect/)

> 8. Advanced WordPress Email Delivery (http://codecanyon.net/item/advanced-wordpress-email-delivery-/1290390?ref=wpexplorer&ref=wpexplorer&clickthrough_id=232274901&redirect_back=true) looks like it cost 15$

> 9. (Done) XML Sitemap Generator (http://wordpress.org/plugins/google-sitemap-generator/)

> 10. (Done) w3 Total Cache (https://wordpress.org/plugins/w3-total-cache/)

> 11. (Done) All in One SEO Pack (http://wordpress.org/plugins/all-in-one-seo-pack/)

> 12. (Done) WordPress SEO by Yoast (http://wordpress.org/plugins/wordpress-seo/)

> 13. (Done) SEO Friendly Images (http://wordpress.org/plugins/seo-image/)

> 14. (Done) User Role Editor (https://wordpress.org/plugins/user-role-editor/)

> 15. (Done). YouTube ShortCode (https://wordpress.org/plugins/youtube-shortcode/installation/)

> 16. (Always keep in plugin folder. Don't remove) Yaarly Site Blogs (http://www.yaarly.com/) (Active)

--------------------------------------------

## For developers:
--------------------------------------------

### After deploy and plugin installed and activate, please find file wp-content/plugins/buddypress/bp-core/bp-core-filters.php and replace line 393 on this <code>/Users/maxim/Sites/schneps/wp-content/plugins/buddypress/bp-core/bp-core-filters.php</code>
--------------------------------------------

1. When you clone repo, create the wp-config.php file by copy wp-config-sample.php and write your database credentials.
1. When you make hotfix, format it into new branch on you local machine.
1. Defined constant below in wp-config.php new file
    >  define('WP_HOME','http://schneps.local');
    >
    >  define('WP_SITEURL','http://schneps.local');

--------------------------------------------    
# Composer WordPress Skeleton
--------------------------------------------

This is simply a skeleton repo for a WordPress site.  It is a light weight repo that will allow you to quickly setup your configuration.

It was inspired by [this post](http://roots.io/using-composer-with-wordpress/) by [Scott Walkinshaw](https://github.com/swalkinshaw)
Use it to jump-start your WordPress site repos, or fork it and customize it to your own liking!  If you do not have an idea how to use composer, you can also check out his [screencast](http://roots.io/screencasts/using-composer-with-wordpress/)

## Assumptions

* You have [Composer](https://github.com/composer/composer) installed.
* WordPress as a root install in `/wp/`
* Custom content directory in `/wp-content/` (cleaner)
* Media uploads directory will be in `/media/` outside `wp` folder
* Changes can be made to the `/wp-content/wp-plugins/wp-setup.php` file to customize options.

## Questions & Answers

**Q:** I want install new plugin. What should I do?
**A:** Go [here](http://plugins.svn.wordpress.org/) and find your plugin. After enter the plugin folder and enter to tag folder, after find the bigger tag value and edit your composer.json file like this: `"wpackagist-plugin/plugin-name": "bigger-tag-value",`.

**Q:** What process do I follow after a clone?  
**A:** You will follow the next steps:

*  `composer install` ran from your command line in the cloned project directory
*  Rename or Copy `wp-config-edit-and-rename.php` to `wp-config.php` and change the necessary information changes (database, etc).
*  Run your project site `/wp/wp-admin/` and under Settings >> General change the Wordpress Address to have `/wp` like `http://www.example.com/wp` and your Site Address would be `http://www.example.com`
*  Now, go fork this repo and play around with some custom installs of your own.

**Q:** Why are you including the plugins that install?  
**A:** I like these as **MY** base, just fork this repo and create any list you want by changing the `composer.json` configuration.

**Q:** Why are there multiple composer json files?  
**A:** The `composer.json` file is the base file for an install with some nice plugins.  The others are my roots examples.  To use the examples just copy and paste from one of them or rename into `composer.json`.  I will add more as I find them useful. Fork and put in a pull request to add one, I might just add it!

**Q:** I copied the `composer-roots.json` to `composer.json` How do I compile and activate the roots theme?  
**A:** This will put [roots](https://github.com/roots/roots) into the `wp-content/theme/roots` folder, but you must run the `npm install` command from the roots folder. You will then run `grunt` or `grunt watch` to compile roots assets. Now you can activate the roots theme in you Wordpress site.

**Q:** I am getting a redirect error, how do I solve?  
**A:** More than likely, you are getting this error with a new setup.  Remember, this is a custom install path using composer. Your install path should look like `http://<server-name>/wp/wp-admin/install.php`.  By default, WordPress will try to go to the default `/wp-admin/` path, but you want `/wp/wp-admin/`.