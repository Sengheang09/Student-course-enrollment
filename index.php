<?php
require_once "config/db.php";
$pageTitle = "Home";
require_once "partials/header.php";

// For showing courses as cards (latest 6)
$courses = $pdo->query("SELECT * FROM courses ORDER BY id DESC LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HERO SECTION -->
<div class="hero mb-5">
  <div class="row align-items-center g-4">
    <div class="col-lg-7">
      <p class="text-success fw-semibold mb-2">Start your favourite course</p>
      <h1 class="display-5">
        Now learning from <br>
        anywhere, and build <span class="text-success">bright career</span>.
      </h1>
      <p class="text-muted mt-3">
        This is a Student Course Enrollment System using PHP CRUD + Many-to-Many relationship.
      </p>

      <a href="courses/index.php" class="btn btn-success px-4 py-2 mt-3">Start A Course</a>
    </div>

    <div class="col-lg-5 d-flex justify-content-center">
      <div class="badge-circle shadow">
        <div style="font-size:26px;"><?= count($courses) ?></div>
        <div style="font-size:14px; opacity:.9;">Courses</div>
      </div>
    </div>
  </div>
</div>

<!-- ALL COURSES HEADER -->
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
  <h3 class="fw-bold mb-0">All <span class="text-success">Courses</span></h3>

  <form class="search-box d-flex gap-2" action="courses/index.php" method="GET">
    <input class="form-control border-0" name="q" placeholder="Search your course...">
    <button class="btn btn-success">Search</button>
  </form>
</div>

<!-- COURSE CARDS -->
<div class="row g-4">
  <?php if(!$courses): ?>
    <div class="col-12">
      <div class="alert alert-warning">No courses yet. Add courses first.</div>
    </div>
  <?php endif; ?>

  <?php foreach($courses as $c): ?>
    <div class="col-md-6 col-lg-4">
      <div class="card course-card">
        <img class="course-thumb" src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800" alt="course">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="tag">Course</span>
            <span class="tag">Credit: <?= (int)$c['credit'] ?></span>
          </div>

          <h5 class="fw-bold"><?= htmlspecialchars($c['course_name']) ?></h5>

          <p class="text-muted small mb-3">
            Learn <?= htmlspecialchars($c['course_name']) ?> with clear lessons and practice.
          </p>

          <div class="d-flex justify-content-between align-items-center">
            <div class="price">$<?= (int)$c['credit'] * 50 ?></div>
            <a href="enrollments/create.php" class="btn btn-outline-success btn-sm">Enroll</a>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<div class="text-center mt-4">
  <a class="btn btn-success px-5" href="courses/index.php">Other Course</a>
</div>

<?php require_once "partials/footer.php"; ?>