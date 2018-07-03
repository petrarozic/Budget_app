<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
      integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
      crossorigin="anonymous">

<!--potrebno za nav-tabs-->
      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous">
      </script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
      integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
      crossorigin="anonymous">
    </script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
      integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
      crossorigin="anonymous">
    </script>
<!-- do tuda -->

    <!-- ICON -->
    <link rel="shortcut icon" href="<?php echo __SITE_URL;?>/budget.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo __SITE_URL;?>/budget.ico" type="image/x-icon">

    <title>Budget-app</title>

  </head>
  <body class="log-body">
    <div class="title">
      <span class="title-name">Budget-app</span>
      <img src="money.png" alt=" " width="40" class="logo">
    </div>

    <div class="row">

    <div class="col-md-6 pull-left">
       <div class="col-lg-11 log-mainPart" style="max-width: 29rem; margin-top: 15%; float:right;">
        <ul class="nav nav-tabs" id="myTab" role="tablist" >
          <li class="nav-item">
            <?php echo '<a class="nav-link';
             if(isset($l_flag) && $l_flag === 1) echo ' active"'; else echo '"';
             echo ' id="log_in-tab" data-toggle="tab" href="#log_in" role="tab" aria-controls="log_in" aria-selected="true">LOG IN</a>'; ?>
          </li>
          <li class="nav-item">
            <?php echo '<a class="nav-link';
             if(!isset($l_flag) || $l_flag !== 1) echo ' active"'; else echo '"';
             echo ' id="sign_up-tab" data-toggle="tab" href="#sign_up" role="tab" aria-controls="sign_up" aria-selected="false">SIGN UP</a>'; ?>
          </li>

        </ul>
        <div class="tab-content" id="myTabContent" >
          <?php echo '<div class="tab-pane fade';
           if(isset($l_flag) && $l_flag === 1) echo ' show active"'; else echo '"';
           echo ' id="log_in" role="tabpanel" aria-labelledby="log_in-tab">'; ?>

            <div style="margin: 20px;">
              <form class="" action="<?php echo __SITE_URL; ?>/index.php?rt=login/processLogin" method="post">

              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Username">
              </div>

              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
              </div>

              <button type="submit" class="btn bgreen">LOG IN</button>
              <button type="reset" class="btn btn-secondary">Reset</button>
              <a data-toggle="modal" href="#forgot">Forgot password?</a>
              <br>
              <br>
              <p style="text-align:center"> <?php if(isset($lmessage)) echo $lmessage; ?> </p>

            </form>
            </div>

          </div>
          <?php echo '<div class="tab-pane fade';
           if(!isset($l_flag) || $l_flag !== 1) echo ' show active"'; else echo '"';
           echo ' id="sign_up" role="tabpanel" aria-labelledby="sign_up-tab">'; ?>

            <div style="margin: 20px;">
              <?php
              if(isset($output))
                echo '<p> '. $output. ' </p>';

              else{ ?>
                <form class="" action="<?php echo __SITE_URL; ?>/index.php?rt=login/processSignUp" method="post">

              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Username">
              </div>

              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                <small id="passwordHelpBlock" class="form-text text-muted">
                  Your password must be 3-20 characters long, contain letters and numbers,
                  and must not contain spaces, special characters, or emoji.
                </small>
              </div>

              <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="email@example.com ">
                  <small id="emailHelp" class="form-text text-muted">Need email for verification.</small>
              </div>

              <button type="submit" class="btn bgreen float-right">SIGN UP</button>
              <button type="reset" class="btn btn-secondary float-right">Reset</button>
              <br>
              <br>
              <br>
              <p style="text-align:center"> <?php if(isset($smessage)) echo $smessage; ?></p>';

            </form>
        <?php  } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 pull-right" style="padding-left:0;">
    <div class="col-lg-11" style="margin-top: 21%; padding-left:0;">
    <div class="text-center">
    <img src="money.png" alt=" " width="215" class="image">
    </div>
    <p class="description">Budget-app is an application for tracking personal finances. <br> Spend smart and enjoy the app!</p>
    </div>
    </div>

  </div>

    <script type="text/javascript">
      $('#myTab a').on('click', function (e) {
        e.preventDefault()
        $(this).tab('show')
      })
    </script>

    <!--- Modal - forgot password--->
    <div class="modal" id="forgot" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"> Forgot password </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>


          <div class="modal-body">

            <small id="passwordHelpBlock" class="form-text text-muted">
              New password will be sent to your email. You can change it later.
            </small>
            <br>
            <form method="post" action="<?php echo __SITE_URL; ?>/index.php?rt=profile/forgotPassword">
              <div class="form-group">
                <label for="username_forgot"> Your username: </label>
                <input type="text" class="form-control" placeholder="username" name="username_forgot">
               </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn bgreen">Submit</button>
          </div>
          </form>
        </div>
      </div>
    </div>
<?php require_once __SITE_PATH . '/view/_footer.php'; ?>
