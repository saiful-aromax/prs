<!DOCTYPE html>
<html>
<head>
    <?php $this->load->view('Layout/meta'); ?>
    <title><?php echo $title; ?></title>
    <?php $this->load->view('Layout/css'); ?>
    <link rel='shortcut icon' type='image/x-icon' href="<?php echo base_url('assets/dist/img/fevicon.ico'); ?>"/>
    <?php if($is_report){ ?>
        <style>
            .skin-blue .wrapper, .skin-blue .main-sidebar, .skin-blue .left-side{
                background-color: inherit;
            }
        </style>
    <?php } ?>
</head>
<body class="hold-transition skin-blue sidebar-mini collapse in">

<div class="wrapper">
    <?php if (!$is_report) { ?>
        <header class="main-header">
            <?php $this->load->view('Layout/nav'); ?>
        </header>
        <aside class="main-sidebar">
            <?php $this->load->view('Layout/left_menu'); ?>
        </aside>
        <div class="content-wrapper">
            <section class="content-header">
                <span style="font-family: Arial !important;"><?php echo $headline; ?></span>
            </section>
            <section class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <?php if ($this->session->flashdata('success')) { ?>
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                    </button>
                                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                                    <?php echo $this->session->flashdata('success'); ?>
                                </div>
                            <?php } ?>
                            <?php if ($this->session->flashdata('error')) { ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                    </button>
                                    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                                    <?php echo $this->session->flashdata('error'); ?>
                                </div>
                            <?php } ?>
                            <?php if ($this->session->flashdata('warning')) { ?>
                                <div class="alert alert-warning alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                    </button>
                                    <h4><i class="icon fa fa-warning"></i> Warning!</h4>
                                    <?php echo $this->session->flashdata('warning'); ?>
                                </div>
                            <?php } ?>
                            <?php echo $content; ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <footer class="main-footer"><?php $this->load->view('Layout/footer'); ?></footer>
        <aside class="control-sidebar control-sidebar-dark"><?php $this->load->view('Layout/right_sidebar'); ?></aside>
        <div class="control-sidebar-bg"></div>
    <?php } else {
        echo $content;
    } ?>
</div>
<?php $this->load->view('Layout/script'); ?>
</body>
</html>