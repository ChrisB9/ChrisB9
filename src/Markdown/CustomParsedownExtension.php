<?php /** @noinspection PhpMultipleClassesDeclarationsInOneFile */

declare(strict_types=1);

namespace App\Markdown;

use Symfony\Component\Asset\Packages;
use Twig\Environment;

final class CustomParsedownExtension extends \ParsedownExtraPlugin
{
    public $blockCodeClassFormat = '%s';
    public Environment $twig;
    public Packages $packages;
    public array $context;
    public string $chartJsDir = '';

    protected string $timelineRegex = '([\w\W\-,\.\d\(\)\_\s\#]+)';

    public static array $tagClassMap = [
        'h1' => 'leading-tight border-b text-4xl font-semibold mb-4 mt-6 pb-2 print:hidden',
        'h2' => 'leading-tight border-b text-2xl font-semibold mb-4 mt-6 pb-2',
        'h3' => 'leading-snug text-lg font-semibold mb-4 mt-6',
        'h4' => 'leading-none text-base font-semibold mb-4 mt-6',
        'h5' => 'leading-tight text-sm font-semibold mb-4 mt-6',
        'h6' => 'leading-tight text-sm font-semibold text-gray-600 mb-4 mt-6',
        'li' => 'mt-1',
        'strong' => 'font-semibold',
        'a' => 'text-blue-600 font-semibold',
        'p' => 'mt-6',
        'blockquote' => 'text-base border-l-4 border-gray-300 pl-4 pr-4 text-gray-600',
        'code' => 'font-mono text-sm inline rounded px-1 py-05',
        'pre' => 'bg-gray-100 rounded p-4',
        'ul' => 'text-base pl-8 list-disc',
        'ol' => 'text-base pl-8 list-decimal',
        'kbd' => 'text-xs inline-block rounded border px-1 py-05 align-middle font-normal font-mono shadow',
        'table' => 'text-base border-gray-600',
        'th' => 'border py-1 px-3',
        'td' => 'border py-1 px-3',
    ];

    public function __construct()
    {
        parent::__construct();

        $this->registerToken('@', 'LineAwesome');
        $this->registerToken('{', 'Twig');
        $this->registerToken('[', 'Checkbox');
        $this->BlockTypes['~'][] = 'ChartJsCode';
        $this->BlockTypes['~'][] = 'TimelineBlock';
    }

    protected function element($data)
    {
        if (isset($data['name'])) {
            $this->applyStyle($data);
        }
        return parent::element($data);
    }

    private function applyStyles(array $elements): array
    {
        foreach ($elements as &$element) {
            $this->applyStyle($element);
        }
        return $elements;
    }

    private function applyStyle(array &$element): void
    {
        $class = '';
        if (isset($element['attributes'], $element['attributes']['class'])) {
            $class = $element['attributes']['class'];
        }
        if (isset(self::$tagClassMap[$element['name'] ?? ''])) {
            if (!isset($element['attributes'])) {
                $element['attributes'] = [];
            }
            $element['attributes']['class'] = $class . ' ' . self::$tagClassMap[$element['name']];
        }
        if (isset($element['elements'])) {
            $element['elements'] = $this->applyStyles($element['elements']);
        }
    }

    private function registerToken(string $token, string $name): void
    {
        if (isset($this->InlineTypes[$token])) {
            $this->InlineTypes[$token] = array_merge($this->InlineTypes[$token], [$name]);
        } else {
            $this->InlineTypes[$token] = [$name];
        }
        if (!str_contains($this->inlineMarkerList, $token)) {
            $this->inlineMarkerList .= $token;
        }
    }

    /**
     * This method is completely copied downstream to fix a bug between multiple versions
     * @param $Line
     * @return array[]|void|null
     */
    protected function blockHeader($Line)
    {
        $level = strspn($Line['text'], '#');

        if ($level > 6) {
            return;
        }

        $text = trim($Line['text'], '#');

        if ($this->strictMode and isset($text[0]) and $text[0] !== ' ') {
            return;
        }

        $text = trim($text, ' ');

        $Block = array(
            'element' => array(
                'name' => 'h' . min(6, $level),
                'handler' => array(
                    'function' => 'lineElements',
                    'argument' => $text,
                    'destination' => 'elements',
                )
            ),
        );

        if (!isset($Block)) {
            return null;
        }

        if (preg_match('/[ #]*{(' . $this->regexAttribute . '+)}[ ]*$/', $Line['text'], $matches, PREG_OFFSET_CAPTURE)) {
            $attributeString = $matches[1][0];

            $Block['element']['attributes'] = $this->parseAttributeData($attributeString);

            $Block['element']['text'] = substr($Block['element']['text'], 0, $matches[0][1]);
        }
        $Level = strspn($Line['text'], '#');
        $this->doSetAttributes($Block['element'], $this->headerAttributes, array($Level));
        $this->doSetContent($Block['element'], $this->headerText, false, 'argument', array($Level));
        return $Block;
    }

