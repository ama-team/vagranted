<?php

namespace AmaTeam\Vagranted\Tests\Suite\E2E;

use AmaTeam\Vagranted\Filesystem\Helper;
use Codeception\Codecept;
use Codeception\Configuration;
use E2ETester;

$root = Helper::getInstallationRoot();
$executable = $root->resolve('bin/vagranted')->toPlatformString();
$project = $root->resolve('tests/Fixtures/E2E/1/project')->toPlatformString();
$target = $root->resolve('tests/Fixtures/E2E/1/target')->toPlatformString();

$I = new E2ETester($scenario);
$I->runShellCommand("php $executable compile --project $project --target $target --log-level debug");
$I->amInPath($target);
$I->seeFileFound('asset');
$I->seeFileFound('template');
$I->seeInThisFile('variable: 42');
$I->seeFileFound('Vagrantfile');
