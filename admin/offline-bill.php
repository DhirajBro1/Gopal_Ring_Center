<?php
session_start();
include(__DIR__ . '/../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: login");
    exit;
}

// Handle POST request - store data in session for language switching
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['offline_bill_data'] = array(
        'user_name' => isset($_POST['user_name']) ? htmlspecialchars($_POST['user_name']) : '',
        'phone' => isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '',
        'address' => isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '',
        'products' => isset($_POST['products']) ? $_POST['products'] : array()
        , 'manual_items' => isset($_POST['manual_items']) ? $_POST['manual_items'] : array()
    );
}

// Check if we have bill data (either from POST or from session)
if (!isset($_SESSION['offline_bill_data'])) {
    header("Location: offline-bill-create");
    exit;
}

$bill_data = $_SESSION['offline_bill_data'];

// Get customer information
$user_name = $bill_data['user_name'];
$phone = $bill_data['phone'];
$address = $bill_data['address'];

// Process selected items
$items = array();
$final_total = 0;

if (isset($bill_data['products']) && is_array($bill_data['products'])) {
    foreach ($bill_data['products'] as $product_item) {
        $qty = isset($product_item['quantity']) ? intval($product_item['quantity']) : 0;
        if ($qty > 0) {
            $product_id = isset($product_item['id']) ? intval($product_item['id']) : 0;
            $price = isset($product_item['price']) ? floatval($product_item['price']) : 0;
            
            // Fetch product name from database
            $stmt = $conn->prepare("SELECT name FROM products WHERE id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            $stmt->close();
            
            if ($product) {
                $line_total = $qty * $price;
                $items[] = array(
                    'id' => $product_id,
                    'name' => $product['name'],
                    'quantity' => $qty,
                    'price' => $price,
                    'line_total' => $line_total,
                    'source' => 'database'
                );
                $final_total += $line_total;
            }
        }
    }
}

if (isset($bill_data['manual_items']) && is_array($bill_data['manual_items'])) {
    foreach ($bill_data['manual_items'] as $manual_item) {
        $name = isset($manual_item['name']) ? trim($manual_item['name']) : '';
        $qty = isset($manual_item['quantity']) ? intval($manual_item['quantity']) : 0;
        $price = isset($manual_item['price']) ? floatval($manual_item['price']) : 0;

        if ($name !== '' && $qty > 0) {
            $line_total = $qty * $price;
            $items[] = array(
                'id' => 0,
                'name' => $name,
                'quantity' => $qty,
                'price' => $price,
                'line_total' => $line_total,
                'source' => 'manual'
            );
            $final_total += $line_total;
        }
    }
}

// Generate a unique bill reference (not stored, just for display)
$bill_ref = 'OL-' . date('YmdHis');

// Language and formatting functions (same as main bill.php)
$lang = isset($_GET['lang']) && $_GET['lang'] === 'en' ? 'en' : 'ne';
$is_download = isset($_GET['download']) && $_GET['download'] === '1';
$business_address_en = 'Urlabari-5, Morang';

function to_devanagari_digits($value) {
    $western = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    $devanagari = array('०', '१', '२', '३', '४', '५', '६', '७', '८', '९');
    return str_replace($western, $devanagari, (string)$value);
}

function t($ne, $en) {
    global $lang;
    return $lang === 'ne' ? $ne : $en;
}

function fmt($value) {
    global $lang;
    return $lang === 'ne' ? to_devanagari_digits($value) : $value;
}

function bill_url_with_lang($lang) {
    return 'offline-bill.php?lang=' . $lang;
}

// Business details
$business_address = 'उर्लाबारी-५, मोरङ';
$business_phone_1 = '9817319154';
$business_phone_2 = '9842188074';
$business_pan = '615950950';
$business_pan_digits = str_split($business_pan);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../logo.png">
    <title>Offline Bill</title>
    <style>
        body {
            margin: 0;
            padding: 24px;
            background: #f5f5f5;
            font-family: "Noto Sans Devanagari", "Mangal", sans-serif;
        }
        .actions {
            max-width: 900px;
            margin: 0 auto 12px auto;
            display: flex;
            gap: 10px;
        }
        .btn {
            background: #1d4ed8;
            color: #fff;
            border: none;
            padding: 10px 14px;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn.secondary {
            background: #475569;
        }
        .bill {
            background: #fff;
            max-width: 900px;
            margin: 0 auto;
            border: 2px solid #111;
            padding: 16px;
        }
        .head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #111;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }
        .head-right {
            text-align: right;
        }
        .title {
            font-size: 34px;
            font-weight: 700;
            letter-spacing: 1px;
            margin: 0;
        }
        .sub {
            margin: 4px 0 0 0;
            font-size: 15px;
        }
        .meta {
            margin-bottom: 12px;
            border-bottom: 1px solid #111;
            padding-bottom: 10px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .meta-top {
            margin-bottom: 10px;
            border-bottom: 1px solid #111;
            padding-bottom: 8px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .line {
            font-size: 15px;
        }
        .pan-block-wrap {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .pan-block {
            width: 24px;
            height: 24px;
            border: 1px solid #111;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            line-height: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }
        th, td {
            border: 1px solid #111;
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }
        th {
            font-weight: 700;
            text-align: center;
        }
        td.center {
            text-align: center;
        }
        td.right {
            text-align: right;
        }
        .total-row td {
            font-weight: 700;
        }
        .foot {
            margin-top: 18px;
            margin-bottom: 50px;
            border-top: 1px solid #111;
            padding-top: 10px;
            text-align: center;
            font-size: 14px;
        }
        .sign-row {
            margin-top: 28px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            font-size: 14px;
            text-align: center;
        }
        .sign-line {
            border-top: 1px dotted #111;
            padding-top: 0;
            margin: 0 auto;
            width: 60%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .sign-signature {
            width: 80px;
            height: 50px;
            object-fit: contain;
            display: block;
            margin-top: -50px;
            margin-bottom: 4px;
        }
        .computer-note {
            margin-top: 14px;
            text-align: center;
            font-size: 12px;
            color: #333;
            font-style: italic;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .actions {
                display: none;
            }
            .bill {
                border: 1px solid #111;
                margin: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="actions">
        <a href="offline-bill-create" class="btn secondary"><?php echo t('Back','Back'); ?></a>
        <button onclick="window.print()" class="btn"><?php echo t('Print Bill','Print Bill'); ?></button>
        <a href="<?php echo bill_url_with_lang('ne'); ?>" class="btn secondary">नेपाली</a>
        <a href="<?php echo bill_url_with_lang('en'); ?>" class="btn">English</a>
    </div>

    <div class="bill">
        <div class="head">
            <div>
                <h1 class="title"><?php echo t('गोपाल रिंग सेन्टर','Gopal Ring Center'); ?></h1>
                <p class="sub"><?php echo htmlspecialchars($lang === 'ne' ? $business_address : $business_address_en); ?></p>
            </div>
            <div class="head-right">
                <div class="line"><strong><?php echo t('मो.','Mo.'); ?></strong> <?php echo htmlspecialchars(fmt($business_phone_1)); ?></div>
                <div class="line"><strong><?php echo t('मो.','Mo.'); ?></strong> <?php echo htmlspecialchars(fmt($business_phone_2)); ?></div>
            </div>
        </div>

        <div class="meta-top">
            <div class="line"><strong><?php echo t('बिल नं:','Bill No:'); ?></strong> <?php echo htmlspecialchars($bill_ref); ?></div>
            <div class="line" style="text-align:right;">
                <strong><?php echo t('मिति:','Date:'); ?></strong> 
                <?php 
                    $current_date = date('Y-m-d');
                    echo htmlspecialchars($current_date);
                ?> AD
            </div>
            <div class="line" style="grid-column: 1 / span 2;">
                <strong><?php echo t('पान नं:','PAN No:'); ?></strong>
                <span class="pan-block-wrap">
                    <?php foreach ($business_pan_digits as $digit) { ?>
                        <span class="pan-block"><?php echo htmlspecialchars(fmt($digit)); ?></span>
                    <?php } ?>
                </span>
            </div>
        </div>

        <div class="meta">
            <div class="line"><strong><?php echo t('नाम:','Name:'); ?></strong> <?php echo htmlspecialchars($user_name); ?></div>
            <div class="line" style="text-align:right;"><strong><?php echo t('अवस्था:','Status:'); ?></strong> <?php echo t('पूरा भएको','Completed'); ?></div>
            <div class="line" style="grid-column: 1 / span 2;"><strong><?php echo t('ठेगाना:','Address:'); ?></strong> <?php echo htmlspecialchars($address ?: t('दिइएको छैन','Not provided')); ?></div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 8%;"><?php echo t('क्र.सं.','S.N.'); ?></th>
                    <th><?php echo t('विवरण','Description'); ?></th>
                    <th style="width: 14%;"><?php echo t('परिमाण','Qty'); ?></th>
                    <th style="width: 14%;"><?php echo t('दर','Rate'); ?></th>
                    <th style="width: 18%;"><?php echo t('रकम','Amount'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($items) > 0) { ?>
                    <?php foreach ($items as $idx => $item) { ?>
                        <tr>
                            <td class="center"><?php echo htmlspecialchars(fmt($idx + 1)); ?></td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td class="center"><?php echo htmlspecialchars(fmt((int)$item['quantity'])); ?></td>
                            <td class="right"><?php echo t('रु','Rs'); ?> <?php echo htmlspecialchars(fmt(number_format((float)$item['price'], 2))); ?></td>
                            <td class="right"><?php echo t('रु','Rs'); ?> <?php echo htmlspecialchars(fmt(number_format((float)$item['line_total'], 2))); ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5" class="center"><?php echo t('कुनै आइटम छैन।','No items.'); ?></td>
                    </tr>
                <?php } ?>
                <tr class="total-row">
                    <td colspan="4" class="right"><?php echo t('जम्मा','Total'); ?></td>
                    <td class="right"><?php echo t('रु','Rs'); ?> <?php echo htmlspecialchars(fmt(number_format((float)$final_total, 2))); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="foot">
            <div><?php echo t('भुलचुक लिनेदिने','To take responsibility for mistakes'); ?></div>
        </div>
        <div class="sign-row">
            <div class="sign-line"><?php echo t('क्रेता','Buyer'); ?></div>
            <div class="sign-line">
                <img src="../public/images/sign_dp_white.png" alt="Dhiraj's Signature" class="sign-signature">
                <div><?php echo t('दर्ता गर्नेः धीरज पण्डित','Entered By: Dhiraj Pandit'); ?></div>
            </div>
            <div class="sign-line">
                <img src="../public/images/sign_gp_white.png" alt="Gopal's Signature" class="sign-signature">
                <div><?php echo t('विक्रेता','Seller'); ?></div>
            </div>
        </div>
        <div class="computer-note"><?php echo t('यो बिल कम्प्युटरबाट तयार गरिएको हो।','This bill is computer generated.'); ?></div>
    </div>
</body>
</html>
