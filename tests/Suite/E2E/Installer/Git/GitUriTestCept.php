<?php

namespace AmaTeam\Vagranted\Tests\Suite\E2E\Installer\Git;

use AmaTeam\Vagranted\Filesystem\Helper;
use E2ETester;

$root = Helper::getInstallationRoot();
$executable = $root->resolve('bin/vagranted');
$uris = [
    'git+ssh://git@github.com/ama-team/vagranted-php.box.git',
    'git+https://git@github.com/ama-team/vagranted-php.box.git',
];

$I = new E2ETester($scenario);
foreach ($uris as $uri) {
    $I->runShellCommand("php $executable installer:test $uri");
}
