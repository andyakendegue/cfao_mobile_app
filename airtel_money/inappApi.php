<?php
$code=uniqid('cfao',true);

if(isset($_GET['montant'])) {
    $montant = $_GET['montant'];
}
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Cfao Technologies</title>

        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>




    </head>

    <body>

        <form name="payment_process" action="https://mypvit.com/airtelmoneypvit.kk" method="POST">
            <input type="hidden" name="tel_marchand" value="07248186" />
            <input type="hidden" name="montant" value="<?php echo $montant; ?>" />
            <input type="hidden" name="ref" value="<?php echo $code; ?>" />
            <input type="hidden" name="redirect" value="https://azur-wifly.com/service/airtel_money/return.php" />


        </form>
    </body>

    </html>
