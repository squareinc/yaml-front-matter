<?php

namespace Spatie\YamlFrontMatter;

use Exception;
use Symfony\Component\Yaml\Yaml;

class YamlFrontMatterParser
{
    public function parse(string $content) : YamlFrontMatterObject
    {
        // Parser regex borrowed from the `devster/frontmatter` package
        // https://github.com/devster/frontmatter/blob/bb5d2c7/src/Parser.php#L123
        $pattern = "/^\s*(?:---)[\n\r\s]*(.*?)[\n\r\s]*(?:---)[\s\n\r]*(.*)$/s";

        $parts = [];

        $match = preg_match($pattern, $content, $parts);

        if ($match === false) {
            throw new Exception('An error occurred while extracting the front matter from the contents');
        }

        if ($match === 0) {
            return new YamlFrontMatterObject([], $content);
        }

        $matter = $this->parseYaml($parts[1]);
        $body = $parts[2];

        return new YamlFrontMatterObject($matter, $body);
    }

    protected function parseYaml(string $data) : array
    {
        $yaml = new Yaml();
        
        return $yaml->parse($data);
    }
}