<?php
  require_once "_header.php";
?>

<?php if ( isset($smessage) )
        echo '<p>'. $smessage. '</p>';
?>

<div class="omotac">

<table class="table">
  <tr>
    <th> Username </th>
    <td>  <?php echo $user->username; ?> </td>
    <td> <button type="button" name="new_username" data-toggle="modal" data-target="#changeUsername" > Edit</button>  </td>
  </tr>
  <tr>
    <th> Email </th>
    <td> <?php echo $user->email; ?> </td>
    <td> <button type="button" name="new_email" data-toggle="modal" data-target="#changeEmail" > Edit</button> </td>
  </tr>
  <tr>
    <th> Password  </th>
    <td> ******** </td>
    <td> <button type="submit">  Edit </td>
  </tr>
  <tr>
    <th> Daily limit </th>
    <td>  <?php echo $user->daily_limit; ?> </td>
    <td> <button type="submit">  Edit </td>
  </tr>
  <tr>
    <th> Weekly limit </th>
    <td>  <?php echo $user->weekly_limit; ?> </td>
    <td> <button type="submit">  Edit </td>
  </tr>
  <tr>
    <th> Monthly limit </th>
    <td>  <?php echo $user->monthly_limit; ?> </td>
    <td> <button type="submit">  Edit </td>
  </tr>
  <tr>
    <th> Notifications  </th>
    <td> I want to get notifications on my email address. </td>
    <td>  <input type="checkbox" >  </td>
  </tr>
  <tr>
    <th> My profile  </th>
    <td> I want to delete my user account.. </td>
    <td> <button type="submit">  Edit </td>
  </tr>
</table>
</div>

<!--- Modal for new username --->
<div class="modal" id="changeUsername" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"> Write your new username </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="<?php echo __SITE_URL; ?>/index.php?rt=profile/changeUsername">
          <div class="form-group">
            <label for="new_email"> New username : </label>
            <input type="text" class="form-control" name="new_username">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!--- Modal for new email address --->
<div class="modal" id="changeEmail" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"> Write your new email </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="<?php echo __SITE_URL; ?>/index.php?rt=profile/changeEmail">
          <div class="form-group">
            <label for="new_email"> New email : </label>
            <input type="text" class="form-control" placeholder="user@example.com" name="new_email">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>


<?php
  require_once "_footer.php";
?>
