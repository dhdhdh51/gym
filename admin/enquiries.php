<?php
require_once __DIR__ . '/../config.php';
require_login();

// Build filter conditions (shared by list + export)
$search = trim((string)($_GET['q'] ?? ''));
$filter = (string)($_GET['type'] ?? '');
$where  = '1=1';
$params = [];
if ($search !== '') {
    $where .= ' AND (name LIKE :q OR phone LIKE :q OR email LIKE :q OR whatsapp LIKE :q)';
    $params[':q'] = '%' . $search . '%';
}
if (in_array($filter, ['contact', 'free_trial'], true)) {
    $where .= ' AND enquiry_type = :type';
    $params[':type'] = $filter;
}

// ---- CSV export (must run before any HTML) ----
if (($_GET['export'] ?? '') === 'csv') {
    $rows = fetch_all("SELECT * FROM enquiries WHERE {$where} ORDER BY created_at DESC", $params);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="enquiries_' . date('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['ID', 'Type', 'Name', 'Phone', 'WhatsApp', 'Email', 'Age', 'Gender', 'Fitness Goal', 'Preferred Time', 'Message', 'Status', 'Notes', 'Date'], ',', '"', '\\');
    foreach ($rows as $r) {
        fputcsv($out, [
            $r['id'], $r['enquiry_type'], $r['name'], $r['phone'], $r['whatsapp'], $r['email'],
            $r['age'], $r['gender'], $r['fitness_goal'], $r['preferred_time'], $r['message'],
            $r['status'], $r['notes'], $r['created_at'],
        ], ',', '"', '\\');
    }
    fclose($out);
    exit;
}

// ---- POST actions ----
if (is_post()) {
    require_csrf();
    $id = (int)input('id');
    $action = input('action');
    if ($action === 'delete' && $id) {
        db()->prepare('DELETE FROM enquiries WHERE id = :id')->execute([':id' => $id]);
        flash('Enquiry deleted.');
    } elseif ($action === 'contacted' && $id) {
        db()->prepare('UPDATE enquiries SET status = "contacted" WHERE id = :id')->execute([':id' => $id]);
        flash('Marked as contacted.');
    } elseif ($action === 'new' && $id) {
        db()->prepare('UPDATE enquiries SET status = "new" WHERE id = :id')->execute([':id' => $id]);
        flash('Marked as new.');
    } elseif ($action === 'note' && $id) {
        db()->prepare('UPDATE enquiries SET notes = :n WHERE id = :id')->execute([':n' => input('notes'), ':id' => $id]);
        flash('Note saved.');
    }
    header('Location: ' . url('admin/enquiries.php?' . http_build_query(array_filter(['q' => $search, 'type' => $filter]))));
    exit;
}

$admin_title = 'Enquiries & Trials';
require __DIR__ . '/includes/admin-header.php';

$enquiries = fetch_all("SELECT * FROM enquiries WHERE {$where} ORDER BY created_at DESC", $params);
$exportQs  = http_build_query(array_filter(['q' => $search, 'type' => $filter, 'export' => 'csv']));
?>
<div class="page-title">Enquiries & Trial Bookings</div>
<div class="page-sub">Showing <?php echo count($enquiries); ?> enquiry(ies).</div>

<div class="toolbar">
  <form method="get" action="<?php echo e(url('admin/enquiries.php')); ?>">
    <input type="text" name="q" placeholder="Search name, phone, email..." value="<?php echo e($search); ?>">
    <select name="type">
      <option value="">All Types</option>
      <option value="contact" <?php echo $filter === 'contact' ? 'selected' : ''; ?>>Contact</option>
      <option value="free_trial" <?php echo $filter === 'free_trial' ? 'selected' : ''; ?>>Free Trial</option>
    </select>
    <button class="abtn abtn-ghost abtn-sm" type="submit">Filter</button>
    <a class="abtn abtn-ghost abtn-sm" href="<?php echo e(url('admin/enquiries.php')); ?>">Reset</a>
  </form>
  <a class="abtn abtn-primary abtn-sm" href="<?php echo e(url('admin/enquiries.php?' . $exportQs)); ?>">⬇ Export CSV</a>
