<?php
/**
 *
 */
interface Highrise_Entity_Object
{
    public function fromXml($xml);
    public function toXml();
    public function getId();
}
?>