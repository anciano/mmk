<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Models\Busi\Channel;
use App\Models\Busi\ChannelGroup;

class ChannelController extends AdminController
{
	public function newEntity(array $attributes = [])
	{
		// TODO: Implement newEntity() method.
		return new Channel($attributes);
	}

	/**
	* Display a listing of the resource.
	*
	* @return  \Illuminate\Http\Response
	*/
	public function index()
	{
		//
		$all = ChannelGroup::all();
		$groups = $all->map(function ($item){
			return ['label' => $item->fname, 'value' => $item->id];
		});
		$groups[]=['label' => '无', 'value' => 0];
		
		return view('admin.channel.index',compact('groups'));
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return  \Illuminate\Http\Response
	*/
	public function create()
	{
		return view('admin.channel.create');
	}

	/**
	* Display the specified resource.
	*
	* @param    int  $id
	* @return  \Illuminate\Http\Response
	*/
	public function edit($id)
	{
		$entity = Channel::find($id);
		return view('admin.channel.edit', ['entity' => $entity]);
	}

	/**
	* Display the specified resource.
	*
	* @param    int  $id
	* @return  \Illuminate\Http\Response
	*/
	public function show($id)
	{
		//
	}

	/**
	 * @param  Request $request
	 * @param  array $searchCols
	 * @param array $with
	 * @param null $conditionCall
	 * @param bool $all_columns
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function pagination(Request $request, $searchCols = [], $with = [], $conditionCall = null, $all_columns = false){
		$searchCols = ["fdocument_status","fname","fnumber","fremark"];
        $data = $request->all();

        return parent::pagination($request, $searchCols,$with,function ($queryBuilder) use ($data) {
            if (!empty($data['nodeid'])) {
                $channelGroup = ChannelGroup::find($data['nodeid']);
                $ids = $channelGroup->getChildrenIds($data['nodeid']);
                $queryBuilder->whereIn('fgroup_id', $ids);
            }
        });
	}



}
