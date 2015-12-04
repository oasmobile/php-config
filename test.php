#! /usr/local/bin/php
<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 12/5/15
 * Time: 12:10 AM
 */

require_once __DIR__ . "/vendor/autoload.php";

\Oasis\Mlib\Config\test\TestConfiguration::instance()->loadYaml(
    "config.yml",
    [
        "/tmp",
    ]
);
