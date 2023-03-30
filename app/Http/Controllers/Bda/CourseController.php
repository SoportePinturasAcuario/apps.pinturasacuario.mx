<?php

namespace Apps\Http\Controllers\Bda;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

// Models
use Apps\Models\Bda\Collaborator;
use Apps\Models\Bda\Course;
use Apps\Models\Bda\Module;
use Apps\Models\Bda\Resource as ResourceModel;

// Request
use Apps\Http\Requests\Bda\CourseRequest;
use Apps\Http\Requests\Bda\Resource;

// Traits
use Apps\Traits\Bda\CourseTrait;

class CourseController extends Controller
{
    use CourseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::with(['image:id,fileable_id,path'])->get();

        $courses = $courses->map(function($course) {

            $course->image->imageGenerateUrl();

            return $course;
        });

        return response()->json([
            'data' => $courses,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseRequest $request)
    {
        $course = Course::create($request->all());

        $name = $course->id;

        $path = "cover/$name.jpg";

        Storage::disk('local')->put("storage/bda/$path", File::get($request->file('image')));

        $course->image()->create([
            'name' => "cover",
            'path' => $path,
        ]);

        return response()->json([
            'data' => $course,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Models\Bda\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show($course_id)
    {
        $course = $this->getCourse($course_id);

        return response()->json([
            'data' => $course,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Models\Bda\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(CourseRequest $request, $course_id)
    {
        $course = Course::findOrFail($course_id);

        $course->update(array_merge($request->all(), [
            'public_access' => $request->get('public_access') == 'true'
        ]));

        if ($request->file('image')) {
            Storage::disk('local')->put("storage/bda/cover/$course->id.jpg", File::get($request->file('image')));
        }

        return response()->json([
            'data' => $course,
        ]);

        return response()->json([
            'data' => $course,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Bda\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy($course_id)
    {
        // $course = Course::findOrFail($course_id);

        // return response()->json([
        //     'data' => $course->delete(),
        // ]);
    }

    // Content
    public function content_index(Request $request, $course_id)
    {
        $this->validate($request, [
            'modules' => 'required|array|min:0|max:40',
            'modules.*.id' => 'required|integer',
            'modules.*.index' => 'required|numeric',
            'modules.*.resources' => 'array|min:0|max:99',
            'modules.*.resources.*.id' => 'required|integer',
            'modules.*.resources.*.index' => 'required|numeric',
        ]);

        foreach ($request->get('modules') as $key => $module) {
            Module::find($module['id'])->update(['index' => $module['index']]);

            foreach ($module['resources'] as $key => $_resource) {
                $resource = ResourceModel::findOrFail($_resource['id'])->update(['index' => $_resource['index']]);
            }
        }

        return response()->json([
            'data' => [],
        ]);
    }

    // Status
    public function status_update(Request $request, $course_id)
    {

        $this->validate($request, [
            'status_id' => 'required|integer|in:1,2',
        ]);

        $course = Course::findOrFail($course_id);

        $course->status_id = $request->get('status_id');

        $course->save();

        return response()->json([
            'data' => $course,
        ]);
    }

    // Collaborator
    public function collaborator_sync(Request $request, $course_id)
    {
        $this->validate($request, [
            'collaborators' => 'array|min:0|max:500',
            'collaborators.*' => 'integer',
        ], [], [
            'collaborators' => '"colaboradores"',
        ]);

        $course = Course::findOrFail($course_id);

        $date = date('Y-m-d H:i:s');

        $toInsert = array_map(function($id) use($course, $date) {
            return [
                'collaborator_id' => $id,
                'course_id' => $course->id,
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }, $request->get('collaborators'));

        DB::connection('bda')
        ->table('collaborator_course')
        ->where('course_id', $course->id)
        ->delete();

        DB::connection('bda')
        ->table('collaborator_course')
        ->insert($toInsert);

        return response()->json([
            'data' => [],
        ]);
    }
}
