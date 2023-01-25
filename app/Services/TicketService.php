<?php

namespace App\Services;

use App\Http\Requests\TicketStoreRequest;
use App\Http\Requests\TicketUpdateRequest;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Notifications\StatusChangeDefault;

/**
 * Class TicketService
 * @package App\Services
 */
class TicketService
{
    /**
     * @param int|null $status
     * @return object
     */
    public function listTickets(int $status = null): object
    {
        if($this->checkAdmin()) {
            if($status === null) {
                return Cache::tags('tickets')->remember('tickets_default_'.request()->page, 120, function() {
                    return Ticket::where('status_id', 1)->orWhere('status_id', 2)->latest()->paginate(15);
                });
            }else {
                return Cache::tags('tickets')->remember('tickets_'.$status.'_'.request()->page, 120, function() {
                    return Ticket::where('status_id', $status)->latest()->paginate(15);
                });
            }
        }else {
            return Cache::tags('tickets')->remember('tickets_user_'.request()->page, 120, function() {
                return Ticket::where('user_id', Auth()->id())->latest()->paginate(15);
            });
        }
    }

    /**
     * @param TicketStoreRequest $request
     * @return object
     */
    public function store(TicketStoreRequest $request): object
    {
        Ticket::create(
            [
                'ticket' => Str::random(8),
                'user_id' => Auth()->id(),
                'status_id' => '1',
                'category_id' => $request->get('category_id'),
                'subject' => $request->get('subject'),
                'description' => $request->get('description')
            ]
        );
        return redirect('tickets.index');
    }

    /**
     * @param int $id
     * @return object
     */
    public function show(int $id): object
    {
        if($this->checkAdmin() || $this->checkOwner($id)) {
            return view('welcome', Ticket::where('id',$id)->firstOrFail());
        }
        abort(401,);
    }

    /**
     * @param int $id
     * @return object
     */
    public function edit(int $id): object
    {
        if($this->checkAdmin()) {
            return view('welcome', Ticket::where('id', $id)->firstOrFail());
        }
        abort(401);
    }

    /**
     * @param TicketUpdateRequest $request
     * @param int $id
     * @return object
     */
    public function update(TicketUpdateRequest $request, int $id): object
    {
        if($this->checkAdmin()) {
            $ticket = $this->getTicket($id);
            if($ticket->status_id !== $request->get('status_id')) {
                $new_status = Status::where('id',$request->get('status_id'))->first();
                $this->sendNotification($ticket, $new_status);
            }
            $ticket->status_id = $request->get('status_id');
            $ticket->category_id = $request->get('category_id');
            $ticket->save();
            return redirect()->back();
        }
        abort(401);
    }


    /**
     * @param int $id
     * @return object
     */
    public function destroy(int $id): object
    {
        if($this->checkAdmin()) {
            $ticket = $this->getTicket($id);
            $ticket->destroy();
            return redirect('ticket.index');
        }
    }

    /**
     * @return bool
     */
    private function checkAdmin(): bool
    {
        if(auth()->user()->hasRole(['Super Admin', 'Admin'])) {
            return true;
        }
        return false;
    }

    /**
     * @param int $id
     * @return bool
     */
    private function checkOwner(int $id): bool
    {
        if(Ticket::where('id', $id)->where('user_id', Auth()->id())->count() == 1) {
            return true;
        }
        return false;
    }

    /**
     * @param int $id
     * @return Ticket
     */
    private function getTicket(int $id): Ticket
    {
        return Ticket::where('id', $id)->firstOrFail();
    }

    /**
     * @param Ticket $ticket
     * @param Status $status
     */
    private function sendNotification(Ticket $ticket, Status $status): void
    {
        $ticket->user()->notify(new StatusChangeDefault($status));
    }
}
