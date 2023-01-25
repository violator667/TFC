<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketStoreRequest;
use App\Http\Requests\TicketUpdateRequest;
use App\Services\TicketService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class TicketController extends Controller
{
    private object $ticketService;

    public function __construct(TicketService $service)
    {
        $this->ticketService = $service;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     * @param null $status
     * @return View
     */
    public function index($status = null): View
    {
        return view('welcome', $this->ticketService->listTickets($status));
    }

    /**
     * Show the form for creating a new resource.
     * @return Factory|View
     */
    public function create(): Factory|View
    {
        return view('welcome');
    }

    /**
     * Store a newly created resource in storage.
     * @param TicketStoreRequest $request
     * @return Redirector
     */
    public function store(TicketStoreRequest $request): Redirector
    {
        return $this->ticketService->store($request);
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        return $this->ticketService->show($id);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        return $this->ticketService->edit($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TicketUpdateRequest $request, int $id): Response
    {
        return $this->ticketService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        return $this->ticketService->destroy($id);
    }
}
