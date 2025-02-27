# Functionality Documentation - Google Drive File Management

This documentation provides a detailed overview of the PHP files and their functionality for managing files in Google Drive. This includes uploading, downloading, deleting files, and creating folders using the Google Drive API without the use of Composer.

## Table of Contents

1. [Introduction](#introduction)
2. [File Descriptions](#file-descriptions)
    - [dashboard.php](#dashboardphp)
    - [upload.php](#uploadphp)
    - [download.php](#downloadphp)
    - [delete.php](#deletephp)
    - [load_subfolders.php](#load_subfoldersphp)
    - [google-drive-config.php](#google-drive-configphp)
3. [General Flow](#general-flow)
4. [Limitations and Improvements](#limitations-and-improvements)

## Introduction

This project provides a simple web interface to interact with Google Drive. It allows users to upload, download, and delete files from Google Drive, as well as organize files within folders and subfolders. The functionality is implemented using PHP and the Google Drive API, with no dependency on Composer.

## File Descriptions

### 1. dashboard.php
**Objective:**  
The dashboard serves as the main control panel for the user. It provides navigation links to the various functionalities of the system: file upload, file download, file deletion, and logout.

**Content:**
- A navigation menu with links to:
  - Upload Files
  - Download Files
  - Delete Files
  - Logout

### 2. upload.php
**Objective:**  
This page allows the user to upload files to Google Drive.

**Functionality:**
- **Display of folders:** The user can view the top-level folders (directly in the root of Google Drive).
- **Folder and subfolder selection:** When a parent folder is selected, the available subfolders are displayed using AJAX, allowing the user to choose where to store the file.
- **Folder creation:** Users can create new folders within the selected folder or in the root directory of Google Drive.
- **File upload:** Once a file is selected, it is uploaded to the chosen folder or subfolder.

**Upload Process:**
1. The user selects a file.
2. The user selects a parent folder.
3. The system loads the subfolders within the selected parent folder.
4. The user selects a subfolder (or uses the parent folder).
5. The file is uploaded to Google Drive.

### 3. download.php
**Objective:**  
This page allows the user to download files from Google Drive.

**Functionality:**
- **Display of folders:** The user can view the top-level folders (directly in the root of Google Drive).
- **Loading of files:** After selecting a parent folder and its subfolders, the files are displayed for download.
- **File download:** The user can click on a file to download it.

**Download Process:**
1. The user selects a parent folder.
2. The system loads the subfolders within the selected parent folder.
3. The user selects a subfolder.
4. The system lists the available files in the selected folder.
5. The user clicks on a file to download it.

### 4. delete.php
**Objective:**  
This page allows the user to delete files from Google Drive.

**Functionality:**
- **Display of folders:** The user can view the top-level folders (directly in the root of Google Drive).
- **File deletion:** The user can choose a file and delete it from Google Drive.

**Deletion Process:**
1. The user selects a parent folder.
2. The system loads the files within the selected folder.
3. The user selects the file they want to delete.
4. The file is deleted from Google Drive.

### 5. load_subfolders.php
**Objective:**  
This PHP file is used to load subfolders via AJAX when a user selects a parent folder for file upload or deletion.

**Functionality:**
- When a parent folder is selected, an AJAX request is sent to this file to fetch the subfolders inside that folder.
- The file receives a `parent_id` via the POST method, queries Google Drive for the subfolders of the selected parent, and returns a list of subfolders in HTML format.

**Process:**
1. The file receives a `parent_id` via POST.
2. The system queries the Google Drive API for subfolders in the selected parent folder.
3. The available subfolders are returned in HTML format and displayed in the dropdown.

**AJAX Response:**
- If subfolders exist, they are displayed in the dropdown.
- If no subfolders are found, an option "No subfolders found" is displayed.

### 6. google-drive-config.php
**Objective:**  
This file configures the Google Drive API client.

**Functionality:**
- Configures the Google Drive API client with necessary credentials.
- Authenticates the user and initializes the Google Drive service for file operations.

## General Flow

1. **Accessing the Dashboard (`dashboard.php`):**  
   The user starts at the dashboard, where they can navigate to file upload, download, or deletion functionalities.

2. **Uploading Files (`upload.php`):**  
   The user selects a folder (and/or subfolder), picks a file, and uploads it to Google Drive.

3. **Downloading Files (`download.php`):**  
   The user selects a folder (and/or subfolder), views available files, and clicks a file to download it.

4. **Deleting Files (`delete.php`):**  
   The user selects a folder, views the files within it, and deletes the selected file from Google Drive.

5. **Subfolder Loading via AJAX (`load_subfolders.php`):**  
   When a folder is selected, the page makes an AJAX request to load the available subfolders.

## Limitations and Improvements

### Limitations:
- **Lack of Permission Validation:**  
   The system does not check if the authenticated user has the necessary permissions to access specific files or folders.
- **No File Type Validation:**  
   The application does not perform advanced validation of file types before uploading.

### Improvements:
- **User Authentication:**  
   Add permission checks to ensure only the authenticated user can access their own files and folders.
- **Error Management:**  
   Enhance error handling and provide user-friendly messages for upload, download, or deletion failures.
- **File Validation:**  
   Implement checks for allowed file types and file size limits before uploading files.

---

