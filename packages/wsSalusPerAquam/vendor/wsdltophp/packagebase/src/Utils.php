<?php

namespace WsdlToPhp\PackageBase;

class Utils
{
    /**
     * Returns a XML string content as a DOMDocument or as a formated XML string
     * @throws \InvalidArgumentException
     * @param string $string
     * @param bool $asDomDocument
     * @return \DOMDocument|string|null
     */
    public static function getFormatedXml($string, $asDomDocument = false)
    {
        @trigger_error(sprintf('%s() will be renamed to getFormattedXml in WsdlToPhp/PackageBase 3.0.', __METHOD__), E_USER_DEPRECATED);

        if (!is_null($string)) {
            $domDocument = self::getDOMDocument($string);
            return $asDomDocument ? $domDocument : $domDocument->saveXML();
        }
        return null;
    }
    /**
     * @param string $string
     * @throws \InvalidArgumentException
     * @return \DOMDocument
     */
    public static function getDOMDocument($string)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;
        $dom->resolveExternals = false;
        $dom->substituteEntities = false;
        $dom->validateOnParse = false;
        try {
            $dom->loadXML($string);
        } catch (\Exception $exception) {
            throw new \InvalidArgumentException('XML string is invalid', $exception->getCode(), $exception);
        }
        return $dom;
    }
}
