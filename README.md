# Google Drive Integration Project - PHP 7.4
This project allows integration with Google Drive using the Google Drive API. It provides functionalities such as uploading, downloading, deleting files, and viewing files directly from a PHP application.


##Requirements
PHP 7.4
Web Server (e.g., Apache or Nginx)
Google Cloud Console to configure the project and obtain the necessary credentials


##Features
File Upload: Upload files to Google Drive.
File Download: Download files from Google Drive.
File Deletion: Delete files from Google Drive.
File Listing: List the most recent files from Google Drive.


##Setup

###Step 1: Set Up Google Cloud Console
Go to the Google Cloud Console.
Create a new project or use an existing project.
Enable the Google Drive API:
In the left menu, go to APIs & Services > Library.
Search for Google Drive API and enable it for your project.
Configure OAuth 2.0 for authentication:
Go to APIs & Services > Credentials.
Click Create Credentials and choose OAuth 2.0 Client ID.
In the OAuth consent screen, fill out the necessary information (product name, support email).
Choose Web application as the application type.
In Authorized redirect URIs, add the redirect URI for your project. Example: https://your_project_name/.
Download the credentials file (raname it to: credentials.json) and place it in the root of your project.

###Step 2: Download the Google API Client Library
This project uses the google-api-php-client version 2.16.0, which is compatible with PHP 7.4. To install it without Composer:

Download the release from GitHub:

Go to the google-api-php-client v2.16.0 release.
Download the ZIP file and extract it into the google-api-php-client folder inside your project.
Ensure your project structure contains the google-api-php-client folder and the autoload.php file inside it.

###Step 3: Configure google-drive-config.php
This file handles authentication with Google Drive using the credentials.json file you obtained from the Google Cloud Console.

/google-drive-config.php: $client->setRedirectUri('https://your_project_name/');



##Step 4: File Structure
Your project should have the following directory structure:

/googleDrive

│

├── /google-api-php-client/          # Google API Client Library

│

├── google-drive-config.php         # Google Drive API Configuration

├── index.php                       # Login page

├── dashboard.php                   # Dashboard to interact with files

├── upload.php                      # File upload page 

├── download.php                    # File download page

├── delete.php                      # File deletion page

└── credentials.json                # Google credentials file


###Step 5: index.php File

The index.php file is the login page, where the user can authenticate with their Google account. The authentication flow will redirect the user to Google, and once authenticated, they will be redirected back to your site.
After login, the user will be redirected to the main page, where they can interact with Google Drive files.


License
This project is licensed under the MIT License.


Notes
Redirect URI and Security: Ensure that the redirect URI in the Google Cloud Console matches the one used in the code. Otherwise, the authentication will fail.
Scope Limitation: The code above uses the scope Google_Service_Drive::DRIVE, which grants full access to the user's Google Drive. Make sure this aligns with the permissions required for your use case.
Sessions: Using sessions ($_SESSION) is crucial to store the access token, ensuring the user doesn’t have to authenticate each time.
