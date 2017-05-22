# This project is a stub

If you see this, this project is just an idea without MVP. Don't worry,
it will be thriving one day.

# Vagranted

Vagranted is a simple PHP tool to build file sets from using *resource
sets* - packages consisting of assets and templates. Vagranted primary
target is avoiding duplication in Vagrant files - if you have lots of 
similar projects with small differences, managing and upgrading their
Vagrant definitions is a PITA - but you can use it every time you have
a common set of files and/or templates and need to render final file 
set in several similar projects.

If you're interested, please proceed to [usage](#usage) section.

## Installation

```bash
composer require --dev ama-team/vagranted
```

## Usage

Vagranted is dumb simple and operates on thing called a *resource set*.
Resource set is just a collection of files, each of which may be just a
file, an asset (meaning it will be copied to target directory during 
the build phase) or template (meaning it will be rendered to target 
directory during the build phase). Each resource set may define other 
resource sets as parents - which may not only be used to deliver 
assets/templates, but also be used in Twig inheritance system. Finally,
each resource set declares context - an arbitrary structure that will
be used during template rendering. When build phase is triggered, all 
contexts are squashed into one, overwriting collisions, so latter 
contexts may override previous ones. Resource set configuration - 
file locators and context - can be specified in `vagranted.yml` in 
root of resource set:

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
git+ssh://github.com/ama-team/vagranted-base-box.git#0.1.0
  Vagrantfile.twig
  
git+ssh://github.com/ama-team/vagranted-php-box.git#0.2.3
  resources/vagrant/chef/cookbooks/vagrant-php/metadata.rb
  resources/vagrant/chef/cookbooks/vagrant-php/recipes/default.rb
  vagranted.yml
  Vagrantfile.twig
  
http+tgz:///private-quarters.amateurs.io/vagranted/boxes/codeigniter.tgz
  resources/vagrant/chef/cookbooks/vagrant-php/recipes/codeigniter.rb
  vagranted.yml
  
(project dir)
  vagranted.yml
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

Every command accepts `--project-directory`, `--data-directory` and
`--working-directory` options, though i don't remember if last is used
anywhere.
