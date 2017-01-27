#m183

Git repository for school project.

###Configuration

Create "config.php" in /app folder with following content:
```
<?php

return [

    "url" => "http://localhost/m183/public/",

    "debug" => true,

    "db" => [
        "host" => "localhost",
        "user" => "root",
        "database" => "m183",
        "password" => "",
        "port" => "3306",
        "charset" => "utf8",
        "collation" => "utf8_unicode_ci",
    ],

    'mail' => [
        "host" => "",
        "username" => "",
        "password" => "",
        "secure" => "",
        "port" => "",
    ],

];
```