# Vagranted

[![Packagist](https://img.shields.io/packagist/v/ama-team/vagranted.svg?style=flat-square)](https://packagist.org/packages/ama-team/vagranted)
[![CircleCI/Master](https://img.shields.io/circleci/project/github/ama-team/vagranted/master.svg?style=flat-square)](https://circleci.com/gh/ama-team/vagranted/tree/master)
[![AppVeyor/Master](https://img.shields.io/appveyor/ci/etki/vagranted/master.svg?style=flat-square)](https://ci.appveyor.com/project/etki/vagranted)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/ama-team/vagranted/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/ama-team/vagranted)
[![Code Climate](https://img.shields.io/codeclimate/github/ama-team/vagranted.svg?style=flat-square)](https://codeclimate.com/github/ama-team/vagranted)
[![Coveralls/Master](https://img.shields.io/coveralls/ama-team/vagranted/master.svg?style=flat-square)](https://coveralls.io/github/ama-team/vagranted?branch=master)

## Installation

```bash
composer require --dev ama-team/vagranted
```

## What is Vagranted?

Vagranted is aimed for a narrow case of having necessity to manage
several identical or similar configurations at once. It is quite common
when developers have to manage several projects with lots of 
similarities and need to smoothly override little things in particular
projects. When it comes to code, we have dependency management,
runtime configuration and inheritance for that, but things are 
different with configuration files - say, Vagrantfiles with slightly 
different provision, CI pipelines that differ only in repository name, 
deploy configurations, even when your READMEs are 50% identical you'll 
want some tool that would help you to template them. Vagranted solves 
this problem in following way - end user may specify asset and template
sources (called resource sets) which Vagranted will download, squash
contexts from all configurations into one huge array, then copy assets 
and render templates using Twig into target directory. And, of course,
Twig inheritance is available out of the box - whatever you need to 
render, you can create one master template, specify gazillion of 
blocks, inherit it in all dependent projects and override only ones you
need.

## Usage

Vagranted is dumb simple and operates on thing called a *resource set*.
Resource set is just a collection of files, each of which may be just a
file (no direct usage, may be used in template inheritance), an asset 
(meaning it will be copied to target directory during the compilation 
phase) or template (meaning it will be rendered to target directory 
during the compilation phase). Each resource set may define other 
resource sets as parents - which may not only be used to deliver 
assets/templates, but also be used in Twig inheritance system (more on 
that later). Finally, each resource set declares context - an arbitrary 
structure that will be used during template rendering. When compilation 
phase is triggered, all contexts are squashed into one, overwriting 
collisions, so latter contexts may override previous ones. Resource set 
configuration - file locators and context - can be specified in 
`vagranted.yml` in root of resource set::

```yml
assets:
  - resources/vagranted/**/* # yeah, that's glob pattern
  - pattern: **/*.rb
    exclusions: 
      - helpers/**/*
templates:
  - Vagrantfile.twig # will be rendered as dirname + basename
  - pattern: **/*.twig
    exclusions:
      - app/Resources/**/*
      - Vagrantfile.twig
    name: 'rendered/{{ directory }}/{{ basename }}'
dependencies:
  base-box: 'git+ssh://github.com/ama-team/vagranted-base-box.git#0.1.0'
  php-box: 'git+ssh://github.com/ama-team/vagranted-php-box.git#0.2.3'
context:
  php: 
    version: '7.1'
    extensions:
      - mbstring
      - dom
  docker:
    images:
      mysql: 'mysql:5.6'
```

Using Vagrantfile inheritance as example, typical resource set chain 
may look like this:

```
base-box: git+ssh://github.com/ama-team/vagranted-base-box.git#0.1.0
  Vagrantfile.twig
  # no vagranted.yml - that's totally ok, empty values will be used
  
php-box: git+ssh://github.com/ama-team/vagranted-php-box.git#0.2.3
  resources/vagrant/chef/cookbooks/vagrant-php/metadata.rb
  resources/vagrant/chef/cookbooks/vagrant-php/recipes/default.rb
  vagranted.yml # depends on base-box
  Vagrantfile.twig # {% extends 'base-box:Vagrantfile.twig' %}
  
codeigniter: http+tgz:///private-quarters.amateurs.io/vagranted/boxes/codeigniter.tgz
  resources/vagrant/chef/cookbooks/vagrant-php/recipes/codeigniter.rb
  vagranted.yml # depends on php-box
  
(project dir)
  vagranted.yml # depends on codeigniter
```

The first resource set declares only basic template file - it is 
implied that it will be used only for extending.  
The second resource set inherits from first one, and it's own 
Vagrantfile.twig extends Vagrantfile.twig from the first set and 
declares it as a template. Also, second resource set defines two assets
that will be included in final build.  
The third resource set adds extra asset that also will be included in
build, and changes Chef run list using context.  
Finally, the project itself alters context again using vagranted.yml in
the directory where build will take place.

### Template inheritance

To exploit template inheritance, just specify `<dependency-name>:`
prefix before file name in `{% extends %}` directive:

```yml
# vagranted.yml
dependencies:
  base-box: 'git+ssh://git@github.com/ama-team/vagranted-base-box.git#0.1.0'
```

```Vagrantfile.twig
{% extends 'base-box:Vagrantfile.twig' %}
{% block machine_name %}shooting-range{% endblock %}
```

### Invocation

During regular usage, you will need only single compile command

```bash
vendor/bin/vagranted compile
```

You can also inspect the resulting combination of resource sets using 
corresponding command:

```bash
vendor/bin/vagranted compile:inspect
```

There are more commands bundled in, but chances are you won't need 
them, and you can always list them using binary without arguments:

```
vendor/bin/vagranted
```

Every command accepts `--project`, `--data-dir`, `--target` and 
`--working-dir` options, though i don't remember if last one is used 
anywhere.

### Restrictions

Currently you have to set distinct id for every dependency set. That 
means that if your parent resource set lists dependency `alpha`, 
specifying another dependency with same name in current resource set 
will overwrite it and result in unexpected behavior. This may be 
changed in future releases, but there is simply no time for that now.

### Contributing

Feel free to contribute and add new features! Please send pull requests
for `dev` branch, i'm somewhat oldschool and don't like to push 
anything to `master` until it is battle-tested.

### Testing

Simply run `composer test`

### Developer shield cellar

Following shields are pointing to dev branch in corresponding services

[![Packagist/Prerelease](https://img.shields.io/packagist/vpre/ama-team/vagranted.svg?style=flat-square)](https://packagist.org/packages/ama-team/vagranted)
[![CircleCI/Dev](https://img.shields.io/circleci/project/github/ama-team/vagranted/dev.svg?style=flat-square)](https://circleci.com/gh/ama-team/vagranted/tree/dev)
[![AppVeyor/Dev](https://img.shields.io/appveyor/ci/etki/vagranted/dev.svg?style=flat-square)](https://ci.appveyor.com/project/etki/vagranted)
[![Scrutinizer/Dev](https://img.shields.io/scrutinizer/g/ama-team/vagranted/dev.svg?style=flat-square)](https://scrutinizer-ci.com/g/ama-team/vagranted)
[![Coveralls/Dev](https://img.shields.io/coveralls/ama-team/vagranted/dev.svg?style=flat-square)](https://coveralls.io/github/ama-team/vagranted?branch=dev)
