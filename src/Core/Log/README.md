![Grimlock Logo](grimlock.png)


## Logging

---

### How to use


### Grimlock Logger

It is recommended to keep the log folder outside the public area and grant it write permissions.

GrimlockLogger must be initialized with the desired log level and the App name. It only needs to be initialized once.

```php
use GorillaSoft\Grimlock\Core\Log\GrimlockLogger;
use GorillaSoft\Grimlock\Core\Log\Enum\LevelLog;

GrimlockLogger::init(__DIR__ . '/../log/app.log', LevelLog::Info, 'App');
```

Writing messages in Log.

```php
GrimlockLogger::log(LevelLog::Info, 'Message Info');
GrimlockLogger::log(LevelLog::Debug, 'Message Debug');
GrimlockLogger::log(LevelLog::Error, 'Message Error');

```