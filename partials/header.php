<?php
if (!isset($pageTitle)) $pageTitle = "Enrollment System";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title><?= htmlspecialchars($pageTitle) ?></title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="/student_course_enrollment/assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-success" href="/student_course_enrollment/index.php">
      EduLe<span class="text-dark">CRUD</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav mx-auto gap-2">
        <li class="nav-item"><a class="nav-link" href="/student_course_enrollment/index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="/student_course_enrollment/courses/index.php">All Course</a></li>
        <li class="nav-item"><a class="nav-link" href="/student_course_enrollment/students/index.php">Students</a></li>
        <li class="nav-item"><a class="nav-link" href="/student_course_enrollment/enrollments/index.php">Enrollments</a></li>
      </ul>

      <div class="d-flex gap-2">
        <a class="btn btn-outline-success btn-sm" href="#">Sign In</a>
        <a class="btn btn-success btn-sm" href="#">Sign Up</a>
      </div>
    </div>
  </div>
</nav>

<div class="container py-4">