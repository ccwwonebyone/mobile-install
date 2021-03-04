<?php

namespace App\Http\Controllers\Install;

use App\Filters\Install\InstallFilter;
use App\Http\Controllers\Controller;
use App\Services\Install\InstallService;
use Illuminate\Http\Request;

class InstallController extends Controller
{
    /**
     * @param Request $request
     * @param InstallFilter $filter
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, InstallFilter $filter)
    {
        return $this->success(
            InstallService::instance()->listInstall($filter, $request->num, $this->getUser())
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->valid($request, [
            'address' => 'required',
            'install_date' => 'required',
            'account' => 'required'
        ]);
        return $this->success(
            InstallService::instance()->createInstall($request->all(), $this->getUser())
        );
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AppException
     */
    public function update($id, Request $request)
    {
        $this->valid($request, [
            'address' => 'required',
            'install_date' => 'required',
            'account' => 'required'
        ]);
        return $this->success(
            InstallService::instance()->updateInstall($id, $request->all(), $this->getUser())
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AppException
     */
    public function show($id)
    {
        return $this->success(
            InstallService::instance()->getInstallById($id, $this->getUser())
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AppException
     */
    public function destroy($id)
    {
        return $this->success(
            InstallService::instance()->deleteInstall($id, $this->getUser())
        );
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function installView()
    {
        return view('install');
    }

    public function installListView()
    {
        return view('install_list');
    }

    public function installDetail()
    {
        return view('install_detail');
    }
}
