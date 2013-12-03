<?php
if(!defined('OSTSCPINC') || !$thisstaff || !$thisstaff->canEditTickets() || !$ticket) die('Access Denied');

$info=Format::htmlchars(($errors && $_POST)?$_POST:$ticket->getUpdateInfo());
?>
<script type="text/javascript">
    function ChangeBookingFields(value){
        if(value=="0"){
            var rows = document.getElementsByClassName("booking");
            for(var i = 0; i < rows.length; i = i + 1) {
                rows[i].style.display="none";
            }
        }else if(value=="1"){
            var rows = document.getElementsByClassName("booking");
            for(var i = 0; i < rows.length; i = i + 1) {
                rows[i].style.display="table-row";
            }
        }else if(value=="general"){
            var rows = document.getElementsByClassName("airport");
            for(var i = 0; i < rows.length; i = i + 1) {
                rows[i].style.display="none";
            }
        }else if(value=="airport"){
            var rows = document.getElementsByClassName("airport");
            for(var i = 0; i < rows.length; i = i + 1) {
                rows[i].style.display="table-row";
            }
        }
    }
    $(function() {
        $( "#datetimeicker" ).datetimepicker();
    });
</script>
<form action="tickets.php?id=<?php echo $ticket->getId(); ?>&a=edit" method="post" id="save"  enctype="multipart/form-data">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="update">
 <input type="hidden" name="a" value="edit">
 <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
 <h2>Update Ticket# <?php echo $ticket->getExtId(); ?></h2>
 <table class="form_table" width="940" border="0" cellspacing="0" cellpadding="2">
    <thead>
        <tr>
            <th colspan="2">
                <h4>Ticket Update</h4>
                <em><strong>User Information</strong>: Make sure the email address is valid.</em>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td width="160" class="required">
                Full Name:
            </td>
            <td>
                <input type="text" size="50" name="name" value="<?php echo $info['name']; ?>">
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['name']; ?></span>
            </td>
        </tr>
        <tr>
            <td width="160" class="required">
                Email Address:
            </td>
            <td>
                <input type="text" size="50" name="email" value="<?php echo $info['email']; ?>">
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['email']; ?></span>
            </td>
        </tr>
        <tr>
            <td width="100">Driver ID:</td>
            <td>
                <input type="text" name="driver_id" size="60" value="<?php echo $info['driver_id']; ?>">
                &nbsp;<font class="error">*&nbsp;<?php $errors['driver_id']; ?></font>
            </td>
        </tr>
        <tr>
            <td width="160">
                Phone Number:
            </td>
            <td>
                <input type="text" size="20" name="phone" value="<?php echo $info['phone']; ?>">
                &nbsp;<span class="error">&nbsp;<?php echo $errors['phone']; ?></span>
                Ext <input type="text" size="6" name="phone_ext" value="<?php echo $info['phone_ext']; ?>">
                &nbsp;<span class="error">&nbsp;<?php echo $errors['phone_ext']; ?></span>
            </td>
        </tr>
        <tr>
            <th colspan="2">
                <em><strong>Ticket Information</strong>: Due date overwrites SLA's grace period.</em>
            </th>
        </tr>
        <tr>
            <td width="160" class="required">
                Ticket Source:
            </td>
            <td>
                <select name="source">
                    <option value="" selected >&mdash; Select Source &mdash;</option>
                    <option value="Phone" <?php echo ($info['source']=='Phone')?'selected="selected"':''; ?>>Phone</option>
                    <option value="Email" <?php echo ($info['source']=='Email')?'selected="selected"':''; ?>>Email</option>
                    <option value="Web"   <?php echo ($info['source']=='Web')?'selected="selected"':''; ?>>Web</option>
                    <option value="API"   <?php echo ($info['source']=='API')?'selected="selected"':''; ?>>API</option>
                    <option value="Other" <?php echo ($info['source']=='Other')?'selected="selected"':''; ?>>Other</option>
                </select>
                &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['source']; ?></font>
            </td>
        </tr>
        <tr>
            <td width="160" class="required">
                Help Topic:
            </td>
            <td>
                <select name="topicId">
                    <option value="" selected >&mdash; Select Help Topic &mdash;</option>
                    <?php
                    if($topics=Topic::getHelpTopics()) {
                        foreach($topics as $id =>$name) {
                            echo sprintf('<option value="%d" %s>%s</option>',
                                    $id, ($info['topicId']==$id)?'selected="selected"':'',$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['topicId']; ?></font>
            </td>
        </tr>
        <tr>
            <td width="160" class="required">
                Priority Level:
            </td>
            <td>
                <select name="priorityId">
                    <option value="" selected >&mdash; Select Priority &mdash;</option>
                    <?php
                    if($priorities=Priority::getPriorities()) {
                        foreach($priorities as $id =>$name) {
                            echo sprintf('<option value="%d" %s>%s</option>',
                                    $id, ($info['priorityId']==$id)?'selected="selected"':'',$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<font class="error">*&nbsp;<?php echo $errors['priorityId']; ?></font>
            </td>
        </tr>
        <tr>
            <td width="160" class="required">
                Subject:
            </td>
            <td>
                 <input type="text" name="subject" size="60" value="<?php echo $info['subject']; ?>">
                 &nbsp;<font class="error">*&nbsp;<?php $errors['subject']; ?></font>
            </td>
        </tr>
        <tr>
            <td width="160">
                SLA Plan:
            </td>
            <td>
                <select name="slaId">
                    <option value="0" selected="selected" >&mdash; None &mdash;</option>
                    <?php
                    if($slas=SLA::getSLAs()) {
                        foreach($slas as $id =>$name) {
                            echo sprintf('<option value="%d" %s>%s</option>',
                                    $id, ($info['slaId']==$id)?'selected="selected"':'',$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['slaId']; ?></font>
            </td>
        </tr>
        <tr>
            <td width="160">
                Due Date:
            </td>
            <td>
                <input class="dp" id="duedate" name="duedate" value="<?php echo Format::htmlchars($info['duedate']); ?>" size="12" autocomplete=OFF>
                &nbsp;&nbsp;
                <?php
                $min=$hr=null;
                if($info['time'])
                    list($hr, $min)=explode(':', $info['time']);
                    
                echo Misc::timeDropdown($hr, $min, 'time');
                ?>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['duedate']; ?>&nbsp;<?php echo $errors['time']; ?></font>
                <em>Time is based on your time zone (GMT <?php echo $thisstaff->getTZoffset(); ?>)</em>
            </td>
        </tr>

        <tr>
            <td width="160">Ticket Type:</td>
            <td>
                <select name="is_booking" onchange="ChangeBookingFields(this.value);">
                    <option value="">&mdash; Select Ticket Type &mdash;</option>
                    <option value="0" <?php if($ticket->getIsBooking() == 0) echo 'selected="selected"'; ?>>Non-Booking</option>
                    <option value="1" <?php if($ticket->getIsBooking() == 1) echo 'selected="selected"'; ?>>Future Booking</option>
                </select>
                &nbsp;<font class="error">*&nbsp;<?php echo $errors['is_booking']; ?></font>
            </td>
        </tr>
        <tr class="booking" <?php if ($ticket->getIsBooking() == '0') echo 'style="display: none"'; ?>>
            <td width="100">Pickup date and time:</td>
            <td>
                <input type="text" name="pickup_datetime" size="60" value="<?php echo $info['pickup_datetime']; ?>" id="datetimeicker">
                &nbsp;<font class="error">*&nbsp;<?php $errors['pickup_datetime']; ?></font>
            </td>
        </tr>
        <tr class="booking" <?php if ($ticket->getIsBooking() == '0') echo 'style="display: none"'; ?>>
            <td>Pickup address:</td>
            <td>
                <input type="text" name="pickup_address" size="60" value="<?php echo $info['pickup_address']; ?>">
                &nbsp;<font class="error">*&nbsp;<?php $errors['pickup_address']; ?></font>
            </td>
        </tr>
        <tr class="booking" <?php if ($ticket->getIsBooking() == '0') echo 'style="display: none"'; ?>>
            <td>Destination:</td>
            <td>
                <input type="text" name="destination" size="60" value="<?php echo $info['destination']; ?>">
                &nbsp;<font class="error">*&nbsp;<?php $errors['destination']; ?></font>
            </td>
        </tr>
        <tr class="booking" <?php if ($ticket->getIsBooking() == '0') echo 'style="display: none"'; ?>>
            <td>Booking type:</td>
            <td>
                <select name="booking_type" onchange="ChangeBookingFields(this.value);">
                    <option value="">&mdash; Select Booking Type &mdash;</option>
                    <?php echo sprintf('<option value="%s"%s>%s</option>', 'general', ($info['booking_type']=='general')?' selected="selected"':'','General Booking'); ?>
                    <?php echo sprintf('<option value="%s"%s>%s</option>', 'airport', ($info['booking_type']=='airport')?' selected="selected"':'','Airport Booking'); ?>
                </select>
                &nbsp;<font class="error">*&nbsp;<?php echo $errors['booking_type']; ?></font>
            </td>
        </tr>
        <tr class="airport" <?php if ($ticket->getIsBooking() == '0' || $ticket->getBookingType() == 'general') echo 'style="display: none"'; ?>>
            <td>Terminal:</td>
            <td>
                <input type="text" name="airport_terminal" size="60" value="<?php echo $info['airport_terminal']; ?>">
                &nbsp;<font class="error">*&nbsp;<?php $errors['airport_terminal']; ?></font>
            </td>
        </tr>
        <tr class="airport" <?php if ($ticket->getIsBooking() == '0' || $ticket->getBookingType() == 'general') echo 'style="display: none"'; ?>>
            <td>Airline:</td>
            <td>
                <input type="text" name="airport_airline" size="60" value="<?php echo $info['airport_airline']; ?>">
                &nbsp;<font class="error">*&nbsp;<?php $errors['airport_airline']; ?></font>
            </td>
        </tr>
        <tr class="airport" <?php if ($ticket->getIsBooking() == '0' || $ticket->getBookingType() == 'general') echo 'style="display: none"'; ?>>
            <td>Flight number:</td>
            <td>
                <input type="text" name="airport_flight_number" size="60" value="<?php echo $info['airport_flight_number']; ?>">
                &nbsp;<font class="error">*&nbsp;<?php $errors['airport_flight_number']; ?></font>
            </td>
        </tr>
        <tr class="airport" <?php if ($ticket->getIsBooking() == '0' || $ticket->getBookingType() == 'general') echo 'style="display: none"'; ?>>
            <td>Flight destination/origin:</td>
            <td>
                <input type="text" name="airport_dest_orig" size="60" value="<?php echo $info['airport_dest_orig']; ?>">
                &nbsp;<font class="error">*&nbsp;<?php $errors['airport_dest_orig']; ?></font>
            </td>
        </tr>
        <tr>
            <th colspan="2">
                <em><strong>Internal Note</strong>: Reason for editing the ticket (required) <font class="error">&nbsp;<?php echo $errors['note'];?></font></em>
            </th>
        </tr>
        <tr>
            <td colspan=2>
                <textarea name="note" cols="21" rows="6" style="width:80%;"><?php echo $info['note']; ?></textarea>
            </td>
        </tr>
    </tbody>
</table>
<p style="padding-left:250px;">
    <input type="submit" name="submit" value="Save">
    <input type="reset"  name="reset"  value="Reset">
    <input type="button" name="cancel" value="Cancel" onclick='window.location.href="tickets.php?id=<?php echo $ticket->getId(); ?>"'>
</p>
</form>
