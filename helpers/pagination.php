<?php
function paginate($total, $page, $perPage) {
    $totalPages = (int)ceil($total / $perPage);
    if ($totalPages < 1) $totalPages = 1;
    if ($page < 1) $page = 1;
    if ($page > $totalPages) $page = $totalPages;

    $offset = ($page - 1) * $perPage;

    return [
        'page' => $page,
        'perPage' => $perPage,
        'total' => $total,
        'totalPages' => $totalPages,
        'offset' => $offset
    ];
}