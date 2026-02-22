<?php
require_once "../config/db.php";
require_once "../helpers/flash.php";

$pageTitle = "Add Student";
$errors = [];

$name = $_POST['name'] ?? '';
$gender = $_POST['gender'] ?? 'Male';
$email = $_POST['email'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = trim($name);
  $email = trim($email);

  if ($name === "") $errors[] = "Name is required.";
  if (!in_array($gender, ["Male","Female"])) $errors[] = "Invalid gender.";
  if ($email === "") $errors[] = "Email is required.";
  if ($email !== "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";

  // unique email check
  if (!$errors) {
    $check = $pdo->prepare("SELECT COUNT(*) FROM students WHERE email=?");
    $check->execute([$email]);
    if ((int)$check->fetchColumn() > 0) {
      $errors[] = "Email already exists. Use another email.";
    }
  }

  if (!$errors) {
    $stmt = $pdo->prepare("INSERT INTO students(name, gender, email) VALUES(?, ?, ?)");
    $stmt->execute([$name, $gender, $email]);
    set_flash("success", "Student added successfully.");
    header("Location: index.php");
    exit;
  }
}

require_once "../partials/header.php";
?>
<div class="card shadow-sm border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Add Student</h4>
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
      <div class="col-md-6">
        <label class="form-label">Name</label>
        <input class="form-control" name="name" value="<?= htmlspecialchars($name) ?>" required>
      </div>

      <div class="col-md-3">
        <label class="form-label">Gender</label>
        <select class="form-select" name="gender" required>
          <option value="Male" <?= $gender=="Male"?"selected":"" ?>>Male</option>
          <option value="Female" <?= $gender=="Female"?"selected":"" ?>>Female</option>
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label">Email</label>
        <input class="form-control" type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
      </div>

      <div class="col-12 d-flex gap-2">
        <button class="btn btn-success">Save</button>
        <a class="btn btn-outline-secondary" href="index.php">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php require_once "../partials/footer.php"; ?>