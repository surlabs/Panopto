<div alt style="text-align: center; transform: scale(.5);">
	<picture>
		<source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/surlabs/Panopto/ilias8/templates/images/GitBannerPanopto2.png" />
		<img alt="Panopto" src="https://raw.githubusercontent.com/surlabs/Panopto/ilias8/templates/images/GitBannerPanopto2.png" />
	</picture>
</div>

# Panopto Repository Object Plugin for ILIAS 9
This plugin allows users to embed Panopto videos in ILIAS as repository objects

## Installation & Update

### Installation steps
1. Create subdirectories, if necessary for Customizing/global/plugins/Services/Repository/RepositoryObject/ or run the following script from the ILIAS root

```bash
mkdir -p Customizing/global/plugins/Services/Repository/RepositoryObject
cd Customizing/global/plugins/Services/Repository/RepositoryObject
```

3. In Customizing/global/plugins/Services/Repository/RepositoryObject/ **ensure you delete any previous Panopto folder**
4. Then, execute:

```bash
git clone https://github.com/surlabs/Panopto.git
git checkout ilias9
```

Ensure you run composer install at platform root before you install/update the plugin
```bash
composer install --no-dev
```

Ensure you run npm install at any code change within your ILIAS9 platform
```bash
npm install
```

Run ILIAS update script at platform root
```bash
php setup/setup.php update
```

**Ensure you don't ignore plugins at the ilias .gitignore files and don't use --no-plugins option at ILIAS setup**

## Configuration
### Panopto Instance

#### Identity Provider
1. Add new Provider:
* Login to your Panopto instance as administrator, Navigate to "System" -> "Identity Providers" and add a new provider with the following data:
* **Provider Type**: *BLTI*
* **Instance Name**: choose an identifier, e.g: "*ilias.myinstitution*" (will be needed in the plugin configuration)
* **Friendly Description**:	choose any description
* **Parent folder name**: choose a folder, where all objects coming from this ILIAS instance will be created
* **Suppress access permission sync on LTI link**: Set 'true' if you want to stop the behavior to revoke Viewer permission of other course folders.
* **Application Key**: save this key for the plugin configuration
* **Bounce page blocks iframes**: False (don't check)
* **Default Sign-in Option**: False (don't check)
* **Personal folders for users**: Choose which kind of users should get personal folders (can be changed later)
* **LTI Username parameter override**:	Leave empty
* **Show this in Sign-in Dropdown**: False (don't check)

2. Create an API User:
* Navigate to "System" -> "Users" 
* Click on "Batch Create" (for some reason you can't create single external users)
* As "Provider", choose the previously created identity provider
* Enter a username and an email address comma-separated, e.g. "api_user, example@myinstitution.com"
* Uncheck the checkbox "Create a personal folder for each user" (except if you want a personal folder for the api user for some reason)
* Click "Preview" and on the next Screen "Create Users"

After the user is created, open the user details by clicking on the user's name. Check the role "Administrator" under "Info" -> "System Roles" and click "Update Roles".

##### REST Client
Navigate to "System" -> "API Clients" and add a new client. Enter a Client Name of your choice and select the Client Type "User Based Server Application". All other fields can be left empty. Write down the Client Name, Client ID and Client Secret for later.

Unfortunately, the previously created api user can not be used for the REST api, as it has to be an internal user. So create another user:
* Navigate to "System" -> "Users"
* Click on "New"
* Fill out the form as follows:
    * enter the required fields 
    * write down the username and password for later
    * uncheck the Options "Email user when recorded..." and "Create a personal folder..."
* Create the user

### ILIAS
Login to your ILIAS platform as an administrator. Navigate to "Administration" -> "Plugins" and look for the "Panopto" plugin and choose "Configure". Configure the plugin as follows:
* **General**
    * **Object Title**: choose how this object type should be named in ILIAS (displayed e.g. when creating a new object in the repository)
* **SOAP API**
    * **API user**: enter the name of the previously created API user (e.g. "api_user")
    * **Hostname**: the hostname of your Panopto instance without "https://". E.g. "demo.panopto.com"
    * **Instance Name**: the same identifier you chose when creating the identity provider in Panopto
    * **Application Key**: the key which appeared when creating the identity provider in Panopto
    * **User Identification**: chose which user field will be used as user identification (either the login or the external account)
* **REST API**
    * **API User**: the user created in the section [REST Client](#rest-client)
    * **API Password**: the password for this user
    * **Client Name**: name of REST Client created in the section [REST Client](#rest-client)
    * **Client ID**: ID of REST Client created in the section [REST Client](#rest-client)
    * **Client-Secret**: Secret of REST Client created in the section [REST Client](#rest-client)
 
# Authors
* Initially created by studer + raimann ag, switzerland
* Further maintained by fluxlabs ag, switzerland
* Revamped and currently maintained by SURLABS, spain [SURLABS](https://surlabs.com)

# Bug Reports & Discussion
- Bug Reports: [Mantis](https://www.ilias.de/mantis) (Choose project "ILIAS plugins" and filter by category "Panopto")
- SIG Panopto [Forum](https://docu.ilias.de/goto_docu_frm_13755.html)

# Version History
* The version 9.x.x for **ILIAS 9** developed and maintained by SURLABS can be found in the Github branch **ilias9**
* The version 8.x.x for **ILIAS 8** developed and maintained by SURLABS can be found in the Github branch **ilias8**
* The version 7.x.x for **ILIAS 7** developed and maintained by SURLABS can be found in the Github branch **ilias7**
* The previous plugin versions for ILIAS <8 is archived. It can be found in https://github.com/fluxapps/Panopto
