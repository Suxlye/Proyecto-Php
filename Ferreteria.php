<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], input[type="date"], select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registrar Nuevo Cliente</h2>
        <form action="clientes.php" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br>

            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required><br>

            <label for="genero">Género:</label>
            <select id="genero" name="genero" required>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
                <option value="Otro">Otro</option>
            </select><br>

            <input type="submit" value="Registrar Cliente" name="submit">
        </form>

        <hr>
        <h2>Clientes Registrados</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Fecha de Nacimiento</th>
                <th>Género</th>
                <th>Acciones</th>
            </tr>
            <?php

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "Registro"; 

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            if (isset($_POST['submit'])) {
                $nombre = $_POST['nombre'];
                $fecha_nacimiento = $_POST['fecha_nacimiento'];
                $genero = $_POST['genero'];

                $hoy = new DateTime();
                $fecha_nacimiento = new DateTime($fecha_nacimiento);
                $edad = $hoy->diff($fecha_nacimiento)->y;

    
                $stmt = $conn->prepare("INSERT INTO clientes (nombre, fecha_nacimiento, genero, edad) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sssi", $nombre, $fecha_nacimiento->format('Y-m-d'), $genero, $edad);
                if ($stmt->execute()) {
                    echo "<script>alert('Cliente registrado correctamente');</script>";
                } else {
                    echo "Error: " . $stmt->error;
                }
            }

            $sql = "SELECT id, nombre, fecha_nacimiento, genero FROM clientes";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["nombre"] . "</td>";
                    echo "<td>" . $row["fecha_nacimiento"] . "</td>";
                    echo "<td>" . $row["genero"] . "</td>";
                    echo "<td><a href='clientes.php?edit=" . $row["id"] . "'>Editar</a> | <a href='clientes.php?delete=" . $row["id"] . "' onclick='return confirm(\"¿Estás seguro de que quieres eliminar este cliente?\")'>Eliminar</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay clientes registrados</td></tr>";
            }

            if (isset($_GET['delete'])) {
                $id = $_GET['delete'];
                $sql = "DELETE FROM clientes WHERE id=$id";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('Cliente eliminado correctamente');</script>";
                } else {
                    echo "Error al eliminar el cliente: " . $conn->error;
                }
            }
            
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
