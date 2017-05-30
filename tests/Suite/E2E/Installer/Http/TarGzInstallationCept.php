<?php

namespace AmaTeam\Vagranted\Tests\Suite\E2E\Installer\Http;

use AmaTeam\Vagranted\Filesystem\Helper;
use E2ETester;

$root = Helper::getInstallationRoot();
$executable = "$root/bin/vagranted";
$set = 'https+tar.gz://github.com/ama-team/vagranted-php-box/archive/0.1.0.tar.gz';

$I = new E2ETester($scenario);
if (DIRECTORY_SEPARATOR !== '/') {
    // yeah this is lame
    $I->wantTo('Not ot test tar.gz installer because it isn\'t supported for non-unix platforms');
    return;
}
$I->wantTo('Test tar.gz installer');
$I->runShellCommand("php $executable sets:install $set --log-level debug");
$I->runShellCommand("php $executable sets:list");
$I->seeInShellOutput($set);
