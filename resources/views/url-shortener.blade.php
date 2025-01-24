<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Olumide's URL Shortener Service</h1>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form id="shortenForm" method="POST" action="{{ route('encode') }}">
            @csrf
            <div class="mb-3">
                <label for="originalUrl" class="form-label">Enter The URL You Want To Shorten:</label>
                <input type="url" name="url" id="originalUrl"
                    class="form-control @error('url') is-invalid @enderror" required value="{{ old('url') }}">
                @error('url')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Shorten URL</button>
        </form>

        @if (session('short_url'))
            <div id="shortenedUrl" class="alert alert-success mt-4">
                Original URL: {{ session('original_url') }}<br>
                Shortened URL: <a href="{{ session('short_url') }}" target="_blank">{{ session('short_url') }}</a>
            </div>
        @endif


        <form id="decodeForm" method="POST" action="{{ route('decode') }}">
            @csrf
            <div class="mb-3 mt-5">
                <label for="shortUrl" class="form-label">Enter The Shortened URL You Want To decode:</label>
                <input type="url" name="short_url" id="shortUrl"
                    class="form-control @error('short_url') is-invalid @enderror" required
                    value="{{ old('short_url') }}">
                @error('short_url')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <button type="submit" class="btn btn-secondary">Decode URL</button>
        </form>

        @if (session('decoded_url'))
            <div id="originalUrl" class="alert alert-info mt-4">
                Original URL: <a href="{{ session('decoded_url') }}" target="_blank">
                    {{ session('decoded_url') }}
                </a>
            </div>
        @endif


    </div>


    {{-- <script>
        document.getElementById('shortenForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const originalUrl = document.getElementById('originalUrl').value;

            const response = await fetch('/encode', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ url: originalUrl })
            });

            const result = await response.json();
            const outputDiv = document.getElementById('shortenedUrl');

            if (response.ok) {
                outputDiv.textContent = `Short URL: ${result.short_url}`;
                outputDiv.classList.remove('d-none');
            } else {
                outputDiv.textContent = result.error || 'An error occurred.';
                outputDiv.classList.replace('alert-success', 'alert-danger');
                outputDiv.classList.remove('d-none');
            }
        });

        document.getElementById('decodeForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const shortUrl = document.getElementById('shortUrl').value;

            const response = await fetch('/decode', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ short_url: shortUrl })
            });

            const result = await response.json();
            const outputDiv = document.getElementById('originalUrl');

            if (response.ok) {
                outputDiv.textContent = `Original URL: ${result.original_url}`;
                outputDiv.classList.remove('d-none');
            } else {
                outputDiv.textContent = result.error || 'An error occurred.';
                outputDiv.classList.replace('alert-info', 'alert-danger');
                outputDiv.classList.remove('d-none');
            }
        });
    </script> --}}
</body>

</html>
