<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Marcello - Internacionalizacao -->
        <title>Confirme seu e-mail iBench Market</title>
    </head>
    <body>
        <div class="container">
            <h1><?php echo $site_name; ?> - Confirme seu e-mail</h1>

            <div class="clearfix"></div>
            <div class="row profile shop">
                <div class="col-md-6">
 
                    <div id="outer" style="width: 100%;margin: 0 auto;background-color:#cccccc; padding:10px;">  

                        <div id="inner" style="width: 80%;margin: 0 auto;background-color: #fff;font-family: Open Sans,Arial,sans-serif;font-size: 13px;
                             font-weight: normal;line-height: 1.4em;color: #444;margin-top: 10px; padding:10px;">
                             <?php if (!empty($site_logo)) { ?>
                                <div align="center"><img src="<?php echo $site_logo; ?>" border="0" alt="logo" /></div>
                            <?php } else { ?>
                                <div align="center"><h2><?php echo $site_name; ?></h2></div>
                            <?php } ?>

                            <p> Ol&aacute; <?php echo $name; ?>,</p><br/><br/>
                            <!-- To complete your registration, please confirm your e-mail address by clicking this link: -->
                            <p> Confirme seu e-mail clicando no link abaixo para concluir seu cadastro no iBench Market:</p> 
                            <p> <a href="<?php echo $url; ?>/confirmemail/<?php echo $keyval; ?>" target="_blank"><?php echo $url; ?>/confirmemail/<?php echo $keyval; ?></a></p>
                            <p>Se o link acima n&atilde;o funcionar, voc&ecirc; pode copi&aacute;-lo e col&aacute;-lo na barra de endere&ccedil;os do seu navegador.</p>

                            <p> Obrigado,</p> 
                            <P>Equipe <?php echo $site_name; ?></p>
                        </div>
                    </div>
                    <div class="height30"></div>
                    <div class="row">
                    </div>
                </div>
                </body>
                </html>