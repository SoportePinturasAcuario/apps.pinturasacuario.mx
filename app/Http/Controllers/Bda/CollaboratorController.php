<?php
namespace Apps\Http\Controllers\Bda;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

// Models
use Apps\Models\Bda\Course;
use Apps\Models\Bda\Collaborator;

// Traits
use Apps\Traits\Bda\CourseTrait;

class CollaboratorController extends Controller
{
    use CourseTrait;

    public function course_index($collaborator_id)
    {
        $courses = $this->available_courses($collaborator_id);

        return response()->json([
            'data' => $courses,
        ]);       
    }

    public function course_show($collaborator_id, $course_id)
    {
        $courses = $this->available_courses($collaborator_id);

        $course = $courses->firstWhere('id', $course_id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Error: recurso no encontrado.',
                'errors' => [
                    'course' => ['Recurso no encontrado.'],
                ]
            ], 404);
        }

        return response()->json([
            'data' => $course,
        ]);
    }


    private function available_courses($collaborator_id)
    {
        $collaborator = Collaborator::select(['id'])
        ->with(['courses' => function($q) {
            $q->with(['image:id,fileable_id,path'])->where([
                ['public_access', false],
                ['status_id', 2]
            ]);
        }])->findOrFail($collaborator_id);

        $public_access_courses = Course::where([
            ['public_access', true],
            ['status_id', 2]
        ])
        ->with(['image:id,fileable_id,path'])
        ->get();

        $courses = $public_access_courses->merge($collaborator->courses);

        $courses = $courses->map(function($course) {
            return $this->getCourse($course->id);
        });

        return $courses;
    }
}