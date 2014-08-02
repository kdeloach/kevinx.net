<ul>

  <? if(isadmin()): ?><li><a href="new-user.php" <?php a('new-user.php') ?>><img src="../images/new-user.gif" alt="" /> New User</a></li><?endif;?>
  <? if(isadmin()): ?><li><a href="manage-users.php" <?php a('manage-users.php') ?>><img src="../images/manage-user.gif" alt="" /> Manage Users</a></li><?endif;?>
  <li><a href="profile.php" <?php a('profile.php') ?>><img src="../images/orb-green.gif" alt="" /> My Profile</a></li>
  

</ul>