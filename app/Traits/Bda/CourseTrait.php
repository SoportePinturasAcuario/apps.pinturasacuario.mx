<?php 
namespace Apps\Traits\Bda;

// Models
use Apps\Models\Bda\Collaborator;
use Apps\Models\Bda\Course;
use Illuminate\Support\Facades\DB;

trait CourseTrait {
    public function getCourse($course_id) {
        $course = Course::with([
            'image:id,fileable_id,fileable_type,path',
            'files:id,fileable_id,fileable_type,name',
            'modules' => function($query) {
                $query->orderBy('index', 'ASC');
            },
            'modules.resources' => function($query) {
                $query->orderBy('index', 'ASC');
            },
            'modules.resources.file:id,fileable_id,fileable_type',
            'modules.resources.files:id,fileable_id,fileable_type',
        ])->findOrFail($course_id);

        $course->image->imageGenerateUrl();

        $collaborator_course = DB::connection('bda')->table('collaborator_course')
        ->select('collaborator_id')
        ->where('course_id', $course->id)
        ->get()
        ->toArray();

        $course->collaborators = Collaborator::whereIn('id', array_column($collaborator_course, 'collaborator_id'))
        ->select(['id', 'name', 'sucursal_id', 'departamento_id'])
        ->with([
            'department' => function($query) {
                $query->select('id', 'nombre');
            },
            'branchoffice' => function($query) {
                $query->select('id', 'name');
            }
        ])->get();


        $files = $course->files->slice(1)->values();

        $course = collect($course);

        $course->put('files', $files);

        return $course;
    }
}