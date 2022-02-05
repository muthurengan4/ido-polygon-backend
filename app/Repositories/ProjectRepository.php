<?php

namespace App\Repositories;

use App\Helpers\Helper;

use Log, Validator, Setting, Exception, DB;

use App\Models\User, App\Models\ProjectOwnerTransaction;

class ProjectRepository {

	/**
     * @method projects_list_response()
     *
     * @uses Format the follow user response
     *
     * @created vithya R
     * 
     * @updated vithya R
     *
     * @param object $request
     *
     * @return object $payment
     */

    public static function projects_list_response($projects, $request) {

        $projects = $projects->map(function ($project, $key) use ($request) {

                        $project->start_time_formatted = common_date($project->start_time, $request->timezone, 'd M Y H:i:s');

                        $project->end_time_formatted = common_date($project->start_time, $request->timezone, 'd M Y H:i:s');

                        $project_transaction = ProjectOwnerTransaction::where('user_id',  $request->id)->where('project_id',  $project->id)->first();

                        $project->is_paid = $project_transaction ? YES : NO;

                        return $project;
                    });

    	return $projects ?: emptyObject();

    }
    
    /**
     * @method projects_single_response()
     *
     * @uses Format the follow user response
     *
     * @created vithya R
     * 
     * @updated vithya R
     *
     * @param object $request
     *
     * @return object $payment
     */

    public static function projects_single_response($project, $request) {

        $project->start_time_formatted = common_date($project->start_time, $request->timezone, 'd M Y H:i:s');

        $project->end_time_formatted = common_date($project->end_time, $request->timezone, 'd M Y H:i:s');

        return $project ?: emptyObject();

    }

}
