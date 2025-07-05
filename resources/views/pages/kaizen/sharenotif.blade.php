<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Kaizen</title>

    <!-- OG Meta Tags -->
    <meta property="og:title" content="New Kaizen" />
    <meta property="og:description" content="{{ $kaizen->keterangan }}" />
    <meta property="og:image" content="{{ url($kaizen->picture) }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />

    <script>
        setTimeout(function() {
            window.location.href = "{{ url('/openkaizen?search=' . $kaizenId) }}";
        }, 100);
    </script>
</head>
<body>
    <p>Loading Kaizen Preview...</p>
</body>
</html>
