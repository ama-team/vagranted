<?php

namespace AmaTeam\Vagranted\Tests\Suite\E2E\Installer\Http;

use AmaTeam\Vagranted\Filesystem\Helper;
use E2ETester;

$root = Helper::getInstallationRoot();
$executable = $root->resolve('bin/vagranted');
$set = 'https+tar.gz://github.com/ama-team/vagranted-php-box/archive/0.1.0.tar.gz';

$I = new E2ETester($scenario);
// yeah this is lame
if (DIRECTORY_SEPARATOR !== '/') {
    $scenario->skip('Tar.gz inflation is not supported on windows');
    return;
}
$I->wantTo('Test tar.gz installer');
$I->runShellCommand("php $executable sets:install $set --log-level debug");
$I->runShellCommand("php $executable sets:list");
$I->seeInShellOutput($set);