    public function inlineImage($excerpt): array
    {
        $image = parent::inlineImage($excerpt);
        $src = $image['element']['attributes']['src'] ?? '';
        $image['element']['attributes']['loading'] = 'lazy';
        $sourceList = [];
        if ($src) {
            $pathInfo = pathinfo($src);
            $webPSrc = str_replace($pathInfo['basename'], sprintf('%s.webp', $pathInfo['filename']), $src);
            if (!str_starts_with($src, 'http')) {
                $image['element']['attributes']['src'] = $this->packages->getUrl($src);
                $webPSrc = $this->packages->getUrl($webPSrc);
            }
            $source = $image;
            $source['element']['name'] = 'source';
            $source['element']['attributes']['srcset'] = $webPSrc;
            unset($source['element']['attributes']['src']);
            unset($source['element']['attributes']['class']);
            $sourceList[] = $source;
        }
        $sourceList[] = $image;
        return [
            'extent' => $image['extent'],
            'element' => [
                'name' => 'picture',
                'elements' => $sourceList,
            ],
        ];
    }

    protected function blockTimelineBlock($line, $block): ?array
    {
        if (preg_match('/~Timeline~/', $line['text'], $matches)) {
            return array(
                'char' => $line['text'][0],
                'element' => [
                    'name' => 'div',
                    'text' => '',
                    'attributes' => [
                        'class' => 'timeline',
                    ],
                    'elements' => [],
                ],
            );
        }
        return null;
    }

    protected function blockTimelineBlockContinue($line, $block): ?array
    {
        if (isset($block['complete'])) {
            return null;
        }

        if (empty($block['element']['elements'])) {
            $block['element']['elements'][] = $this->createTimelineEntry();
        }
        $elements = &$block['element']['elements'];
        $last = &$elements[array_key_last($elements)];

        $match = [];
        if (preg_match('/Title\: ' . $this->timelineRegex . '/', $line['text'], $match)) {
            $last['elements'][0]['elements'][0]['text'] = $match[1];
            return $block;
        }

        $match = [];
        if (preg_match('/Company\: ' . $this->timelineRegex . '/', $line['text'], $match)) {
            $last['elements'][0]['elements'][1]['text'] = $match[1];
            return $block;
        }

        $match = [];
        if (preg_match('/Subtitle\: ' . $this->timelineRegex . '/', $line['text'], $match)) {
            $last['elements'][1]['elements'][0]['text'] = $match[1];
            return $block;
        }

        $match = [];
        if (preg_match('/\- ' . $this->timelineRegex . '/', $line['text'], $match)) {
            $last['elements'][1]['elements'][1]['elements'][] = [
                'name' => 'li',
                'text' => $match[1],
            ];
            return $block;
        }

        if (preg_match('/\-\-\-/', $line['text'])) {
            $elements[] = $this->createTimelineEntry();
            return $block;
        }

        if (isset($block['interrupted'])) {
            $block['element']['text'] .= "\n";
            unset($block['interrupted']);
        }

        if (preg_match('/~Timeline~/', $line['text'])) {
            unset($block['element']['text']);
            $block['complete'] = true;
            return $block;
        }

        $block['element']['text'] .= "\n" . $line['body'];

        return $block;
    }

    private function createTimelineEntry(): array
    {
        return [
            'name' => 'div',
            'text' => '',
            'attributes' => [
                'class' => 'entry',
            ],
            'elements' => [
                [
                    'name' => 'div',
                    'text' => '',
                    'attributes' => [
                        'class' => 'title',
                    ],
                    'elements' => [
                        [
                            'name' => 'h3',
                            'text' => '',
                        ],
                        [
                            'name' => 'p',
                            'text' => '',
                        ],
                    ]
                ],
                [
                    'name' => 'div',
                    'text' => '',
                    'attributes' => [
                        'class' => 'body',
                    ],
                    'elements' => [
                        [
                            'name' => 'p',
                            'text' => '',
                        ],
                        [
                            'name' => 'ul',
                            'text' => '',
                            'elements' => []
                        ],
                    ]
                ],
            ],
        ];
    }

    protected function blockTimelineBlockComplete($block): ?array
    {
        return $block;
    }

