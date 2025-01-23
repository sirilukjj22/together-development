<!-- resources/views/gmail/inbox.blade.php -->
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gmail Inbox</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Gmail Inbox</h2>

    @if(session('gmail_token'))
        <p>Access token is available, now showing inbox messages.</p>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Sender</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $message)
                    @php
                        // ดึงข้อมูลอีเมลจาก API
                        $msg = $message->getPayload();
                        $subject = '';
                        $sender = '';
                        $date = '';

                        // หา subject, sender, และ date จากข้อมูล headers
                        foreach ($msg->getHeaders() as $header) {
                            if ($header->getName() == 'Subject') {
                                $subject = $header->getValue();
                            }
                            if ($header->getName() == 'From') {
                                $sender = $header->getValue();
                            }
                            if ($header->getName() == 'Date') {
                                $date = $header->getValue();
                            }
                        }
                    @endphp
                    <tr>
                        <td>{{ $subject }}</td>
                        <td>{{ $sender }}</td>
                        <td>{{ $date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No Gmail token found. Please authenticate first.</p>
        <a href="/google/auth" class="btn btn-primary">Authenticate with Gmail</a>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
