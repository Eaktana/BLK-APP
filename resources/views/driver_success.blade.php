<!DOCTYPE html>
<html lang="th" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลงทะเบียนสำเร็จ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: radial-gradient(circle at 20% 30%, rgba(59,130,246,0.15), transparent 70%),
                        radial-gradient(circle at 80% 70%, rgba(168,85,247,0.15), transparent 70%),
                        #0d1117;
        }

        .success-card {
            max-width: 500px;
            background: #111827;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            box-shadow: 0 0 25px rgba(0,0,0,0.3);
        }

        .success-icon {
            font-size: 4rem;
            color: #22c55e;
            margin-bottom: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
        }

        .btn-outline-light:hover {
            background: rgba(255,255,255,0.1);
        }
    </style>
</head>

<body>
    <div class="success-card">

        <!-- Icon -->
        <div class="success-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>

        <!-- Title -->
        <h3 class="fw-bold text-white mb-2">ลงทะเบียนสำเร็จ!</h3>

        <!-- Description -->
        <p class="text-secondary mb-4">
            ระบบได้บันทึกข้อมูลพนักงานขับรถเรียบร้อยแล้ว<br>
            เจ้าหน้าที่จะดำเนินการตรวจสอบข้อมูลในภายหลัง
        </p>

        <!-- Buttons -->
        <div class="d-flex justify-content-center gap-2">
            <a href="{{ url()->previous() }}" class="btn btn-outline-light px-4 py-2">
                <i class="bi bi-arrow-left-short me-1"></i> กลับไปก่อนหน้า
            </a>

            <a href="/" class="btn btn-primary px-4 py-2">
                <i class="bi bi-house-door-fill me-1"></i> หน้าแรก
            </a>
        </div>
    </div>
</body>
</html>
