<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as Req;
use App\Ticket;
use App\TicketMessage;
use App\User;
use Auth;
use DB;

class SupportController extends Controller
{
    public function create() {
        if(Request::ajax()) return view('support_create');
        return view('layouts.app')->with('page', view('support_create'));
    }

    public function view($id) {
        $ticket = Ticket::where([['id', $id], ['user_id', Auth::id()]])->first();
        if(!$ticket) return redirect('/support');

        $ticket->date = date("d.m.Y в H:i", strtotime($ticket->created_at));

        $messages = TicketMessage::where('ticket_id', $ticket->id)->get();

        foreach($messages as $message) {
            $message->date = date("d.m.Y в H:i", strtotime($message->created_at));
        }

        if(Request::ajax()) return view('support_view', compact('ticket', 'messages'));
        return view('layouts.app')->with('page', view('support_view', compact('ticket', 'messages')));
    }

    public function admin_view($id) {
        $ticket = Ticket::where([['id', $id]])->first();
        if(!$ticket) return redirect('/admin/support');

        $ticket->date = date("d.m.Y в H:i", strtotime($ticket->updated_at));

        $messages = TicketMessage::where('ticket_id', $ticket->id)->get();

        foreach($messages as $message) {
            $message->date = date("d.m.Y в H:i", strtotime($message->created_at));
        }

        $user = User::where('id', $ticket->user_id)->first();

        return view('admin.support_view', compact('ticket', 'messages', 'user'));
    }

    public function list_tickets() {
        $tickets = Ticket::orderBy('status', 'ASC')->orderBy('id', 'DESC')->paginate(12);

        return view('admin.support', compact('tickets'));
    }

    public function createTicket(Req $r) {
        if(Auth::guest()) return response()->json(['error' => 'Unauthenticated.']);
        if(Auth::user()->ban) return response()->json(['error' => 'User banned']);

        $messages = [
            'subject.required' => 'Поле "Тема" должно быть заполнено',
            'subject.min' => 'Тема должна иметь больше :min символов',
            'subject.max' => 'Максимальная длина темы :min символов',
            'body.required' => 'Поле "Сообщение" должно быть заполнено',
            'body.min' => 'Сообщение должно иметь больше :min символов',
            'body.max' => 'Максимальная длина сообщения :min символов'
        ];

        $validated = \Validator::make($r->all(), [
            'subject' => 'required|min:5|max:255',
            'body' => 'required|min:10|max:255',
        ], $messages);
        
        if ($validated->fails()) return response()->json(['error' => $validated->errors()->first()]);

        $get_ticket = Ticket::where([['user_id', Auth::id()], ['status', 0]])->count();

        if($get_ticket > 5) return response()->json(['error' => 'Лимит открытых тикетов - 5']);

        try {
            DB::beginTransaction();

            $ticket = Ticket::create([
                'user_id' => Auth::id(),
                'subject' => htmlspecialchars($r->subject)
            ]);

            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'username' => Auth::user()->name,
                'body' => htmlspecialchars($r->body)
            ]);

            DB::commit();

            return response()->json(['success' => true, 'ticket' => $ticket]);
        } catch(\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Ошибка сервера']);
        }
    }

    public function adminClose($id) {
        $ticket = Ticket::where([['id', $id]])->first();

        if(!$ticket || $ticket->status == 1) return redirect('/admin/support');

        $ticket->update(['status' => 1]);

        return redirect('/admin/support');
    }

    public function cloaseTicket(Req $r) {
        if(Auth::guest()) return response()->json(['error' => 'Unauthenticated.']);
        if(Auth::user()->ban) return response()->json(['error' => 'User banned'],);

        $ticket = Ticket::where([['id', $r->id], ['user_id', Auth::id()]])->first();

        if(!$ticket) return response()->json(['error' => 'Ticket not found.']);
        if($ticket->status == 1) return response()->json(['error' => 'Ticket already closed']);

        $ticket->update(['status' => 1]);

        return response()->json(['success' => true, 'ticket' => $ticket]);
    }

    public function sendTicket(Req $r) {
        if(Auth::guest()) return response()->json(['error' => 'Unauthenticated.']);
        if(Auth::user()->ban) return response()->json(['error' => 'User banned']);

        if(\Cache::has('support.user.' . Auth::user()->id)) return response()->json(['error' => 'Подождите']);
        \Cache::put('support.user.' . Auth::user()->id, '', 2);

        $messages = [
            'body.required' => 'Поле "Сообщение" должно быть заполнено',
            'body.min' => 'Сообщение должно иметь больше двух (2) символов',
            'body.max' => 'Максимальная длина сообщения 255 символов'
        ];

        $validated = \Validator::make($r->all(), [
            'body' => 'required|min:2|max:255',
        ], $messages);

        if ($validated->fails()) return response()->json(['error' => $validated->errors()->first()]);

        $ticket = Ticket::where([['id', $r->id], ['user_id', Auth::id()]])->first();

        if(!$ticket) return response()->json(['error' => 'Ticket not found.']);
        if($ticket->status == 1) return response()->json(['error' => 'Ticket already closed']);


        try {
            DB::beginTransaction();

            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'username' => Auth::user()->name,
                'body' => htmlspecialchars($r->body),
            ]);

            DB::commit();

            return response()->json(['success' => true, 'ticket' => $ticket]);
        } catch(\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Ошибка сервера']);
        }
    }

    public function adminSend($id, Req $r) {
        $ticket = Ticket::where([['id', $id]])->first();

        if(!$ticket || $ticket->status == 1) return redirect('/admin/support');

        try {
            DB::beginTransaction();

            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'username' => 'Агент Поддержки #'. Auth::id(),
                'body' => htmlspecialchars($r->message),
                'answer' => 1,
                'admin_id' => Auth::id()
            ]);

            DB::commit();

            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Ошибка сервера']);
        }
    }
}
