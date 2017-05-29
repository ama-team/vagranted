<?php

namespace AmaTeam\Vagranted\Tests\Suite\E2E\Installer\Http;

use AmaTeam\Vagranted\Filesystem\Helper;
use E2ETester;

$root = Helper::getInstallationRoot();
$executable = "$root/bin/vagranted";
$set = 'https+tar.gz://github.com/ama-team/vagranted-php-box/archive/0.1.0.tar.gz';

$I = new E2ETester($scenario);
$I->runShellCommand("'$executable' sets:install $set --log-level debug");
$I->runShellCommand("'$executable' sets:list");
$I->seeInShellOutput($set);

