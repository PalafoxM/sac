<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title><?php echo $title;?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Junta de Gobierno del ISAPEG" name="description" />
        <meta content="ISAPEG" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon.ico">

        <!-- App css -->
        <link href="<?php echo base_url();?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/css/app-creative.min.css" rel="stylesheet" type="text/css" id="light-style" />
        <link href="<?php echo base_url();?>assets/css/app-creative-dark.min.css" rel="stylesheet" type="text/css" id="dark-style" />
        <link href="<?php echo base_url();?>assets/css/custom.css" rel="stylesheet" type="text/css" />
      

        <script src="<?php echo base_url(); ?>/assets/js/vendor.min.js"></script>
       
        <script type="text/javascript" src="<?php echo base_url();?>/assets/sweetAlert2/sweetalert2.all.min.js"></script>
        <script src="<?= base_url("/js/general.js")?>"></script>
        <!-- third party js ends -->

        <?php if (isset($scripts)): foreach ($scripts as $js): ?>
            <script src="<?php echo base_url() . "/js/{$js}.js" ?>?filever=<?php echo time() ?>" type="text/javascript"></script>
                <?php endforeach;
            endif;
        ?>       

    </head>

    <body class="authentication-bg pb-0" data-layout-config='{"darkMode":true}'>
        <script>            
            var base_url = "<?php echo base_url();?>";          
        </script>  
        