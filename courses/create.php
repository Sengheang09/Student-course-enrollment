<?php
require_once "../config/db.php";
require_once "../helpers/flash.php";

$pageTitle = "Add Course";
$errors = [];

$course_name = $_POST['course_name'] ?? '';
$credit = $_POST['credit'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $course_name = trim($course_name);
  $credit = (int)$credit;

  if ($course_name === "") $errors[] = "Course name is required.";
  if ($credit <= 0) $errors[] = "Credit must be greater than 0.";

  if (!$errors) {
    $stmt = $pdo->prepare("INSERT INTO courses(course_name, credit) VALUES(?, ?)");
    $stmt->execute([$course_name, $credit]);
    set_flash("success", "Course added successfully.");
    header("Location: index.php");
    exit;
  }
}

require_once "../partials/header.php";
?>
<div class="card shadow-sm border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Add Course</h4>
      <a class="btn btn-outline-secondary btn-sm" href="index.php">Back</a>
    </div>

    <?php if ($errors): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach($errors as $er): ?><li><?= htmlspecialchars($er) ?></li><?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" class="row g-3">
      <div class="col-md-8">
        <label class="form-label">Course Name</label>
        <input class="form-control" name="course_name" value="<?= htmlspecialchars($course_name) ?>" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Credit</label>
        <input class="form-control" type="number" name="credit" min="1" max="10"
               value="<?= htmlspecialchars($credit) ?>" required>
      </div>

      <div class="col-12 d-flex gap-2">
        <button class="btn btn-success">Save</button>
        <a class="btn btn-outline-secondary" href="index.php">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php require_once "../partials/footer.php"; ?>