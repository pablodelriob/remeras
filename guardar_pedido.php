<?php
// 📁 Directorios de almacenamiento
$carpetaJSON = "pedidos/";
$carpetaIMG = "pedidos_img/";

// 🔐 Asegurar que existan las carpetas
if (!file_exists($carpetaJSON)) mkdir($carpetaJSON, 0777, true);
if (!file_exists($carpetaIMG)) mkdir($carpetaIMG, 0777, true);

// 🔢 Generar ID único (desde frontend)
$id = isset($_POST["id_pedido"]) ? preg_replace('/\D/', '', $_POST["id_pedido"]) : time();
$imgName = $id . ".png";
$jsonName = $id . "_pedido.json";

// 📷 Procesar imagen recibida
$imagenGuardada = false;
if (
    isset($_FILES["imagen_diseño"]) &&
    $_FILES["imagen_diseño"]["error"] === UPLOAD_ERR_OK &&
    is_uploaded_file($_FILES["imagen_diseño"]["tmp_name"])
) {
    $imagenGuardada = move_uploaded_file($_FILES["imagen_diseño"]["tmp_name"], $carpetaIMG . $imgName);
}

// 📋 Capturar datos del formulario
function limpiarCampo($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : '';
}

$datos = [
    "nombre" => limpiarCampo("nombre_cliente"),
    "telefono" => limpiarCampo("telefono_cliente"),
    "ciudad" => limpiarCampo("ciudad_cliente"),
    "descripcion" => limpiarCampo("descripcion_cliente"),
    "talles" => limpiarCampo("detalles_remeras_clientes"),
    "imagen" => $imagenGuardada ? $imgName : ""
];

// 📝 Guardar JSON
$resultadoJSON = file_put_contents($carpetaJSON . $jsonName, json_encode($datos, JSON_PRETTY_PRINT));

// 📣 Resultado
if ($resultadoJSON) {
    echo "<h3>✅ Pedido guardado correctamente</h3>";
    echo "<p><strong>ID:</strong> $id</p>";
    echo "<p><strong>Imagen:</strong> " . ($imagenGuardada ? $imgName : "No se recibió") . "</p>";
} else {
    echo "<h3>❌ Error al guardar el pedido</h3>";
}
