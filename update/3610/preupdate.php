<?php

// create legacy config folder
$legacyFolder = PIMCORE_CONFIGURATION_DIRECTORY . "/LEGACY";
if (!is_dir($legacyFolder)) {
    mkdir($legacyFolder, 0777, true);
}

// QR-CODES
$dir = PIMCORE_CONFIGURATION_DIRECTORY . "/qrcodes";

if(is_dir($dir)) {
    $file = Pimcore\Config::locateConfigFile("qrcode.json");
    $json = \Pimcore\Db\JsonFileTable::get($file);
    $json->truncate();

    $files = scandir($dir);
    foreach ($files as $file) {
        if (strpos($file, ".xml")) {
            $name = str_replace(".xml", "", $file);
            $thumbnail = \Pimcore\Model\Tool\Qrcode\Config::getByName($name);
            $thumbnail = object2array($thumbnail);

            $thumbnail["id"] = $thumbnail["name"];
            unset($thumbnail["name"]);

            $json->insertOrUpdate($thumbnail, $thumbnail["id"]);
        }
    }

    // move data
    rename($dir, $legacyFolder . "/qrcodes");
}


// SQL REPORTS
$dir = PIMCORE_CONFIGURATION_DIRECTORY . "/sqlreport";

if(is_dir($dir)) {
    $file = Pimcore\Config::locateConfigFile("custom-reports.json");
    $json = \Pimcore\Db\JsonFileTable::get($file);
    $json->truncate();

    $files = scandir($dir);
    foreach ($files as $file) {
        if (strpos($file, ".xml")) {
            $name = str_replace(".xml", "", $file);
            $thumbnail = \Pimcore\Model\Tool\CustomReport\Config::getByName($name);
            $thumbnail = object2array($thumbnail);

            $thumbnail["id"] = $thumbnail["name"];
            unset($thumbnail["name"]);

            $json->insertOrUpdate($thumbnail, $thumbnail["id"]);
        }
    }

    // move data
    rename($dir, $legacyFolder . "/sqlreport");
}
