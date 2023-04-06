<!DOCTYPE html>
<html>
<head>
    <title>Strona główna</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header class="header">
	<a href="http://localhost/wyjazdy" class="logo">Strona główna</a>
	</header>
    <div class="container">
  <div class="box"><h1>Formularz kosztów wyjazdów służbowych</h1>
  <form id="upload" method="GET" action="formularz.php">
    <div class="formularz">
        <label for="data_wyjazdu">Data wyjazdu:</label>
        <input type="date" name="data_wyjazdu" required><br><br>
        
        <label for="koszt_transportu">Koszt transportu:</label>
        <input type="number" step="0.01" name="koszt_transportu" required><br><br>
        
        <label for="koszt_noclegow">Koszt noclegów:</label>
        <input type="number" step="0.01" name="koszt_noclegow" required><br><br>
        
        <label for="koszt_wyzywienia">Koszt wyżywienia:</label>
        <input type="number" step="0.01" name="koszt_wyzywienia" required><br><br>

        <label for="id_pracownika">Id pracownika:</label>
        <input type="number" step="0.01" name="id_pracownika" required><br><br>
        
        <input type="submit" value="Dodaj koszt">
        
    </form>
</div></div>
 <div class="box"><h1>Suma kosztów pracowników</h1>
  <div class="suma">
  <?php
  error_reporting(0);
  // połączenie z bazą danych
  $servername = "localhost";
  $username = "admin";
  $password = "admin";
  $dbname = "wyjazdy";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Nieudane połączenie z bazą danych: " . $conn->connect_error);
}
 // pobranie danych z bazy danych
if(isset($_GET['id_wyjazdu'])){
  // wyświetlanie kosztów związanych z pojedynczym wyjazdem
  $id_wyjazdu = $_GET['id_wyjazdu'];
  $sql = "SELECT * FROM koszty_wyjazdow WHERE id_wyjazdu = $id_wyjazdu";
} else {
  // wyświetlanie kosztów związanych z całymi zespołami lub działami
  $sql = "SELECT id_pracownika, SUM(koszt_transportu) AS koszt_transportu, SUM(koszt_noclegow) AS koszt_noclegow, SUM(koszt_wyzywienia) AS koszt_wyzywienia 
          FROM koszty_wyjazdow 
          GROUP BY id_pracownika";
}
$result = $conn->query($sql);
// wyświetlanie danych w formie tabeli i wykresu
if ($result->num_rows > 0) {
    echo "<div class='test'>";
    echo "<table align='center'><tr><th>&nbsp;ID Pracownika&nbsp;&nbsp;&nbsp; </th><th>&nbsp;Koszt Transportu&nbsp;&nbsp;&nbsp;</th><th>&nbsp;Koszt Noclegów&nbsp;&nbsp;&nbsp;&nbsp</th><th>  &nbsp; Koszt Wyżywienia&nbsp;&nbsp;&nbsp;   </th></tr>";
    while($row = $result->fetch_assoc()) {
      echo "<tr><td>" . $row["id_pracownika"] . "</td><td>" . $row["koszt_transportu"] . "</td><td>" . $row["koszt_noclegow"] . "</td><td>" . $row["koszt_wyzywienia"] . "</td></tr>";
    }
    echo "</table>";
    echo "</div>";
}
?>
</div>
</div>
</div>
<div class="box">    <?php // Zapytanie SQL
$sql = "SELECT id_pracownika, SUM(koszt_transportu) AS koszt_transportu, SUM(koszt_noclegow) AS koszt_noclegow, SUM(koszt_wyzywienia) AS koszt_wyzywienia FROM koszty_wyjazdow GROUP BY id_pracownika";

// Wykonanie zapytania SQL
$result = $conn->query($sql);

// Przetworzenie wyników zapytania SQL do formatu, który może być użyty przez bibliotekę Chart.js
$data = array(
    'labels' => array(),
    'datasets' => array(
        array(
            'label' => 'Koszt transportu',
            'data' => array(),
            'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
            'borderColor' => 'rgba(255, 99, 132, 1)',
            'borderWidth' => 1
        ),
        array(
            'label' => 'Koszt noclegów',
            'data' => array(),
            'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
            'borderColor' => 'rgba(54, 162, 235, 1)',
            'borderWidth' => 1
        ),
        array(
            'label' => 'Koszt wyżywienia',
            'data' => array(),
            'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
            'borderColor' => 'rgba(255, 206, 86, 1)',
            'borderWidth' => 1
        )
    )
);

while ($row = $result->fetch_assoc()) {
    $data['labels'][] = $row['id_pracownika'];
    $data['datasets'][0]['data'][] = $row['koszt_transportu'];
    $data['datasets'][1]['data'][] = $row['koszt_noclegow'];
    $data['datasets'][2]['data'][] = $row['koszt_wyzywienia'];
}

// Zamknięcie połączenia z bazą danych
$conn->close();

// Kod JavaScript wyświetlający wykres na stronie
?>
    <canvas id="myChart"></canvas>
    <script>
      Chart.defaults.color = '#fff';
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx,
         {
            type: 'bar',
            data: <?php echo json_encode($data); ?>,
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                            
                        }
                    }]
                }
            }
        });
    </script>
</div>
</div></div>
</script>
</body>
</html>