<?php
$hostname = "192.168.52.203";
$username = "robotlog";
$password = "0000";
$db = "robotTransporteur";

$dbconnect = mysqli_connect($hostname, $username, $password, $db);

if ($dbconnect->connect_error) {
    die("Database connection failed: " . $dbconnect->connect_error);
}

// Requête SELECT pour récupérer les données
$query1 = "SELECT etape_chemin FROM chemins WHERE idChemins = 1";
$query2 = "SELECT etape_chemin FROM chemins WHERE idChemins = 2";

//Préparation des requêtes SQL
$stmt1 = mysqli_prepare($dbconnect, $query1);
$stmt2 = mysqli_prepare($dbconnect, $query2);

// Exécution des requêtes préparées
if (mysqli_stmt_execute($stmt1)) {
    // Récupération des résultats
    $resultat1 = mysqli_stmt_get_result($stmt1);
    mysqli_stmt_store_result($stmt1); // Vider les résultats

    if ($resultat1) {
        // Créer un tableau pour stocker les données
        $donnees1 = array();

        // Parcourir les résultats de la première requête
        while ($row = mysqli_fetch_assoc($resultat1)) {
            // Extraire les données souhaitées
            $etape_chemin = $row["etape_chemin"];

            // Ajouter les données au tableau
            $donnees1[] = $etape_chemin;
        }
    } else {
        // Erreur lors de l'exécution de la requête
        echo "Erreur lors de l'exécution de la requête : " . mysqli_error($dbconnect);
    }
}

if (mysqli_stmt_execute($stmt2)) {
    // Récupération des résultats
    $resultat2 = mysqli_stmt_get_result($stmt2);
    mysqli_stmt_store_result($stmt2); // Vider les résultats

    if ($resultat2) {
        // Créer un tableau pour stocker les données
        $donnees2 = array();

        // Parcourir les résultats de la deuxième requête
        while ($row = mysqli_fetch_assoc($resultat2)) {
            // Extraire les données souhaitées
            $etape_chemin = $row["etape_chemin"];

            // Ajouter les données au tableau
            $donnees2[] = $etape_chemin;
        }
    } else {
        // Erreur lors de l'exécution de la requête
        echo "Erreur lors de l'exécution de la requête : " . mysqli_error($dbconnect);
    }

    // Créer un tableau pour stocker les données finales
    $donneesFinale = array(
        'donnees1' => $donnees1,
        'donnees2' => $donnees2
    );

    // Renvoyer les données au client
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *'); // Ajout de l'en-tête CORS
    echo json_encode($donneesFinale);
}

// Vérification si le formulaire a été soumis 
if (isset($_POST['submit'])) {
    //Récupération des valeurs de la zone de départ et de la zone d'arrivée et la commande
    $depart = mysqli_real_escape_string($dbconnect, $_POST['depart']);
    $arrivee = mysqli_real_escape_string($dbconnect, $_POST['arrivee']);
    $element = mysqli_real_escape_string($dbconnect, $_POST['element']);

    // Debugging: Affichage des valeurs reçues
    //var_dump($_POST);

    // Vérification de la saisie des données
    if (empty($depart) || empty($arrivee) || empty($element)) {
        die("Missing input data.");
    }

    // Insertion des valeurs dans la table "trajet"
    $query3 = "INSERT INTO trajet (zoneDepart, zoneArrivee, autonomieDepart,autonomieArrivee, etatTrajet, 
              horodatage) VALUES ($depart, $arrivee,50,60,1, NOW())";

    // Insertion des valeurs dans la table "commande"
    $query4 = "INSERT INTO commande (nbCommande, type_commande) VALUES (1, '$element')";

    //Préparation des requêtes SQL
    $stmt1 = mysqli_prepare($dbconnect, $query3);
    $stmt2 = mysqli_prepare($dbconnect, $query4);

    mysqli_stmt_bind_param($stmt1, "ss", $depart, $arrivee);
    mysqli_stmt_bind_param($stmt2, "s", $element);

    // Exécution de la requête
    if (mysqli_stmt_execute($stmt1) && mysqli_stmt_execute($stmt2)) {
        echo "Les données sont bien enregistrées.";
    } else {
        die('Error: ' . mysqli_error($dbconnect));
    }

    mysqli_stmt_close($stmt1);
    mysqli_stmt_close($stmt2);
}

mysqli_close($dbconnect);
?>
