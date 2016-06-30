<?php
global $EM_Event;
$required = apply_filters('em_required_html','<i>*</i>');
?>
<table class="em-purchase-ticket-data">
    <tr class="em-purchase-ticket-url">
        <th><?php _e ( 'Url:', 'dbem' )?>&nbsp;</th>
        <td>
            <input id="purchase-ticket-url" type="text" name="event_purchase_ticket_url" value="<?php echo esc_attr(EM_Events::getFieldValueByEventId($EM_Event->event_id, 'purchase_ticket_url'), ENT_QUOTES); ?>" /><?php echo $required; ?>
        </td>
    </tr>
    <tr class="em-purchase-ticket-link-id">
        <th><?php _e ( 'Link Id:', 'dbem' )?>&nbsp;</th>
        <td>
            <input id="purchase-ticket-link-id" type="text" name="event_purchase_ticket_link_id" value="<?php echo esc_attr(EM_Events::getFieldValueByEventId($EM_Event->event_id, 'purchase_ticket_link_id'), ENT_QUOTES); ?>" /><?php echo $required; ?>
        </td>
    </tr>
</table>