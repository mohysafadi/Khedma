<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestChat;
use App\Models\ChatMessage;
use App\Models\Customer;
use App\Models\Professional;
class ChatController extends Controller
{
    /**
     * جلب كل المحادثات الخاصة بالمستخدم الحالي
     */

public function getChats()
{
    $user = auth()->user();

    if ($user->customer) {
        $chats = RequestChat::where('customer_id', $user->customer->customer_id)
            ->with(['messages' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }])
            ->orderBy('updated_at', 'desc')
            ->get();
    } elseif ($user->professional) {
        $chats = RequestChat::where('professional_id', $user->professional->professional_id)
            ->with(['messages' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }])
            ->orderBy('updated_at', 'desc')
            ->get();
    } else {
        $chats = collect();
    }

    $data = $chats->map(function ($chat) {
        // جلب الزبون
        $customer = Customer::with('user')->find($chat->customer_id);
        // جلب المهني
        $professional = Professional::with('user')->find($chat->professional_id);

        return [
            'chat_id'           => $chat->chat_id,
            'request_id'        => $chat->request_id,

            'customer_id'       => $chat->customer_id,
            'customer_name'     => $customer?->user?->name,

            'professional_id'   => $chat->professional_id,
            'professional_name' => $professional?->user?->name,

            'created_at'        => $chat->created_at,
            'updated_at'        => $chat->updated_at,

            'messages'          => $chat->messages,
        ];
    });

    return response()->json([
        'message' => 'تم جلب المحادثات بنجاح',
        'chats'   => $data
    ]);
}
    /**
     * جلب الرسائل داخل محادثة واحدة
     */
    public function getChatMessages($chat_id)
    {
        $messages = ChatMessage::where('chat_id', $chat_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'message' => 'تم جلب الرسائل بنجاح',
            'messages' => $messages
        ]);
    }

    /**
     * إرسال رسالة جديدة داخل محادثة
     */
    public function sendMessage(Request $request, $chat_id)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $chat = RequestChat::findOrFail($chat_id);
        $user = $request->user();

        // تحديد نوع المرسل
        if ($user->customer) {
            $senderType = 'customer';
            $senderId = $user->customer->customer_id;
        } else {
            $senderType = 'professional';
            $senderId = $user->professional->professional_id;
        }

        // إنشاء الرسالة
        $message = ChatMessage::create([
            'chat_id' => $chat_id,
            'sender_id' => $senderId,
            'sender_type' => $senderType,
            'message' => $request->message,
        ]);

        return response()->json([
            'message' => 'تم إرسال الرسالة',
            'data' => $message
        ]);
    }


    // إنشاء محادثة جديدة عند قبول الطلب

    public function createChatOnAccept($request_id, $customer_id, $professional_id)
    {
        $chat = RequestChat::create([
            'request_id' => $request_id,
            'customer_id' => $customer_id,
            'professional_id' => $professional_id
        ]);

        return $chat;
    }
}
