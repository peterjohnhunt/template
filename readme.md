# Website Template Installer
---

### wp-cli config:
```
core config:
    extra-php: |
        if (file_exists(dirname( __FILE__ ).'/local-config.php')) {
            require_once(dirname( __FILE__ ).'/local-config.php');
        } elseif (file_exists(dirname( __FILE__ ).'/shared-config.php')) {
            require_once(dirname( __FILE__ ).'/shared-config.php');
        }
        define( 'DB_CHARSET', 'utf8' );
        define( 'DB_COLLATE', '' );
        define( 'WP_DEBUG', true );
        define( 'WP_DEBUG_DISPLAY', false );
        define( 'WP_DEBUG_LOG', true );
```
---
### .bash_profile alias
```
alias install='cd PATH_TO_TEMPLATE_FOLDER/; git pull; clear; ./installer.sh;'
```
---
### MAMP users

add to PATH in .bash_profile
```
export PATH=/Applications/MAMP/Library/bin:$PATH
```