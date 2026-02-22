<?php
require_once "../config/db.php";
require_once "../helpers/flash.php";

$pageTitle = "Add Enrollment";

$students = $pdo->query("SELECT id, name FROM students ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$courses  = $pdo->query("SELECT id, course_name FROM courses ORDER BY course_name ASC")->fetchAll(PDO::FETCH_ASSOC);

$errors = [];
$student_id = $_POST['student_id'] ?? '';
$course_id  = $_POST['course_id'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($student_id === '') $errors[] = "Please select a student.";
    if ($course_id === '')  $errors[] = "Please select a course.";

    if (!$errors) {
        // Extra safety: check duplicate before insert (even though DB UNIQUE key blocks it)
        $check = $pdo->prepare("SELECT COUNT(*) FROM enrollments WHERE student_id=? AND course_id=?");
        $check->execute([$student_id, $course_id]);
        $exists = (int)$check->fetchColumn();

        if ($exists > 0) {
            $errors[] = "This student is already enrolled in this course.";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO enrollments(student_id, course_id) VALUES(?, ?)");
                $stmt->execute([$student_id, $course_id]);
                set_flash("success", "Enrollment added successfully.");
                header("Location: index.php");
                exit;
            } catch (PDOException $e) {
                // If DB UNIQUE key triggers duplicate
                if ($e->getCode() == 23000) {
                    $errors[] = "Duplicate enrollment is not allowed.";
                } else {
                    $errors[] = "Error: " . $e->getMessage();
                }
            }
        }
    }
}

require_once "../partials/header.php";
?>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <h4 class="mb-3">Add Enrollment</h4>

    <?php if ($errors): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $er): ?>
            <li><?= htmlspecialchars($er) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Student</label>
        <select class="form-select" name="student_id" required>
          <option value="">-- Select Student --</option>
          <?php foreach($students as $s): ?>
            <option value="<?= $s['id'] ?>" <?= ($student_id == $s['id']) ? "selected" : "" ?>>
              <?= htmlspecialchars($s['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label">Course</label>
        <select class="form-select" name="course_id" required>
          <option value="">-- Select Course --</option>
          <?php foreach($courses as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($course_id == $c['id']) ? "selected" : "" ?>>
              <?= htmlspecialchars($c['course_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12 d-flex gap-2">
        <button class="btn btn-primary">Save</button>
        <a class="btn btn-outline-secondary" href="index.php">Back</a>
      </div>
    </form>
  </div>
</div>

<?php require_once "../partials/footer.php"; ?>