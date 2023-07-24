var MQTT;
    var reconnectTimeout = 2000;
    var host="192.168.52.203";
    var port=9001;

    function onFailure(message)
    {
        console.log("Connection Attempt to Host "+host+"Failed");
        setTimeout(MQTTconnect, reconnectTimeout);
    }

    function onMessageArrived(msg)
    {
        out_msg="Message received "+msg.payloadString+"<br>";
        out_msg=out_msg+"Message received Topic "+msg.destinationName;
        console.log(out_msg);
    }

    function onConnect() 
    {
        console.log("Connected ");
        sendDataToMQTT(); // Envoi des données au broker MQTT
    }

    function MQTTconnect()
    {
        console.log("connecting to "+ host +" "+ port);
        var x=Math.floor(Math.random() * 10000); 
        var cname="orderform-"+x;
        MQTT = new Paho.MQTT.Client(host,port,cname);
        var options = {
            timeout: 3,
            onSuccess: onConnect,
            onFailure: onFailure,
        };
        MQTT.onMessageArrived = onMessageArrived;
        MQTT.connect(options); //connect
    }

    // Déclaration de la variable MQTT globalement
    MQTT = new Paho.MQTT.Client(host,port,"orderform");

    //Fonction pour envoyer les données du formulaire au broker MQTT
    function sendDataToMQTT() {
    // Vérifier que la connexion MQTT a été établie avant d'essayer d'envoyer des données
    if (MQTT.isConnected()) {
        // Extraire les valeurs des champs
        var depart = document.getElementById("depart").value;
        var arriver = document.getElementById("arriver").value;
        var element = document.getElementById("element").value;

        fetch('http://192.168.52.203/robot.php')
        .then(response => response.json())
        .then(donneesFinale => {

            console.log("Les données sont :", donneesFinale); // Affiche les données dans la console:

            // Extraire les données donnees1 et les stocker dans une variable
            var donnees1 = donneesFinale.donnees1;

            // Extraire les données donnees2 et les stocker dans une variable
            var donnees2 = donneesFinale.donnees2;

            // Utiliser les variables donnees1 et donnees2 comme vous le souhaitez
            console.log(donnees1);
            console.log(donnees2);

            // Utilisez les données JSON ici
            console.log("Les données sont :", donnees1); // Affiche les données dans la console

            if (depart == "0010" && arriver == "0011")
            {
                var etape = donnees1[0]; // Modifier cette ligne en fonction de l'index approprié

                // Créer le message MQTT à envoyer
                var message = new Paho.MQTT.Message(JSON.stringify({
                    element: element,
                    donnees1: etape
                }));
                message.destinationName = "testTopic";
                message.retained = true;

                // Envoyer le message au broker MQTT
                MQTT.send(message);
            }
            else if(depart == "0011" && arriver == "0010")
            {
                var etape = donnees2[0]; // Modifier cette ligne en fonction de l'index approprié

                // Créer le message MQTT à envoyer
                var message = new Paho.MQTT.Message(JSON.stringify({
                    element: element,
                    donnees2: etape
                }));
                message.destinationName = "testTopic";
                message.retained = true;

                // Envoyer le message au broker MQTT
                MQTT.send(message);
            }
            else
            {
                console.log("Le chemin n'a pas été trouvé !");
            }
        })

        .catch(error => {
            // Gérez les erreurs de la requête
            console.error('Erreur lors de la récupération des données:', error);
        });
    }
}
var myButton = document.querySelector('#Valdier');

// Vérifier si l'élément existe dans le DOM
if (myButton !== null)
{
    // Ajouter un écouteur d'événements au bouton
    myButton.addEventListener('click', function() {
        // Votre code ici
        onConnect();
        sendDataToMQTT(); // Envoyer les données au broker MQTT
    });
}
else
{
    console.error('L\'élément #myButton n\'a pas été trouvé dans le DOM.');
}