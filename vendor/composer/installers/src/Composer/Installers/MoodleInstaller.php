<?php

namespace Composer\Installers;

class MoodleInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array(
        'mod'                => 'mod/{$name}/',
        'admin_report'       => 'admin/report/{$name}/',
        'atto'               => 'lib/editor/atto/plugins/{$name}/',
        'tool'               => 'admin/tool/{$name}/',
        'assignment'         => 'mod/assignment/type/{$name}/',
        'assignsubmission'   => 'mod/assign/submission/{$name}/',
        'assignfeedback'     => 'mod/assign/feedback/{$name}/',
        'antivirus'          => 'lib/antivirus/{$name}/',
        'auth'               => 'auth/{$name}/',
        'availability'       => 'availability/condition/{$name}/',
        'block'              => 'blocks/{$name}/',
        'booktool'           => 'mod/book/tool/{$name}/',
        'cachestore'         => 'cache/stores/{$name}/',
        'cachelock'          => 'cache/locks/{$name}/',
        'calendartype'       => 'calendar/type/{$name}/',
<<<<<<< HEAD
        'communication'      => 'communication/provider/{$name}/',
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        'customfield'        => 'customfield/field/{$name}/',
        'fileconverter'      => 'files/converter/{$name}/',
        'format'             => 'course/format/{$name}/',
        'coursereport'       => 'course/report/{$name}/',
        'contenttype'        => 'contentbank/contenttype/{$name}/',
        'customcertelement'  => 'mod/customcert/element/{$name}/',
        'datafield'          => 'mod/data/field/{$name}/',
        'dataformat'         => 'dataformat/{$name}/',
        'datapreset'         => 'mod/data/preset/{$name}/',
        'editor'             => 'lib/editor/{$name}/',
        'enrol'              => 'enrol/{$name}/',
        'filter'             => 'filter/{$name}/',
<<<<<<< HEAD
        'forumreport'        => 'mod/forum/report/{$name}/',
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        'gradeexport'        => 'grade/export/{$name}/',
        'gradeimport'        => 'grade/import/{$name}/',
        'gradereport'        => 'grade/report/{$name}/',
        'gradingform'        => 'grade/grading/form/{$name}/',
<<<<<<< HEAD
        'h5plib'             => 'h5p/h5plib/{$name}/',
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        'local'              => 'local/{$name}/',
        'logstore'           => 'admin/tool/log/store/{$name}/',
        'ltisource'          => 'mod/lti/source/{$name}/',
        'ltiservice'         => 'mod/lti/service/{$name}/',
        'media'              => 'media/player/{$name}/',
        'message'            => 'message/output/{$name}/',
<<<<<<< HEAD
        'mlbackend'          => 'lib/mlbackend/{$name}/',
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        'mnetservice'        => 'mnet/service/{$name}/',
        'paygw'              => 'payment/gateway/{$name}/',
        'plagiarism'         => 'plagiarism/{$name}/',
        'portfolio'          => 'portfolio/{$name}/',
<<<<<<< HEAD
        'qbank'              => 'question/bank/{$name}/',
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        'qbehaviour'         => 'question/behaviour/{$name}/',
        'qformat'            => 'question/format/{$name}/',
        'qtype'              => 'question/type/{$name}/',
        'quizaccess'         => 'mod/quiz/accessrule/{$name}/',
        'quiz'               => 'mod/quiz/report/{$name}/',
        'report'             => 'report/{$name}/',
        'repository'         => 'repository/{$name}/',
        'scormreport'        => 'mod/scorm/report/{$name}/',
        'search'             => 'search/engine/{$name}/',
        'theme'              => 'theme/{$name}/',
<<<<<<< HEAD
        'tiny'               => 'lib/editor/tiny/plugins/{$name}/',
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        'tinymce'            => 'lib/editor/tinymce/plugins/{$name}/',
        'profilefield'       => 'user/profile/field/{$name}/',
        'webservice'         => 'webservice/{$name}/',
        'workshopallocation' => 'mod/workshop/allocation/{$name}/',
        'workshopeval'       => 'mod/workshop/eval/{$name}/',
        'workshopform'       => 'mod/workshop/form/{$name}/'
    );
}