</div>

<div class="acard">
  <?php if ($enquiries): ?>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Name</th><th>Type</th><th>Contact</th><th>Details</th><th>Status</th><th>Notes</th><th>Date</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($enquiries as $r): ?>
        <tr>
          <td><strong><?php echo e($r['name']); ?></strong></td>
          <td><span class="badge <?php echo $r['enquiry_type'] === 'free_trial' ? 'badge-blue' : 'badge-gray'; ?>"><?php echo $r['enquiry_type'] === 'free_trial' ? 'Free Trial' : 'Contact'; ?></span></td>
          <td style="font-size:.82rem">
            <?php if ($r['phone']): ?>📞 <?php echo e($r['phone']); ?><br><?php endif; ?>
            <?php if ($r['whatsapp']): ?>💬 <?php echo e($r['whatsapp']); ?><br><?php endif; ?>
            <?php if ($r['email']): ?>✉️ <?php echo e($r['email']); ?><?php endif; ?>
          </td>
          <td style="font-size:.82rem" class="muted">
            <?php if ($r['age']): ?>Age: <?php echo e($r['age']); ?><br><?php endif; ?>
            <?php if ($r['gender']): ?>Gender: <?php echo e($r['gender']); ?><br><?php endif; ?>
            <?php if ($r['fitness_goal']): ?>Goal: <?php echo e($r['fitness_goal']); ?><br><?php endif; ?>
            <?php if ($r['preferred_time']): ?>Time: <?php echo e($r['preferred_time']); ?><br><?php endif; ?>
            <?php if ($r['message']): ?>Msg: <?php echo e(excerpt((string)$r['message'], 14)); ?><?php endif; ?>
          </td>
          <td><span class="badge <?php echo $r['status'] === 'new' ? 'badge-amber' : 'badge-green'; ?>"><?php echo e(ucfirst($r['status'])); ?></span></td>
          <td>
            <form method="post" action="<?php echo e(url('admin/enquiries.php?' . http_build_query(array_filter(['q' => $search, 'type' => $filter])))); ?>">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="action" value="note">
              <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
              <textarea name="notes" style="min-height:50px;font-size:.8rem" placeholder="Add note..."><?php echo e($r['notes']); ?></textarea>
              <button class="abtn abtn-ghost abtn-sm" type="submit" style="margin-top:4px">Save Note</button>
            </form>
          </td>
          <td class="muted" style="font-size:.8rem"><?php echo e(format_date($r['created_at'], 'M j, Y g:i A')); ?></td>
          <td>
            <div class="actions-cell">
              <?php $qs = http_build_query(array_filter(['q' => $search, 'type' => $filter])); ?>
              <?php if ($r['status'] === 'new'): ?>
              <form method="post" action="<?php echo e(url('admin/enquiries.php?' . $qs)); ?>" style="display:inline"><?php echo csrf_field(); ?><input type="hidden" name="action" value="contacted"><input type="hidden" name="id" value="<?php echo $r['id']; ?>"><button class="abtn abtn-ghost abtn-sm" type="submit">Mark Contacted</button></form>
              <?php else: ?>
              <form method="post" action="<?php echo e(url('admin/enquiries.php?' . $qs)); ?>" style="display:inline"><?php echo csrf_field(); ?><input type="hidden" name="action" value="new"><input type="hidden" name="id" value="<?php echo $r['id']; ?>"><button class="abtn abtn-ghost abtn-sm" type="submit">Mark New</button></form>
              <?php endif; ?>
              <form method="post" action="<?php echo e(url('admin/enquiries.php?' . $qs)); ?>" style="display:inline" data-confirm="Delete this enquiry?"><?php echo csrf_field(); ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo $r['id']; ?>"><button class="abtn abtn-danger abtn-sm" type="submit">Delete</button></form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
    <p class="muted">No enquiries found.</p>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
