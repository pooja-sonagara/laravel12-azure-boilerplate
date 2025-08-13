<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Azure Blob Storage Demo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light min-vh-100">
    <div class="container py-5">
        <!-- Hero Section -->
        <div class="row mb-5">
            <div class="col-lg-10 mx-auto text-center">
                <div class="mb-4">
                    <i class="bi bi-cloud-arrow-up-fill display-1 text-primary"></i>
                </div>
                <h1 class="display-5 fw-bold mb-2 text-primary">Azure Blob Storage</h1>
                <p class="lead text-secondary mb-4">Upload, view, and download your files securely in the cloud.</p>
            </div>
        </div>

        <!-- Upload Card -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow border-0">
                    <div class="card-body p-4">
                        <h4 class="mb-3 text-primary"><i class="bi bi-upload me-2"></i>Upload File</h4>
                        @if(session('success'))
                            <div class="alert alert-success text-center">{{ session('success') }}</div>
                        @endif
                        <form action="{{ route('azure.upload') }}" method="POST" enctype="multipart/form-data" class="row g-3 align-items-center justify-content-center">
                            @csrf
                            <div class="col-12 col-md-8">
                                <input type="file" name="file" id="file" class="form-control form-control-lg" required>
                            </div>
                            <div class="col-12 col-md-4 text-end">
                                <button type="submit" class="btn btn-primary btn-lg w-100"><i class="bi bi-cloud-arrow-up"></i> Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Files Grid -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-secondary mb-0"><i class="bi bi-folder2-open me-2"></i>Files in <span class="badge bg-info text-dark">storage/</span></h4>
                </div>
                <div class="row g-4">
                    @forelse($files as $file)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div class="mb-2">
                                        <i class="bi bi-file-earmark-text text-info fs-2 mb-2"></i>
                                        <div class="fw-semibold text-truncate" title="{{ basename($file) }}">{{ basename($file) }}</div>
                                    </div>
                                    <a href="{{ route('azure.download', ['filename' => $file]) }}" class="btn btn-outline-success btn-sm mt-auto w-100">
                                        <i class="bi bi-cloud-arrow-down"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">No files found.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
