<?php
/**
 * UI Element: Monthly Expense Statistics Chart (Section 13 & 23)
 */
$monthlyStats = $monthlyStats ?? [
    ['month' => 'Jan', 'amount' => 3200000],
    ['month' => 'Feb', 'amount' => 2800000],
    ['month' => 'Mar', 'amount' => 4100000],
    ['month' => 'Apr', 'amount' => 3500000],
    ['month' => 'Mei', 'amount' => 5200000],
    ['month' => 'Jun', 'amount' => 4800000],
    ['month' => 'Jul', 'amount' => 3900000],
    ['month' => 'Agu', 'amount' => 4400000],
    ['month' => 'Sep', 'amount' => 3650000],
];

$maxVal = max(array_column($monthlyStats, 'amount')) ?: 1;
// Ceiling to nice round number
$ceiling = ceil($maxVal / 1000000) * 1000000;

// Calculate SVG coordinates for smooth line
$chartWidth = 560;
$chartHeight = 180;
$paddingX = 35;
$paddingY = 25;

$usableWidth = $chartWidth - ($paddingX * 2);
$usableHeight = $chartHeight - ($paddingY * 2);

$points = [];
$count = count($monthlyStats);
foreach ($monthlyStats as $i => $stat) {
    $x = $paddingX + ($count > 1 ? ($i / ($count - 1)) * $usableWidth : $usableWidth / 2);
    $y = $chartHeight - $paddingY - (($stat['amount'] / $ceiling) * $usableHeight);
    $points[] = ['x' => round($x, 1), 'y' => round($y, 1), 'month' => $stat['month'], 'amount' => $stat['amount']];
}

// Generate smooth Bezier curve SVG path
$pathD = '';
$areaD = '';
if (!empty($points)) {
    $pathD = "M {$points[0]['x']} {$points[0]['y']}";
    for ($i = 0; $i < count($points) - 1; $i++) {
        $p0 = ($i > 0) ? $points[$i - 1] : $points[$i];
        $p1 = $points[$i];
        $p2 = $points[$i + 1];
        $p3 = ($i + 2 < count($points)) ? $points[$i + 2] : $p2;

        $cp1x = $p1['x'] + ($p2['x'] - $p0['x']) / 6;
        $cp1y = $p1['y'] + ($p2['y'] - $p0['y']) / 6;
        $cp2x = $p2['x'] - ($p3['x'] - $p1['x']) / 6;
        $cp2y = $p2['y'] - ($p3['y'] - $p1['y']) / 6;

        $pathD .= " C " . round($cp1x, 1) . " " . round($cp1y, 1) . ", " . round($cp2x, 1) . " " . round($cp2y, 1) . ", {$p2['x']} {$p2['y']}";
    }

    $lastX = $points[count($points) - 1]['x'];
    $firstX = $points[0]['x'];
    $bottomY = $chartHeight - $paddingY;
    $areaD = $pathD . " L {$lastX} {$bottomY} L {$firstX} {$bottomY} Z";
}
?>