    protected function blockChartJsCode($line, $block): ?array
    {
        if (preg_match('/~!~/', $line['text'], $matches)) {
            return array(
                'char' => $line['text'][0],
                'element' => [
                    'name' => 'div',
                    'text' => '',
                    'attributes' => [
                        'class' => 'chartjs-container relative',
                        'style' => 'height: 500px',
                    ]
                ],
            );
        }
        return null;
    }

    protected function blockChartJsCodeContinue($line, $block): ?array
    {
        if (isset($block['complete'])) {
            return null;
        }

        $match = [];
        if (preg_match('/\:height\:([0-9]+)px/', $line['text'], $match)) {
            $block['element']['attributes']['style'] = str_replace(
                'height: 500px',
                'height: ' . $match[1] . 'px',
                $block['element']['attributes']['style']
            );
            return $block;
        }

        $match = [];
        if (preg_match('/\:json\:([a-zA-Z\-\_]+)/', $line['text'], $match)) {
            $block['loadJson'] = $match[1];
            return $block;
        }

        if (isset($block['interrupted'])) {
            $block['element']['text'] .= "\n";
            unset($block['interrupted']);
        }

        if (preg_match('/~!~/', $line['text'])) {
            $json = substr($block['element']['text'], 1);
            if (isset($block['loadJson'])) {
                $json = file_get_contents(sprintf('%s/%s.json', $this->chartJsDir, $block['loadJson']));
            }
            $block['element']['attributes']['data-json'] = $json;
            unset($block['element']['text']);
            $block['complete'] = true;
            return $block;
        }

        $block['element']['text'] .= "\n" . $line['body'];

        return $block;
    }

    protected function blockChartJsCodeComplete($block): ?array
    {
        $aria = json_decode($block['element']['attributes']['data-json'])->aria;
        $block['element']['elements'] = [
            [
                'name' => 'canvas',
                'text' => '',
                'attributes' => [
                    'class' => 'chartjs max-h-full',
                    'aria-label' => $aria->label,
                    'tabindex' => 0,
                    '@keydown.enter' => "console.log('TODO: add a11y to chartjs-canvas')"
                ],
            ]
        ];
        return $block;
    }

    protected function inlineCheckbox(array $excerpt): ?array
    {
        $matches = [];
        if (preg_match('/\[[x| ]\][ ]?([a-z\-]+)/', $excerpt['text'], $matches)) {
            $attributes = [
                'type' => 'checkbox',
                'disabled' => true,
            ];
            if (str_starts_with($matches[0], '[x] ')) {
                $attributes['checked'] = true;
            }
            return [
                'extent' => strlen($matches[0]),
                'element' => [
                    'name' => 'input',
                    'handler' => 'line',
                    'text' => ' ' . $matches[1],
                    'attributes' => $attributes,
                ],
            ];
        }
        return null;
    }

    protected function inlineLineAwesome(array $excerpt): ?array
    {
        $matches = [];
        if (preg_match('/@[b\-]?([a-z\-]+)@/', $excerpt['text'], $matches)) {
            $source = 'las';
            $class = $matches[1];
            if (str_starts_with($matches[0], '@b-')) {
                $source = 'lab';
                $class = substr($matches[1], 1);
            }
            $possibleAttributes = str_replace($matches[0], '', $excerpt['text']);
            $attributeList = ['class' => $source . ' la-' . $class];
            $length = strlen($matches[0]);

            if (str_contains($possibleAttributes, '{') && str_contains($possibleAttributes, '}')) {
                $string = substr($possibleAttributes, strpos($possibleAttributes, '{'), strpos($possibleAttributes, '}'));
                $attributes = $this->parseAttributeData(str_replace(['{', '}'], '', $string));
                if (isset($attributes['class'])) {
                    $attributeList['class'] .= ' ' . $attributes['class'];
                    unset($attributes['class']);
                }
                $attributeList = array_merge($attributeList, $attributes);
                $length = strpos($excerpt['text'], $string) + strlen($string);
            }

            return [
                'extent' => $length,
                'element' => [
                    'name' => 'i',
                    'handler' => 'line',
                    'text' => '',
                    'attributes' => $attributeList,
                ],
            ];
        }
        return null;
    }

    protected function inlineTwig(array $excerpt): ?array
    {
        if ($this->twig !== null && preg_match('/\{%[\-]?([a-z\.\-]+)%\}/', $excerpt['text'], $matches)) {
            $content = $this->twig->render('content/' . $matches[1], $this->context);
            $name = str_starts_with($matches[0], '{%-') ? 'span' : 'div';
            return [
                'extent' => strlen($matches[0]),
                'element' => [
                    'name' => $name,
                    'rawHtml' => trim($content),
                    'allowRawHtmlInSafeMode' => true,
                    'attributes' => []
                ],
            ];
        }
        return null;
    }
}
