# Google Drive PHP File Upload, Download, and Delete

This application allows users to interact with their Google Drive account through a simple PHP web interface. It supports uploading, downloading, and deleting files from Google Drive. Users can select specific folders in Google Drive for these actions.

## Features
- **Upload** files to Google Drive.
- **Download** files from Google Drive, retaining the correct file format.
- **Delete** files from Google Drive.
- **Select folders** in Google Drive for organizing files.
- **Create new folders** in Google Drive (to be added if needed).

## Prerequisites
- PHP 7.4 
- A Google Cloud project with the Google Drive API enabled
- Google API PHP Client version 2.16.0 for PHP 7.4 (downloaded without Composer)
- **credentials.json** file from the Google Cloud Console for OAuth2 authentication

## Setup Instructions

### Step 1: Download and Install the Google API PHP Client
1. Download the Google API PHP Client version 2.16.0 from [GitHub](https://github.com/googleapis/google-api-php-client/releases/tag/v2.16.0).
2. Extract the contents into the project folder.

### Step 2: Enable Google Drive API
1. Visit the [Google Cloud Console](https://console.cloud.google.com/).
2. Create a new project or use an existing one.
3. Enable the **Google Drive API** for the project.
4. Create **OAuth 2.0 credentials** and download the `credentials.json` file and rename it to "credentials.json" if needed.
5. Place the `credentials.json` file into the project directory.

### Step 3: Configure the Google API Client
Edit the `google-drive-config.php` file:
```php
$client->setAuthConfig('credentials.json');
$client->setRedirectUri('YOUR_REDIRECT_URI');
```
Replace YOUR_REDIRECT_URI with the URL where your OAuth callback will be handled.

### Step 4: Upload the Files
Navigate to the Upload section of the application.
Select a file to upload from your local machine.
Optionally, select a folder where the file should be uploaded.
The file will be uploaded to your Google Drive in the selected folder (or the root directory if no folder is selected).

### Step 5: Download the Files
To download a file, you can either enter the file ID manually or choose from the most recent files listed.
Click the "Download" button to retrieve the file in the same format as it was originally stored on Google Drive.

### Step 6: Delete Files
Choose a folder to list files from.
Select the file you wish to delete and click the "Delete" button next to it.
The file will be permanently deleted from your Google Drive.
File Structure

This is the structure of the project:
```
.
├── google-api-php-client/       # Google API PHP Client
├── credentials.json             # OAuth2 credentials file
├── google-drive-config.php      # Google API configuration file
├── index.php                    # Login page
├──login.php                     # Login script
├──logout.php                    # Logout script
├── dashboard                    # Options of the aplication
├── upload.php                   # File upload script
├── download.php                 # File download script
└── delete.php                   # File delete script
```

### Security Considerations
Ensure that your credentials.json file is kept secure and not exposed publicly.

Do not store sensitive data (like API keys or client secrets) in your project directory without proper security mechanisms.

When deploying the app, ensure it is running over HTTPS for secure communication.

### Troubleshooting
Authentication issues: If you encounter any issues during authentication, verify that the redirect URI in your google-drive-config.php file matches the one registered in your Google Cloud Console project.

File upload/download issues: If file uploads or downloads are not functioning as expected, verify that:

The file size does not exceed Google Drive's API limits;

The file permissions are correct and the app has sufficient access rights to the file;

OAuth issues: If the application is not prompting for OAuth consent, ensure that the access token is correctly stored and refreshed in the session.

Google Drive API documentation: https://developers.google.com/drive/api/guides/about-sdk?hl=pt-br

Useful links: https://www.postman.com/postman/google-api-workspace/documentation/uqkp49c/google-drive-api

### License
This project is licensed under the MIT License - see the LICENSE.md file for details.

### Author
Ivan Gonçalves da Silva
