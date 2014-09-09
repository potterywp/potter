
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

### Criando seu ThemeOptions

- Cria uma pasta chamada `/app` dentro da raiz do seu tema `/wp-content/themes/meutema/`
- Dentro da pasta `app` crie um arquivo chamado `ThemeOptions.php`
- Dentro de `ThemeOptions.php` coloque o seguinte código:

 ```php
<?php
 use Potter\Theme\Options;

 class ThemeOptions extends Options
 {
    protected $page_title = 'Opções do Tema';
    protected $menu_title = 'Opções do Tema';
    protected $settings_id = 'my_theme_options_id';

    public function doRegister()
    {
    }
 }
```

> No momento que o Potter inicializar suas configurações ele automaticamente cria uma instancia de `ThemeOptions` então você não precisa fazer mais nada alem de criar o arquivo e colocar suas configurações lá.

### Configurações adcionais

Você ainda tem mais opções disponiveis, que te permitiram custumizar melhor seu ThemeOptions
 ```php
 class ThemeOptions extends Options
 {
    protected $page_title = 'Theme Options';
    protected $menu_title = 'Theme Options';
    protected $settings_id = 'theme_options';
    protected $header_logo = null;
    protected $header_version_text = null;
    protected $header_logo_link = null;
    protected $show_new_layout = false;
    protected $show_docs = false;
    protected $show_pages = false;
    protected $options_capability = 'edit_theme_options';

    protected $contextual_help
        = array(
            'content' => array(),
            'sidebar' => ''
        );

```


### Adcionando opções

Todas os campos/opções são executados dentro de `doRegister()`

 ```php
 public function doRegister()
 {
    // Primeiro você cria a seção
    $this->addSection('general', 'Geral')
        // depois você adcionar as opções, que são automaticamente inseridas na devida seção
        ->addUpload('logo', 'Logo')
        ->addText('header_slogan', 'Header Slogan');

    $this->addSection('another_section', 'Another')
        ->addTextArea('text_impact', 'Text impact')
        ->addPageSelect('my_page_id', 'Select Page');


    // Você não é obrigado a encadear os metodos
    $this->addSection('more_section', 'GoT');
    $this->addCustomPostTypeSelect('my_got_id','Select GoT', 'Desc of select', 'got');
    $this->addCategorySelect('my_cat_id','Select GoT', 'Desc of select', 'got');
    // As as opções são anexadas automaticamente a última seção configurada.

 }
```

#### Opções disponíveis

- **addText**
   `$this->addText($id, $label, $desc = null, $std = null, $section = null, array $extra = array())` *text*
- **addTextarea**
    `addTextarea($id, $label, $desc = null, $std = null, $section = null, array $extra = array())` *textarea*
- **addSelect**
   `$this->addSelect($id, $label, array $choices, $desc = null, $std = null, $section = null, array $extra = array())` *field of type select.*
- **addCheckbox**
    `$this->addCheckbox($id, $label, array $choices, $desc = null, $std = null, $section = null, array $extra = array())` *field of type checkbox.*
- **addRadio**
    `$this->addRadio($id, $label, array $choices, $desc = null, $std = null, $section = null, array $extra = array())` *field of type radio.*
- **addWYSIWYG**
    `$this->addWYSIWYG($id, $label, $desc = null, $std = null, $section = null, array $extra = array())` *WYSIWYG*
- **addUpload**
    `$this->addUpload($id, $label, $desc = null, $std = null, $section = null, array $extra = array())` *upload (image)*
- **addCustomPostTypeSelect**
    `$this->addCustomPostTypeSelect($id, $label, $desc = null, $postType = 'post', $std = null, $section = null, array $extra = array())` *select type field with custom post type*
- **addCustomPostTypeCheckbox**
    `$this->addCustomPostTypeCheckbox($id, $label, $desc = null, $postType = 'post', $std = null, $section = null, array $extra = array())` *checkbox type field with custom post type*
- **addPageSelect**
   `$this->addPageSelect($id, $label, $desc = null, $std = null, $section = null, array $extra = array())` *select type field with post type page*
- **addPageCheckbox**
   `$this->addPageCheckbox($id, $label, $desc = null, $std = null, $section = null, array $extra = array())` *checkbox type field with post type page*
- **addPostCheckbox**
   `$this->addPageCheckbox($id, $label, $desc = null, $std = null, $section = null, array $extra = array())` *checkbox type field with post type post*
- **addPostSelect**
   `$this->addPostSelect($id, $label, $desc = null, $std = null, $section = null, array $extra = array())` *select type field with post type post*
- **addTaxonomySelect**
   `$this->addTaxonomySelect($id, $label, $desc = null, $taxonomy = 'category', $std = null, $section = null, array $extra = array())` *select type field with taxonomy*
- **addTaxonomyCheckbox**
   `$this->addTaxonomyCheckbox($id, $label, $desc = null, $taxonomy = 'category', $std = null, $section = null, array $extra = array())` *checkbox type field with taxonomy*
- **addCategorySelect**
   `$this->addCategorySelect($id, $label, $desc = null, $std = null, $section = null, array $extra = array())` *select type field with categories*
- **addCategoryCheckbox**
   `$this->addCategoryCheckbox($id, $label, $desc = null, $std = null, $section = null, array $extra = array())` *checkbox type field with categories*
- **addTagSelect**
   `$this->addTagSelect($id, $label, $desc = null, $std = null, $section = null, array $extra = array())` *select type field with tags*
- **addTagCheckbox**
   `$this->addTagCheckbox($id, $label, $desc = null, $std = null, $section = null, array $extra = array())` *checkbox type field with tags*
- **addTypography**
   `$this->addTypography($id, $label, $desc = null, $std = null, $section = null, array $extra = array())`
- **addOnOff**
   `$this->addOnOff($id, $label, $desc = null, $std = null, $section = null, array $extra = array())`
- **addOption**
   `$this->addOption(array $args)` Raw data for option.


### Recuperando opções

Recuperar os dados salvos no ThemeOptions é muito fácil.

```php

$option_name = OPT::get('option_name', 'default_value');

OPT::_get('option_name', 'default_value'); // echo OPT::get('option_name', 'default_value');

$option = OPT::get_nl2br('option_name', 'default_value'); // $option =  nl2br(OPT::get('option_name', 'default_value'));

OPT::_get_nl2br('option_name', 'default_value') echo nl2br(OPT::get('option_name', 'default_value'));

```