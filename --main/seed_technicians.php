<?php
/**
 * Seed Script: Populate 3 Technicians and sample assigned tickets
 */
require_once __DIR__ . '/config/database.php';

$pdo = Database::getConnection();

// 1. Seed Technicians into users table
$users = [
    ['admin', '1234', 'สมศักดิ์ หัวหน้าแอดมิน IT (Admin)', 'ฝ่าย IT / เทคโนโลยีสารสนเทศ', '0899999999', 'admin@company.com', 'Admin'],
    ['tech1', 'tech123', 'วิชัย ช่างคอมพิวเตอร์', 'ฝ่าย IT / ซ่อมบำรุงคอมพิวเตอร์', '0821111111', 'wichai@company.com', 'Technician'],
    ['tech2', 'tech123', 'กิตติ ช่างเน็ตเวิร์ก', 'ฝ่าย IT / โครงข่ายและเครือข่าย', '0822222222', 'kitti@company.com', 'Technician'],
    ['tech3', 'tech123', 'ธีระ ช่างอุปกรณ์ทั่วไป', 'ฝ่าย IT / ฮาร์ดแวร์และพริ้นเตอร์', '0823333333', 'theera@company.com', 'Technician'],
    ['user1', 'user123', 'นายสมชาย ใจดี (ผู้ใช้ทั่วไป)', 'ฝ่ายการเงินและบัญชี', '0812345678', 'somchai@company.com', 'User']
];

foreach ($users as $u) {
    $check = $pdo->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
    $check->execute([$u[0]]);
    $existing = $check->fetch();
    if ($existing) {
        $update = $pdo->prepare("UPDATE users SET password = ?, full_name = ?, department = ?, phone = ?, email = ?, role = ? WHERE id = ?");
        $update->execute([$u[1], $u[2], $u[3], $u[4], $u[5], $u[6], $existing['id']]);
    } else {
        $insert = $pdo->prepare("INSERT INTO users (username, password, full_name, department, phone, email, role, duty_status) VALUES (?, ?, ?, ?, ?, ?, ?, 'Available')");
        $insert->execute([$u[0], $u[1], $u[2], $u[3], $u[4], $u[5], $u[6]]);
    }
}

