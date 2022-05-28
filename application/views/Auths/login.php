<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url('assets/font_awesome/css/font-awesome.min.css'); ?>">
    <!-- Ionicons -->
    <link href="<?php echo base_url('assets/css/ionicons.min.css'); ?>" rel="stylesheet">
    <!-- Theme style -->
    <link href="<?php echo base_url('assets/dist/css/AdminLTE.min.css'); ?>" rel="stylesheet">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/iCheck/flat/blue.css'); ?>">
    <link rel='shortcut icon' type='image/x-icon' href="<?php echo base_url('assets/dist/img/fevicon.ico'); ?>"/>


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="<?php echo base_url('assets/js/html5shiv.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/respond.min.js'); ?>"></script>
    <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="container">

    <!-- /.login-logo -->
    <div class="login-box-body" style="width: 40%; margin-top: 13%; text-align: center; margin-left: 30%">
        <div class="login-logo">
            <a href="#"><img src="<?php echo base_url(); ?>assets/dist/img/logo.png" width="42%" border="0"
                             vspace="0px" hspace="0px"/></a>
                             <br><span
                    style="font-size: 28px; color: #4f87ac; font-weight: bold"></span>
        </div>
        <p class="login-box-msg">Sign in to start your session</p>
        <?php
        $message = $this->session->flashdata('message');
        if (!empty($message)) {
            echo "<p class='login-box-msg' style='color: red'>$message</p>";
        }
        if (!empty($error_message)) {
            echo "<p class='login-box-msg' style='color: red'>$error_message</p>";
        }
        ?>
        <?php echo form_open('auths/login', array('method' => 'post')); ?>
        <div class="form-group has-feedback">
            <?php
            $attribute = array('name' => 'txt_login', 'id' => 'txt_login', 'class' => 'form-control', 'required' => "required", 'placeholder' => "Please Enter Your Login Username", 'autocomplete' => "off");
            echo form_input($attribute, set_value('txt_login'));
            ?>
        </div>
        <div class="form-group has-feedback">
            <?php
            $attribute = array('name' => 'txt_password', 'id' => 'txt_password', 'class' => 'form-control', 'placeholder' => 'Password');
            echo form_password($attribute);
            ?>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?php if (isset($captcha_image)) { ?>
                    <tr class="password_section captcha_section">
                        <td align="left" colspan="2">
                            <div id="capcha">
                                <table border="0" width="255px">
                                    <tr>
                                        <td id="captcha_image"><?php echo isset($captcha_image) ? $captcha_image : ''; ?></td>
                                    </tr>
                                    <tr>
                                        <td><label for="capcha">Enter the letters above:<em>&nbsp;</em></label></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php
                                            $attribute = array('name' => 'txt_captcha', 'id' => 'txt_captcha', 'class' => 'login_input_text', 'style' => 'width:250px;');
                                            echo form_input($attribute);
                                            echo '<span class="specialHints_1">Letters are not case-sensitive.</span>';
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                <?php } ?>

            </div>
            <!-- /.col -->
            <div class="col-xs-8">
                <a href="<?php echo base_url('users/reset_password') ?>" class="pull-left" style="text-decoration: underline;">Forgot password?</a>
            </div>
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div>
            <!-- /.col -->
        </div>
        </form>
    </div>
    <!-- /.login-box-body -->
</div>
<script src="<?php echo base_url('assets/plugins/jQuery/jquery-2.2.3.min.js'); ?>"></script>
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/iCheck/icheck.min.js'); ?>"></script>
</body>
</html>
