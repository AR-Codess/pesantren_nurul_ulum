<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Browser</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            color: #333;
        }
        .file-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }
        .file-item {
            border: 1px solid #ddd;
            padding: 10px;
            width: 150px;
            text-align: center;
            cursor: pointer;
        }
        .file-item:hover {
            background-color: #f5f5f5;
        }
        .file-thumbnail {
            width: 100%;
            height: 100px;
            object-fit: cover;
            margin-bottom: 5px;
        }
        .file-name {
            font-size: 12px;
            word-break: break-all;
        }
        .no-files {
            margin-top: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <h2>Pilih Gambar</h2>
    
    @if(count($files) > 0)
        <div class="file-container">
            @foreach($files as $file)
                <div class="file-item" onclick="selectFile('{{ $file['url'] }}')">
                    <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="file-thumbnail">
                    <div class="file-name">{{ $file['name'] }}</div>
                </div>
            @endforeach
        </div>
    @else
        <div class="no-files">Tidak ada file gambar yang tersedia.</div>
    @endif

    <script>
        function selectFile(url) {
            window.opener.CKEDITOR.tools.callFunction({{ $funcNum }}, url);
            window.close();
        }
    </script>
</body>
</html>