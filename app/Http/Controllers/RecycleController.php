<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Http\Requests\RecycleRequest;
use App\Models\Recycle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RecycleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recycles = Recycle::with('user')->latest()->paginate(2);

        return view('recycles.index', compact('recycles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('recycles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RecycleRequest $request)
    {
        $recycle = new Recycle($request->all());
        $recycle->user_id = $request->user()->id;
        $file = $request->file('image');
        $recycle->image = self::createFileName($file);

        // トランザクション開始
        DB::beginTransaction();
        try {
            // 登録
            $recycle->save();

            // 画像アップロード
            if (!Storage::putFileAs('images/recycles', $file, $recycle->image)) {
                // 例外を投げてロールバックさせる
                throw new \Exception('画像ファイルの保存に失敗しました。');
            }

            // トランザクション終了(成功)
            DB::commit();
        } catch (\Exception $e) {
            // トランザクション終了(失敗)
            DB::rollback();
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()
            ->route('recycles.show', $recycle)
            ->with('notice', '記事を登録しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $recycle = Recycle::with(['user'])->find($id);
        $comments = $recycle->comments()->latest()->get()->load(['user']);

        return view('recycles.show', compact('recycle', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $recycle = Recycle::find($id);

        return view('recycles.edit', compact('recycle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RecycleRequest $request, $id)
    {
        $recycle = Recycle::find($id);

        if ($request->user()->cannot('update', $recycle)) {
            return redirect()->route('recycles.show', $recycle)
                ->withErrors('自分の記事以外は更新できません');
        }

        $file = $request->file('image');
        if ($file) {
            $delete_file_path = $recycle->image_path;
            $recycle->image = self::createFileName($file);
        }
        $recycle->fill($request->all());

        // トランザクション開始
        DB::beginTransaction();
        try {
            // 更新
            $recycle->save();

            if ($file) {
                // 画像アップロード
                if (!Storage::putFileAs('images/recycles', $file, $recycle->image)) {
                    // 例外を投げてロールバックさせる
                    throw new \Exception('画像ファイルの保存に失敗しました。');
                }
                // 画像削除
                if (!Storage::delete($delete_file_path)) {
                    //アップロードした画像を削除する
                    Storage::delete($recycle->image_path);
                    throw new \Exception('画像ファイルの削除に失敗しました。');
                }
            }

            // トランザクション終了(成功)
            DB::commit();
        } catch (\Exception $e) {
            // トランザクション終了(失敗)
            DB::rollback();
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('recycles.show', $recycle)
            ->with('notice', '記事を更新しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $recycle = Recycle::find($id);

        // トランザクション開始
        DB::beginTransaction();
        try {
            $recycle->delete();

            // 画像削除
            if (!Storage::delete($recycle->image_path)) {
                // 例外を投げてロールバックさせる
                throw new \Exception('画像ファイルの削除に失敗しました。');
            }

            // トランザクション終了(成功)
            DB::commit();
        } catch (\Exception $e) {
            // トランザクション終了(失敗)
            DB::rollback();
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('recycles.index')
            ->with('notice', '記事を削除しました');
    }

    private static function createFileName($file)
    {
        return date('YmdHis') . '_' . $file->getClientOriginalName();
    }
}
