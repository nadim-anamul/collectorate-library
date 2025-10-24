<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>{{ __('ui.member_card') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" />
</head>
<body class="p-6">
    <div class="w-96 border p-4 rounded shadow">
        <div class="text-center mb-2">
            <h1 class="text-lg font-semibold">{{ __('ui.library_member_card') }}</h1>
        </div>
        <div class="mb-2">
            <div><span class="font-medium">{{ __('ui.name') }}:</span> {{ $member->name }}</div>
            <div><span class="font-medium">{{ __('ui.member_id') }}:</span> {{ $member->member_id }}</div>
            <div><span class="font-medium">{{ __('ui.type') }}:</span> {{ $member->type }}</div>
        </div>
        <div class="text-center">
            {!! DNS1D::getBarcodeHTML($member->member_id, 'C39') !!}
        </div>
    </div>
    <script>window.print()</script>
</body>
</html>
