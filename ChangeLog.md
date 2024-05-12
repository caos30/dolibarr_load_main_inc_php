---------------------------------
   dolibarr_load_main_inc_php
---------------------------------

## v 2.0 [2024-05-12]
- Fixed more issues with Windows paths.
- Used in first place the file /documents/main_module_inc_php.txt to store the cached path (it's less problematic regarding writing permissions than /custom directory)
- a SEARCH LOG has been included at the bottom of the web form detailing all the file paths used by the script, when there are troubles.
- Fixed: full compatibility with the PHPCS ruleset of Dolibarr software project.

## v 1.0 [2024-01-22]
- Fixed an issue with Windows paths. Now using around constant DIRECTORY_SEPARATOR in file paths.

## v 0.5 [2023-11-21]
- Check directory and file writing permissions.

## v 0.4 [2023-10-12]
- Trying to prevent hacker uploading a malicious file

## v 0.3 [2023-10-10]
- Changed the filename for compatibility with Dolistore.

## v 0.2 [2023-09-18]
- Add load_main.inc.php file
- Added Dolibarr suggested CONTEXT_DOCUMENT_ROOT and SCRIPT_FILENAME
- Add screenshot of main.inc.php not found user form
- Add screenshot to README.md of the user webform

## v 0.1 [2023-09-16]
- Initial roadmap commit

