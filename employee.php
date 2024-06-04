<?php
session_start();
$iduser = $_SESSION["id"];
$name = $_SESSION["username"];
include("connection.php");
if (isset($_POST["clock"])) {
    $sql = "INSERT INTO clock_in (entry, user_id) VALUES (NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $iduser);
    $stmt->execute();
    header("Location: index");
}

if (isset($_POST["out"])) {
    try {
        $sqll = "SELECT * FROM fichajes.clock_in where entry is null and leaving is null and user_id = ?";
        $s = $conn->prepare($sqll);
        $s->bindParam(1, $iduser);
        $s->execute();
        if ($s->rowCount() > 0) {
            echo "Is is not allowed to sing in the clock out if there is not registered the clock in. You should speack with Human Resources Departement for trying to insert your clock in manually";
        } else {
            $sql1 = "SELECT * FROM fichajes.clock_in where leaving is null and user_id = ?";
            $st = $conn->prepare($sql1);
            $st->bindParam(1, $iduser);
            $st->execute();
            if ($st->rowCount() > 0) {
                $sql2 = "UPDATE clock_in SET leaving = NOW() WHERE user_id = ?";
                $stm = $conn->prepare($sql2);
                $stm->bindParam(1, $iduser);
                $stm->execute();
                header("Location: index");
            } else {
                $error = "You have already checked out before";
            }
        }
    } catch (Exception $e) {
        $error = "Error interno" . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee</title>
</head>

<body>
    <h1>Welcome to Gabimania,<?php echo "<p>$name</p>" ?></h1>
    <h2>It´s time to clock in?</h2>
    <form method="post" action="">
        <input type="submit" value="Clock in" name="clock">
    </form>
    <h2>It´s time to clock out</h2>
    <form method="post" action="">
        <input type="submit" value="Clock out" name="out">
    </form>
    <a href="index"><button>Volver</button></a>
    <?php
    if (isset($error)) {
        echo "<p class='error-msg'>" . $error . "</p>";
    }
    ?>


</body>

</html>