// 2. Seed Sample Tickets assigned to Technicians (including unassigned tickets for claiming)
$tickets = [
    ['JOB-1001', 'สมชาย ใจดี', 'ฝ่ายการเงิน', '0812345678', 'somchai@company.com', 'AST-1001', 'Computer', 'เครื่องเปิดไม่ติด มีไฟกระพริบ', 'High', 'High', 0.00, '6 ชั่วโมง', 'In Progress', 'วิชัย ช่างคอมพิวเตอร์'],
    ['JOB-1002', 'สุภาพร การดี', 'ฝ่ายบุคคล', '0819998888', 'supaporn@company.com', 'AST-1002', 'Computer', 'หน้าจอฟ้า BSOD ขึ้น Error Memory', 'Medium', 'Normal', 0.00, '24 ชั่วโมง', 'Completed', 'วิชัย ช่างคอมพิวเตอร์'],
    ['JOB-1003', 'อนันต์ ขยันยิ่ง', 'ฝ่ายไอที', '0823456789', 'anan@company.com', 'AST-4001', 'Network', 'Wi-Fi ชั้น 2 สัญญาณหลุดบ่อย', 'Critical', 'Urgent', 0.00, '2 ชั่วโมง', 'In Progress', 'กิตติ ช่างเน็ตเวิร์ก'],
    ['JOB-1004', 'วารุณี สุขใจ', 'ฝ่ายการตลาด', '0834567890', 'warunee@company.com', 'AST-2001', 'Printer', 'เครื่องพิมพ์หมึกเลอะ กระดาษติดบ่อย', 'Low', 'Normal', 0.00, '24 ชั่วโมง', 'Completed', 'ธีระ ช่างอุปกรณ์ทั่วไป'],
    ['JOB-1005', 'มานพ ทำงานดี', 'ฝ่ายบัญชี', '0845678901', 'manop@company.com', 'AST-2002', 'Printer', 'พิมพ์ไม่ออก ขึ้น Error E-02 (รอช่างรับงาน)', 'Medium', 'Normal', 1200.00, '24 ชั่วโมง', 'Pending', null],
    ['JOB-1006', 'รุ่งทิพย์ จิตงาม', 'ฝ่ายขาย', '0856789012', 'rungthip@company.com', 'AST-3001', 'Monitor', 'จอภาพไม่แสดงผล ไฟไม่เข้า', 'High', 'High', 850.00, '12 ชั่วโมง', 'Pending', null],
    ['JOB-2302', 'อรอุมา เจริญสุข', 'ฝ่ายจัดซื้อ (ชั้น 3)', '0861112233', 'onuma@company.com', 'AST-4001', 'Network', 'สัญญาณอินเทอร์เน็ตหลุดบ่อย สัญญาณ Wi-Fi มองไม่เห็น ไม่สามารถส่งใบสั่งซื้อได้', 'Critical', 'Urgent', 0.00, '2 ชั่วโมง (Urgent Service)', 'Pending', null],
    ['JOB-9235', 'ณัฐพล ศรีสวัสดิ์', 'ฝ่ายบริหารทั่วไป', '0872223344', 'nattapol@company.com', 'AST-1002', 'Computer', 'เปิดโปรแกรมแล้วเครื่องค้าง มีเสียงพัดลมดังตลอดเวลา และตัวเครื่องร้อนผิดปกติ', 'High', 'High', 0.00, '6 ชั่วโมง (High Priority)', 'Pending', null],
    ['JOB-5740', 'ปรียาภรณ์ ชัยชนะ', 'ฝ่ายทรัพยากรบุคคล (HR)', '0883334455', 'preeyaporn@company.com', 'AST-2001', 'Printer', 'พิมพ์เอกสารแล้วมีคราบหมึกดำเปรอะเป็นแถบยาว และกระดาษติดในถาดดึงบ่อยครั้ง', 'Medium', 'Normal', 0.00, '24 ชั่วโมง (Standard Repair)', 'Pending', null],
    ['JOB-9098', 'เกรียงไกร สดใส', 'ฝ่ายออกแบบและกราฟิก', '0894445566', 'kriangkrai@company.com', 'AST-3001', 'Monitor', 'จอภาพไม่แสดงผล ไฟ LED สีส้มกระพริบ ลองสลับสาย DisplayPort และสาย Power แล้วภาพยังไม่ขึ้น', 'High', 'High', 0.00, '6 ชั่วโมง (High Priority)', 'Pending', null],
    ['JOB-1159', 'สุชานันท์ มีทรัพย์', 'ฝ่ายต้อนรับและลูกค้าสัมพันธ์', '0815556677', 'suchanan@company.com', 'AST-1003', 'Computer', 'เปิดเครื่องแล้วบูตเข้า Windows ช้ามาก บางครั้งเปิดไม่ติดค้างอยู่ที่หน้าโลโก้ HP', 'Medium', 'Normal', 850.00, '24 ชั่วโมง (Standard Repair)', 'Pending', null],
];

foreach ($tickets as $t) {
    $check = $pdo->prepare("SELECT id FROM repair_tickets WHERE ticket_id = ? LIMIT 1");
    $check->execute([$t[0]]);
    $existing = $check->fetch();
    if ($existing) {
        $update = $pdo->prepare("UPDATE repair_tickets SET status = ?, assigned_technician = ? WHERE id = ?");
        $update->execute([$t[12], $t[13], $existing['id']]);
    } else {
        $insert = $pdo->prepare("INSERT INTO repair_tickets 
            (ticket_id, user_name, department, phone, user_email, asset_id, device_type, issue_description, issue_severity, priority, estimated_cost, estimated_completion_time, status, assigned_technician) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert->execute($t);
    }
}

echo "Seed Technicians & Tickets completed successfully!\n";

