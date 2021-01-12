[1] Alerts from libs/alerts/alerts.php
- We have alert options for bootstrap alerts, simple js alerts.
    <?php msg($message, $redirect="#", $ALERT_TYPE = "js", $SCRIPT = true  ,$TYPE = "info"); ?>
    <?php jsMsg($message, $SCRIPT, $redirect); ?>
    <?php notifyMsg($message, $SCRIPT, $redirect, $TYPE); ?>


[2] Non-generics from libs/alerts/non-generics.php
- As per requirnment you can update this API's.
    <?php setLog($LOG_MESSAGE, $LOG_PATH="../logs/NAV_ACTION_LOG.txt"); ?>