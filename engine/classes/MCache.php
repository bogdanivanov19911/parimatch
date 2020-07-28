<?php

$mCache = '';
$mCache = new Memcached();
$mCache->addServer('127.0.0.1',11211);