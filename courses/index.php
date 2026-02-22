<?php
require_once "../config/db.php";
require_once "../helpers/pagination.php";

$pageTitle = "Courses";

$q = trim($_GET['q'] ?? '');
$page = (int)($_GET['page'] ?? 1);
$perPage = 7;

$params = [];
$where = "";
if ($q !== "") {
  $where = "WHERE course_name LIKE ?";
  $params = ["%$q%"];
}

$countSql = "SELECT COUNT(*) FROM courses $where";
$stmt = $pdo->prepare($countSql);
$stmt->execute($params);
$total = (int)$stmt->fetchColumn();

$pg = paginate($total, $page, $perPage);

$dataSql = "SELECT * FROM courses
            $where
            ORDER BY id DESC
            LIMIT {$pg['perPage']} OFFSET {$pg['offset']}";
$stmt = $pdo->prepare($dataSql);
$stmt->execute($params);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once "../partials/header.php";
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
  <div>
    <h3 class="mb-1">Courses</h3>
    <div class="text-muted small">Manage courses (CRUD) + search + pagination</div>
  </div>
  <a class="btn btn-success" href="create.php">+ Add Course</a>
</div>

<div class="card shadow-sm border-0">
  <div class="card-body">

    <form class="row g-2 mb-3" method="GET">
      <div class="col-md-8">
        <input class="form-control" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search course name...">
      </div>
      <div class="col-md-4 d-flex gap-2">
        <button class="btn btn-outline-success w-100">Search</button>
        <a class="btn btn-outline-secondary w-100" href="index.php">Reset</a>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-success">
          <tr>
            <th style="width:90px;">ID</th>
            <th>Course Name</th>
            <th style="width:120px;">Credit</th>
            <th style="width:170px;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$courses): ?>
            <tr>
              <td colspan="4" class="text-center text-muted py-4">No data found.</td>
            </tr>
          <?php endif; ?>

          <?php foreach($courses as $c): ?>
            <tr>
              <td><?= $c['id'] ?></td>
              <td class="fw-semibold"><?= htmlspecialchars($c['course_name']) ?></td>
              <td><span class="badge bg-success-subtle text-success"><?= (int)$c['credit'] ?></span></td>
              <td class="d-flex gap-2">
                <a class="btn btn-sm btn-outline-primary" href="edit.php?id=<?= $c['id'] ?>">Edit</a>
                <a class="btn btn-sm btn-outline-danger"
                   href="delete.php?id=<?= $c['id'] ?>"
                   onclick="return confirm('Delete this course?')">Delete</a>
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