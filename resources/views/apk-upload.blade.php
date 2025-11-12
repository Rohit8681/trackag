<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload APK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h3 class="mb-4">Upload APK File</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            <p><strong>File Path:</strong> {{ session('file') }}</p>
        @endif

        <form action="{{ route('apk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($apkData) && !empty($apkData->id))
            <input type="hidden" name="main_id" value="{{ $apkData->id }}">
            @else
            <input type="hidden" name="main_id" value="0">
            @endif
            <div class="mb-3">
                <label for="apkFile" class="form-label">APK File</label>
                <input type="file" class="form-control" accept=".apk" id="apkFile" name="apkFile" required>
                @error('apkFile') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="versionCode" class="form-label">Version Code</label>
                <input type="text" class="form-control" id="versionCode" name="versionCode" required>
                @error('versionCode') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="versionName" class="form-label">Version Name</label>
                <input type="text" class="form-control" id="versionName" name="versionName" required>
                @error('versionName') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>
</div>

</body>
</html>
