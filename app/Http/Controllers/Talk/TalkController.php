<?php

namespace App\Http\Controllers\Talk;

use App\Enums\Enum;
use App\Filters\Talk\TalkFilter;
use App\Http\Controllers\Controller;
use App\Models\Talk\Talk;
use App\Services\Talk\TalkService;
use Illuminate\Http\Request;

class TalkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(TalkFilter $filter, Request $request)
    {
        return $this->success(
            TalkService::instance()->talkList($filter, $this->getUser(), $request->input('num', 10))
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AppException
     */
    public function store(Request $request)
    {
        $this->valid($request, [
            'title' => 'required',
            'type' => 'required|in:'.Enum::IS.','.Enum::NO,
            'plan_over_time' => 'required',
            'ding_user_ids' => 'required|array'
        ]);
        return $this->success(
            TalkService::instance()->create($request->all(), $this->getUser())
        );
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Talk\Talk $talk
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return $this->success(
            TalkService::instance()->get($id, $this->getUser())
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Talk\Talk $talk
     * @return \Illuminate\Http\Response
     */
    public function edit(Talk $talk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AppException
     */
    public function update(Request $request, $id)
    {
        $this->valid($request, [
            'title' => 'required',
            'type' => 'required|in:'.Enum::IS.','.Enum::NO,
            'plan_over_time' => 'required',
            'ding_user_ids' => 'required|array'
        ]);
        return $this->success(
            TalkService::instance()->update($id, $request->all(), $this->getUser())
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AppException
     */
    public function destroy($id)
    {
        return $this->success(
            TalkService::instance()->delTalk($id, $this->getUser())
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AppException
     */
    public function comment(Request $request)
    {
        $this->valid($request, [
            'talk_id' => 'required',
            'content' => 'required',
        ]);
        return $this->success(
            TalkService::instance()->comment($request->talk_id, $request->all(), $this->getUser())
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AppException
     */
    public function delComment($id)
    {
        return $this->success(
            TalkService::instance()->delComment($id, $this->getUser())
        );
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AppException
     */
    public function getComment(Request $request, $id)
    {
        return $this->success(
            TalkService::instance()->getComment($id, $this->getUser(), $request->input('num', 10))
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AppException
     */
    public function stop($id)
    {
        return $this->success(
            TalkService::instance()->setOver($id, true)
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AppException
     */
    public function restart($id)
    {
        return $this->success(
            TalkService::instance()->setOver($id, false)
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function delCommentAll()
    {
        return $this->success(
            TalkService::instance()->delCommentAll($this->getUser())
        );
    }
}
