<?php

namespace App\Http\Controllers\api;

use App\Helpers\ApiResponse\DataBuilder;
use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;
use App\Models\Menu;
use App\Models\UserRole;
use Exception;
use Illuminate\Http\Request;
use Throwable;

class MenuController extends Controller
{
    /**
     * @param Request $request
     * @return DataBuilder
     */
    public function customer(Request $request): DataBuilder
    {
        return $this->getTypeMenu(Constants::CUSTOMER, $request);
    }

    /**
     * @param Request $request
     * @return DataBuilder
     */
    public function admin(Request $request): DataBuilder
    {
        return $this->getTypeMenu(Constants::ADMIN, $request);
    }

    /**
     * @param $type
     * @param Request $request
     * @return DataBuilder
     */
    public function getTypeMenu($type, Request $request): DataBuilder
    {
        $typeId  = UserRole::query()->whereType($type)->first()->id;
        $perPage = $request->query('per_page', 5);

        return $this->api->data(
            Menu::query()
                ->with(['subMenu'])
                ->whereTypeId($typeId)
                ->paginate($perPage)
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param MenuRequest $request
     * @return DataBuilder
     * @throws Throwable
     */
    public function store(MenuRequest $request): DataBuilder
    {
        try {
            $response = Menu::query()
                ->create([
                    'title'   => $request->input('title'),
                    'type_id' => $request->input('type_id'),
                ]);

            return $this->isUpdated($response);

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
        $menu = Menu::query()
            ->where('id', '=', $id)
            ->first();

        return $this->api->data([$menu]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string $id
     * @return DataBuilder
     * @throws Throwable
     */
    public function update(Request $request, string $id): DataBuilder
    {
        try {
            $response = Menu::query()
                ->where('id', '=', $id)
                ->update([
                    'title'   => $request->input('title'),
                    'type_id' => $request->input('type_id'),
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
            $response = Menu::query()->whereId($id)->delete();
            return $this->isUpdated($response);

        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }
}
