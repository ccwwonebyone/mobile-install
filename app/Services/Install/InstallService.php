<?php


namespace App\Services\Install;


use App\Filters\QueryFilter;
use App\Models\Install\Equipment;
use App\Models\Install\Install;
use App\Models\User;
use App\Services\Service;

class InstallService extends Service
{
    /**
     * @param QueryFilter $filter
     * @param $num
     * @param User $user
     * @return array
     */
    public function listInstall(QueryFilter $filter, $num, User $user)
    {
        return Install::filter($filter)->where('user_id', $user->id)->paginate($num)->toArray();
    }

    /**
     * @param array $data
     * @param User $user
     * @return bool
     */
    public function createInstall(array $data, User $user)
    {
        $equipments = $data['equipments'] ?? [];
        $data['user_id'] = $user->id;
        $install = Install::query()->create($data);
        return $this->insertEquipments($equipments, $install->id, $user);
    }

    /**
     * @param $equipments
     * @param $installId
     * @param $user
     * @return bool
     */
    public function insertEquipments($equipments, $installId, $user)
    {
        Equipment::query()->where('install_id', $installId)->delete();
        return Equipment::query()->insert(array_map(function ($equipment) use ($installId, $user) {
            $equipment['install_id'] = $installId;
            $equipment['user_id'] = $user->id;
            unset($equipment['id']);
            return $equipment;
        }, $equipments));
    }

    /**
     * @param int $id
     * @param array $data
     * @param User $user
     * @return bool
     */
    public function updateInstall(int $id, array $data, User $user)
    {
        $install = $this->findInstallById($id);
        if ($install->user_id != $user->id) {
            $this->throwAppException('非法请求');
        }
        $install->update($data);
        return $this->insertEquipments($data['equipments'] ?? [], $id, $user);
    }

    /**
     * @param $id
     * @return Install
     * @throws \App\Exceptions\AppException
     */
    public function findInstallById($id)
    {
        if (!$install = Install::query()->find($id)) {
            $this->throwAppException('未找到话题');
        }
        return $install;
    }

    /**
     * @param $id
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @throws \App\Exceptions\AppException
     */
    public function getInstallById($id, User $user)
    {
        $install = Install::query()->with('equipments')->find($id);
        if ($install->user_id != $user->id) {
            $this->throwAppException('非法请求');
        }
        return $install;
    }

    /**
     * @param $id
     * @param User $user
     * @return bool|mixed|null
     * @throws \App\Exceptions\AppException
     */
    public function deleteInstall($id, User $user)
    {
        $install = $this->findInstallById($id);
        if ($install->user_id != $user->id) {
            $this->throwAppException('非法请求');
        }
        return $install->delete();
    }
}
