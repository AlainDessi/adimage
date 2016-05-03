# AdImage #
AdImage est une petite library PHP permettant de redimensionner des images facilement.

## Configuration requise ##

- PHP >= 5.4
- GD Library >= 2.0

## Installation ##

```bash
$ composer require alaindessi/adimage
```

## Exemple ##

```php
$image = new Adweb\AdImage('public/image.png');
$image->resize('upload/test.png', 200, 200);
```

## License ##
Distribué sous la [licence MIT](https://opensource.org/licenses/MIT).
