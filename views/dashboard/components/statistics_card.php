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

$statsYear = $statsYear ?? (int)date('Y');
$lastStat = !empty($monthlyStats) ? end($monthlyStats) : ['month' => 'Sep', 'amount' => 0];
$statsCurrentMonthLabel = $statsCurrentMonthLabel ?? $lastStat['month'];
$statsCurrentAmount = $statsCurrentAmount ?? (float)$lastStat['amount'];
$nonZeroStats = array_values(array_filter(array_column($monthlyStats, 'amount'), fn($v) => $v > 0));
$statsAverageAmount = $statsAverageAmount ?? (!empty($nonZeroStats) ? (array_sum($nonZeroStats) / count($nonZeroStats)) : 0);
$statsTrendText = $statsTrendText ?? 'Hemat 17%';
$statsTrendPositive = $statsTrendPositive ?? true;

$amounts = array_column($monthlyStats, 'amount');
$maxVal = !empty($amounts) ? (float)max($amounts) : 0.0;
$hasExpenseData = $maxVal > 0;

if ($maxVal <= 0) {
    $ceiling = 1000000;
} elseif ($maxVal <= 100000) {
    $ceiling = (int)(ceil($maxVal / 50000) * 50000);
} elseif ($maxVal <= 1000000) {
    $ceiling = (int)(ceil($maxVal / 250000) * 250000);
} else {
    $ceiling = (int)(ceil($maxVal / 1000000) * 1000000);
}

// Normalized SVG viewBox (0..600 x 0..200) paired with non-scaling-stroke and CSS % circular dots
$chartWidth = 600;
$chartHeight = 200;

$points = [];
$count = count($monthlyStats);
foreach ($monthlyStats as $i => $stat) {
    $ratioX = $count > 1 ? ($i / ($count - 1)) : 0.5;
    $amt = (float)($stat['amount'] ?? 0);
    $ratioY = 1.0 - min(1.0, max(0.0, $amt / $ceiling));

    $points[] = [
        'x' => round($ratioX * $chartWidth, 2),
        'y' => round($ratioY * $chartHeight, 2),
        'pctX' => round($ratioX * 100, 3),
        'pctY' => round($ratioY * 100, 3),
        'month' => (string)$stat['month'],
        'amount' => $amt,
    ];
}

// Monotone Cubic Hermite (Fritsch-Carlson) interpolation to prevent curve overshoot below baseline
$slopes = [];
for ($i = 0; $i < $count - 1; $i++) {
    $dx = $points[$i + 1]['x'] - $points[$i]['x'];
    $dy = $points[$i + 1]['y'] - $points[$i]['y'];
    $slopes[$i] = $dx != 0.0 ? ($dy / $dx) : 0.0;
}

$tangents = array_fill(0, $count, 0.0);
if ($count > 1) {
    $tangents[0] = $slopes[0];
    $tangents[$count - 1] = $slopes[$count - 2];
    for ($i = 1; $i < $count - 1; $i++) {
        if ($slopes[$i - 1] * $slopes[$i] <= 0) {
            $tangents[$i] = 0.0;
        } else {
            $tangents[$i] = ($slopes[$i - 1] + $slopes[$i]) / 2.0;
        }
    }
    for ($i = 0; $i < $count - 1; $i++) {
        if (abs($slopes[$i]) < 1e-6) {
            $tangents[$i] = 0.0;
            $tangents[$i + 1] = 0.0;
        } else {
            $alpha = $tangents[$i] / $slopes[$i];
            $beta = $tangents[$i + 1] / $slopes[$i];
            $sq = ($alpha * $alpha) + ($beta * $beta);
            if ($sq > 9.0) {
                $tau = 3.0 / sqrt($sq);
                $tangents[$i] = $tau * $alpha * $slopes[$i];
                $tangents[$i + 1] = $tau * $beta * $slopes[$i];
            }
        }
    }
}

$pathD = '';
$areaD = '';
if (!empty($points)) {
    $pathD = "M {$points[0]['x']} {$points[0]['y']}";
    for ($i = 0; $i < $count - 1; $i++) {
        $p1 = $points[$i];
        $p2 = $points[$i + 1];
        $dx = ($p2['x'] - $p1['x']) / 3.0;

        $cp1x = round($p1['x'] + $dx, 2);
        $cp1y = round(max(0.0, min((float)$chartHeight, $p1['y'] + ($dx * $tangents[$i]))), 2);
        $cp2x = round($p2['x'] - $dx, 2);
        $cp2y = round(max(0.0, min((float)$chartHeight, $p2['y'] - ($dx * $tangents[$i + 1]))), 2);

        $pathD .= " C {$cp1x} {$cp1y}, {$cp2x} {$cp2y}, {$p2['x']} {$p2['y']}";
    }

    $lastX = $points[$count - 1]['x'];
    $firstX = $points[0]['x'];
    $areaD = $pathD . " L {$lastX} {$chartHeight} L {$firstX} {$chartHeight} Z";
}

