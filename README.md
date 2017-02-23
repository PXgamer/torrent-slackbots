# slackbots

A bot framework for Slack using the ET API.

## Example Usage

```php
<?php

require 'vendor/autoload.php';

use pxgamer\TorrentParser\ExtraTorrent as ET;
use pxgamer\TorrentSlackBots\Functions\Quick as Q;
use pxgamer\TorrentSlackBots\BotSender as BOT;

$BOT = new BOT;

$BOT->setDb('localhost', 't-cronned', '', 't-cronned');
$BOT->setEndpoint('https://hooks.slack.com/services/...');

$BOT->setUser('condors');

$BOT->loop(1);
```