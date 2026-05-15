<?php
session_start();
include("../config/database.php");
include("../layout/header.php");

// 1. Kiểm tra quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('Bạn không có quyền truy cập!'); window.location='/tour_web/index.php';</script>";
    exit();
}

// Điều kiện lọc trạng thái (Thống nhất cho tất cả các query bên dưới)
$st = "(bookings.status = 'Confirmed' OR bookings.status = 'Đã xác nhận' OR bookings.status LIKE '%xác nhận%')";

// 2. CÁC CÂU LỆNH TRUY VẤN (SQL QUERIES)
$sql_total = mysqli_query($conn, "SELECT SUM(total_price) as total_money, COUNT(id) as total_bookings 
                                  FROM bookings 
                                  WHERE $st");
$data_total = mysqli_fetch_assoc($sql_total);
$revenue = $data_total['total_money'] ?? 0;
$count_bookings = $data_total['total_bookings'] ?? 0;

$sql_tour_stats = mysqli_query($conn, "SELECT tours.tour_name, SUM(bookings.total_price) as revenue_per_tour, COUNT(bookings.id) as tickets 
                                       FROM bookings 
                                       JOIN tours ON bookings.tour_id = tours.id 
                                       WHERE $st
                                       GROUP BY tours.id, tours.tour_name 
                                       ORDER BY revenue_per_tour DESC");

$sql_daily = mysqli_query($conn, "SELECT DATE(created_at) as date, SUM(total_price) as daily_revenue, COUNT(id) as daily_orders 
                                  FROM bookings 
                                  WHERE $st 
                                  GROUP BY DATE(created_at) 
                                  ORDER BY date DESC LIMIT 7");

$sql_monthly_detail = mysqli_query($conn, "SELECT MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_price) as revenue, COUNT(id) as orders 
                                           FROM bookings 
                                           WHERE $st 
                                           GROUP BY year, month 
                                           ORDER BY year DESC, month DESC");

$sql_yearly_detail = mysqli_query($conn, "SELECT YEAR(created_at) as year, SUM(total_price) as revenue, COUNT(id) as orders 
                                          FROM bookings 
                                          WHERE $st 
                                          GROUP BY year 
                                          ORDER BY year DESC");
?>

<style>
    :root { --primary-blue: #3b82f6; --emerald: #10b981; --slate-700: #1e293b; --slate-900: #0f172a; }
    body { background-color: var(--slate-900); color: #e2e8f0; }
    
    .revenue-card { 
        background: var(--slate-700); border: 1px solid rgba(255,255,255,0.05); 
        border-radius: 20px; padding: 25px; margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .stat-box {
        background: rgba(255,255,255,0.03); border-radius: 15px;
        padding: 15px; border-left: 4px solid var(--primary-blue);
    }

    .table-revenue { color: #e2e8f0; vertical-align: middle; }
    .table-revenue thead { background: rgba(59, 130, 246, 0.1); }
    .table-revenue th { border: none; padding: 15px; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; }
    .table-revenue td { border-bottom: 1px solid rgba(255,255,255,0.05); padding: 15px; }

    /* Class mới để đổi màu tên tour thành đen */
    .tour-name-black { color: #000000 !important; font-weight: 700; }

    .text-money { color: var(--emerald); font-weight: 700; }
    .badge-count { background: rgba(59, 130, 246, 0.2); color: var(--primary-blue); padding: 5px 12px; border-radius: 10px; font-weight: bold; }

    @media print {
        header, footer, .navbar, .btn-print-action { display: none !important; }
        body { background-color: white !important; color: black !important; }
        .revenue-card { background: white !important; border: 1px solid #ddd !important; box-shadow: none !important; color: black !important; }
        .text-money { color: #059669 !important; }
        .table-revenue td { color: black !important; border-bottom: 1px solid #eee !important; }
    }
</style>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 btn-print-action">
        <div>
            <h3 class="fw-bold mb-0 text-white"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Hệ Thống Báo Cáo Doanh Thu</h3>
        </div>
        <button onclick="window.print()" class="btn btn-outline-light rounded-pill px-4">
            <i class="bi bi-printer me-2"></i> Xuất Báo Cáo
        </button>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="revenue-card h-100 border-start border-4 border-primary">
                <p class="text-muted small text-uppercase mb-1 fw-bold">Tổng doanh thu hệ thống</p>
                <h2 class="display-5 fw-bold text-money"><?= number_format($revenue, 0, ',', '.') ?> <span class="fs-4 text-muted">VNĐ</span></h2>
            </div>
        </div>
        <div class="col-md-6">
            <div class="revenue-card h-100">
                <div class="stat-box mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary">Giá trị trung bình đơn:</span>
                        <span class="fw-bold text-info"><?= $count_bookings > 0 ? number_format($revenue/$count_bookings, 0, ',', '.') : 0 ?> VNĐ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="revenue-card p-0 overflow-hidden mb-4 border-0">
        <div class="p-4 border-bottom border-secondary border-opacity-10 bg-primary bg-opacity-10">
            <h5 class="mb-0 fw-bold"><i class="bi bi-trophy-fill me-2 text-warning"></i>Top Tour Mang Lại Doanh Thu Cao Nhất</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-revenue mb-0">
                <thead>
                    <tr>
                        <th>Tên Tour Du Lịch</th>
                        <th class="text-center">Số vé bán ra</th>
                        <th class="text-end">Doanh thu thu về</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($sql_tour_stats) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($sql_tour_stats)): ?>
                        <tr>
                            <td class="tour-name-black"><?= htmlspecialchars($row['tour_name']) ?></td>
                            <td class="text-center"><span class="badge-count"><?= $row['tickets'] ?> vé</span></td>
                            <td class="text-end text-money"><?= number_format($row['revenue_per_tour'], 0, ',', '.') ?> VNĐ</td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center py-5 text-muted">Chưa có dữ liệu.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-12">
            <div class="revenue-card p-0 overflow-hidden">
                <div class="p-4 border-bottom border-secondary border-opacity-10">
                    <h5 class="mb-0 fw-bold text-info">Chi tiết doanh thu 7 ngày qua</h5>
                </div>
                <table class="table table-revenue mb-0">
                    <thead>
                        <tr>
                            <th>Ngày giao dịch</th>
                            <th class="text-center">Số đơn</th>
                            <th class="text-end">Doanh thu ngày</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($sql_daily)): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($row['date'])) ?></td>
                            <td class="text-center"><?= $row['daily_orders'] ?></td>
                            <td class="text-end text-money text-info"><?= number_format($row['daily_revenue'], 0, ',', '.') ?> VNĐ</td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-7">
            <div class="revenue-card p-0 overflow-hidden h-100">
                <div class="p-4 border-bottom border-secondary border-opacity-10 bg-success bg-opacity-10">
                    <h5 class="mb-0 fw-bold text-success">Lịch Sử Doanh Thu Theo Tháng</h5>
                </div>
                <table class="table table-revenue mb-0">
                    <thead>
                        <tr>
                            <th>Tháng / Năm</th>
                            <th class="text-center">Tổng đơn</th>
                            <th class="text-end">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($m = mysqli_fetch_assoc($sql_monthly_detail)): ?>
                        <tr>
                            <td>Tháng <?= $m['month'] ?> năm <?= $m['year'] ?></td>
                            <td class="text-center"><?= $m['orders'] ?> đơn</td>
                            <td class="text-end text-money text-success"><?= number_format($m['revenue'], 0, ',', '.') ?> VNĐ</td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-5">
            <div class="revenue-card p-0 overflow-hidden h-100">
                <div class="p-4 border-bottom border-secondary border-opacity-10 bg-warning bg-opacity-10">
                    <h5 class="mb-0 fw-bold text-warning">Tổng Kết Năm</h5>
                </div>
                <table class="table table-revenue mb-0">
                    <thead>
                        <tr>
                            <th>Năm</th>
                            <th class="text-center">Số lượng đơn</th>
                            <th class="text-end">Tổng tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($y = mysqli_fetch_assoc($sql_yearly_detail)): ?>
                        <tr>
                            <td class="fw-bold text-info fs-5"><?= $y['year'] ?></td>
                            <td class="text-center"><?= $y['orders'] ?></td>
                            <td class="text-end text-money"><?= number_format($y['revenue'], 0, ',', '.') ?> VNĐ</td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include("../layout/footer.php"); ?>