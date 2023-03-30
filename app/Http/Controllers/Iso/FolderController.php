<?php
namespace Apps\Http\Controllers\Iso;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Apps\Http\Requests\Iso\FolderRequest;

class FolderController extends Controller{

    protected $storage_path = '/home2/pintura1/public_html/pdfviewer/STORAGE/';
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FolderRequest $request){

        $name = $request->get('name');

        $path = $request->get('path');

        $pathDir = $path . "/" .  $name;

        // Validar si existe un directrio con el mismo nombre
        if (Storage::disk('iso')->exists($pathDir)) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['Ya existe un directorio con este nombre.']
                ]
            ], 422);
        }

        Storage::disk('iso')->makeDirectory($pathDir, 7777, true);

        Storage::disk('iso')->put($pathDir . '/config.json', json_encode($request->get('config')));

        return response()->json([
            'data' => [
                'path' => $pathDir,
                'name' => $name,
                'type' => 'directory',
                'config' => $request->get('config'),
                'parent' => $this->directoryEncode($request->get('path')),
            ]
        ]);   
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FolderRequest $request){
        $path = $request->get('path');

        $name = $request->get('name');

        $orignalName = explode('/', $path);
        $orignalName = end($orignalName);

        Storage::disk('iso')->put($path . "/config.json", json_encode($request->get('config')));


        if ($name != $orignalName) {
            // Creamos el nuevo path
            $pathExplode = explode('/', $path);
            $pathExplode[count($pathExplode) - 1] = $name;
            $pathDir = implode('/', $pathExplode);

            // Validar si existe un directrio con el mismo nombre
            if (Storage::disk('iso')->exists($pathDir)) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => [
                        'name' => ['Ya existe un directorio con este nombre.']
                    ]
                ], 422);
            }        

            rename($this->storage_path . $path, $this->storage_path . $pathDir);

            $path = $pathDir;
        }

        return response()->json([
            'data' => [
                'path' => $path,
                'parent' => $request->get('parent'),
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {

        Storage::disk('iso')->deleteDirectory($request->get('path'));

        return response()->json([
            'data' => $request->all(),
        ]);         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function open(Request $request){
        $path = $request->get('path');
        $path = $path === '/';
        $path = $request->get('path');

        $directory = collect(
            array_merge(
                $this->directoryEncode($request->get('path')),
                ['parent' => $this->directoryEncode(dirname($path))]
            )
        );
        
        $content = collect([]);

        foreach (Storage::disk('iso')->directories($path) as $key => $dirPath) {
            $content->push(array_merge(
                $this->directoryEncode($dirPath),
                ['parent' => $this->directoryEncode($path)]
            ));
        }

        foreach (Storage::disk('iso')->files($path) as $key => $filePath) {
            if ($this->extension($filePath) != 'json') {
                $content->push(array_merge(
                    $this->fileEncode($filePath),
                    ['parent' => $this->directoryEncode($path)]
                ));        
            }
        }

        $directory->put('content', $content);
        
        return response()->json([
            'data' => $directory,
        ]);
    }

    private function directoryEncode($path){
        $path = empty($path) ? '' : $path;
        
        $name = explode('/', $path);
        $name = end($name);

        $config = null;

        if (Storage::disk('iso')->exists($path . '/config.json')) {
            $config = json_decode(Storage::disk('iso')->get($path . '/config.json'));
        }

        return [
            'path' => $path,
            'name' => $name,
            'config' => $config,
            'type' => 'directory',
        ];
    }

    private function fileEncode($path){
        $file = [];

        $name = explode('/', $path);
        $name = end($name);

        $extension = $this->extension($name);

        $url = "https://pinturasacuario.mx/pdfviewer/STORAGE/" . $path;

        if (in_array($extension, ['doc','docx','docm','dotx','dotm','pptx','pptm','ppt','csv','xls','xlsx','xlsm','xlsb','xltx'])) {
            $url = "https://view.officeapps.live.com/op/view.aspx?src=$url";
        }

        if ($extension === 'pdf') {
            $url = "https://pinturasacuario.mx/pdfviewer/index.html?file=STORAGE/" . $path; 
        }

        return [
            'url' => $url,
            'path' => $path,
            'type' => 'file',
            'name' => $name,
            'extension' => $this->extension($name),
        ];
    }

    private function extension($name){
        $extension = explode('.', $name);
        return strtolower(end($extension));
    }


    public function fileGet(Request $request){

        $this->validate($request, [
            'path' => 'required',
        ]);

        return Storage::disk('iso')->response($request->get('path'));
    } 
}

