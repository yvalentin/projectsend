### The proposed structure changes from this branch will need automation for the following task during updates

- Move the sys.config.php file from includes/ to config/. This probably needs to be executed regularly until providers adapt their own routines to comply with the new directory structure (eg: Softaculous installer). It would probably be a good idea to always look for the file on the old location if it doesn't exist on the new one (this could mean that the system was never installed or that the file could not be moved for example).
- Move the branding logo image uploaded by the user from /img/custom/logo to /upload/branding
- Clean up the directory by removing the files from the old branch
