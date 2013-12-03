<?php
if(!defined('OSTCLIENTINC')) die('Access Denied!');
$info=array();
if($thisclient && $thisclient->isValid()) {
    $info=array('name'=>$thisclient->getName(),
                'email'=>$thisclient->getEmail(),
                'phone'=>$thisclient->getPhone(),
                'phone_ext'=>$thisclient->getPhoneExt());
}

$info=($_POST && $errors)?Format::htmlchars($_POST):$info;
?>
<h1>Create a New Future Order</h1>
<p>Please fill in the form below to create a future order.</p>
<form id="ticketForm" method="post" action="booking.php" enctype="multipart/form-data">
  <?php csrf_token(); ?>
  <input type="hidden" name="a" value="open">
  <table width="800" cellpadding="1" cellspacing="0" border="0">
    <tr>
        <th class="required" width="160">Full Name:</th>
        <td>
            <?php
            if($thisclient && $thisclient->isValid()) {
                echo $thisclient->getName();
            } else { ?>
                <input id="name" type="text" name="name" size="40" value="<?php echo $info['name']; ?>">
                <font class="error">*&nbsp;<?php echo $errors['name']; ?></font>
            <?php
            } ?>
        </td>
    </tr>
    <tr>
        <th class="required" width="160">Email Address:</th>
        <td>
            <?php
            if($thisclient && $thisclient->isValid()) { 
                echo $thisclient->getEmail();
            } else { ?>
                <input id="email" type="text" name="email" size="40" value="<?php echo $info['email']; ?>">
                <font class="error">*&nbsp;<?php echo $errors['email']; ?></font>
            <?php
            } ?>
        </td>
    </tr>
    <tr>
        <th>Passenger Telephone:</th>
        <td>
            <input id="phone" type="text" name="phone" size="24" value="<?php echo $info['phone']; ?>">
            <label for="ext" class="inline">Ext.:</label>
            <input id="ext" type="text" name="phone_ext" size="3" value="<?php echo $info['phone_ext']; ?>">
            <font class="error">&nbsp;<?php echo $errors['phone']; ?>&nbsp;&nbsp;<?php echo $errors['phone_ext']; ?></font>
        </td>   
    </tr>
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr>
      <td class="required">Pickup date and time:</td>
      <td>
          <input id="pickup_datetime" type="text" name="pickup_datetime" size="40" value="<?php echo $info['pickup_datetime']; ?>">
          <font class="error">*&nbsp;<?php echo $errors['pickup_datetime']; ?></font>
      </td>
    </tr>
    <tr>
    <td class="required">Pickup address:</td>
        <td>
            <input id="pickup_address" type="text" name="pickup_address" size="40" value="<?php echo $info['pickup_address']; ?>">
            <font class="error">*&nbsp;<?php echo $errors['pickup_address']; ?></font>
        </td>
    </tr>
      <tr>
          <td>Booking Type:</td>
          <td>
              <select id="booking_type" name="booking_type" style="width: 310px" onchange="ChangeDropdowns();">
                  <option value="" <?php if($info['booking_type'] == '') echo 'selected="selected"'; ?>>Select a Booking Type</option>
                  <option value="general"<?php if($info['booking_type'] == 'general') echo 'selected="selected"'; ?>>General Booking</option>
                  <option value="airport"<?php if($info['booking_type'] == 'airport') echo 'selected="selected"'; ?>>Airport Booking</option>
              </select>
              <font class="error">*&nbsp;<?php echo $errors['booking_type']; ?></font>
          </td>
      </tr>
    <tr class="booking_row" style="display: none">
        <td class="required">Terminal:</td>
        <td>
            <input id="airport_terminal" type="text" name="airport_terminal" size="40" value="<?php echo $info['airport_terminal']; ?>">
            <font class="error">*&nbsp;<?php echo $errors['airport_terminal']; ?></font>
        </td>
    </tr>
    <tr class="booking_row" style="display: none">
        <td class="required">Airline:</td>
        <td>
            <input id="airport_airline" type="text" name="airport_airline" size="40" value="<?php echo $info['airport_airline']; ?>">
            <font class="error">*&nbsp;<?php echo $errors['airport_airline']; ?></font>
        </td>
    </tr>
    <tr class="booking_row" style="display: none">
        <td class="required">Flight Number:</td>
        <td>
            <input id="airport_flight_number" type="text" name="airport_flight_number" size="40" value="<?php echo $info['airport_flight_number']; ?>">
            <font class="error">*&nbsp;<?php echo $errors['airport_flight_number']; ?></font>
        </td>
    </tr>
    <tr class="booking_row" style="display: none">
        <td class="required">Flight Destination/Origin:</td>
        <td>
            <input id="airport_dest_orig" type="text" name="airport_dest_orig" size="40" value="<?php echo $info['airport_dest_orig']; ?>">
            <font class="error">*&nbsp;<?php echo $errors['airport_dest_orig']; ?></font>
        </td>
    </tr>
    <tr>
        <td class="required">Destination:</td>
        <td>
            <input id="destination" type="text" name="destination" size="40" value="<?php echo $info['destination']; ?>">
            <font class="error">*&nbsp;<?php echo $errors['destination']; ?></font>
        </td>
    </tr>
    <tr>
        <td class="required">Special requests:</td>
        <td>
            <div><em>Please provide as much detail as possible so we can best assist you.</em> <font class="error">*&nbsp;<?php echo $errors['message']; ?></font></div>
            <textarea id="message" cols="60" rows="8" name="message"><?php echo $info['message']; ?></textarea>
        </td>
    </tr>

    <?php if(($cfg->allowOnlineAttachments() && !$cfg->allowAttachmentsOnlogin())
            || ($cfg->allowAttachmentsOnlogin() && ($thisclient && $thisclient->isValid()))) { ?>
    <tr>
        <td>Attachments:</td>
        <td>
            <div class="uploads"></div><br>
            <input type="file" class="multifile" name="attachments[]" id="attachments" size="30" value="" />
            <font class="error">&nbsp;<?php echo $errors['attachments']; ?></font>
        </td>
    </tr>
    <tr><td colspan=2>&nbsp;</td></tr>
    <?php } ?>
    <?php
    if($cfg->allowPriorityChange() && ($priorities=Priority::getPriorities())) { ?>
    <tr>
        <td>Ticket Priority:</td>
        <td>
            <select id="priority" name="priorityId">
                <?php
                    if(!$info['priorityId'])
                        $info['priorityId'] = $cfg->getDefaultPriorityId(); //System default.
                    foreach($priorities as $id =>$name) {
                        echo sprintf('<option value="%d" %s>%s</option>',
                                        $id, ($info['priorityId']==$id)?'selected="selected"':'', $name);
                        
                    }
                ?>
            </select>
            <font class="error">&nbsp;<?php echo $errors['priorityId']; ?></font>
        </td>
    </tr>
    <?php
    }
    ?>
    <?php
    if($cfg && $cfg->isCaptchaEnabled() && (!$thisclient || !$thisclient->isValid())) {
        if($_POST && $errors && !$errors['captcha'])
            $errors['captcha']='Please re-enter the text again';
        ?>
    <tr class="captchaRow">
        <td class="required">CAPTCHA Text:</td>
        <td>
            <span class="captcha"><img src="captcha.php" border="0" align="left"></span>
            &nbsp;&nbsp;
            <input id="captcha" type="text" name="captcha" size="6">
            <em>Enter the text shown on the image.</em>
            <font class="error">*&nbsp;<?php echo $errors['captcha']; ?></font>
        </td>
    </tr>
    <?php
    } ?>
    <tr><td colspan=2>&nbsp;</td></tr>
  </table>
  <p style="padding-left:150px;">
        <input type="submit" value="Create Ticket">
        <input type="reset" value="Reset">
        <input type="button" value="Cancel" onClick='window.location.href="index.php"'>
  </p>
</form>
<script type="text/javascript">
    function ChangeDropdowns(){
        var selection = document.getElementById("booking_type");
        if(selection.options[selection.selectedIndex].value == "general"){
            var rows = document.getElementsByClassName("booking_row");
            for(var i = 0; i < rows.length; i = i + 1) {
                rows[i].style.display="none";
            }
        }else if(selection.options[selection.selectedIndex].value == "airport"){
            var rows = document.getElementsByClassName("booking_row");
            for(var i = 0; i < rows.length; i = i + 1) {
                rows[i].style.display="table-row";
            }
        }
    }
    window.onload = ChangeDropdowns();
</script>