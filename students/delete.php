<?php
require_once "../config/db.php";
require_once "../helpers/flash.php";

$id = $_GET["id"] ?? null;
if (!$id) { set_flash("danger","Invalid request."); header("Location: index.php"); exit; }

$pdo->prepare("DELETE FROM students WHERE id=?")->execute([$id]);
set_flash("success", "Student deleted.");
header("Location: index.php");
exit;