<?php
/**
 * DanaHibah™ - E2E Testing Script
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db_connect.php';

$base_url = 'http://localhost/danahibah';
$cookie_jar = __DIR__ . '/cookie.txt';
if (file_exists($cookie_jar)) unlink($cookie_jar);

$results = [];
$total_tests = 0;
$passed_tests = 0;

function run_test($name, $callback) {
    global $results, $total_tests, $passed_tests;
    $total_tests++;
    try {
        $res = $callback();
        if ($res === true) {
            $results[] = "✅ PASS | $name";
            $passed_tests++;
        } else {
            $results[] = "❌ FAIL | $name\n   Reason: " . (is_string($res) ? $res : 'Unknown');
        }
    } catch (Exception $e) {
        $results[] = "❌ FAIL | $name\n   Exception: " . $e->getMessage();
    }
}

function http_request($url, $method = 'GET', $data = []) {
    global $cookie_jar;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_HEADER, true);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }

    $response = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ['code' => $http_code, 'header' => $header, 'body' => $body];
}

function extract_csrf($html) {
    if (preg_match('/name="csrf_token"\s+value="([^"]+)"/', $html, $matches)) {
        return $matches[1];
    }
    return '';
}

echo "Starting E2E Tests...\n\n";

// 1. Valid Login Test
run_test("Authentication - Valid Login", function() use ($base_url) {
    $res = http_request("$base_url/login.php");
    $csrf = extract_csrf($res['body']);
    $res2 = http_request("$base_url/login.php", 'POST', [
        'csrf_token' => $csrf,
        'username' => 'admin',
        'password' => 'Admin@123'
    ]);
    if ($res2['code'] == 302 && strpos($res2['header'], 'index.php') !== false) {
        // Follow redirect to index
        http_request("$base_url/index.php");
        return true;
    }
    return "Did not redirect to dashboard. Code: " . $res2['code'];
});

// 2. Add Branch Test
$branch_id = null;
run_test("Branches - Add Branch", function() use ($base_url, &$branch_id) {
    $res = http_request("$base_url/modules/branches/index.php");
    $csrf = extract_csrf($res['body']);
    $res2 = http_request("$base_url/modules/branches/index.php", 'POST', [
        'csrf_token' => $csrf,
        'action' => 'add',
        'name' => 'Test Branch E2E',
        'code' => 'TEST-001',
        'type' => 'masjid',
        'status' => 'active'
    ]);
    if ($res2['code'] == 302) {
        require __DIR__ . '/../includes/config.php';
        require __DIR__ . '/../includes/db_connect.php';
        $res_db = mysqli_query($conn, "SELECT id FROM branches WHERE code='TEST-001' ORDER BY id DESC LIMIT 1");
        if ($row = mysqli_fetch_assoc($res_db)) {
            $branch_id = $row['id'];
            return true;
        }
    }
    return "Branch not created in DB. Body: \n" . substr($res2['body'], 0, 500);
});

// 3. Edit Branch Test
run_test("Branches - Edit Branch", function() use ($base_url, &$branch_id) {
    if (!$branch_id) return "Skipped - No branch ID";
    $res = http_request("$base_url/modules/branches/edit.php?id=$branch_id");
    $csrf = extract_csrf($res['body']);
    $res2 = http_request("$base_url/modules/branches/edit.php?id=$branch_id", 'POST', [
        'csrf_token' => $csrf,
        'name' => 'Test Branch E2E Updated',
        'status' => 'active'
    ]);
    if ($res2['code'] == 302) return true;
    return "Failed to edit branch.";
});

// 4. Toggle Status Branch
run_test("Branches - Toggle Status", function() use ($base_url, &$branch_id) {
    if (!$branch_id) return "Skipped - No branch ID";
    $res = http_request("$base_url/modules/branches/index.php");
    $html = $res['body'];
    // Need CSRF from URL
    if (preg_match('/toggle_status\.php\?id='.$branch_id.'&amp;csrf=([^"]+)/', $html, $matches)) {
        $csrf_get = $matches[1];
        $res2 = http_request("$base_url/modules/branches/toggle_status.php?id=$branch_id&csrf=$csrf_get");
        return $res2['code'] == 302 ? true : "Toggle failed.";
    }
    return "Could not find toggle URL in HTML.";
});

// 5. Add Device
$device_id = null;
run_test("Devices - Register Device", function() use ($base_url, &$branch_id, &$device_id) {
    if (!$branch_id) return "Skipped - No branch ID";
    $res = http_request("$base_url/modules/devices/index.php");
    $csrf = extract_csrf($res['body']);
    $res2 = http_request("$base_url/modules/devices/index.php", 'POST', [
        'csrf_token' => $csrf,
        'action' => 'add',
        'branch_id' => $branch_id,
        'serial_no' => 'SN-E2E-123',
        'type' => 'hybrid'
    ]);
    if ($res2['code'] == 302) {
        global $conn;
        $res_db = mysqli_query($conn, "SELECT id FROM devices WHERE serial_no='SN-E2E-123' ORDER BY id DESC LIMIT 1");
        if ($row = mysqli_fetch_assoc($res_db)) {
            $device_id = $row['id'];
            return true;
        }
    }
    return "Device not created.";
});

// 6. Add Collection
run_test("Collections - Add Collection", function() use ($base_url, &$branch_id) {
    if (!$branch_id) return "Skipped - No branch ID";
    $res = http_request("$base_url/modules/collections/add.php");
    $csrf = extract_csrf($res['body']);
    $res2 = http_request("$base_url/modules/collections/add.php", 'POST', [
        'csrf_token' => $csrf,
        'branch_id' => $branch_id,
        'amount' => 50.00,
        'channel' => 'cash',
        'donor_name' => 'E2E Donor'
    ]);
    if ($res2['code'] == 302) return true;
    return "Failed to add collection.";
});

// 7. Access Reports
run_test("Reports - View Reports", function() use ($base_url) {
    $res = http_request("$base_url/modules/reports/index.php");
    return $res['code'] == 200 ? true : "HTTP ".$res['code'];
});

// 8. Access Audit Trail
run_test("Audit - View Audit Logs", function() use ($base_url) {
    $res = http_request("$base_url/modules/audit/index.php");
    return $res['code'] == 200 ? true : "HTTP ".$res['code'];
});

// Output Results
echo implode("\n", $results) . "\n\n";
echo "Summary: $passed_tests / $total_tests Passed.\n";
