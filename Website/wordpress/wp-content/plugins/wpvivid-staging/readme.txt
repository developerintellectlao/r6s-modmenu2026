=== WPvivid Staging Pro Plugin Readme===

Contributors: WPvivid Team
Requires at least: 4.5
Tested up to: 6.6.1
Requires PHP: 5.3
Stable tag: 2.0.19
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html

== Description ==
 
WPvivid Staging Pro is an individual WordPress plugin that was split from WPvivid Backup Pro. It allows you to create a staging site and publish a staging site to live site and more.

== Full Features Lists ==

Create A Staging or Development Environment with One-Click

Install A Staging Site to A Subdomain

Push A Staging Site to the Live Site with One-Click

Update A Live Site to A Staging Site

Create A Fresh WordPress Install in A Subfolder

Choose the Content You Want to Copy from Live Site to Staging

Choose the Content You Want to Publish from Staging Site to Live

Choose to Create Staging Sites under Root Directory or /wp-content Directory

Choose to Use Same Database or Different Databases for Staging Sites

Creating A Staging Site for A MU Network

Create A Staging Site for A Single MU subsite (Both Subdomain and Subdirectory Multisite Supported)

== Support ==
We provide 7*24 support with top priority for pro users via our ticket system.
[Submit a ticket](https://wpvivid.com/submit-ticket)

== Installation ==
Download WPvivid Installer package from My Account Area on wpvivid.com >> Downloads. 
Upload the zip file to websites where you want to install it.
Activate the plugin and activate your WPvivid License.
On the Installer page, Select WPvivid Staging Pro from the plugin list and click Install Now.

== Privacy Policy and Term of Service ==
Here are our [terms and conditions](https://wpvivid.com/terms-of-service) for the use of WPvivid Staging Pro.

Contact Us
If you have any questions about these terms, please feel free to [contact us](https://wpvivid.com/contact-us).

== Frequently Asked Questions ==
1.What is WPvivid Staging Pro?
WPvivid Staging Pro works as an individual WordPress plugin that allows you to create a staging site and publish a staging site to a live site.

2.How to download WPvivid Staging and activate it?
Download WPvivid Installer package from My Account Area on wpvivid.com >> Downloads. 
Upload the zip file to websites where you want to install it.
Activate the plugin and activate your WPvivid License.
On the Installer page, Select WPvivid Staging Pro from the plugin list and click Install Now.

3.How long can I access to updates and support for?
You will have annual access namely one-year or lifetime access to updates and support, depending on which subscription period you purchase.

4.How many sites can I use a license for?
You can use a license for 3, 20, 100, or unlimited websites, depending on which subscription plan you purchase

5.Do you provide a trail or refund?
We do not provide a trail. But we do offer 100% refund within 30 days after purchase, no question asked.

6.Can I upgrade or downgrade a purchased plan?
The option is not available yet. But we are working on it and will enable it in a further release.

7.Are there any discounts available for renewal?
Yes. We give a generous 40% off discount for all types of renewals.

8.Can I reuse a licence for different sites within it’s expiration?
For personal plan: It’s limited. In order to avoid abuse of the personal version, we have set 3 days for you to deactivate after you activate a website.

For freelancer or higher plan: Yes. You can easily do this by unbinding the license from the previous websites and reactivating it on new ones, without a limit.

9.What happens if I choose not to renew after a year?
It’s sad to see. But you can still use the plugin, without any barriers. You just will not receive updates and support for the plugin.

10. I have more questions…
Please feel free to contact us using the form [here](https://wpvivid.com/contact-us).

== Change Log ==
= 2.0.19 =
- Fixed: Staging creation would get stuck at 75% in some cases.
- Fixed: Creating a staging site to a separate database could fail in some cases.
- Fixed: Creating a fresh install would cause a 404 error on live site pages in some environments.
- Fixed: Could not log in to a staging site in some environments with Divi theme.
- Fixed: Staging site did not function properly after creation in some environments.
- Fixed: Staging site data could not be deleted when the plugin was deleted.
- Fixed some UI bugs.
- Optimized the plugin code.
= 2.0.18 =
- Staging sites would not work properly when Polylang plugin was installed on the multisite.
- WP_ENVIRONMENT_TYPE in the staging site's wp-config.php would not be changed to staging upon creation.
- WPML license would not be copied to the staging site.
- Fixed some bugs in the plugin code.
- Optimized the plugin code.
- Successfully tested with WordPress 6.4.3.
= 2.0.17 =
- Successfully tested with WordPress 6.3.
- Added an option to set file permissions in staging creation, update and push.
- Fixed: Themes and plugins exclusion did not work in fresh install creation.
- Fixed: Information of local backups was cleared after staging update or push.
- Fixed incorrect staging site login url that appeared in some cases.
- Fixed: Staging creation would fail in some cases.
- Fixed: Divi’s cache was not cleared in staging creation, update and push.
- Fixed some bugs in the plugin code.
- Optimized the plugin code.
= 2.0.16 =
- Added support for regeneration of Elementor's CSS after each transfer.
- Added support for file and database size calculation.
- Fixed: Staging creation process would get stuck in some cases.
- Fixed a compatibility issue with special characters in the password of a separate database.
- Fixed a compatibility issue with capital letters in the table prefix of the live site database.
- Fixed a compatibility issue with some special rewrite rules in the live site's .htaccess file.
- Fixed some bugs in the plugin code.
- Optimized the plugin code.
= 2.0.15 =
- Added support for WPvivid Database Merging plugin.
- Optimized the plugin UI.
- Fixed some bugs in the plugin code.
= 2.0.14 =
- Upgraded and optimized the whole processes of staging creating and pushing.
- Added an option of 'Push Staging Site to Live' on staging sites.
- Fixed: Staging settings could not be saved in some environments.
- Fixed: Staging sites created with free version did not show up in the pro version.
- Fixed: No staging settings on a staging site.
- Fixed some bugs in the plugin code.
- Successfully tested with WordPress 6.1.1.
= 2.0.13 =
- Added a notice of changing remote backup folder on a staging site.
- Updated: Staging menu is merged into WPvivid Plugin menu when backup pro addon is installed.
- Fixed: Staging site for WordPress multisite would be inaccessible when it was installed to /wp-content directory.
- Fixed some bugs in the plugin code.
- Optimized the plugin code.
= 2.0.12 =
- Added: Check whether a subdomain directory is empty.
- Fixed: Creating a staging for multisite to subdomain failed.
- Fixed: Pushing subdomain staging site to live failed on multisite.
- Fixed: 'Copy staging to live' button would disappear in a push failure situation.
- Fixed: Could not edit staging comments.
- Fixed: Space was not allowed in staging comments.
- Fixed some bugs in the plugin code and optimized the plugin code.
= 2.0.11 =
- Added an option of adding a comment to the staging site when creating a staging site.
- Fixed: When creating a staging site, backup information of the live site was copied to staging site.
- Fixed: Creating a staging site failed in some cases.
- Fixed: Creating fresh installs would failed if Elementor was enabled.
- Fixed: Some themes were not copied when creating a fresh install.
- Updated: No extra WPvivid pro license is needed on staging sites if a license is enabled on the live site.
- Updated the staging site creation and update time to local time.
- Updated: Elementor pro license of the live site will not be overwritten when pushing the staging site to live.
- Optimized the plugin code.
= 2.0.10 =
- Fixed: Creating staging site failed in some environments.
- Fixed: WPvivid license would lost on staging sites in some environments.
- Added a check to table prefix of an external database.
- Deleted unused nopriv ajax from the plugin code and optimized the code.
= 2.0.9 =
- Fixed: In some cases the staging site backend would be inaccessible after pushing the staging to live.
- Added creation and update time for a staging site.
- Optimized the plugin code.
= 2.0.8 =
- Added a check to Nginx server when creating a staging site.
- Added an option of 'Files + DB' on the 'Push staging to live' page.
- Added a check to the entered directory when creating a subdomain staging, to see whether the subdomain is mapped to the directory.
- Improved parts of the plugin UI.
- Optimized the plugin code.
= 2.0.7 =
- Fixed: Fresh install admin url did not display correctly when the live site has a 'custom login url'.
- Added a checking to the staging table prefix. So the same prefix with the live site database will not be allowed.
- Optimized the plugin code.
= 2.0.6 =
- Added support to 'custom login url'.
- Refined some descriptions on the plugin UI.
- Optimized the plugin UI.
= 2.0.5 =
- Optimized the process of creating a staging site.
- Added an option to resume the task of creating a staging site when it was interrupted.
- Optimized the plugin code.
= 2.0.4 =
- Added a check to the permissions of the staging folder before creating a staging site.
- Fixed some bugs in the plugin code.
- Optimized the plugin code.
- Successfully tested with WordPress 5.8.1.
= 2.0.3 =
- Updated: Now a staging site do not need an extra WPvivid license to use the WPvivid Staging Pro plugin.
- Fixed some bugs in the plugin code.
- Optimized the plugin code.
= 2.0.2 =
- Added: WPvivid Staging Pro plugin will be white labeled as well when you enable WPvivid white label option.
- Fixed some bugs in the plugin code.
- Optimized the plugin code.
= 2.0.1 =
- Added a feature of installing the staging site to a subdomain.
- Fixed some bugs in the plugin code.
- Optimized the plugin code.
= 1.9.11 =
- Fixed: The option 'Database + Uploads folder' did not work when pushing staging to live or updating the staging site.
- Fixed some bugs in the plugin code.
- Optimized the plugin code.
= 1.9.10 =
- Updated and optimized the plugin UI.
- Updated the License page.
- Fixed some bugs in the plugin code.
- Optimized the plugin code.
= 1.9.9 =
- Fixed: Could not exclude 'views'(not actual tables) when creating a staging site.
- Fixed: Could not update a staging site properly in some cases.
- Changed: The option 'Keep permalink when transferring website' in the plugin settings is checked by default now.
- Added: Keep a record of tables selected in the last push and will push the same tables by default to the live site next time.
- Fixed some bugs in the plugin code and optimized the plugin code.
- Successfully tested with WordPress 5.6.
= 1.9.8 =
- Added a new feature of creating a staging site for a single MU subsite (both subdomain and subdirectory Multisite supported).
- Fixed some bugs in the plugin code.
- Refined some descriptions in the UI.
- Optimized the plugin code.
- Successfully tested with WordPress 5.4.2.
= 1.9.7 =
- Added a feature of creating a fresh WP install in a subfolder.
- Fixed: Some tables of MainWP Child plugin were not copied when transferring a site.
- Fixed some bugs in the plugin code and optimized the plugin code.
= 1.9.6 =
- Added support for creating a staging site for a MU network.
- Added an option to update a live site to a staging site.
- Added a log tab page.
- Added an option of Delay Between Requests in Settings.
- Added an option to keep Permalink settings in Settings.
- Optimized the plugin UI.
- Fixed some bugs in the plugin code.
= 1.9.5 =
- Added an option to automatically update the plugin. 
- Fixed: Permalinks went back to default in some cases after copying staging to live.
- Fixed: The link to the live site in the staging dashboard was broken.
- Fixed some bugs in the plugin code.
= 1.9.4 =
- Optimized the plugin UI and refined some descriptions on the UI.
- Fixed: Database tables was not completely removed after a staging site was deleted.
- Optimized the plugin code.
= 1.9.3 =
- Fixed: Staging site url and admin url were not displayed correctly in some websites.
- Fixed: Permalinks went back to default after copying staging to live in some websites.
- Fixed the compatibility issues with the W3TC plugin.
- Fixed some bugs in the plugin code.
= 1.9.2 =
- Fixed a bug that would cause staging site to be lost if an error occurs during the process of copying staging to live.
= 1.9.1 =
- The initial release of WPvivid Staging Pro, an individual WordPress plugin that was split from WPvivid Backup Pro.