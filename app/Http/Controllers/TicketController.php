<?php

namespace App\Http\Controllers;

use App\Models\SupportAttachment;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

class TicketController extends Controller
{
    public function supportTicket()
    {
        if (!Auth::user()) {
            abort(404);
        }
        $user = Auth::user();
        $pageTitle = "Support Tickets";
        $emptyMessage = "No data found";
        $supports = SupportTicket::where('user_id', $user->id)->orderBy('priority', 'desc')->orderBy('id','desc')->paginate(getPaginate());
        if($user->user_type == "staff"){
            return view('staff.support.index', compact('supports', 'pageTitle', 'emptyMessage'));
        }
        elseif($user->user_type == "manager"){
            return view('manager.support.index', compact('supports', 'pageTitle', 'emptyMessage'));
        }
        elseif($user->user_type == "staff_warehouse"){
            return view('staff_warehouse.support.index', compact('supports', 'pageTitle', 'emptyMessage'));
        }
        elseif($user->user_type == "manager_warehouse"){
            return view('manager_warehouse.support.index', compact('supports', 'pageTitle', 'emptyMessage'));
        }
        elseif($user->user_type == "delivery_man"){
            return view('delivery_man.support.index', compact('supports', 'pageTitle', 'emptyMessage'));
        }
    }


    public function openSupportTicket()
    {
        if (!Auth::user()) {
            abort(404);
        }
        $pageTitle = "Support Tickets";
        $user = Auth::user();
        if($user->user_type == "staff"){
            return view('staff.support.create', compact('pageTitle','user'));
        }
        elseif($user->user_type == "manager"){
            return view('manager.support.create', compact('pageTitle', 'user'));
        }
        elseif($user->user_type == "staff_warehouse"){
            return view('staff_warehouse.support.create', compact('pageTitle','user'));
        }
        elseif($user->user_type == "manager_warehouse"){
            return view('manager_warehouse.support.create', compact('pageTitle', 'user'));
        }
        elseif($user->user_type == "delivery_man"){
            return view('delivery_man.support.create', compact('pageTitle', 'user'));
        }
    }

    public function storeSupportTicket(Request $request)
    {
        $ticket = new SupportTicket();
        $message = new SupportMessage();

        $files = $request->file('attachments');
        $allowedExts = array('jpg', 'png', 'jpeg', 'pdf','doc','docx');

        $this->validate($request, [
            'attachments' => [
                'max:4096',
                function ($attribute, $value, $fail) use ($files, $allowedExts) {
                    foreach ($files as $file) {
                        $ext = strtolower($file->getClientOriginalExtension());
                        if (($file->getSize() / 1000000) > 2) {
                            return $fail("Miximum 2MB file size allowed!");
                        }
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, pdf, doc, docx files are allowed");
                        }
                    }
                    if (count($files) > 5) {
                        return $fail("Maximum 5 files can be uploaded");
                    }
                },
            ],
            'name' => 'required|max:191',
            'email' => 'required|email|max:191',
            'subject' => 'required|max:100',
            'message' => 'required',
            'priority' => 'required|in:1,2,3',
        ]);

        $user = auth()->user();
        $ticket->user_id = $user->id;
        $random = rand(100000, 999999);
        $ticket->ticket = $random;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = 0;
        $ticket->priority = $request->priority;
        $ticket->save();

        $message->supportticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();


