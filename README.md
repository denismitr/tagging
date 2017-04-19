# Laravel Tagging Package

## Author

[Denis Mitrofanov](https://thecollection.ru)

### Installation
```bash
composer require denismitr/tagging
```

Set Service provider

```php
Denismitr\Tagging\TaggingServiceProvider::class
```

Add trait to your model
```php
use Taggable;
```

### Available API

Methods on the trait

```php
/**
 * Get tags relationship
 *
 * @return MorphToMany
 */
public function tags()

/**
 * Tag with one or more tags
 *
 * @param  array|string|Tag $tags
 * @return void
 */
public function tag($tags)

/**
 * Remove tag or tags
 *
 * @param  array|string|Tag $tags
 * @return void
 */
public function untag($tags = null)

/**
 * Remove all tags and then add tag or tags
 *
 * @param  array|string|Tag $tags
 * @return void
 */
public function retag($tags)
```

Scopes on the trait

```php
public function scopeWithAnyTag($query, array $tags)

public function scopeWithAllTag($query, array $tags)

public function scopeHasTags($query, array $tags)

public function scopeUsedGte($query, $count)

public function scopeUsedGt($query, $count)

public function scopeUsedLte($query, $count)

public function scopeUsedLt($query, $count)
```

### Version 1.1

### LICENSE
[MIT](/license.txt)