$yTickTop = formatShortRupiah($ceiling);
$yTickMid = formatShortRupiah((int)round($ceiling / 2));
?>

<!-- Statistics Card Container -->
<div class="bg-white border border-[#E8EAED] rounded-xl p-5 sm:p-6 shadow-sm flex flex-col justify-between flex-1 h-full">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
        <div>
            <h3 class="text-xs font-medium text-[#8A8F98] uppercase tracking-wider">
                Statistik Pengeluaran
            </h3>
            <span class="text-base font-bold text-[#2B2B2B]">Pengeluaran Bulanan</span>
        </div>

        <div class="flex items-center gap-2">
            <?php if (!$hasExpenseData): ?>
                <span class="text-[11px] text-[#8A8F98]">Belum ada pengeluaran <?= (int)$statsYear ?></span>
            <?php endif; ?>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-[#F5F6F8] text-[#2B2B2B] border border-[#E8EAED]">
                <span class="w-2 h-2 rounded-full bg-[#2FA89D]"></span>
                Tahun <?= (int)$statsYear ?>
            </span>
        </div>
    </div>

    <!-- Chart Area with Y-Axis Labels + Curve + Undistorted Circular Points -->
    <div class="flex-1 flex flex-col justify-center my-2">
        <div class="flex items-stretch gap-3">
            
            <!-- Y-Axis Labels -->
            <div class="flex flex-col justify-between text-[10px] font-medium text-[#8A8F98] text-right w-10 sm:w-12 py-0.5 select-none tabular-nums flex-shrink-0">
                <span><?= e($yTickTop) ?></span>
                <span><?= e($yTickMid) ?></span>
                <span>0</span>
            </div>

            <!-- Interactive Plot Container -->
            <div class="relative flex-1 h-48 sm:h-52 mr-2" id="stats-plot-area">
                
                <!-- Floating Tooltip -->
                <div
                    id="chart-tooltip"
                    class="absolute z-30 pointer-events-none opacity-0 transition-opacity duration-150 bg-[#171717] text-white text-[11px] font-medium px-2.5 py-1.5 rounded-md shadow-md whitespace-nowrap"
                    style="left: 0; top: 0;"
                >
                    <span id="tooltip-text"></span>
                </div>

                <!-- Responsive Grid, Area & Line SVG -->
                <svg class="w-full h-full overflow-visible" viewBox="0 0 <?= $chartWidth ?> <?= $chartHeight ?>" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="tealGradient" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#2FA89D" stop-opacity="0.22"/>
                            <stop offset="100%" stop-color="#2FA89D" stop-opacity="0.0"/>
                        </linearGradient>
                    </defs>

                    <!-- Top & Middle Dashed Gridlines -->
                    <line x1="0" y1="0" x2="<?= $chartWidth ?>" y2="0" stroke="#E8EAED" stroke-width="1" stroke-dasharray="4 4" vector-effect="non-scaling-stroke" />
                    <line x1="0" y1="<?= $chartHeight / 2 ?>" x2="<?= $chartWidth ?>" y2="<?= $chartHeight / 2 ?>" stroke="#E8EAED" stroke-width="1" stroke-dasharray="4 4" vector-effect="non-scaling-stroke" />

                    <!-- Baseline -->
                    <line x1="0" y1="<?= $chartHeight ?>" x2="<?= $chartWidth ?>" y2="<?= $chartHeight ?>" stroke="#E8EAED" stroke-width="1" vector-effect="non-scaling-stroke" />

                    <!-- Filled Area -->
                    <path d="<?= $areaD ?>" fill="url(#tealGradient)" class="mt-chart-area" />

                    <!-- Smooth Teal Line -->
                    <path 
                        d="<?= $pathD ?>" 
                        pathLength="1"
                        fill="none" 
                        stroke="#2FA89D" 
                        stroke-width="2.5" 
                        stroke-linecap="round" 
                        stroke-linejoin="round"
                        vector-effect="non-scaling-stroke"
                        class="mt-chart-line"
                    />
                </svg>

                <!-- HTML Percentage-Positioned Circular Dots (Never squish!) -->
                <?php foreach ($points as $i => $p): ?>
                    <button
                        type="button"
                        aria-label="<?= e($p['month'] . ': ' . formatRupiah($p['amount'])) ?>"
                        style="left: <?= $p['pctX'] ?>%; top: <?= $p['pctY'] ?>%; --mt-delay: <?= 520 + ($i * 40) ?>ms;"
                        class="mt-chart-dot absolute -translate-x-1/2 -translate-y-1/2 w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-white border-[2.5px] border-[#2FA89D] hover:scale-125 focus:scale-125 hover:bg-[#2FA89D] transition-transform focus:outline-none z-10 cursor-pointer"
                        data-month="<?= e($p['month']) ?>"
                        data-amount="<?= e(formatRupiah($p['amount'])) ?>"
                        onmouseenter="showChartTooltip(this, '<?= e($p['month']) ?>: <?= e(formatRupiah($p['amount'])) ?>')"
                        onfocus="showChartTooltip(this, '<?= e($p['month']) ?>: <?= e(formatRupiah($p['amount'])) ?>')"
                        onmouseleave="hideChartTooltip()"
                        onblur="hideChartTooltip()"
                    ></button>
                <?php endforeach; ?>

            </div>

        </div>

        <!-- Aligned X-Axis Month Labels -->
        <div class="flex items-center gap-3 mt-2.5">
            <div class="w-10 sm:w-12 flex-shrink-0"></div>
            <div class="relative flex-1 h-4 mr-2">
                <?php foreach ($points as $p): ?>
                    <span
                        style="left: <?= $p['pctX'] ?>%;"
                        class="absolute -translate-x-1/2 text-[10px] sm:text-[11px] font-medium text-[#8A8F98] whitespace-nowrap"
                    >
                        <?= e($p['month']) ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Bottom Dynamic Summary Row -->
    <div class="mt-4 pt-3 border-t border-[#E8EAED] grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
        <div>
            <span class="text-[10px] text-[#8A8F98] block uppercase">Bulan <?= e($statsCurrentMonthLabel) ?></span>
            <span class="font-bold text-[#2B2B2B] tabular-nums"><?= formatRupiah($statsCurrentAmount) ?></span>
        </div>
        <div>
            <span class="text-[10px] text-[#8A8F98] block uppercase">Rata-rata / Bln</span>
            <span class="font-bold text-[#2B2B2B] tabular-nums"><?= formatRupiah($statsAverageAmount) ?></span>
        </div>
        <div class="col-span-2 sm:col-span-1 text-left sm:text-right">
            <span class="text-[10px] text-[#8A8F98] block uppercase">Tren Pengeluaran</span>
            <span class="font-bold <?= $statsTrendPositive ? 'text-[#2FA89D]' : 'text-[#E56B6F]' ?> inline-flex items-center gap-1">
                <?php if ($statsTrendPositive): ?>
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                        <polyline points="17 18 23 18 23 12"></polyline>
                    </svg>
                <?php else: ?>
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                <?php endif; ?>
                <span><?= e($statsTrendText) ?></span>
            </span>
        </div>
    </div>

</div>

<script>
function showChartTooltip(targetEl, text) {
    const tooltip = document.getElementById('chart-tooltip');
    const label = document.getElementById('tooltip-text');
    const plot = document.getElementById('stats-plot-area');
    if (!tooltip || !label || !plot || !targetEl) return;

    label.textContent = text;
    const rect = targetEl.getBoundingClientRect();
    const plotRect = plot.getBoundingClientRect();
    const centerX = rect.left - plotRect.left + (rect.width / 2);
    const pointY = rect.top - plotRect.top;

    // Show briefly to measure width accurately, then clamp within plot bounds
    tooltip.style.opacity = '1';
    const tipWidth = tooltip.offsetWidth || 120;
    const tipHeight = tooltip.offsetHeight || 28;

    const minLeft = tipWidth / 2;
    const maxLeft = Math.max(minLeft, plotRect.width - (tipWidth / 2));
    const clampedX = Math.min(Math.max(centerX, minLeft), maxLeft);
    const topPos = pointY > (tipHeight + 12) ? (pointY - tipHeight - 8) : (pointY + 14);

    tooltip.style.left = clampedX + 'px';
    tooltip.style.top = topPos + 'px';
    tooltip.style.transform = 'translateX(-50%)';
}

function hideChartTooltip() {
    const tooltip = document.getElementById('chart-tooltip');
    if (tooltip) tooltip.style.opacity = '0';
}
</script>
