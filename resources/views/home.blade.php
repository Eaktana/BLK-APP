<!DOCTYPE html>
<html lang="th" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจัดการพนักงานขับรถ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: "Sarabun", sans-serif;
            min-height: 100vh;
            background: radial-gradient(circle at 20% 30%, rgba(59, 130, 246, .15), transparent 70%),
                        radial-gradient(circle at 80% 70%, rgba(168, 85, 247, .15), transparent 70%),
                        #0d1117;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .menu-card {
            background: #111827;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
            padding: 2.5rem 2rem;
            text-align: center;
            max-width: 550px;
            width: 100%;
        }

        .menu-btn {
            width: 100%;
            padding: 1rem;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .btn-blue {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border: none;
        }
        .btn-blue:hover {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
        }

        .btn-yellow {
            background: linear-gradient(135deg, #eab308, #facc15);
            border: none;
            color: #222;
        }
        .btn-yellow:hover {
            background: linear-gradient(135deg, #ca8a04, #eab308);
            color: #111;
        }
    </style>
</head>

<body>

    <div class="menu-card">

        <div class="mb-4">
            <h2 class="fw-bold text-white">
                ระบบจัดการพนักงานขับรถ
            </h2>
            <p class="text-secondary mb-1">เลือกการทำงานที่ต้องการ</p>
        </div>

        <div class="d-grid gap-3 mt-4">

            <!-- ปุ่มลงทะเบียน -->
            <a href="{{ route('register.form') }}" class="btn btn-blue menu-btn">
                <i class="bi bi-person-plus-fill me-2"></i>
                ลงทะเบียนพนักงานขับรถใหม่
            </a>

            <!-- ปุ่มแก้ไข -->
            <a href="/drivers/1/edit" class="btn btn-yellow menu-btn">
                <i class="bi bi-pencil-square me-2"></i>
                แก้ไขข้อมูลพนักงานขับรถ
            </a>

        </div>

    </div>

</body>
</html>
