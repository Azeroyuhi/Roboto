<?php
$host = '192.168.1.87';
$username = 'username';
$password = 'password';
$dbname = 'database_name';

$mqtt_host = '192.168.1.87';
$mqtt_port = 1883;
$mqtt_topic = 'robot';

$client_id = 'Yonkeu';

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_error) 
{
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

//insataltion de MOSQUITO MQTT "sudo apt-get install php-mosquitto"
$mosquitto = new Mosquitto\Client($client_id);
$mosquitto->onConnect(function() use ($mosquitto, $mqtt_topic)
{
    $mosquitto->subscribe($mqtt_topic);
});

//extraire les donnees
$mosquitto->onMessage(function($message) use ($mysqli)
{
    $payload = json_decode($message->payload);
    $autonomieDepart = $payload->{'autonomie_depart'};
    $autonomieArriver= $payload->{'autonomie_arriver'};

    $sql = "INSERT INTO trajet (autonomieDepart, autonomieArriver) VALUES ('$autonomieDepart', '$autonomieArriver')";
    if ($mysqli->query($sql) === TRUE)
    {
        echo "New record created successfully\n";
    }
    else 
    {
        echo "Error: " . $sql . "\n" . $mysqli->error;
    }
});

$mosquitto->connect($mqtt_host, $mqtt_port);
while (true) {
    $mosquitto->loop();
}
?>
