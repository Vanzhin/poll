<?php

namespace App\Entity\Analytics;

use Symfony\Component\Serializer\Annotation\Groups;

class YandexMetrika extends Analytics
{
    private const START_SCRIPT = '<script type="text/javascript">';
    private const END_SCRIPT = '</script>';
    private const START_HTML = '<noscript>';
    private const END_HTML = '</noscript>';


    #[Groups(['analytics'])]
    private ?string $counterCode;

    #[Groups(['analytics'])]
    private ?string $counterHtml;


    public function __construct(Analytics $analytics)
    {
        $this->title = $analytics->getTitle();
        $this->code = $analytics->getCode();
        $this->counterCode = $this->getCounterCode();

    }

    public static function create(Analytics $analytics): YandexMetrika
    {
        return new self($analytics);
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return string|null
     */
    public function getCounterCode(): ?string
    {
        return $this->getCodeString(self::START_SCRIPT, self::END_SCRIPT);
    }

    /**
     * @return string|null
     */
    public function getCounterHtml(): ?string
    {
        return $this->getCodeString(self::START_HTML, self::END_HTML);
    }

    private function getCodeString(string $codeStartsWith, string $codeEndsWith): string
    {
        $codeString = substr($this->code, stripos($this->code, $codeStartsWith), stripos($this->code, $codeEndsWith) - stripos($this->code, $codeStartsWith) + strlen($codeEndsWith));

        return trim(preg_replace('/\s+/', ' ', $codeString));
    }


}