        $path = imagePath()['ticket']['path'];
        if ($request->hasFile('attachments')) {
            foreach ($files as  $file) {
                try {
                    $attachment = new SupportAttachment();
                    $attachment->support_message_id = $message->id;
                    $attachment->attachment = uploadFile($file, $path);
                    $attachment->save();
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Could not upload your file'];
                    return back()->withNotify($notify);
                }
            }
        }
        $notify[] = ['success', 'ticket created successfully!'];
        return redirect()->route('ticket')->withNotify($notify);
    }

    public function viewTicket($ticket)
    {
        $pageTitle = "Support Tickets";
        $userId = 0;
        if (Auth::user()) {
            $userId = Auth::id();
        }
        $my_ticket = SupportTicket::where('ticket', $ticket)->where('user_id',$userId)->orderBy('id','desc')->firstOrFail();
        $messages = SupportMessage::where('supportticket_id', $my_ticket->id)->orderBy('id','desc')->get();
        $user = auth()->user();
        if($user){
            if($user->user_type == "staff"){
                return view('staff.support.view', compact('my_ticket', 'messages', 'pageTitle', 'user'));
            }
            elseif($user->user_type == "manager"){
                return view('manager.support.view', compact('my_ticket', 'messages', 'pageTitle', 'user'));
            }
            elseif($user->user_type == "staff_warehouse"){
                return view('staff_warehouse.support.view', compact('my_ticket', 'messages', 'pageTitle', 'user'));
            }
            elseif($user->user_type == "manager_warehouse"){
                return view('manager_warehouse.support.view', compact('my_ticket', 'messages', 'pageTitle', 'user'));
            }
            elseif($user->user_type == "delivery_man"){
                return view('delivery_man.support.view', compact('my_ticket', 'messages', 'pageTitle', 'user'));
            }
        }
        else{
            return view('templates.basic.ticket', compact('my_ticket', 'messages', 'pageTitle'));
        }
    }

    public function replyTicket(Request $request, $id)
    {
        $userId = 0;
        if (Auth::user()) {
            $userId = Auth::id();
        }
        $ticket = SupportTicket::where('user_id',$userId)->where('id',$id)->firstOrFail();
        $message = new SupportMessage();
        if ($request->replayTicket == 1) {
            $attachments = $request->file('attachments');
            $allowedExts = array('jpg', 'png', 'jpeg', 'pdf', 'doc','docx');

            $this->validate($request, [
                'attachments' => [
                    'max:4096',
                    function ($attribute, $value, $fail) use ($attachments, $allowedExts) {
                        foreach ($attachments as $file) {
                            $ext = strtolower($file->getClientOriginalExtension());
                            if (($file->getSize() / 1000000) > 2) {
                                return $fail("Miximum 2MB file size allowed!");
                            }
                            if (!in_array($ext, $allowedExts)) {
                                return $fail("Only png, jpg, jpeg, pdf doc docx files are allowed");
                            }
                        }
                        if (count($attachments) > 5) {
                            return $fail("Maximum 5 files can be uploaded");
                        }
                    },
                ],
                'message' => 'required',
            ]);

            $ticket->status = 2;
            $ticket->last_reply = Carbon::now();
            $ticket->save();

            $message->supportticket_id = $ticket->id;
            $message->message = $request->message;
            $message->save();

            $path = imagePath()['ticket']['path'];

            if ($request->hasFile('attachments')) {
                foreach ($attachments as $file) {
                    try {
                        $attachment = new SupportAttachment();
                        $attachment->support_message_id = $message->id;
                        $attachment->attachment = uploadFile($file, $path);
                        $attachment->save();

                    } catch (\Exception $exp) {
                        $notify[] = ['error', 'Could not upload your ' . $file];
                        return back()->withNotify($notify)->withInput();
                    }
                }
            }

            $notify[] = ['success', 'Support ticket replied successfully!'];
        } elseif ($request->replayTicket == 2) {
            $ticket->status = 3;
            $ticket->last_reply = Carbon::now();
            $ticket->save();
            $notify[] = ['success', 'Support ticket closed successfully!'];
        }else{
            $notify[] = ['error','Invalid request'];
        }
        return back()->withNotify($notify);

    }
    


    public function ticketDownload($ticket_id)
    {
        $attachment = SupportAttachment::findOrFail(decrypt($ticket_id));
        $file = $attachment->attachment;

        $path = imagePath()['ticket']['path'];
        $full_path = $path.'/'. $file;

        $title = slug($attachment->supportMessage->ticket->subject);
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);


        header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }

}
