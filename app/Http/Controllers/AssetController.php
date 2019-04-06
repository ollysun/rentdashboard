<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Asset;
use App\Http\Requests\AssetRequest;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function index(Asset $model)
    {
        return view('assets.index', ['assets' => $model->paginate(15)]);
    }

    public function create()
    {
        return view('assets.create');
    }

    /**
     * Store a newly created user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AssetRequest $request, Asset $model)
    {

        $cover = $request->file('assetimg');
        $extension = $cover->getClientOriginalExtension();
        Storage::disk('public')->put($cover->getFilename().'.'.$extension,  File::get($cover));


        $asset = new Asset();
        $asset->assetType = $request->assetType;
        $asset->address = $request->address;
        $asset->summary = $request->summary;
        $asset->state = $request->state;
        $asset->mime = $cover->getClientMimeType();
        $asset->original_filename = $cover->getClientOriginalName();
        $asset->filename = $cover->getFilename().'.'.$extension;
        $asset->save();
        return redirect()->route('asset.index')
            ->with('success','Asset added successfully...');
        //return redirect()->route('assets.index')->withStatus(__('User successfully created.'));
    }

}
