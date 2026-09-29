<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' — MoneyTrack' : 'MoneyTrack' ?></title>
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN with configuration for instant preview -->
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
                            500: '#2FA89D',
                            600: '#27958B',
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
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: #F5F6F8;
            color: #2B2B2B;
        }
    </style>
    <link rel="stylesheet" href="<?= asset('css/output.css') ?>">
</head>
<body class="bg-[#F5F6F8] min-h-screen flex flex-col justify-center items-center p-4 antialiased selection:bg-teal-100 selection:text-teal-700">

    <!-- Auth Container: Narrow, lightweight, centered with generous empty space -->
    <div class="w-full max-w-[360px] sm:max-w-[380px] my-auto py-8">
        <?php if (isset($contentView) && file_exists($contentView)): ?>
            <?php include $contentView; ?>
        <?php else: ?>
            <?= $content ?? '' ?>
        <?php endif; ?>
    </div>

    <!-- Small footer copyright -->
    <footer class="pb-6 text-center text-xs text-[#8A8F98]">
        &copy; <?= date('Y') ?> MoneyTrack. Hak cipta dilindungi undang-undang.
    </footer>

</body>
</html>
