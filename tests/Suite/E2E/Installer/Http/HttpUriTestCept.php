<?php

namespace AmaTeam\Vagranted\Tests\Suite\E2E\Installer\Http;

use AmaTeam\Vagranted\Filesystem\Helper;
use E2ETester;

$root = Helper::getInstallationRoot();
$executable = $root->resolve('bin/vagranted');

$uris = array_reduce(['http', 'https',], function ($carrier, $scheme) {
    $uris = array_map(function ($format) use ($scheme) {
        return "$scheme+$format://host.tld/file.$format";
    }, ['tar.gz', 'zip', 'tar.bz2',]);
    return array_merge($carrier, $uris);
}, []);

$I = new E2ETester($scenario);
foreach ($uris as $uri) {
    $I->runShellCommand("php $executable installer:test $uri");
}
