<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse\DataBuilder;
use App\Http\Requests\UserRoleRequest;
use App\Models\User;
use App\Models\UserRole;
use DB;
use Exception;
use Throwable;

class UserRoleController extends Controller
{

    /**
     * @return DataBuilder
     */
    public function roleList(): DataBuilder
    {
        $userRoles = UserRole::query()
            ->get();

        return $this->api->data([$userRoles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRoleRequest $request
     * @return DataBuilder
     * @throws Throwable
     */
    public function store(UserRoleRequest $request): DataBuilder
    {
        try {
            $response = UserRole::query()
                ->create([
                    'type'        => $request->input('type'),
                    'permissions' => $request->input('permissions'),
                ]);

            return $this->isCreated($response);

        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return DataBuilder
     */
    public function show(string $id): DataBuilder
    {
        $user = UserRole::query()
            ->where('id', '=', $id)
            ->first();

        return $this->api->data([$user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRoleRequest $request
     * @param UserRole $userRole
     * @return DataBuilder
     * @throws Throwable
     */
    public function update(UserRoleRequest $request, UserRole $userRole): DataBuilder
    {
        try {
            $type        = $request->input('type');
            $permissions = $request->input('permissions');

            if ($this->checkExist('user_roles', $type, 'type', $userRole->id)) {
                return $this->responseMessageError('El type debe ser distinto');
            }

            $response = UserRole::query()
                ->where('id', '=', $userRole->id)
                ->update([
                    'type'        => $type,
                    'permissions' => $permissions,
                ]);

            return $this->isUpdated($response);

        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return DataBuilder
     * @throws Throwable
     */
    public function destroy(string $id): DataBuilder
    {
        try {
            DB::beginTransaction();

            User::query()
                ->where('role_id', $id)
                ->update([
                    'role_id' => $this->getUserRoleGuest()->id,
                ]);

            $response = UserRole::whereId($id)->delete();

            return $this->isUpdated($response);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseError($e);
        }
    }
}
