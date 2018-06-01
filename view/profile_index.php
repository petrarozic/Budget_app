<?php
  require_once "_header.php";
?>

<h1>Shema profila</h1>

<div class="omotac">


<table class="table">
  <tr>
    <th> Username </th>
    <td>  <?php echo $_SESSION['username']; ?> </td>
    <td> <button type="submit">  Edit </td>
  </tr>
  <tr>
    <th> Email </th>
    <td> <?php echo $user->email; ?> </td>
    <td> <button type="button" name="new_email" data-toggle="modal" data-target="#changeEmail">Edit</button> </td>
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

<?php
  require_once "_footer.php";
?>
