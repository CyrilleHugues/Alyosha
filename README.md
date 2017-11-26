# Alyosha

## What is this ?

Alyosha is a framework for IRC bot, and basically a event driven framework to fiddle with for any purpose
– be it a cross social media bot or a web server (please don't do this).

## Why ?

Because the other framework for IRC were entirely focused on IRC and I wanted something cross medium.
By writing a module, you can wire up Slack, Facebook bot, ...

It's written in PHP because I like this language pretty much and I started before thinking about the
asynchronous needs of this project.

## Usage

### Requirements

-   PHP, no kidding
-   [composer](https://getcomposer.org/download/)

### Installation

Create a new composer project with this library.

```bash
mkdir myBot && cd myBot
composer init --require CyrilleHugues/Alyosha:*
composer install
```

Add the following `bot.php` to the project

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Alyosha\Core\Kernel;

class AppKernel extends Kernel
{
    protected function registerModules()
    {
        return [];
    }
}

$kernel = new AppKernel(__DIR__ . '/vendor/CyrilleHugues/Alyosha/config.yml');
$kernel->run();
```

You can now start the bot, which does nothing because he doesn't have any modules.

```bash
php bot.php
```

### Your first use

Let's say you use IRC to chat with some friends and want to celebrate the leet time, which is 13:37.
For this, you need the following modules:

-   TimeModule
    -   Fire the `time_module.date` with the time every seconds so that you can trigger an event when you want.
-   IrcModule
    -   Connect to IRC servers and channels and send you events for every activities
-   IrcAdminModule
    -   You don't want a bot without a master, right ?

So let's add them to the mix in `bot.php`.

```php
use Alyosha\Core\Kernel;
use Alyosha\IRC\IrcModule;
use Alyosha\IrcAdmin\IrcAdminModule;
use Alyosha\Time\TimeModule;

class AppKernel extends Kernel
{
    protected function registerModules()
    {
        return [
            new IrcModule(),
            new IrcAdminModule(),
            new TimeModule(),
        ];
    }
}
```

Execute the script again: `php bot.php`.

> Wow it's writing a whole bunch of text !

Yep, it connected to `irc.iiens.net` server and handled all the tedious work to get you to the `Alyosha` channel.

> Nice, that's not where I want it to connect.

Ok, let's talk about configuration.

### Configuration

If you read your `bot.php` script, you have this line:

```php
$kernel = new AppKernel(__DIR__ . '/vendor/CyrilleHugues/Alyosha/config.yml');
```

It create the kernel with a .yml file from the lib. Let's write our own `config.yml`.
Let's start with the IRC configuration:

```
irc:
    servers:
        super:
            address: my.super.server
            port: 6667
            nickname: i_m_a_robot
            chans:
                - doc_chan
```

Here we configured the bot to connect to `my.super.server` with the nickname `i_m_a_robot`. You can make it connect to several server
by adding another key (see [https://symfony.com/doc/3.3/components/yaml.html](https://symfony.com/doc/3.3/components/yaml.html)).

For this server we make it connect to a list of one chan, doc_chan.

Since we are responsible, we use IrcAdminModule which need a password for you to authenticate yourself.

```
security:
    password: <your password to administrate the bot>
```

Now, let's make our bot take this configuration into account by modifying this line

```php
$kernel = new AppKernel(__DIR__ . '/vendor/CyrilleHugues/Alyosha/config.yml');
```

to

```php
$kernel = new AppKernel(__DIR__ . '/config.yml');
```

and start the bot again. It connect to your channel with the provided nickname ... and does nothing.
Let's change that.

### Scripting

#### Configuring composer to autoload our classes

Look, i'm not a monster to send scripting like it's PHP4 with `include` and `require`.
Add the following to your `composer.json`

```json
...
    },
    "autoload": {
        "psr-4": {"Bot\\": "src/"}
    }
}
```

and run `composer install` again. Now you can write PHP classes in the `src` and `use Bot\Smthg` as
in every modern PHP files.

#### Creating your first module

Create a `src/LeetModule.php` with this content

```php
<?php

namespace Bot;

use Alyosha\Core\Event\EventInterface;
use Alyosha\Core\Module\AbstractModule;
use Alyosha\IRC\Event\Command\MessageCommand;
use Alyosha\IRC\Event\IrcCommandEvent;
use Alyosha\Time\TimeEvent;

class LeetModule extends AbstractModule
{
    private $servers = [];
    private $events;

    // the name of the module for all other modules
    public function getName()
    {
        return 'leet_module';
    }

    // You can add it in the `config.yml` and it will be available here to start your module
    public function start(array $config)
    {
        // we get all the channels of the configuration
        foreach ($config['irc']['servers'] as $serverName => $serverConfig) {
            $this->servers[$serverName] = $serverConfig['chans']
        }
    }

    // Which are the event i want for this module
    // Here I only want the time
    public function getSubscriptions()
    {
      return [
          'time_module.date'
      ];
    }

    // Alyosha use this function to notify every module of an event they are subscribed to.
    public function notify(EventInterface $event)
    {
        // always check the type of event before executing logic
        if ($event instanceof TimeEvent && $event->getDate()->format('H:i:s') === "13:37:00") {
            foreach ($this->servers as $serverName => $chanList) {
                foreach ($chanList as $chan) {
                  // We create a message to ask the IRCModule to send a message on a chan.
                  new MessageCommand($serverName, $chan, "LEET TIME !!");
                  // We put the message in an envelope to send it to IRCModule via Alyosha.
                  $this->events[] = new IrcCommandEvent($messageCommand);
                }
            }
        }
    }

    // here we send the new events created
    public function getEvents()
    {
      $events = $this->events;
      $this->events = [];
      return $events;
    }
}
```

and add it to the `bot.php` script.

```php
use Bot\LeetModule;

...

protected function registerModules()
{
    return [
        new IrcModule(),
        new IrcAdminModule(),
        new TimeModule(),
        new LeetModule(),
    ];
}
```

Now you can execute `php bot.php` and at 13:37 every day, it will output "LEET TIME !!" in all the channels.

## Managing Alyosha

Don't let the script in a random window on a computer/server.
Please use [PM2](http://pm2.keymetrics.io/) to manage Alyosha's process.
