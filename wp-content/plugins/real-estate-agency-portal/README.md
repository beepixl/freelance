# Download #

* git clone https://sanljiljan@bitbucket.org/sanljiljan/themes-customization.git

### What is this repository for? ###

* Additional theme for real estate script

### How do I get set up? ###

1. Install portable XAMPP Version 5.6.3 to c:/ and SourceTree or similar git client
2. Create folder C:\xampp\htdocs\themes-customization
3. Clone repository inside this folder
4. Create empty files install.txt and sitemap.xml inside C:\xampp\htdocs\themes-customization\
5. Install it as usual
6. Copy application/config/production/database.php into application/config/development/database.php
7. Copy application/config/cms_config.php into application/config/development/cms_config.php
8. Discard changes on application/config/production/database.php and application/config/cms_config.php in SourceTree or other git tool
9. Remove file C:\xampp\htdocs\themes-customization\install.txt
10. Now system will autoload configuration from development folder if index.php is located on C:\xampp\htdocs\themes-customization\index.php


### Contribution guidelines ###

* Please create new branch from develop branch called by your name and make commits only there, when some work is completed please ask for pull request.
* Please work only on one branch on same time and before any work fetch last changes.

### Who do I talk to? ###

* Repo owner, sanljiljan@geniuscript.com