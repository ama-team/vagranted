# This project is a stub

If you see this, this project is just an idea without MVP. Don't worry,
it will be thriving one day.

# Vagranted

Vagranted is a tool to help in sharing similar vagrant file structure 
across projects. Even if you have the option and infrastructure to 
create and share boxes (we don't), most likely  you'll hit the 
necessity of specifying similar customizations to different project 
Vagrantfiles. If you're running a web studio, you'll be fatigued 
pretty soon simply by managing those Vagrantfiles.

Vagranted takes another approach on Vagrant inheritance: target 
Vagrantfile is rendered from a *source base* - a Vagrantfile 
template, optional context and optional file set. Source base may 
inherit from another one, which makes possible to define a common
source base and reuse it in child projects with only altering context
variables.

If you're interested, please proceed to [usage](#usage) section.

## Installation

```bash
composer require --dev ama-team/vagranted
```

## Usage

Vagranted uses *source base* to render final Vagrantfile. *Source base*
consists of:

- Vagrantfile.twig
- (Optionally) other files, conventionally stored in resources/vagrant
- (Optionally) vagranted.yml configuration file

Whenever Vagranted is run, it simply renders Vagrantfile.twig to 
Vagrantfile using context which will be discussed later. Twig template 
engine, which is used in this project, allows *template inheritance*
that allows source base to extend another source base, and that allows
source base to use another source bases as starting points. To do so,
you need to explicitly define inheritance in `vagranted.yml`:

```yml
extends:
  ama-base-box: ssh+git://git@github.com/ama-team/vagranted-base-box
  root-box: https+tgz://domain/box.tgz 
```

Source base names are necessary and can't overlap even in different
source bases - Twig loaders are context-free and have to resolve 
template names exactly the same without any regard which template is 
processed right now. To use Vagrantfile.twig from another source base,
you need to extend from it:

```twig
{% extends 'ama-base-box://Vagrantfile.twig' %}
```

Now you can override particular blocks stored in extended file:

```twig
{% block chef_run_list %}
    {{ parent() }}
    "mysql",
{% endblock %}
```

Contexts and file sets are deeply merged: contexts overwrite matching 
keys as deep as possible, files overwrite each other.

### vagranted.yml structure

`vagranted.yml` has very simple structure:

```yaml
extends:
  %name%: %uri%
  ssh+git: ssh+git://git@github.com:user/repo.git#0.1.0
  https+git: https+git://github.com/user/repo.git#0.1.0
  https+tgz: https+tgz://github.com/user/repo/archive/0.1.0.tgz
  http+zip: http+zip://github.com/user/repo/archive/0.1.0.zip
context:
  image: ubuntu/xenial64
  feature_flags:
    docker: true
```

### Invocation

Currently there is only one command used to render Vagrantfile:

```bash
vendor/bin/vagranted [--workspace|-w path] [--directory|-d path] [target]
```

It will find Vagrantfile.twig in current directory or one of it's 
parents and will render it to `target` argument (`Vagrantfile` next to
`Vagrantfile.twig` by default)
