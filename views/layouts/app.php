<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' — MoneyTrack' : 'MoneyTrack — Dashboard' ?></title>
    
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN configured with exact MoneyTrack design tokens -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        teal: {
                            DEFAULT: '#2FA89D',
                            hover: '#27958B',
                            50: '#F0F9F8',
                            100: '#D6F0ED',
                            200: '#ADE0DB',
                            500: '#2FA89D',
                            600: '#27958B',
                            700: '#1F7A71',
                        },
                        sidebar: '#171717',
                        canvas: '#F5F6F8',
                        card: '#FFFFFF',
                        main: '#2B2B2B',
                        secondary: '#8A8F98',
                        border: '#E8EAED',
                        danger: '#E56B6F'
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        'sm': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #F5F6F8;
            color: #2B2B2B;
        }
        /* Custom sleek scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #D1D5DB;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #9CA3AF;
        }
    </style>
    <link rel="stylesheet" href="<?= asset('css/output.css') ?>">
</head>
<body class="bg-[#F5F6F8] min-h-screen text-[#2B2B2B] antialiased selection:bg-teal-100 selection:text-teal-700">

    <div class="flex min-h-screen">
        
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden transition-opacity"></div>

        <!-- Dark Vertical Sidebar -->
        <?php include __DIR__ . '/sidebar.php'; ?>

        <!-- Main Content Area with Desktop offset for sidebar (~240px) -->
        <div class="flex-1 flex flex-col min-w-0 lg:pl-[240px]">
            
            <!-- Dashboard Header / Navbar -->
            <?php include __DIR__ . '/navbar.php'; ?>

            <!-- Page Content Body -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <?php if (isset($contentView) && file_exists($contentView)): ?>
                    <?php include $contentView; ?>
                <?php else: ?>
                    <?= $content ?? '' ?>
                <?php endif; ?>
            </main>

        </div>

    </div>

    <!-- Base URL for client AJAX calls -->
    <script>
        window.APP_BASE_URL = '<?= rtrim(base_url(), '/') ?>';
    </script>
    <!-- Application JavaScript -->
    <script src="<?= asset('js/app.js') ?>"></script>
    <script src="<?= asset('js/dashboard.js') ?>"></script>
</body>
</html>
