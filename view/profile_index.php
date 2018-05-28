<?php
  require_once "_header.php";
?>

<h1>Shema profila</h1>

<div class="omotac">


<table class="table">
  <tr>
    <th> Korisničko ime </th>
    <td>  <?php echo $_SESSION['username']; ?> </td>
    <td> <button type="submit">  Uredi </td>
  </tr>
  <tr>
    <th> Email </th>
    <td> <?php echo $user->email; ?> </td>
    <td> <form method="post" action="<?php echo __SITE_URL; ?>/index.php?rt=profile/changeEmail" ><button type="submit" name="new_email"> </form> Uredi </td>
  </tr>
  <tr>
    <th> Lozinka  </th>
    <td> _getati_lozinka_ispistai_zvjezdice_ </td>
    <td> <button type="submit">  Uredi </td>
  </tr>
  <tr>
    <th> Obavijesti  </th>
    <td> Želim primati obavijesti na moju email adresu. </td>
    <td>  <input type="checkbox" >  </td>
  </tr>
  <tr>
    <th> Moj profil  </th>
    <td> Želim izbrisati korisnički račun. </td>
    <td> <button type="submit">  Uredi </td>
  </tr>
</table>


</div>

<?php
  require_once "_footer.php";
?>
