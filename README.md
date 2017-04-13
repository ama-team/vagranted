# This project is a stub

If you see this, this project is just an idea without MVP. Don't worry,
it will be thriving one day.

# Vagranted

Vagranted is a tool to help in sharing similar vagrant file structure 
across projects. Even if you have the option to create and share boxes
(we don't), most likely  you'll hit the necessity of specifying similar
customizations to different project Vagrantfiles. If you're running a
web studio, you'll be fatigued pretty soon simply by managing those
Vagrantfiles.

So, why not to take the option of creating Vagrantfile template and
render it with project-specific context? That's exactly what Vagranted 
do: take a Twig, add some fancy http and git loaders and allow end 
users to render their boxes configuration from templates.

## Installation

```bash
composer require --dev ama-team/vagranted
```

## Usage

Just hit

```bash
vendor/bin/vagranted
```

In the directory with `Vagrantfile.twig` and `.vagranted.yml` or one
of it's descendants. That will render `Vagrantfile` you may use to 
work with later.
