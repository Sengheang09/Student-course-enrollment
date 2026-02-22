<?php
require_once "../config/db.php";
require_once "../helpers/pagination.php";

$pageTitle = "Enrollments";

$q = trim($_GET['q'] ?? '');
$page = (int)($_GET['page'] ?? 1);
$perPage = 7;

// Count
$params = [];
$where = "";
if ($q !== "") {
    $where = "WHERE s.name LIKE ? OR c.course_name LIKE ?";
    $params = ["%$q%", "%$q%"];
}

$countSql = "SELECT COUNT(*) 
             FROM enrollments e
             JOIN students s ON e.student_id = s.id
             JOIN courses c ON e.course_id = c.id
             $where";
$stmt = $pdo->prepare($countSql);
$stmt->execute($params);
$total = (int)$stmt->fetchColumn();

$pg = paginate($total, $page, $perPage);

// Data
$dataSql = "SELECT e.id, s.name AS student_name, c.course_name
            FROM enrollments e
            JOIN students s ON e.student_id = s.id
            JOIN courses c ON e.course_id = c.id
            $where
            ORDER BY e.id DESC
            LIMIT {$pg['perPage']} OFFSET {$pg['offset']}";

$stmt = $pdo->prepare($dataSql);
$stmt->execute($params);
$enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once "../partials/header.php";
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
  <h3 class="mb-0">Enrollments</h3>
  <a class="btn btn-primary" href="create.php">+ Add Enrollment</a>
</div>

<div class="card shadow-sm border-0">
  <div class="card-body">

    <form class="row g-2 mb-3" method="GET">
      <div class="col-md-8">
        <input class="form-control" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search by student or course...">
      </div>
      <div class="col-md-4 d-flex gap-2">
        <button class="btn btn-outline-primary w-100">Search</button>
        <a class="btn btn-outline-secondary w-100" href="index.php">Reset</a>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle">
        <thead class="table-primary">
          <tr>
            <th style="width:80px;">ID</th>
            <th>Student</th>
            <th>Course</th>
            <th style="width:120px;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$enrollments): ?>
            <tr><td colspan="4" class="text-center text-muted py-4">No data found.</td></tr>
          <?php endif; ?>

          <?php foreach($enrollments as $e): ?>
            <tr>
              <td><?= $e['id'] ?></td>
              <td><?= htmlspecialchars($e['student_name']) ?></td>
              <td><?= htmlspecialchars($e['course_name']) ?></td>
              <td>
                <a class="btn btn-sm btn-outline-danger"
                   href="delete.php?id=<?= $e['id'] ?>"
                   onclick="return confirm('Delete this enrollment?')">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <nav>
      <ul class="pagination mb-0">
        <?php
          $base = "index.php?q=" . urlencode($q) . "&page=";
          $prev = $pg['page'] - 1;
          $next = $pg['page'] + 1;
        ?>
        <li class="page-item <?= $pg['page'] <= 1 ? 'disabled' : '' ?>">
          <a class="page-link" href="<?= $base . $prev ?>">Previous</a>
        </li>

        <?php for($i=1; $i <= $pg['totalPages']; $i++): ?>
          <li class="page-item <?= $i == $pg['page'] ? 'active' : '' ?>">
            <a class="page-link" href="<?= $base . $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>

        <li class="page-item <?= $pg['page'] >= $pg['totalPages'] ? 'disabled' : '' ?>">
          <a class="page-link" href="<?= $base . $next ?>">Next</a>
        </li>
      </ul>
    </nav>

  </div>
</div>

<?php require_once "../partials/footer.php"; ?>