<!-- Statistics Card Container -->
<div class="bg-white border border-[#E8EAED] rounded-xl p-5 sm:p-6 shadow-sm flex flex-col justify-between">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
        <div>
            <h3 class="text-xs font-medium text-[#8A8F98] uppercase tracking-wider">
                Statistik Pengeluaran
            </h3>
            <span class="text-base font-bold text-[#2B2B2B]">Pengeluaran Bulanan</span>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-[#F5F6F8] text-[#2B2B2B] border border-[#E8EAED]">
                <span class="w-2 h-2 rounded-full bg-[#2FA89D]"></span>
                Tahun 2026
            </span>
        </div>
    </div>

    <!-- Chart Container with SVG Smooth Line -->
    <div class="relative w-full overflow-hidden my-2">
        <!-- Tooltip Element -->
        <div id="chart-tooltip" class="absolute z-20 pointer-events-none opacity-0 transition-opacity bg-[#171717] text-white text-[11px] px-2.5 py-1 rounded-md shadow-md -translate-x-1/2 -translate-y-full mb-2">
            <span id="tooltip-text"></span>
        </div>

        <svg class="w-full h-48 sm:h-56" viewBox="0 0 <?= $chartWidth ?> <?= $chartHeight ?>" preserveAspectRatio="none">
            <defs>
                <!-- Teal Area Gradient -->
                <linearGradient id="tealGradient" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#2FA89D" stop-opacity="0.25"/>
                    <stop offset="100%" stop-color="#2FA89D" stop-opacity="0.0"/>
                </linearGradient>
            </defs>

            <!-- Light Horizontal Grid Lines -->
            <?php for ($g = 1; $g <= 3; $g++): ?>
                <?php $gridY = $chartHeight - $paddingY - (($g / 3) * $usableHeight); ?>
                <line 
                    x1="<?= $paddingX ?>" 
                    y1="<?= $gridY ?>" 
                    x2="<?= $chartWidth - $paddingX ?>" 
                    y2="<?= $gridY ?>" 
                    stroke="#E8EAED" 
                    stroke-width="1" 
                    stroke-dasharray="4 4"
                />
            <?php endfor; ?>

            <!-- Baseline -->
            <line 
                x1="<?= $paddingX ?>" 
                y1="<?= $chartHeight - $paddingY ?>" 
                x2="<?= $chartWidth - $paddingX ?>" 
                y2="<?= $chartHeight - $paddingY ?>" 
                stroke="#E8EAED" 
                stroke-width="1"
            />

            <!-- Filled Area Under Curve -->
            <path d="<?= $areaD ?>" fill="url(#tealGradient)" />

            <!-- Smooth Teal Line -->
            <path 
                d="<?= $pathD ?>" 
                fill="none" 
                stroke="#2FA89D" 
                stroke-width="2.5" 
                stroke-linecap="round" 
                stroke-linejoin="round"
            />

            <!-- Data Points & Hover Targets -->
            <?php foreach ($points as $p): ?>
                <circle 
                    cx="<?= $p['x'] ?>" 
                    cy="<?= $p['y'] ?>" 
                    r="4" 
                    fill="#FFFFFF" 
                    stroke="#2FA89D" 
                    stroke-width="2.5" 
                    class="cursor-pointer transition-all hover:scale-150"
                    data-month="<?= e($p['month']) ?>"
                    data-amount="<?= formatRupiah($p['amount']) ?>"
                    onmouseenter="showChartTooltip(event, '<?= e($p['month']) ?>: <?= formatRupiah($p['amount']) ?>')"
                    onmouseleave="hideChartTooltip()"
                />
            <?php endforeach; ?>
        </svg>

        <!-- X-Axis Month Labels -->
        <div class="flex justify-between items-center px-4 pt-1 text-[11px] text-[#8A8F98] font-medium">
            <?php foreach ($monthlyStats as $stat): ?>
                <span><?= e($stat['month']) ?></span>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bottom summary row -->
    <div class="mt-4 pt-3 border-t border-[#E8EAED] grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs">
        <div>
            <span class="text-[10px] text-[#8A8F98] block uppercase">Bulan Ini (Sep)</span>
            <span class="font-bold text-[#2B2B2B]"><?= formatRupiah(3650000) ?></span>
        </div>
        <div>
            <span class="text-[10px] text-[#8A8F98] block uppercase">Rata-rata Bulanan</span>
            <span class="font-bold text-[#2B2B2B]"><?= formatRupiah(3950000) ?></span>
        </div>
        <div class="col-span-2 sm:col-span-1 text-left sm:text-right">
            <span class="text-[10px] text-[#8A8F98] block uppercase">Tren Pengeluaran</span>
            <span class="font-bold text-[#2FA89D] inline-flex items-center gap-0.5">
                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                    <polyline points="17 6 23 6 23 12"></polyline>
                </svg>
                Hemat 17%
            </span>
        </div>
    </div>

</div>

<script>
function showChartTooltip(e, text) {
    const tooltip = document.getElementById('chart-tooltip');
    const label = document.getElementById('tooltip-text');
    if (!tooltip || !label) return;
    
    label.innerText = text;
    const rect = e.target.getBoundingClientRect();
    const parentRect = tooltip.parentElement.getBoundingClientRect();
    
    tooltip.style.left = (rect.left - parentRect.left + (rect.width / 2)) + 'px';
    tooltip.style.top = (rect.top - parentRect.top) + 'px';
    tooltip.style.opacity = '1';
}

function hideChartTooltip() {
    const tooltip = document.getElementById('chart-tooltip');
    if (tooltip) tooltip.style.opacity = '0';
}
</script>
