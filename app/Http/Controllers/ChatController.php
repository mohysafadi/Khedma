<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestChat;
use App\Models\ChatMessage;

class ChatController extends Controller
{
    /**
     * جلب كل المحادثات الخاصة بالمستخدم الحالي
     */
    public function getChats()
    {
        $user = auth()->user();

        // إذا المستخدم زبون
        if ($user->customer) {
            $chats = RequestChat::where('customer_id', $user->customer->customer_id)
                ->with(['messages' => function ($q) {
                    $q->orderBy('created_at', 'desc');
                }])
                ->orderBy('updated_at', 'desc')
                ->get();
        }

        // إذا المستخدم مهني
        elseif ($user->professional) {
            $chats = RequestChat::where('professional_id', $user->professional->professional_id)
                ->with(['messages' => function ($q) {
                    $q->orderBy('created_at', 'desc');
                }])
                ->orderBy('updated_at', 'desc')
                ->get();
        } else {
            $chats = [];
        }

        return response()->json([
            'message' => 'تم جلب المحادثات بنجاح',
            'chats' => $chats
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
