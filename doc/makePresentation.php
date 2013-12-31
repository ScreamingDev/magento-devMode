<?php
/**
 * @since     0.2.0
 */

$template = <<<HTML
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8">

    <title>LeMike_DevMode - Development with Magento</title>

    <meta name="description" content="A suite of helper for easily creating beautiful shops using Magento">
    <meta name="author" content="Ralf Mike Pretzlaw">

    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <link rel="stylesheet" href="css/reveal.min.css">
    <link rel="stylesheet" href="css/theme/sky.css" id="theme">

    <!-- For syntax highlighting -->
    <link rel="stylesheet" href="lib/css/zenburn.css">

    <!-- If the query includes 'print-pdf', use the PDF print sheet -->
    <script>
        document.write('<link rel="stylesheet" href="css/print/paper.css" type="text/css" media="print">');
    </script>

    <!--[if lt IE 9]>
    <script src="lib/js/html5shiv.js"></script>
    <![endif]-->
</head>
<body>

<div class="reveal">

    <!-- Any section element inside of this container is displayed as a slide -->
    <div class="slides">
        {{{sections}}}
    </div>

</div>

<script src="lib/js/head.min.js"></script>
<script src="js/reveal.min.js"></script>

<script>

    // Full list of configuration options available here:
    // https://github.com/hakimel/reveal.js#configuration
    Reveal.initialize({
        controls: true,
        progress: true,
        history: true,
        center: true,

        theme: Reveal.getQueryHash().theme, // available themes are in /css/theme
        transition: Reveal.getQueryHash().transition || 'default', // default/cube/page/concave/zoom/linear/fade/none

        // Optional libraries used to extend on reveal.js
        dependencies: [
            {
                src: 'lib/js/classList.js',
                condition: function () {
                    return !document.body.classList;
                }
            },
            {
                src: 'plugin/markdown/marked.js',
                condition: function () {
                    return !!document.querySelector('[data-markdown]');
                }
            },
            {
                src: 'plugin/markdown/markdown.js',
                condition: function () {
                    return !!document.querySelector('[data-markdown]');
                }
            },
            {
                src: 'plugin/highlight/highlight.js',
                async: true,
                callback: function () {
                    hljs.initHighlightingOnLoad();
                }
            },
            {
                src: 'plugin/zoom-js/zoom.js',
                async: true,
                condition: function () {
                    return !!document.body.classList;
                }
            },
            {
                src: 'plugin/notes/notes.js',
                async: true,
                condition: function () {
                    return !!document.body.classList;
                }
            }
        ]
    });

</script>

</body>
</html>
HTML;

$section = <<<HTML

        <section data-markdown="{{{file}}}"
                         data-separator="^\\n\\n\\n"
                         data-vertical="^\\n\\n"
                         data-notes="^Note:"
                         data-charset="iso-8859-15"></section>
HTML;

class Shell_MakeReveal
{
    protected $_args;

    protected $_logDepth = 0;


    public function __construct($args = array())
    {
        $this->_args = $args;
    }


    public function __invoke()
    {
        global $template, $section;

        chdir(__DIR__);

        $this->_logMessage('Fetch reveal.js ...');
        $this->_logDepth++;
        $this->fetchReveal();
        $this->_logDepth--;

        $this->_logMessage('Generate presentation ...');
        $this->_logDepth++;
        $this->generatePresentation($section, $template);
        $this->_logDepth--;

        $this->_logMessage('Done!');
    }


    /**
     * .
     *
     * @param $str
     * @return void
     */
    public function _logMessage($str)
    {
        $this->_logAdapter(str_repeat('  ', $this->_logDepth) . $str . PHP_EOL);
    }


    /**
     * .
     *
     * @return void
     */
    public function fetchReveal()
    {
        if (!file_exists('reveal.zip'))
        {
            $this->_logVerbose('Download reveal.js ...');
            file_put_contents(
                'reveal.zip',
                file_get_contents('https://github.com/hakimel/reveal.js/archive/master.zip')
            );
        }
        else
        {
            $this->_logVerbose('Use existing reveal.zip');
        }

        $this->_logVerbose('Unzip ...');
        $zip = new ZipArchive();
        $zip->open('reveal.zip');
        $zip->extractTo('.');

        $this->_logVerbose('Copy files ...');
        @mkdir('presentation');
        $this->copy('reveal.js-master/css', './presentation/css');
        $this->copy('reveal.js-master/js', './presentation/js');
        $this->copy('reveal.js-master/lib', './presentation/lib');
        $this->copy('reveal.js-master/plugin', './presentation/plugin');

        $this->_logVerbose('Delete obsolete files ...');
        $this->removeDir('reveal.js-master');
    }


    /**
     * .
     *
     * @param $section
     * @param $template
     * @return void
     */
    public function generatePresentation($section, $template)
    {
        $sectionHtml = '';

        // readme will be introduction
        $introductionSource = __DIR__ . '/../README.md';
        $introductionFile = __DIR__ . '/00-introduction.md';
        $this->_symlink($introductionSource, $introductionFile);

        // maybe put contributing at the end too
        symlink(__DIR__ . '/../CONTRIBUTING.md', __DIR__ . '/999-contributing.md');

        foreach (glob('*.md') as $markdown)
        {
            $sectionHtml .= str_replace('{{{file}}}', '../' . $markdown, $section);
            $this->_logVerbose("Added $markdown");
        }
        $html = str_replace('{{{sections}}}', $sectionHtml, $template);
        file_put_contents('./presentation/index.html', $html);
        $this->_logVerbose('Wrote presentation/index.html');
    }


    // removes files and non-empty directories
    public function removeDir($dir)
    {
        if (is_dir($dir))
        {
            $files = scandir($dir);
            foreach ($files as $file)
            {
                if ($file != "." && $file != "..")
                {
                    $this->removeDir("$dir/$file");
                }
            }
            rmdir($dir);
        }
        else
        {
            if (file_exists($dir))
            {
                unlink($dir);
            }
        }
    }


    public function copy($src, $dst)
    {
        if (file_exists($dst))
        {
            $this->removeDir($dst);
        }
        if (is_dir($src))
        {
            @mkdir($dst);
            $files = scandir($src);
            foreach ($files as $file)
            {
                if ($file != "." && $file != "..")
                {
                    $this->copy("$src/$file", "$dst/$file");
                }
            }
        }
        else
        {
            if (file_exists($src))
            {
                copy($src, $dst);
            }
        }
    }


    /**
     * .
     *
     * @param $str
     * @return void
     */
    protected function _logAdapter($str)
    {
        echo $str;
    }


    /**
     * .
     *
     * @param $introductionSource
     * @param $introductionFile
     *
     * @return void
     */
    protected function _symlink($source, $target)
    {
        symlink($source, $target);
    }


    private function _logVerbose($string)
    {
        $this->_logMessage($string);
    }
}

$cmd = new Shell_MakeReveal();
$cmd->__invoke();

echo PHP_EOL;
