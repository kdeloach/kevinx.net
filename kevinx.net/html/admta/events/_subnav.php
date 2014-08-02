<ul>

  <? if(isadmin()): ?><li><a href="new-event.php" <?php a('new-event.php') ?>><img src="../images/register-event.gif" alt="" /> New Event</a></li><?endif;?>
  <li><a href="register-events.php" <?php a('register-events.php') ?>><img src="../images/register-event.gif" alt="" /> Register for Events</a></li>
  <? if(isadmin()): ?><li><a href="manage-events.php" <?php a('manage-events.php') ?>><img src="../images/manage-events.gif" alt="" /> Manage Events</a></li><?endif;?>
  <li><a href="manage-registrations.php" <?php a('manage-registrations.php') ?>><img src="../images/manage-events.gif" alt="" /> Manage Registrations</a></li>
  <li><a href="download-forms.php" <?php a('download-forms.php') ?>><img src="../images/forms.gif" alt="" /> Download Forms</a></li>
  

</ul>