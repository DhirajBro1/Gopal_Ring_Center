<?php
session_start();
include(__DIR__ . '/../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: login");
    exit;
}

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($order_id <= 0) {
    http_response_code(400);
    echo "Invalid order ID.";
    exit;
}

$order_stmt = $conn->prepare(
    "SELECT o.id AS order_id, o.total_amount, o.created_at, o.status,
            u.name AS user_name, u.phone, u.address
     FROM orders o
     JOIN users u ON o.user_id = u.id
     WHERE o.id = ?"
);
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();
$order = $order_result->fetch_assoc();
$order_stmt->close();

if (!$order) {
    http_response_code(404);
    echo "Order not found.";
    exit;
}

$items_stmt = $conn->prepare(
    "SELECT p.name, oi.quantity, oi.price
     FROM order_items oi
     JOIN products p ON p.id = oi.product_id
     WHERE oi.order_id = ?"
);
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$items_result = $items_stmt->get_result();

$items = array();
$computed_total = 0;
while ($row = $items_result->fetch_assoc()) {
    $row_total = $row['quantity'] * $row['price'];
    $row['line_total'] = $row_total;
    $computed_total += $row_total;
    $items[] = $row;
}
$items_stmt->close();

$final_total = $computed_total > 0 ? $computed_total : $order['total_amount'];

function to_devanagari_digits($value) {
    $western = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    $devanagari = array('०', '१', '२', '३', '४', '५', '६', '७', '८', '९');
    return str_replace($western, $devanagari, (string)$value);
}

// Business details from printed bill format
$business_address = 'उर्लाबारी-५, मोरङ';
$business_phone_1 = '9817319154';
$business_phone_2 = '9842188074';
$business_pan = '615950950';
$business_pan_digits = str_split($business_pan);

// Language: 'ne' for Nepali (default), 'en' for English
$lang = isset($_GET['lang']) && $_GET['lang'] === 'en' ? 'en' : 'ne';
$business_address_en = 'Urlabari-5, Morang';
function t($ne, $en) {
    global $lang;
    return $lang === 'ne' ? $ne : $en;
}
function fmt($value) {
    global $lang;
    return $lang === 'ne' ? to_devanagari_digits($value) : $value;
}
// Helper for building current bill URL with lang
function bill_url_with_lang($lang) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    return 'bill?id=' . $id . '&lang=' . $lang;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../logo.png">
    <title>Bill #<?php echo $order['order_id']; ?></title>
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
        <a href="orders" class="btn secondary"><?php echo t('Back to Orders','Back to Orders'); ?></a>
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
            <div class="line"><strong><?php echo t('बिल नं:','Bill No:'); ?></strong> <?php echo htmlspecialchars(fmt(str_pad($order['order_id'], 4, '0', STR_PAD_LEFT))); ?></div>
            <div class="line" style="text-align:right;">
                <strong><?php echo t('मिति:','Date:'); ?></strong> 
                <?php 
                    $ad_date = new DateTime($order['created_at']);
                    $ad_year = $ad_date->format('Y');
                    $ad_month = $ad_date->format('m');
                    $ad_day = $ad_date->format('d');
                    echo htmlspecialchars($ad_year . '-' . $ad_month . '-' . $ad_day);
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
            <div class="line"><strong><?php echo t('नाम:','Name:'); ?></strong> <?php echo htmlspecialchars($order['user_name']); ?></div>
            <div class="line" style="text-align:right;"><strong><?php echo t('अवस्था:','Status:'); ?></strong> <?php echo htmlspecialchars($order['status']); ?></div>
            <div class="line" style="grid-column: 1 / span 2;"><strong><?php echo t('ठेगाना:','Address:'); ?></strong> <?php echo htmlspecialchars($order['address']); ?></div>
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
                        <td colspan="5" class="center"><?php echo t('यस अर्डरको लागि कुनै आइटम फेला परेन।','No line items found for this order.'); ?></td>
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
        <div class="sign-row" style="grid-template-columns: repeat(3, 1fr);">
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
