<?php 
session_start();
include '../config.php';

if (empty($_SESSION[WP.'checklogin']))  {
    $_SESSION['message']='You are not autherlize';
    header("location:{$base_url}/login.php");
    exit();
}

$query = mysqli_query($conn, "SELECT * FROM user WHERE id");
$user = mysqli_fetch_assoc($query);

$query_audio = mysqli_query($conn, "SELECT * FROM audio WHERE user_id");
$row = mysqli_num_rows($query_audio); 

$kmeans_json = file_get_contents("http://localhost:5000/");
$kmeans_summary = $kmeans_json ? json_decode($kmeans_json, true) : [];

$elbow_json = file_get_contents("http://localhost:5000/elbow");
$elbow_data = $elbow_json ? json_decode($elbow_json, true) : [];

$k_values = json_encode($elbow_data['k']);
$inertias = json_encode($elbow_data['inertia']);

$cluster_labels = [];
$cluster_values = [];
foreach ($kmeans_summary as $item) {
    $cluster_labels[] = $item['cluster'];
    $cluster_values[] = $item['count'];
}

$cluster_labels = json_encode($cluster_labels);
$cluster_values = json_encode($cluster_values);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Tables - SB Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<?php include 'nav.php'?>
<div id="layoutSidenav">
    <?php include 'side_nav.php'?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Tables</h1>
                <div class="card mb-4">
                    <div class="card-body">
                        DataTables demo with clustering and Elbow Method chart.
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        DataTable Example
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>ID Audio</th>
                                    <th>User ID</th>
                                    <th>User Name</th>
                                    <th>Description</th>
                                    <th>Audio</th>
                                    <th>ลบ</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if ($row > 0): ?>
                                <?php while ($User = mysqli_fetch_assoc($query)): ?>
                                    <?php while ($User_audio = mysqli_fetch_assoc($query_audio)): ?>
                                        <tr>
                                            <td><small class="text-muted"><?php echo nl2br($User_audio['id']);?></small></td>
                                            <td><small class="text-muted"><?php echo ($User_audio['user_id']);?></small></td>
                                            <td><small class="text-muted"><?php echo ($User['username']);?></small></td>
                                            <td><small class="text-muted"><?php echo ($User_audio['description_audio']);?></small></td>
                                            <td>
                                                <?php if (!empty($User_audio['audio_url'])): ?>
                                                    <audio controls>
                                                        <source src="<?php echo $base_url; ?>/upload_audio/<?php echo ($User_audio['audio_url']); ?>">
                                                    </audio>
                                                <?php else: ?>
                                                    <span>No Audio</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="../delete_audio_admin.php?id=<?php echo $User_audio['id']; ?>" 
                                                   onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบเพลงนี้?');" 
                                                   class="btn btn-danger btn-sm">
                                                    ลบ
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">No records found</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>

                        <!-- Cluster Chart -->
                        <div class="card mb-4">
                            <div class="card-header"><i class="fas fa-chart-bar me-1"></i> จำนวนคนในแต่ละคลัสเตอร์</div>
                            <div class="card-body">
                                <canvas id="clusterChart" width="100%" height="40"></canvas>
                            </div>
                        </div>

                        <!-- Elbow Chart -->
                        <div class="card mb-4">
                            <div class="card-header"><i class="fas fa-chart-line me-1"></i> Elbow Method (Inertia vs K)</div>
                            <div class="card-body">
                                <canvas id="elbowChart" width="100%" height="40"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; Your Website 2023</div>
                    <div>
                        <a href="#">Privacy Policy</a>
                        &middot;
                        <a href="#">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
<div class="card mb-4">
    <div class="card-header"><i class="fas fa-table me-1"></i>สรุปข้อมูลแต่ละคลัสเตอร์</div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Cluster</th>
                    <th>จำนวนคน</th>
                    <th>อายุเฉลี่ย</th>
                    <th>ช่วงอายุ</th>
                    <th>แนวเพลงยอดนิยม</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kmeans_summary as $item): ?>
                    <tr>
                        <td><?php echo $item['cluster']; ?></td>
                        <td><?php echo $item['count']; ?></td>
                        <td><?php echo $item['age_avg']; ?></td>
                        <td><?php echo $item['age_min'] . " - " . $item['age_max']; ?></td>
                        <td><?php echo $item['top_genre']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const clusterLabels = <?php echo $cluster_labels; ?>;
    const clusterValues = <?php echo $cluster_values; ?>;
    const ctx = document.getElementById('clusterChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: clusterLabels,
            datasets: [{
                label: 'จำนวนคนในแต่ละคลัสเตอร์',
                data: clusterValues,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#4e73df',
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 5
                    }
                }
            }
        }
    });

    // Elbow Chart
    const kValues = <?php echo $k_values; ?>;
    const inertias = <?php echo $inertias; ?>;
    const ctxElbow = document.getElementById('elbowChart').getContext('2d');
    new Chart(ctxElbow, {
        type: 'line',
        data: {
            labels: kValues,
            datasets: [{
                label: 'Inertia (SSE)',
                data: inertias,
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                pointBackgroundColor: 'red',
                tension: 0.2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: { display: true, text: 'จำนวนคลัสเตอร์ (K)' }
                },
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Inertia (SSE)' }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Elbow Method - เลือก K ที่เหมาะสม',
                    font: { size: 18 }
                }
            }
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>
</body>
</html>
