<?php
$host = 'aws-1-us-west-2.pooler.supabase.com';
$port = 5432;
$db = 'postgres';
$user = 'postgres.qxqjgglykvgextltftco';
$pass = 'JOSUENAMEDAVID3021';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    $password = 'admin123!';
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO \"User\" (id, ruc, \"firstName\", \"lastName\", email, password, role, \"updatedAt\") VALUES (gen_random_uuid()::text, '1790011223002', 'David', 'Rodriguez', 'david2626@gmail.com', ?, 'admin', current_timestamp)");
    $stmt->execute([$hashedPassword]);
    
    echo "Admin user seeded!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
