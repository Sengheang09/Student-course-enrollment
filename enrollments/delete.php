<?php
require_once "../config/db.php";
require_once "../helpers/flash.php";

$id = $_GET["id"] ?? null;
if (!$id) {
    set_flash("danger", "Invalid request.");
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("DELETE FROM enrollments WHERE id=?");
$stmt->execute([$id]);

set_flash("success", "Enrollment deleted.");
header("Location: index.php");
exit;