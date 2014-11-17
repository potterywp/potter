<?php namespace Potter\Utils;

use Illuminate\Support\Collection;

/**
 * Class Paginator
 * @package Potter\Utils
 *
 * @property string $before
 * @property string $after
 * @property string $before_link
 * @property string $after_link
 * @property string $link
 * @property array  $class
 * @property array  $labels
 */
class Paginator
{
    /**
     * @var Collection
     */
    protected     $args;
    protected     $wp_query, $currentPage, $pages, $range, $showItens;
    protected     $element     = '';
    public static $argsDefault = array(
        'before'      => '<ul class="pagination %1$s">',
        'after'       => '</ul>',
        'before_link' => '<li class="%1$s">',
        'after_link'  => '</li>',
        'link'        => '<a class="%3$s" href="%1$s">%2$s</a>',
        'class'       => array(
            'disabled' => 'disabled',
            'active'   => 'active',
            'before'   => '',
            'prev'     => 'prev',
            'after'    => '',
            'first'    => 'first',
            'last'     => 'last',
            'back'     => 'back',
            'next'     => 'next',
            'link'     => ''
        ),
        'labels'      => array(
            'first'  => 'first',
            'last'   => 'last',
            'prev'   => '&laquo;',
            'next'   => '&raquo;',
            'active' => '%1$s <span class="sr-only">(current)</span>'
        )
    );

    public function __construct(array $args = [], $pages = null, $range = 2, $_wp_query = null)
    {
        $this->setArgs($args);
        $this->setQuery($_wp_query);
        $this->setPages($pages);
        $this->setCurrentPage();
        $this->range     = $range;
        $this->showItens = ($this->range * 2) + 1;
    }

    /**
     * @param $key
     *
     * @return mixed|null
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * @param      $key
     * @param null $default
     *
     * @return mixed
     */
    protected function get($key, $default = null)
    {
        return $this->args->get($key, $default);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @return string
     */
    public function render()
    {
        if (empty($this->element)):
            $this->run();
        endif;

        return $this->element;
    }

    protected function run()
    {
        if ($this->pages > 1):
            $this->_before(); //Before

            $this->_first(); // First

            $this->_prev(); // Prev

            $this->_loop(); // Loop

            $this->_next(); // next

            $this->_last(); // Last

            $this->_after();// After
        endif;
    }

    /**
     * @return $this
     */
    protected function _before()
    {
        $before = sprintf($this->before, $this->class['before']);
        $this->addToElement($before);

        return $this;
    }

    /**
     * @return $this
     */
    protected function _after()
    {
        $before = sprintf($this->after, $this->class['after']);
        $this->addToElement($before);

        return $this;
    }

    /**
     * @return $this
     */
    protected function _first()
    {
        if ($this->currentPage > 2 && $this->currentPage > $this->range + 1 && $this->showItens < $this->pages) :
            $url  = get_pagenum_link(1);
            $link = $this->_makeLink($url, $this->labels['first']);

            $this->_item($this->class['first'], $link);
        endif;

        return $this;
    }

    /**
     * @return $this
     */
    protected function _last()
    {
        if ($this->currentPage < $this->pages - 1 && $this->currentPage + $this->range - 1 < $this->pages && $this->showitems < $this->pages) :
            $url  = get_pagenum_link($this->pages);
            $link = $this->_makeLink($url, $this->labels['last']);

            $this->_item($this->class['last'], $link);
        endif;

        return $this;
    }

    /**
     * @return $this
     */
    protected function _prev()
    {
        $label = $this->labels['prev'];
        $class = $this->class['prev'];

        if ($this->currentPage > 1 && $this->showItens < $this->pages) :
            $url = get_pagenum_link($this->currentPage - 1);
        else:
            $class .= ' ' . $this->class['disabled'];
            $url = 'javascript:;';
        endif;

        $link = $this->_makeLink($url, $label);
        $this->_item($class, $link);

        return $this;
    }

    /**
     * @return $this
     */
    protected function _next()
    {
        $label = $this->labels['next'];
        $class = $this->class['next'];

        if ($this->currentPage < $this->pages && $this->showItens < $this->pages) :
            $url = get_pagenum_link($this->currentPage + 1);
        else:
            $class = $this->class['prev'] . ' ' . $this->class['disabled'];
            $url   = 'javascript:;';
        endif;

        $link = $this->_makeLink($url, $label);
        $this->_item($class, $link);

        return $this;
    }

    /**
     * @return void
     */
    protected function _loop()
    {
        for ($i = 1; $i <= $this->pages; $i++) :
            if (1 != $this->pages && (!($i >= $this->currentPage + $this->range + 1 || $i <= $this->currentPage - $this->range - 1) || $this->pages <= $this->showItens)):
                if ($this->currentPage == $i):
                    $class = $this->class['active'] . ' link-' . $i;
                    $url   = 'javascript:;';
                    $label = sprintf($this->labels['active'], $i);
                    $link  = $this->_makeLink($url, $label);
                else :
                    $url   = get_pagenum_link($i);
                    $class = 'link-' . $i;
                    $label = $i;
                    $link  = $this->_makeLink($url, $label);
                endif;

                $this->_item($class, $link);
            endif;
        endfor;
    }

    /**
     * @param string $class
     * @param string $link
     *
     * @return $this
     */
    protected function _item($class, $link)
    {
        $before = sprintf($this->before_link, $class);
        $after  = $this->after_link;
        $item   = $before . $link . $after;

        $this->addToElement($item);

        return $this;
    }

    /**
     * @param      $url
     * @param      $text
     * @param null $class
     *
     * @return $this
     */
    protected function _makeLink($url, $text, $class = null)
    {
        $class .= ' ' . $this->class['link'];
        $link = sprintf($this->link, $url, $text, $class);

        return $link;
    }

    /**
     * @param string $el
     *
     * @return $this
     */
    protected function addToElement($el)
    {
        $this->element .= $el . "\n";

        return $this;
    }

    /**
     * @param null $_wp_query
     *
     * @return void
     */
    private function setQuery($_wp_query)
    {
        if (is_null($_wp_query)):
            global $wp_query;
            $this->wp_query = $wp_query;
        else:
            $this->wp_query = $_wp_query;
        endif;
    }

    /**
     * @param null $_currentPage
     *
     * @return void
     */
    private function setCurrentPage($_currentPage = null)
    {
        if (is_null($_currentPage)):
            global $paged;
            $this->currentPage = $paged;
        else:
            $this->currentPage = $_currentPage;
        endif;

        if (empty($this->currentPage)):
            $this->currentPage = 1;
        endif;
    }

    /**
     * @param $_pages
     *
     * @return void
     */
    private function setPages($_pages)
    {
        if (empty($_pages)):
            $_pages = $this->wp_query->max_num_pages;
        endif;

        if (!$_pages):
            $this->pages = 1;
        else:
            $this->pages = $_pages;
        endif;
    }

    /**
     * @param array $args
     *
     * @return void
     */
    private function setArgs(array $args)
    {
        $args       = wp_parse_args($args, self::$argsDefault);
        $this->args = new Collection($args);
    }

    /**
     * @param array $args
     *
     * @return static;
     */
    public static function make(array $args = array(), $pages = null, $range = 2, $_wp_query = null)
    {
        return new static ($args, $pages, $range, $_wp_query);
    }


}