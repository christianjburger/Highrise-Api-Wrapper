<?php
/**
 *
 */
interface Highrise_Entity_DataObject
{
    public function fromXml($xml);
    public function getXmlNode();
}
?>