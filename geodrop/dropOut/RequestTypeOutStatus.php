<?php
/**
 * Used in the REQUEST_TYPE tag of the SMS Status request
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 */
class RequestTypeOutStatus
{
  /**
   * Used to retrieve the status of all SMS messages sent in a given time interval
   * @var string
   */
  const RANGE = "range";
  /**
   * Used to retrieve the overall status of a job
   * @var string
   */
  const JOB = "job";
  /**
   * Used to retrieve the status of any set of SMS messages
   * @var string
   */
  const ADHOC = "adhoc";
}

?>
