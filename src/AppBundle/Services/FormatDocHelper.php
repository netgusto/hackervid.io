<?php

namespace AppBundle\Services;

class FormatDocHelper {

    public function toHtml(string $str) : string {
        $contents = [];

        $str = htmlspecialchars($str);
        $str = str_replace("\r", "", $str);

        // find blocks indented by 2 spaces or more
        if(trim($str) === '') return '';

        $str = trim($str);
        $lines = explode("\n", $str);

        $block = [];
        $para = [];

        foreach($lines as $index => $line) {
            if(substr($line, 0, 2) === '  ') {
                $block[] = substr($line, 2);
            } else {
                if(count($block) > 0) {
                    $contents[] = ['type' => 'block', 'content' => implode("\n", $block)];
                    $block = [];
                }
                $contents[] = ['type' => 'para', 'content' => $line];
            }
        }

        if(count($block) > 0) {
            $contents[] = ['type' => 'block', 'content' => implode("\n", $block)];
        } else if(count($para) > 0) {
            $contents[] = ['type' => 'para', 'content' => implode("\n", $para)];
        }

        return implode("\n", array_map(function(array $content) : string {
            if($content['type'] === 'block') {
                return '<pre><code>' . $content['content'] . '</code></pre>';
            }

            return '<p>' . $this->linkify($content['content']) . '</p>';
        }, $contents));
    }

    // credits to cloud8421 on Stack Overflow https://stackoverflow.com/a/3890175
    // Ported from JavaScript to PHP
    protected function linkify(string $inputText) : string {

        //URLs starting with http://, https://, or ftp://
        $replacePattern1 = '/(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/im';
        $replacedText = preg_replace($replacePattern1, '<a href="$1" target="_blank">$1</a>', $inputText);

        //URLs starting with "www." (without // before it, or it'd re-link the ones done above).
        $replacePattern2 = '/(^|[^\/])(www\.[\S]+(\b|$))/im';
        $replacedText = preg_replace($replacePattern2, '$1<a href="http://$2" target="_blank">$2</a>', $replacedText);

        //Change email addresses to mailto:: links.
        $replacePattern3 = '/(([a-zA-Z0-9\-\_\.])+@[a-zA-Z\_]+?(\.[a-zA-Z]{2,6})+)/im';
        $replacedText = preg_replace($replacePattern3, '<a href="mailto:$1">$1</a>', $replacedText);

        return $replacedText;
    }
}