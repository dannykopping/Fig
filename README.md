### Fig - _Dead-simple configurations_

[![Build Status](https://travis-ci.org/dannykopping/Fig.png?branch=master)](https://travis-ci.org/dannykopping/Fig)

Using `Fig` is stupidly easy. Install with Composer:

```
{
	"require": {
		"dannykopping/fig": "dev-master"
	}
}
```


To initialize a set of configuration options:

```php
use Fig\Fig;

require_once "vendor/autoload.php";

Fig::setUp(array(
        "name" => "Fig",
        "multiple" => array(
            "levels" => array(
                "of" => "nesting goodness"
            )
        )
    )
);

```

To access your configuration options, either use simple strings for top-level keys:

```php
echo Fig::get("name");  // prints "Fig"
```

...or use dot-notation to indicate hierarchy:

```php
echo Fig::get("multiple.levels.of");  // prints "nesting goodness"
```


Oh - you want to set values, too?

```php
Fig::set("year", 2013);
echo Fig::get("year");  // prints "2013" (and maintains your data types)
```

...even multi-level values!

```php
Fig::set("calendar.years", array(2010,2011,2012,2013));
echo implode(", ", Fig::get("calendar.years"));  // prints "2010, 2011, 2012, 2013"
```


Wanna get rid of a bad fig?

```php
Fig::delete("year");
echo Fig::get("year");  // prints null, key is removed
```

All your figs?

```php
print_r(Fig::getAll());
```