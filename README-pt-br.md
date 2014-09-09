
## Features

Para usar o Features insira o seguinte código em seu `functions.php` logo após `require_once "vendor/autoload.php";`
A vantagem de usar as funções do Features é o ganho em organização e otimização, já que em poucas linhas você tem vários recursos do WP que você teria que separar em vários arquivos para manter organizado. Potter se encarrega de carregar os comandos apenás quando eles são nescessários.


```php
use Potter\Potter;

$features = Potter::features();
```

### Menu

Adcione menus ao seu tema

```php
// $features->addMenu($location, $description);
$features->addMenu('main', 'Menu Principal');
```

### Theme Support

```php
// $features->addThemeSupport($feature, $arguments = array());
$features->addThemeSupport('post-thumbnails');
$features->addThemeSupport('post-formats');
```

### Post Type Support

```php
// $features->addPostTypeSupport($post_type, $feature)
$features->addPostTypeSupport('page', 'excerpt');
```

### Image Size

```php
// $features->addImageSize($name, $width = 0, $height = 0, $crop = false);
$features->addImageSize('thumb-home', 300, 300, true);
$features->addImageSize('thumb-page', 248, 888, true);
$features->addImageSize('thumb-contact', 460, 400, false);
```

### Assets

Adcione e organize arquivos css e js com facilidade.

#### CSS

```php
// $features->addCss($handle, $src = false, $deps = array(), $ver = null, $media = 'all');
$features->addCss('gfonts', 'http://fonts.googleapis.com/css?family=Rosario:400,700');
$features->addCss('main', 'assets/pub/css/main.css');
```

### JS

```php
// $features->addJs($handle, $src, $deps = array(), $ver = null, $in_footer = false);
// $features->addJsToHead($handle, $src, $deps = array(), $ver = null);
// $features->addJsToFooter($handle, $src, $deps = array(), $ver = null);

$features->addJsToHead('modernizr', 'assets/pub/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js');
$features->addJsToFooter('app', 'assets/pub/js/app.js');
```

#### jQuery CDN
Defina qual a versão de jQuery você esta usando. Potter vai ajustar todas as configurações nescessárias automaticamente.

```php
// $features->setJqueryCDNSupport($version, $fallback = null, $migrate = null, $in_footer = false);
$features->setJqueryCDNSupport('2.1.1', 'assets/pub/js/vendor/jquery-2.1.1.min.js', 'assets/pub/js/vendor/jquery-migrate-1.2.1.min.js');
```
> Alem de definir a versão do jQuery que você quer, você pode definir o seu fallback (caso o cdn não carregue) e ainda definir o jQuery migrate.

## Google Analytcs

Adcione o código de rastreamento do Google Analytcs no tema com apenas uma linha de código.

```php
// $features->setGoogleAnalytcsID($id);
$features->setGoogleAnalytcsID('A1-XXXXXX');
```

## Login Logo

Mude a imagem de login do Wordpress (d+ não?)

```php
// $features->setLoginLogo($logo, $style = array());
$features->setLoginLogo('assets/pub/imgs/login-logo.png', array('width'=>'150px'));
```


## Opções do tema

Trabalhar com sites feitos em WP é muito bom, porém há momentos que precisamos deixar determinados recursos mais flexiveis pelo ambiente do usuário final (o cliente), para isso usamos recursos como o Theme Options.
Há varias formas de se implementar Theme Options, umas mais faceis e/ou robustas que outras. O plugin [option-tree](https://github.com/valendesigns/option-tree) é uma exelente opção para se usar, é facil e flexivel, porém não possui uma interface de desenvolvimento que agrade a todos.
Por isso o Potter possui um wrapper API que facilita muito o trabalho de desenvolvimento de Theme Options com o option-tree.
