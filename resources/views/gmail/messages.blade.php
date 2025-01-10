<h1>Gmail Messages</h1>
<ul>
    {{-- @foreach ($emails as $email) --}}
        <li>
            <strong>Subject:</strong> 
            {{-- {{ $email['subject'] }} --}}
            <br>
            <strong>Body:</strong> 
            @php
                // $body = $email['body'];
                // if (preg_match('/ประเภทของรายการ.*?วันและเวลาการทำรายการ:.*?\r?\n/si', $body, $matches)) {
                //     $result = $matches[0]; // ข้อความที่ต้องการ
                //     echo $result;
                // } else {
                //     echo "ไม่พบข้อมูลที่ต้องการ";
                // }
            @endphp     
            <br>
        </li>
    {{-- @endforeach --}}
</ul>