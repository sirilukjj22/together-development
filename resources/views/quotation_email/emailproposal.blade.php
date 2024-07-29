<!DOCTYPE html>
<html>
<head>
    <title>{{ $Data['title'] }}</title>
</head>
<body>
    <p>{!! $Data['detail'] !!}</p>
    <p>ความคิดเห็น: {{ $Data['comment'] }}</p>
    <a href="{{ asset($Data['pdf'].".pdf") }}">[เอกสาร]</a>
    <!-- เพิ่มรายละเอียดใบเสนอราคาเพิ่มเติมตามต้องการ -->
</body>
</html>
