<?php
/**
 * URI used for the http request
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 */
class Uri
{
  const TOKEN_REQUEST           = "https://api.geodrop.net/tokenrequest";
  
  const OUT_SMS_SEND            = "https://api.geodrop.net/out/1/sms/1/send";
  const OUT_SMS_ESTIMATECOST    = "https://api.geodrop.net/out/1/sms/1/estimatecost";
  const OUT_SMS_STATUS          = "https://api.geodrop.net/out/1/sms/1/status";
  const OUT_SMS_JOBS_SCHEDULED  = "https://api.geodrop.net/out/1/sms/1/jobs/scheduled";
  
  const PAY_CONTENT_MESSAGE     = "https://api.geodrop.net/pay/1/content/message/1/";
  const PAY_CUSTOMERS_CHECK     = "https://api.geodrop.net/pay/1/customers/check/1/";
  const PAY_CUSTOMERS_GOODBYE   = "https://api.geodrop.net/pay/1/customers/goodbye/1/";
  const PAY_CUSTOMERS_WELCOME   = "https://api.geodrop.net/pay/1/customers/welcome/1/";
  const PAY_PORT_CHALLENGE      = "https://api.geodrop.net/pay/1/port/challenge/1/";
  const PAY_PORT_CHARGE         = "https://api.geodrop.net/pay/1/port/charge/1/";
  const PAY_PORT_DESCRIPTOR     = "https://api.geodrop.net/pay/1/port/descriptor/1/";
  const PAY_PORT_TRIGGER        = "https://api.geodrop.net/pay/1/port/trigger/1/";
  
} 
