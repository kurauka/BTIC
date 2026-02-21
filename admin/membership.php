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

// Search and Filter logic
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';

$query = "SELECT * FROM membership_requests WHERE 1=1";
$params = [];

if ($search) {
    $query .= " AND (full_name LIKE :search OR admission_number LIKE :search OR email LIKE :search)";
    $params[':search'] = "%$search%";
}

if ($status_filter) {
    $query .= " AND status = :status";
    $params[':status'] = $status_filter;
}

$query .= " ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->execute($params);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header" style="margin-bottom: 2rem;">
            <div>
                <h1 class="section-title">Membership Applications</h1>
                <p class="section-subtitle">Manage new students and professionals who wish to join Bandari Tech &
                    Innovation Club.</p>
            </div>
            <a href="export_members.php" class="btn btn-secondary">
                <i class="ri-download-2-line"></i> Export CSV
            </a>
        </header>

        <!-- Search & Filter Bar -->
        <div class="glass-card" style="margin-bottom: 2rem; padding: 1.5rem;">
            <form method="GET" class="search-filter-grid">
                <div class="search-input-wrapper">
                    <i class="ri-search-line"></i>
                    <input type="text" name="search" placeholder="Search by name, admission, or email..."
                        value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <select name="status" onchange="this.form.submit()" style="flex: 1;">
                        <option value="">All Status</option>
                        <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Pending
                        </option>
                        <option value="approved" <?php echo $status_filter == 'approved' ? 'selected' : ''; ?>>Approved
                        </option>
                        <option value="rejected" <?php echo $status_filter == 'rejected' ? 'selected' : ''; ?>>Rejected
                        </option>
                    </select>
                    <button type="submit" class="btn btn-accent" style="flex: 1;">Filter</button>
                    <?php if ($search || $status_filter): ?>
                        <a href="membership.php" class="btn" style="background: rgba(255,255,255,0.05); flex: 1;">Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <style>
            .search-filter-grid {
                display: grid;
                grid-template-columns: 1.5fr 1fr;
                gap: 1.5rem;
                align-items: center;
            }

            .search-input-wrapper {
                position: relative;
                display: flex;
                align-items: center;
            }

            .search-input-wrapper i {
                position: absolute;
                left: 1rem;
                color: var(--muted);
            }

            .search-input-wrapper input {
                width: 100%;
                padding: 0.75rem 1rem 0.75rem 2.5rem;
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid var(--border);
                border-radius: 12px;
                color: var(--white);
            }

            .search-filter-grid select {
                padding: 0.75rem 1rem;
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid var(--border);
                border-radius: 12px;
                color: var(--white);
            }

            @media (max-width: 768px) {
                .search-filter-grid {
                    grid-template-columns: 1fr;
                }
            }

            /* Modal Overrides */
            .modal-content {
                background: var(--surface);
                border: 1px solid var(--border);
                margin: 10% auto;
                padding: 2rem;
                width: 50%;
                max-width: 600px;
                border-radius: 20px;
                position: relative;
            }

            .modal {
                display: none;
                position: fixed;
                z-index: 1100;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.8);
                backdrop-filter: blur(5px);
            }

            .close-modal {
                position: absolute;
                right: 1.5rem;
                top: 1.5rem;
                font-size: 1.5rem;
                cursor: pointer;
                color: var(--muted);
            }
        </style>

        <div class="glass-card" style="padding: 0; overflow: hidden;">
            <?php if (count($requests) > 0): ?>
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th width="200">Applicant</th>
                            <th>Academic Info</th>
                            <th width="100">Status</th>
                            <th width="160">Actions</th>
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
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button class="btn btn-secondary" style="padding: 0.5rem; border-radius: 10px;"
                                            onclick="showDetail(<?php echo htmlspecialchars(json_encode($req)); ?>)"
                                            title="View Details">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        <a href="mailto:<?php echo $req['email']; ?>" class="btn"
                                            style="padding: 0.5rem; border-radius: 10px; background: rgba(255,255,255,0.05); color: var(--muted);"
                                            title="Email">
                                            <i class="ri-mail-line"></i>
                                        </a>
                                        <?php if ($req['status'] == 'pending'): ?>
                                            <a href="membership.php?approve=<?php echo $req['id']; ?>" class="btn btn-secondary"
                                                style="padding: 0.5rem; border-radius: 10px; background: rgba(0,255,136,0.1); color: #00ff88;"
                                                title="Approve">
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

<!-- Detail Modal -->
<div id="detailModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <h2 id="modalName" style="margin-bottom: 0.5rem; font-family: 'Syne', sans-serif;"></h2>
        <p id="modalEmail" style="color: var(--accent); font-weight: 600; margin-bottom: 1.5rem;"></p>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
            <div>
                <label
                    style="color: var(--muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em;">Institution</label>
                <p id="modalInstitution" style="font-weight: 600;"></p>
            </div>
            <div>
                <label
                    style="color: var(--muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em;">Course</label>
                <p id="modalCourse" style="font-weight: 600;"></p>
            </div>
            <div>
                <label
                    style="color: var(--muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em;">Phone</label>
                <p id="modalPhone" style="font-weight: 600;"></p>
            </div>
            <div>
                <label
                    style="color: var(--muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em;">Status</label>
                <p id="modalStatus" style="font-weight: 600; text-transform: uppercase;"></p>
            </div>
        </div>

        <div>
            <label
                style="color: var(--muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em;">Interests
                & Motivation</label>
            <p id="modalInterests"
                style="margin-top: 0.5rem; line-height: 1.6; color: var(--muted); background: rgba(255,255,255,0.02); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border);">
            </p>
        </div>
    </div>
</div>

<script>
    function showDetail(data) {
        document.getElementById('modalName').innerText = data.full_name;
        document.getElementById('modalEmail').innerText = data.email;
        document.getElementById('modalInstitution').innerText = data.institution;
        document.getElementById('modalCourse').innerText = data.course + ' (' + data.year_of_study + ')';
        document.getElementById('modalPhone').innerText = data.phone || 'N/A';
        document.getElementById('modalStatus').innerText = data.status;
        document.getElementById('modalInterests').innerText = data.interests || 'No interests specified.';
        document.getElementById('detailModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('detailModal').style.display = 'none';
    }

    window.onclick = function (event) {
        let modal = document.getElementById('detailModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>

</body>

</html>