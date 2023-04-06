<?php error_reporting(0);
// połączenie z bazą danych
$servername = "localhost";
$username = "admin";
$password = "admin";
$dbname = "wyjazdy";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Nieudane połączenie z bazą danych: " . $conn->connect_error);
}

// pobranie danych z formularza
$data_wyjazdu = $_GET['data_wyjazdu'];
$koszt_transportu = $_GET['koszt_transportu'];
$koszt_noclegow = $_GET['koszt_noclegow'];
$koszt_wyzywienia = $_GET['koszt_wyzywienia'];
$id_pracownika = $_GET['id_pracownika'];

// przygotowanie i wykonanie zapytania SQL
$sql = "INSERT INTO koszty_wyjazdow (data_wyjazdu, koszt_transportu, koszt_noclegow, koszt_wyzywienia, id_pracownika) 
        VALUES ('$data_wyjazdu', '$koszt_transportu', '$koszt_noclegow', '$koszt_wyzywienia', '$id_pracownika')";
if ($conn->query($sql) === TRUE) {
  echo "<script>window.location.href='http://localhost/wyjazdy';</script>";
} else {
  echo "Błąd podczas dodawania kosztu wyjazdu: " . $conn->error;
}
$conn->close();
?>