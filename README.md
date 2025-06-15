ipa-chart
=========
To run locally, requires apache2, mysql-server, php.
Restart apache2 server and place index.html and all associated files in /var/www/http, access with localhost in browser. 

Running tests
-------------
Ensure PHP is installed. Execute the test suite with:

    php -d short_open_tag=On tests/ChompTest.php

