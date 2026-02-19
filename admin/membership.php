<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
include '../db_connect.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM membership_requests WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: membership.php?msg=Request deleted");
    exit;
}

// Handle Status Change
if (isset($_GET['approve'])) {
    $id = $_GET['approve'];
    $stmt = $conn->prepare("UPDATE membership_requests SET status = 'approved' WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: membership.php?msg=Member approved");
    exit;
}

if (isset($_GET['reject'])) {
    $id = $_GET['reject'];
    $stmt = $conn->prepare("UPDATE membership_requests SET status = 'rejected' WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: membership.php?msg=Member rejected");
    exit;
}

// Fetch Membership Requests
$requests = $conn->query("SELECT * FROM membership_requests ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header" style="margin-bottom: 3rem;">
            <h1 class="section-title">Membership Applications</h1>
            <p class="section-subtitle">Manage new students and professionals who wish to join Bandari Tech & Innovation
                Club.</p>
        </header>

        <div class="glass-card" style="padding: 0; overflow: hidden;">
            <?php if (count($requests) > 0): ?>
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th width="200">Applicant</th>
                            <th>Academic Info</th>
                            <th>Interests / Motivation</th>
                            <th width="100">Status</th>
                            <th width="140">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $req): ?>
                            <tr>
                                <td data-label="Applicant">
                                    <div style="font-weight: 700; color: var(--white);">
                                        <?php echo htmlspecialchars($req['full_name']); ?>
                                    </div>
                                    <div
                                        style="font-size: 0.8rem; color: var(--accent); font-weight: 600; margin-bottom: 0.1rem;">
                                        Adm: <?php echo htmlspecialchars($req['admission_number']); ?>
                                    </div>
                                    <div style="font-size: 0.8rem; color: var(--muted); margin-top: 0.1rem;">
                                        <?php echo htmlspecialchars($req['email']); ?>
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--muted); opacity: 0.8;">
                                        <?php echo htmlspecialchars($req['phone']); ?>
                                    </div>
                                </td>
                                <td data-label="Academic Info">
                                    <div style="font-weight: 600; color: var(--muted);">
                                        <?php echo htmlspecialchars($req['institution']); ?>
                                    </div>
                                    <div style="font-size: 0.8rem; color: var(--muted);">
                                        <?php echo htmlspecialchars($req['course']); ?>
                                        <span style="color: var(--accent);">•
                                            <?php echo htmlspecialchars($req['year_of_study']); ?></span>
                                    </div>
                                </td>
                                <td data-label="Interests" style="color: var(--muted); line-height: 1.5; font-size: 0.9rem;">
                                    <?php echo htmlspecialchars($req['interests'] ?: 'No interests specified.'); ?>
                                </td>
                                <td data-label="Status">
                                    <?php
                                    $statusClass = 'badge-secondary';
                                    if ($req['status'] == 'approved')
                                        $statusClass = 'badge-accent';
                                    if ($req['status'] == 'rejected')
                                        $statusClass = 'badge-warning';
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?>">
                                        <?php echo strtoupper($req['status']); ?>
                                    </span>
                                </td>
                                <td data-label="Actions">
                                    <div style="display: flex; gap: 0.75rem;">
                                        <?php if ($req['status'] == 'pending'): ?>
                                            <a href="membership.php?approve=<?php echo $req['id']; ?>" class="btn btn-secondary"
                                                style="padding: 0.5rem; border-radius: 10px;" title="Approve">
                                                <i class="ri-check-line"></i>
                                            </a>
                                            <a href="membership.php?reject=<?php echo $req['id']; ?>" class="btn"
                                                style="padding: 0.5rem; border-radius: 10px; background: rgba(255,107,107,0.1); color: #ff6b6b;"
                                                title="Reject">
                                                <i class="ri-close-line"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="membership.php?delete=<?php echo $req['id']; ?>" class="btn"
                                            style="padding: 0.5rem; border-radius: 10px; background: rgba(255,255,255,0.05); color: var(--muted);"
                                            onclick="return confirm('Delete this application permanently?')" title="Delete">
                                            <i class="ri-delete-bin-7-line"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="text-align: center; padding: 5rem 2rem;">
                    <i class="ri-user-add-line"
                        style="font-size: 3rem; color: var(--muted); opacity: 0.3; display: block; margin-bottom: 1rem;"></i>
                    <p style="color: var(--muted); font-weight: 500;">No membership applications found.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>

</html>