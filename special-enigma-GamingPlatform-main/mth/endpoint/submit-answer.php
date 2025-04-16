<?php
include("../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name'], $_POST['score'])) {
        $name = $_POST['name'];
        $score = $_POST['score'];

        try {
            $stmt = $conn->prepare("INSERT INTO tbl_leaderboard (name, score) VALUES (:name, :score)");
            
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":score", $score, PDO::PARAM_INT);

            $stmt->execute();

            echo "
                <script>
                    alert('Score Submitted Added! View Leaderboard!');
                    window.location.href = 'http://localhost/GameZone/mth/';
                </script>
                ";

            exit();
        } catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }

    }
}
?>
