<?php
/**
 * Used in the header of an http request
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 */
class ContentType
{
  /**
   * Used when the content is JSON
   * @var string
   */
  const JSON = "application/json";
  /**
   * Used when the content isXML
   * @var string
   */
  const XML = "application/xml";
  /**
   * Used when the content is RAW
   * @var string
   */
  const RAW = "application/x-www-form-urlencoded";
}

?>
