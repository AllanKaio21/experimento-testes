<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host='.getenv('GUIA_DBHOST').';dbname='.getenv('GUIA_DBNAME'),
    'username' => getenv('GUIA_DBUSER'),
    'password' => getenv('GUIA_DBPASS'),
];