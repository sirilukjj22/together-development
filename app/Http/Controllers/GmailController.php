<?php

namespace App\Http\Controllers;

use App\Models\SMS_forwards;
use Carbon\Carbon;
use Google\Client;
use Google\Service\Gmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GmailController extends Controller
{
    private function getGoogleClient()
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->addScope(Gmail::GMAIL_READONLY);
        $client->setAccessType('offline'); // ใช้เพื่อรับ Refresh Token
        $client->setPrompt('consent'); // รับ Refresh Token ครั้งแรก

        return $client;
    }

    public function redirectToGoogle()
    {
        $client = $this->getGoogleClient();
        $authUrl = $client->createAuthUrl();

        return redirect($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = $this->getGoogleClient();
        $code = $request->get('code');

        if ($code) {
            $token = $client->fetchAccessTokenWithAuthCode($code);

            // เก็บ Refresh Token ไว้ในฐานข้อมูล
            $refreshToken = $token['refresh_token'];
            // Save this token securely, e.g., in users table
            // auth()->user()->update(['google_refresh_token' => $refreshToken]);

            DB::table('google_api')->insert([
                'token' => $refreshToken,
                'created_at' => Carbon::now(),
            ]);

            return redirect()->route('gmail.messages');
        }

        return redirect()->route('home')->with('error', 'Unable to authenticate');
    }

    // public function listMessages()
    // {
    //     $user = DB::table('google_api')->first();
    //     $refreshToken = $user->token;

    //     if (!$refreshToken) {
    //         return redirect()->route('google.auth')->with('error', 'Google account not connected');
    //     }

    //     $client = $this->getGoogleClient();
    //     $client->fetchAccessTokenWithRefreshToken($refreshToken);

    //     $gmail = new Gmail($client);
    //     $searchQuery = 'from:sirilukjj22@gmail.com subject:"Transfer Test"';
    //     $messages = $gmail->users_messages->listUsersMessages('me', ['q' => $searchQuery]);

    //     dd($messages->getMessages());

    //     return view('gmail.messages', ['messages' => $messages->getMessages()]);
    // }

    public function listMessages()
    {
        $data_api = DB::table('google_api')->first();
        $refreshToken = $data_api->token;

        if (!$refreshToken) {
            return redirect()->route('google.auth')->with('error', 'Google account not connected');
        }

        $client = $this->getGoogleClient();
        $client->fetchAccessTokenWithRefreshToken($refreshToken);

        $gmail = new Gmail($client);

        // ดึงรายการข้อความ
        $startDate = date('Y/m/d', strtotime($data_api->date_read));
        // $searchQuery = "after:$startDate before:$endDate";
        // $searchQuery = 'from:sirilukjj22@gmail.com subject:"Transfer Test"';
        $searchQuery = "after:$startDate subject:'SCB Business Alert: Transaction Notification'";

        $messages = $gmail->users_messages->listUsersMessages('me', ['q' => $searchQuery]);
        $emailDetails = [];

        if ($messages->getMessages()) {
            foreach ($messages->getMessages() as $message) {
                $messageId = $message->getId();
                $messageDetail = $gmail->users_messages->get('me', $message->getId());

                // ตรวจสอบ Internal Date
                $internalDate = $messageDetail->getInternalDate(); // เป็น timestamp
                $receivedDateTime = date('Y-m-d H:i:s', $internalDate / 1000); // แปลง timestamp เป็นเวลาปกติ

                $startDateTime = date('Y/m/d H:i:s', strtotime($data_api->date_read));
                $endDateTime = date('Y/m/d H:i:s');

                if (strtotime($receivedDateTime) > strtotime($startDateTime) &&
                    strtotime($receivedDateTime) <= strtotime($endDateTime)) {
                    // ดึงข้อมูลส่วน Header
                    $headers = $messageDetail->getPayload()->getHeaders();
                    $subject = '';
                    $from = ''; // เพิ่มตัวแปรสำหรับชื่อผู้ส่ง
                    foreach ($headers as $header) {
                        if ($header->getName() === 'Subject') {
                            $subject = $header->getValue();
                        }

                        if ($header->getName() === 'From') {
                            $from = $header->getValue();
                        }
                    }

                    // แยกแค่ชื่อผู้ส่ง (หากมีอีเมลในวงเล็บ)
                    if (preg_match('/^(.*?)(?=\s<)/', $from, $matches)) {
                        $from = $matches[1];
                    }

                    // ดึง Body (Content)
                    $body = '';
                    $parts = $messageDetail->getPayload()->getParts();
                    foreach ($parts as $part) {
                        if ($part->getMimeType() === 'text/plain') {
                            // ใช้ base64_decode และ strtr เพื่อแปลงข้อความ
                            $body = base64_decode(strtr($part->getBody()->getData(), '-_', '+/'));
                        }
                    }

                    if (preg_match('/หมายเลขบัญชี.*?รายละเอียดเพิ่มเติม: เงินโอนจาก [\w\d]+ - [\w\d]+/s', $body, $matches)) {
                        $result_body = $matches[0]; // ข้อความที่ต้องการ
                    } else {
                        $result_body = "ไม่พบข้อมูลที่ต้องการ";
                    }

                    SMS_forwards::create([
                        'messages' => $result_body,
                        'sender' => $from,
                        'chanel' => 'Gmail',
                        'is_status' => 0
                    ]);

                    // อัพเดทวันที่ดึงข้อมูลล่าสุด
                    DB::table('google_api')->where('id', 1)->update([
                        'date_read' => Carbon::now(),
                    ]);
                }
            }
        }

        return view('gmail.messages');
    }
}







