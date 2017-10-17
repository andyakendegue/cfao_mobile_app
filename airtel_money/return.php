<?php
	function bdd()
	{
		//Script de connexion à la base de donnée
		try 
		{
			$bdd = new PDO('mysql:host=localhost;dbname=airtel','root','azur');
			$bdd->query("SET NAMES utf8");
			$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		} 

		catch (Exception $e)
		{
			die('Echec de connexion à la base de donnée : '.$e->getMessage());  // Envoie du message en cas d'erreur 
		} 
	 return $bdd;
	}

	$bdd = bdd();
	//$_POST['ref']='cfao59c290a6c9921';
	if(isset($_POST['ref']) and !empty($_POST['ref'])){
		$ref=$_POST['ref'];
		//$ref=substr($ref,1,-1);
		$ref=str_replace("''","",$ref);
		$req = $bdd->query('SELECT * FROM test WHERE ref="'.$ref.'"');
		$data = $req->fetch(PDO::FETCH_ASSOC);
		$stat= $data['statut'];
		
		if($stat=='200'){
			$message='Transaction effectuée avec succès !!!';
            $status='success';
		}
		else{
			$message='Échec de la transaction !!!';
            $status='error';
		}
	
?>

    <!DOCTYPE html>
    <html lang="fr">

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

    <body id="page-top">

        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="#page-top">CFAO E-Commerce</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">

                </div>
            </div>
        </nav>

        <!-- Header -->
        <header class="masthead">
            <div class="container">
                <div class="intro-text">
                    <div class="intro-heading">
                        <?php echo $message; ?>
                    </div>
                    <a class="btn btn-xl js-scroll-trigger" href="#" onclick="close()">Retour dans l'application</a>
                </div>
            </div>
        </header>

        <!-- Footer -->
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <span class="copyright">Tous droits reservés CFAO technologies 2017</span>
                    </div>
                    <div class="col-md-4">
                        <ul class="list-inline social-buttons">
                            <li class="list-inline-item">
                                <a href="#">
                  <i class="fa fa-twitter"></i>
                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#">
                  <i class="fa fa-facebook"></i>
                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#">
                  <i class="fa fa-linkedin"></i>
                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <ul class="list-inline quicklinks">
                            <li class="list-inline-item">
                                <a href="#">CFAO Technologies</a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#">Make it Digital</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>



    </body>

    <script>
        function close() {

            localStorage.setItem('close', 'true');
            localStorage.setItem('status', <?php echo $status ?>);
        }

    </script>

    </html>
    <?php
	}
	else{
		
	$data_sent=file_GET_contents("php://input");
	
	$xml=  new  SimpleXMLElement($data_sent);
	$ligneReponse=$xml[0];
	$reference=$ligneReponse->REF;
	$statut =$ligneReponse->STATUT;
	

	$req = $bdd->prepare('INSERT INTO test SET ref=:ref, statut=:statut');
	$req->bindValue('ref', $reference);
	$req->bindValue('statut', $statut);
	$req->execute();
	}
?>
