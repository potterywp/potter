Potter
======
Conjunto de ferramentas que ajudam a criar sites WordPress mais facilmente.

> Potter ainda esta em fase beta

# Instalação

No seu `composer.json`

```
"repositories": [{
      "type": "vcs",
      "url": "https://github.com/potterywp/meta-box"
   }],
   "require": {
      "potterywp/potter": "dev-master"
   },
```

No seu `functions.php`

```php
   require_once "vendor/autoload.php";
```

# Características

- **Post/Type** - Crie custom posts types com facilidade e flexibilidade
- **ThemeOptions** - Opções de tema ficam faceis de serem criados. Potter usa um wrapper para [option-tree](https://github.com/valendesigns/option-tree), melhorando ainda mais o fluxo de desenvolvimento.
- **Features** - Potter possui uma série de helpers que facilitão a inclusisão de recursos nos seus projetos com Wordpress, como a inclusão de css e javascript com facilidade em suas páginas.
