<?php
if(!defined('OSTCLIENTINC') || !is_object($ticket)) die('Kwaheri rafiki!');
//Please customize the message below to fit your organization speak!
?>
<div style="margin:5px 100px 100px 0;">
    <?php echo Format::htmlchars($ticket->getName()); ?>,<br>
    <p>Thank you for your interest in Gett (A division of the global GetTaxi)!</p>
    <p>
        A Support Inquiry has been created and assigned a ticket number. A Gett Customer Support Team member will respond to your inquiry shortly.
        <?php if($cfg->autoRespONNewTicket()){ ?>
            You will receive an email with the ticket information, and can use your email address <?php echo $ticket->getEmail(); ?> along with the ticket number to check the status of your request.
        <?php } ?>
    </p>
    <p>
        If you wish to send additional comments or information regarding this issue, please don't open a new ticket. Simply reply to the email, or login to the system to update the ticket.
    </p>
    <p>
        Happy Riding!<br>
        Gett NYC Customer Support
    </p>
</div>
