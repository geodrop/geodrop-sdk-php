<?php
/**
 * Action attribute of the JOB tag in the Jobs Scheduled request
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 */
class ActionOutScheduled
{
  /**
   * To get the job status, its description (text and recipient's list)
   * and the date and time planned for sending
   * @var string
   */
  const STATUS = "STATUS";
  /**
   * To modify the text, the recipient's list and the planned date and time
   * @var string
   */
  const MODIFY = "MODIFY";
  /**
   * To delete the job (the message will not be sent anymore)
   * @var string
   */
  const DELETE = "DELETE";
}

?>
